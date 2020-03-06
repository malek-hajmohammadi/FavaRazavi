<?php


////آرایه ای که می خواهم بگیرم///
/*
$listGuest = array(
    array("ali", "alavi", "0939845654", "1398/11/11", 1, 2, 3, 1),
    array("ali", "alavi", "0939845654", "1398/11/11", 1, 2, 3, 1),
    array("ali", "alavi", "0939845654", "1398/11/11", 1, 2, 3, 1),
    array("ali", "alavi", "0939845654", "1398/11/11", 1, 2, 3, 1)
    ["ali","alavi","0939845654","1398/11/11",1,2,3]
);
*/
//get Data//
if (Request::getInstance()->varCleanFromInput('listGuest') && Request::getInstance()->varCleanFromInput('docId')) {
    $listGuest = Request::getInstance()->varCleanFromInput('listGuest');
    $listGuest = json_decode($listGuest);

    $docId = Request::getInstance()->varCleanFromInput('docId');

    ////for test///
    /*
    $message=RavanResult::raiseSuccess($listGuest);
    Response::getInstance()->response=$message;
    return;
    */
} else {

    $message = RavanResult::raiseError(0, "لطفا پارامترهای آیجکس رو مقدار دهی کنید", "فراخوانی آیجکس");
    Response::getInstance()->response = $message;
    return;
}
///////////
//$docId = "11811283"; //شماره نامه که مستر میشه//بری تست
//$detailedTable = "1099"; //جدول مهمان ها//
$messageString = "";
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///کوئری برای ذخیره کردن//
$db = MySQLAdapter::getInstance();
$sql = "DELETE from dm_datastoretable_1099
where MasterID=$docId";
$db->execute($sql);
/*
$sql = "INSERT INTO dm_datastoretable_1099  (MasterID,Field_0, Field_1,Field_2,Field_3,Field_4,Field_5,Field_6,Field_7)
VALUES ($docId, \"ali\",\"Razavi\",\"0938954490\",\"1398/11/11\",1,2,3,1)";

$db->execute($sql);
*/
$t = "empty";
for ($count = 1; $count < count($listGuest); $count++) {


    $sql = "INSERT INTO dm_datastoretable_1099 (MasterID,Field_0, Field_1,Field_2,Field_3,Field_4,Field_5,Field_6,Field_7)
VALUES ($docId,'{$listGuest[$count][0]}','{$listGuest[$count][1]}','{$listGuest[$count][2]}','{$listGuest[$count][3]}',{$listGuest[$count][4]},{$listGuest[$count][5]},{$listGuest[$count][6]},1)";



    $t = $sql;

    if (!$db->execute($sql)) {
        $message = RavanResult::raiseError(0, "عمليات ذخیره با خطا مواجه شد", "ذخیره");
        Response::getInstance()->response = $message;
        return;
    }
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


/////////////////////////////////////////تست سابقه استفاده///////////////////////////////////////
/// //////////////////////////////////////////پایان سابقه استفاده/////////////////////////////////

$messageString = "ذخیره سازی و بررسی اطلاعات مهمان ها با موفقيت انجام شد" . "<br/>";
$message = RavanResult::raiseSuccess($messageString);
//$message = RavanResult::raiseSuccess($t);
//Response::getInstance()->response = $message;
Response::getInstance()->response="success";
