<?php


/*گرفتن سقف مجاز اضافه کار حوزه که بصورت پیش فرض ذخیره شده است */

if (1) {

    $hozeh = "";

    if (Request::getInstance()->varCleanFromInput('hozeh'))
        $hozeh = Request::getInstance()->varCleanFromInput('hozeh');
    else {

        Response::getInstance()->response = "There is no specefic input";
        return;
    }

}/*گرفتن ورودی*/

if(3){
    /*f1:mah , f2:sal , f4:hozeh*/
    $db = PDOAdapter::getInstance();


//اول گرفتن داک آدی که می خوام و در مرحله بعد برم کاربر سمت رو از ویو بگیرم//
    $sql="SELECT dm.`DocID` FROM `dm_datastoretable_58` AS dm
 WHERE  dm.`Field_4`=:hozeh AND dm.`Field_6`=1 ";
    $PDOParams=array(
        array('name'=>'hozeh','value'=>$hozeh,'type'=>PDO::PARAM_INT),
    );
    $db->executeSelect($sql,$PDOParams);

    $docId = 0;
    $uId=0;
    $rId=0;

    if ($row = $db->fetchAssoc()) {
        $docId=$row['DocID'];
    }

    if($docId<>0){

        $sql="SELECT v.`uid`,v.`rid` FROM `vi_form_userrole` AS v
 WHERE  v.`DocID`=:docId  ";
        $PDOParams=array(
            array('name'=>'docId','value'=>$docId,'type'=>PDO::PARAM_INT),
        );
        $db->executeSelect($sql,$PDOParams);

        if ($row = $db->fetchAssoc()) {
            $uId=$row['uid'];
            $rId=$row['rid'];
        }

    }

$arUserRole=[];
    $arUserRole['uid']=$uId;
    $arUserRole['rid']=$rId;


}/*گرفتن آرایه از دیتابیس*/
if(4){
    Response::getInstance()->response =$arUserRole;

}/*برگشت خروجی به برنامه*/



