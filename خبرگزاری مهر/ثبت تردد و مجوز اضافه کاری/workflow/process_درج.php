<?php

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

