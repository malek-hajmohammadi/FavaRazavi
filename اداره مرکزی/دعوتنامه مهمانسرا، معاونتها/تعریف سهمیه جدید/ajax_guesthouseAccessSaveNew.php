<?php




$listItems = Request::getInstance()->varCleanFromInput("listItems");
if (!$listItems || strlen($listItems) == 0) {
    Response::getInstance()->response = "ليست كاربران نميتواند خالي باشد";
    return false;
}

$db = PDOAdapter::getInstance();
$listItems = json_decode($listItems, true);
if (count($listItems) == 0) {
    Response::getInstance()->response = "اطلاعات ارسال شده معتبر نميباشد";
    return false;
}


$group_values = array();
$form_values = array();
$PDOParams = array();
$i = 0;
foreach($listItems as $value){
    $i++;
    $user = explode(',', $value[0]);
    if(!is_array($user) || count($user) != 2){
        Response::getInstance()->response = RavanResult::raiseError(0, "فيلد كاربري ارسال شده معتبر نميباشد", "فيلد كاربري ارسال شده معتبر نميباشد");
        return false;
    }
    if(strlen($value[1]) > 0 && !preg_match('/^[0-9]{4}\/[0-9]{1,2}\/[0-9]{1,2}$/',$value[1])){
        Response::getInstance()->response = RavanResult::raiseError(0, "تاريخ شروع بازه معتبر نميباشد", "تاريخ شروع بازه معتبر نميباشد");
        return false;
    }
    if(strlen($value[2]) > 0 && !preg_match('/^[0-9]{4}\/[0-9]{1,2}\/[0-9]{1,2}$/',$value[2])){
        Response::getInstance()->response = RavanResult::raiseError(0, "تاريخ پايان بازه معتبر نميباشد", "تاريخ پايان بازه معتبر نميباشد");
        return false;
    }


    $roleID = intval($user[1]);
    $group_values[] = "($roleID, 64)";

    $shareCount = intval($value[3]);
    $shareCountTashrif = intval($value[4]);

    $startDate = $value[1];
    if(strlen($startDate) > 0)
        $startDate = Date::JalaliToGreg($value[1]);
    $endDate = $value[2];
    if(strlen($endDate) > 0)
        $endDate = Date::JalaliToGreg($value[2]);

    if(strlen($startDate) > 0 && strlen($endDate) > 0){
        $date1 = new DateTime($startDate);
        $date2 = new DateTime($endDate);
        $interval = $date1->diff($date2);
        $diff = intval($interval->format('%R%a'));
        if($diff < 0){
            Response::getInstance()->response = RavanResult::raiseError(0, "تاريخ پايان بازه كوچكتر از تاريخ شروع ميباشد", "تاريخ پايان بازه كوچكتر از تاريخ شروع ميباشد");
            return false;
        }
    }


    $form_values[] = "('guestHouseAccessNew', $roleID, '$startDate', '$endDate', $shareCount,$shareCountTashrif)";

}
$group_values = implode(',', $group_values);
$form_values = implode(',', $form_values);

$ids = $db->executeScalar("SELECT group_concat(RowID) from oa_group_members where UserGroupID = 64");
$members_delete_sql = "DELETE from oa_group_members where RowID in($ids)";

$sql = "DELETE from dm_datastoretable_1104";
$db->execute($sql);

$sql = "INSERT INTO oa_group_members(RoleID, UserGroupID) VALUES $group_values";

if ($db->execute($sql)) {
    $db->execute($members_delete_sql);
    $sql = "INSERT INTO dm_datastoretable_1104(FullTextField, Field_0,Field_1,Field_2,Field_3,Field_4) VALUES $form_values";
    $res = $db->execute($sql);
    if($res == 0){
        die($sql);
    }
    $result = RavanResult::raiseSuccess("ثبت كاربران با موفقيت انجام شد"."($res)");
}
else
    $result = RavanResult::raiseError(0, "عمليات با خطا مواجه شد", "عمليات با خطا مواجه شد");

Response::getInstance()->response = $result;

