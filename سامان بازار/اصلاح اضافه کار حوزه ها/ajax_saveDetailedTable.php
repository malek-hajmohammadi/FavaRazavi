<?php

$detailedTable="";/*آرایه ای که می خواهد در دیتابیس ذخیره شود*/
$docId="";/*شماره فرم مستر*/
$hozeh="";/*کد حوزه*/

if(1){
    if (Request::getInstance()->varCleanFromInput('detailedTable') && Request::getInstance()->varCleanFromInput('docId') && Request::getInstance()->varCleanFromInput('hozeh') ) {
        $detailedTable = Request::getInstance()->varCleanFromInput('detailedTable');
        $detailedTable = json_decode($detailedTable);

        $docId = Request::getInstance()->varCleanFromInput('docId');
        $hozeh=Request::getInstance()->varCleanFromInput('hozeh');

        ////for test///

       /* Response::getInstance()->response=$detailedTable;
        return;*/

    } else {

        $message = RavanResult::raiseError(0, "لطفا پارامترهای آیجکس رو مقدار دهی کنید", "فراخوانی آیجکس");
        Response::getInstance()->response = $message;
        return;
    }

} /*گرفتن ورودی*/
if(2){
    $db = MySQLAdapter::getInstance();
    $sql = "DELETE from dm_datastoretable_59
where MasterID=$docId";
    $db->execute($sql);

}/*حذف داده های قبلی قبل از ذخیره سازی، در غیر این صورت اطلاعات تکراری می شود*/
if(3){
    $db = MySQLAdapter::getInstance();
    $sql="UPDATE dm_datastoretable_58 as dm SET `Field_6`=0 WHERE DocID<>$docId AND `Field_4`=$hozeh  ";
    $db->execute($sql);
}/*حذف پیش فرض بودن لیست قبلی حوزه انتخاب شد*/
if(2){
///
    $db = PDOAdapter::getInstance();
    for ($count = 1; $count < count($detailedTable); $count++) {

        $sql = "INSERT INTO dm_datastoretable_59 (MasterID,Field_0,Field_1,Field_2,Field_3,Field_4)
 VALUES (:docId,:firstName,:lastName,:cardNumber,:overworkDone,:overworkConfirm)";
        $PDOParams = array(
            array('name' => 'docId', 'value' => $docId, 'type' => PDO::PARAM_STR),
            array('name' => 'firstName', 'value' => $detailedTable[$count][0], 'type' => PDO::PARAM_STR),
            array('name' => 'lastName', 'value' => $detailedTable[$count][1], 'type' => PDO::PARAM_STR),
            array('name' => 'cardNumber', 'value' => $detailedTable[$count][2], 'type' => PDO::PARAM_STR),
            array('name' => 'overworkDone', 'value' => $detailedTable[$count][3], 'type' => PDO::PARAM_STR),
            array('name' => 'overworkConfirm', 'value' => $detailedTable[$count][4], 'type' => PDO::PARAM_STR),

        );
        $db->executeSelect($sql,$PDOParams);

    }


}/*ذخیره در دیتابیس*/