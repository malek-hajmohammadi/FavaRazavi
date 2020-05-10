<?php


////////////////////
$docID = $execution->workflow->myForm->instanceID;

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
        INNER JOIN dm_structure_field on(dm_structure_field.StructID = 873 and oa_content.PersistFileName = dm_structure_field.RowID)
        WHERE 
            oa_content.DocReferID = $docID 
            AND oa_content.contentState = 1
            and dm_structure_field.FieldName = 'field_6' 
            and dm_structure_field.IsEnable = 1";
$db = MySQLAdapter::getInstance();
$db->executeSelect($sql);

if (!$row = $db->fetchAssoc()) {
    $res = '<span style="color:red">فايل  پيوست يافت نشد</span>';
    $execution->workflow->myForm->setFieldValueByName('Field_11', $res);
    return false;
}

$cid = $row['RowID'];

Response::getInstance()->doGenerate = false;

$date = explode('-', $row['CD']);
$now = Date::gregorian_to_jalali($date[0], $date[1], $date[2]);


$path = SecUtils::GetStoragePath($row['SecID'], $now[0], $now[1]);

$cont = $row['EncryptedHeader'];
$cont = SecureContent::Decode($cont);
$detailID = 875;





/// //////////////



$client = new SoapClient('http://10.10.10.113/WSExcelTools/default.asmx?wsdl');

$content = $cont . file_get_contents($path . $cid);
$content = base64_encode($content);

$param = array('strBase64FileData' => $content, 'extension' => 'xlsx');
//$cont ,   چی هست؟
//$path
//$cid
