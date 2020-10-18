<?php




$db = MySQLAdapter::getInstance();

$wfid = $db->executeScalar("SELECT `workflow_id` FROM `wf_workflow` WHERE `workflow_enable` = 1 AND `workflow_formtypeid` = 790");


Response::getInstance()->response = $wfid;

