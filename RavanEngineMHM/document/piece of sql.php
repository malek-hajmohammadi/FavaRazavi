<?php

//select//
function template_pdo(){
    $db = WFPDOAdapter::getInstance();
    $sql="SELECT * FROM `dm_datastoretable_1098` WHERE `DocID`=:docId";
    $PDOParams=array(
        array('name'=>'docId','value'=>$docId,'type'=>PDO::PARAM_STR)
    );
    $db->executeSelect($sql,$PDOParams);

    $count = 0;
    $arrayList=[];
    while ($row = $db->fetchAssoc()) {
        $arrayList[$count]= $row['Field_2'];


        $count++;
    }

}

//insert//
$sql = "INSERT INTO dm_datastoretable_1115  (Field_0, Field_1,Field_2,Field_3)
VALUES (:userId,:dateSave,:answer,:dept)";

$db = PDOAdapter::getInstance();
//or
$db = WFPDOAdapter::getInstance();

$PDOParams = array(
    array('name' => 'userId', 'value' => $userId, 'type' => PDO::PARAM_STR),
    array('name' => 'dateSave', 'value' => "1399/3/3", 'type' => PDO::PARAM_STR),
    array('name' => 'answer', 'value' => $questionerList, 'type' => PDO::PARAM_STR),
    array('name' => 'dept', 'value' => $dep, 'type' => PDO::PARAM_STR)
);
$db->execute($sql,$PDOParams);


///delete///

private function deleteAllDataInAdvance(){
    $db = PDOAdapter::getInstance();
    $sql = "DELETE from dm_datastoretable_36 where MasterID=$this->docId";
    $db->execute($sql);
}

//update//
$db = WFPDOAdapter::getInstance();
$sql="UPDATE dm_datastoretable_58 as dm SET `Field_6`=0 WHERE DocID<>$docId AND `Field_4`=$hozeh  ";
$db->execute($sql);