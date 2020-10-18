<?php

class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {
        /*
        statuslevel=3 غلط تورال
        statuslevel=4 درست تورال
        */


        $returnValueFromGraph= self::insertDailyMissionToGraph($execution);
        if($returnValueFromGraph){
            $execution->setVariable('statuslevel', 4);
            $execution->workflow->myForm->setFieldValueByName('Field_8', '4 / گراف ثبت شد');
        } else {
            $execution->setVariable('statuslevel', 3);
            $execution->workflow->myForm->setFieldValueByName('Field_8', '3 / گراف ثبت نشد');
        }

    }


    protected function insertDailyMissionToGraph($execution)
    {
        ////from other form///
        /*چیزهایی که لازم دارم------------------------*/
        /*شماره پرسنلی*/
        //$empId=$execution->workflow->myForm->getFieldValueByName('Field_1');
        $GID = $execution->workflow->myForm->getFieldValueByName('Field_5');/*کد پرسنلی در گراف*/


        // $empId="123";
        /*تاریخ مرخصی*/
        //$date=$execution->workflow->myForm->getFieldValueByName('Field_4');
        $dateR = $execution->workflow->myForm->getFieldValueByName('Field_2'); //date raft
        $dateB = $execution->workflow->myForm->getFieldValueByName('Field_3'); //date bargasht


        /*شماره فرم*/
        $docID = $execution->workflow->myForm->instanceID;
        /*تاریخ جاری برای ثبت در لاگ*/
        $docDate = Date::GregToJalali(MySQLAdapter::getInstance()->executeScalar("SELECT CreateDate From oa_document where RowID = $docID"));


        /*پایان چیزهایی که لازم دارم-----------------------*/




        // $st1 = "EXEC [adon].[FlowDocs_Fill] '0', '".'25'."', '".'123'."', '".'1399/05/22'."', '".'1'."', '".''."', '".'1399/05/15'."', '".'1399/05/15'."', ".'460'.", ".'620'.", ".'0'.", 1, 'WB".$docID."','WB".$docID."', '".$log."', 1";

        /*Field1:شماره ثبت است که یک مقدار خروجی است*/
        $reqId='';

        /*Field2:نوع سند است*/
        /*مرخصی ساعتی:15 مرخصی روزانه:10  ماموریت ساعتی:25  ماموریت روزانه:20*/
        /*نوع مرخصی همه جا ثابت است ولی نوع تردد متفاوت است*/
        $formType='20'; //daily mission

        /*Field3:شماره پرسنلی هست*/
        // $emp='123';

        /*Field4:تاریخ درخواست هست که تاریخ جاری رو می گذاریم*/
        /*این رو باید امتحان کنم یا نگاه کنم که ماه و روز حتما باید دو رقمی باشد یا نه*/
        // $docDate='1399/05/22';

        /*Field5:نوع تردد است که مرخصی ساعتی:1  ماموریت ساعتی:2 ماموریت روزانه :150  و برای مرخصی روزانه بر اساس یک جدول می گذاریم که برای هر شرکت متغییر است*/
        /*مثلا مرخصی استحقاقی می تونه 104 باشه*/
         $tradodType=150;/*for daily mission*/
        /*ورودی تابع می گیرم*/

        /*Field6:طول مدت هست که من خالی می زارم ولی خود سیستم قرار محاسبه کند*/
        $reqVal='';

        /*تاریخ شروع و پایان داریم که برای ساعتی ها یک عدد هست و برای روزانه ها تاریخ شروع و پایان هست*/
        /*Field7:تاریخ شروع*/
        // $startDate='1396/05/15';
        $startDate=$dateR;
        /*Field8:تاریخ پایان*/
        $endDate=$dateB;

        /*ساعت شروع و پایان رو داریم که برای روزانه ها صفر می دم و برای ساعتی ها یک عدد می دهم که ساعت در 60 ضرب بعلاوه دقیقه*/
        /*Field9:ساعت شروع*/
        //$startTime=540;
        $startTime=0;

        /*Field10: ساعت خاتمه*/
        //$endTime=600;
        $endTime=0;

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
        $log = explode('/', $docDate);
        $log[0] = substr($log[0], 2);
        $log = $log[0] . '' . $log[1] . '' . $log[2] . 'T1000UW';

        /*Field16:یک فلگ هست که می گه در صورت تداخل این سند رو ثبت کند یا نه */
        /*اخوان 1 گذاشته ولی باید ازش سوال کنم که کی این می تونه یک نباشه*/
        $flag=1;




        $sqlString = "EXEC [Timex_TaminPoshtibani].[adon].[FlowDocs_Fill] '$reqId', '$formType', $GID, '$docDate', $tradodType, '$reqVal', '$startDate', '$endDate', $startTime,$endTime, $city, $docStatus, '$commentSys','$commentUser', '$log', $flag";

        /*در فرم درست شده است*/
        //EXEC [adon].[FlowDocs_Fill] '', '10', 882866, '1399/06/19', 104, '', '1399/06/20', '1399/06/20', 0,0, 0, 1, 'WB13446619','WB13446619', '990619T1000UW', 1
        //$execution->workflow->myForm->setFieldValueByName('Field_13', $sqlString);//That was for test

        $client = new SoapClient('http://10.10.100.15/WSTuralInOut/TuralInOut.asmx?wsdl');
        $param = array(
            'username' => '3ef1b48067e4f2ac9913141d77e847dd',
            'password' => '9a3f5b14f1737c15e86680d9cd40b840',
            'objStr' => $sqlString
        );
        $res = $client->RunQuery($param);

        //show to querySTring into Commment for test
        //$execution->workflow->myForm->setFieldValueByName('Field_13', $sqlString);

        $res = $res->RunQueryResult;
        $res = json_decode(json_encode($res), true);

        return $res;


        //just for test
        //$temp= $execution->workflow->myForm->getFieldValueByName('Field_13', $sqlString);
        //$execution->workflow->myForm->setFieldValueByName('Field_13', $temp."\r\n".$res);


        // $st1 = "EXEC [Timex_TaminPoshtibani].[adon].[FlowDocs_Fill] '$reqIdOutput', 10, 882866, '1399/06/17',104 , '','1399/05/15','1399/05/15', 0,0, 0, 1, 'WB123','WB123', '990616T120512UW', 1";

       // return $sqlString;
        /// end from other form//

    }

}


