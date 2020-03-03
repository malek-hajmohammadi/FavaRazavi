

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
$permittedRoles = array(4351,8920,1508,11904,11905,11895);
if (!in_array($rid, $permittedRoles)) {
    Response::getInstance()->response = 'درخواست غير مجاز';
    return;
}
$db = PDOAdapter::getInstance();
$res = $db->executeScalar("UPDATE `dm_datastoretable_1042` SET `Field_6` = '$value' WHERE `dm_datastoretable_1042`.`RowID` = $id");
if ($res && intval($docID) > 0) {
    Response::getInstance()->response = 'true';
    return;
}

Response::getInstance()->response = 'true';

