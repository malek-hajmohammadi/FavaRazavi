<?php
$db = WFPDOAdapter::getInstance();
$flag = Request::getInstance()->varCleanFromInput('flag');
if(intval($flag) == 0){
Response::getInstance()->response = 'invalid flag';
return;
}
$sql = "update dm_datastoretable_26 set Field_2 = :flag where RowID = 1";
$PDOParams[['name' => 'flag', 'value' =>$flag, 'type' => PDO::PARAM_INT]];
$res = $db->execute($sql, $PDOParams);
Response::getInstance()->response = $res;
