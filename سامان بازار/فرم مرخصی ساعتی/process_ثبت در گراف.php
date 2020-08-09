<?php
class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {
        //*************گرفتن متغیرهای لازم**************/
        /*تاریخ*/
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
        /*شماره پرسنلی میخواهم*/
        $empId=$execution->workflow->myForm->getFieldValueByName('Field_1');
        /*ثبت در گراف*****************/
        $userName="3ef1b48067e4f2ac9913141d77e847dd";
        $password="9a3f5b14f1737c15e86680d9cd40b840";
        $client = new SoapClient('http://46.209.31.219:8050/Timex.asmx?wsdl');


        $sqlFirst="EXEC [adon].[IOData_ins] $empId, '$date',$timeFirst, 1";
        $sqlSecond="EXEC [adon].[IOData_ins] $empId, '$date',$timeSecond, 0";

        $paramFirst = array(
            'username' => $userName,
            'password' => $password,
            'objStr' => $sqlFirst
        );

        $paramSecond = array(
            'username' => $userName,
            'password' => $password,
            'objStr' => $sqlSecond
        );

         $client->RunQuery($paramFirst);
         $client->RunQuery($paramSecond);

    }
}

