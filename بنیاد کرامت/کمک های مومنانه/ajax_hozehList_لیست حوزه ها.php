<?php
$db = WFPDOAdapter::getInstance();
$parent = Request::getInstance()->varCleanFromInput('parent');
$pathsql = ' ';
if (!empty($parent)) {
    $pathsql = ' and path LIKE "%/' . $parent . '/%" ';
}
$sql = 'SELECT RowID,Name  FROM oa_depts_roles where RowType =2 and IsEnable=1';
$sql .= $pathsql;
$db->executeSelect($sql);
while ($row = $db->fetchAssoc()) {
    if (!empty($row['RowID'])) $res[] = array('id'=>$row['RowID'],'text'=> $row['Name']);
}
Response::getInstance()->response = $res;
