<?php

$emp = "3100";


$s1 = "SELECT   report_Year,
report_Month,
Rotbe_PrsInfo.prsCode,
Rotbe_PrsInfo.prsFamily,
Rotbe_PrsInfo.prsName,
vahed,post,
estekhdam,
eshteghal,
startWork,
madrak,
reshte,
prsAge_Year,
prsAge_Month,
birthDate,
Rotbe_PrsInfo.sanavat_Year as Pyear,
Rotbe_PrsInfo.sanavat_Month as Pmonth ,
Rotbe_Rotbe.sanavat_Year as Ryear ,
Rotbe_Rotbe.sanavat_Month as Rmonth,
tahol,
vaziyat,
mamorMahal,
endWork,
gender,
prsType,
rotbeCur_Code,
rotbeNew_Code,
rotbeCur_Title,
rotbeNew_Title
  FROM Rotbe_PrsInfo
  left outer join Rotbe_Rotbe
  on Rotbe_PrsInfo.prsCode=Rotbe_Rotbe.prsCode
  where CAST(Rotbe_PrsInfo.prsCode as int)='" . (intval($emp)) . "' ";

$titles = array(
    0 => 'سال گزارش',
    1 => 'ماه گزارش',
    2 => 'پرسنلی در سیستم پرسنلی',
    3 => 'نام خانوادگی',
    4 => 'نام',
    5 => 'واحد سازمانی',
    6 => 'پست',
    7 => 'استخدامی',
    8 => 'اشتغال',
    9 => 'شروع به کار',
    10 => 'مدرک',
    11 => 'رشته',
    12 => 'سن(سال)',
    13 => 'سن(ماه)',
    14 => 'تاریخ تولد',
    15 => 'سنوات _ سال',
    16 => 'سنوات _ ماه',
    17 => 'سنوات _ سال2',
    18 => 'سنوات _ ماه2',
    19 => 'تاهل',
    20 => 'وضعیت',
    21 => 'محل ماموریت',
    22 => 'پایان کار',
    23 => 'جنسیت',
    24 => 'رده',
    25 => 'کد رتبه فعلی',
    26 => 'کد رتبه جدید',
    27 => 'عنوان رتبه فعلی',
    28 => 'عنوان رتبه جدید'
);

$s1 = urlencode($s1);

$client = new SoapClient('http://10.10.100.15/WSEvaluation/evaluation.asmx?wsdl');
$html = '';
$param = array('objStr' => $s1);
$resp1 = $client->RunSelectQuery($param);
$resp2 = $this->objToArray($resp1);
$result = $resp2['RunSelectQueryResult']['cols'];

Response::getInstance()->response = $result;
