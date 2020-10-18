<?php

if (1)/*دریافت پارامترهای ورودی*/ {


    $db = MySQLAdapter::getInstance();

    $status = Request::getInstance()->varCleanFromInput('status');
    $mm = Request::getInstance()->varCleanFromInput('mm');
    $yy = Request::getInstance()->varCleanFromInput('yy');
    $aztd = Request::getInstance()->varCleanFromInput('aztd');
    $aztm = Request::getInstance()->varCleanFromInput('aztm');
    $azty = Request::getInstance()->varCleanFromInput('azty');
    $tatd = Request::getInstance()->varCleanFromInput('tatd');
    $tatm = Request::getInstance()->varCleanFromInput('tatm');
    $taty = Request::getInstance()->varCleanFromInput('taty');
    $PID = Request::getInstance()->varCleanFromInput('PID');
}
if (2)/*مقداردهی اول متغیرها*/ {
    $P = '';
    $html = '';
    $TimexUpdate = '';
    $creat = '';
    $TedadGheibat = 0;
    $TedadNaghes = 0;

    $res = 0;

    if ($status == 'DoreZamani') {
        $dt1 = $yy . "/" . $mm . "/01";
        $dt2 = $yy . "/" . $mm . "/31";
    } else if ($status == 'BazeZamani') {
        $dt1 = $azty . "/" . $aztm . "/" . $aztd;
        $dt2 = $taty . "/" . $tatm . "/" . $tatd;

    }

//$GID = '128';
//$dt1 = '1399/02/28';
//$dt2 = '1399/02/30';

}
if (3)/*دریافت آرایه اطلاعات از سرویس*/ {
    try {

        $params = array('location' => "http://46.209.31.219:8082/WSStaffInOut/StaffInOut.asmx?wsdl");
        $client = new SoapClient("http://46.209.31.219:8082/WSStaffInOut/StaffInOut.asmx?wsdl", $params);
        $client->soap_defencoding = 'UTF-8';
        $params = array("uname" => 'bf6db9a036816352f2a5128417ad3154', "pass" => 'dbe99b52bf0008708dc38373490d1526',
            "userID" => $PID, "startDate" => $dt1, "endDate" => $dt2);
        //$params=array("uname"=>'fava',"pass"=>'Adminkkr', "userID"=>'101',"startDate"=>'',"endDate"=>'' );
        $res = $client->GetStaffInOut($params)->GetStaffInOutResult;

        $res = get_object_vars($res->data)["StaffOutIn"];


    } catch (SoapFaultException $e) {
        $SoapStatus = 0;
    } catch (Exception $e) {
        $SoapStatus = 0;
    }

     /* Response::getInstance()->response=$res;
      return;*/


}

if(4)/*مرتب سازی آرایه*/
{
    $count = count($res);
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
    }
}

if(2)/*output*/
{
    $count = count($res);
   /* Response::getInstance()->response=$count;
    return;*/
    $output="";
    for ($i = 0; $i < $count; $i++)
    {
        $rowHtml="malek";
        $html .= "<tr><td class='class f-tooltip'>". $res[$i]->Date ."</td><td>";
        $date=$res[$i]->Date;
        $taradodha="<table cellpadding='0' cellspacing='3'><tr>";
        $j=$i;
        do{

            ///
            switch ($res[$i]->IOType) {

                case "0":
                    $class = 'table-Adi';
                    $tooltip = 'عادي';
                    $type = 'Adi';
                    break;
                case "1":
                    $class = 'table-Mor-N';
                    $tooltip = 'مرخصي';
                    $type = 'MorS';
                    break;
                case "2":
                    $class = 'table-Mam-N';
                    $tooltip = 'ماموريت';
                    $type = 'MamS';
                    break;
            }


                $taradodha .= "<td class='$class f-tooltip'>" . $res[$j]->Time . "<span class='f-tooltip-text f-tooltip-top'>$tooltip</span></td>";
            ///






           $j++;
        }while($j<$count && $res[$i]->Date==$res[$j]->Date);
        $i=$j-1;
        $taradodha.="</tr></table></td>";

        $html.=$taradodha."</tr>";



       // $row = $res[$i];
       /* $date = urldecode($row->Date);
        $output.="$date \n";*/

    }

}

if(4){

    $html = "


<section>
    <table class='table' cellpadding='0' cellspacing='3' style='width:100%'>
        <thead>
        <tr>
        <th style='width: 80px'>تاريخ</th>
       
        <th>ترددها</th>
        <!--
        <th>حضور</th>
        -->
      
        <!--
        <th>شب كار</th>
        <th>تعطيل كار</th>
        -->
        </tr></thead>
        <tbody>$html</tbody>
    </table>
</section>

";


}
Response::getInstance()->response=$html;
return;




if (false) {
//---





//---
    if (4)/*استخراج اطلاعات از آرایه دریافتی*/ {
        $count = count($res);
        $RoozMorkhasiT = 0;
        $ModalID = 0;
        $DocIDFroms = 0;

        $row = $res[0];
        $date = $row;


        $date = '';
        $dateMe = "";
        for ($i = 0; $i < $count; $i++) {
            /*$ModalID++;
            $taradodha = '';
            $class = '';
            $class2 = '';
            $Forms = '';
            $RoozMorkhasi = 0;
            $RoozTatile = 0;

            if ($count == 1) $row = $res['recs']['string'];
            else $row = $res[$i]['recs']['string'];*/
            $row = $res[$i];

            // تاريخ و روز
            $date = urldecode($row->Date);

            $date1 = explode('/', $date);
            $date1 = Date::jalali_to_gregorian($date1[0], $date1[1], $date1[2]);
            $date1 = implode('-', $date1);
            $rooz = $date1[0] . $date1[1] . $date1[2];

            $dateMe .= " Rooz=$rooz\n";
            if (true)//not sure
            {

                $a = explode(',', urldecode($row[3]));

                switch ($a[1]) {
                    case "1":
                        $class = 'table-Tat';
                        $RoozTatile = 1;
                        break;
                    case "2":
                        $class = 'table-Tat';
                        $RoozTatile = 1;
                        break;
                }
                $Check = strpos(urldecode($row[15]), 'مرخص');
                if ($Check !== false) {
                    $class2 = 'table-Mor';
                    $RoozMorkhasi = 0;
                    $RoozMorkhasiT++;
                }

                $Check = strpos(urldecode($row[15]), 'استعلا');
                if ($Check !== false) {
                    $class2 = 'table-Mor';
                    $RoozMorkhasi = 0;
                    $RoozMorkhasiT++;
                }

                $Check = strpos(urldecode($row[15]), 'مامور');
                if ($Check !== false) $class2 = 'table-Mam';

                $Check = strpos(urldecode($row[15]), 'اپ');
                if ($Check !== false) $class2 = 'table-Mam';

                $Check = strpos(urldecode($row[15]), 'ناقص');
                if ($Check !== false) {
                    $class2 = 'table-Nagh';
                    $TedadNaghes++;
                }

                $Check = strpos(urldecode($row[15]), 'بت');
                if ($Check !== false) {
                    $class2 = 'table-Tat';
                    $TedadGheibat++;
                }
                ///////for test////////
                $a[0] = "a[0]";
                $date = "date";
                $row[1] = "row[1]";
                $row[4] = "row[4]";
                /// end "for test"/////

                //  $html .= "<tr><td class='$class f-tooltip'>" . $a[0] . "<br>" . $date . "<span class='f-tooltip-text f-tooltip-right'>شيفت: " . urldecode($row[1]) . "<br>" . urldecode($row[4]) . "</span></td>";
                $html .= "<tr><td class='class f-tooltip'>" . "a0" . "<br>" . "date" . "<span class='f-tooltip-text f-tooltip-right'>شيفت: " . "row[1]" . "<br>" . "row[4]" . "</span></td>";

                // وضعيت
                /// for test/////

                /// end for test/////

                // $html .= "<td class='$class2'>" . urldecode($row[15]) . "</td>";
                $html .= "<td class='class2'>" . "row15" . "</td>";

                // ترددها
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
                        /// for test/////
                        $a[0] = "a[0]";
                        $tooltip = "tooltip";
                        /// end for test/////
                        $taradodha .= "<td class='$class f-tooltip'>" . $a[0] . "<span class='f-tooltip-text f-tooltip-top'>$tooltip</span></td>";
                        $s25 = urldecode($row[25]) == NULL ? "0000" : str_replace(":", "", urldecode($row[25]));
                        $s26 = urldecode($row[26]) == NULL ? "0000" : str_replace(":", "", urldecode($row[26]));
                        $s28 = urldecode($row[28]) == NULL ? "0000" : str_replace(":", "", urldecode($row[28]));
                    }
                }


                $creat = '';


                // $html .= "<td align='right' style='width:100%; position: relative;'><table cellpadding='0' cellspacing='3'><tr>$creat $taradodha</tr></table></td>";
                $html .= "<td align='right' style='width:100%; position: relative;'><table cellpadding='0' cellspacing='3'><tr>creat taradodha</tr></table></td>";

                // مرخصي و ماموريت
                $s19 = urldecode($row[19]) == NULL ? "00:00" : urldecode($row[19]);
                $s20 = urldecode($row[20]) == NULL ? "00:00" : urldecode($row[20]);
                $s23 = urldecode($row[23]) == NULL ? "00:00" : urldecode($row[23]);
                $TMorkhasi[$i] = $s19;
                $TMamoriat[$i] = $s20;

                //$html .= "<td class='f-tooltip'>$s23<span class='f-tooltip-text f-tooltip-top'>مرخصي: $s19<br>ماموريت: $s20</br></span></td>";
                $html .= "<td class='f-tooltip'>s23<span class='f-tooltip-text f-tooltip-top'>مرخصي: s19<br>ماموريت: s20</br></span></td>";

                // تاخير و تعجيل
                $s16 = urldecode($row[16]) == NULL ? "00:00" : urldecode($row[16]);
                $s16a = explode(':', $s16);
                $H = strlen(intval($s16a[0])) == 1 ? "0" . intval($s16a[0]) : intval($s16a[0]);
                $M = strlen(intval($s16a[1])) == 1 ? "0" . intval($s16a[1]) : intval($s16a[1]);
                $s16 = $H . ":" . $M;
                $s16a = explode(':', $s16);
                $s16j = "00:00";
                $FirstHour = 0;
                $JJJ = $s16;
                $JJJ = explode(':', $JJJ);

                $s17 = urldecode($row[17]) == NULL ? "00:00" : urldecode($row[17]);
                $s17a = explode(':', $s17);
                $H = strlen(intval($s17a[0])) == 1 ? "0" . intval($s17a[0]) : intval($s17a[0]);
                $M = strlen(intval($s17a[1])) == 1 ? "0" . intval($s17a[1]) : intval($s17a[1]);
                $s17 = $H . ":" . $M;
                $s17a = explode(':', $s17);

                $s18 = urldecode($row[18]) == NULL ? "00:00" : urldecode($row[18]);
                $s18a = explode(':', $s18);
                $H = strlen(intval($s18a[0])) == 1 ? "0" . intval($s18a[0]) : intval($s18a[0]);
                $M = strlen(intval($s18a[1])) == 1 ? "0" . intval($s18a[1]) : intval($s18a[1]);
                $s18 = $H . ":" . $M;
                $s18a = explode(':', $s18);

                $KasrVasat1 = (int)$s16a[1] + ((int)$s16a[0] * 60) + (int)$s17a[1] + ((int)$s17a[0] * 60);
                $KasrVasat2 = (int)$s18a[1] + ((int)$s18a[0] * 60);
                $KasrVasat = (int)$KasrVasat2 - (int)$KasrVasat1;
                $KasrVasat = (int)$KasrVasat / 60;
                $KasrVasat = explode('.', (string)$KasrVasat);
                $H = (int)$KasrVasat[0];
                $M = "0." . $KasrVasat[1];
                $M = $M * 60;
                $H = $H + floor($M / 60);
                $H = strlen(intval($H)) == 1 ? "0" . intval($H) : intval($H);
                $M = $M % 60;
                $M = strlen(intval($M)) == 1 ? "0" . intval($M) : intval($M);
                $KasrVasat = $H . ":" . $M;
                $KasrVasata = explode(':', $KasrVasat);

                $H = (int)$s16a[0] + (int)$s17a[0] + (int)$KasrVasata[0];
                $M = (int)$s16a[1] + (int)$s17a[1] + (int)$KasrVasata[1];

                $H = $H + floor($M / 60);
                $H = strlen(intval($H)) == 1 ? "0" . intval($H) : intval($H);
                $M = $M % 60;
                $M = strlen(intval($M)) == 1 ? "0" . intval($M) : intval($M);
                $TjT = $H . ":" . $M;

                /// for test/////
                $s16 = "s16";
                $kasrVasat = "kasrVasat";
                $s17 = "s17";
                /// end for test/////

                // $html .= "<td class='f-tooltip'>$TjT<span class='f-tooltip-text f-tooltip-right'>تاخير اول: $s16<br>كسر وسط: $KasrVasat<br>تعجيل آخر: $s17</span></br>";
                $html .= "<td class='f-tooltip'>TjT<span class='f-tooltip-text f-tooltip-right'>تاخير اول: s16<br>كسر وسط: KasrVasat<br>تعجيل آخر: s17</span></br>";

                $TKasr[$i] = $TjT;

                // اضافه كاري و شب كاري و تعطيل كاري
                /*اضافه*/
                $s26 = urldecode($row[26]) == NULL ? "00:00" : urldecode($row[26]);
                /*شب*/
                $s30 = urldecode($row[31]) == NULL ? "00:00" : urldecode($row[31]);
                /*تعطيل*/
                $s28 = urldecode($row[28]) == NULL ? "00:00" : urldecode($row[28]);
                //$html .= "<td class='f-tooltip'>$s26</td>";
                //$html .= "<td class='f-tooltip'>$s30</td>";
                //$html .= "<td class='f-tooltip'>$s28</td>";


                /// for test/////

                $s30 = "s30";
                $s28 = "s28";
                /// end for test/////

                //  $html .= "<td class='f-tooltip'>$s26<span class='f-tooltip-text f-tooltip-left'>شب كار: $s30<br>تعطيل كار: $s28</span></br>";
                $html .= "<td class='f-tooltip'>s26<span class='f-tooltip-text f-tooltip-left'>شب كار: s30<br>تعطيل كار: s28</span></br>";
                $TEzafe[$i] = $s26;
                $TShab[$i] = $s30;
                $TTatil[$i] = $s28;
                $html .= "</tr>";
            }

        }
        /* Response::getInstance()->response=$dateMe;
         return;*/
    }
    if (false) /*مقادیر آماری اضافه*/ {
        $TMorkhasiH = (int)0;
        $TMorkhasiM = (int)0;
        foreach ($TMorkhasi as $time) {
            $time = explode(':', $time);
            $TMorkhasiH = $TMorkhasiH + (int)$time[0];
            $TMorkhasiM = $TMorkhasiM + (int)$time[1];
        }
        $TMorkhasiH = $TMorkhasiH + floor($TMorkhasiM / 60);
        $TMorkhasiH = strlen($TMorkhasiH) == 1 ? "0" . $TMorkhasiH : $TMorkhasiH;
        $TMorkhasiM = $TMorkhasiM % 60;
        $TMorkhasiM = strlen($TMorkhasiM) == 1 ? "0" . $TMorkhasiM : $TMorkhasiM;

        $TMorkhasiKol = number_format(($RoozMorkhasiT + (((($TMorkhasiH * 60) + $TMorkhasiM) / 60) / 7.33)), 2);

        $TMamoriatH = (int)0;
        $TMamoriatM = (int)0;
        foreach ($TMamoriat as $time) {
            $time = explode(':', $time);
            $TMamoriatH = $TMamoriatH + (int)$time[0];
            $TMamoriatM = $TMamoriatM + (int)$time[1];
        }
        $TMamoriatH = $TMamoriatH + floor($TMamoriatM / 60);
        $TMamoriatH = strlen($TMamoriatH) == 1 ? "0" . $TMamoriatH : $TMamoriatH;
        $TMamoriatM = $TMamoriatM % 60;
        $TMamoriatM = strlen($TMamoriatM) == 1 ? "0" . $TMamoriatM : $TMamoriatM;


        $TKasrH = (int)0;
        $TKasrM = (int)0;
        foreach ($TKasr as $time) {
            $time = explode(':', $time);
            $TKasrH = $TKasrH + (int)$time[0];
            $TKasrM = $TKasrM + (int)$time[1];
        }
        $TKasrH = $TKasrH + floor($TKasrM / 60);
        $TKasrH = strlen($TKasrH) == 1 ? "0" . $TKasrH : $TKasrH;
        $TKasrM = $TKasrM % 60;
        $TKasrM = strlen($TKasrM) == 1 ? "0" . $TKasrM : $TKasrM;

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

        $TTatilH = (int)0;
        $TTatilM = (int)0;
        foreach ($TTatil as $time) {
            $time = explode(':', $time);
            $TTatilH = $TTatilH + (int)$time[0];
            $TTatilM = $TTatilM + (int)$time[1];
        }
        $TTatilH = $TTatilH + floor($TTatilM / 60);
        $TTatilH = strlen($TTatilH) == 1 ? "0" . $TTatilH : $TTatilH;
        $TTatilM = $TTatilM % 60;
        $TTatilM = strlen($TTatilM) == 1 ? "0" . $TTatilM : $TTatilM;

//محاسبات
//$TEzafe2H = $TEzafeH + $TTatilH + $TShabH + $KharejKartH;
        //   $TEzafe2H = $TEzafeH + $KharejKartH;
//$TEzafe2M = $TEzafeM + $TTatilM + $TShabM + $KharejKartM;
        //   $TEzafe2M = $TEzafeM + $KharejKartM;
        $TEzafe2H = $TEzafe2H + floor($TEzafe2M / 60);
        $TEzafe2H = strlen($TEzafe2H) == 1 ? "0" . $TEzafe2H : $TEzafe2H;
        $TEzafe2M = $TEzafe2M % 60;
        $TEzafe2M = strlen($TEzafe2M) == 1 ? "0" . $TEzafe2M : $TEzafe2M;

        $TShab2 = ((int)$TShabH * 60) + (int)$TShabM;
        $TKasr2 = ((int)$TKasrH * 60) + (int)$TKasrM;
        $TEzafe = ((int)$TEzafeH * 60) + (int)$TEzafeM;
        $TEzafe2 = ((int)$TEzafe2H * 60) + (int)$TEzafe2M;

        $diff_time = (int)$TEzafe - (int)$TShab2;
        if ($diff_time < 0) $manfi = '-';
        else $manfi = '';
        $diff_time = (int)$diff_time / 60;
        $diff_time = explode('.', (string)$diff_time);
        $h = (int)$diff_time[0];
        $m = "0." . $diff_time[1];
        $m = $m * 60;
        $h = $h + floor($m / 60);
        $TEzafeKhalesH = strlen($h) == 1 ? "0" . $h : $h;
        $m = $m % 60;
        $TEzafeKhalesM = strlen($m) == 1 ? "0" . $m : $m;

        if ($TEzafeKhalesH == '00' && $manfi == '-') $TEzafeKhales = $TEzafeKhalesH . ':' . $TEzafeKhalesM . $manfi;
        else $TEzafeKhales = $TEzafeKhalesH . ':' . $TEzafeKhalesM;
    }
    if (6)/*ساخت html خروجی*/ {
        //for test
        $TEzafeKhales = "";
        $TKasrH = $TKasrM = "";
        $TNahaee = "";
        $TTatilH = "";
        $TTatilM = "";
        $TShabH = "";
        $TShabM = "";
        $TedadNaghes = "";
        $TedadGheibat = "";
        $RoozMorkhasiT = "";
        $TMorkhasiH = "";
        $TMorkhasiM = "";
        $TMorkhasiKol = "";
        $TimexUpdate = "";
        $PID = "";
        $p = 0;

        //end for test

        $html = "
<section>
    
    <fieldset><table><tbody>
    
    <tr><td style='font-weight: bold !important'>جمع اضافه كار خالص: </td>
    <td style=''>$TEzafeKhales</td></tr>
    
    <tr><td style='font-weight: bold !important'>جمع كسركار: </td>
    <td style=''>$TKasrH:$TKasrM</td></tr>

    <tr><td style='font-weight: bold !important'>اضافه كار پرداختي: </td>
    <td style=''>$TNahaee</td></tr>

    <tr><td style='font-weight: bold !important'>جمع تعطيل كار: </td>
    <td style=''>$TTatilH:$TTatilM</td></tr>

    <tr><td style='font-weight: bold !important'>جمع شب كار: </td>
    <td style=''>$TShabH:$TShabM</td></tr>

    </tbody></table></fieldset>
    <fieldset><table><tbody>

    <tr><td style='font-weight: bold !important'>تعداد تردد ناقص: </td>
    <td style=''>$TedadNaghes</td></tr>

    <tr><td style='font-weight: bold !important'>تعداد غيبت: </td>
    <td style=''>$TedadGheibat</td></tr>

    <tr><td style='font-weight: bold !important'>تعداد مرخصي روزانه: </td>
    <td style=''>$RoozMorkhasiT</td></tr>

    <tr><td style='font-weight: bold !important'>جمع مرخصي ساعتي: </td>
    <td style=''>$TMorkhasiH:$TMorkhasiM</td></tr>

    <tr><td style='font-weight: bold !important'>مرخصي استفاده شده: </td>
    <td style=''>$TMorkhasiKol روز</td></tr>

    </tbody></table></fieldset>
    <fieldset><table><tbody>

    <tr><td style='font-weight: bold !important'>آخرين تاريخ انجام محاسبات: </td>
    <td style=''>$TimexUpdate</td></tr>

    <tr><td style='font-weight: bold !important'>شماره پرسنلي در اتوماسيون: </td>
    <td style=''>$PID</td></tr>

    <tr><td style='font-weight: bold !important'>شماره پرسنلي در گراف: </td>
    <td style=''>$PID</td></tr>

    </tbody></table></fieldset>

</section>

<section>
    <table class='table' cellpadding='0' cellspacing='3' style='width:100%'>
        <thead>
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
        <tbody>$html</tbody>
    </table>
</section>

";

    }

//if($SoapStatus == 1)
    Response::getInstance()->response = $html . $P;
//else
    //   Response::getInstance()->response = '<section>امكان ارتباط با سامانه گراف مقدور نمي باشد</section>';

}

Response::getInstance()->response=$output;