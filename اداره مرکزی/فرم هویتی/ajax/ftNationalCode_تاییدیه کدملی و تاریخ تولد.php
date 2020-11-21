<?php



$nationalCode=Request::getInstance()->varCleanFromInput('meli');
$birthDate=Request::getInstance()->varCleanFromInput('birth');
//$nationalCode="0961850401";
//$birthDate="1397/08/29";


$db = MySQLAdapter::getInstance();
$sql = "SELECT DocID FROM `dm_datastoretable_964` dm
        INNER JOIN wf_execution on(wf_execution.execution_doc_id = dm.DocID AND wf_execution.is_enable = 1)
        INNER JOIN oa_document on(oa_document.RowID = dm.DocID and oa_document.IsEnable = 1)
        WHERE dm.Field_6 = '$nationalCode' and dm.Field_25 > 2";
$check = $db->executeScalar($sql);
if(intval($check) > 0){
    Response::getInstance()->response= "اين شماره ملي قبلا در سيستم ثبت گرديده";
    return;
}

$birthDate = Date::JalaliToGreg($birthDate);

$user = "ravan-aqr";
$pass = "587ebw667nwf989";

$client = curl_init('https://sabt-api.aqr.ir/api/national-code-verify/');

curl_setopt($client, CURLOPT_POST, 1);
curl_setopt($client, CURLOPT_HEADER, true);
curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
curl_setopt($client, CURLOPT_VERBOSE, true);

$params = array(
    "apiUser" => "$user",
    "apiKey" => "$pass",
    "nationalCode" => "$nationalCode",
    "birthDate" => "$birthDate"
);
$params = json_encode($params);

curl_setopt($client, CURLOPT_POSTFIELDS, $params);

$res = curl_exec($client);

$header_size = curl_getinfo($client, CURLINFO_HEADER_SIZE);
$httpcode = curl_getinfo($client, CURLINFO_HTTP_CODE);
curl_close($client);
$res = substr($res, $header_size);

$state=-1;

$sql = "update dm_datastoretable_964 set Field_27 = 'national-code-verify=$httpcode' where  Field_6 = '$nationalCode'";
$db->execute($sql);

if(intval($httpcode == 200))
    $state=true;
else {
    switch ($httpcode){
        case 403:
            $state= ' كابر يا كلمه عبور نامعتبر در اعتبار سنجي ';
            break;
        case 412:
            $state= ' اطلاعات نامعتبر ميباشد<br> لطفا اطلاعات خود را بررسي نماييد';
            break;
        case 404:
            $state= ' فردي با چنين مشخصات وجود ندارد<br>لطفا اطلاعات خود را بررسي نماييد';
            break;
        case 409:
            $state= ' اطلاعات شخص معتبر نيست<br> لطفا اطلاعات خود را بررسي نماييد';
            break;
        case 410:
            $state= ' با توجه به اطلاعات واصله اين فرد فوت كرده است';
            break;
        case 403:
            $state= ' عدم امكان برفراري ارتباط';
            break;
        case 503:
            $state= ' 503: موقتا امكان بررسي وجود ندارد';
            break;
        case 500:
            $state= ' 500: موقتا امكان بررسي وجود ندارد';
            break;
        Default:
            $state= $httpcode;
    }

}
Response::getInstance()->response= $state;
