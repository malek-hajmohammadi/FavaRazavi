<?php

class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {
        /*
        statuslevel=3 / غلط / غلط تورال
        statuslevel=4 درست تورال
        statuslevel=5 غلط آرمان
        statuslevel=6 درست / درست آرمان

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
        switch ($type) {
            case '1':
                $type_ARAMAN = '1';
                break;
            case '3':
                $type_ARAMAN = '2';
                break;
            case '33':
                $type_ARAMAN = '2';
                break;
            case '4':
                $type_ARAMAN = '11';
                break;
            case '44':
                $type_ARAMAN = '11';
                break;
            case '11':
                $type_ARAMAN = '2';
                break;
            case '111':
                $type_ARAMAN = '10';
                break;
        }
        $codem = $execution->workflow->myForm->getFieldValueByName('Field_6');
        $dateR = $execution->workflow->myForm->getFieldValueByName('Field_2');
        $dateR_ARAMAN = STR_REPLACE('/', '-', $dateR);
        $dateB = $execution->workflow->myForm->getFieldValueByName('Field_3');
        $dateB_ARAMAN = Date::GregToJalali((new DateTime(Date::JalaliToGreg($dateB)))->add(new DateInterval("P1D"))->format('Y-m-d'));
        $dateB_ARAMAN = STR_REPLACE('/', '-', $dateB_ARAMAN);
        $shifty = $execution->workflow->myForm->getFieldValueByName('Field_12');

        //--------------------
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

        $execution->workflow->myForm->setFieldValueByName('Field_15', $MandeMorkhasi);
        $execution->workflow->myForm->setFieldValueByName('Field_16', $MandeMorkhasiMerg);

        $ModatMorkhasiINT = intval(substr($ModatMorkhasi, 0, stripos($ModatMorkhasi, 'روز')));
        if ($Type == 1 && $RID != 8651 && $MandeMorkhasi == 'خطا') {
            $newReferNote = 'مانده مرخصی خطا دارد';
            $execution->workflow->myForm->setFieldValueByName('Field_8', '90 BPD / ' . $newReferNote);
            $execution->setVariable('statuslevel', 90);
            $execution->setVariable('newReferNote', $newReferNote);
        } else if ($Type == 1 && $RID != 8651 && $MandeMorkhasiStatus == 'manfi') {
            $newReferNote = 'مانده مرخصی منفی است';
            $execution->workflow->myForm->setFieldValueByName('Field_8', '90 BPD / ' . $newReferNote);
            $execution->setVariable('statuslevel', 90);
            $execution->setVariable('newReferNote', $newReferNote);
        } else if ($Type == 1 && $RID != 8651 && $ModatMorkhasiINT > intval($day)) {
            $newReferNote = 'مدت مرخصی بیشتر از مانده مرخصی است ';
            $execution->workflow->myForm->setFieldValueByName('Field_8', '90 BPD / ' . $newReferNote);
            $execution->setVariable('statuslevel', 90);
            $execution->setVariable('newReferNote', $newReferNote);
        } else {
            if ($type == '44' || $type == '3' || $type == '33' || $type == '11' || $shifty == true) {
                if ($type == '33') $type = '3';
                if ($type == '44') $type = '4';
                $s1 = "EXEC [TimeRecordMV].[dbo].[iVacation_Tamin] '" . $codem . "','" . $dateR . "','" . $dateB . "'," . $type . ",1";
            } else {
                if ($type == '111') $type = '11';
                $s1 = "EXEC [TimeRecordMV].[dbo].[iVacation_Tamin] '" . $codem . "','" . $dateR . "','" . $dateB . "'," . $type . ",0";
            }
            $s1 = urlencode($s1);
            $client = new SoapClient('http://10.10.100.15/WSTuralInOut/TuralInOut.asmx?wsdl');
            $param = array('username' => '8bfc0e61722d9e9c9bb2138cb359fef9', 'password' => '085c734188fb09a96eba5d22893a44c4', 'objStr' => $s1);
            $resp1 = $client->RunQuery($param);
            $result1 = json_decode(json_encode($resp1), true);
            if (is_array($result1) && isset($result1['RunQueryResult']) && $result1['RunQueryResult']) {
                if ($type == '10') {
                    $execution->workflow->myForm->setFieldValueByName('Field_8', '6 / تورال ثبت شد / آرمان لازم نیست');
                    $newReferNote = 'تورال ثبت شد / آرمان لازم نیست';
                    $execution->setVariable('statuslevel', 6);
                    $execution->setVariable('newReferNote', $newReferNote);
                    $execution->workflow->myForm->setFieldValueByName('Field_14', 'No Arman');
                } else {
                    $execution->workflow->myForm->setFieldValueByName('Field_8', '4 / تورال ثبت شد / آرمان ثبت نشد');
                    $newReferNote = 'تورال ثبت شد / آرمان ثبت نشد';
                    $execution->setVariable('statuslevel', 3);
                    $execution->setVariable('newReferNote', $newReferNote);

                    $server = "http://10.10.10.118:84/Services/PrsServices.svc/Prs/";
                    $url = $server . "addLeaveByNationalCode/";
                    $url .= (string)$systemIDـARAMAN . "/";
                    $url .= (string)$userـARAMAN . "/";
                    $url .= (string)$passـARAMAN . "/";
                    $url .= (string)$type_ARAMAN . "/";
                    $url .= (string)$codem . "/";
                    $url .= (string)$dateR_ARAMAN . "/";
                    $url .= (string)$dateB_ARAMAN . "/";
                    $url .= (string)$docDate_ARAMAN . "/";
                    $url .= "temp/";
                    $url .= (string)$docID . "/";
                    $url .= 'RAVAN';

                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_RETURNTRANSFER => 1,
                        CURLOPT_URL => $url,
                    ));
                    $resp = curl_exec($curl);
                    curl_close($curl);
                    $resp = json_decode(json_decode($resp, true), true);

                    if ($resp['Error'][0]['Result'] == 'True') {
                        $execution->workflow->myForm->setFieldValueByName('Field_8', '6 / تورال ثبت شد / آرمان ثبت شد');
                        $newReferNote = 'تورال ثبت شد / آرمان ثبت شد';
                        $execution->setVariable('statuslevel', 6);
                        $execution->setVariable('newReferNote', $newReferNote);
                        $execution->workflow->myForm->setFieldValueByName('Field_14', $url);
                    } else {
                        $Error_Arman = $resp['Error'][0]['Description'];
                        $execution->workflow->myForm->setFieldValueByName('Field_8', '5 / تورال ثبت شد / آرمان ثبت نشد / ' . $Error_Arman);
                        $newReferNote = 'تورال ثبت شد / آرمان ثبت نشد';
                        $execution->setVariable('statuslevel', 3);
                        $execution->setVariable('newReferNote', $newReferNote);
                        $execution->workflow->myForm->setFieldValueByName('Field_14', $url);
                    }
                }
            } else {
                $execution->workflow->myForm->setFieldValueByName('Field_8', '3 / تورال ثبت نشد / آرمان ثبت نشد');
                $newReferNote = 'تورال ثبت شد / آرمان ثبت نشد';
                $execution->setVariable('statuslevel', 3);
                $execution->setVariable('newReferNote', $newReferNote);
            }
        }

    }
}

