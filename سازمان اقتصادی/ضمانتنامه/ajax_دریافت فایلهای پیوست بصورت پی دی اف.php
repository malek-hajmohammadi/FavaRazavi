<?php

$did = Request::getInstance()->varCleanFromInput('docID');

if(intval($did) == 0) {
    die('invalid document id');
}

$db = PDOAdapter::getInstance();
$PDOParams = array(
    array('name' => 'did', 'value' => $did, 'type' => PDO::PARAM_INT)
);
$sql = "SELECT oa_content.RealFileName,
              oa_content.RowID,
              oa_content.MimeType,
              Date(oa_content.CreateDate) AS CD,
              oa_content.EncryptedHeader,
              oa_content.SecID
        FROM oa_content
        WHERE (oa_content.DocReferID = :did) 
            AND (oa_content.MimeType like '%image/jpeg%' OR oa_content.RealFileName LIKE '%.jpg' OR oa_content.RealFileName LIKE '%.jpeg') and  (oa_content.contentState = 1)
		  ";
$db->executeSelect($sql, $PDOParams);

$res = array();
while ($row = $db->fetchAssoc())
{
    $res[] = $row;
};
//add by ebrahimi
$lid = Document::GetLetterID($did);

//add by amoozgar 880921
$regCode = Letter::GetRegCode($refer);
$index = 0;
//end add
$pdf = new tcpdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true);

$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);
foreach ($res as $row)
{
    //add by amoozgar 880921
    // filerecorder::recorder("name of file".$row['RealFileName']);
    $prefixArr = explode('.',$row['RealFileName'] );
    $prefix='';
    if (count($prefixArr)> 1)
        $prefix = '.'.$prefixArr[1];
    //change by ebrahimi
    if(strlen($regCode)) {
        $index++;
        $file_name = $regCode.'_'.$index.$prefix;//$row['RealFileName'];
    }
    else{
        $index++;
        $file_name = $index.$prefix;//$row['RealFileName'];
    }
    //end change
    //end add
    $cid = $row['RowID'];
    $type = $row['MimeType'];
    // add by mohammadzadeh 1394/6/7 bugID = mm_$$106_ , find mimetype for scaned files without mimetype for open in pdf
    if(strlen($type)==0){
        $fileNameArray = explode('.',$row['RealFileName']);
        $postfix = $fileNameArray[count($fileNameArray)-1];
        $fileMime = $db->executeScalar("SELECT Mime FROM `oa_file_types` WHERE `Postfix` LIKE :postfix", array(['name' => 'postfix', 'value' => $postfix, 'type' => PDO::PARAM_STR]));
        $PDOParams = array();
        $PDOParams[] = array('name' => 'fileMime', 'value' => $fileMime, 'type' => PDO::PARAM_STR);
        $PDOParams[] = array('name' => 'cid', 'value' => $cid, 'type' => PDO::PARAM_INT);
        $db->execute("UPDATE `oa_content` SET `MimeType` = :fileMime WHERE `oa_content`.`RowID` = :cid", $PDOParams);
        $type = $fileMime;
    }
    // end add
    $date = explode('-', $row['CD']);
    $now = Date::gregorian_to_jalali($date[0], $date[1], $date[2]);
    $sid= $row['SecID'];
    /*if(!intval($lid)){//doc is a message
        $sid= $row['SecID'];
    }else{
        $sid = SecUser::GetCurrUserSecID();
    }*/

    $path = SecUtils::GetStoragePath($sid, $now[0], $now[1]);

    $cont = $row['EncryptedHeader'];
    $cont = SecureContent::Decode($cont);

    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    $fileData = $cont.file_get_contents($path.$cid);

    $imgFileName=sys_get_temp_dir().uniqid('img_').'.'.$prefixArr[1];
    // for windows : $imgFileName=sys_get_temp_dir().uniqid('img_').'.'.$prefixArr[1];

    if(!file_put_contents($imgFileName,$fileData)){
        $imgFileName=sys_get_temp_dir().'/'.uniqid('img_').'.'.$prefixArr[1];
        file_put_contents($imgFileName,$fileData);
    }
    // set image 1
    $pdf->AddPage();
    $pageWidth=round($pdf->GetPageWidth());
    //$pageHeight=round($pdf->getPageHeight());
    $pdf->Image($imgFileName, 0, 0, $pageWidth, 0);
    //amoozgar_$$392 // add unlink
    unlink($imgFileName);
}
$pdf->Output(uniqid('export_').'.pdf', 'D');
