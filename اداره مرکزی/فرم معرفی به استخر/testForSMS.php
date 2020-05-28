<?php


$mobile = Request::getInstance()->varCleanFromInput('mobile');
$text = Request::getInstance()->varCleanFromInput('text');
/*$mobile="09155227340";
$text="text";*/
/*if(strlen($text) == 0 || intval($text) == 0) {
    Response::getInstance()->response = 'Invalid Data';
    return ;
}*/
if(strlen($mobile)>0) {
    /*ثابت*/
    $db = PDOAdapter::getInstance();
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

    $result = $myInstance->sendWithTracking($mobile, $text);
    $trackCode = '';
    $status = 0;
    if(is_array($result) && is_int($result[0])) {
        $status = 1;
        $trackCode = $result[0];
    }
    $result = json_encode($result);
    /* save sms */


    Response::getInstance()->response = $result;
    return;
}
Response::getInstance()->response = 'شماره صحيح نميباشد';