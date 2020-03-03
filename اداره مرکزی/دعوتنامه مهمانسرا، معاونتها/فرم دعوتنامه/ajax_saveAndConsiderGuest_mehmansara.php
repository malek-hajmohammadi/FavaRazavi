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



/////////////////////////////////////////////////////ثبت احوال//////////////////////////////////////

for ($count = 1; $count < count($listGuest); $count++) {
    $nationalCode = $listGuest[$count][2];
    $birthDate = Date::JalaliToGreg($listGuest[$count][3]);
    $stateAhval = 1;  /*1:na moshakhas 2:mojaz 3:na motaber*/
    $messageString .= $nationalCode . "<br/>" . $birthDate . "<br/>";

    /**
     * اینجا می تونم چک کنم که کلا در جدولم
     * اگر کسی با این مشخصات هست و تایید هم شده است که اوکی 
     * هست دیگه چک نکنم اون رو
     */


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

    $state = -1;
    /*
    $sql = "update dm_datastoretable_964 set Field_27 = 'national-code-verify=$httpcode' where  Field_6 = '$nationalCode'";
    $db->execute($sql);
    */

    if (intval($httpcode == 200)) {
        $state = true;
        $stateAhval = 2;
    } else {
        switch ($httpcode) {
            case 403:
                $state = ' كابر يا كلمه عبور نامعتبر در اعتبار سنجي ';
                $stateAhval = 1;
                break;
            case 412:
                $state = ' اطلاعات نامعتبر ميباشد<br> لطفا اطلاعات خود را بررسي نماييد';
                $stateAhval = 3;
                break;
            case 404:
                $state = ' فردي با چنين مشخصات وجود ندارد<br>لطفا اطلاعات خود را بررسي نماييد';
                $stateAhval = 3;
                break;
            case 409:
                $state = ' اطلاعات شخص معتبر نيست<br> لطفا اطلاعات خود را بررسي نماييد';
                $stateAhval = 3;
                break;
            case 410:
                $state = ' با توجه به اطلاعات واصله اين فرد فوت كرده است';
                $stateAhval = 3;
                break;
            case 403:
                $state = ' عدم امكان برفراري ارتباط';
                $stateAhval = 1;
                break;
            case 503:
                $state = ' 503: موقتا امكان بررسي وجود ندارد';
                $stateAhval = 1;
                break;
            case 500:
                $state = ' 500: موقتا امكان بررسي وجود ندارد';
                $stateAhval = 1;
                break;
            default:
                $state = $httpcode;
                $stateAhval = 1;
        }
    }
    $messageString .= $state . "<br/>";

    $sql = "update dm_datastoretable_1099 set Field_6 =$stateAhval where  Field_2 = '$nationalCode'";
    $db->execute($sql);

    $listGuest[$count][6] = $stateAhval;
}


/////////////////////////////////////////////////اتمام ثبت احوال///////////////////////////////////
////////////////////////////////////تست نعیم رضوان////////////////////////////////

//یک کد ملی بگیره و تعداد غذاهایی که خورده در سه سال گذشته رو برگردونه//

//loop
for ($count = 1; $count < count($listGuest); $count++) {
    $nationalCode = $listGuest[$count][2];

    $stateNaiim = 1;  /*1:na moshakhas 2:mojaz 3:na motaber*/


    ///////////////

    //یک کد ملی بگیره و تعداد غذاهایی که خورده در سه سال گذشته رو برگردونه//

    $client = curl_init('https://mehmansara.razavi.ir/api/feeded-log/');

    curl_setopt($client, CURLOPT_POST, 1);
    curl_setopt($client, CURLOPT_HEADER, true);
    curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($client, CURLOPT_VERBOSE, true);

    $params = array(
        "apiUser" => "ravan.aqr.ir",
        "apiKey" => "D5hX(#N3*DhpfDR",
        "nationalCode" => "$nationalCode"
    );
    $params = json_encode($params);

    curl_setopt($client, CURLOPT_POSTFIELDS, $params);

    $res = curl_exec($client);


    $header_size = curl_getinfo($client, CURLINFO_HEADER_SIZE);
    $httpcode = curl_getinfo($client, CURLINFO_HTTP_CODE);
    curl_close($client);


    $httpcode = intval($httpcode);
    if ($httpcode >= 200 and $httpcode < 300) {


        $res = substr($res, $header_size);
        $res = json_decode($res, true);
        $value = $res['reserveCount'];

        if ($value==0)
        ///کل سابقه رو چک می کند که بعدا درستش کنم برای فقط چک کردن سه سال اخیر//
          $stateNaiim=2;
        else
          $stateNaiim=3;
          
    }
    else if($httpcode == 412)
      $stateNaiim=3;
    else
      $stateNaiim=1;


    

   

    $sql = "update dm_datastoretable_1099 set Field_5 =$stateNaiim where  Field_2 = '$nationalCode'";
    $db->execute($sql);

    $listGuest[$count][5] = $stateNaiim;//فکر کنم لازم نیست//
}





//////////////////////////////////////پایان نعیم رضوان/////////////////////////////////////////
$messageString = "ذخیره سازی و بررسی اطلاعات مهمان ها با موفقيت انجام شد" . "<br/>";
$message = RavanResult::raiseSuccess($messageString);
//$message = RavanResult::raiseSuccess($t);
//Response::getInstance()->response = $message;
Response::getInstance()->response="success";