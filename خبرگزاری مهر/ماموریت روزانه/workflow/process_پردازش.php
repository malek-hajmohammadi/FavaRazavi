<?php

class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {


        $docId=$execution->workflow->myForm->instanceID;
        $docDate = Date::GregToJalali(WFPDOAdapter::getInstance()->executeScalar("SELECT CreateDate From oa_document where RowID = $docId"));

        $user = $execution->workflow->myForm->getFieldValueByName('Field_0');
        $uid = $user[0]['uid'];
        $db = WFPDOAdapter::getInstance();
        $sql2 = "SELECT fname,lname,sex,employeeID FROM oa_users WHERE UserID=" . $uid;
        $db->executeSelect($sql2);
        $person = $db->fetchAssoc();
        $empId = $person['employeeID'];

        $dateR = $execution->workflow->myForm->getFieldValueByName('Field_2');
        $dateB = $execution->workflow->myForm->getFieldValueByName('Field_3');

        $res=$this->mamooriatRoozaneh($empId,$docDate,$dateR,$dateB);
        if($res) {
            $this->updateGraph($empId,$dateR,$dateB);
            $execution->setVariable('status', 1);
        }
        else{
            $execution->setVariable('status', 0);
        }


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

    private function updateGraph($personalId,$dateRaft,$dateBargasht){
        $client = new SoapClient('http://192.168.100.46:8080/IOProcessService.asmx?wsdl');
        $param = $client->DoProcess(array('EmpFilter' => $personalId, 'StartProcessDate' => $dateRaft, 'EndProcessDate' => $dateBargasht, 'BranchFilter' => 1, 'UserID' => 1));
        $res= $param->DoProcessResult;
        return $res;

    }




}



///////////////////////////////////////////////////////////
/*
class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {
        $docID = $execution->workflow->myForm->instanceID;
        $docDate = Date::GregToJalali(WFPDOAdapter::getInstance()->executeScalar("SELECT CreateDate From oa_document where RowID = $docID"));
        $FormType = 10;
        //   $emp = $execution->workflow->myForm->getFieldValueByName('Field_11');
        $user = $execution->workflow->myForm->getFieldValueByName('Field_0');
        $uid = $user[0]['uid'];
        $db = WFPDOAdapter::getInstance();
        $sql2 = "SELECT fname,lname,sex,employeeID FROM oa_users WHERE UserID=" . $uid;
        $db->executeSelect($sql2);
        $person = $db->fetchAssoc();
        $emp = $person['employeeID'];

        $FormType2 = 150;

        $ModatMorkhasi = '1';
        $dateR = $execution->workflow->myForm->getFieldValueByName('Field_2');
        $dateB = $execution->workflow->myForm->getFieldValueByName('Field_3');
        $dateB = Date::GregToJalali((new DateTime(Date::JalaliToGreg($dateB)))->sub(new DateInterval("P1D"))->format('Y-m-d'));
        $timeR = 0;
        $timeB = 0;
        $city = 1101;
        $log = explode('/', $docDate);
        $log[0] = substr($log[0], 2);
        $log = $log[0] . '' . $log[1] . '' . $log[2] . 'T1000UW';
        $execution->workflow->myForm->setFieldValueByName('Field_8', '8');

        try {
            $client = new SoapClient('http://192.168.100.46:85/TimeX.asmx?wsdl');

            $s1 = "EXEC [adon].[FlowDocs_Fill] '0', '" . $FormType . "', '" . $emp . "', '" . $docDate . "', '" . $FormType2 . "', '" . $ModatMorkhasi . "', '" . $dateR . "', '" . $dateB . "', " . $timeR . ", " . $timeB . ", " . $city . ", 1, 'WB" . $docID . "','WB" . $docID . "', '" . $log . "', 1";
            $param1 = array(
                'username' => '3ef1b48067e4f2ac9913141d77e847dd',
                'password' => '9a3f5b14f1737c15e86680d9cd40b840',
                'objStr'   => $s1
            );
            $resp1 = $client->RunQuery($param1);
            $recs1 = $resp1->RunQueryResult;
            $recs1 = json_decode(json_encode($recs1), true);
            $aaa = '1';
            if ($recs1 == 'true' || $recs1 == true) {
                $execution->setVariable('status', 1);
            } else {
                $execution->setVariable('status', 0);
            }
        } catch (SoapFaultException $e) {
            $execution->setVariable('status', 0);
            $e = $e->getMessage();
        } catch (Exception $e) {
            $execution->setVariable('status', 0);
            $e = $e->getMessage();
        }
//$execution->workflow->myForm->setFieldValueByName('Field_12','recs1:'.$recs1.'-Query:'.$s1.'-e:'.$e);
    }
}
*/

