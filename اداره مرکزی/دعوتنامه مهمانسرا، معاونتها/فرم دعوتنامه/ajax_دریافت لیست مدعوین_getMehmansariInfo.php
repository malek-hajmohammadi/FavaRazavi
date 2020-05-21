<?php
/*
$docId = 12010559;//تایید شده//
$docId = 22287812;//وجود ندارد//
$docId = 12287004; //مهمان دارد//*/

$jsonReturn = array();
$jsonReturn['isFound'] = true;
$jsonReturn['status'] = "verified";
$jsonReturn['quotaType'] = "تشریفات";
$jsonReturn['requestedBy'] = "عباسی";
$jsonReturn['verifiedBy'] = "امینی گازار"; //نمی دونم کدوم فیلد منظورش هست//احتمالا نفر آخری که تایید می کند و توضیح می نویسد//
$jsonReturn['verifiedMeal'] = "1"; //صبحانه نهار شام//
$jsonReturn['verifiedDate'] = "1399/12/1";
$jsonReturn['count'] = 2;
$jsonReturn['verifyNotes'] = "توضیحات تایید کننده";
$jsonReturn['persons'] = array();

$jsonReturn['persons'][0] = array();
$jsonReturn['persons'][0]['nationalCode'] = "123345456";
$jsonReturn['persons'][0]['birthDate'] = "1370/01/01";
$jsonReturn['persons'][0]['mobile'] = "09151234567";
$jsonReturn['persons'][0]['verifyBySabt'] = true;

$jsonReturn['persons'][1] = array();
$jsonReturn['persons'][1]['nationalCode'] = "154652456";
$jsonReturn['persons'][1]['birthDate'] = "1390/01/01";
$jsonReturn['persons'][1]['mobile'] = "24244242";
$jsonReturn['persons'][1]['verifyBySabt'] = false;


if (!Request::getInstance()->varCleanFromInput('docId')) {
    $returnString="پارامتر docId ورودی ندارد";
    Response::getInstance()->response = $returnString;
    return;
}

$docId=Request::getInstance()->varCleanFromInput('docId');

$sql = "SELECT * FROM `dm_datastoretable_1098` WHERE `DocID`=:docId";
$db = PDOAdapter::getInstance();
$PDOParams = array(
    array('name' => 'docId', 'value' => $docId, 'type' => PDO::PARAM_STR)
);
$db->executeSelect($sql,$PDOParams);

if (!$result = $db->fetchAssoc()) {
    $jsonReturn['isFound'] = false;
    $jsonReturn['status'] = null;
    $jsonReturn['quotaType'] = null;
    $jsonReturn['requestedBy'] = null;
    $jsonReturn['verifiedBy'] = null;
    $jsonReturn['verifiedMeal'] = null;
    $jsonReturn['verifiedDate'] = null;
    $jsonReturn['count'] = null;
    $jsonReturn['verifyNotes'] = null;
    $jsonReturn['persons'] = null;
} else {

    if ($result['Field_16'] == "5") //فرم تایید شده است//
        $jsonReturn['status'] = "verified";
    else
        $jsonReturn['status'] = "in_progress";

    $jsonReturn['isFound'] = true;
    $jsonReturn['status'] = "in_progress";
    if ($result['Field_5'] == 0)
        $jsonReturn['quotaType'] = "عادی";
    else
        $jsonReturn['quotaType'] = "تشریفاتی";
    $jsonReturn['requestedBy'] = $result['Field_1'];
    $jsonReturn['verifiedBy'] = "جعفری";
    $jsonReturn['verifiedMeal'] = $result['Field_12'];


    if(strlen(['Field_10'])==10) {
        $dateMiladi = explode('-', $result['Field_10']);
        $dateShamsi = Date::gregorian_to_jalali($dateMiladi[0], $dateMiladi[1], $dateMiladi[2]);
        $jsonReturn['verifiedDate'] = $dateShamsi;
    }
    else
        $jsonReturn['verifiedDate']="";

    $jsonReturn['verifyNotes'] = $result['Field_13'];

    //////////////////
    $sql = "select * FROM dm_datastoretable_1099 where MasterID=:docId ORDER BY RowID";
    $PDOParams=array(
        array('name'=>'docId','value'=>$docId,'type'=>PDO::PARAM_INT)
    );
    $db->executeSelect($sql,$PDOParams);
    $count = 0;
    while ($row = $db->fetchAssoc()) {
        $jsonReturn['persons'][$count]['nationalCode'] = $row['Field_2'];
        $jsonReturn['persons'][$count]['birthDate'] = $row['Field_3'];

        if ($row['Field_6'] == 2)
            $jsonReturn['persons'][$count]['verifyBySabt'] = true;
        else
            $jsonReturn['persons'][$count]['verifyBySabt'] = false;

        $count++;
    }

    /// /////////////


    $jsonReturn['count'] = $count;


}



Response::getInstance()->response = $jsonReturn;

