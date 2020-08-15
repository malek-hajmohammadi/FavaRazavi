<?php
class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {


       $stringQuery=self::saveToGraph($execution);
      // $execution->workflow->myForm->setFieldValueByName('Field_1',$stringQuery);
       self::runStringQuery($stringQuery);



    }
    protected function saveToGraph($execution){

        /*چیزهایی که لازم دارم------------------------*/
        /*شماره پرسنلی*/
        $empId=$execution->workflow->myForm->getFieldValueByName('Field_1');
       // $empId="123";
        /*تاریخ مرخصی*/
        $date=$execution->workflow->myForm->getFieldValueByName('Field_4');

        /*تایم می خواهیم یکی شروع و دیگری پایان*/
        $timeFirstForm=$execution->workflow->myForm->getFieldValueByName('Field_5');//format like this: 18:30 or 9:20
        $timeSecondForm=$execution->workflow->myForm->getFieldValueByName('Field_6');

        $timeFirstForm = $timeFirstForm == "" ? "00:00" : $timeFirstForm;
        $timeFirstFormAr = explode(':',$timeFirstForm);
        $timeHourFirst = strlen(intval($timeFirstFormAr[0]))==1 ? "0".intval($timeFirstFormAr[0]) : intval($timeFirstFormAr[0]);
        $timeMinFirst = strlen(intval($timeFirstFormAr[1]))==1 ? "0".intval($timeFirstFormAr[1]) : intval($timeFirstFormAr[1]);

        $timeSecondForm = $timeSecondForm == "" ? "00:00" :  $timeSecondForm;
        $timeSecondFormAr = explode(':',$timeSecondForm);
        $timeHourSec = strlen(intval($timeSecondFormAr[0]))==1 ? "0".intval($timeSecondFormAr[0]) : intval($timeSecondFormAr[0]);
        $timeMinSec = strlen(intval($timeSecondFormAr[1]))==1 ? "0".intval($timeSecondFormAr[1]) : intval($timeSecondFormAr[1]);

        $timeFirst=$timeHourFirst*60+$timeMinFirst;
        $timeSecond=$timeHourSec*60+$timeMinSec;


        /*شماره فرم*/
        $docID = $execution->workflow->myForm->instanceID;
        /*تاریخ جاری برای ثبت در لاگ*/
        $docDate = Date::GregToJalali(MySQLAdapter::getInstance()->executeScalar("SELECT CreateDate From oa_document where RowID = $docID"));


        /*پایان چیزهایی که لازم دارم-----------------------*/





        $log = explode('/', $docDate);
        $log[0] = substr($log[0], 2);
        $log = $log[0] . '' . $log[1] . '' . $log[2] . 'T1000UW';

       // $st1 = "EXEC [adon].[FlowDocs_Fill] '0', '".'25'."', '".'123'."', '".'1399/05/22'."', '".'1'."', '".''."', '".'1399/05/15'."', '".'1399/05/15'."', ".'460'.", ".'620'.", ".'0'.", 1, 'WB".$docID."','WB".$docID."', '".$log."', 1";




        /*Field1:شماره ثبت است که معمولا صفر می گذاریم*/
        $reqId='0';

        /*Field2:نوع سند است*/
        /*مرخصی ساعتی:15 مرخصی روزانه:10  ماموریت ساعتی:25  ماموریت روزانه:20*/
        $formType='15';

        /*Field3:شماره پرسنلی هست*/
       // $emp='123';

        /*Field4:تاریخ درخواست هست که تاریخ جاری رو می گذاریم*/
        /*این رو باید امتحان کنم یا نگاه کنم که ماه و روز حتما باید دو رقمی باشد یا نه*/
       // $docDate='1399/05/22';

        /*Field5:نوع تردد است که مرخصی ساعتی:1  ماموریت ساعتی:2 ماموریت روزانه :150  و برای مرخصی روزانه بر اساس یک جدول می گذاریم که برای هر شرکت متغییر است*/
        /*مثلا مرخصی استحقاقی می تونه 104 باشه*/
        $tradodType='1';

        /*Field6:طول مدت هست که من خالی می زارم ولی خود سیستم قرار محاسبه کند*/
        $reqVal='';

        /*تاریخ شروع و پایان داریم که برای ساعتی ها یک عدد هست و برای روزانه ها تاریخ شروع و پایان هست*/
        /*Field7:تاریخ شروع*/
       // $startDate='1396/05/15';
        $startDate=$date;
        /*Field8:تاریخ پایان*/
        $endDate=$startDate;

        /*ساعت شروع و پایان رو داریم که برای روزانه ها صفر می دم و برای ساعتی ها یک عدد می دهم که ساعت در 60 ضرب بعلاوه دقیقه*/
        /*Field9:ساعت شروع*/
        //$startTime=540;
        $startTime=$timeFirst;

        /*Field10: ساعت خاتمه*/
        //$endTime=600;
        $endTime=$timeSecond;

        /*Field11:شهر هست که این رو صفر بدم*/
        $city=0;

        /*Field12:وضعیت سند هست که این رو همیشه 1 بدم یعنی تایید شده*/
        $docStatus=1;

        /*Field13:توضیحات سیستمی هست که برای هماهنگ بودن با اخوان اولش وب می نویسیم و بعدش شماره فرمی که باعث زدن این سند شده*/
        $commentSys="WB$docID";

        /*Field14:توضیحات کاربر هست که اخوان گفته این رو هم مثله بالا یکسان بگذارم*/
        $commentUser=$commentSys;

        /*Field15:یک لاگ هست که فرم مشخصی دارد و بصورت زیر می دهیم*/
        /*930208T1000UW    شامل تاریخ،ساعت و اختصار کاربر*/
        /*تاریخ که معلومه بقیه رو ثابت به آن می چسبونیم*/
       //$log=$dateForLog."T1000UW";

        /*Field16:یک فلگ هست که می گه در صورت تداخل این سند رو ثبت کند یا نه */
        /*اخوان 1 گذاشته ولی باید ازش سوال کنم که کی این می تونه یک نباشه*/
        $flag=1;


        $sqlString = "EXEC [adon].[FlowDocs_Fill] '$reqId', '$formType', '$empId', '$docDate', '$tradodType', '$reqVal', '$startDate', '$endDate', $startTime,$endTime, $city, $docStatus, '$commentSys','$commentUser', '$log', $flag";
        return $sqlString;

    }
    protected function runStringQuery($stringQuery){
        $xml = <<<XML
<soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
<soap12:Body>
    <RunSelectQuery xmlns="http://tempuri.org/">
    <username>3ef1b48067e4f2ac9913141d77e847dd</username>
    <password>9a3f5b14f1737c15e86680d9cd40b840</password>
    <objStr>$stringQuery</objStr>
    </RunSelectQuery>
</soap12:Body>
</soap12:Envelope>
XML;
        $WSDL = "http://46.209.31.219:8050/Timex.asmx?wsdl";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_URL, $WSDL);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml", "Content-length: ".strlen($xml), "Connection: close"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        $res = curl_exec($ch);
        $res = str_replace('xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"','',$res);
        $res = <<<XML
$res
XML;

        $res = simplexml_load_string($res);
        $res = json_encode($res);
        Response::getInstance()->response =$res;

    }
}

