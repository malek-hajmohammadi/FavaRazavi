<?php


/*گرفتن آخرین لیستی که برای این حوزه پر شده و از سطح یک گذشته باشد رو می خوانم و بر می گردونم*/

if (1) {
    $year = "";/*سال برای لیست*/
    $month = "";
    $hozeh = "";
    if (Request::getInstance()->varCleanFromInput('year'))
        $year = Request::getInstance()->varCleanFromInput('year');
    else {

        Response::getInstance()->response = "There is no specefic input";
        return;
    }

    if (Request::getInstance()->varCleanFromInput('month'))
        $month = Request::getInstance()->varCleanFromInput('month');
    else {

        Response::getInstance()->response = "There is no specefic input";
        return;
    }

    if (Request::getInstance()->varCleanFromInput('hozeh'))
        $hozeh = Request::getInstance()->varCleanFromInput('hozeh');
    else {

        Response::getInstance()->response = "There is no specefic input";
        return;
    }

}/*گرفتن ورودی*/
if(2){

    /*این فرم فعلا اوکی هست ولی وقتی سال دیگه شد باید شرط بگذارم که اگر فرودین بود برگردد به اسفند سال قبل*/
    if($month=="1"){
        Response::getInstance()->response = "روی این ماه در سال 1400 تعریف می شود";
        return;
    }
    $month--;

}/*تبدیل تاریخ به تاریخی که می خواهیم دنبال بگردیم*/
if(3){
    /*f1:mah , f2:sal , f4:hozeh*/
    $db = PDOAdapter::getInstance();
    $sql="SELECT dmSlave.* FROM `dm_datastoretable_58` AS dmMaster
 INNER JOIN `dm_datastoretable_59` AS dmSlave ON(dmMaster.DocID = dmSlave.MasterID)
 WHERE dmMaster.`Field_1`=:month AND dmMaster.`Field_2`=:year AND dmMaster.`Field_4`=:hozeh ORDER BY dmSlave.`RowID`";
    $PDOParams=array(
        array('name'=>'month','value'=>$month,'type'=>PDO::PARAM_INT),
        array('name'=>'year','value'=>$year,'type'=>PDO::PARAM_INT),
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



