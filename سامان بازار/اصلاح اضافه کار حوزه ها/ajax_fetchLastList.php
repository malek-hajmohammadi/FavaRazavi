<?php


/*گرفتن آخرین لیستی که برای این حوزه پر شده و از سطح یک گذشته باشد رو می خوانم و بر می گردونم*/

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
    $sql="SELECT dmSlave.* FROM `dm_datastoretable_58` AS dmMaster
 INNER JOIN `dm_datastoretable_59` AS dmSlave ON(dmMaster.DocID = dmSlave.MasterID)
 WHERE  dmMaster.`Field_4`=:hozeh AND dmMaster.`Field_6`=1 ORDER BY dmSlave.`RowID`";
    $PDOParams=array(
         array('name'=>'hozeh','value'=>$hozeh,'type'=>PDO::PARAM_INT),
    );
    $db->executeSelect($sql,$PDOParams);

    $count = 0;
    $arrayList=[];
    while ($row = $db->fetchAssoc()) {
        $arrayList[$count]=[];
        $arrayList[$count][0]=$row['Field_0'];
        $arrayList[$count][1]=$row['Field_1'];
        $arrayList[$count][2]=$row['Field_2'];
        $count++;
    }
}/*گرفتن آرایه از دیتابیس*/
if(4){
    Response::getInstance()->response =$arrayList;

}/*برگشت خروجی به برنامه*/



