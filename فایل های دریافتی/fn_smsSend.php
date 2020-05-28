<?php

$text = Request::getInstance()->varCleanFromInput("smsText");/*متن پیام*/
$mobs = Request::getInstance()->varCleanFromInput("mobs");/*شماره موبایل*/
$docID = Request::getInstance()->varCleanFromInput("docID");
$type = Request::getInstance()->varCleanFromInput("type");
$smsID = Request::getInstance()->varCleanFromInput("smsID");/*کد پیگیری اس ام اس*/
$db = PDOAdapter::getInstance();

if($smsID && strlen($smsID) > 0){
    $sql = "select * from dm_datastoretable_968 where Field_2 = '$smsID'";
    $db->executeSelect($sql);
    $row = $db->fetchAssoc();
    $text = $row['Field_1'];
    $mobs = $row['Field_5'];
    $docID = $row['MasterID'];
    $type = $row['Field_0'];
}
if(strlen($text) == 0 || intval($docID) == 0) {
    Response::getInstance()->response = 'Invalid Data';
    return ;
}
if(strlen($mobs)>0) {
    $sid = SecUser::GetCurrUserSecID();
    $sql = "select smsAction from oa_secretariat where RowID=" . intval($sid);
    $phpcode = $db->executeScalar($sql);

    $uniqID = uniqid();
    $calssName = 'calssName' . $uniqID;
    $code = $phpcode;
    $code = str_replace('<?php', '', $code);
    $code = str_replace('?>', '', $code);
    $code = str_replace('calssName', $calssName, $code);
    eval($code);
    $myInstance = new $calssName();
    $result = $myInstance->sendWithTracking($mobs, $text);
    $trackCode = '';
    $status = 0;
    if(is_array($result) && is_int($result[0])) {
        $status = 1;
        $trackCode = $result[0];
    }
    $result = json_encode($result);
    /* save sms */
    $myForm = new DMSNFC_form(array('structid' => NULL, 'fieldid' => 968, 'docid' => NULL, 'referid' => NULL, 'masterid' => $docID));
    $data = ["12105" => "$type", "12106" => $text, "12107" => "$trackCode", "12108" => "$status", "12109" => "$result", "12324" => "$mobs"];
    $res = $myForm->setData($data);

    Response::getInstance()->response = $result;
    return;
}
Response::getInstance()->response = 'شماره صحيح نميباشد';