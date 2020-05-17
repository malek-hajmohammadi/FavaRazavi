<?php

$questionerList = Request::getInstance()->varCleanFromInput("questionerList");
$userId=Request::getInstance()->varCleanFromInput("userId");
if (!$questionerList ) {
    Response::getInstance()->response = "پاسخنامه به عنوان پارامتر وارد نشده است";
    return false;
}
if (!$userId ) {
    Response::getInstance()->response = "پارامتر نام کاربردی وجود ندارد";
    return false;
}


/*
$sql = "INSERT INTO oa_group_members(RoleID, UserGroupID) VALUES $group_values";
$db = PDOAdapter::getInstance();
$PDOParams = array(
    array('name' => 'docId', 'value' => $docId, 'type' => PDO::PARAM_STR)
);
$db->executeSelect($sql,$PDOParams);
*/