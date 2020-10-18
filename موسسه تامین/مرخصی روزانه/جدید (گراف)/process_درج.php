<?php

class calssName
{
    protected $mondehMorakhasiShow; //مانده مرخصی بصورت نمایشی//
    protected $mondehMorakhasiNum;//مانده مرخصی عددی برای مقایسه و به ساعت//

    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution){

            self::getMondehMorakhasi($execution);
            $execution->workflow->myForm->setFieldValueByName('Field_15', $this->mondehMorakhasiShow);

            if(self::checkMondehMorakhasiAfterThisForm($execution))
                self::setCommuteToGraph($execution);
            else
                $execution->setVariable('statuslevel', 90);//MondehMorkhasi isn't enough


    }


    protected function setCommuteToGraph($execution)
    {


        $typeMorakhasi = $execution->workflow->myForm->getFieldValueByName('Field_10'); /*نوع مرخصی روزانه*/


        /*
        تورال و تکمیل شونده
        ['1','استحقاقی'],
        ['3','استعلاجی - کمتر از ۳ روز'],
        ['33','استعلاجی - بیشتر از ۳ روز'],
        ['4','بدون حقوق - کمتر از ۷ روز'],
        ['44','بدون حقوق - بیشتر از ۷ روز'],
        ['11','زایمان'],
        ['111','اضطراری - کمتر از ۳ روز'],
        ['10','استراحت'],*/

        $result=0;


        switch ($typeMorakhasi) {

            case 1://استحقاقی
                $result=self::insertToGraph($execution, 104);
                break;
            case 3://استعلاجی کمتر از 3 روز
                $result=self::insertToGraph($execution, 110);
                break;
            case 33: //استعلاجی بیشتر از 3 روز
                $result=self::insertToGraph($execution, 111);
                break;
            case 4://مرخصی بدون حقوق
                $result=self::insertToGraph($execution, 112);
                break;
            case 44://مرخصی بدون حقوق
                $result=self::insertToGraph($execution, 113);
                break;
            case 111://اضطراری
                $result=self::insertToGraph($execution, 114);
                break;
            case 11://زایمان
                $result=self::insertToGraph($execution, 115);
                break;
            default:
                $execution->workflow->myForm->setFieldValueByName('Field_13', "no case");
                break;

        }
        //$execution->workflow->myForm->setFieldValueByName('Field_13', "the result of insert:".$result);

    }

    protected function getMondehMorakhasi($execution)
    {
        $remainMorakhasi = "";
        $dateEnd = '1399/12/30';
        $client = new SoapClient('http://10.10.100.15/WSTuralInOut/TuralInOut.asmx?wsdl');

        $GID = $execution->workflow->myForm->getFieldValueByName('Field_5');
        $s1 = "SELECT * FROM [Timex_TaminPoshtibani].adon.Kardex('" . $GID . "', '" . $dateEnd . "')";

        $param = array(
            'username' => '3ef1b48067e4f2ac9913141d77e847dd',
            'password' => '9a3f5b14f1737c15e86680d9cd40b840',
            'objStr' => $s1
        );
        $res = $client->RunSelectQuery($param);
        $res = $res->RunSelectQueryResult->cols;
        $res = json_decode(json_encode($res), true);
        $MandeMorkhasiString = urldecode($res['recs']['string'][30]);

        $MandeMorkhasiAr = explode(':', $MandeMorkhasiString);

        $this->mondehMorakhasiShow = $MandeMorkhasiAr[0] . ' روز و ' . $MandeMorkhasiAr[1] . ' ساعت ';
        $this->mondehMorakhasiNum = $MandeMorkhasiAr[0];
    }

    protected function checkMondehMorakhasiAfterThisForm($execution)
    {
        $ModatMorkhasiInthisForm = $execution->workflow->myForm->getFieldValueByName('Field_4');
        $ModatMorkhasiInthisForm= intval(substr($ModatMorkhasiInthisForm, 0, stripos($ModatMorkhasiInthisForm, 'روز')));

        $modatMorakhasiAfterThisForm=$this->mondehMorakhasiNum-$ModatMorkhasiInthisForm;

         $temp=$execution->workflow->myForm->getFieldValueByName('Field_13');
         $temp=$temp."modatMorakhasiAfterThisForm:".$modatMorakhasiAfterThisForm." \n";
         $execution->workflow->myForm->setFieldValueByName('Field_13', $temp);


        if ($modatMorakhasiAfterThisForm < 0) {
            $execution->setVariable('toprole', 90);
            return false;
        }
        return true;
    }



    protected function insertToGraph($execution,$tradodType)
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
        $formType='10';

        /*Field3:شماره پرسنلی هست*/
        // $emp='123';

        /*Field4:تاریخ درخواست هست که تاریخ جاری رو می گذاریم*/
        /*این رو باید امتحان کنم یا نگاه کنم که ماه و روز حتما باید دو رقمی باشد یا نه*/
        // $docDate='1399/05/22';

        /*Field5:نوع تردد است که مرخصی ساعتی:1  ماموریت ساعتی:2 ماموریت روزانه :150  و برای مرخصی روزانه بر اساس یک جدول می گذاریم که برای هر شرکت متغییر است*/
        /*مثلا مرخصی استحقاقی می تونه 104 باشه*/
       // $tradodType='1';
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
        if($res)
            $execution->setVariable('statuslevel', 6);


        //just for test
        //$temp= $execution->workflow->myForm->getFieldValueByName('Field_13', $sqlString);
        //$execution->workflow->myForm->setFieldValueByName('Field_13', $temp."\r\n".$res);


       // $st1 = "EXEC [Timex_TaminPoshtibani].[adon].[FlowDocs_Fill] '$reqIdOutput', 10, 882866, '1399/06/17',104 , '','1399/05/15','1399/05/15', 0,0, 0, 1, 'WB123','WB123', '990616T120512UW', 1";

        return $sqlString;
        /// end from other form//

    }




    public function execute_old(ezcWorkflowExecution $execution)
    {
        /*


        تورال و تکمیل شونده
        ['1','استحقاقی'],
        ['3','استعلاجی - کمتر از ۳ روز'],
        ['33','استعلاجی - بیشتر از ۳ روز'],
        ['4','بدون حقوق - کمتر از ۷ روز'],
        ['44','بدون حقوق - بیشتر از ۷ روز'],
        ['11','زایمان'],
        ['111','اضطراری - کمتر از ۳ روز'],
        ['10','استراحت'],

        آرمان
        استحقاقی = 1
        استعلاجی = 2
        بدون حقوق = 11
        اضطراری = 10
        تشویقی = 3
        */

        $execution->setVariable('newReferNote', '');

        $ACM = AccessControlManager::getInstance();
        $RID = $ACM->getRoleID();
        //RID = 8651 مدير امور اداري موسسه تامين و پشتيباني رضوي
        //if($RID != 8651 && ...)

        $execution->setVariable('statuslevel', 3);

        $docID = $execution->workflow->myForm->instanceID;
        $docDate = Date::GregToJalali(MySQLAdapter::getInstance()->executeScalar("SELECT CreateDate From oa_document where RowID = $docID"));
        $docDate_ARAMAN = STR_REPLACE('/', '-', $docDate);
        $type = $execution->workflow->myForm->getFieldValueByName('Field_10');
        $ModatMorkhasi = $execution->workflow->myForm->getFieldValueByName('Field_4');


        $codem = $execution->workflow->myForm->getFieldValueByName('Field_6');
        $dateR = $execution->workflow->myForm->getFieldValueByName('Field_2');
        $dateR_ARAMAN = STR_REPLACE('/', '-', $dateR);
        $dateB = $execution->workflow->myForm->getFieldValueByName('Field_3');
        $dateB_ARAMAN = Date::GregToJalali((new DateTime(Date::JalaliToGreg($dateB)))->add(new DateInterval("P1D"))->format('Y-m-d'));
        $dateB_ARAMAN = STR_REPLACE('/', '-', $dateB_ARAMAN);
        $shifty = $execution->workflow->myForm->getFieldValueByName('Field_12');

        //--------------------


        //--------------------

    }
}

