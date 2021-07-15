<?php
class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {
        return $this->sendToGraph($execution);

    }



    private function sendToGraph(ezcWorkflowExecution $execution){


        /*
         * شماره ثبت است که یک مقدار خروجی است
         * */
        $reqId='';

        /*
         * نوع سند است
         * مرخصی ساعتی:15 مرخصی روزانه:10  ماموریت ساعتی:25  ماموریت روزانه:20
         * نوع مرخصی همه جا ثابت است ولی نوع تردد متفاوت است
         * */
        $formType='15';

        /*
         * شماره پرسنلی شخص هست که باید در گراف هم همین باشد
         * "123"
         * $execution->workflow->myForm->getFieldValueByName('Field_5')
         * */

        $geraphId=$execution->workflow->myForm->getFieldValueByName('Field_5');
        //$geraphId=substr($geraphId,4);
        //$geraphId="850";

        /*
         * تاریخ درخواست هست که تاریخ جاری رو می گذاریم
         * '1399/05/22'
         * البته مطمئن نیستم که ماه و روز حتما باید دو رقمی باشد یا نه
         *
         * شماره فرم
        $docID = $execution->workflow->myForm->instanceID;
        *تاریخ جاری برای ثبت در لاگ
        * $docDate = Date::GregToJalali(WFPDOAdapter::getInstance()->executeScalar("SELECT CreateDate From oa_document where RowID = $docID"));
         * */
        $docID = $execution->workflow->myForm->instanceID;
        $docDate = Date::GregToJalali(WFPDOAdapter::getInstance()->executeScalar("SELECT CreateDate From oa_document where RowID = $docID"));

        //$docDate="1399/05/22";

        /*
         * نوع تردد است که مرخصی ساعتی:1  ماموریت ساعتی:2 ماموریت روزانه :150
         * و برای مرخصی روزانه بر اساس یک جدول می گذاریم که برای هر شرکت متغییر است
         * مثلا مرخصی استحقاقی می تونه 104 باشه
         * */

        $taradodType='1';

        /*
         * طول مدت مرخصی هست که اگر خالی رد بشه
         * خود سیستم نادیده می گیره و خودش حساب می کنه
         * */
        $reqVal=0;

        /*
         * تاریخ شروع با فرمت 1396/05/15 است
         * تاریخ ها برای مرخصی ساعتی یکسان هستند
         *
         *  $dateR = $execution->workflow->myForm->getFieldValueByName('Field_2'); //date raft
        $dateB = $execution->workflow->myForm->getFieldValueByName('Field_3'); //date bargasht
         *
         * */
        $startDate = $execution->workflow->myForm->getFieldValueByName('Field_2');
        //$startDate='1399/12/11';



        $endDate=$startDate;

        /*
         * یک عدد هست از ضرب ساعت بعلاوه دقیقه
         * برای مرخصی روزانه صفر می گذاریم
         * $startTime=540;
         * */
        $startTime=$execution->workflow->myForm->getFieldValueByName('Field_3');
        $startTimeArray = explode(':', $startTime);


        $startTime=$startTimeArray[0]*60+$startTimeArray[1];

        /*
         * یک عدد هست از ضرب ساعت بعلاوه دقیقه
         * برای مرخصی روزانه صفر می گذاریم
         * */
        $endTime=$execution->workflow->myForm->getFieldValueByName('Field_4');
        $endTimeArray = explode(':', $endTime);
        $endTime=$endTimeArray[0]*60+$endTimeArray[1];

        /*
         * شهر هست که معمولا صفر می دهیم
         * */
        $city=0;

        /*
         * وضعیت سند هست که همیشه یک می گذاریم
         * */
        $docStatus=1;

        /*
         * توضیحات سیستمی هست که اون رو به صورت اختیاری با WB شروع می کنیم
         * و بعد شماره فرم رو در آن می گذاریم
         *
         *  $docID = $execution->workflow->myForm->instanceID;
         * $commentSys="WB$docID"
         *
         * */
        $commentSys="WB$docID";
        //$commentSys="WB1234";

        /*
         * توضیحات کاربری است که می تواند مانند بالایی باشد
         * */
        $commentUser=$commentSys;

        /*
         * یک لاگ با قالب مشخص زیر هست
         * 930208T1000UW    شامل تاریخ،ساعت و یک سری حروف ثابت
         * ."T1000UW"
         * */
        $log = explode('/', $docDate);
        $log[0] = substr($log[0], 2);
        $log = $log[0] . '' . $log[1] . '' . $log[2] . 'T1000UW';

        //$log="000125T1000UW";

        /*
         * یک flag هست که میگه در صورت تداخل این سند رو ثبت کند یا نه
         * و معمولا یک می گذاریم
         * */
        $flag=1;

        $query = "EXEC [adon].[FlowDocs_Fill] '$reqId', '$formType', $geraphId,
         '$docDate', $taradodType, '$reqVal', '$startDate', '$endDate', $startTime,$endTime, $city,
          $docStatus, '$commentSys','$commentUser', '$log', $flag";


        $execution->workflow->myForm->setFieldValueByName('Field_5',$query);


        return $this->runService($query);

    }

    private function runService($query){

        $client = new SoapClient("http://192.168.110.119:82/TimeX.asmx?wsdl");
        $param = array(
            'username' => '3ef1b48067e4f2ac9913141d77e847dd',
            'password' => '9a3f5b14f1737c15e86680d9cd40b840',
            'objStr' => $query
        );
        $res = $client->RunQuery($param);

        return $res;
    }

}

