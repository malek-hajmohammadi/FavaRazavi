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

        $PID = $execution->workflow->myForm->getFieldValueByName('Field_1');
        $DateD = $execution->workflow->myForm->getFieldValueByName('Field_2');
        $Query = '';

        $TimeT = $execution->workflow->myForm->getFieldValueByName('Field_3');
        if ($TimeT != '' && $TimeT != '00:00:00') {
            $TimeT = explode(':', date('H:i', strtotime($TimeT)));
            $TimeT = ($TimeT[0] * 60) + $TimeT[1];
            $TypeT = $execution->workflow->myForm->getFieldValueByName('Field_8');
            switch ($TypeT) {
                case 0:
                    $Query .= "EXEC [adon].[IOData_ins] $PID, '$DateD', $TimeT, 0,'$docID';";
                    break;
                case 1:
                    $Query .= "EXEC [adon].[IOData_ins] $PID, '$DateD', $TimeT, 0,'$docID';";
                    break;
                case 2:
                    $Query .= "EXEC [adon].[IOData_ins] $PID, '$DateD', $TimeT, 1,'$docID';";
                    break;
                case 3:
                    $Query .= "EXEC [adon].[IOData_ins] $PID, '$DateD', $TimeT, 2,'$docID';";
                    break;
                case 4:
                    $Query .= "EXEC [adon].[IOData_del] $PID, '$DateD', $TimeT, '000000T000000U0';";
                    break;
            }
        }

        $TimeT = $execution->workflow->myForm->getFieldValueByName('Field_4');
        if ($TimeT != '' && $TimeT != '00:00:00') {
            $TimeT = explode(':', date('H:i', strtotime($TimeT)));
            $TimeT = ($TimeT[0] * 60) + $TimeT[1];
            $TypeT = $execution->workflow->myForm->getFieldValueByName('Field_11');
            switch ($TypeT) {
                case 0:
                    $Query .= "EXEC [adon].[IOData_ins] $PID, '$DateD', $TimeT, 0,'$docID';";
                    break;
                case 1:
                    $Query .= "EXEC [adon].[IOData_ins] $PID, '$DateD', $TimeT, 0,'$docID';";
                    break;
                case 2:
                    $Query .= "EXEC [adon].[IOData_ins] $PID, '$DateD', $TimeT, 1,'$docID';";
                    break;
                case 3:
                    $Query .= "EXEC [adon].[IOData_ins] $PID, '$DateD', $TimeT, 2,'$docID';";
                    break;
                case 4:
                    $Query .= "EXEC [adon].[IOData_del] $PID, '$DateD', $TimeT, '000000T000000U0';";
                    break;
            }
        }

        $TimeT = $execution->workflow->myForm->getFieldValueByName('Field_5');
        if ($TimeT != '' && $TimeT != '00:00:00') {
            $TimeT = explode(':', date('H:i', strtotime($TimeT)));
            $TimeT = ($TimeT[0] * 60) + $TimeT[1];
            $TypeT = $execution->workflow->myForm->getFieldValueByName('Field_12');
            switch ($TypeT) {
                case 0:
                    $Query .= "EXEC [adon].[IOData_ins] $PID, '$DateD', $TimeT, 0,'$docID';";
                    break;
                case 1:
                    $Query .= "EXEC [adon].[IOData_ins] $PID, '$DateD', $TimeT, 0,'$docID';";
                    break;
                case 2:
                    $Query .= "EXEC [adon].[IOData_ins] $PID, '$DateD', $TimeT, 1,'$docID';";
                    break;
                case 3:
                    $Query .= "EXEC [adon].[IOData_ins] $PID, '$DateD', $TimeT, 2,'$docID';";
                    break;
                case 4:
                    $Query .= "EXEC [adon].[IOData_del] $PID, '$DateD', $TimeT, '000000T000000U0';";
                    break;
            }
        }

        $TimeT = $execution->workflow->myForm->getFieldValueByName('Field_6');
        if ($TimeT != '' && $TimeT != '00:00:00') {
            $TimeT = explode(':', date('H:i', strtotime($TimeT)));
            $TimeT = ($TimeT[0] * 60) + $TimeT[1];
            $TypeT = $execution->workflow->myForm->getFieldValueByName('Field_13');
            switch ($TypeT) {
                case 0:
                    $Query .= "EXEC [adon].[IOData_ins] $PID, '$DateD', $TimeT, 0,'$docID';";
                    break;
                case 1:
                    $Query .= "EXEC [adon].[IOData_ins] $PID, '$DateD', $TimeT, 0,'$docID';";
                    break;
                case 2:
                    $Query .= "EXEC [adon].[IOData_ins] $PID, '$DateD', $TimeT, 1,'$docID';";
                    break;
                case 3:
                    $Query .= "EXEC [adon].[IOData_ins] $PID, '$DateD', $TimeT, 2,'$docID';";
                    break;
                case 4:
                    $Query .= "EXEC [adon].[IOData_del] $PID, '$DateD', $TimeT, '000000T000000U0';";
                    break;
            }
        }

        $TimeT = $execution->workflow->myForm->getFieldValueByName('Field_9');
        if ($TimeT != '' && $TimeT != '00:00:00') {
            $TimeT = explode(':', date('H:i', strtotime($TimeT)));
            $TimeT = ($TimeT[0] * 60) + $TimeT[1];
            $TypeT = $execution->workflow->myForm->getFieldValueByName('Field_14');
            switch ($TypeT) {
                case 0:
                    $Query .= "EXEC [adon].[IOData_ins] $PID, '$DateD', $TimeT, 0,'$docID';";
                    break;
                case 1:
                    $Query .= "EXEC [adon].[IOData_ins] $PID, '$DateD', $TimeT, 0,'$docID';";
                    break;
                case 2:
                    $Query .= "EXEC [adon].[IOData_ins] $PID, '$DateD', $TimeT, 1,'$docID';";
                    break;
                case 3:
                    $Query .= "EXEC [adon].[IOData_ins] $PID, '$DateD', $TimeT, 2,'$docID';";
                    break;
                case 4:
                    $Query .= "EXEC [adon].[IOData_del] $PID, '$DateD', $TimeT, '000000T000000U0';";
                    break;
            }
        }

        $TimeT = $execution->workflow->myForm->getFieldValueByName('Field_10');
        if ($TimeT != '' && $TimeT != '00:00:00') {
            $TimeT = explode(':', date('H:i', strtotime($TimeT)));
            $TimeT = ($TimeT[0] * 60) + $TimeT[1];
            $TypeT = $execution->workflow->myForm->getFieldValueByName('Field_15');
            switch ($TypeT) {
                case 0:
                    $Query .= "EXEC [adon].[IOData_ins] $PID, '$DateD', $TimeT, 0,'$docID';";
                    break;
                case 1:
                    $Query .= "EXEC [adon].[IOData_ins] $PID, '$DateD', $TimeT, 0,'$docID';";
                    break;
                case 2:
                    $Query .= "EXEC [adon].[IOData_ins] $PID, '$DateD', $TimeT, 1,'$docID';";
                    break;
                case 3:
                    $Query .= "EXEC [adon].[IOData_ins] $PID, '$DateD', $TimeT, 2,'$docID';";
                    break;
                case 4:
                    $Query .= "EXEC [adon].[IOData_del] $PID, '$DateD', $TimeT, '000000T000000U0';";
                    break;
            }
        }

        try {
            $client = new SoapClient('http://192.168.88.137/TimeX.asmx?wsdl');
            $param = array(
                'username' => '3ef1b48067e4f2ac9913141d77e847dd',
                'password' => '9a3f5b14f1737c15e86680d9cd40b840',
                'objStr'   => $Query
            );
            $res = $client->RunQuery($param);
            $res = $res->RunQueryResult;
            $res = json_decode(json_encode($res), true);

            //این شرط با True بود که ظاهرا کار نمی کرد با false جایگیزین کردم
            if ($res !='false' && $res != false) {
                $execution->setVariable('status', 1);
                $res = 'TrueTrue';

                $client = new SoapClient('http://192.168.88.134:8080/IOProcessService.asmx');
                $param = $client->DoProcess(array('EmpFilter' => $PID, 'StartProcessDate' => $DateD, 'EndProcessDate' => $DateD, 'BranchFilter' => 1, 'UserID' => 1));
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
        $execution->workflow->myForm->setFieldValueByName('Field_17', 'Query: ' . $Query . ' - res: ' . $res);
    }
}

$a="Query: 
EXEC [adon].[IOData_ins] 2104, '1400/03/11', 1323, 0,'339016';
EXEC [adon].[IOData_ins] 2104, '1400/03/11', 210, 0,'339016';
EXEC [adon].[IOData_ins] 2104, '1400/03/11', 210, 0,'339016';
EXEC [adon].[IOData_ins] 2104, '1400/03/11', 210, 0,'339016';
EXEC [adon].[IOData_ins] 2104, '1400/03/11', 210, 0,'339016';
EXEC [adon].[IOData_ins] 2104, '1400/03/11', 210, 0,'339016'; - res: FalseException: SOAP-ERROR: Parsing WSDL: Couldn't load from 'http://192.168.88.134:8080/IOProcessService.asmx' : Premature end of data in tag html line 3 ";