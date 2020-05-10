<?php
//$docId = "12255671";
$fieldName="Field_9";
$formId=1098;
$returnString = "";

if (!Request::getInstance()->varCleanFromInput('docId')) {
    $returnString="پارامتر docId ورودی ندارد";
    Response::getInstance()->response = $returnString;
    return;
}

$docId=Request::getInstance()->varCleanFromInput('docId');


$sql = "SELECT 
            oa_content.IsScan,
            oa_content.RealFileName, 
            oa_content.RowID, 
            oa_content.MimeType, 
            Date(oa_content.CreateDate) AS CD, 
            oa_content.EncryptedHeader,
            oa_content.ContentType, 
            oa_content.PhysicalFileName, 
            oa_content.CurrentAddress,
            oa_content.DocReferID, 
            oa_content.SecID 
        FROM oa_content
        INNER JOIN dm_structure_field on(dm_structure_field.StructID = $formId and oa_content.PersistFileName = dm_structure_field.RowID)
        WHERE 
            oa_content.DocReferID = $docId 
            AND oa_content.contentState = 1
            and dm_structure_field.FieldName = '$fieldName' 
            and dm_structure_field.IsEnable = 1";



$db = MySQLAdapter::getInstance();
$db->executeSelect($sql);
if (!$row = $db->fetchAssoc()) {
    $res = '<span style="color:red">فايل  پيوست يافت نشد</span>';
    $execution->workflow->myForm->setFieldValueByName('Field_11', $res);
    return false;
}
$file_name = $row['RealFileName'];
$cid = $row['RowID'];
$type = $row['MimeType'];
$isScan = $row['IsScan'];
$referDocId = $row['DocReferID'];
/*
Response::getInstance()->doGenerate = false;
*/

$date = explode('-', $row['CD']);
$now = Date::gregorian_to_jalali($date[0], $date[1], $date[2]);



$path = SecUtils::GetStoragePath($row['SecID'], $now[0], $now[1]);

$cont = $row['EncryptedHeader'];
$cont = SecureContent::Decode($cont);

//$detailID = 875;

if (file_exists($path . $cid)) {

    $content = $cont . file_get_contents($path . $cid);
    $content = base64_encode($content);

    $client = new SoapClient('http://10.10.10.113/WSExcelTools/default.asmx?wsdl');
    $html = '';
    $param = array('strBase64FileData' => $content, 'extension' => 'xlsx');

    $resp1 = $client->ExcelToJson($param);

    $lines = $resp1->ExcelToJsonResult;
    $lines = json_decode($lines, true);

    $outputList = array();
    $i=0;

    foreach ($lines as $line) {
        // Skip the empty line
        if (empty($line)) continue;

        $outputList[$i]['nationalCode']=$line['field_0'];
        $outputList[$i]['birthDate']=$line['field_1'];
        $outputList[$i]['name']=$line['field_2'];
        $outputList[$i]['family']=$line['field_3'];
        $i++;

    }

    Response::getInstance()->response = $outputList;
    return;

}

Response::getInstance()->response="0";
