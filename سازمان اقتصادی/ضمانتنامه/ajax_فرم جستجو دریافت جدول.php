<?php


$db = PDOAdapter::getInstance();
$acm = AccessControlManager::getInstance();
$rid = $acm->getRoleID();
$uid = $acm->getUserID();

$PDOParams = array();

$sql = "
from  dm_datastoretable_50 dm
INNER JOIN oa_document on(oa_document.RowID = dm.DocID and oa_document.IsEnable = 1)
INNER JOIN oa_doc_refer on(oa_doc_refer.DocID = oa_document.RowID)
INNER JOIN wf_execution on(wf_execution.execution_doc_id = dm.DocID AND wf_execution.is_enable = 1)
";
$sqlWhere .= " where dm.Field_32 > 0 ";


$companyName = Request::getInstance()->varCleanFromInput('companyName');
if ($companyName && strlen($companyName) > 0) {
    $sqlWhere .= " and dm.Field_0 like :companyName";
    $PDOParams[] = array('name' => 'companyName', 'value' => "%$companyName%", 'type' => PDO::PARAM_INT);
}

$priceFrom = Request::getInstance()->varCleanFromInput('priceFrom');
if ($priceFrom && strlen($priceFrom) > 0) {
    $sqlWhere .= " and dm.Field_2 >= :priceFrom";
    $PDOParams[] = array('name' => 'priceFrom', 'value' => $priceFrom, 'type' => PDO::PARAM_INT);
}
$priceTo = Request::getInstance()->varCleanFromInput('priceTo');
if ($priceTo && strlen($priceTo) > 0) {
    $sqlWhere .= " and dm.Field_2 >= :priceTo";
    $PDOParams[] = array('name' => 'priceTo', 'value' => $priceTo, 'type' => PDO::PARAM_INT);
}

$checkNum1 = Request::getInstance()->varCleanFromInput('checkNum1');
if ($checkNum1 && strlen($checkNum1) > 0) {
    $sqlWhere .= " and dm.Field_16 = :checkNum1";
    $PDOParams[] = array('name' => 'checkNum1', 'value' => $checkNum1, 'type' => PDO::PARAM_INT);
}

$checkNum2 = Request::getInstance()->varCleanFromInput('checkNum2');
if ($checkNum2 && strlen($checkNum2) > 0) {
    $sqlWhere .= " and dm.Field_17 = :checkNum2";
    $PDOParams[] = array('name' => 'checkNum2', 'value' => $checkNum2, 'type' => PDO::PARAM_INT);
}

$checkDoc1 = Request::getInstance()->varCleanFromInput('checkDoc1');
if ($checkDoc1 && strlen($checkDoc1) > 0) {
    $sqlWhere .= " and dm.Field_18 = :checkDoc1";
    $PDOParams[] = array('name' => 'checkDoc1', 'value' => $checkDoc1, 'type' => PDO::PARAM_INT);
}

$checkDoc2 = Request::getInstance()->varCleanFromInput('checkDoc2');
if ($checkDoc2 && strlen($checkDoc2) > 0) {
    $sqlWhere .= " and dm.Field_19 = :checkDoc2";
    $PDOParams[] = array('name' => 'checkDoc2', 'value' => $checkDoc2, 'type' => PDO::PARAM_INT);
}

$loanType = Request::getInstance()->varCleanFromInput('loanType');
if ($loanType && intval($loanType) != 0) {
    $sqlWhere .= " and dm.Field_2 = :loanType";
    $PDOParams[] = array('name' => 'loanType', 'value' => $loanType, 'type' => PDO::PARAM_INT);
}

$bankID = Request::getInstance()->varCleanFromInput('bankID');
if ($bankID && intval($bankID) != 0) {
    $sqlWhere .= " and dm.Field_24 = :bankID";
    $PDOParams[] = array('name' => 'bankID', 'value' => $bankID, 'type' => PDO::PARAM_INT);
}

$acceptOne = Request::getInstance()->varCleanFromInput('acceptOne');
if ($acceptOne && intval($acceptOne) != 0) {
    $sqlWhere .= " and dm.Field_13 = :acceptOne";
    $PDOParams[] = array('name' => 'acceptOne', 'value' => $acceptOne, 'type' => PDO::PARAM_INT);
}

$acceptTwo = Request::getInstance()->varCleanFromInput('acceptTwo');
if ($acceptTwo && intval($acceptTwo) != 0) {
    $sqlWhere .= " and dm.Field_23 = :acceptTwo";
    $PDOParams[] = array('name' => 'acceptTwo', 'value' => $acceptTwo, 'type' => PDO::PARAM_INT);
}

$requestStatus = Request::getInstance()->varCleanFromInput('requestStatus');
if ($requestStatus && intval($requestStatus) > 0) {
    $sqlWhere .= " and dm.Field_32 = :requestStatus";
    $PDOParams[] = array('name' => 'requestStatus', 'value' => $requestStatus, 'type' => PDO::PARAM_INT);
}

$requestDesc = Request::getInstance()->varCleanFromInput('requestDesc');
if (strlen($requestDesc) > 0) {
    $sqlWhere .= " and dm.Field_5 like :requestDesc";
    $PDOParams[] = array('name' => 'requestDesc', 'value' => "%$requestDesc%", 'type' => PDO::PARAM_STR);
}

$descOne = Request::getInstance()->varCleanFromInput('descOne');
if (strlen($descOne) > 0) {
    $sqlWhere .= " and dm.Field_12 like :descOne";
    $PDOParams[] = array('name' => 'descOne', 'value' => "%$descOne%", 'type' => PDO::PARAM_STR);
}

$descTwo = Request::getInstance()->varCleanFromInput('descTwo');
if (strlen($descTwo) > 0) {
    $sqlWhere .= " and dm.Field_12 like :descTwo";
    $PDOParams[] = array('name' => 'descTwo', 'value' => "%$descTwo%", 'type' => PDO::PARAM_STR);
}

$referDateFrom = Request::getInstance()->varCleanFromInput('createDateFrom');
if ($referDateFrom && strlen($referDateFrom) > 0) {
    $referDateFrom = Date::JalaliToGreg($referDateFrom);
    $sqlWhere .= " and oa_doc_refer.ReferDate >= :referDateFrom";
    $PDOParams[] = array('name' => 'referDateFrom', 'value' => "$referDateFrom 00:00:00", 'type' => PDO::PARAM_STR);
}

$referDateTo = Request::getInstance()->varCleanFromInput('createDateTo');
if ($referDateTo && strlen($referDateTo) > 0) {
    $referDateTo = Date::JalaliToGreg($referDateTo);
    $sqlWhere .= " and oa_doc_refer.ReferDate <= :referDateTo";
    $PDOParams[] = array('name' => 'referDateTo', 'value' => "$referDateTo  23:59:59", 'type' => PDO::PARAM_STR);
}

$pageNumber = Request::getInstance()->varCleanFromInput('pageNumber');
$pageNumber = intval($pageNumber);
if ($pageNumber == 0)
    $pageNumber = 1;
$rowsStart = ($pageNumber - 1) * 50;

$sqlList = "SELECT
dm.*,
oa_doc_refer.ReferDate
$sql $sqlWhere
group by dm.RowID order by oa_doc_refer.ReferDate Desc,Field_32 Desc ";


$body = '';
$i = 0;
$statusArray = array(
    'پيشنويس',
    'تاييد رئيس حسابداري',
    'تاييد چك ضمانت',
    'تاييد چك كارمزد',
    'تاييد نهايي رئيس حسابداري',
    'تاييد مدير مالي',
    'تاييد مدير حسابرسي و مجامع',
    'تاييد معاون شركت ها',
    'درحال صدور ضمانتنامه',
    'تاييد مديرعامل',
    'تاييد شده'
);

/**
 * send date for create excel output
 */
$CSV = intval(Request::getInstance()->varCleanFromInput('CSV'));
if ($CSV) {
    $db->executeSelect($sqlList, $PDOParams);
    $dataRows = array();
    while ($row = $db->fetchAssoc()) {
        $offerWorkType = 'پيماني';
        if (intval($row['Field_5']) == 1)
            $offerWorkType = 'رسمي';
        if (intval($row['Field_5']) == 2)
            $offerWorkType = 'خريد خدمت';
        if (intval($row['Field_5']) == 3)
            $offerWorkType = 'قراردادي';
        $referDate = Date::GregToJalali($row['ReferDate']) . ' ' . ((explode(' ', $row['ReferDate'])[1]));
        $dataRows[] = array(
            'DocID' => $row['DocID'],
            'creator' => $row['creator'],
            'referDate' => $referDate,
            'employeeID' => $row['Field_1'],
            'moavenat' => $row['Field_2'],
            'modiriat' => $row['Field_3'],
            'edareh' => $row['Field_4'],
            'gender' => (intval($row['Field_5']) == 0) ? 'مرد' : 'زن',
            'workType' => $offerWorkType,
            'offerNumber' => $row['Field_7'],
            'mobile' => $row['Field_8'],
            'offerTitle' => $row['Field_9'],
            'offerDesc' => $row['Field_10'],
            'offerMethodOfRun' => $row['Field_11'],
            'mainOffersNumber' => $row['Field_13'],
            'requestStatus' => $statusArray[intval($row['Field_15'])],
            'deputyDesc' => $row['Field_16'],
            'mainOfferID' => $row['Field_13'],
        );
    }

    $title = array();
    $content = array();
    $title[] = " شماره فرم ";
    $title[] = " پيشنهاد دهنده ";
    $title[] = " تاريخ ايجاد ";
    $title[] = " شماره پرسنلي ";
    $title[] = " معاونت پيشنهاد دهنده ";
    $title[] = " مديريت پيشنهاد دهنده ";
    $title[] = " اداره پيشنهاد دهنده ";
    $title[] = " جنسيت ";
    $title[] = " نوع خدمت ";
    $title[] = " شماره پيشنهاد ";
    $title[] = " شماره همراه ";
    $title[] = " عنوان پيشنهاد ";
    $title[] = " بيان مساله و آسيبهاي فعلي ";
    $title[] = " شرح و نحوه اجرا (ارائه راهكار) ";
    $title[] = " وضعيت درخواست ";
    $title[] = " نظر فائم مقام ";
    $title[] = " شماره فرم نظام پيشنهادها ";

//++++++++++++++++++++++++++
    $content[] = "DocID";
    $content[] = "creator";
    $content[] = "referDate";
    $content[] = "employeeID";
    $content[] = "moavenat";
    $content[] = "modiriat";
    $content[] = "edareh";
    $content[] = "gender";
    $content[] = "workType";
    $content[] = "offerNumber";
    $content[] = "mobile";
    $content[] = "offerTitle";
    $content[] = "offerDesc";
    $content[] = "offerMethodOfRun";
    $content[] = "mainOffersNumber";
    $content[] = "requestStatus";
    $content[] = "deputyDesc";
    $content[] = "mainOfferID";

    ModReport::createOutCSV($title, $content, $dataRows);
    exit();
}
/**
 * end send date for create excel output
 */

$sqlList .= " limit $rowsStart,50";
$db->executeSelect($sqlList, $PDOParams);
$rowIndex = $rowsStart;
while ($row = $db->fetchAssoc()) {

    $bankName="";
    switch ($row['Field_24']){
        case 1: $bankName="صادرات";
            break;
        case 2:$bankName= "ملت";
            break;
        case 3:$bankName="تجارت";
            break;
    }



    $referDate = Date::GregToJalali($row['ReferDate']) . '<br>' . ((explode(' ', $row['ReferDate'])[1]));
    $status = $statusArray[intval($row['Field_32'])];
    $price = strrev($row['Field_2']);
    $price = str_split($price, 3);
    $price = implode(',', $price);
    $row['Field_2'] = strrev($price);
    $body .= '
<tr id="accessRow_' . (++$i) . '" >
    <td style="padding: 2px;border: 1px solid #ccc;" >' . $rowIndex++ . '</td>
    <td style="padding: 2px;border: 1px solid #ccc;" >' . $row['DocID'] . '</td>
    <td style="padding: 2px;border: 1px solid #ccc;" onmouseover="Tooltip.show(this, \'<b>شرح درخواست:</b> ' . filter_var($row['Field_5'], FILTER_SANITIZE_STRING) . '\',\'500px\')" onmouseout="Tooltip.hide()">' . $row['Field_0'] . '</td>
    <td style="padding: 2px;border: 1px solid #ccc;" >' . $row['Field_2'] . '</td>
    <td style="padding: 2px;border: 1px solid #ccc;" >' . $bankName . '</td>
    <td style="padding: 2px;border: 1px solid #ccc;" >' . $referDate . '</td>
    <td style="padding: 2px;border: 1px solid #ccc;" >' . $status . '</td>
</tr>
';
}

$sqlForChart = "SELECT
IFNULL (dm.Field_32, 0) as Field_32,
count(distinct(dm.RowID)) as offerCount $sql $sqlWhere group by Field_32";
$db->executeSelect($sqlForChart, $PDOParams);
$counts = array();
$sumCount = 0;
while ($row = $db->fetchAssoc()) {
    $sumCount += $row['offerCount'];
    $counts[] = $row;
}
$pages = ceil($sumCount / 50);
$counts = json_encode($counts);
$html = '
<div style="text-align: center;padding: 2px;">
    <span style="float: right;padding: 5px;font-weight: bold;">' . $sumCount . ' مورد يافت شد</span>
    <button onclick="FormOnly.allFieldsContianer[0].prevPage()" id="prevPage">صفحه قبل </button>
    صفحه
    <input id="pageNumber" class="f-input" tabindex="6001" style="width: 50px;" value="' . $pageNumber . '">
    از ' . $pages . '
    <input type="hidden" value="' . $pages . '" id="maxPage" >
    <button onclick="FormOnly.allFieldsContianer[0].getReport()" id="showPage" style="background-color:#b7f7ab">نمايش صفحه وارد  شده  </button>
    <button tabindex="6001" onclick="FormOnly.allFieldsContianer[0].nextPage()" onkeydown="return FormBuilder.designFormModal.setLFocus(event)" id="textPage">صفحه بعد </button> </td></div>
<table width="100%" class="f-table accessTable" cellpadding="0" cellspacing="1">
    <tbody>
    <tr>
        <th width="3%" style="padding: 2px; ">رديف</th>
        <th width="5%" style="padding: 2px; ">شماره فرم</th>
        <th width="35%" style="padding: 2px; ">نام شركت</th>
        <th width="10%" style="padding: 2px; ">مبلغ تسهيلات</th>
        <th width="20%" style="padding: 2px; ">بانك</th>
        <th width="10%" style="padding: 2px; ">تاريخ درخواست</th>
        <th width="17%" style="padding: 2px; ">وضعيت</th>
    </tr>
    ' . $body . '
    </tbody>
</table>
<div id="chartData" style="display: none;">' . $counts . '</div>
<div id="chartDataSql" style="display: none;">' . $sqlList . '</div>
<div id="chartDataParams" style="display: none;">' . json_encode($PDOParams, true) . '</div>';


Response::getInstance()->response = $html;

