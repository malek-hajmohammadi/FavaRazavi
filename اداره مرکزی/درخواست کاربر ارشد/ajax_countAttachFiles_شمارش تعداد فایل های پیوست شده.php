<?php
if (Request::getInstance()->varCleanFromInput('docId'))
    $docId = Request::getInstance()->varCleanFromInput('docId');
else {

    Response::getInstance()->response = "There is no specefic input";
    return;
}

$db = PDOAdapter::getInstance();
$sql="SELECT count(oa_content.RowID) FROM oa_content where oa_content.DocReferID = :docId and oa_content.ContentType = 3 AND oa_content.contentState = 1";
$PDOParams = array(
    array('name' => 'docId', 'value' => $docId, 'type' => PDO::PARAM_INT)
);
$countAttachFilesInForm=$db->executeScalar($sql, $PDOParams);


$sql="SELECT count(oa_content.RowID) FROM oa_content 
INNER JOIN oa_doc_refer on (oa_doc_refer.RowID = oa_content.DocReferID)
where oa_doc_refer.DocID = :docId and oa_content.ContentType = 4 AND oa_content.contentState = 1";

$PDOParams = array(
    array('name' => 'docId', 'value' => $docId, 'type' => PDO::PARAM_INT)
);
$countAttachFilesInReferForm=$db->executeScalar($sql, $PDOParams);

Response::getInstance()->response = $countAttachFilesInForm+$countAttachFilesInReferForm;
