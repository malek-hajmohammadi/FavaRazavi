<?php
class calssName
{
    public function __construct(){}
    public function execute(ezcWorkflowExecution $execution, ezcWorkflowNodeAction $caller = null, $dabug = false)
    {

        $docID = $execution->workflow->myForm->instanceID;
        $docDate = Date::GregToJalali(MySQLAdapter::getInstance()->executeScalar("SELECT CreateDate From oa_document where RowID = $docID"));
        $PID = $execution->workflow->myForm->getFieldValueByName('Field_1');
        $GID = $execution->workflow->myForm->getFieldValueByName('Field_15');
        $FormType = 10;
        $FormType2 = $execution->workflow->myForm->getFieldValueByName('Field_13');
        /*
        1 100 استعلاجی
        4 102 تشویقی
        0 104 استحقاقی
        5 105 اضطراری
        2 108 بدون حقوق
        3 109 تحصیلی
        */
        switch($FormType2){
            case 1: $FormType2 = 100; break;
            case 0: $FormType2 = 104; break;
            case 2: $FormType2 = 108; break;
            case 3: $FormType2 = 109; break;
            case 4: $FormType2 = 102; break;
            case 5: $FormType2 = 105; break;
        }
        $ModatMorkhasi = '1';
        $dateR = $execution->workflow->myForm->getFieldValueByName('Field_5');
        $dateB = $execution->workflow->myForm->getFieldValueByName('Field_6');
        //$dateB = Date::GregToJalali((new DateTime(Date::JalaliToGreg($dateB)))->sub(new DateInterval("P1D"))->format('Y-m-d'));
        $timeR = 0;
        $timeB = 0;
        $city = 1101;
        $log = explode('/',$docDate);
        $log[0] = substr($log[0], 2);
        $log = $log[0].''.$log[1].''.$log[2].'T1000UW';

        try{
            //$client = new SoapClient('http://192.168.5.96/Timex.asmx?wsdl');
            $client = new SoapClient('http://185.23.128.168/Timex.asmx?wsdl');
            $s1 = "EXEC [adon].[FlowDocs_Fill] '0', '".$FormType."', '".$GID."', '".$docDate."', '".$FormType2."', '".$ModatMorkhasi."', '".$dateR."', '".$dateB."', ".$timeR.", ".$timeB.", ".$city.", 1, 'WB".$docID."','WB".$docID."', '".$log."', 1";
            $param1 = array(
                'username' => '3ef1b48067e4f2ac9913141d77e847dd',
                'password' => '9a3f5b14f1737c15e86680d9cd40b840',
                'objStr' => $s1
            );
            $resp1 = $client->RunQuery($param1);
            $recs1 = $resp1->RunQueryResult;
            $recs1 = json_decode(json_encode($recs1), true);
            if($recs1 == 'true' || $recs1 == true)
            {
                $execution->setVariable('graph',1);
                $execution->workflow->myForm->setFieldValueByName('Field_14','درج شده است');
                $execution->workflow->myForm->setFieldValueByName('Field_11','9');
            }
            else
            {
                $execution->setVariable('graph',0);
                $execution->workflow->myForm->setFieldValueByName('Field_14','درج نشده است '.$recs1);
                $execution->workflow->myForm->setFieldValueByName('Field_11','8');
            }
        }
        catch(SoapFaultException $e){
            $execution->setVariable('graph',0);
            $execution->workflow->myForm->setFieldValueByName('Field_14','درج نشده است '.$e);
            $execution->workflow->myForm->setFieldValueByName('Field_11','8');
        }
        catch(Exception $e){
            $execution->setVariable('graph',0);
            $execution->workflow->myForm->setFieldValueByName('Field_14','درج نشده است '.$e);
            $execution->workflow->myForm->setFieldValueByName('Field_11','8');
        }

        $execution->workflow->myForm->setFieldValueByName('Field_16','recs1:'.$recs1.' - Query:'.$s1);
    }

}
?>
