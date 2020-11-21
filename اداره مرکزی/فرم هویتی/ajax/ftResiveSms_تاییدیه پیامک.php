<?php



$codeUser = Request::getInstance()->varCleanFromInput('codeUser');
$mobile = Request::getInstance()->varCleanFromInput('mobile');
$docid= Request::getInstance()->varCleanFromInput('fn');


$db = MySQLAdapter::getInstance();


$sql = "SELECT Field_13 FROM `dm_datastoretable_964` WHERE docid = $docid";
$f13 = $db->executeScalar($sql);
$f13= explode("-",$f13);

$codeTaid=(int)$f13[1];
if ($f13[0] != $mobile)
    $result = false;
else if((int)$codeUser==(int)$codeTaid) {
    $sql = "UPDATE `dm_datastoretable_964` SET Field_12='$codeUser' WHERE docid=$docid";
    $db->execute($sql);
    $result = true;
}
else
    $result=false;


Response::getInstance()->response = $result;
