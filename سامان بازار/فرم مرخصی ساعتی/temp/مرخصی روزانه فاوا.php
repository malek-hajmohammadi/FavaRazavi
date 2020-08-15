<?php

class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {
        $docID = $execution->workflow->myForm->instanceID;
        $docDate = Date::GregToJalali(MySQLAdapter::getInstance()->executeScalar("SELECT CreateDate From oa_document where RowID = $docID"));
        $FormType = 10;
        $emp = $execution->workflow->myForm->getFieldValueByName('Field_11');
        $FormType2 = $execution->workflow->myForm->getFieldValueByName('Field_7');
        switch ($FormType2) {
            case 10:
                $FormType2 = 104;
                break;
            case 11:
                $FormType2 = 100;
                break;
            case 12:
                $FormType2 = 108;
                break;
            case 13:
                $FormType2 = 102;
                break;
            case 14:
                $FormType2 = 103;
                break;
            case 15:
                $FormType2 = 105;
                break;
        }
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
        $execution->workflow->myForm->setFieldValueByName('Field_6', '8');

        try {
            $client = new SoapClient('http://172.16.61.253/Timex.asmx?wsdl');
            $s1 = "EXEC [adon].[FlowDocs_Fill] '0', '" . $FormType . "', '" . $emp . "', '" . $docDate . "', '" . $FormType2 . "', '" . $ModatMorkhasi . "', '" . $dateR . "', '" . $dateB . "', " . $timeR . ", " . $timeB . ", " . $city . ", 1, 'WB" . $docID . "','WB" . $docID . "', '" . $log . "', 1";
            $param1 = array(
                'username' => '3ef1b48067e4f2ac9913141d77e847dd',
                'password' => '9a3f5b14f1737c15e86680d9cd40b840',
                'objStr' => $s1
            );
            $resp1 = $client->RunQuery($param1);
            $recs1 = $resp1->RunQueryResult;
            $recs1 = json_decode(json_encode($recs1), true);
            if ($recs1 == 'true' || $recs1 == true) $execution->setVariable('status', 1);
            else $execution->setVariable('status', 0);
        } catch (SoapFaultException $e) {
            $execution->setVariable('status', 0);
        } catch (Exception $e) {
            $execution->setVariable('status', 0);
        }
        $execution->workflow->myForm->setFieldValueByName('Field_12', 'recs1:' . $recs1 . '-Query:' . $s1);
    }
}

