<?php
$mm = Request::getInstance()->varCleanFromInput('mm');
$yy = Request::getInstance()->varCleanFromInput('yy');
$codem = Request::getInstance()->varCleanFromInput('codem');
$mmnow = Request::getInstance()->varCleanFromInput('mmnow');
$ddnow = Request::getInstance()->varCleanFromInput('ddnow');
$yynow = Request::getInstance()->varCleanFromInput('yynow');
$csv = Request::getInstance()->varCleanFromInput('csv');
$yy = $yy - 1;
//$db = MySQLAdapter::getInstance();
$db = PDOAdapter::getInstance();

$ACM = AccessControlManager::getInstance();
$RID = $ACM->getRoleID();
$UID = $ACM->getUserID();
//-----------------------------------

$tedad = 0;
$d1 = $yy."/".$mm."/01";
if($mm >= 1 && $mm <= 6) $d2= $yy."/".$mm."/31";
else if($mm >= 7 && $mm <= 11) $d2= $yy."/".$mm."/30";
else if($mm == 12 ) $d2= $yy."/".$mm."/29";
$dd1 = Date::JalaliToGreg($d1);
$dd2 = Date::JalaliToGreg($d2);

$sql = "SELECT count(docid) FROM `dm_datastoretable_895` 
LEFT JOIN oa_document on (oa_document.rowid = dm_datastoretable_895.docid) 
WHERE  oa_document.isenable = 1 
/*AND oa_document.DocStatus = 0 */
AND (`Field_7` = '1' OR `Field_7` = 'در تورال ثبت شد') 
AND `Field_8` = '$codem' 
AND `Field_2` between '$dd1' AND '$dd2' 
";
$tedad = $db->executeScalar($sql);

switch($mm){
    case 1: $mmName = "فروردين"; break;
    case 2: $mmName = "ارديبهشت"; break;
    case 3: $mmName = "خرداد"; break;
    case 4: $mmName = "تير"; break;
    case 5: $mmName="مرداد"; break;
    case 6: $mmName="شهريور"; break;
    case 7: $mmName="مهر"; break;
    case 8: $mmName="آبان"; break;
    case 9: $mmName="آذر"; break;
    case 10: $mmName="دي"; break;
    case 11: $mmName="بهمن"; break;
    case 12: $mmName="اسفند"; break;
    default: $mmName="---";
}

//ARMAN--------------------
$type_ARAMAN = '1';
$systemIDـARAMAN = "10101086"; //كد سيستم آرمان
$userـARAMAN = "ravanrequest"; //يوزر آرمان
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
        elseif($day != 00 && $hour == 00 && $min == 00) $MandeMorkhasi = $day.'روز';
        else $MandeMorkhasi = '0';
        if($MandeMorkhasiStatus == 'manfi') $MandeMorkhasi = 'منفي '.$MandeMorkhasi;
        $MandeMorkhasiMerg2 = ((intval($day)*24)*60)+(intval($hour)*60)+intval($min);
        $MandeMorkhasiMerg = $day.'-'.$hour.'-'.$min.'-'.$MandeMorkhasiMerg2.'-'.$MandeMorkhasiStatus;
    }
    else{
        $MandeMorkhasiMerg = 0;
    }






    //--Mod_PreviousYearLeave
    $Mod_PreviousYearLeave = $resp['data'][0]['Mod_PreviousYearLeave'];
    if($Mod_PreviousYearLeave != 0)
    {
        $Mod_PreviousYearLeaveStatus = 'mosbat';
        $offset = strpos($Mod_PreviousYearLeave,'-');
        if ($offset !== FALSE){
            $Mod_PreviousYearLeave = substr($Mod_PreviousYearLeave,1);
            $Mod_PreviousYearLeaveStatus = 'manfi';
        }
        $Mod_PreviousYearLeave = explode('.',$Mod_PreviousYearLeave);
        $day = $Mod_PreviousYearLeave[0];
        $day = strlen($day)==1 ? "0".$day : $day;
        if($Mod_PreviousYearLeave[1] != NULL) $hourmin = '0.'.$Mod_PreviousYearLeave[1];
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
        if($day != 00 && $hour != 00 && $min != 00) $Mod_PreviousYearLeave = $day.'روز و '.$hour.'ساعت و '.$min.'دقيقه';
        elseif($day != 00 && $hour != 00 && $min == 00) $Mod_PreviousYearLeave = $day.'روز و '.$hour.'ساعت';
        elseif($day != 00 && $hour == 00 && $min != 00) $Mod_PreviousYearLeave = $day.'روز و '.$min.'دقيقه';
        elseif($day == 00 && $hour != 00 && $min != 00) $Mod_PreviousYearLeave = $hour.'ساعت و '.$min.'دقيقه';
        elseif($day == 00 && $hour == 00 && $min != 00) $Mod_PreviousYearLeave = $min.'دقيقه';
        elseif($day != 00 && $hour == 00 && $min == 00) $Mod_PreviousYearLeave = $day.'روز';
        else $Mod_PreviousYearLeave = '0';
        if($Mod_PreviousYearLeaveStatus == 'manfi') $Mod_PreviousYearLeave = 'منفي '.$Mod_PreviousYearLeave;
    }

    //--Mod_ThisYearLeave
    $Mod_ThisYearLeave = $resp['data'][0]['Mod_ThisYearLeave'];
    if($Mod_ThisYearLeave != 0)
    {
        $Mod_ThisYearLeaveStatus = 'mosbat';
        $offset = strpos($Mod_ThisYearLeave,'-');
        if ($offset !== FALSE){
            $Mod_ThisYearLeave = substr($Mod_ThisYearLeave,1);
            $Mod_ThisYearLeaveStatus = 'manfi';
        }
        $Mod_ThisYearLeave = explode('.',$Mod_ThisYearLeave);
        $day = $Mod_ThisYearLeave[0];
        $day = strlen($day)==1 ? "0".$day : $day;
        if($Mod_ThisYearLeave[1] != NULL) $hourmin = '0.'.$Mod_ThisYearLeave[1];
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
        if($day != 00 && $hour != 00 && $min != 00) $Mod_ThisYearLeave = $day.'روز و '.$hour.'ساعت و '.$min.'دقيقه';
        elseif($day != 00 && $hour != 00 && $min == 00) $Mod_ThisYearLeave = $day.'روز و '.$hour.'ساعت';
        elseif($day != 00 && $hour == 00 && $min != 00) $Mod_ThisYearLeave = $day.'روز و '.$min.'دقيقه';
        elseif($day == 00 && $hour != 00 && $min != 00) $Mod_ThisYearLeave = $hour.'ساعت و '.$min.'دقيقه';
        elseif($day == 00 && $hour == 00 && $min != 00) $Mod_ThisYearLeave = $min.'دقيقه';
        elseif($day != 00 && $hour == 00 && $min == 00) $Mod_ThisYearLeave = $day.'روز';
        else $Mod_ThisYearLeave = '0';
        if($Mod_ThisYearLeaveStatus == 'manfi') $Mod_ThisYearLeave = 'منفي '.$Mod_ThisYearLeave;
    }

    //--TakenLeaveInThisYear
    $TakenLeaveInThisYear = $resp['data'][0]['TakenLeaveInThisYear'];
    if($TakenLeaveInThisYear != 0)
    {
        $TakenLeaveInThisYearStatus = 'mosbat';
        $offset = strpos($TakenLeaveInThisYear,'-');
        if ($offset !== FALSE){
            $TakenLeaveInThisYear = substr($TakenLeaveInThisYear,1);
            $TakenLeaveInThisYearStatus = 'manfi';
        }
        $TakenLeaveInThisYear = explode('.',$TakenLeaveInThisYear);
        $day = $TakenLeaveInThisYear[0];
        $day = strlen($day)==1 ? "0".$day : $day;
        if($TakenLeaveInThisYear[1] != NULL) $hourmin = '0.'.$TakenLeaveInThisYear[1];
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
        if($day != 00 && $hour != 00 && $min != 00) $TakenLeaveInThisYear = $day.'روز و '.$hour.'ساعت و '.$min.'دقيقه';
        elseif($day != 00 && $hour != 00 && $min == 00) $TakenLeaveInThisYear = $day.'روز و '.$hour.'ساعت';
        elseif($day != 00 && $hour == 00 && $min != 00) $TakenLeaveInThisYear = $day.'روز و '.$min.'دقيقه';
        elseif($day == 00 && $hour != 00 && $min != 00) $TakenLeaveInThisYear = $hour.'ساعت و '.$min.'دقيقه';
        elseif($day == 00 && $hour == 00 && $min != 00) $TakenLeaveInThisYear = $min.'دقيقه';
        elseif($day != 00 && $hour == 00 && $min == 00) $TakenLeaveInThisYear = $day.'روز';
        else $TakenLeaveInThisYear = '0';
        if($TakenLeaveInThisYearStatus == 'manfi') $TakenLeaveInThisYear = 'منفي '.$TakenLeaveInThisYear;
    }

    //--Num_ThisYearLeaveFromPreviousYear
    $Num_ThisYearLeaveFromPreviousYear = $resp['data'][0]['Num_ThisYearLeaveFromPreviousYear'];
    if($Num_ThisYearLeaveFromPreviousYear != 0)
    {
        $Num_ThisYearLeaveFromPreviousYearStatus = 'mosbat';
        $offset = strpos($Num_ThisYearLeaveFromPreviousYear,'-');
        if ($offset !== FALSE){
            $Num_ThisYearLeaveFromPreviousYear = substr($Num_ThisYearLeaveFromPreviousYear,1);
            $Num_ThisYearLeaveFromPreviousYearStatus = 'manfi';
        }
        $Num_ThisYearLeaveFromPreviousYear = explode('.',$Num_ThisYearLeaveFromPreviousYear);
        $day = $Num_ThisYearLeaveFromPreviousYear[0];
        $day = strlen($day)==1 ? "0".$day : $day;
        if($Num_ThisYearLeaveFromPreviousYear[1] != NULL) $hourmin = '0.'.$Num_ThisYearLeaveFromPreviousYear[1];
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
        if($day != 00 && $hour != 00 && $min != 00) $Num_ThisYearLeaveFromPreviousYear = $day.'روز و '.$hour.'ساعت و '.$min.'دقيقه';
        elseif($day != 00 && $hour != 00 && $min == 00) $Num_ThisYearLeaveFromPreviousYear = $day.'روز و '.$hour.'ساعت';
        elseif($day != 00 && $hour == 00 && $min != 00) $Num_ThisYearLeaveFromPreviousYear = $day.'روز و '.$min.'دقيقه';
        elseif($day == 00 && $hour != 00 && $min != 00) $Num_ThisYearLeaveFromPreviousYear = $hour.'ساعت و '.$min.'دقيقه';
        elseif($day == 00 && $hour == 00 && $min != 00) $Num_ThisYearLeaveFromPreviousYear = $min.'دقيقه';
        elseif($day != 00 && $hour == 00 && $min == 00) $Num_ThisYearLeaveFromPreviousYear = $day.'روز';
        else $Num_ThisYearLeaveFromPreviousYear = '0';
        if($Num_ThisYearLeaveFromPreviousYearStatus == 'manfi') $Num_ThisYearLeaveFromPreviousYear = 'منفي '.$Num_ThisYearLeaveFromPreviousYear;
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








$type_ARAMAN = '1';
$systemIDـARAMAN = "10101086"; //كد سيستم آرمان
$userـARAMAN = "ravanrequest"; //يوزر آرمان
$passـARAMAN = "4H8_gHf2X2@y7Wt"; //پسورد آرمان
$server = "http://10.10.10.118:84/Services/PrsServices.svc/Prs/";
$url = $server."getLeaveModPreYear/";
$url .= (string)$systemIDـARAMAN."/";
$url .= (string)$userـARAMAN."/";
$url .= (string)$passـARAMAN."/";
$url .= (string)$type_ARAMAN."/";
$url .= (string)$codem;
$url .= "/1398-12-29";

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
        elseif($day != 00 && $hour == 00 && $min == 00) $MandeMorkhasi2 = $day.'روز';
        else $MandeMorkhasi2 = '0';
        if($MandeMorkhasi2Status == 'manfi') $MandeMorkhasi2 = 'منفي '.$MandeMorkhasi2;
        $MandeMorkhasi2Merg2 = ((intval($day)*24)*60)+(intval($hour)*60)+intval($min);
        $MandeMorkhasi2Merg = $day.'-'.$hour.'-'.$min.'-'.$MandeMorkhasi2Merg2.'-'.$MandeMorkhasi2Status;
    }
    else{
        $MandeMorkhasi2Merg = 0;
    }

    //--Mod_PreviousYearLeave
    $Mod_PreviousYearLeave2 = $resp['data'][0]['Mod_PreviousYearLeave'];
    if($Mod_PreviousYearLeave2 != 0)
    {
        $Mod_PreviousYearLeave2Status = 'mosbat';
        $offset = strpos($Mod_PreviousYearLeave2,'-');
        if ($offset !== FALSE){
            $Mod_PreviousYearLeave2 = substr($Mod_PreviousYearLeave2,1);
            $Mod_PreviousYearLeave2Status = 'manfi';
        }
        $Mod_PreviousYearLeave2 = explode('.',$Mod_PreviousYearLeave2);
        $day = $Mod_PreviousYearLeave2[0];
        $day = strlen($day)==1 ? "0".$day : $day;
        if($Mod_PreviousYearLeave2[1] != NULL) $hourmin = '0.'.$Mod_PreviousYearLeave2[1];
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
        if($day != 00 && $hour != 00 && $min != 00) $Mod_PreviousYearLeave2 = $day.'روز و '.$hour.'ساعت و '.$min.'دقيقه';
        elseif($day != 00 && $hour != 00 && $min == 00) $Mod_PreviousYearLeave2 = $day.'روز و '.$hour.'ساعت';
        elseif($day != 00 && $hour == 00 && $min != 00) $Mod_PreviousYearLeave2 = $day.'روز و '.$min.'دقيقه';
        elseif($day == 00 && $hour != 00 && $min != 00) $Mod_PreviousYearLeave2 = $hour.'ساعت و '.$min.'دقيقه';
        elseif($day == 00 && $hour == 00 && $min != 00) $Mod_PreviousYearLeave2 = $min.'دقيقه';
        elseif($day != 00 && $hour == 00 && $min == 00) $Mod_PreviousYearLeave2 = $day.'روز';
        else $Mod_PreviousYearLeave2 = '0';
        if($Mod_PreviousYearLeave2Status == 'manfi') $Mod_PreviousYearLeave2 = 'منفي '.$Mod_PreviousYearLeave2;
    }

    //--Mod_ThisYearLeave2
    $Mod_ThisYearLeave2 = $resp['data'][0]['Mod_ThisYearLeave'];
    if($Mod_ThisYearLeave2 != 0)
    {
        $Mod_ThisYearLeave2Status = 'mosbat';
        $offset = strpos($Mod_ThisYearLeave2,'-');
        if ($offset !== FALSE){
            $Mod_ThisYearLeave2 = substr($Mod_ThisYearLeave2,1);
            $Mod_ThisYearLeave2Status = 'manfi';
        }
        $Mod_ThisYearLeave2 = explode('.',$Mod_ThisYearLeave2);
        $day = $Mod_ThisYearLeave2[0];
        $day = strlen($day)==1 ? "0".$day : $day;
        if($Mod_ThisYearLeave2[1] != NULL) $hourmin = '0.'.$Mod_ThisYearLeave2[1];
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
        if($day != 00 && $hour != 00 && $min != 00) $Mod_ThisYearLeave2 = $day.'روز و '.$hour.'ساعت و '.$min.'دقيقه';
        elseif($day != 00 && $hour != 00 && $min == 00) $Mod_ThisYearLeave2 = $day.'روز و '.$hour.'ساعت';
        elseif($day != 00 && $hour == 00 && $min != 00) $Mod_ThisYearLeave2 = $day.'روز و '.$min.'دقيقه';
        elseif($day == 00 && $hour != 00 && $min != 00) $Mod_ThisYearLeave2 = $hour.'ساعت و '.$min.'دقيقه';
        elseif($day == 00 && $hour == 00 && $min != 00) $Mod_ThisYearLeave2 = $min.'دقيقه';
        elseif($day != 00 && $hour == 00 && $min == 00) $Mod_ThisYearLeave2 = $day.'روز';
        else $Mod_ThisYearLeave2 = '0';
        if($Mod_ThisYearLeave2Status == 'manfi') $Mod_ThisYearLeave2 = 'منفي '.$Mod_ThisYearLeave2;
    }

    //--TakenLeaveInThisYear2
    $TakenLeaveInThisYear2 = $resp['data'][0]['TakenLeaveInThisYear'];
    if($TakenLeaveInThisYear2 != 0)
    {
        $TakenLeaveInThisYear2Status = 'mosbat';
        $offset = strpos($TakenLeaveInThisYear2,'-');
        if ($offset !== FALSE){
            $TakenLeaveInThisYear2 = substr($TakenLeaveInThisYear2,1);
            $TakenLeaveInThisYear2Status = 'manfi';
        }
        $TakenLeaveInThisYear2 = explode('.',$TakenLeaveInThisYear2);
        $day = $TakenLeaveInThisYear2[0];
        $day = strlen($day)==1 ? "0".$day : $day;
        if($TakenLeaveInThisYear2[1] != NULL) $hourmin = '0.'.$TakenLeaveInThisYear2[1];
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
        if($day != 00 && $hour != 00 && $min != 00) $TakenLeaveInThisYear2 = $day.'روز و '.$hour.'ساعت و '.$min.'دقيقه';
        elseif($day != 00 && $hour != 00 && $min == 00) $TakenLeaveInThisYear2 = $day.'روز و '.$hour.'ساعت';
        elseif($day != 00 && $hour == 00 && $min != 00) $TakenLeaveInThisYear2 = $day.'روز و '.$min.'دقيقه';
        elseif($day == 00 && $hour != 00 && $min != 00) $TakenLeaveInThisYear2 = $hour.'ساعت و '.$min.'دقيقه';
        elseif($day == 00 && $hour == 00 && $min != 00) $TakenLeaveInThisYear2 = $min.'دقيقه';
        elseif($day != 00 && $hour == 00 && $min == 00) $TakenLeaveInThisYear2 = $day.'روز';
        else $TakenLeaveInThisYear2 = '0';
        if($TakenLeaveInThisYear2Status == 'manfi') $TakenLeaveInThisYear2 = 'منفي '.$TakenLeaveInThisYear2;
    }

    //--Num_ThisYearLeaveFromPreviousYear
    $Num_ThisYearLeaveFromPreviousYear2 = $resp['data'][0]['Num_ThisYearLeaveFromPreviousYear'];
    if($Num_ThisYearLeaveFromPreviousYear2 != 0)
    {
        $Num_ThisYearLeaveFromPreviousYear2Status = 'mosbat';
        $offset = strpos($Num_ThisYearLeaveFromPreviousYear2,'-');
        if ($offset !== FALSE){
            $Num_ThisYearLeaveFromPreviousYear2 = substr($Num_ThisYearLeaveFromPreviousYear2,1);
            $Num_ThisYearLeaveFromPreviousYear2Status = 'manfi';
        }
        $Num_ThisYearLeaveFromPreviousYear2 = explode('.',$Num_ThisYearLeaveFromPreviousYear2);
        $day = $Num_ThisYearLeaveFromPreviousYear2[0];
        $day = strlen($day)==1 ? "0".$day : $day;
        if($Num_ThisYearLeaveFromPreviousYear2[1] != NULL) $hourmin = '0.'.$Num_ThisYearLeaveFromPreviousYear2[1];
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
        if($day != 00 && $hour != 00 && $min != 00) $Num_ThisYearLeaveFromPreviousYear2 = $day.'روز و '.$hour.'ساعت و '.$min.'دقيقه';
        elseif($day != 00 && $hour != 00 && $min == 00) $Num_ThisYearLeaveFromPreviousYear2 = $day.'روز و '.$hour.'ساعت';
        elseif($day != 00 && $hour == 00 && $min != 00) $Num_ThisYearLeaveFromPreviousYear2 = $day.'روز و '.$min.'دقيقه';
        elseif($day == 00 && $hour != 00 && $min != 00) $Num_ThisYearLeaveFromPreviousYear2 = $hour.'ساعت و '.$min.'دقيقه';
        elseif($day == 00 && $hour == 00 && $min != 00) $Num_ThisYearLeaveFromPreviousYear2 = $min.'دقيقه';
        elseif($day != 00 && $hour == 00 && $min == 00) $Num_ThisYearLeaveFromPreviousYear2 = $day.'روز';
        else $Num_ThisYearLeaveFromPreviousYear2 = '0';
        if($Num_ThisYearLeaveFromPreviousYear2Status == 'manfi') $Num_ThisYearLeaveFromPreviousYear2 = 'منفي '.$Num_ThisYearLeaveFromPreviousYear2;
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

$wfidE = $db->executeScalar("SELECT `workflow_id` FROM `wf_workflow` WHERE `workflow_enable` = 1 AND `workflow_formtypeid` = 895");
$wfidMorkhasiR = $db->executeScalar("SELECT `workflow_id` FROM `wf_workflow` WHERE `workflow_enable` = 1 AND `workflow_formtypeid` = 931");
$wfidMorkhasiS = $db->executeScalar("SELECT `workflow_id` FROM `wf_workflow` WHERE `workflow_enable` = 1 AND `workflow_formtypeid` = 962");
$wfidMamoriatR = $db->executeScalar("SELECT `workflow_id` FROM `wf_workflow` WHERE `workflow_enable` = 1 AND `workflow_formtypeid` = 960");
$wfidMamoriatS = $db->executeScalar("SELECT `workflow_id` FROM `wf_workflow` WHERE `workflow_enable` = 1 AND `workflow_formtypeid` = 963");

$client = new SoapClient('http://10.10.100.15/WSTuralInOut/TuralInOut.asmx?wsdl');

$s1 = "SELECT * FROM InOutData.dbo.[fnGetMonthTaradodList_Tamin]('".$codem."','".$yy."','".$mm."')";

// SELECT * FROM InOutData.dbo.[fnGetMonthTaradodList_Tamin]('NationalCode','Year','Month')
$s1 = urlencode($s1);
$param = array(
    'username' => '8bfc0e61722d9e9c9bb2138cb359fef9',
    'password' => '085c734188fb09a96eba5d22893a44c4',
    'objStr' => $s1
);
$recs = $client->RunSelectQuery($param);
$recs = $recs->RunSelectQueryResult->cols;

//--
$s2 = "SELECT * FROM [TimeRecordMV].[dbo].[fGetPersonInfo]('".$codem."')";
$s2 = urlencode($s2);
$param2 = array(
    'username' => '8bfc0e61722d9e9c9bb2138cb359fef9',
    'password' => '085c734188fb09a96eba5d22893a44c4',
    'objStr' => $s2
);
$resp2 = $client->RunSelectQuery($param2);
$resp2 = $resp2->RunSelectQueryResult->cols;
$resp2 = json_decode(json_encode($resp2), true);
$resp2 = $resp2['recs']['string'];

if(urldecode($resp2[4]) == 'اداره خدمات عمومي') $LocationCoding = 1;
else if(urldecode($resp2[4]) == 'پاركينگ حرم مطهر') $LocationCoding = 2;
else $LocationCoding = 3;

$html2 = '<tr>
<td class="f-bgcolor-white">كد تورال: <span id="f-CodeTural">'.ltrim(urldecode($resp2[0]),'0').'</span></td>
<td class="f-bgcolor-white">نام و نام خانوادگي: <span id="f-Name">'.urldecode($resp2[1]).'</span></td>
</tr><tr>
<td class="f-bgcolor-white" colspan="2"><span id="f-LocationCoding">'.$LocationCoding.'</span> محل خدمت: <span id="f-Location">'.urldecode($resp2[2]).'، '.urldecode($resp2[3]).'، '.urldecode($resp2[4]).'</span><span id="f-LocationCoding">'.urldecode($resp3[3]).'</span></td>
</tr>';

$html = '';
foreach($recs as $key => $value)
{
    $value = (array)$value;
    foreach($value as $key2 => $value2)
    {
        $value[$key2] = (array)$value2;
    }
    $recs[$key] = $value;
}

$p[0] = 1;
$p[1] = 0;
$ModalID = 0;
$row = array();
$rows = array();
foreach($recs as $v)
{
    $ModalID++;
    $cl = '';
    $aa = array_sum($p);
    $p[1] = $aa;
    $row = $v['recs']['string'];
    $date = urldecode($row[0]);
    $dttt = explode('/', $date);
    $k = $p[1] - 1;

    $class = '';
    if(urldecode($row[3]) == "مرخصي استحقاقي") $class = 'f-table-MorR';
    if(urldecode($row[3]) == "مرخصي استعلاجي") $class = 'f-table-MorR';
    if(urldecode($row[3]) == "مرخصي بدون حقوق") $class = 'f-table-MorR';
    if(urldecode($row[3]) == "مرخصي اضطراري") $class = 'f-table-MorR';
    if(urldecode($row[3]) == "تشويقي تولد فرزند") $class = 'f-table-MorR';
    if(urldecode($row[3]) == "استعلاجي بدون حقوق") $class = 'f-table-MorR';
    if(urldecode($row[3]) == "ماموريت اداري") $class = 'f-table-Mam';
    if(urldecode($row[2]) == "True") $class = 'f-table-Tat';

    $td[$k] = "<tr><td class='$class'>".urldecode($row[1])."<br>".$date."</td><td class='$class'>".urldecode($row[3])."</td>";
    $row['Rooz'] = urldecode($row[1]);
    $row['Tarikh'] = urldecode($row[0]);
    $row['Vaziat'] = urldecode($row[3]);

    switch (urldecode($row[5])) {
        default: $class = 'f-table-Adi'; $tooltip = 'عادي'; break;
        case "1": $class = 'f-table-Mor'; $tooltip = 'مرخصي ساعتي'; break;
        case "2": $class = 'f-table-Mam'; $tooltip = 'ماموريت ساعتي'; break;
    }
    $td[$k].= "<td class='$class f-tooltip'>".urldecode($row[4])."<span class='f-tooltiptext'>$tooltip</span></td>";
    $row['T1'] = urldecode($row[4]);
    $row['T1v'] = $tooltip;

    switch (urldecode($row[7])) {
        default: $class = 'f-table-Adi'; $tooltip = 'عادي'; break;
        case "1": $class = 'f-table-Mor'; $tooltip = 'مرخصي ساعتي'; break;
        case "2": $class = 'f-table-Mam'; $tooltip = 'ماموريت ساعتي'; break;
    }
    $td[$k].= "<td class='$class f-tooltip'>".urldecode($row[6])."<span class='f-tooltiptext'>$tooltip</span></td>";
    $row['T2'] = urldecode($row[6]);
    $row['T2v'] = $tooltip;

    switch (urldecode($row[9])) {
        default: $class = 'f-table-Adi'; $tooltip = 'عادي'; break;
        case "1": $class = 'f-table-Mor'; $tooltip = 'مرخصي ساعتي'; break;
        case "2": $class = 'f-table-Mam'; $tooltip = 'ماموريت ساعتي'; break;
    }
    $td[$k].= "<td class='$class f-tooltip'>".urldecode($row[8])."<span class='f-tooltiptext'>$tooltip</span></td>";
    $row['T3'] = urldecode($row[8]);
    $row['T3v'] = $tooltip;

    switch (urldecode($row[11])) {
        default: $class = 'f-table-Adi'; $tooltip = 'عادي'; break;
        case "1": $class = 'f-table-Mor'; $tooltip = 'مرخصي ساعتي'; break;
        case "2": $class = 'f-table-Mam'; $tooltip = 'ماموريت ساعتي'; break;
    }
    $td[$k].= "<td class='$class f-tooltip'>".urldecode($row[10])."<span class='f-tooltiptext'>$tooltip</span></td>";
    $row['T4'] = urldecode($row[10]);
    $row['T4v'] = $tooltip;

    switch (urldecode($row[13])) {
        default: $class = 'f-table-Adi'; $tooltip = 'عادي'; break;
        case "1": $class = 'f-table-Mor'; $tooltip = 'مرخصي ساعتي'; break;
        case "2": $class = 'f-table-Mam'; $tooltip = 'ماموريت ساعتي'; break;
    }
    $td[$k].= "<td class='$class f-tooltip'>".urldecode($row[12])."<span class='f-tooltiptext'>$tooltip</span></td>";
    $row['T5'] = urldecode($row[12]);
    $row['T5v'] = $tooltip;

    switch (urldecode($row[15])){
        default: $class = 'f-table-Adi'; $tooltip = 'عادي'; break;
        case "1": $class = 'f-table-Mor'; $tooltip = 'مرخصي ساعتي'; break;
        case "2": $class = 'f-table-Mam'; $tooltip = 'ماموريت ساعتي'; break;
    }
    $td[$k].= "<td class='$class f-tooltip'>".urldecode($row[14])."<span class='f-tooltiptext'>$tooltip</span></td>";
    $row['T6'] = urldecode($row[14]);
    $row['T6v'] = $tooltip;

    switch (urldecode($row[17])){
        default: $class = 'f-table-Adi'; $tooltip = 'عادي'; break;
        case "1": $class = 'f-table-Mor'; $tooltip = 'مرخصي ساعتي'; break;
        case "2": $class = 'f-table-Mam'; $tooltip = 'ماموريت ساعتي'; break;
    }
    $td[$k].= "<td class='$class f-tooltip'>".urldecode($row[16])."<span class='f-tooltiptext'>$tooltip</span></td>";
    $row['T7'] = urldecode($row[16]);
    $row['T7v'] = $tooltip;

    $editLinkWithEslah = '
<td class="f-button-1">
<a class="f-button-0" href="#Modal'.$ModalID.'">ايجادفرم</a>
<div id="Modal'.$ModalID.'" class="f-modal-modalDialog"><div><a href="#close" title="بستن" class="f-modal-close">X</a>
انتخاب يكي از فرم  ها براي روز '.$date.':
 <input type="button" class="f-button-2" value="اصلاح تردد" onclick="FormOnly.allFieldsContianer[8].eslah('.$wfidE.',\''.$date.'\')"> 
 <input type="button" class="f-button-2" value="مرخصي روزانه" onclick="FormOnly.allFieldsContianer[8].MorkhasiR('.$wfidMorkhasiR.',\''.$date.'\')"">
 <input type="button" class="f-button-2" value="مرخصي ساعتي" onclick="FormOnly.allFieldsContianer[8].MorkhasiS('.$wfidMorkhasiS.',\''.$date.'\')"">
 <input type="button" class="f-button-2" value="ماموريت روزانه" onclick="FormOnly.allFieldsContianer[8].MamoriatR('.$wfidMamoriatR.',\''.$date.'\')"">
 <!--<input type="button" class="f-button-2" value="ماموريت ساعتي" onclick="FormOnly.allFieldsContianer[8].MamoriatS('.$wfidMamoriatS.',\''.$date.'\')"">-->
</div></div>
</td>
';

    $editLinkWithOutEslah = '
<td class="f-button-1">
<a class="f-button-0" href="#Modal'.$ModalID.'">ايجادفرم</a>
<div id="Modal'.$ModalID.'" class="f-modal-modalDialog"><div><a href="#close" title="بستن" class="f-modal-close">X</a>
انتخاب يكي از فرم  ها براي روز '.$date.':
 <input type="button" class="f-button-2" value="مرخصي روزانه" onclick="FormOnly.allFieldsContianer[8].MorkhasiR('.$wfidMorkhasiR.',\''.$date.'\')"">
 <input type="button" class="f-button-2" value="مرخصي ساعتي" onclick="FormOnly.allFieldsContianer[8].MorkhasiS('.$wfidMorkhasiS.',\''.$date.'\')"">
 <input type="button" class="f-button-2" value="ماموريت روزانه" onclick="FormOnly.allFieldsContianer[8].MamoriatR('.$wfidMamoriatR.',\''.$date.'\')"">
 <!--<input type="button" class="f-button-2" value="ماموريت ساعتي" onclick="FormOnly.allFieldsContianer[8].MamoriatS('.$wfidMamoriatS.',\''.$date.'\')"">-->
</div></div>
</td>
';

    $shahrestan = 0;
    $sql5 = "SELECT path FROM oa_depts_roles WHERE RowID = $RID";
    $path = $db->executeScalar($sql5);
    if(strpos($path, '/6597/') !== false) $shahrestan = 1;
    else $shahrestan = 0;

    //RID = 8651 مدير امور اداري موسسه تامين و پشتيباني رضوي
    if($RID != 8651 && $RID != 1508 && $shahrestan == 0)
    {
        if((int)$yynow != (int)$dttt[0] && 0)
        {
            $editLink = '';
        }
        else
        {
            $datediff = (int)$mmnow - (int)$dttt[1];
            if((int)$mmnow < (int)$dttt[1]) $datediff = ((int)$mmnow + 12) - (int)$dttt[1];
            if($datediff > 1 && $datediff != '11')
            {
                $editLink = '';
            }
            else if($datediff == '11')
            {
                $editLink = $editLinkWithOutEslah;
            }
            else if($datediff < 0)
            {
                $editLink = '';
            }
            else if($datediff == 1 && (int)$ddnow >= 6 && (int)$ddnow <= 17)
            {
                $editLink = $editLinkWithOutEslah;
            }
            else if($datediff == 1 && (int)$ddnow >= 18)
            {
                $editLink = $editLinkWithEslah;
            }
            else if($tedad < 4)
            {
                $editLink = $editLinkWithEslah;
            }
            else
            {
                $editLink = $editLinkWithOutEslah;
            }
        }
    }
    else
    {
        $editLink = $editLinkWithEslah;
    }

    //$td[$k] .= $editLink;
    $td[$k] .= $editLinkWithEslah;


    $Txt = '';
    $i = 0;
    $date = Date::JalaliToGreg($date);

    // اصلاح
    $sql = "SELECT docid, Field_3, Field_13 FROM `dm_datastoretable_895` 
	LEFT JOIN oa_document on (oa_document.rowid = dm_datastoretable_895.docid) 
	WHERE  oa_document.isenable = 1 
	/*AND oa_document.DocStatus = 0 */
	AND (`Field_7` = '1' OR `Field_7` = 'در تورال ثبت شد') 
	AND `Field_8` = '$codem' 
	AND `Field_2` = '$date'
	";
    $db->executeSelect($sql);
    while($row1 = $db->fetchAssoc())
    {
        if($row1)
        {
            switch($row1['field_13'])
            {
                case "11": $type = 'افزودن تردد'; break;
                case "12": $type = 'حذف تردد'; break;
            }
            $Txt .= "نوع فرم: ".$type."<br> شماره فرم: ".$row1['docid']."<br>ساعت تردد: ".$row1['field_3']."<br><br>";
            $i++;
        }
    }

    // مرخصي روزانه
    $sql = "SELECT docid, field_2, field_3, field_6, field_8, field_10 FROM `dm_datastoretable_931` 
	LEFT JOIN oa_document on (oa_document.rowid = dm_datastoretable_931.docid) 
	WHERE  oa_document.isenable = 1 
	/*AND oa_document.DocStatus = 0 */
	AND (`Field_8` = '1' OR `Field_8` = '6 / تورال ثبت شد / آرمان ثبت شد') 
	AND `Field_6` = '$codem' 
	AND `Field_2` = '$date'
	";
    $db->executeSelect($sql);
    while($row1 = $db->fetchAssoc())
    {
        if($row1)
        {
            switch($row1['field_10'])
            {
                case "1": $type = 'مرخصي استحقاقي'; break;
                case "3": $type = 'مرخصي استعلاجي'; break;
                case "33": $type = 'مرخصي استعلاجي'; break;
                case "4": $type = 'مرخصي بدون حقوق'; break;
                case "44": $type = 'مرخصي بدون حقوق'; break;
                case "11": $type = 'مرخصي زايمان'; break;
                case "111": $type = 'مرخصي اضطراري'; break;
            }
            $Txt .= "نوع فرم: ".$type."<br> شماره فرم: ".$row1['docid']."<br>تاريخ رفت: ".Date::GregToJalali($row1['field_2'])."<br>تاريخ برگشت: ".Date::GregToJalali($row1['field_3'])."<br><br>";
            $i++;
        }
    }

    // ماموريت روزانه
    $sql = "SELECT docid, field_2, field_3, field_6, field_8, field_10 FROM `dm_datastoretable_960` 
	LEFT JOIN oa_document on (oa_document.rowid = dm_datastoretable_960.docid) 
	WHERE  oa_document.isenable = 1 
	/*AND oa_document.DocStatus = 0 */
	AND (`Field_8` = '1' OR `Field_8` = '4 / تورال ثبت شد') 
	AND `Field_6` = '$codem' 
	AND `Field_2` = '$date'
	";
    $db->executeSelect($sql);
    while($row1 = $db->fetchAssoc())
    {
        if($row1)
        {
            $type = 'ماموريت روزانه';
            $Txt .= "نوع فرم: ".$type."<br> شماره فرم: ".$row1['docid']."<br>تاريخ رفت: ".Date::GregToJalali($row1['field_2'])."<br>تاريخ برگشت: ".Date::GregToJalali($row1['field_3'])."<br><br>";
            $i++;
        }
    }

    // مرخصي ساعتي
    $sql = "SELECT docid, field_1, field_2, field_14, field_9, field_10 FROM `dm_datastoretable_962` 
	LEFT JOIN oa_document on (oa_document.rowid = dm_datastoretable_962.docid) 
	WHERE  oa_document.isenable = 1 
	/*AND oa_document.DocStatus = 0 */
	AND (`Field_9` = '1' OR `Field_9` = '6 / تورال ثبت شد / آرمان ثبت شد') 
	AND `Field_4` = '$codem' 
	AND `Field_1` = '$date' 
	";
    $db->executeSelect($sql);
    while($row1 = $db->fetchAssoc())
    {
        if($row1)
        {
            $type = 'مرخصي ساعتي';
            $Txt .= "نوع فرم: ".$type."<br> شماره فرم: ".$row1['docid']."<br>ساعت رفت: ".$row1['field_2']."<br>ساعت برگشت: ".$row1['field_14']."<br><br>";
            $i++;
        }
    }


    if($i!= 0)
    {
        $i = $i * 110;
        $td[$k] .= "<td class='f-tooltip'>جزئيات<span class='f-tooltiptext-2' style='top: -".$i."px'>$Txt</span></td>";
    }




    $td[$k] .= "</tr>";
    $rows[] = $row;
}
$html = implode('',$td);
if($html != false)
{
    $html = '
	<table class="f-table" cellpadding="0" cellspacing="3"><tbody>'.$html2.'</tbody></table>
	
	<table class="f-table" cellpadding="0" cellspacing="3" style="margin-top:15px"><thead>
	<tr>
	<th>تعداد فرم اصلاح تردد در اين ماه: <span id="f-EslahNum">'.$tedad.'</span></th>
	<th>كل مانده مرخصي از ابتدا تا الان: <span id="f-MandeMorkhasi">'.$MandeMorkhasi.'</span><span style="display:none" id="f-MandeMorkhasiMerg">'.$MandeMorkhasiMerg.'</span></th>
	</tr><tr>
	<td>كل مرخصي استفاده شده سال ۹۹: <span>'.$TakenLeaveInThisYear.'</span></td>
	<td>كل مرخصي استفاده شده سال ۹۸: <span>'.$TakenLeaveInThisYear2.'</span></td>
	</tr>
	</thead></table>
	
	<table class="f-table" cellpadding="0" cellspacing="3" style="margin-top:15px"><tbody>'.$html.'</tbody></table>
	';
}
else
    $html = '<div id="dvQueryFalse">جستجو نتيجه اي نداشت</div>';


if ((int)$csv == 1)
{
    if(count($rows)>0){
        $title = array();
        $content = array();
        $title[] = "روز";
        $title[] = "تاريخ روز";
        $title[] = "وضعيت روز";
        $title[] = "تردد اول";
        $title[] = "نوع تردد اول";
        $title[] = "تردد دوم";
        $title[] = "نوع تردد دوم";
        $title[] = "تردد سوم";
        $title[] = "نوع تردد سوم";
        $title[] = "تردد چهارم";
        $title[] = "نوع تردد چهارم";
        $title[] = "تردد پنجم";
        $title[] = "نوع تردد پنجم";
        $title[] = "تردد ششم";
        $title[] = "نوع تردد ششم";
        $title[] = "تردد هفتم";
        $title[] = "نوع تردد هفتم";
        $content[] = "Rooz";
        $content[] = "Tarikh";
        $content[] = "Vaziat";
        $content[] = "T1";
        $content[] = "T1v";
        $content[] = "T2";
        $content[] = "T2v";
        $content[] = "T3";
        $content[] = "T3v";
        $content[] = "T4";
        $content[] = "T4v";
        $content[] = "T5";
        $content[] = "T5v";
        $content[] = "T6";
        $content[] = "T6v";
        $content[] = "T7";
        $content[] = "T7v";
        ModReport::createOutCSV($title,$content,$rows);
    }
}
else{
    Response::getInstance()->response = $html;
}
