<?php



$acm = AccessControlManager::getInstance();
$uid = $acm->getUserID();

if(!in_array($uid, [377, 84, 153, 267, 253, 274, 353, 355, 338, 464,456])) {
    Response::getInstance()->response = 'access denied';
    return;
}

$year = Request::getInstance()->varCleanFromInput("year");

$systemIDـARAMAN = "1389060000";
$userـARAMAN = "ravanrequest";
$passـARAMAN = "4H8_gHf2X2@y7Wt";
$server = "http://erp.aqrazavi.net/Services/HesabServices.svc/Reports/1?C_Sal=$year&ArmanSystemID=$systemIDـARAMAN&ArmanUserName=$userـARAMAN&ArmanPassword=$passـARAMAN";

$client = curl_init($server);

curl_setopt($client, CURLOPT_HEADER, true);
curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
curl_setopt($client, CURLOPT_VERBOSE, true);
$resp = curl_exec($client);
$header_size = curl_getinfo($client, CURLINFO_HEADER_SIZE);
$resp = substr($resp, $header_size);
curl_close($client);

$resp = trim($resp, '"');
$resp = trim($resp, "'");
$resp =  str_replace('\\', '', $resp);
$resp = json_decode($resp, true);

/*if(!$resp && $uid != 353) {
    $resp = '"{\"Error\":[{\"Description\":\"عمليات با موفقيت انجام شد\",\"Action\":\"RaiseSuccess\",\"Result\":\"True\",\"Type\":1,\"Order\":0,\"Storage\":null}],\"data\":[{\"N_Tafzili\":\"واحد ( توليد و پشتيباني ) معاونت سخت افزار\",\"Hazineh\":22041638873,\"Foroosh\":22654633437,\"SoodZiyan\":612994564,\"TashimHazinehMoshtarak\":25549236,\"TedadNiroo\":2,\"Karkard\":3257.93,\"HazinehKamShavad\":777629540,\"TaghsimHazinehAmaliyatNiroo\":388814770,\"C_Tafzili\":20001},{\"N_Tafzili\":\"واحد معاونت شبكه\",\"Hazineh\":5574633106,\"Foroosh\":7315222293,\"SoodZiyan\":1740589187,\"TashimHazinehMoshtarak\":63363360,\"TedadNiroo\":5,\"Karkard\":8079.65,\"HazinehKamShavad\":2072209537,\"TaghsimHazinehAmaliyatNiroo\":414441907,\"C_Tafzili\":20002},{\"N_Tafzili\":\"واحد ( توسعه و پشتيباني ) معاونت نرم افزار\",\"Hazineh\":4394247290,\"Foroosh\":9147045194,\"SoodZiyan\":4752797904,\"TashimHazinehMoshtarak\":174562920,\"TedadNiroo\":15,\"Karkard\":22259.66,\"HazinehKamShavad\":4392612290,\"TaghsimHazinehAmaliyatNiroo\":292840819,\"C_Tafzili\":20006},{\"N_Tafzili\":\"واحد مديريت\",\"Hazineh\":152706435,\"Foroosh\":null,\"SoodZiyan\":null,\"TashimHazinehMoshtarak\":0,\"TedadNiroo\":null,\"Karkard\":null,\"HazinehKamShavad\":152706435,\"TaghsimHazinehAmaliyatNiroo\":null,\"C_Tafzili\":20008},{\"N_Tafzili\":\"واحد پشتيباني مستقر در اداره مركزي - ( نرم افزار و سخت افزار )\",\"Hazineh\":14144226137,\"Foroosh\":3321651320,\"SoodZiyan\":-10822574817,\"TashimHazinehMoshtarak\":613009140,\"TedadNiroo\":47,\"Karkard\":78169.79,\"HazinehKamShavad\":14144226137,\"TaghsimHazinehAmaliyatNiroo\":300940981,\"C_Tafzili\":20010},{\"N_Tafzili\":\"واحد مالي\",\"Hazineh\":1927828437,\"Foroosh\":null,\"SoodZiyan\":null,\"TashimHazinehMoshtarak\":67660776,\"TedadNiroo\":5,\"Karkard\":8627.59,\"HazinehKamShavad\":1927828437,\"TaghsimHazinehAmaliyatNiroo\":385565687,\"C_Tafzili\":20011},{\"N_Tafzili\":\"واحد اداري\",\"Hazineh\":657821790,\"Foroosh\":null,\"SoodZiyan\":null,\"TashimHazinehMoshtarak\":33602970,\"TedadNiroo\":2,\"Karkard\":4285.06,\"HazinehKamShavad\":657821790,\"TaghsimHazinehAmaliyatNiroo\":328910895,\"C_Tafzili\":20012},{\"N_Tafzili\":\"واحد تحقيق و توسعه\",\"Hazineh\":618522899,\"Foroosh\":null,\"SoodZiyan\":null,\"TashimHazinehMoshtarak\":26349120,\"TedadNiroo\":2,\"Karkard\":3360.34,\"HazinehKamShavad\":618522899,\"TaghsimHazinehAmaliyatNiroo\":309261449,\"C_Tafzili\":20013}],\"pageInfo\":{\"totalRowNum\":8},\"recordType\":\"object\",\"IsAuthenticated\":\"True\",\"Date\":\"1398\/07\/03\",\"Time\":\"08:59:59\"}"';
    $resp = json_decode(json_decode($resp, true),true);
}*/
if($resp && isset($resp['Error'])) {
    if ($resp['Error'][0]['Result'] == 'True') {
        $list = $resp['data'];

        $body = '';
        $i = 1;
        $count_Hazineh = array();
        $count_Foroosh = array();
        $count_SoodZiyan = array();
        $rowIndex = $rowsStart;
        $monyFields = array( 'Foroosh', 'ForooshAsli','Hazineh', 'TashimHazinehMoshtarak', 'SoodZiyan', 'HazinehKamShavad');
        $sumFields = array('Foroosh', 'ForooshAsli', 'Hazineh', 'TashimHazinehMoshtarak', 'SoodZiyan', 'TedadNiroo', 'Karkard', 'HazinehKamShavad');
        $sum = array();
        foreach ($list as $row){

            foreach ($sumFields as $field){
                if($row[$field] && is_numeric($row[$field]))
                    $sum[$field] += ($row[$field]);
            }

            $count_Hazineh[] = array("name" => $row['N_Tafzili'], "Foroosh" => ($row['Hazineh']));
            $count_Foroosh[] = array("name" => $row['N_Tafzili'], "Foroosh" => ($row['Foroosh']));
            $count_SoodZiyan[] = array("name" => $row['N_Tafzili'], "SoodZiyan" => ($row['SoodZiyan']));

            foreach ($monyFields as $field){
                $value = strrev($row[$field]);
                $negative = (strpos($value, '-') !== false)?'-':'';
                $value = str_replace('-', '', $value);
                $value = str_split($value, 3);
                $value = implode(',', $value);
                $value = strrev($value);
                $value = $negative.$value;
                $row[$field] = $value;
            }

            $body .= '
                    <tr id="accessRow_'.(++$i).'" >
                        <td style="padding: 2px;border: 1px solid #ccc;" >'.(($rowIndex++)+1).'</td>
                        <td style="padding: 2px;border: 1px solid #ccc;" >'.$row['N_Tafzili'].'</td>
                        <td style="padding: 2px;border: 1px solid #ccc;direction: ltr" >'.$row['Foroosh'].'</td>
                        <td style="padding: 2px;border: 1px solid #ccc;direction: ltr" >'.$row['ForooshAsli'].'</td>
                        <td style="padding: 2px;border: 1px solid #ccc;direction: ltr" >'.$row['Hazineh'].'</td>
                        <td style="padding: 2px;border: 1px solid #ccc;direction: ltr" >'.$row['TashimHazinehMoshtarak'].'</td>
                        <td style="padding: 2px;border: 1px solid #ccc;direction: ltr" >'.$row['SoodZiyan'].'</td>
                        <td style="padding: 2px;border: 1px solid #ccc;direction: ltr" >'.$row['TedadNiroo'].'</td>
                        <td style="padding: 2px;border: 1px solid #ccc;direction: ltr" >'.$row['Karkard'].'</td>
                        <td style="padding: 2px;border: 1px solid #ccc;direction: ltr" >'.$row['HazinehKamShavad'].'</td>
                    </tr>
                    ';

        }
        foreach ($monyFields as $field){
            if(!isset($sum[$field]))
                continue;
            $value = strrev($sum[$field]);
            $value = str_split($value, 3);
            $value = implode(',', $value);
            $sum[$field] = strrev($value);
        }
        $body .= '
                    <tr style="background-color: #eac88a;">
                        <td style="padding: 2px;border: 1px solid #ccc;" >-</td>
                        <td style="padding: 2px;border: 1px solid #ccc;font-weight: bold;" >جمع كل</td>
                        <td style="padding: 2px;border: 1px solid #ccc;direction: ltr" >'.$sum['Foroosh'].'</td>
                        <td style="padding: 2px;border: 1px solid #ccc;direction: ltr" >'.$sum['ForooshAsli'].'</td>
                        <td style="padding: 2px;border: 1px solid #ccc;direction: ltr" >'.$sum['Hazineh'].'</td>
                        <td style="padding: 2px;border: 1px solid #ccc;direction: ltr" >'.$sum['TashimHazinehMoshtarak'].'</td>
                        <td style="padding: 2px;border: 1px solid #ccc;direction: ltr" >'.$sum['SoodZiyan'].'</td>
                        <td style="padding: 2px;border: 1px solid #ccc;direction: ltr" >'.$sum['TedadNiroo'].'</td>
                        <td style="padding: 2px;border: 1px solid #ccc;direction: ltr" >'.$sum['Karkard'].'</td>
                        <td style="padding: 2px;border: 1px solid #ccc;direction: ltr" >'.$sum['HazinehKamShavad'].'</td>
                    </tr>
                    ';

        $count_Hazineh = json_encode($count_Hazineh);
        $count_Foroosh = json_encode($count_Foroosh);
        $count_SoodZiyan = json_encode($count_SoodZiyan);

        $html = '
                <table width="100%" class="f-table accessTable" cellpadding="0" cellspacing="1" >     
                    <tbody>
                        <tr>
                            <th width="3%" style="padding: 2px; ">رديف</th>
                            <th width="30%" style="padding: 2px; ">نام واحد</th>
                            <th width="10%" style="padding: 2px; ">فروش</th>
                            <th width="10%" style="padding: 2px; ">صورت حساب هاي صادر شده</th>
                            <th width="10%" style="padding: 2px; ">هزينه</th>
                            <th width="10%" style="padding: 2px; ">تسهيم هزينه مشترك</th>
                            <th width="10%" style="padding: 2px; ">تفاضل درآمد و هزينه</th>
                            <th width="7" style="padding: 2px; ">تعداد نيرو</th>
                            <th width="10%" style="padding: 2px; ">كاركرد به ساعت</th>
                            <th width="10%" style="padding: 2px; ">هزينه حقوق و سربار</th>
                        </tr>
                        '.$body.'
                    </tbody>
                </table>
                <div id="chartData_Hazineh" style="display: none;">'.$count_Hazineh.'</div>
                <div id="chartData_Foroosh" style="display: none;">'.$count_Foroosh.'</div>
                <div id="chartData_SoodZiyan" style="display: none;">'.$count_SoodZiyan.'</div>';
        $result = $html;
    } else
        $result = $resp['Error'][0]['Description'];
}
else
    $result = 'خطا در برقراري ارتباط';

Response::getInstance()->response = $result;
