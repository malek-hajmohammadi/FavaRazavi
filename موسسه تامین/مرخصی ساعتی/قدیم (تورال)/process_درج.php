<?php
class calssName{
    public function __construct(){}
    public function execute(ezcWorkflowExecution $execution)
    {
        /*
        statuslevel=3 / غلط / غلط تورال
        statuslevel=4 درست تورال
        statuslevel=5 غلط آرمان
        statuslevel=6 درست / درست آرمان

        [1, "مرخصي اول وقت"],
        [2, "مرخصي ميان وقت"],
        [3, "مرخصي آخر وقت"]
        */

        $ACM = AccessControlManager::getInstance();
        $RID = $ACM->getRoleID();
        //RID = 8651 مدير امور اداري موسسه تامين و پشتيباني رضوي
        //if($RID != 8651 && ...)

        $execution->setVariable('statuslevel',3);
        $docID = $execution->workflow->myForm->instanceID;

        $docDate = Date::GregToJalali(MySQLAdapter::getInstance()->executeScalar("SELECT CreateDate From oa_document where RowID = $docID"));
        $docDate_ARAMAN = STR_REPLACE('/','-',$docDate);
        $codem = $execution->workflow->myForm->getFieldValueByName('Field_4');
        $type = $execution->workflow->myForm->getFieldValueByName('Field_3');
        switch ($type)
        {
            /*
            case 1: $type_ARMAN = 'روان - مرخصی اول وقت'; break;
            case 2: $type_ARMAN = 'روان - مرخصی میان وقت'; break;
            case 3: $type_ARMAN = 'روان - مرخصی آخر وقت'; break;
            */
            case 1: $type_ARMAN = 'RAVAN-FirstTime'; break;
            case 2: $type_ARMAN = 'RAVAN-MiddleTime'; break;
            case 3: $type_ARMAN = 'RAVAN-LastTime'; break;
        }
        /*$type_ARMAN = urlencode($type_ARMAN);*/
        $dateD = $execution->workflow->myForm->getFieldValueByName('Field_1');
        $dateD_ARAMAN = STR_REPLACE('/','-',$dateD);

        $timeR = $execution->workflow->myForm->getFieldValueByName('Field_2');
        $timeR = explode(':',$timeR);
        if(intval($timeR[0])<10){
            $timeR[0]=trim($timeR[0],'0');
            $timeR[0]='0'.$timeR[0];
        }
        if($timeR[0]=='0')
            $timeR[0]='00';
        if(intval($timeR[1])<10){
            $timeR[1]=trim($timeR[1],'0');
            $timeR[1]='0'.$timeR[1];
        }
        if($timeR[1]=='0')$timeR[1]='00';
        $timeR=implode(':',$timeR);
        $timeR_ARAMAN = STR_REPLACE(':','-',$timeR);

        $timeB = $execution->workflow->myForm->getFieldValueByName('Field_14');
        $timeB = explode(':',$timeB);
        if(intval($timeB[0])<10){
            $timeB[0]=trim($timeB[0],'0');
            $timeB[0]='0'.$timeB[0];
        }
        if($timeB[0]=='0')
            $timeB[0]='00';
        if(intval($timeB[1])<10){
            $timeB[1]=trim($timeB[1],'0');
            $timeB[1]='0'.$timeB[1];
        }
        if($timeB[1]=='0')$timeB[1]='00';
        $timeB=implode(':',$timeB);
        $timeB_ARAMAN = STR_REPLACE(':','-',$timeB);

        //--------------------
        $type_ARAMAN = '1';
        $systemIDـARAMAN = "10101086"; //کد سیستم آرمان
        $userـARAMAN = "ravanrequest"; //یوزر آرمان
        $passـARAMAN = "4H8_gHf2X2@y7Wt"; //پسورد آرمان
        $server = "http://10.10.10.118:84/Services/PrsServices.svc/Prs/";
        $url = $server."getLeaveMod/";
        $url .= (string)$systemIDـARAMAN."/";
        $url .= (string)$userـARAMAN."/";
        $url .= (string)$passـARAMAN."/";
        $url .= (string)$type_ARAMAN."/";
        $url .= (string)$codem;

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
        ));
        $resp = curl_exec($curl);
        curl_close($curl);
        $resp = json_decode(json_decode($resp, true),true);

        if($resp['Error'][0]['Result'] == 'True')
        {
            $MandeMorkhasi = $resp['data'][0]['Mod_Leave'];
            $MandeMorkhasiOO = $MandeMorkhasi;
            if($MandeMorkhasi != 0)
            {
                $MandeMorkhasiStatus = 'mosbat';
                $offset = strpos($MandeMorkhasi,'-');
                if ($offset !== FALSE){
                    $MandeMorkhasi = substr($MandeMorkhasi,1);
                    $MandeMorkhasiStatus = 'manfi';
                }
                $MandeMorkhasi = explode('.',$MandeMorkhasi);
                $day = $MandeMorkhasi[0];
                $day = strlen($day)==1 ? "0".$day : $day;
                if($MandeMorkhasi[1] != NULL) $hourmin = '0.'.$MandeMorkhasi[1];
                else $hourmin = 0;
                $hourmin = $hourmin * 440;
                $hourmin = $hourmin / 60;
                $hourmin = explode('.',$hourmin);
                $min = "0.".$hourmin[1];
                $min = $min * 60;
                $min = $min % 60;
                $min = strlen($min)==1 ? "0".$min : $min;
                $hour = $hourmin[0];
                $hour = $hour + floor($min/60);
                $hour = strlen($hour)==1 ? "0".$hour : $hour;
                if($day != 00 && $hour != 00 && $min != 00) $MandeMorkhasi = $day.'روز و '.$hour.'ساعت و '.$min.'دقيقه';
                elseif($day != 00 && $hour != 00 && $min == 00) $MandeMorkhasi = $day.'روز و '.$hour.'ساعت';
                elseif($day != 00 && $hour == 00 && $min != 00) $MandeMorkhasi = $day.'روز و '.$min.'دقيقه';
                elseif($day == 00 && $hour != 00 && $min != 00) $MandeMorkhasi = $hour.'ساعت و '.$min.'دقيقه';
                elseif($day == 00 && $hour == 00 && $min != 00) $MandeMorkhasi = $min.'دقيقه';
                else $MandeMorkhasi = '0';
                if($MandeMorkhasiStatus == 'manfi') $MandeMorkhasi = 'منفی '.$MandeMorkhasi;
                $MandeMorkhasiMerg2 = ((intval($day)*24)*60)+(intval($hour)*60)+intval($min);
                $MandeMorkhasiMerg = $day.'-'.$hour.'-'.$min.'-'.$MandeMorkhasiMerg2.'-'.$MandeMorkhasiStatus;
            }
            else{
                $MandeMorkhasiMerg = 0;
            }
        }
        else
        {
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
        $url = $server."getLeaveModPreYear/";
        $url .= (string)$systemIDـARAMAN."/";
        $url .= (string)$userـARAMAN."/";
        $url .= (string)$passـARAMAN."/";
        $url .= (string)$type_ARAMAN."/";
        $url .= (string)$codem;
        $url .= "/1397-12-29";

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
        ));
        $resp = curl_exec($curl);
        curl_close($curl);
        $resp = json_decode(json_decode($resp, true),true);
        if($resp['Error'][0]['Result'] == 'True')
        {
            $MandeMorkhasi2 = $resp['data'][0]['Mod_Leave'];
            $MandeMorkhasi2OO = $MandeMorkhasi2;
            if($MandeMorkhasi2 != 0)
            {
                $MandeMorkhasi2Status = 'mosbat';
                $offset = strpos($MandeMorkhasi2,'-');
                if ($offset !== FALSE){
                    $MandeMorkhasi2 = substr($MandeMorkhasi2,1);
                    $MandeMorkhasi2Status = 'manfi';
                }
                $MandeMorkhasi2 = explode('.',$MandeMorkhasi2);
                $day = $MandeMorkhasi2[0];
                $day = strlen($day)==1 ? "0".$day : $day;
                if($MandeMorkhasi2[1] != NULL) $hourmin = '0.'.$MandeMorkhasi2[1];
                else $hourmin = 0;
                $hourmin = $hourmin * 440;
                $hourmin = $hourmin / 60;
                $hourmin = explode('.',$hourmin);
                $min = "0.".$hourmin[1];
                $min = $min * 60;
                $min = $min % 60;
                $min = strlen($min)==1 ? "0".$min : $min;
                $hour = $hourmin[0];
                $hour = $hour + floor($min/60);
                $hour = strlen($hour)==1 ? "0".$hour : $hour;
                if($day != 00 && $hour != 00 && $min != 00) $MandeMorkhasi2 = $day.'روز و '.$hour.'ساعت و '.$min.'دقيقه';
                elseif($day != 00 && $hour != 00 && $min == 00) $MandeMorkhasi2 = $day.'روز و '.$hour.'ساعت';
                elseif($day != 00 && $hour == 00 && $min != 00) $MandeMorkhasi2 = $day.'روز و '.$min.'دقيقه';
                elseif($day == 00 && $hour != 00 && $min != 00) $MandeMorkhasi2 = $hour.'ساعت و '.$min.'دقيقه';
                elseif($day == 00 && $hour == 00 && $min != 00) $MandeMorkhasi2 = $min.'دقيقه';
                else $MandeMorkhasi2 = '0';
                if($MandeMorkhasi2Status == 'manfi') $MandeMorkhasi2 = 'منفی '.$MandeMorkhasi2;
                $MandeMorkhasi2Merg2 = ((intval($day)*24)*60)+(intval($hour)*60)+intval($min);
                $MandeMorkhasi2Merg = $day.'-'.$hour.'-'.$min.'-'.$MandeMorkhasi2Merg2.'-'.$MandeMorkhasi2Status;
            }
            else{
                $MandeMorkhasi2Merg = 0;
            }
        }
        else
        {
            $MandeMorkhasi2 = 'خطا';
            $MandeMorkhasi2Merg = 'خطا';
            $Mod_PreviousYearLeave2 = 'خطا';
            $Mod_ThisYearLeave2 = 'خطا';
            $TakenLeaveInThisYear2 = 'خطا';
            $Num_ThisYearLeaveFromPreviousYear2 = 'خطا';
        }

        //--------------------

        $MandeMorkhasiNahaee = $MandeMorkhasiOO + $MandeMorkhasi2OO;
        if($MandeMorkhasiNahaee != 0)
        {
            $MandeMorkhasiNahaeeStatus = 'mosbat';
            $offset = strpos($MandeMorkhasiNahaee,'-');
            if ($offset !== FALSE){
                $MandeMorkhasiNahaee = substr($MandeMorkhasiNahaee,1);
                $MandeMorkhasiNahaeeStatus = 'manfi';
            }
            $MandeMorkhasiNahaee = explode('.',$MandeMorkhasiNahaee);
            $day = $MandeMorkhasiNahaee[0];
            $day = strlen($day)==1 ? "0".$day : $day;
            if($MandeMorkhasiNahaee[1] != NULL) $hourmin = '0.'.$MandeMorkhasiNahaee[1];
            else $hourmin = 0;
            $hourmin = $hourmin * 440;
            $hourmin = $hourmin / 60;
            $hourmin = explode('.',$hourmin);
            $min = "0.".$hourmin[1];
            $min = $min * 60;
            $min = $min % 60;
            $min = strlen($min)==1 ? "0".$min : $min;
            $hour = $hourmin[0];
            $hour = $hour + floor($min/60);
            $hour = strlen($hour)==1 ? "0".$hour : $hour;
            if($day != 00 && $hour != 00 && $min != 00) $MandeMorkhasiNahaee = $day.'روز و '.$hour.'ساعت و '.$min.'دقيقه';
            elseif($day != 00 && $hour != 00 && $min == 00) $MandeMorkhasiNahaee = $day.'روز و '.$hour.'ساعت';
            elseif($day != 00 && $hour == 00 && $min != 00) $MandeMorkhasiNahaee = $day.'روز و '.$min.'دقيقه';
            elseif($day == 00 && $hour != 00 && $min != 00) $MandeMorkhasiNahaee = $hour.'ساعت و '.$min.'دقيقه';
            elseif($day == 00 && $hour == 00 && $min != 00) $MandeMorkhasiNahaee = $min.'دقيقه';
            else $MandeMorkhasiNahaee = '0';
            if($MandeMorkhasiNahaeeStatus == 'manfi') $MandeMorkhasiNahaee = 'منفی '.$MandeMorkhasiNahaee;
            $MandeMorkhasiNahaeeMerg2 = ((intval($day)*24)*60)+(intval($hour)*60)+intval($min);
            $MandeMorkhasiNahaeeMerg = $day.'-'.$hour.'-'.$min.'-'.$MandeMorkhasiNahaeeMerg2.'-'.$MandeMorkhasiNahaeeStatus;
        }
        else{
            $MandeMorkhasiNahaee = 0;
            $MandeMorkhasiNahaeeMerg = 0;
        }
        //--------------------
        $execution->workflow->myForm->setFieldValueByName('Field_10',$MandeMorkhasiNahaee);
        $execution->workflow->myForm->setFieldValueByName('Field_12',$MandeMorkhasiNahaeeMerg);
        $ModatMorkhasi =  $execution->workflow->myForm->getFieldValueByName('Field_15');
        $ModatMorkhasi = explode(':');
        $ModatMorkhasi = (intval($ModatMorkhasi[0])*60)+$ModatMorkhasi[1];

        if($RID != 8651 && $MandeMorkhasiNahaee == 'خطا')
        {
            $execution->setVariable('statuslevel',90);
            $execution->workflow->myForm->setFieldValueByName('Field_9','90 / مانده مرخصی خطا دارد / برگشت از پردازش نام');
        }
        else if($RID != 8651 && $MandeMorkhasiNahaeeStatus == 'manfi')
        {
            $execution->setVariable('statuslevel',90);
            $execution->workflow->myForm->setFieldValueByName('Field_9','90 / مانده مرخصی منفی است / برگشت از پردازش نام');
        }
        else if($RID != 8651 && $ModatMorkhasi > $MandeMorkhasiNahaeeMerg2)
        {
            $execution->setVariable('statuslevel',90);
            $execution->workflow->myForm->setFieldValueByName('Field_9','90 / مدت مرخصی بیشتر از مانده مرخصی است / برگشت از پردازش نام');
        }
        else
        {
            if($type == 1)
            {
                $s2 = "EXEC InOutData.dbo.[dTaradod_Tamin] '".$codem."', '".$dateD."', '".$timeB."'";
                //$s2 = "EXEC InOutData.dbo.[iNormalTaradod_Tamin] '".$codem."','".$dateD."','".$timeB."'";
                $s2 = urlencode($s2);
                $client = new SoapClient('http://10.10.100.15/WSTuralInOut/TuralInOut.asmx?wsdl');
                $param  = array('username' => '8bfc0e61722d9e9c9bb2138cb359fef9','password' => '085c734188fb09a96eba5d22893a44c4','objStr' => $s2);
                $resp2 = $client->RunQuery($param);
                $result2 = json_decode(json_encode($resp2), true);

                $s3 = "EXEC InOutData.dbo.[iNormalTaradod_Tamin] '".$codem."','".$dateD."','".$timeB."'";
                $s3 = urlencode($s3);
                $client = new SoapClient('http://10.10.100.15/WSTuralInOut/TuralInOut.asmx?wsdl');
                $param  = array('username' => '8bfc0e61722d9e9c9bb2138cb359fef9','password' => '085c734188fb09a96eba5d22893a44c4','objStr' => $s3);
                $resp3 = $client->RunQuery($param);
                $result3 = json_decode(json_encode($resp3), true);

                $s1="EXEC [InOutData].[dbo].[TaminHourlyVacationMission] '".$codem."','".$dateD."','".$timeB."',1";
                $s1 = urlencode($s1);
                $client = new SoapClient('http://10.10.100.15/WSTuralInOut/TuralInOut.asmx?wsdl');
                $param  = array('username' => '8bfc0e61722d9e9c9bb2138cb359fef9','password' => '085c734188fb09a96eba5d22893a44c4','objStr' => $s1);
                $resp1 = $client->RunQuery($param);
                $result1 = json_decode(json_encode($resp1), true);
            }
            else if($type == 3)
            {
                $s2 = "EXEC InOutData.dbo.[dTaradod_Tamin] '".$codem."', '".$dateD."', '".$timeR."'";
                //$s2 = "EXEC InOutData.dbo.[iNormalTaradod_Tamin] '".$codem."','".$dateD."','".$timeR."'";
                $s2 = urlencode($s2);
                $client = new SoapClient('http://10.10.100.15/WSTuralInOut/TuralInOut.asmx?wsdl');
                $param  = array('username' => '8bfc0e61722d9e9c9bb2138cb359fef9','password' => '085c734188fb09a96eba5d22893a44c4','objStr' => $s2);
                $resp2 = $client->RunQuery($param);
                $result2 = json_decode(json_encode($resp2), true);

                $s3="EXEC InOutData.dbo.[iNormalTaradod_Tamin] '".$codem."','".$dateD."','".$timeR."'";
                $s3 = urlencode($s3);
                $client = new SoapClient('http://10.10.100.15/WSTuralInOut/TuralInOut.asmx?wsdl');
                $param  = array('username' => '8bfc0e61722d9e9c9bb2138cb359fef9','password' => '085c734188fb09a96eba5d22893a44c4','objStr' => $s3);
                $resp3 = $client->RunQuery($param);
                $result3 = json_decode(json_encode($resp3), true);

                $s1="EXEC [InOutData].[dbo].[TaminHourlyVacationMission] '".$codem."','".$dateD."','".$timeR."',1";
                $s1 = urlencode($s1);
                $client = new SoapClient('http://10.10.100.15/WSTuralInOut/TuralInOut.asmx?wsdl');
                $param  = array('username' => '8bfc0e61722d9e9c9bb2138cb359fef9','password' => '085c734188fb09a96eba5d22893a44c4','objStr' => $s1);
                $resp1 = $client->RunQuery($param);
                $result1 = json_decode(json_encode($resp1), true);
            }
            else if($type == 2)
            {
                $s2 = "EXEC InOutData.dbo.[iNormalTaradod_Tamin] '".$codem."','".$dateD."','".$timeR."'";
                $s2 = urlencode($s2);
                $client = new SoapClient('http://10.10.100.15/WSTuralInOut/TuralInOut.asmx?wsdl');
                $param  = array('username' => '8bfc0e61722d9e9c9bb2138cb359fef9','password' => '085c734188fb09a96eba5d22893a44c4','objStr' => $s2);
                $resp2 = $client->RunQuery($param);
                $result2 = json_decode(json_encode($resp2), true);

                $s3="EXEC InOutData.dbo.[iNormalTaradod_Tamin] '".$codem."','".$dateD."','".$timeB."'";
                $s3 = urlencode($s3);
                $client = new SoapClient('http://10.10.100.15/WSTuralInOut/TuralInOut.asmx?wsdl');
                $param  = array('username' => '8bfc0e61722d9e9c9bb2138cb359fef9','password' => '085c734188fb09a96eba5d22893a44c4','objStr' => $s3);
                $resp3 = $client->RunQuery($param);
                $result3 = json_decode(json_encode($resp3), true);

                $s1="EXEC [InOutData].[dbo].[TaminHourlyVacationMission] '".$codem."','".$dateD."','".$timeR."',1";
                $s1 = urlencode($s1);
                $client = new SoapClient('http://10.10.100.15/WSTuralInOut/TuralInOut.asmx?wsdl');
                $param  = array('username' => '8bfc0e61722d9e9c9bb2138cb359fef9','password' => '085c734188fb09a96eba5d22893a44c4','objStr' => $s1);
                $resp1 = $client->RunQuery($param);
                $result1 = json_decode(json_encode($resp1), true);
            }

            if(is_array($result1) && isset($result1['RunQueryResult']) && $result1['RunQueryResult'] && is_array($result2) && isset($result2['RunQueryResult']) && $result2['RunQueryResult'] && is_array($result3) && isset($result3['RunQueryResult']) && $result3['RunQueryResult'])
            {
                $execution->setVariable('statuslevel',3);
                $execution->workflow->myForm->setFieldValueByName('Field_9','4 / تورال ثبت شد / آرمان ثبت نشد');

                $server = "http://10.10.10.118:84/Services/PrsServices.svc/Prs/";
                $url = $server."addLeaveByNationalCode/";
                $url .= (string)$systemIDـARAMAN."/";
                $url .= (string)$userـARAMAN."/";
                $url .= (string)$passـARAMAN."/";
                $url .= "5/";
                $url .= (string)$codem."/";
                $url .= (string)$timeR_ARAMAN."/";
                $url .= (string)$timeB_ARAMAN."/";
                $url .= (string)$docDate_ARAMAN."/";
                $url .= (string)$dateD_ARAMAN."/";
                $url .= (string)$docID."/";
                $url .= (string)$type_ARMAN;

                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_URL => $url,
                ));
                $resp = curl_exec($curl);
                curl_close($curl);
                $resp = json_decode(json_decode($resp, true),true);

                if($resp['Error'][0]['Result'] == 'True')
                {
                    $execution->setVariable('statuslevel',6);
                    $execution->workflow->myForm->setFieldValueByName('Field_9','6 / تورال ثبت شد / آرمان ثبت شد');
                    $execution->workflow->myForm->setFieldValueByName('Field_13',$url);
                }
                else
                {
                    $Error_Arman = $resp['Error'][0]['Description'];
                    $execution->workflow->myForm->setFieldValueByName('Field_9','5 / تورال ثبت شد / آرمان ثبت نشد / '.$Error_Arman);
                    $execution->setVariable('statuslevel',3);
                    $execution->workflow->myForm->setFieldValueByName('Field_13',$url);
                }
            }
            else
            {
                $execution->setVariable('statuslevel',3);
                $execution->workflow->myForm->setFieldValueByName('Field_9','3 / تورال ثبت نشد / آرمان ثبت نشد');
            }
        }
    }
}

?>