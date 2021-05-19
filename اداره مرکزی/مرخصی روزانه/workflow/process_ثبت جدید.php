<?php


class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution, ezcWorkflowNodeAction $caller = null)
    {
        $docID = $execution->workflow->myForm->instanceID;
        $regInfo = Letter::GetRegInfo($docID);

        $dateStart = $execution->workflow->myForm->getFieldValueByName('Field_1');
        $dateEnd = $execution->workflow->myForm->getFieldValueByName('Field_2');
        $empID = $execution->workflow->myForm->getFieldValueByName('Field_6');
        $vacType = $execution->workflow->myForm->getFieldValueByName('Field_5');
        $personalType = $vacType;

        /* ordered types by mohammadzadeh */
        if ($vacType == '1')/*استحقاقي*/ {
            $vacType = '1';
            $personalType = '1';
        } elseif ($vacType == '2')/*استعلاجي تا 3 روز*/ {
            $vacType = '3';
            $personalType = '2';
        } elseif ($vacType == '20')/*استعلاجي بيش از 3 روز*/ {
            $vacType = '5';
            $personalType = '30';
        } elseif ($vacType == '3')/*بدون حقوق تا 7 روز*/ {
            $vacType = '4';
            $personalType = '3';
        } elseif ($vacType == '33')/*بدون حقوق بيشتر از 7 روز*/ {
            $vacType = '17';
            $personalType = '3';
        } elseif ($vacType == '4')/*اضطراري*/ {
            $vacType = '11';
            $personalType = '4';
        } elseif ($vacType == '10')/*استراحت هفتگي كاركنان شيفتي- حوزه معاونت اماكن*/ {
            $vacType = '10';
            $personalType = '40';
        } elseif ($vacType == '31')/*تشويقي تولد فرزند (آقايان )*/ {
            $vacType = '19';
            $personalType = '31';
        } elseif ($vacType == '32')/*استعلاجي زايمان*/ {
            $vacType = '18';
            $personalType = '30';
        } elseif ($vacType == '9')/*چكاپ*/ {
            $vacType = '9';
            $personalType = '9';
        } elseif ($vacType == '34')/*اداري*/ {
            $vacType = '6';
            $personalType = '22';
        } elseif ($vacType == '50')/*خاص 50 درصد*/ {
            $vacType = '20';
            $personalType = '50';
        } elseif ($vacType == '21')/*استعلاجي كرونا*/ {
            $vacType = '21';
            $personalType = '21';
        }


        $client = new SoapClient('http://10.10.100.15/WSTural/Tural.asmx?wsdl');
        $where = '';

        $shift = $execution->workflow->myForm->getFieldValueByName('Field_21');
        if ($shift)
            $shift = 1;
        else
            $shift = 0;

        $sql = "EXEC iVacation '$empID', '$dateStart', '$dateEnd', $vacType,$shift";
        $param = array('username' => '8bfc0e61722d9e9c9bb2138cb359fef9', 'password' => '085c734188fb09a96eba5d22893a44c4', 'objStr' => $sql);
        if ($execution->getVariable('turalok') != 1) {
            $resp = $client->RunQuery($param);

            if ($resp && $resp->RunQueryResult && $resp->RunQueryResult == true) {
                $execution->setVariable('turalok', 1);
                $logText = 'tural query: ' . $sql . ', result=' . var_export($resp, true);
                $execution->workflow->myForm->setFieldValueByName('Field_27', $logText);
            } else {
                $execution->setVariable('turalok', 0);
                $error = 'tural error: ' . var_export($resp);
                $execution->workflow->myForm->setFieldValueByName('Field_27', $error);
            }
        }

        if (!(is_array($regInfo) && isset($regInfo['regcode']))) {
            $docID = $execution->workflow->myForm->instanceID;
            WorkFlowSecRegister::regOutForm($docID);
            $regInfo = Letter::GetRegInfo($docID);
            $execution->workflow->myForm->setFieldValueByName('Field_16', $docID);
            $execution->workflow->myForm->setFieldValueByName('Field_17', $regInfo['regcode']);
            $execution->workflow->myForm->setFieldValueByName('Field_7', 'حكم مرخصي');
        }
        $docID = $execution->workflow->myForm->instanceID;
        //soltanabadiyan - morakhasi  CSV maker - 1393-07-01
        //filerecorder::recorder('morakhasi1:'.date("Y-m-d H:i:s"), "a");
        $db = WFPDOAdapter::getInstance();
        $user = $execution->workflow->myForm->getFieldValueByName('Field_0');//user
        //filerecorder::recorder('morakhasi000:'.var_export($user[0]['uid'],true), "a");

        $sql = "SELECT employeeID  FROM `oa_users` where UserID = " . $user[0]['uid'];
        $res = $db->executeScalar($sql);
        //filerecorder::recorder('morakhasi00:'.$sql, "a");

        $a[] = $res; //user personal ID
        $empuid = $res;
        $a[] = iconv('utf-8', 'windows-1256', $personalType);//morakhasy type
        $a[] = $execution->workflow->myForm->getFieldValueByName('Field_1');//start Date
        $a[] = $execution->workflow->myForm->getFieldValueByName('Field_2');//end date
        $a[] = $execution->workflow->myForm->getFieldValueByName('Field_4');//days count
        $signer = $execution->getVariable('signer');//signer
        //filerecorder::recorder('MORKHASI123:'.var_export($a , true) , "a");
        $sql = "SELECT employeeID  FROM `oa_users` where UserID = " . $signer[0];
        //filerecorder::recorder('MORKHASI123456:'.var_export($signer , true) , "a");
        //filerecorder::recorder('morakhasi1232:'.$sql, "a");
        $res = $db->executeScalar($sql);
        //filerecorder::recorder('morakhasi2:'.$sql, "a");

        $a[] = $res; //signer personal ID
        $sql = 'SELECT oa_depts_roles.Name  FROM oa_depts_roles WHERE  oa_depts_roles.RowID =' . $signer[1];
        $res = $db->executeScalar($sql);
        //filerecorder::recorder('morakhasi3:'.$sql, "a");

        $a[] = iconv('utf-8', 'windows-1256', $res); // semat
        $docID = $execution->workflow->myForm->instanceID;
        $sql = "SELECT oa_letter.RegCode,oa_letter.RegDate  FROM `oa_document` INNER JOIN oa_letter ON (`oa_document`.RowID = oa_letter.DocID) where `oa_document`.RowID = $docID";
        $res = $db->executeSelect($sql);
        $res = $db->fetchAssoc();
        //filerecorder::recorder('morakhasi4:'.$sql, "a");

        $a[] = $res['RegCode'];
        $a[] = Date::GregToJalaliDisplay($res['RegDate']);
        $year = $res['RegDate'];
        $regcode = $res['RegCode'];
        $year = explode('/', Date::GregToJalaliDisplay($year));
        $year = $year[0];
        $a[] = iconv('utf-8', 'windows-1256', $execution->workflow->myForm->getFieldValueByName('Field_10'));//details
        //filerecorder::recorder('SelamSolt:'.var_export($a , true) , "a");
        $data[] = $a;
        //filerecorder::recorder('CSVDATA:'.var_export($data , true) , "a");
        $execution->setVariable('csvok', '1');
        $tm = time();

        /* add by mohammadzadeh for fava users */
        $person = $execution->workflow->myForm->getFieldValueByName('Field_0');
        $rid = $person[0]['rid'];
        $acm = AccessControlManager::getInstance();
        $gid = $acm->getUserGroupID($rid);
        $gids = explode(',', $gid);
        $fava = in_array(25, $gids);
        if ($fava)//fava 970116 مرخصي پرسنل فاوا نياز به ثبت در سيستم پرسنلي ندارد
        {
            $execution->setVariable('csvok', '1');
        } else if ($personalType == '9' || $personalType == '50')
//checkup 951130 نوع مرخصي چكاپ نياز به ثبت در سيستم پرسنلي ندارد
//نوع مرخصی خاص 50درصد فعلا نیاز به ثبت در سیستم پرسنلی ندارد 980106
        {
            $execution->setVariable('csvok', '1');
        } else {
            $csv_handler = fopen('/opt/storage/Morkhasi/' . $empuid . '_' . $year . '_' . $regcode . '.csv', 'w+');

            if (!$csv_handler) {
                $error = error_get_last();
                $error = 'file create error: ' . var_export($error);
                $execution->workflow->myForm->setFieldValueByName('Field_27', $error);
            }


            //fprintf($csv_handler, chr(0xEF).chr(0xBB).chr(0xBF));
            foreach ($data as $fields) {
                fputcsv($csv_handler, $fields);
            }
            fclose($csv_handler);


            if (!(file_exists('/opt/storage/Morkhasi/' . $empuid . '_' . $year . '_' . $regcode . '.csv'))) {
                $execution->setVariable('csvok', '0');
            }
        }//end if checkup
        //iconv('utf-8', 'windows-1256', $testvalue);
    }//end func
}


