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
    $sql="SELECT dm.`Field_3` FROM `dm_datastoretable_58` AS dm
 WHERE  dm.`Field_4`=:hozeh AND dm.`Field_6`=1 ";
    $PDOParams=array(
        array('name'=>'hozeh','value'=>$hozeh,'type'=>PDO::PARAM_INT),
    );
    $db->executeSelect($sql,$PDOParams);

    $saghf = 0;

    if ($row = $db->fetchAssoc()) {
        $saghf=$row['Field_3'];
    }
}/*گرفتن آرایه از دیتابیس*/
if(4){
    Response::getInstance()->response =$saghf;

}/*برگشت خروجی به برنامه*/



