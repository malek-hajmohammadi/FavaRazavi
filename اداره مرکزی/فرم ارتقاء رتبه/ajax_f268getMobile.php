<?php


//-----------------------parameters----------------------//

$userId = 0;

if (Request::getInstance()->varCleanFromInput('userId'))
    $userId = Request::getInstance()->varCleanFromInput('userId');


//------------------end  parameters-------------------------//
$mobile = 0;
$db = MySQLAdapter::getInstance();
$sql = "SELECT mobile FROM `oa_users` WHERE UserID=$userId";

$db->executeSelect($sql);

if ($info = $db->fetchAssoc()) {

    $mobile = $info['mobile'];

}
Response::getInstance()->response = $mobile;

return;

