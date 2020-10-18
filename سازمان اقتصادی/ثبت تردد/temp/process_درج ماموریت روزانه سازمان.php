<?php
class calssName
{
    public function __construct(){}
    public function execute(ezcWorkflowExecution $execution, ezcWorkflowNodeAction $caller = null, $dabug = false)
    {
        $docID = $execution->workflow->myForm->instanceID;
        $docDate = Date::GregToJalali(MySQLAdapter::getInstance()->executeScalar("SELECT CreateDate From oa_document where RowID = $docID"));
        $PID = $execution->workflow->myForm->getFieldValueByName('Field_15');
        $GID = $execution->workflow->myForm->getFieldValueByName('Field_20');
        $FormType = 20;
        $FormType2 = '150';
        $ModatMorkhasi = '1';
        // 2019-09-15 05:00:00
        $dateR = $execution->workflow->myForm->getFieldValueByName('Field_9');
        $dateR = explode(' ',$dateR);
        $dateR = $dateR[0];
        $dateB = $execution->workflow->myForm->getFieldValueByName('Field_12');
        $dateB = explode(' ',$dateB);
        $dateB = $dateB[0];
        $timeR = 0;
        $timeB = 0;
        $city = 1101;
        $log = explode('/',$docDate);
        $log[0] = substr($log[0], 2);
        $log = $log[0].''.$log[1].''.$log[2].'T1000UW';

        try{
            $client = new SoapClient('http://192.168.5.96/Timex.asmx?wsdl');
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
                $execution->workflow->myForm->setFieldValueByName('Field_19','درج شده است');
                $execution->workflow->myForm->setFieldValueByName('Field_18','13');
            }
            else
            {
                $execution->setVariable('graph',0);
                $execution->workflow->myForm->setFieldValueByName('Field_19','درج نشده است '.$recs1);
            }
        }
        catch(SoapFaultException $e){
            $execution->setVariable('graph',0);
            $execution->workflow->myForm->setFieldValueByName('Field_19','درج نشده است '.$e);
        }
        catch(Exception $e){
            $execution->setVariable('graph',0);
            $execution->workflow->myForm->setFieldValueByName('Field_19','درج نشده است '.$e);
        }

        $execution->workflow->myForm->setFieldValueByName('Field_17','recs1:'.$recs1.' - Query:'.$s1);
    }

}
?>
