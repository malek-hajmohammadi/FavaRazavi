<?php

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
$PID = Request::getInstance()->varCleanFromInput('codem');

//---
$sql2 = "SELECT employeeID FROM oa_users WHERE NationalCode = '$CodeM'";
$db->executeSelect($sql2);
$person = $db->fetchAssoc();
$PIDRAVAN = $person['employeeID'];

$P = '';
$html = '';
$TimexUpdate = '';
$creat = '';
$TedadGheibat = 0;
$TedadNaghes = 0;
$MandeMorkhasi = 0;

if ($status == 'DoreZamani') {
    switch ($mm) {
        case 1:
            $DateOne = "$yy/01/01";
            $DateTwo = "$yy/01/24";
            $DateEnd = "$yy/01/31";
            break;
        case 13:
            $DateOne = "$yy/12/25";
            $DateTwo = "$yy/12/30";
            $DateEnd = "$yy/12/30";
            break;
        default:
            $m = (int)$mm - 1;
            if (strlen($m) == 1) $m = "0" . $m;
            if (strlen($mm) == 1) $mm = "0" . $mm;
            $DateOne = "$yy/$m/25";
            $DateTwo = "$yy/$mm/24";
            if ($mm <= 6) $d = 31;
            if ($mm >= 7) $d = 30;
            $DateEnd = "$yy/$mm/$d";
            break;
    }
} else if ($status == 'BazeZamani') {
    $DateOne = $azty . "/" . $aztm . "/" . $aztd;
    $DateTwo = $taty . "/" . $tatm . "/" . $tatd;
    if ($tatm <= 6) $d = 31;
    if ($tatm >= 7) $d = 30;
    $DateEnd = "$taty/$tatm/$d";
}

$DateOnee = Date::JalaliToGreg($DateOne);
$DateTwoe = Date::JalaliToGreg($DateTwo);

//---
function Func($Num)
{
    $Num = explode(':', $Num);
    $Day = $Num[0];
    $Hour = $Num[1];
    $Min = $Num[2];
    if ($Day != 0 && $Hour != 0 && $Min != 0) $res = $Day . ' روز و ' . $Hour . ' ساعت و ' . $Min . ' دقيقه';
    elseif ($Day != 0 && $Hour != 0 && $Min == 0) $res = $Day . ' روز و ' . $Hour . ' ساعت';
    elseif ($Day != 0 && $Hour == 0 && $Min != 0) $res = $Day . ' روز و ' . $Min . ' دقيقه';
    elseif ($Day == 0 && $Hour != 0 && $Min != 0) $res = $Hour . ' ساعت و ' . $Min . ' دقيقه';
    elseif ($Day == 0 && $Hour == 0 && $Min != 0) $res = $Min . ' دقيقه';
    else $res = '0';
    return $res;
}


$tedad = 0;

try {
    $client = new SoapClient('http://192.168.100.46:85/TimeX.asmx?wsdl');

    $s1 = "SELECT * FROM adon.Kardex('" . $PID . "', '" . $DateEnd . "')";
    $param = array(
        'username' => '3ef1b48067e4f2ac9913141d77e847dd',
        'password' => '9a3f5b14f1737c15e86680d9cd40b840',
        'objStr'   => $s1
    );
    $res = $client->RunSelectQuery($param);
    $res = $res->RunSelectQueryResult->cols;
    $res = json_decode(json_encode($res), true);
    $KardexMandeJari = Func(urldecode($res['recs']['string'][32]));
    $KardexSaleGhabl = Func(urldecode($res['recs']['string'][38]));


    $s1 = "EXEC [adon].[TimeSheetView] " . $PID . ",'" . $DateOne . "','" . $DateTwo . "'";
    //EXEC [adon].[TimeSheetView] 361405,'1399/12/01','1399/12/29'
    $param = array(
        'username' => '3ef1b48067e4f2ac9913141d77e847dd',
        'password' => '9a3f5b14f1737c15e86680d9cd40b840',
        'objStr'   => $s1
    );
    $res = $client->RunSelectQuery($param);
    $res = $res->RunSelectQueryResult->cols;
    $res = json_decode(json_encode($res), true);
    //Response::getInstance()->response = var_export($res,true);
    //$P = $P.' -*- Q: '.$s1;

    $SoapStatus = 1;
} catch (SoapFaultException $e) {
    $SoapStatus = 0;
} catch (Exception $e) {
    $SoapStatus = 0;
}

$count = count($res);
$RoozMorkhasiT = 0;
$ModalID = 0;
$DocIDFroms = 0;
for ($i = 0; $i < $count; $i++) {
    $ModalID++;
    $taradodha = '';
    $class = '';
    $class2 = '';
    $Forms = '';
    $RoozMorkhasi = 0;
    $RoozTatile = 0;
    if ($count == 1) $row = $res['recs']['string'];
    else $row = $res[$i]['recs']['string'];

    // تاريخ و روز
    $date = urldecode($row[2]);
    $date1 = explode('/', $date);
    $date1 = Date::jalali_to_gregorian(("13" . $date1[0]), $date1[1], $date1[2]);
    $date1 = implode('-', $date1);
    $rooz = $date1[0] . $date1[1] . $date1[2];
    $a = explode(',', urldecode($row[3]));
    switch (urldecode($row[1])) {
        default:
            $class = '';
            break;
        case "15":
            $class = 'f-table-Shift';
            break;
        case "16":
            $class = 'f-table-Shift';
            break;
    }
    switch ($a[1]) {
        case "1":
            $class = 'f-table-Tat';
            $RoozTatile = 1;
            break;
        case "2":
            $class = 'f-table-Tat';
            $RoozTatile = 1;
            break;
    }
    $Check = strpos(urldecode($row[15]), 'مرخص');
    if ($Check !== false) {
        $class2 = 'f-table-Mor';
        $RoozMorkhasi = 0;
        $RoozMorkhasiT++;
    }

    $Check = strpos(urldecode($row[15]), 'استعلا');
    if ($Check !== false) {
        $class2 = 'f-table-Mor';
        $RoozMorkhasi = 0;
        $RoozMorkhasiT++;
    }

    $Check = strpos(urldecode($row[15]), 'مامور');
    if ($Check !== false) $class2 = 'f-table-Mam';

    $Check = strpos(urldecode($row[15]), 'ناقص');
    if ($Check !== false) {
        $class2 = 'f-table-Nagh';
        $TedadNaghes++;
    }

    $Check = strpos(urldecode($row[15]), 'بت');
    if ($Check !== false) {
        $class2 = 'f-table-Tat';
        $TedadGheibat++;
    }

    $html .= "<tr><td class='$class f-tooltip'>" . $a[0] . "<br>" . $date . "<span class='f-tooltip-text f-tooltip-left'>شيفت: " . urldecode($row[1]) . "<br>" . urldecode($row[4]) . "</span></td>";

    // وضعيت
    $html .= "<td class='$class2'>" . urldecode($row[15]) . "</td>";

    // ترددها
    $Nahar = 0;
    for ($j = 5; $j <= 14; $j++) {
        $a = explode(',', urldecode($row[$j]));
        switch ($a[1]) {
            default:
                $class = 'f-table-Adi';
                $tooltip = 'فاقد تردد';
                $type = 'N';
                break;
            case "0":
                $class = 'f-table-Adi';
                $tooltip = 'عادي';
                $type = 'Adi';
                break;
            case "-16744156":
                $class = 'f-table-Mam';
                $tooltip = 'ماموريت ساعتي';
                $type = 'MamS';
                break;
            case "-32768":
                $class = 'f-table-Mor';
                $tooltip = 'مرخصي ساعتي';
                $type = 'MorS';
                break;
        }
        if ($a[0] != NULL) {
            $taradodha .= "<td class='$class f-tooltip'>" . $a[0] . "<span class='f-tooltip-text f-tooltip-top'>$tooltip</span></td>";
            $s25 = urldecode($row[25]) == NULL ? "0000" : str_replace(":", "", urldecode($row[25]));
            $s26 = urldecode($row[26]) == NULL ? "0000" : str_replace(":", "", urldecode($row[26]));
            $s28 = urldecode($row[28]) == NULL ? "0000" : str_replace(":", "", urldecode($row[28]));
        }
    }


    $html .= "<td align='right' style='width:100%; position: relative;'><table cellpadding='0' cellspacing='3'><tr>$taradodha</tr></table></td>";

    // حضور
    $s23 = urldecode($row[23]) == NULL ? "00:00" : urldecode($row[23]);
    $html .= "<td class='f-tooltip'>$s23</td>";

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

    $html .= "<td class='f-tooltip'>$TjT<span class='f-tooltip-text f-tooltip-right'>تاخير اول: $s16<br>كسر وسط: $KasrVasat<br>تعجيل آخر: $s17</span></td>";

    $TKasr[$i] = $TjT;

    // اضافه كاري و شب كاري و تعطيل كاري
    /*اضافه*/
    $s26 = urldecode($row[26]) == NULL ? "00:00" : urldecode($row[26]);
    /*شب*/
    $s30 = urldecode($row[31]) == NULL ? "00:00" : urldecode($row[31]);
    /*تعطيل*/
    $s28 = urldecode($row[28]) == NULL ? "00:00" : urldecode($row[28]);
    /*غيرمجاز*/
    $s27 = urldecode($row[27]) == NULL ? "00:00" : urldecode($row[27]);
    //$html .= "<td class='f-tooltip'>$s26</td>";
    //$html .= "<td class='f-tooltip'>$s30</td>";
    //$html .= "<td class='f-tooltip'>$s28</td>";
    //$html .= "<td class='f-tooltip'>$s26<span class='f-tooltip-text f-tooltip-right'>شب كار: $s30<br>تعطيل كار: $s28</span></td>";
    $html .= "<td class='f-tooltip'>$s26</td>";
    //$html .= "<td class='f-tooltip'>$s30</td>";
    $html .= "<td class='f-tooltip'>$s28</td>";
    $html .= "<td class='f-tooltip'>$s27</td>";
    $TEzafe[$i] = $s26;
    $TShab[$i] = $s30;
    $TTatil[$i] = $s28;
    $html .= $Forms . "</tr>";

}

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
$TEzafe2H = $TEzafeH + $KharejKartH;
//$TEzafe2M = $TEzafeM + $TTatilM + $TShabM + $KharejKartM;
$TEzafe2M = $TEzafeM + $KharejKartM;
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

$html = "

<section>
    
    <fieldset><legend>گزارش</legend><table><tbody>
    <tr><td style='font-weight: bold !important'>جمع اضافه كار خالص: </td>
    <td style=''>$TEzafeKhales</td></tr>
    
    <tr><td style='font-weight: bold !important'>جمع كسركار: </td>
    <td style=''>$TKasrH:$TKasrM</td></tr>

    <tr><td style='font-weight: bold !important'>جمع تعطيل كار: </td>
    <td style=''>$TTatilH:$TTatilM</td></tr>

    <!--
    <tr><td style='font-weight: bold !important'>جمع شب كار: </td>
    <td style=''>$TShabH:$TShabM</td></tr>
    -->
    </tbody></table></fieldset>

    <fieldset><legend>نواقص</legend><table><tbody>

    <tr><td style='font-weight: bold !important'>تعداد تردد ناقص: </td>
    <td style=''>$TedadNaghes</td></tr>

    <tr><td style='font-weight: bold !important'>تعداد غيبت: </td>
    <td style=''>$TedadGheibat</td></tr>

    </tbody></table></fieldset>

    <fieldset><legend>مرخصي</legend><table><tbody>

    <tr><td style='font-weight: bold !important'>مانده جاري: </td>
    <td style=''>$KardexMandeJari</td></tr>

    <tr><td style='font-weight: bold !important'>مانده از سال قبل: </td>
    <td style=''>$KardexSaleGhabl</td></tr>

    <tr><td style='font-weight: bold !important'>محاسبات تا </td>
    <td style=''>$DateEnd</td></tr>

    </tbody></table></fieldset>

</section>

<section>
    <table class='f-table' cellpadding='0' cellspacing='3' style='width:100%'>
        <thead>
        <tr>
        <th>تاريخ</th>
        <th>وضعيت</th>
        <th>ترددها</th>
        <th>حضور</th>
        <th>كسر كار</th>
        <th>اضافه  كار</th>
        <th>تعطيل كار</th>
        <th>غير مجاز</th>
        </tr></thead>
        <tbody>$html</tbody>
    </table>
</section>

";
if ($SoapStatus == 1)
    Response::getInstance()->response = $html . $P;
else
    Response::getInstance()->response = '<section>امكان ارتباط با سامانه گراف مقدور نمي باشد</section>';
