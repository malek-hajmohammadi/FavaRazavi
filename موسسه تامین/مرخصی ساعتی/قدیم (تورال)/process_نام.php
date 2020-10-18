<?php

class calssName
{

    protected $mondehMorakhasiShow; //مانده مرخصی بصورت نمایشی//
    protected $mondehMorakhasiNum;//مانده مرخصی عددی برای مقایسه و به ساعت//
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution, ezcWorkflowNodeAction $caller = null)
    {

        self::setSubjectInCable($execution);


        $ACM = AccessControlManager::getInstance();
        $RID = $ACM->getRoleID();
        //RID = 8651 مدير امور اداري موسسه تامين و پشتيباني رضوي
        //if($RID != 8651 && ...)

        $docID = $execution->workflow->myForm->instanceID;
        $Name = $execution->workflow->myForm->getFieldValueByName('Field_0');
        $codem = $execution->workflow->myForm->getFieldValueByName('Field_4');


        //toprole=0 کاربر
        //toprole=1 ریس به بالا
        //toprole=99 اتمام

        $Status = $execution->workflow->myForm->getFieldValueByName('Field_8');
        if ($Status == '') $execution->setVariable('toprole', 99);
        else {
            if(false)/*برای آرمان و تورال بود*/{
            //--------------------
            $type_ARAMAN = '1';
            $systemIDـARAMAN = "10101086"; //کد سیستم آرمان
            $userـARAMAN = "ravanrequest"; //یوزر آرمان
            $passـARAMAN = "4H8_gHf2X2@y7Wt"; //پسورد آرمان
            $server = "http://10.10.10.118:84/Services/PrsServices.svc/Prs/";
            $url = $server . "getLeaveMod/";
            $url .= (string)$systemIDـARAMAN . "/";
            $url .= (string)$userـARAMAN . "/";
            $url .= (string)$passـARAMAN . "/";
            $url .= (string)$type_ARAMAN . "/";
            $url .= (string)$codem;

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $url,
            ));
            $resp = curl_exec($curl);
            curl_close($curl);
            $resp = json_decode(json_decode($resp, true), true);

            if ($resp['Error'][0]['Result'] == 'True') {
                $MandeMorkhasi = $resp['data'][0]['Mod_Leave'];
                $MandeMorkhasiOO = $MandeMorkhasi;
                if ($MandeMorkhasi != 0) {
                    $MandeMorkhasiStatus = 'mosbat';
                    $offset = strpos($MandeMorkhasi, '-');
                    if ($offset !== FALSE) {
                        $MandeMorkhasi = substr($MandeMorkhasi, 1);
                        $MandeMorkhasiStatus = 'manfi';
                    }
                    $MandeMorkhasi = explode('.', $MandeMorkhasi);
                    $day = $MandeMorkhasi[0];
                    $day = strlen($day) == 1 ? "0" . $day : $day;
                    if ($MandeMorkhasi[1] != NULL) $hourmin = '0.' . $MandeMorkhasi[1];
                    else $hourmin = 0;
                    $hourmin = $hourmin * 440;
                    $hourmin = $hourmin / 60;
                    $hourmin = explode('.', $hourmin);
                    $min = "0." . $hourmin[1];
                    $min = $min * 60;
                    $min = $min % 60;
                    $min = strlen($min) == 1 ? "0" . $min : $min;
                    $hour = $hourmin[0];
                    $hour = $hour + floor($min / 60);
                    $hour = strlen($hour) == 1 ? "0" . $hour : $hour;
                    if ($day != 00 && $hour != 00 && $min != 00) $MandeMorkhasi = $day . 'روز و ' . $hour . 'ساعت و ' . $min . 'دقيقه';
                    elseif ($day != 00 && $hour != 00 && $min == 00) $MandeMorkhasi = $day . 'روز و ' . $hour . 'ساعت';
                    elseif ($day != 00 && $hour == 00 && $min != 00) $MandeMorkhasi = $day . 'روز و ' . $min . 'دقيقه';
                    elseif ($day == 00 && $hour != 00 && $min != 00) $MandeMorkhasi = $hour . 'ساعت و ' . $min . 'دقيقه';
                    elseif ($day == 00 && $hour == 00 && $min != 00) $MandeMorkhasi = $min . 'دقيقه';
                    else $MandeMorkhasi = '0';
                    if ($MandeMorkhasiStatus == 'manfi') $MandeMorkhasi = 'منفی ' . $MandeMorkhasi;
                    $MandeMorkhasiMerg2 = ((intval($day) * 24) * 60) + (intval($hour) * 60) + intval($min);
                    $MandeMorkhasiMerg = $day . '-' . $hour . '-' . $min . '-' . $MandeMorkhasiMerg2 . '-' . $MandeMorkhasiStatus;
                } else {
                    $MandeMorkhasiMerg = 0;
                }
            } else {
                $MandeMorkhasi = 'خطا';
                $MandeMorkhasiMerg = 'خطا';
                $Mod_PreviousYearLeave = 'خطا';
                $Mod_ThisYearLeave = 'خطا';
                $TakenLeaveInThisYear = 'خطا';
                $Num_ThisYearLeaveFromPreviousYear = 'خطا';
            }
            //--------------------
            $type_ARAMAN = '1';
            $systemIDـARAMAN = "10101086"; //کد سیستم آرمان
            $userـARAMAN = "ravanrequest"; //یوزر آرمان
            $passـARAMAN = "4H8_gHf2X2@y7Wt"; //پسورد آرمان
            $server = "http://10.10.10.118:84/Services/PrsServices.svc/Prs/";
            $url = $server . "getLeaveModPreYear/";
            $url .= (string)$systemIDـARAMAN . "/";
            $url .= (string)$userـARAMAN . "/";
            $url .= (string)$passـARAMAN . "/";
            $url .= (string)$type_ARAMAN . "/";
            $url .= (string)$codem;
            $url .= "/1397-12-29";

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $url,
            ));
            $resp = curl_exec($curl);
            curl_close($curl);
            $resp = json_decode(json_decode($resp, true), true);
            if ($resp['Error'][0]['Result'] == 'True') {
                $MandeMorkhasi2 = $resp['data'][0]['Mod_Leave'];
                $MandeMorkhasi2OO = $MandeMorkhasi2;
                if ($MandeMorkhasi2 != 0) {
                    $MandeMorkhasi2Status = 'mosbat';
                    $offset = strpos($MandeMorkhasi2, '-');
                    if ($offset !== FALSE) {
                        $MandeMorkhasi2 = substr($MandeMorkhasi2, 1);
                        $MandeMorkhasi2Status = 'manfi';
                    }
                    $MandeMorkhasi2 = explode('.', $MandeMorkhasi2);
                    $day = $MandeMorkhasi2[0];
                    $day = strlen($day) == 1 ? "0" . $day : $day;
                    if ($MandeMorkhasi2[1] != NULL) $hourmin = '0.' . $MandeMorkhasi2[1];
                    else $hourmin = 0;
                    $hourmin = $hourmin * 440;
                    $hourmin = $hourmin / 60;
                    $hourmin = explode('.', $hourmin);
                    $min = "0." . $hourmin[1];
                    $min = $min * 60;
                    $min = $min % 60;
                    $min = strlen($min) == 1 ? "0" . $min : $min;
                    $hour = $hourmin[0];
                    $hour = $hour + floor($min / 60);
                    $hour = strlen($hour) == 1 ? "0" . $hour : $hour;
                    if ($day != 00 && $hour != 00 && $min != 00) $MandeMorkhasi2 = $day . 'روز و ' . $hour . 'ساعت و ' . $min . 'دقيقه';
                    elseif ($day != 00 && $hour != 00 && $min == 00) $MandeMorkhasi2 = $day . 'روز و ' . $hour . 'ساعت';
                    elseif ($day != 00 && $hour == 00 && $min != 00) $MandeMorkhasi2 = $day . 'روز و ' . $min . 'دقيقه';
                    elseif ($day == 00 && $hour != 00 && $min != 00) $MandeMorkhasi2 = $hour . 'ساعت و ' . $min . 'دقيقه';
                    elseif ($day == 00 && $hour == 00 && $min != 00) $MandeMorkhasi2 = $min . 'دقيقه';
                    else $MandeMorkhasi2 = '0';
                    if ($MandeMorkhasi2Status == 'manfi') $MandeMorkhasi2 = 'منفی ' . $MandeMorkhasi2;
                    $MandeMorkhasi2Merg2 = ((intval($day) * 24) * 60) + (intval($hour) * 60) + intval($min);
                    $MandeMorkhasi2Merg = $day . '-' . $hour . '-' . $min . '-' . $MandeMorkhasi2Merg2 . '-' . $MandeMorkhasi2Status;
                } else {
                    $MandeMorkhasi2Merg = 0;
                }
            } else {
                $MandeMorkhasi2 = 'خطا';
                $MandeMorkhasi2Merg = 'خطا';
                $Mod_PreviousYearLeave2 = 'خطا';
                $Mod_ThisYearLeave2 = 'خطا';
                $TakenLeaveInThisYear2 = 'خطا';
                $Num_ThisYearLeaveFromPreviousYear2 = 'خطا';
            }

            //--------------------

            $MandeMorkhasiNahaee = $MandeMorkhasiOO + $MandeMorkhasi2OO;
            if ($MandeMorkhasiNahaee != 0) {
                $MandeMorkhasiNahaeeStatus = 'mosbat';
                $offset = strpos($MandeMorkhasiNahaee, '-');
                if ($offset !== FALSE) {
                    $MandeMorkhasiNahaee = substr($MandeMorkhasiNahaee, 1);
                    $MandeMorkhasiNahaeeStatus = 'manfi';
                }
                $MandeMorkhasiNahaee = explode('.', $MandeMorkhasiNahaee);
                $day = $MandeMorkhasiNahaee[0];
                $day = strlen($day) == 1 ? "0" . $day : $day;
                if ($MandeMorkhasiNahaee[1] != NULL) $hourmin = '0.' . $MandeMorkhasiNahaee[1];
                else $hourmin = 0;
                $hourmin = $hourmin * 440;
                $hourmin = $hourmin / 60;
                $hourmin = explode('.', $hourmin);
                $min = "0." . $hourmin[1];
                $min = $min * 60;
                $min = $min % 60;
                $min = strlen($min) == 1 ? "0" . $min : $min;
                $hour = $hourmin[0];
                $hour = $hour + floor($min / 60);
                $hour = strlen($hour) == 1 ? "0" . $hour : $hour;
                if ($day != 00 && $hour != 00 && $min != 00) $MandeMorkhasiNahaee = $day . 'روز و ' . $hour . 'ساعت و ' . $min . 'دقيقه';
                elseif ($day != 00 && $hour != 00 && $min == 00) $MandeMorkhasiNahaee = $day . 'روز و ' . $hour . 'ساعت';
                elseif ($day != 00 && $hour == 00 && $min != 00) $MandeMorkhasiNahaee = $day . 'روز و ' . $min . 'دقيقه';
                elseif ($day == 00 && $hour != 00 && $min != 00) $MandeMorkhasiNahaee = $hour . 'ساعت و ' . $min . 'دقيقه';
                elseif ($day == 00 && $hour == 00 && $min != 00) $MandeMorkhasiNahaee = $min . 'دقيقه';
                else $MandeMorkhasiNahaee = '0';
                if ($MandeMorkhasiNahaeeStatus == 'manfi') $MandeMorkhasiNahaee = 'منفی ' . $MandeMorkhasiNahaee;
                $MandeMorkhasiNahaeeMerg2 = ((intval($day) * 24) * 60) + (intval($hour) * 60) + intval($min);
                $MandeMorkhasiNahaeeMerg = $day . '-' . $hour . '-' . $min . '-' . $MandeMorkhasiNahaeeMerg2 . '-' . $MandeMorkhasiNahaeeStatus;
            } else {
                $MandeMorkhasiNahaee = 0;
                $MandeMorkhasiNahaeeMerg = 0;
            }
            //--------------------
                }

            self::getMondehMorakhasi($execution);

             $execution->workflow->myForm->setFieldValueByName('Field_10', $this->mondehMorakhasiShow);
           // $execution->workflow->myForm->setFieldValueByName('Field_12', $MandeMorkhasiNahaeeMerg);
            $ModatMorkhasi = $execution->workflow->myForm->getFieldValueByName('Field_15');
            $ModatMorkhasi = explode(':');
            $ModatMorkhasi = (intval($ModatMorkhasi[0]) * 60) + $ModatMorkhasi[1];

           if ($ModatMorkhasi > $this->mondehMorakhasiNum*60) {
                $execution->setVariable('toprole', 90);
                $execution->workflow->myForm->setFieldValueByName('Field_9', '90 / مدت مرخصی بیشتر از مانده مرخصی است / برگشت از پردازش نام');
            }
        }
    }
    protected function setSubjectInCable($execution){

        $docID = $execution->workflow->myForm->instanceID;
        $Name = $execution->workflow->myForm->getFieldValueByName('Field_0');

        $newTitle = 'مرخصی ساعتی (تامین) ' . $Name;
        $sql_title = "update oa_document set DocDesc='" . $newTitle . "',Subject='" .
            $newTitle . "' where RowID=" . $docID . "  limit 1";

        MySQLAdapter::getInstance()->execute($sql_title);
    }
    protected function getMondehMorakhasi($execution){
        $remainMorakhasi="";
        $dateEnd = '1399/12/30';
        $client = new SoapClient('http://10.10.100.15/WSTuralInOut/TuralInOut.asmx?wsdl');

        $GID=$execution->workflow->myForm->setFieldValueByName('Field_5');
        $s1 = "SELECT * FROM [Timex_TaminPoshtibani].adon.Kardex('".$GID."', '".$dateEnd."')";

        $param = array(
            'username' => '3ef1b48067e4f2ac9913141d77e847dd',
            'password' => '9a3f5b14f1737c15e86680d9cd40b840',
            'objStr' => $s1
        );
        $res = $client->RunSelectQuery($param);
        $res = $res->RunSelectQueryResult->cols;
        $res = json_decode(json_encode($res), true);
        $MandeMorkhasiString = urldecode($res['recs']['string'][30]);

        $MandeMorkhasiAr=explode(':',$MandeMorkhasiString);

        $this->mondehMorakhasiShow= $MandeMorkhasiAr[0].' روز و '.$MandeMorkhasiAr[1].' ساعت ';
        $this->mondehMorakhasiNum=$MandeMorkhasiAr[0]*24+$MandeMorkhasiAr[1];



    }
}

