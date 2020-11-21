<?php



$user = "ravan-aqr";
$pass = "587ebw667nwf989";

$docid = Request::getInstance()->varCleanFromInput('fn');
$nationalCode = Request::getInstance()->varCleanFromInput('meli');
$birthDate = Request::getInstance()->varCleanFromInput('birth');
$birthDate = Date::JalaliToGreg($birthDate);

$nationalCardSerial = Request::getInstance()->varCleanFromInput('s_MeliCode');
$nationalCardSerial = strtoupper($nationalCardSerial);

$textfalse404 = ' كد ملي يا تاريخ تولد معتبر نيست';
$textfalse409 = ' اطلاعات پرونده شما در بانك اطلاعاتي نهايي نيست';
$textfalse410 = ' فرد در قيد حيات نيست';
$textfalse503 = ' موقتا امكان بررسي وجود ندارد';
$textfalse403 = ' كابر يا كلمه عبور نامعتبر';
$textfalse412 = ' خطاي اعتبار سنجي';
$textfalseD = 'خطاي ناشناخته';
$texttrue = '';

$client = curl_init('https://sabt-api.aqr.ir/api/get-image/');

curl_setopt($client, CURLOPT_POST, 1);
curl_setopt($client, CURLOPT_HEADER, true);
curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
curl_setopt($client, CURLOPT_VERBOSE, true);

$params = array(
    "apiUser" => "$user",
    "apiKey" => "$pass",
    "nationalCode" => $nationalCode,
    "birthDate" => $birthDate,
    "nationalCardSerial" => $nationalCardSerial
);
$params = json_encode($params);
curl_setopt($client, CURLOPT_POSTFIELDS, $params);
$res = curl_exec($client);
$header_size = curl_getinfo($client, CURLINFO_HEADER_SIZE);
$httpcode = curl_getinfo($client, CURLINFO_HTTP_CODE);
curl_close($client);
$res = substr($res, $header_size);

$db = MySQLAdapter::getInstance();
$sql = "update dm_datastoretable_964 set Field_27 = 'get-image=$httpcode' where  DocID = $docid";
$db->execute($sql);

switch ($httpcode) {
    case 200:
        $res = json_decode($res, true);
        $userInfoDir = '/datastorage/userinfo/userimage_' . $nationalCode . '.jfif';
        if (copy($res['imageUrl'], $userInfoDir)) {
            Response::getInstance()->response = 'true';
        }
        else
            Response::getInstance()->response = 'خطايي در اتنقال فايل رخ داده لطفا به كاربر ارشد اطلاع دهيد';
        break;
    case 404:
        Response::getInstance()->response = ' فردي با چنين مشخصات يافت نشد <br> لطفا اطلاعات خود را بررسي نماييد';
        break;
    case 409:
        Response::getInstance()->response =  $textfalse409 ;
        break;
    case 503:
        Response::getInstance()->response = $textfalse503;
        break;
    case 403:
        Response::getInstance()->response = $textfalse403;
        break;
    case 412:
        Response::getInstance()->response = $textfalse412;
        break;
    Default:
        Response::getInstance()->response = $httpcode.': '.$textfalseD;
        break;

}




