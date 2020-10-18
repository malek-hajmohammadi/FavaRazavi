<?php

class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution, ezcWorkflowNodeAction $caller = null)
    {
        $execution->setVariable('newReferNote', '');
        $ACM = AccessControlManager::getInstance();
        $RID = $ACM->getRoleID();
        //RID = 8651 مدير امور اداري موسسه تامين و پشتيباني رضوي
        //if($RID != 8651 && ...)
        $docID = $execution->workflow->myForm->instanceID;
        $Name = $execution->workflow->myForm->getFieldValueByName('Field_1');
        $Type = $execution->workflow->myForm->getFieldValueByName('Field_10');
        $codem = $execution->workflow->myForm->getFieldValueByName('Field_6');
        $ModatMorkhasi = $execution->workflow->myForm->getFieldValueByName('Field_4');

        if ($Type == 1) $Type2 = 'استحقاقی';
        if ($Type == 3 || $Type == 33) $Type2 = 'استعلاجی';
        if ($Type == 4 || $Type == 44) $Type2 = 'بدون حقوق';
        if ($Type == 11) $Type2 = 'زایمان';
        if ($Type == 111) $Type2 = 'اضطراری';
        if ($Type == 10) $Type2 = 'استراحت';
        switch ($Type) {
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
        $newTitle = 'مرخصی ' . $Type2 . ' (تامین) ' . $Name;
        $sql_title = "UPDATE oa_document SET DocDesc='" . $newTitle . "', Subject='" . $newTitle . "' WHERE RowID=" . $docID . " LIMIT 1";
        MySQLAdapter::getInstance()->execute($sql_title);


        $tedad = 0;
        $dateR = $execution->workflow->myForm->getFieldValueByName('Field_2');
        $dateE = explode('/', $dateR);
        $date1 = $dateE[0] . "/" . $dateE[1] . "/01";
        if ($dateE[1] >= 1 && $dateE[1] <= 6) $date2 = $dateE[0] . "/" . $dateE[1] . "/31";
        else if ($dateE[1] >= 7 && $dateE[1] <= 11) $date2 = $dateE[0] . "/" . $dateE[1] . "/30";
        else if ($dateE[1] == 12) $date2 = $dateE[0] . "/" . $dateE[1] . "/29";
        $dd1 = Date::JalaliToGreg($date1);
        $dd2 = Date::JalaliToGreg($date2);

        $sql = "SELECT count(docid) FROM `dm_datastoretable_931` 
        LEFT JOIN oa_document on (oa_document.rowid = dm_datastoretable_931.docid) 
        WHERE  oa_document.isenable = 1 
        AND `Field_8` = '6 / تورال ثبت شد / آرمان لازم نیست' 
        AND `Field_6` = '$codem' 
        AND `Field_2` between '$dd1' AND '$dd2' 
        ";
        $tedad = MySQLAdapter::getInstance()->executeScalar($sql);
        $execution->workflow->myForm->setFieldValueByName('Field_8', $tedad);
        if ($RID != 8651 && $tedad > 4) {
            $execution->setVariable('toprole', 90);
            $execution->workflow->myForm->setFieldValueByName('Field_8', $tedad . ' / تعداد استراحت بیش از حد است / !برگشت از پردازش نام');
        } else {
            //toprole=0 کاربر
            //toprole=1 ریس به بالا
            //toprole=99 اتمام
            //toprole=90 مدت بیش از مجاز

            $Status = $execution->workflow->myForm->getFieldValueByName('Field_8');
            if ($Status == '') $execution->setVariable('toprole', 99);
            else {
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
                        $dayMandeMorkhasi = $MandeMorkhasi[0];
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
                    $MandeMorkhasiNahaeeMerg = ((intval($day) * 24) * 60) + (intval($hour) * 60) + intval($min);
                    $MandeMorkhasiNahaeeMerg = $day . '-' . $hour . '-' . $min . '-' . $MandeMorkhasiNahaeeMerg . '-' . $MandeMorkhasiNahaeeStatus;
                } else {
                    $MandeMorkhasiNahaeeMerg = 0;
                }
                //--------------------
                $execution->workflow->myForm->setFieldValueByName('Field_15', $MandeMorkhasi);
                $execution->workflow->myForm->setFieldValueByName('Field_16', $MandeMorkhasiMerg);

                $ModatMorkhasiINT = intval(substr($ModatMorkhasi, 0, stripos($ModatMorkhasi, 'روز')));
                if ($Type == 1 && $RID != 8651 && $MandeMorkhasi == 'خطا') {
                    $newReferNote = 'مانده مرخصی خطا دارد';
                    $execution->workflow->myForm->setFieldValueByName('Field_8', '90 BPN / ' . $newReferNote);
                    $execution->setVariable('toprole', 90);
                    $execution->setVariable('newReferNote', $newReferNote);
                } else if ($Type == 1 && $RID != 8651 && $MandeMorkhasiStatus == 'manfi') {
                    $newReferNote = 'مانده مرخصی منفی است';
                    $execution->workflow->myForm->setFieldValueByName('Field_8', '90 BPN / ' . $newReferNote);
                    $execution->setVariable('toprole', 90);
                    $execution->setVariable('newReferNote', $newReferNote);
                } else if ($Type == 1 && $RID != 8651 && $ModatMorkhasiINT > intval($dayMandeMorkhasi)) {
                    $newReferNote = 'مدت مرخصی بیشتر از مانده مرخصی است ';
                    $execution->workflow->myForm->setFieldValueByName('Field_8', '90 BPN / ' . $newReferNote);
                    $execution->setVariable('toprole', 90);
                    $execution->setVariable('newReferNote', $newReferNote);
                }
                /*
                else if($Type == 1 && $RID != 8651 && $ModatMorkhasiINT > 6)
                {
                    $newReferNote = 'مدت مرخصی نباید بیش از ۵ روز باشد ';
                    $execution->workflow->myForm->setFieldValueByName('Field_8','90 BPN / '.$newReferNote);
                    $execution->setVariable('toprole',90);
                    $execution->setVariable('newReferNote',$newReferNote);
                }
                */
            }
        }
    }
}

