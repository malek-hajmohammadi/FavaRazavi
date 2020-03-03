<?php


////آرایه ای که می خواهم بگیرم///
/*
  array("تلویزیون", "1212312", 6, 1, "120000", "نیاز به تعمیر دارد"),
    array("تلویزیون", "1212312", 6, 1, "120000", "نیاز به تعمیر دارد"),
    array("تلویزیون", "1212312", 6, 1, "120000", "نیاز به تعمیر دارد"),
    array("تلویزیون", "1212312", 6, 1, "120000", "نیاز به تعمیر دارد"),
    array("تلویزیون", "1212312", 6, 1, "120000", "نیاز به تعمیر دارد"),
$myList = array(
    array("ali", "alavi", "0939845654", "1398/11/11", 1, 2, 3, 1),
    array("ali", "alavi", "0939845654", "1398/11/11", 1, 2, 3, 1),
    array("ali", "alavi", "0939845654", "1398/11/11", 1, 2, 3, 1),
    array("ali", "alavi", "0939845654", "1398/11/11", 1, 2, 3, 1)
    ["ali","alavi","0939845654","1398/11/11",1,2,3]
);
*/
//get Data//
if (Request::getInstance()->varCleanFromInput('myList') && Request::getInstance()->varCleanFromInput('docId')) {
    $myList = Request::getInstance()->varCleanFromInput('myList');
    $myList = json_decode($myList);

    $docId = Request::getInstance()->varCleanFromInput('docId');

    ////for test///
  
   
   // Response::getInstance()->response=$myList;
   // return;
   
} else {

    $message = RavanResult::raiseError(0, "لطفا پارامترهای آیجکس رو مقدار دهی کنید", "فراخوانی آیجکس");
    Response::getInstance()->response = $message;
    return;
}
///////////
//$docId = "11811283"; //شماره نامه که مستر میشه//بری تست
//$detailedTable = "1101"; //کد جدول جزء//
$messageString = "";
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///کوئری برای ذخیره کردن//
$db = MySQLAdapter::getInstance();
$sql = "DELETE from dm_datastoretable_1101
where MasterID=$docId";
$db->execute($sql);
/*
$sql = "INSERT INTO dm_datastoretable_1101  (MasterID,Field_0, Field_1,Field_2,Field_3,Field_4,Field_5)
VALUES ($docId, \"ali\",\"Razavi\",\"0938954490\",\"1398/11/11\",1,2,3,1)";

$db->execute($sql);
*/
$t = "";
for ($count = 1; $count < count($myList); $count++) {


    $sql = "INSERT INTO dm_datastoretable_1101 (MasterID,Field_0, Field_1,Field_2,Field_3,Field_4,Field_5)
VALUES ($docId,'{$myList[$count][0]}','{$myList[$count][1]}','{$myList[$count][2]}','{$myList[$count][3]}','{$myList[$count][4]}','{$myList[$count][5]}')";


    $t .= $sql.'<br/>';

    if (!$db->execute($sql)) {
        $message = RavanResult::raiseError(0, "عمليات ذخیره با خطا مواجه شد", "ذخیره");
        Response::getInstance()->response = $message;
        return;
    }
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//Response::getInstance()->response = $t;
//return;
$messageString="ذخیره سازی  با موفقيت انجام شد"."<br/>";
$message = RavanResult::raiseSuccess($messageString);

Response::getInstance()->response = $message;

