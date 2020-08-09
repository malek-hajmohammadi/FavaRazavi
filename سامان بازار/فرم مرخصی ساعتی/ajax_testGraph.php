<?php

/*if (Request::getInstance()->varCleanFromInput('placeHolder'))
    $varTemp = Request::getInstance()->varCleanFromInput('placeHolder');
else {

    Response::getInstance()->response = "There is no specefic input";
    return;
}*/
/*----------------متغییرهای لازم----------*/
$docID="12354";
$dateForLog="990512";

/*Field1:شماره ثبت است که معمولا صفر می گذاریم*/
$reqId='0';

/*Field2:نوع سند است*/
/*مرخصی ساعتی:15 مرخصی روزانه:10  ماموریت ساعتی:25  ماموریت روزانه:20*/
$formType='15';

/*Field3:شماره پرسنلی هست*/
$emp='112';

/*Field4:تاریخ درخواست هست که تاریخ جاری رو می گذاریم*/
/*این رو باید امتحان کنم یا نگاه کنم که ماه و روز حتما باید دو رقمی باشد یا نه*/
$docDate='1396/10/25';

/*Field5:نوع تردد است که مرخصی ساعتی:1  ماموریت ساعتی:2 ماموریت روزانه :150  و برای مرخصی روزانه بر اساس یک جدول می گذاریم که برای هر شرکت متغییر است*/
/*مثلا مرخصی استحقاقی می تونه 104 باشه*/
$tradodType='1';

/*Field6:طول مدت هست که من خالی می زارم ولی خود سیستم قرار محاسبه کند*/
$reqVal='';

/*تاریخ شروع و پایان داریم که برای ساعتی ها یک عدد هست و برای روزانه ها تاریخ شروع و پایان هست*/
/*Field7:تاریخ شروع*/
$startDate='1396/10/12';
/*Field8:تاریخ پایان*/
$endDate='1396/10/12';

/*ساعت شروع و پایان رو داریم که برای روزانه ها صفر می دم و برای ساعتی ها یک عدد می دهم که ساعت در 60 ضرب بعلاوه دقیقه*/
/*Field9:ساعت شروع*/
$startTime=540;
/*Field10: ساعت خاتمه*/
$endTime=600;

/*Field11:شهر هست که این رو صفر بدم*/
$city=0;

/*Field12:وضعیت سند هست که این رو همیشه 1 بدم یعنی تایید شده*/
$docStatus=1;

/*Field13:توضیحات سیستمی هست که برای هماهنگ بودن با اخوان اولش وب می نویسیم و بعدش شماره فرمی که باعث زدن این سند شده*/
$commentSys="wb$docID";

/*Field14:توضیحات کاربر هست که اخوان گفته این رو هم مثله بالا یکسان بگذارم*/
$commentUser=$commentSys;

/*Field15:یک لاگ هست که فرم مشخصی دارد و بصورت زیر می دهیم*/
/*930208T1000UW    شامل تاریخ،ساعت و اختصار کاربر*/
/*تاریخ که معلومه بقیه رو ثابت به آن می چسبونیم*/
$log=$dateForLog."T1000UW";

/*Field16:یک فلگ هست که می گه در صورت تداخل این سند رو ثبت کند یا نه */
/*اخوان 1 گذاشته ولی باید ازش سوال کنم که کی این می تونه یک نباشه*/
$flag=1;

/*-----------------اجرای تابع---------------------*/
/*$s1 = "EXEC [adon].[FlowDocs_Fill] '0', '".$FormType."', '".$emp."', '".$docDate."', '".$FormType2."', '".$ModatMorkhasi."', '".$dateR."', '".$dateB."', ".$timeR.", ".$timeB.", ".$city.", 1, 'WB".$docID."','WB".$docID."', '".$log."', 1";*/
//$s1 = "EXEC [adon].[FlowDocs_Fill] '0', '$FormType', '$emp', '$docDate', '$FormType2', '$ModatMorkhasi', '$dateR', '$dateB', $timeR,$timeB, $city, 1, 'WB$docID','WB$docID', '$log', 1";
$sqlString = "EXEC [adon].[FlowDocs_Fill] '$reqId', '$formType', '$emp', '$docDate', '$tradodType', '$reqVal', '$startDate', '$endDate', $startTime,$endTime, $city, $docStatus, '$commentSys','$commentUser', '$log', $flag";

$userName="3ef1b48067e4f2ac9913141d77e847dd";
$password="9a3f5b14f1737c15e86680d9cd40b840";


$param = array(
    'username' => $userName,
    'password' => $password,
    'objStr' => $sqlString
);
$error="";
try {
    $client = new SoapClient('http://46.209.31.219:8050/Timex.asmx?wsdl');
    $response=$client->RunQuery($param);
}
catch(SoapFaultException $e){
    $error .= $e;
}
catch(Exception $e){
    $error .= $e;
}


Response::getInstance()->response =$error;




