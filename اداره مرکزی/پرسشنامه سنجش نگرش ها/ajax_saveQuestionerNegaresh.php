<?php

$questionerList = Request::getInstance()->varCleanFromInput("questionerList");
$userId=Request::getInstance()->varCleanFromInput("userId");
$dep=Request::getInstance()->varCleanFromInput("dep");

if (!$questionerList ) {
    Response::getInstance()->response = "پاسخنامه به عنوان پارامتر وارد نشده است";
    return false;
}
if (!$userId ) {
    Response::getInstance()->response = "پارامتر نام کاربردی وجود ندارد";
    return false;
}
if (!$dep ) {
    Response::getInstance()->response = "پارامتر واحد سازمانی وجود ندارد";
    return false;
}



$sql = "INSERT INTO dm_datastoretable_1115  (Field_0, Field_1,Field_2,Field_3)
VALUES (:userId,:dateSave,:answer,:dept)";
$db = PDOAdapter::getInstance();
$PDOParams = array(
    array('name' => 'userId', 'value' => $userId, 'type' => PDO::PARAM_STR),
    array('name' => 'dateSave', 'value' => "1399/3/3", 'type' => PDO::PARAM_STR),
    array('name' => 'answer', 'value' => $questionerList, 'type' => PDO::PARAM_STR),
    array('name' => 'dept', 'value' => $dep, 'type' => PDO::PARAM_STR)
);
$db->executeSelect($sql,$PDOParams);
