<?php

class MainAjax
{


    public function main()
    {
        //return $this->morakhasiRoozaneh();
        //return $this->morakhasiSaati();
        //return $this->mamooriatRoozahen();
        return $this->call_mamooriatRoozaneh();
        //return $this->mamooriatSaati();
        //return $this->sabtTaradod();


    }

    private function sabtTaradod()
    {

        /*
        * شماره پرسنلی شخص هست که باید در گراف هم همین باشد
        * "123"
        * $execution->workflow->myForm->getFieldValueByName('Field_5')
        * */
        $geraphId = "850";

        /*
        * تاریخ  با فرمت 1396/05/15 است

        *
        *  $dateR = $execution->workflow->myForm->getFieldValueByName('Field_2'); //date
        * */
        $date = '1399/12/02';

        /*
        * یک عدد هست از ضرب ساعت بعلاوه دقیقه
        * برای مرخصی روزانه صفر می گذاریم
        * $startTime=540;
        * */
        $time = 600;

        /*
       * توضیحات سیستمی هست که اون رو به صورت اختیاری با WB شروع می کنیم
       * و بعد شماره فرم رو در آن می گذاریم
       *
       *  $docID = $execution->workflow->myForm->instanceID;
       * $commentSys="WB$docID"
       *
       * */
        $commentSys = "WB1234";

        $type = "0";


        $query = "EXEC [adon].[IOData_ins] " . $geraphId . ", '" . $date . "', " . $time . ", " . $type . ",'$commentSys';";

        return $this->runService($query);

    }

    private function morakhasiRoozaneh()
    {


        /*
         * شماره ثبت است که یک مقدار خروجی است
         * */
        $reqId = '';

        /*
         * نوع سند است
         * مرخصی ساعتی:15 مرخصی روزانه:10  ماموریت ساعتی:25  ماموریت روزانه:20
         * نوع مرخصی همه جا ثابت است ولی نوع تردد متفاوت است
         * */
        $formType = '10';

        /*
         * شماره پرسنلی شخص هست که باید در گراف هم همین باشد
         * "123"
         * $execution->workflow->myForm->getFieldValueByName('Field_5')
         * */
        $geraphId = "850";

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
        $docDate = "1399/05/22";

        /*
         * نوع تردد است که مرخصی ساعتی:1  ماموریت ساعتی:2 ماموریت روزانه :150
         * و برای مرخصی روزانه بر اساس یک جدول می گذاریم که برای هر شرکت متغییر است
         * مثلا مرخصی استحقاقی می تونه 104 باشه
         * */
        $taradodType = '104';

        /*
         * طول مدت مرخصی هست که اگر خالی رد بشه
         * خود سیستم نادیده می گیره و خودش حساب می کنه
         * */
        $reqVal = 0;

        /*
         * تاریخ شروع با فرمت 1396/05/15 است
         * تاریخ ها برای مرخصی ساعتی یکسان هستند
         *
         *  $dateR = $execution->workflow->myForm->getFieldValueByName('Field_2'); //date raft
        $dateB = $execution->workflow->myForm->getFieldValueByName('Field_3'); //date bargasht
         *
         * */
        $startDate = '1399/12/02';


        $endDate = '1399/12/03';

        /*
         * یک عدد هست از ضرب ساعت بعلاوه دقیقه
         * برای مرخصی روزانه صفر می گذاریم
         * $startTime=540;
         * */
        $startTime = 0;

        /*
         * یک عدد هست از ضرب ساعت بعلاوه دقیقه
         * برای مرخصی روزانه صفر می گذاریم
         * */
        $endTime = 0;

        /*
         * شهر هست که معمولا صفر می دهیم
         * */
        $city = 0;

        /*
         * وضعیت سند هست که همیشه یک می گذاریم
         * */
        $docStatus = 1;

        /*
         * توضیحات سیستمی هست که اون رو به صورت اختیاری با WB شروع می کنیم
         * و بعد شماره فرم رو در آن می گذاریم
         *
         *  $docID = $execution->workflow->myForm->instanceID;
         * $commentSys="WB$docID"
         *
         * */
        $commentSys = "WB1234";

        /*
         * توضیحات کاربری است که می تواند مانند بالایی باشد
         * */
        $commentUser = "WB1234";

        /*
         * یک لاگ با قالب مشخص زیر هست
         * 930208T1000UW    شامل تاریخ،ساعت و یک سری حروف ثابت
         * ."T1000UW"
         * */
        $log = "000125T1000UW";

        /*
         * یک flag هست که میگه در صورت تداخل این سند رو ثبت کند یا نه
         * و معمولا یک می گذاریم
         * */
        $flag = 1;

        $query = "EXEC [adon].[FlowDocs_Fill] '$reqId', '$formType', $geraphId,
         '$docDate', $taradodType, '$reqVal', '$startDate', '$endDate', $startTime,$endTime, $city,
          $docStatus, '$commentSys','$commentUser', '$log', $flag";
        return $this->runService($query);

    }

    private function morakhasiSaati()
    {


        /*
         * شماره ثبت است که یک مقدار خروجی است
         * */
        $reqId = '';

        /*
         * نوع سند است
         * مرخصی ساعتی:15 مرخصی روزانه:10  ماموریت ساعتی:25  ماموریت روزانه:20
         * نوع مرخصی همه جا ثابت است ولی نوع تردد متفاوت است
         * */
        $formType = '15';

        /*
         * شماره پرسنلی شخص هست که باید در گراف هم همین باشد
         * "123"
         * $execution->workflow->myForm->getFieldValueByName('Field_5')
         * */
        $geraphId = "850";

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
        $docDate = "1399/05/22";

        /*
         * نوع تردد است که مرخصی ساعتی:1  ماموریت ساعتی:2 ماموریت روزانه :150
         * و برای مرخصی روزانه بر اساس یک جدول می گذاریم که برای هر شرکت متغییر است
         * مثلا مرخصی استحقاقی می تونه 104 باشه
         * */
        $taradodType = '1';

        /*
         * طول مدت مرخصی هست که اگر خالی رد بشه
         * خود سیستم نادیده می گیره و خودش حساب می کنه
         * */
        $reqVal = 0;

        /*
         * تاریخ شروع با فرمت 1396/05/15 است
         * تاریخ ها برای مرخصی ساعتی یکسان هستند
         *
         *  $dateR = $execution->workflow->myForm->getFieldValueByName('Field_2'); //date raft
        $dateB = $execution->workflow->myForm->getFieldValueByName('Field_3'); //date bargasht
         *
         * */
        $startDate = '1399/12/10';


        $endDate = '1399/12/10';

        /*
         * یک عدد هست از ضرب ساعت بعلاوه دقیقه
         * برای مرخصی روزانه صفر می گذاریم
         * $startTime=540;
         * */
        $startTime = 480;

        /*
         * یک عدد هست از ضرب ساعت بعلاوه دقیقه
         * برای مرخصی روزانه صفر می گذاریم
         * */
        $endTime = 530;

        /*
         * شهر هست که معمولا صفر می دهیم
         * */
        $city = 0;

        /*
         * وضعیت سند هست که همیشه یک می گذاریم
         * */
        $docStatus = 1;

        /*
         * توضیحات سیستمی هست که اون رو به صورت اختیاری با WB شروع می کنیم
         * و بعد شماره فرم رو در آن می گذاریم
         *
         *  $docID = $execution->workflow->myForm->instanceID;
         * $commentSys="WB$docID"
         *
         * */
        $commentSys = "WB1234";

        /*
         * توضیحات کاربری است که می تواند مانند بالایی باشد
         * */
        $commentUser = "WB1234";

        /*
         * یک لاگ با قالب مشخص زیر هست
         * 930208T1000UW    شامل تاریخ،ساعت و یک سری حروف ثابت
         * ."T1000UW"
         * */
        $log = "000125T1000UW";

        /*
         * یک flag هست که میگه در صورت تداخل این سند رو ثبت کند یا نه
         * و معمولا یک می گذاریم
         * */
        $flag = 1;

        $query = "EXEC [adon].[FlowDocs_Fill] '$reqId', '$formType', $geraphId,
         '$docDate', $taradodType, '$reqVal', '$startDate', '$endDate', $startTime,$endTime, $city,
          $docStatus, '$commentSys','$commentUser', '$log', $flag";
        return $this->runService($query);

    }

    private function call_mamooriatRoozaneh(){

        $geraphId = "332038";
        $docDate = "1400/03/22";
        $startDate = '1400/02/25';
        $endDate = '1400/02/25';
        $res=$this->mamooriatRoozaneh($geraphId,$docDate,$startDate,$endDate);
        return $res;

    }
    private function mamooriatRoozaneh($geraphId,$docDate,$startDate,$endDate)
    {


        /*
         * شماره ثبت است که یک مقدار خروجی است
         * */
        $reqId = '';

        /*
         * نوع سند است
         * مرخصی ساعتی:15 مرخصی روزانه:10  ماموریت ساعتی:25  ماموریت روزانه:20
         * نوع مرخصی همه جا ثابت است ولی نوع تردد متفاوت است
         * */
        $formType = '20';

        /*
         * شماره پرسنلی شخص هست که باید در گراف هم همین باشد
         * "123"
         * $execution->workflow->myForm->getFieldValueByName('Field_5')
         * */
       // $geraphId = "332038";

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
        //$docDate = "1400/03/22";

        /*
         * نوع تردد است که مرخصی ساعتی:1  ماموریت ساعتی:2 ماموریت روزانه :150
         * و برای مرخصی روزانه بر اساس یک جدول می گذاریم که برای هر شرکت متغییر است
         * مثلا مرخصی استحقاقی می تونه 104 باشه
         * */
        $taradodType = '150';

        /*
         * طول مدت مرخصی هست که اگر خالی رد بشه
         * خود سیستم نادیده می گیره و خودش حساب می کنه
         * */
        $reqVal = 0;

        /*
         * تاریخ شروع با فرمت 1396/05/15 است
         * تاریخ ها برای مرخصی ساعتی یکسان هستند
         *
         *  $dateR = $execution->workflow->myForm->getFieldValueByName('Field_2'); //date raft
        $dateB = $execution->workflow->myForm->getFieldValueByName('Field_3'); //date bargasht
         *
         * */
        //$startDate = '1400/02/24';


       // $endDate = '1400/02/24';

        /*
         * یک عدد هست از ضرب ساعت بعلاوه دقیقه
         * برای مرخصی روزانه صفر می گذاریم
         * $startTime=540;
         * */
        $startTime = 0;

        /*
         * یک عدد هست از ضرب ساعت بعلاوه دقیقه
         * برای مرخصی روزانه صفر می گذاریم
         * */
        $endTime = 0;

        /*
         * شهر هست که معمولا صفر می دهیم
         * */
        $city = 0;

        /*
         * وضعیت سند هست که همیشه یک می گذاریم
         * */
        $docStatus = 1;

        /*
         * توضیحات سیستمی هست که اون رو به صورت اختیاری با WB شروع می کنیم
         * و بعد شماره فرم رو در آن می گذاریم
         *
         *  $docID = $execution->workflow->myForm->instanceID;
         * $commentSys="WB$docID"
         *
         * */
        $commentSys = "WB1234";

        /*
         * توضیحات کاربری است که می تواند مانند بالایی باشد
         * */
        $commentUser = "WB1234";

        /*
         * یک لاگ با قالب مشخص زیر هست
         * 930208T1000UW    شامل تاریخ،ساعت و یک سری حروف ثابت
         * ."T1000UW"
         * */
        $log = "000125T1000UW";

        /*
         * یک flag هست که میگه در صورت تداخل این سند رو ثبت کند یا نه
         * و معمولا یک می گذاریم
         * */
        $flag = 1;

        $query = "EXEC [adon].[FlowDocs_Fill] '$reqId', '$formType', $geraphId,
         '$docDate', $taradodType, '$reqVal', '$startDate', '$endDate', $startTime,$endTime, $city,
          $docStatus, '$commentSys','$commentUser', '$log', $flag";
        return $this->runService($query);

    }

    private function mamooriatSaati()
    {


        /*
         * شماره ثبت است که یک مقدار خروجی است
         * */
        $reqId = '';

        /*
         * نوع سند است
         * مرخصی ساعتی:15 مرخصی روزانه:10  ماموریت ساعتی:25  ماموریت روزانه:20
         * نوع مرخصی همه جا ثابت است ولی نوع تردد متفاوت است
         * */
        $formType = '25';

        /*
         * شماره پرسنلی شخص هست که باید در گراف هم همین باشد
         * "123"
         * $execution->workflow->myForm->getFieldValueByName('Field_5')
         * */
        $geraphId = "850";

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
        $docDate = "1399/05/22";

        /*
         * نوع تردد است که مرخصی ساعتی:1  ماموریت ساعتی:2 ماموریت روزانه :150
         * و برای مرخصی روزانه بر اساس یک جدول می گذاریم که برای هر شرکت متغییر است
         * مثلا مرخصی استحقاقی می تونه 104 باشه
         * */
        $taradodType = '2';

        /*
         * طول مدت مرخصی هست که اگر خالی رد بشه
         * خود سیستم نادیده می گیره و خودش حساب می کنه
         * */
        $reqVal = 0;

        /*
         * تاریخ شروع با فرمت 1396/05/15 است
         * تاریخ ها برای مرخصی ساعتی یکسان هستند
         *
         *  $dateR = $execution->workflow->myForm->getFieldValueByName('Field_2'); //date raft
        $dateB = $execution->workflow->myForm->getFieldValueByName('Field_3'); //date bargasht
         *
         * */
        $startDate = '1399/12/12';


        $endDate = '1399/12/12';

        /*
         * یک عدد هست از ضرب ساعت بعلاوه دقیقه
         * برای مرخصی روزانه صفر می گذاریم
         * $startTime=540;
         * */
        $startTime = 480;

        /*
         * یک عدد هست از ضرب ساعت بعلاوه دقیقه
         * برای مرخصی روزانه صفر می گذاریم
         * */
        $endTime = 530;

        /*
         * شهر هست که معمولا صفر می دهیم
         * */
        $city = 0;

        /*
         * وضعیت سند هست که همیشه یک می گذاریم
         * */
        $docStatus = 1;

        /*
         * توضیحات سیستمی هست که اون رو به صورت اختیاری با WB شروع می کنیم
         * و بعد شماره فرم رو در آن می گذاریم
         *
         *  $docID = $execution->workflow->myForm->instanceID;
         * $commentSys="WB$docID"
         *
         * */
        $commentSys = "WB1234";

        /*
         * توضیحات کاربری است که می تواند مانند بالایی باشد
         * */
        $commentUser = "WB1234";

        /*
         * یک لاگ با قالب مشخص زیر هست
         * 930208T1000UW    شامل تاریخ،ساعت و یک سری حروف ثابت
         * ."T1000UW"
         * */
        $log = "000125T1000UW";

        /*
         * یک flag هست که میگه در صورت تداخل این سند رو ثبت کند یا نه
         * و معمولا یک می گذاریم
         * */
        $flag = 1;

        $query = "EXEC [adon].[FlowDocs_Fill] '$reqId', '$formType', $geraphId,
         '$docDate', $taradodType, '$reqVal', '$startDate', '$endDate', $startTime,$endTime, $city,
          $docStatus, '$commentSys','$commentUser', '$log', $flag";
        return $this->runService($query);

    }



    private function runService($query)
    {

        $client = new SoapClient('http://192.168.100.46:85/TimeX.asmx?wsdl');
        $param = array(
            'username' => '3ef1b48067e4f2ac9913141d77e847dd',
            'password' => '9a3f5b14f1737c15e86680d9cd40b840',
            'objStr'   => $query
        );
        $res = $client->RunQuery($param);

        return $res;

    }


}

$mainAjax = new MainAjax();
Response::getInstance()->response = $mainAjax->main();
return $mainAjax;

///////////////////////////////////////////


class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution, ezcWorkflowNodeAction $caller = null)
    {
        $docID = $execution->workflow->myForm->instanceID;
        $docDate = Date::GregToJalali(WFPDOAdapter::getInstance()->executeScalar("SELECT CreateDate From oa_document where RowID = $docID"));

        $PID = $execution->workflow->myForm->getFieldValueByName('Field_2');
        $DateR = $execution->workflow->myForm->getFieldValueByName('Field_5');
        $DateB = $execution->workflow->myForm->getFieldValueByName('Field_5');

        $TimeR = $execution->workflow->myForm->getFieldValueByName('Field_6');
        $TimeR = explode(':', date('H:i', strtotime($TimeR)));
        $TimeR = ($TimeR[0] * 60) + $TimeR[1];
        $TimeB = $execution->workflow->myForm->getFieldValueByName('Field_7');
        $TimeB = explode(':', date('H:i', strtotime($TimeB)));
        $TimeB = ($TimeB[0] * 60) + $TimeB[1];
        $Modat = abs(intval($TimeB) - intval($TimeR));

        $log = explode('/', $docDate);
        $log[0] = substr($log[0], 2);
        $log = $log[0] . $log[1] . $log[2] . 'T000000UW';

        try {
            $client = new SoapClient('http://192.168.100.46:85/TimeX.asmx?wsdl');
            $FormType = $execution->workflow->myForm->getFieldValueByName('Field_3');
            switch ($FormType) {
                case 0:  // مجوز اضافه کاری
                    $Desc = 'Form-Ez-' . $docID;
                    $Query = "EXEC [adon].[IOMnuList_Fill] $PID, '$docDate','$DateR','$DateB', 'ALL', 10, $Modat, 1, '$log', '$Desc';";
                    $Query .= "EXEC [adon].[IOMnuList_Fill] $PID, '$docDate','$DateR','$DateB', 'ALL', 80, $Modat, 1, '$log', '$Desc';";
                    $Query .= "EXEC [adon].[IOMnuList_Fill] $PID, '$docDate','$DateR','$DateB', 'ALL', 85, $Modat, 1, '$log', '$Desc';";
                    $Query .= "EXEC [adon].[IOMnuList_Fill] $PID, '$docDate','$DateR','$DateB', 'ALL', 90, $Modat, 1, '$log', '$Desc';";
                    break;
                case 1: // ثبت تردد
                    $Desc = 'Form-Sa-' . $docID;
                    $Query = "EXEC [adon].[IOData_ins] $PID, '$DateR', $TimeR, 0, '$Desc';";
                    $Query .= "EXEC [adon].[IOData_ins] $PID, '$DateR', $TimeB, 0, '$Desc';";
                    break;
                case 2:  // مجوز اضافه کاری و ثبت تردد
                    $Desc = 'Form-SaEz-' . $docID;
                    $Query = "EXEC [adon].[IOMnuList_Fill] $PID, '$docDate','$DateR','$DateB', 'ALL', 10, $Modat, 1, '$log', '$Desc';";
                    $Query .= "EXEC [adon].[IOMnuList_Fill] $PID, '$docDate','$DateR','$DateB', 'ALL', 80, $Modat, 1, '$log', '$Desc';";
                    $Query .= "EXEC [adon].[IOMnuList_Fill] $PID, '$docDate','$DateR','$DateB', 'ALL', 85, $Modat, 1, '$log', '$Desc';";
                    $Query .= "EXEC [adon].[IOMnuList_Fill] $PID, '$docDate','$DateR','$DateB', 'ALL', 90, $Modat, 1, '$log', '$Desc';";
                    $Query .= "EXEC [adon].[IOData_ins] $PID, '$DateR', $TimeR, 0, '$Desc';";
                    $Query .= "EXEC [adon].[IOData_ins] $PID, '$DateR', $TimeB, 0, '$Desc';";
                    break;
            }
            $param = array(
                'username' => '3ef1b48067e4f2ac9913141d77e847dd',
                'password' => '9a3f5b14f1737c15e86680d9cd40b840',
                'objStr'   => $Query
            );
            $res = $client->RunQuery($param);
            $res = $res->RunQueryResult;
            $res = json_decode(json_encode($res), true);

            if ($res == 'true' || $res == true) {
                $execution->setVariable('status', 1);
                $res = 'TrueTrue';

                $client = new SoapClient('http://192.168.100.46:8080/IOProcessService.asmx?wsdl');
                $param = $client->DoProcess(array('EmpFilter' => $PID, 'StartProcessDate' => $DateR, 'EndProcessDate' => $DateB, 'BranchFilter' => 1, 'UserID' => 1));
                $res .= $param->DoProcessResult;
            } else {
                $execution->setVariable('status', 0);
                $res = 'TrueFalse';
            }
        } catch (SoapFaultException $e) {
            $execution->setVariable('status', 0);
            $res = 'FalseSoapFaultException: ' . $e->getMessage();
        } catch (Exception $e) {
            $execution->setVariable('status', 0);
            $res = 'FalseException: ' . $e->getMessage();
        }
        $execution->workflow->myForm->setFieldValueByName('Field_10', 'Query: ' . $Query . ' - res: ' . $res);
    }
}

