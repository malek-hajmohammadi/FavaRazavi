<?php
$Creator = $execution->workflow->myForm->getCreator(); //get creator's record
$UID = $Creator['uid'];

$db = MySQLAdapter::getInstance(); //connect to the db
$sql2 = "SELECT fname,lname FROM oa_users WHERE UserID=" . $UID;
$db->executeSelect($sql2);
$person = $db->fetchAssoc();

$name=$person['fname']+$person['lname'];
$date=$execution->workflow->myForm->getFieldValueByName('Field_12');
$subject="درخواست اصلاح تردد "+$name+" مورخه "+$date;
MySQLAdapter::getInstance()->execute("UPDATE oa_document SET Subject = '$subject', DocDesc='$subject' WHERE RowID = " . $execution->workflow->myForm->instanceID);



