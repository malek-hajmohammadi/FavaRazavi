<?php
class MainAjax
{
    private $beginDate="";
    private $endDate="";
    private $personalId="";
    private $webserviceData=[];
    private $trNumber=0;

    private $tedadGheibat=0;
    private $roozMorkhasiT=0;
    private $tedadNaghes=0;
    private $gId="";

    private $ezafehKar;
    private $shabKar;
    private $tatilKar;
    private $morkhasi;
    private $mamooriat;
    private $morkhasiKol;
    private $kasrKol;
    private $ezafePardakhti;




    public function main()
    {
        $this->getInput();
      /*  $this->beginDate="1399/10/10";
        $this->endDate="1399/11/24";
        $this->personalId="850";*/


        $webResult= $this->getDataViaWebserviceBySoup();
       if($webResult==0)
           return "شماره پرسنلی معتبر نمی باشد";
       else{
           $this->gatherStatisticalData();
           $html=$this->buildStatisticalTable();
           $html.=$this->buildCalendarTable();
           return $html;
       }

    }
    private function getMinutesFromTimeString($strTime){

        $timeArray=explode(':', $strTime);
        return (int)$timeArray[1];
    }
    private function getHoursFromTimeString($strTime){

        $timeArray=explode(':', $strTime);

        return (int)$timeArray[0];
    }
    private function makeTimeString($hours,$minutes){
        if ($minutes>=60){
            $hours+=floor($minutes/60);
            $minutes=$minutes%60;
        }
        if ($hours<10)
            $timeString="0".$hours.":";
        else
            $timeString=$hours.":";

        if($minutes<10)
            $timeString.="0".$minutes;
        else
            $timeString.=$minutes;

        return $timeString;

    }
    private function getMorkhasiKol(){


        $this->roozMorkhasiT;
        $morkhasiHours=$this->getHoursFromTimeString($this->morkhasi);
        $morkhasiMinutes=$this->getMinutesFromTimeString($this->morkhasi);
        return number_format(($this->roozMorkhasiT + (((($morkhasiHours * 60) + $morkhasiMinutes) / 60) / 7.33)), 2);

    }
    private function getKasrKol($takhirAvalHours,$takhirAvalMinutes,
                                $tagilAkharHours,$tagilAkharMinutes,
                                $kasrKarHours,$kasrKarMinutes){

        $kasrKolHours=$kasrKarHours;//-($takhirAvalHours+$tagilAkharHours);
        $kasrKolMinutes=$kasrKarMinutes;//-($takhirAvalMinutes+$tagilAkharMinutes);
        return $this->makeTimeString($kasrKolHours,$kasrKolMinutes);

    }
    private function getEzafehPardakhti($ezafeKarHours,$ezafeKarMinutes,
                                        $shabKarHours,$shabKarMinuts,
                                        $kasrKarHours,$kasrKarMinutes){
        $ezafehPardakhtiHours=$ezafeKarHours-( $shabKarHours+$kasrKarHours);
        $ezafehPardakhtiMinutes=$ezafeKarMinutes-($shabKarMinuts+$kasrKarMinutes);

        if($ezafehPardakhtiMinutes<0){
            $ezafehPardakhtiHours--;
            $ezafehPardakhtiMinutes+=60;
        }

        return $this->makeTimeString($ezafehPardakhtiHours,$ezafehPardakhtiMinutes);
    }
    private function gatherStatisticalData(){

        $count = count($this->webserviceData);
        $ezafeKarHours=0;
        $ezafeKarMinutes=0;

        $shabKarHours=0;
        $shabKarMinuts=0;

        $tatilKarHours=0;
        $tatilKarMinutes=0;

        $morkhasiHours=0;
        $morkhasiMinutes=0;

        $mamooriatHours=0;
        $mamooriatMinutes=0;

        $takhirAvalHours=0;
        $takhirAvalMinutes=0;

        $tagilAkharHours=0;
        $tagilAkharMinutes=0;

        $kasrKarHours=0;
        $kasrKarMinutes=0;

        $str="";
        $stringIn="";
        for ($i = 0; $i < $count; $i++){

            if ($count == 1)
                $row = $this->webserviceData['recs']['string'];
            else
                $row = $this->webserviceData[$i]['recs']['string'];


            $check=urldecode($row[15]);
            $check = strpos(urldecode($row[15]),'مرخص');
            if($check !== false) $this->roozMorkhasiT++;

            $check = strpos(urldecode($row[15]),'استعلا');
            if($check !== false)  $this->roozMorkhasiT++;


            $check = strpos(urldecode($row[15]),'ناقص');
            if($check !== false) $this->tedadGheibat++;

            $check = strpos(urldecode($row[15]),'بت');
            if($check !== false) $this->tedadGheibat++;



          /*  switch ($check){
                case 'مرخصي استحقاقي':
                case 'مرخصی استعلاجی':
                   $this->roozMorkhasiT++;
                break;

                case 'تردد ناقص':
                    $this->tedadNaghes++;
                    break;
                case 'غيبت':
                    $this->tedadGheibat++;
                    break;
            }*/
            // اضافه كاري و شب كاري و تعطيل كاري
            /*اضافه*/
            $s26 = urldecode($row[26]) == NULL ? "00:00" : urldecode($row[26]);
            $ezafeKarHours+=$this->getHoursFromTimeString($s26);
            $ezafeKarMinutes+=$this->getHoursFromTimeSTring($s26);

            /*شب*/
            $s30 = urldecode($row[31]) == NULL ? "00:00" : urldecode($row[31]);
            $shabKarHours+=$this->getHoursFromTimeString($s30);
            $shabKarMinuts+=$this->getMinutesFromTimeString($s30);

            /*تعطيل*/
            $s28 = urldecode($row[28]) == NULL ? "00:00" : urldecode($row[28]);
            $tatilKarHours+=$this->getHoursFromTimeString($s28);
            $tatilKarMinutes+=$this->getMinutesFromTimeString($s28);

            // مرخصي
            $s19 = urldecode($row[19]) == NULL ? "00:00" : urldecode($row[19]);
            $morkhasiHours+=$this->getHoursFromTimeString($s19);
            $morkhasiMinutes+=$this->getMinutesFromTimeString($s19);

            //ماموریت
            $s20 = urldecode($row[20]) == NULL ? "00:00" : urldecode($row[20]);
            $mamooriatHours+=$this->getHoursFromTimeString($s20);
            $mamooriatMinutes+=$this->getMinutesFromTimeString($s20);

            //تاخیر اول
            $s16 = urldecode($row[16]) == NULL ? "00:00" : urldecode($row[16]);
            $takhirAvalHours+=$this->getHoursFromTimeString($s16);
            $takhirAvalMinutes+=$this->getMinutesFromTimeString($s16);

            //تعجیل آخر
            $s17 = urldecode($row[17]) == NULL ? "00:00" : urldecode($row[17]);
            $tagilAkharHours+=$this->getHoursFromTimeString($s17);
            $tagilAkharMinutes+=$this->getMinutesFromTimeString($s17);

            //کسر کار
            $s18 = urldecode($row[18]) == NULL ? "00:00" : urldecode($row[18]);
            $kasrKarHours+=$this->getHoursFromTimeString($s18);
            $kasrKarMinutes+=$this->getMinutesFromTimeString($s18);



            /*غيرمجاز*/
            $s27 = urldecode($row[27]) == NULL ? "00:00" : urldecode($row[27]);

            $s23 = urldecode($row[23]) == NULL ? "00:00" : urldecode($row[23]);




            //$stringIn="";
            for($j=0;$j<32;$j++){
                $stringIn.="[$j:".urldecode($row[$j])."]";





            }

            $stringIn.="<br/><br/><br/>";
        }
       // $this->gId=$str;

        /////////////////////
        ///
        if(false) {

            $TEzafeH = (int)0;
            $TEzafeM = (int)0;
            foreach ($TEzafe as $time) {
                $time = explode(':', $time);
                $TEzafeH = $TEzafeH + (int)$time[0];
                $TEzafeM = $TEzafeM + (int)$time[1];
            }
            $TEzafeH = $TEzafeH + floor($TEzafeM / 60);
            $TEzafeH = strlen($TEzafeH) == 1 ? "0" . $TEzafeH : $TEzafeH;
            $TEzafeM = $TEzafeM % 60;
            $TEzafeM = strlen($TEzafeM) == 1 ? "0" . $TEzafeM : $TEzafeM;


            $TShabH = (int)0;
            $TShabM = (int)0;
            foreach ($TShab as $time) {
                $time = explode(':', $time);
                $TShabH = $TShabH + (int)$time[0];
                $TShabM = $TShabM + (int)$time[1];
            }
            $TShabH = $TShabH + floor($TShabM / 60);
            $TShabH = strlen($TShabH) == 1 ? "0" . $TShabH : $TShabH;
            $TShabM = $TShabM % 60;
            $TShabM = strlen($TShabM) == 1 ? "0" . $TShabM : $TShabM;


            $TShab2 = ((int)$TShabH * 60) + (int)$TShabM;
            $TEzafe = ((int)$TEzafeH * 60) + (int)$TEzafeM;
            $diff_time = (int)$TEzafe - (int)$TShab2;
            if ($diff_time < 0) $manfi = '-';
            else $manfi = '';
            $diff_time = (int)$diff_time / 60;
            $diff_time = explode('.', (string)$diff_time);
            $h = (int)$diff_time[0];
            $m = "0." . $diff_time[1];
            $m = $m * 60;
            $TEzafeKhalesH = strlen($h) == 1 ? "0" . $h : $h;
            $m = $m % 60;
            $TEzafeKhalesM = strlen($m) == 1 ? "0" . $m : $m;

            if ($TEzafeKhalesH == '00' && $manfi == '-') $TEzafeKhales = $TEzafeKhalesH . ':' . $TEzafeKhalesM . $manfi;
            else $TEzafeKhales = $TEzafeKhalesH . ':' . $TEzafeKhalesM;
        }
        $this->ezafehKar=$this->makeTimeString($ezafeKarHours,$ezafeKarMinutes);
        $this->shabKar=$this->makeTimeString($shabKarHours,$shabKarMinuts);
        $this->tatilKar=$this->makeTimeString($tatilKarHours,$tatilKarMinutes);
        $this->mamooriat=$this->makeTimeString($mamooriatHours,$mamooriatMinutes);
        $this->morkhasi=$this->makeTimeString($morkhasiHours,$morkhasiMinutes);

        $this->kasrKol=$this->getKasrKol($takhirAvalHours,$takhirAvalMinutes,
            $tagilAkharHours,$tagilAkharMinutes,$kasrKarHours,$kasrKarMinutes);
        $this->ezafePardakhti= $this->getEzafehPardakhti($ezafeKarHours,$ezafeKarMinutes,
            $shabKarHours,$shabKarMinuts,
            $kasrKarHours,$kasrKarMinutes);






    }

    private function buildStatisticalTable(){

        //$TEzafeKhales="";
        //$TKasrH="";
        //$TKasrM="";
        $TNahaee="";
        //$TTatilH="";
       // $TTatilM="";
        //$TShabH="";
       // $TShabM="";
        //$TedadNaghes="";
        //$TedadGheibat="";
        //$RoozMorkhasiT="";
        //$TMorkhasiH ="";
        //$TMorkhasiM="";
        $TMorkhasiKol="";
        $TimexUpdate="";
        $PID="";
        $GID="";




        $html = "
<section>
    
    <fieldset><table><tbody>
    
    <tr><td style='font-weight: bold !important'>جمع اضافه كار خالص: </td>
    <td style=''>$this->ezafehKar</td></tr>
    
    <tr><td style='font-weight: bold !important'>جمع كسركار: </td>
    <td style=''>$this->kasrKol</td></tr>

    <tr><td style='font-weight: bold !important'>اضافه كار پرداختي: </td>
    <td style=''>$this->ezafePardakhti</td></tr>

    <tr><td style='font-weight: bold !important'>جمع تعطيل كار: </td>
    <td style=''>$this->tatilKar</td></tr>

    <tr><td style='font-weight: bold !important'>جمع شب كار: </td>
    <td style=''>$this->shabKar</td></tr>

    </tbody></table></fieldset>
    <fieldset><table><tbody>

    <tr><td style='font-weight: bold !important'>تعداد تردد ناقص: </td>
    <td style=''>$this->tedadNaghes</td></tr>

    <tr><td style='font-weight: bold !important'>تعداد غيبت: </td>
    <td style=''>$this->tedadGheibat</td></tr>

    <tr><td style='font-weight: bold !important'>تعداد مرخصي روزانه: </td>
    <td style=''>$this->roozMorkhasiT</td></tr>

    <tr><td style='font-weight: bold !important'>جمع مرخصي ساعتي: </td>
    <td style=''>$this->morkhasi</td></tr>

    <tr><td style='font-weight: bold !important'>مرخصي استفاده شده: </td>
    <td style=''>".$this->getMorkhasiKol()."</td></tr>

    </tbody></table></fieldset>
    <fieldset><table><tbody>

    

    <tr><td style='font-weight: bold !important'>شماره پرسنلي: </td>
    <td style=''>$this->personalId</td></tr>


    </tbody></table></fieldset>

</section>";
        return $html;
    }
    private function getInput(){

        $db = WFPDOAdapter::getInstance();

        $status = Request::getInstance()->varCleanFromInput('status');
        $mm = Request::getInstance()->varCleanFromInput('mm');
        $yy = Request::getInstance()->varCleanFromInput('yy');
        $aztd = Request::getInstance()->varCleanFromInput('aztd');
        $aztm = Request::getInstance()->varCleanFromInput('aztm');
        $azty = Request::getInstance()->varCleanFromInput('azty');
        $tatd = Request::getInstance()->varCleanFromInput('tatd');
        $tatm = Request::getInstance()->varCleanFromInput('tatm');
        $taty = Request::getInstance()->varCleanFromInput('taty');
        $this->personalId = Request::getInstance()->varCleanFromInput('PID');

        if ($status == 'DoreZamani') {
            $this->beginDate = $yy . "/" . $mm . "/01";
            $this->endDate = $yy . "/" . $mm . "/31";
        }
        else if ($status == 'BazeZamani')
        {
            $this->beginDate = $azty . "/" . $aztm . "/" . $aztd;
            $this->endDate = $taty . "/" . $tatm . "/" . $tatd;

        }

    }

    private function getDataViaWebserviceBySoup()
    {

        $soapStatus=1;
        try {

            $client = new SoapClient("http://192.168.110.119:82/TimeX.asmx?wsdl");
            $client->soap_defencoding = 'UTF-8';
            $queryString = "EXEC [adon].[TimeSheetView] " . $this->personalId . ",'" . $this->beginDate . "','" . $this->endDate . "'";
           // $queryString = "EXEC [adon].[TimeSheetView] 55,'1400/01/01','1400/01/20'";

            $params = array("username" => '3ef1b48067e4f2ac9913141d77e847dd', "password" => '9a3f5b14f1737c15e86680d9cd40b840',
                            "objStr"   => $queryString
            );


            $res = $client->RunSelectQuery($params);
            $res = $res->RunSelectQueryResult->cols;
            $res = json_decode(json_encode($res), true);

            /*to get the result you should write like below*/
            //$res=$res[0]['recs']['string'];

            $count = count($res);

            if ($count <1)
                $soapStatus=0;
            else
                $soapStatus=1;

            $this->webserviceData=$res;




        } catch (SoapFaultException $e) {
            $soapStatus = 0;
            $this->webserviceData[0] = "خطا در کچ اول";
        } catch (Exception $e) {
            $soapStatus = 0;
            $this->webserviceData[0]="خطا در کچ دوم";
        }
        return $soapStatus;

    }

    private function sortResultArray(){

        /*$count = count($res);
        for($i=0;$i<$count;$i++){
            for($j=$i+1;$j<$count;$j++){
                if($res[$i]->Date > $res[$j]->Date){
                    $temp=$res[$i];
                    $res[$i]=$res[$j];
                    $res[$j]=$temp;
                }
                elseif ($res[$i]->Date == $res[$j]->Date)
                    if($res[$i]->Time > $res[$j]->Time){
                        $temp=$res[$i];
                        $res[$i]=$res[$j];
                        $res[$j]=$temp;
                    }
            }
        }*/
    }

    private function buildCalendarTable(){

        $count = count($this->webserviceData);
        $calendarTable="";
        $calendarTable.="<section><table class='table' cellpadding='0' cellspacing='3' style='width:100%'>".
        " <thead>
        <tr>
        <th>تاريخ</th>
        <th>وضعيت</th>
        <th>ترددها</th>
        <!--
        <th>حضور</th>
        -->
        <th>كسركار</th>
        <th>اضافه  كار</th>
        <!--
        <th>شب كار</th>
        <th>تعطيل كار</th>
        -->
        </tr></thead>
        <tbody>";

        for ($i = 0; $i < $count; $i++){

            $this->trNumber=$i+1;

            if ($count == 1)
                $row = $this->webserviceData['recs']['string'];
            else
                $row = $this->webserviceData[$i]['recs']['string'];

            $calendarTable.=  $this->buildTableRow($row);

        }
        $calendarTable.="</tbody></table></section>";
        return $calendarTable;

    }
    private function buildTableRow($row){
       $tableRow="<tr>";

       $tableRow.=$this->buildDateTd($row);
       $tableRow.=$this->buildSituationTd($row);
       $tableRow.=$this->buildTimeTableTd($row);
       $tableRow.=$this->buildCreateFormBoxTd($row);
       $tableRow.=$this->buildLowWorkTd($row);
       $tableRow.=$this->buildWorkOvertimeTd($row);

       $tableRow.="</tr>";



      /* $date= $this->webserviceData[$rowIndex]['recs']['string'][2];

       $tableRow .= "<tr><td class='class f-tooltip'>". $date ."</td><td align='right' style='width:100%; position: relative;'>";
       $test="</td></tr>";

       $tableRow.=$test;
       // $taradodha="<table cellpadding='0' cellspacing='3'><tbody><tr>";*/

        return $tableRow;


    }
    private function buildDateTd($row){
        $dateTd="";
        $date = urldecode($row[2]);

        $dateType = explode(',', urldecode($row[3]));
        switch (urldecode($row[1])) {
            default:
                $class = '';
                break;
            case "15":
                $class = 'table-Shift';
                break;
            case "16":
                $class = 'table-Shift';
                break;
        }
        switch ($dateType[1]) {
            case "1":
                $class = 'table-Tat';
                $RoozTatile = 1;
                break;
            case "2":
                $class = 'table-Tat';
                $RoozTatile = 1;
                break;
        }

        $dateTd .= "<td class='$class f-tooltip'>" . $dateType[0] . "<br>" . $date . "<span class='f-tooltip-text f-tooltip-right'>شيفت: " . urldecode($row[1]) . "<br>" . urldecode($row[4]) . "</span></td>";
        return $dateTd;

    }
    private function buildSituationTd($row){
        $stTd="";

        $Check = strpos(urldecode($row[15]), 'مرخص');
        if ($Check !== false) {
            $class2 = 'table-Mor';
        }

        $Check = strpos(urldecode($row[15]), 'استعلا');
        if ($Check !== false) {
            $class2 = 'table-Mor';
           /* $RoozMorkhasi = 0;
            $RoozMorkhasiT++;*/
        }

        $Check = strpos(urldecode($row[15]), 'مامور');
        if ($Check !== false) $class2 = 'table-Mam';

        $Check = strpos(urldecode($row[15]), 'اپ');
        if ($Check !== false) $class2 = 'table-Mam';

        $Check = strpos(urldecode($row[15]), 'ناقص');
        if ($Check !== false) {
            $class2 = 'table-Nagh';

        }

        $Check = strpos(urldecode($row[15]), 'بت');
        if ($Check !== false) {
            $class2 = 'table-Tat';

        }
        $stTd .= "<td class='$class2'>" . urldecode($row[15]) . "</td>";

        return $stTd;
    }
    private function buildTimeTableTd($row){
        // ترددها
        $timeTable="";
        $Nahar = 0;
        for ($j = 5; $j <= 14; $j++) {
            $a = explode(',', urldecode($row[$j]));
            switch ($a[1]) {
                default:
                    $class = 'table-Adi';
                    $tooltip = 'فاقد تردد';
                    $type = 'N';
                    break;
                case "0":
                    $class = 'table-Adi';
                    $tooltip = 'عادي';
                    $type = 'Adi';
                    break;
                case "-32768":
                    $class = 'table-Mor-N';
                    $tooltip = 'مرخصي بدون فرم';
                    $type = 'MorS';
                    break;
                case "-16744156":
                    $class = 'table-Mam-N';
                    $tooltip = 'ماموريت بدون فرم';
                    $type = 'MamS';
                    break;
            }
            if ($a[0] != NULL) {
                $timeTable .= "<td class='$class f-tooltip'>" . $a[0] . "<span class='f-tooltip-text f-tooltip-top'>$tooltip</span></td>";
                $s25 = urldecode($row[25]) == NULL ? "0000" : str_replace(":", "", urldecode($row[25]));
                $s26 = urldecode($row[26]) == NULL ? "0000" : str_replace(":", "", urldecode($row[26]));
                $s28 = urldecode($row[28]) == NULL ? "0000" : str_replace(":", "", urldecode($row[28]));
            }
        }



        $timeTableTd = "<td align='right' style='width:100%; position: relative;'><table cellpadding='0' cellspacing='3' style='float: right'><tr>$timeTable</tr></table>";
        return $timeTableTd;
    }
    private function buildCreateFormBoxTd($row){


        $db = WFPDOAdapter::getInstance();
        $wfidMorkhasiS = $db->executeScalar("SELECT max(`workflow_id`) FROM `wf_workflow` WHERE `workflow_enable` = 1 AND `workflow_formtypeid` =1426");
        $wfidMorkhasiR = $db->executeScalar("SELECT max(`workflow_id`) FROM `wf_workflow` WHERE `workflow_enable` = 1 AND `workflow_formtypeid` = 1427");
        $wfidMamoriatS = $db->executeScalar("SELECT max(`workflow_id`) FROM `wf_workflow` WHERE `workflow_enable` = 1 AND `workflow_formtypeid` = 1428");
        $wfidMamoriatR = $db->executeScalar("SELECT max(`workflow_id`) FROM `wf_workflow` WHERE `workflow_enable` = 1 AND `workflow_formtypeid` = 1429");
        $wfidAfzoodanTaradod = $db->executeScalar("SELECT max(`workflow_id`) FROM `wf_workflow` WHERE `workflow_enable` = 1 AND `workflow_formtypeid` = 1437");
        /*$wfidEslah = $db->executeScalar("SELECT max(`workflow_id`) FROM `wf_workflow` WHERE `workflow_enable` = 1 AND `workflow_formtypeid` = {${MainAjax::ESLAH_FORM_ID}}");*/



        $date = urldecode($row[2]);


        //$btnsBox='';


        $btnsBox = '<div class="f-button-1" onmousedown="window.codeSet.createMamoriatRoozaneh('.$wfidMamoriatR.',\''.$date.'\')">ماموريت<br>روزانه</div>';
        $btnsBox .= '<div class="f-button-1" onmousedown="window.codeSet.createMorkhasiRoozaneh('.$wfidMorkhasiR.',\''.$date.'\')">مرخصي<br>روزانه</div>';

        if(false) {
    //$btnsBox .= '<div class="f-button-1" onmousedown="FormOnly.allFieldsContianer[3].eslah('.$wfidEslah.',\''.$date.'\')">اصلاح<br>تردد</div>';
    //   $creat .= "<div class='f-button-1' onmousedown='FormOnly.allFieldsContianer[3].CreateFormTaradodH($wfidMorkhasiS,$date2[0],$date2[1],$date2[2],$CodeM)'>حذف<br>تردد</div>";
    //   $creat .= "<div class='f-button-1' onmousedown='FormOnly.allFieldsContianer[3].CreateFormTaradodA($wfidMorkhasiS,$date2[0],$date2[1],$date2[2],$CodeM)'>افزودن<br>تردد</div>";

    //  $creat .= "<div class='f-button-1' onmousedown='FormOnly.allFieldsContianer[3].MorkhasiS($wfidMorkhasiS,'$date')'>مرخصي<br>ساعتی</div>";
}


        $btnsBox .= '<div class="f-button-1" onmousedown="window.codeSet.createMamoriatSaati('.$wfidMamoriatS.',\''.$date.'\')">ماموریت<br>ساعتی</div>';
        $btnsBox .= '<div class="f-button-1" onmousedown="window.codeSet.createMorkhasiSaati('.$wfidMorkhasiS.',\''.$date.'\')">مرخصی<br>ساعتی</div>';
        $btnsBox .= '<div class="f-button-1" onmousedown="window.codeSet.createAfzoodanTaradod('.$wfidAfzoodanTaradod.',\''.$date.'\')">افزودن<br>تردد</div>';


        $link = "
        <a class='f-button-float-1' href='#Modal$this->trNumber'>ايجاد فرم</a>
        <div id='Modal$this->trNumber' class='f-modal-modalDialog'>
            <div style='width: 310px'>
            <a href='#close' class='f-modal-close'>✖</a>
            $btnsBox
            </div>
        </div>
        ";

        return $link;


    }
    private function buildLowWorkTd($row){


        //تاخیر اول
        $s16 = urldecode($row[16]) == NULL ? "00:00" : urldecode($row[16]);
        $takhirAvalHours=$this->getHoursFromTimeString($s16);
        $takhirAvalMinutes=$this->getMinutesFromTimeString($s16);
        $takhirAval=$this->makeTimeString($takhirAvalHours,$takhirAvalMinutes);

        //تعجیل آخر
        $s17 = urldecode($row[17]) == NULL ? "00:00" : urldecode($row[17]);
        $tagilAkharHours=$this->getHoursFromTimeString($s17);
        $tagilAkharMinutes=$this->getMinutesFromTimeString($s17);
        $tagilAkhar=$this->makeTimeString($tagilAkharHours,$tagilAkharMinutes);

        //کسر کار
        $s18 = urldecode($row[18]) == NULL ? "00:00" : urldecode($row[18]);
        $kasrKarHours=$this->getHoursFromTimeString($s18);
        $kasrKarMinutes=$this->getMinutesFromTimeString($s18);
        $kasrKar=$this->makeTimeString($kasrKarHours,$kasrKarMinutes);


        $lowWorkTd= "<td class='f-tooltip'>$kasrKar<span class='f-tooltip-text f-tooltip-right'>تاخير اول: $takhirAval<br>كسر وسط: $kasrKar<br>تعجيل آخر: $tagilAkhar</span></br>";
        return $lowWorkTd;


    }
    private function buildWorkOvertimeTd($row){
        // اضافه كاري و شب كاري و تعطيل كاري
        /*اضافه*/$s26 = urldecode($row[26]) == NULL ? "00:00" : urldecode($row[26]);
        /*شب*/$s31 = urldecode($row[31]) == NULL ? "00:00" : urldecode($row[31]);
        /*تعطيل*/$s28 = urldecode($row[28]) == NULL ? "00:00" : urldecode($row[28]);

        $workOvertimeTd="<td class='f-tooltip'>$s26<span class='f-tooltip-text f-tooltip-left'>شب كار: $s31<br>تعطيل كار: $s28</span></br>";
        return $workOvertimeTd;

    }

}

$mainAjax = new MainAjax();
Response::getInstance()->response = $mainAjax->main();
return $mainAjax;

