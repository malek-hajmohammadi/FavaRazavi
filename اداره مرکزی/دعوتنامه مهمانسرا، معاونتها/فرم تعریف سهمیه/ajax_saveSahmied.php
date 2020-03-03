<?php



$acm = AccessControlManager::getInstance();
$uid = $acm->getUserID();

$permittedUsers = array(6685, 1662, 227, 2306);
if (!in_array($uid, $permittedUsers)) {
    Response::getInstance()->response = RavanResult::raiseError(0, "شما دسترسي به اين قسمت نداريد", "شما دسترسي به اين قسمت نداريد");
    return false;
}

$userList = Request::getInstance()->varCleanFromInput("userList");
if (!$userList || strlen($userList) == 0) {
    Response::getInstance()->response = RavanResult::raiseError(0, "ليست كاربران نميتواند خالي باشد", "ليست كاربران نميتواند خالي باشد");
    return false;
}

$db = PDOAdapter::getInstance();
$userList = json_decode($userList, true);
if (count($userList) == 0) {
    Response::getInstance()->response = RavanResult::raiseError(0, "اطلاعات ارسال شده معتبر نميباشد", "اطلاعات ارسال شده معتبر نميباشد");
    return false;
}
$group_values = array();
$form_values = array();
$PDOParams = array();
$i = 0;
foreach($userList as $value){
    $i++;
    $user = explode(',', $value['user']);
    if(!is_array($user) || count($user) != 2){
        Response::getInstance()->response = RavanResult::raiseError(0, "فيلد كاربري ارسال شده معتبر نميباشد", "فيلد كاربري ارسال شده معتبر نميباشد");
        return false;
    }
    if(strlen($value['startDate']) > 0 && !preg_match('/^[0-9]{4}\/[0-9]{1,2}\/[0-9]{1,2}$/',$value['startDate'])){
        Response::getInstance()->response = RavanResult::raiseError(0, "تاريخ شروع بازه معتبر نميباشد", "تاريخ شروع بازه معتبر نميباشد");
        return false;
    }
    if(strlen($value['endDate']) > 0 && !preg_match('/^[0-9]{4}\/[0-9]{1,2}\/[0-9]{1,2}$/',$value['endDate'])){
        Response::getInstance()->response = RavanResult::raiseError(0, "تاريخ پايان بازه معتبر نميباشد", "تاريخ پايان بازه معتبر نميباشد");
        return false;
    }


    $roleID = intval($user[1]);
    $group_values[] = "($roleID, 64)";

    $shareCount = intval($value['shareCount']);
    $startDate = $value['startDate'];
    if(strlen($startDate) > 0)
        $startDate = Date::JalaliToGreg($value['startDate']);
    $endDate = $value['endDate'];
    if(strlen($endDate) > 0)
        $endDate = Date::JalaliToGreg($value['endDate']);

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


    $form_values[] = "('guestHouseAccess', $roleID, '$startDate', '$endDate', $shareCount)";

}
$group_values = implode(',', $group_values);
$form_values = implode(',', $form_values);

$ids = $db->executeScalar("SELECT group_concat(RowID) from oa_group_members where UserGroupID = 64");
$members_delete_sql = "DELETE from oa_group_members where RowID in($ids)";

$sql = "DELETE from dm_datastoretable_1051";
$db->execute($sql);

$sql = "INSERT INTO oa_group_members(RoleID, UserGroupID) VALUES $group_values";

if ($db->execute($sql)) {
    $db->execute($members_delete_sql);
    $sql = "INSERT INTO dm_datastoretable_1051(FullTextField, Field_0,Field_1,Field_2,Field_3) VALUES $form_values";
    $res = $db->execute($sql);
    if($res == 0){
        die($sql);
    }
    $result = RavanResult::raiseSuccess("ثبت كاربران با موفقيت انجام شد"."($res)");
}
else
    $result = RavanResult::raiseError(0, "عمليات با خطا مواجه شد", "عمليات با خطا مواجه شد");

Response::getInstance()->response = $result;
