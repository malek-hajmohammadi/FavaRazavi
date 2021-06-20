<?php
class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {

        $this->call_sabtTaradod($execution);


    }
    private function call_sabtTaradod($execution){
        $geraphId=$execution->workflow->myForm->getFieldValueByName('Field_0');
        $geraphId=substr($geraphId,4);

        $date = $execution->workflow->myForm->getFieldValueByName('Field_2');

        //8:30
        $time=$execution->workflow->myForm->getFieldValueByName('Field_3');

        //----------------------------
        $this->sabtTaradod($geraphId,$date,$time,$execution);
    }

    private function sabtTaradod($geraphId,$date,$time,$execution){

        /*
        * شماره پرسنلی شخص هست که باید در گراف هم همین باشد
        * "123"
        * $execution->workflow->myForm->getFieldValueByName('Field_5')
        * */
        //$geraphId="850";
        //-------------------------------------------------

        /*
        * تاریخ  با فرمت 1396/05/15 است

        *
        *  $dateR = $execution->workflow->myForm->getFieldValueByName('Field_2'); //date
        * */
        //$date='1399/12/02';
        //$date = $execution->workflow->myForm->getFieldValueByName('Field_2'); //date
        //------------------------------------------

        /*
        * یک عدد هست از ضرب ساعت بعلاوه دقیقه
        * برای مرخصی روزانه صفر می گذاریم
        * $startTime=540;
        * */
        //$time=$execution->workflow->myForm->getFieldValueByName('Field_3');
        $timeArray = explode(':', $time);
        $time=$timeArray[0]*60+$timeArray[1];

        //------------------------------------------

        /*
       * توضیحات سیستمی هست که اون رو به صورت اختیاری با WB شروع می کنیم
       * و بعد شماره فرم رو در آن می گذاریم
       *
       *  $docID = $execution->workflow->myForm->instanceID;
       * $commentSys="WB$docID"
       *
       * */
       // $commentSys="WB1234";
        $docID = $execution->workflow->myForm->instanceID;
        $commentSys="WB$docID";

        $type="0";


        $query = "EXEC [adon].[IOData_ins] " . $geraphId . ", '" . $date . "', " . $time . ", " . $type . ",'$commentSys';";

        $execution->workflow->myForm->setFieldValueByName('Field_4',$query);

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
