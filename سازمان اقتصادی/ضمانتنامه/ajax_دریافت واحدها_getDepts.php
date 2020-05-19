<?php
$db = MySQLAdapter::getInstance();
$parent = Request::getInstance()->varCleanFromInput('parent');
$pathsql = ' ';
if (!empty($parent)) {
    $pathsql = ' and path LIKE "%/' . $parent . '/%" ';
}
$sql = 'SELECT RowID,Name  FROM oa_depts_roles where RowType =1 and IsEnable=1';
$sql .= $pathsql;
$db->executeSelect($sql);
while ($row = $db->fetchAssoc()) {
    if (!empty($row['RowID']))
        $res[] = array($row['RowID'], $row['Name']);
}
Response::getInstance()->response = $res;
