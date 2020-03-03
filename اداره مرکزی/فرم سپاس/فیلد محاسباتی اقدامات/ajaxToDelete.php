<?php



$id = Request::getInstance()->varCleanFromInput('id');
$value = Request::getInstance()->varCleanFromInput('value');
$id = intval($id);
$value = intval($value);
if ($id == 0) {
    Response::getInstance()->response = 'شماره اقدام نامعتبر';
    return;
}

$acm = AccessControlManager::getInstance();
$rid = $acm->getRoleID();


$db = PDOAdapter::getInstance();
$res = $db->executeScalar("UPDATE `dm_datastoretable_1097` SET `Field_3` = '$value' WHERE `dm_datastoretable_1097`.`RowID` = $id");
if ($res && intval($docID) > 0) {
    Response::getInstance()->response = 'true';
    return;
}

Response::getInstance()->response = 'true';
