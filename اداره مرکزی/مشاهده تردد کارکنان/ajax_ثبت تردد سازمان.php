<?php



$IP = $_SERVER['REMOTE_ADDR'];

$validIPRange = array(
    '10.5.50.',
    '10.9.',
    '10.11.',
    '10.20.',
    '10.30.',
    '10.31.',
    '10.40.',
    '10.41.',
    '10.42.',
    '10.50.',
    '172.18.1.',
    '172.20.26.',
    '172.24.26.',
    '172.32.',
    '192.168.1.',
    '192.168.20.',
    '192.168.85.',
    '10.10.10.1',
    /*'10.10.10.5',*/
    '10.10.10.210',
);
$IPIsValid = false;
foreach ($validIPRange as $value) {
    $ip_array = explode('.', $value);
    if(count($ip_array) == 4) {
        if($IP == $value) {
            $IPIsValid = true;
        }
        continue;
    }
    if (strpos($IP, $value) === 0) {
        $IPIsValid = true;
    }
}

if (!$IPIsValid) {
    Response::getInstance()->response = '<div class="dvQueryFalse">ثبت تردد فقط از طريق رايانه اداري در محل كار امكان‌پذير است، آدرس اينترنتي شما: ' . $IP . '</div>';
    return;
}

$html = '';
$P = '';
$SoapStatus = 0;
$db = MySQLAdapter::getInstance();

$WFidEslah = $db->executeScalar("SELECT `workflow_id` FROM `wf_workflow` WHERE `workflow_enable` = 1 AND `workflow_formtypeid` = 790");

$status = Request::getInstance()->varCleanFromInput('status');
$TimeD = Request::getInstance()->varCleanFromInput('TimeD');

$newTime = strtotime(date('Y-m-d') . ' ' . $TimeD);
$nowTime = time();
$diff = ($nowTime > $newTime)? ($nowTime - $newTime) : ($newTime - $nowTime);
if($diff > (15 * 60)) {
    Response::getInstance()->response = '<div class="dvQueryFalse">اختلاف ' . round($diff/60) . ' دقيقه اي با ساعت جاري بيش از حد مجاز است' + '</div>';
}

$rid = AccessControlManager::getInstance()->getRoleID();
$uid = AccessControlManager::getInstance()->getUserID();
$sql2 = "SELECT employeeID FROM oa_users WHERE UserID=" . $uid;
$db->executeSelect($sql2);
$person = $db->fetchAssoc();
$db->close();
$PID = $person['employeeID'];

$DateD = date('Y-m-d');
$DateD = Date::GregToJalali($DateD);

/*
$TimeD = date('H:i');
$TimeD = strtotime('+1 minute', strtotime($TimeD)) ;
$TimeD = date('H:i', $TimeD);
*/


$TimeD = explode(':', $TimeD);
if (intval($TimeD[0]) < 10) {
    $TimeD[0] = trim($TimeD[0], '0');
    $TimeD[0] = '0' . $TimeD[0];
}
if ($TimeD[0] == '0') $TimeD[0] = '00';
if (intval($TimeD[1]) < 10) {
    $TimeD[1] = trim($TimeD[1], '0');
    $TimeD[1] = '0' . $TimeD[1];
}
if ($TimeD[1] == '0') $TimeD[1] = '00';
$TimeD = implode(':', $TimeD);


//----------------
//-- ثبت تردد
//----------------
if ($status != 'Load') {
    try {
        $client = new SoapClient('http://10.10.100.15/WSTuralInOut/TuralInOut.asmx?wsdl');
        $sql = "EXEC InOutData.dbo.[iNormalTaradod] '" . $PID . "', '" . $DateD . "', '" . $TimeD . "' , '500' ";
        /* پارامتر چهارم بابت تشخيص هست از فرم ثبت تردد ۵۰۰ از فرم اصلاح تردد ۴۰۰ */
        $sql = urlencode($sql);
        $param = array(
            'username' => '8bfc0e61722d9e9c9bb2138cb359fef9',
            'password' => '085c734188fb09a96eba5d22893a44c4',
            'objStr' => $sql
        );
        $result = $client->RunQuery($param);
        $result = json_decode(json_encode($result), true);
        $SoapStatus = 1;
    } catch (SoapFaultException $e) {
        $SoapStatus = 0;
    } catch (Exception $e) {
        $SoapStatus = 0;
    }
//$P = $P." <br> SoapStatus: '.$SoapStatus;
//$P = $P." <br> Query: '.$sql;
//$P = $P." <br> Res: '.$result['RunQueryResult'];

    if ($SoapStatus == 1 && is_array($result) && isset($result['RunQueryResult']) && $result['RunQueryResult']) {
        $html = "
        <div class='dvQueryTrue'>
            ساعت $TimeD براي روز $DateD با موفقيت ثبت شد
        </div>
        ";
    } else {
        $html = "
        <div class='dvQueryFalse'>
            ارتباط با تورال امكان پذير نيست
        </div>
        ";
    }
}


//----------------
//-- نمايش ترددها
//----------------
//$PID = '7110';
//$DateD = '1398/10/10';

$html2 = '';
$P = '';
$SoapStatus = 0;
$DateD2 = explode("/", $DateD);
//$P = $P." <br> YY: ".$DateD2[0];
//$P = $P." <br> MM: ".$DateD2[1];

try {
    $client = new SoapClient('http://10.10.100.15/WSTuralInOut/TuralInOut.asmx?wsdl');
    if(!$client) {
        Response::getInstance()->response = 'خطاي ارتباطي';
        return;
    }
    $sql = "EXEC [InOutData].[dbo].[sTaradodForRavan] '" . $PID . "', " . $DateD2[0] . ", " . $DateD2[1] . "";
//$sql = "SELECT * FROM InOutData.dbo.[sTaradodForRavan]('".$PID."','".$DateD2[0]."','".$DateD2[1]."')";
    $sql = urlencode($sql);
    $param = array(
        'username' => '8bfc0e61722d9e9c9bb2138cb359fef9',
        'password' => '085c734188fb09a96eba5d22893a44c4',
        'objStr' => $sql
    );
    $result = $client->RunSelectQuery($param);
    $rawResult = $result;
    $result = $result->RunSelectQueryResult->cols;
    $result = json_decode(json_encode($result), true);
    $SoapStatus = 1;
} catch (SoapFaultException $e) {
    Response::getInstance()->response = 'SoapFaultException: '.var_export($rawResult);
    return;
    $SoapStatus = 0;
} catch (Exception $e) {
    Response::getInstance()->response = 'Exception: '.var_export($rawResult);
    return;
    $SoapStatus = 0;
}
//$P = $P." <br> SoapStatus: ".$SoapStatus;
//$P = $P." <br> Query: ".$sql;
//$P = $P." <br> Res: ".urldecode(var_export($result[1]['recs']['string'], true));
//$P = $P." <br> Res: ".urldecode($result[0]);


if ($SoapStatus == 1) {
    $count = count($result);
//$P = $P." <br> count: ".$count;
    $RoozMorkhasiT = 0;
    $DocIDFroms = 0;
    for ($i = 0; $i < $count; $i++) {
        $taradodha = '';
        $class = '';
        $class2 = '';
        $vaziat = '';
        $html2 .= "<tr>";
        $row = $result[$i]['recs']['string'];

        $class = '';
        if (urldecode($row[4]) == 0) {
            $class = '';
            $class2 = '';
            $vaziat = '';
        }
        if (urldecode($row[4]) == 1) {
            $class = '';
            $class2 = 'f-table-Mor';
            $vaziat = 'مرخصي استحقاقي';
        }
        if (urldecode($row[4]) == 3 || urldecode($row[4]) == 5) {
            $class = '';
            $class2 = 'f-table-Mor';
            $vaziat = 'مرخصي استعلاجي';
        }
        if (urldecode($row[4]) == 4 || urldecode($row[4]) == 17) {
            $class = '';
            $class2 = 'f-table-Mor';
            $vaziat = 'بدون حقوق';
        }
        if (urldecode($row[4]) == 11) {
            $class = '';
            $class2 = 'f-table-Mor';
            $vaziat = 'مرخصي اضطراري';
        }
        if (urldecode($row[4]) == 10) {
            $class = '';
            $class2 = 'f-table-Mor';
            $vaziat = 'استراحت';
        }
        if (urldecode($row[4]) == 19) {
            $class = '';
            $class2 = 'f-table-Mor';
            $vaziat = 'مرخصي تشويقي';
        }
        if (urldecode($row[4]) == 18) {
            $class = '';
            $class2 = 'f-table-Mor';
            $vaziat = 'مرخصي زايمان';
        }
        if (urldecode($row[4]) == 9) {
            $class = '';
            $class2 = 'f-table-Mor';
            $vaziat = 'چكاپ';
        }
        if (urldecode($row[4]) == 6) {
            $class = '';
            $class2 = 'f-table-Mor';
            $vaziat = 'مرخصي اداري';
        }
        if (urldecode($row[4]) == 2) {
            $class = '';
            $class2 = 'f-table-Mam';
            $vaziat = 'ماموريت روزانه';
        }
        /*
        if(urldecode($row[4]) == 100){
        $class = 'f-table-Tat';
        $class2 = 'f-table-Tat';
        $vaziat = 'تعطيل';
        }
        */
        if (strpos(urldecode($row[3]), 'تعط') !== false) {
            $class = 'f-table-Tat';
            $class2 = 'f-table-Tat';
            $vaziat = 'تعطيل';
        }

        // روز و تاريخ و شيفت
        $html2 .= "<td class='$class f-tooltip'>" . urldecode($row[2]) . "<br>" . urldecode($row[1]) . "<span class='f-tooltip-text f-tooltip-right'>" . urldecode($row[13]) . "</span></td>";

        // وضعيت
        $html2 .= "<td class='$class2 f-tooltip'>" . $vaziat . "<span class='f-tooltip-text f-tooltip-right'>" . urldecode($row[3]) . "<br>" . urldecode($row[4]) . "</span></td>";

        // ترددها
        for ($j = 5; $j <= 12; $j += 2) {
            switch (urldecode($row[$j + 1])) {
                default:
                    $class = 'f-table-Adi';
                    $tooltip = 'فاقد تردد';
                    $type = 'N';
                    break;
                case 0:
                    $class = 'f-table-Adi';
                    $tooltip = 'عادي';
                    $type = 'Adi';
                    break;
                case 1:
                    $class = 'f-table-Mor';
                    $tooltip = 'مرخصي ساعتي';
                    $type = 'MorS';
                    break;
                case 2:
                    $class = 'f-table-Mam';
                    $tooltip = 'ماموريت ساعتي';
                    $type = 'MamS';
                    break;
            }
            if (trim(urldecode($row[$j]), ' ') != '') $taradodha .= "<td class='$class f-tooltip'>" . urldecode($row[$j]) . "<span class='f-tooltip-text f-tooltip-top'>$tooltip</span></td>";
        }
        $creat = '';
        $DateE = explode("/", urldecode($row[1]));
        $creat .= "<button class='f-button-float-1' onmousedown=FormOnly.allFieldsContianer[5].CreateForm($WFidEslah,$DateE[0],$DateE[1],$DateE[2])>اصلاح<br>تردد</button>";
        $html2 .= "<td align='right' style='width:100%; position: relative;'><table cellpadding='0' cellspacing='3'><tr>$creat $taradodha</tr></table></td>";

        // تاخير
        $html2 .= "<td>" . urldecode($row[14]) . "</td>";

        // تعجيل
        $html2 .= "<td>" . urldecode($row[15]) . "</td>";

        // اضافه كار
        $html2 .= "<td>" . urldecode($row[16]) . "</td>";

        // مرخصي ساعتي
        $html2 .= "<td>" . urldecode($row[17]) . "</td>";

        // ماموريت ساعتي
        $html2 .= "<td>" . urldecode($row[18]) . "</td>";

        //FormOnly.allFieldsContianer[10].eslah(4883 ,1398,12,03)
        $html2 .= "</tr>";
    }
} else $html2 = 'خطا';

$html2 = "
<section id='SazmanST-Taradodha'>
    <table class='f-table' cellpadding='0' cellspacing='3' style='width:100%'>
        <thead>
        <tr>
            <th>تاريخ</th>
            <th>وضعيت</th>
            <th>ترددها</th>
            <th>تاخير</th>
            <th>تعجيل</th>
            <th>اضافه  كار</th>
            <th>مرخصي ساعتي</th>
            <th>ماموريت ساعتي</th>
        </tr></thead>
        <tbody>$html2</tbody>
    </table>
</section>
";
Response::getInstance()->response = $html . $html2 . $P;
