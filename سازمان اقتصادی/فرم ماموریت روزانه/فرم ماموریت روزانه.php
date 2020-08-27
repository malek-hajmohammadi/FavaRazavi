<?php
$newWFID = 563;
$oldWFID = 628;
$db = PDOAdapter::getInstance();
$sql = "UPDATE `wf_execution` SET `workflow_id` = :newWFID WHERE `wf_execution`.`workflow_id` = :oldWFID";
$PDOParams = array(
    array('name' => 'newWFID', 'value' => $newWFID, 'type' => PDO::PARAM_INT),
    array('name' => 'oldWFID', 'value' => $oldWFID, 'type' => PDO::PARAM_INT)
);
$db->execute($sql, $PDOParams);