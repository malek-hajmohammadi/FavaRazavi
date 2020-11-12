<?php


$db = PDOAdapter::getInstance();
$acm = AccessControlManager::getInstance();
$rid = $acm->getRoleID();
$uid = $acm->getUserID();

$PDOParams = array();

$sql = "
from  dm_datastoretable_1052 dm
INNER JOIN oa_document on(oa_document.RowID = dm.DocID and oa_document.IsEnable = 1)
INNER JOIN oa_doc_refer on(oa_doc_refer.DocID = oa_document.RowID AND oa_doc_refer.ToRoleID = 12104)
INNER JOIN wf_execution on(wf_execution.execution_doc_id = dm.DocID AND wf_execution.is_enable = 1)
INNER JOIN oa_users on(oa_users.UserID = oa_document.CreatorUserID)
INNER JOIN oa_depts_roles on(oa_depts_roles.RowID = oa_document.CreatorRoleID)
INNER JOIN oa_depts_roles dept on(dept.RowID = oa_depts_roles.DeptID)
";
$sqlWhere .= " where dm.Field_15 > 0 ";

$offerUser = Request::getInstance()->varCleanFromInput('offerUser');
if ($offerUser && strlen($offerUser) > 0) {
    $offerUser = explode(',', $offerUser);
    if (count($offerUser) == 2) {
        $sqlWhere .= " and oa_users.UserID = :uid and oa_depts_roles.RowID = :rid";
        $PDOParams[] = array('name' => 'uid', 'value' => $offerUser[0], 'type' => PDO::PARAM_INT);
        $PDOParams[] = array('name' => 'rid', 'value' => $offerUser[1], 'type' => PDO::PARAM_INT);
    }
}

$offerEID = Request::getInstance()->varCleanFromInput('offerEID');
if ($offerEID && strlen($offerEID) > 0) {
    $sqlWhere .= " and dm.Field_1 = :offerEID";
    $PDOParams[] = array('name' => 'offerEID', 'value' => $offerEID, 'type' => PDO::PARAM_INT);
}

$offerGender = Request::getInstance()->varCleanFromInput('offerGender');
if ($offerGender && intval($offerGender) != 2) {
    $sqlWhere .= " and dm.Field_5 = :offerGender";
    $PDOParams[] = array('name' => 'offerGender', 'value' => $offerGender, 'type' => PDO::PARAM_INT);
}

$offerWorkType = Request::getInstance()->varCleanFromInput('offerWorkType');
if ($offerWorkType && intval($offerWorkType) != 2) {
    $sqlWhere .= " and dm.Field_6 = :offerWorkType";
    $PDOParams[] = array('name' => 'offerWorkType', 'value' => $offerWorkType, 'type' => PDO::PARAM_INT);
}

$offerDept = Request::getInstance()->varCleanFromInput('offerDept');
if ($offerDept && intval($offerDept) != 2) {
    $sqlWhere .= " and dept.RowID = :offerDept";
    $PDOParams[] = array('name' => 'offerDept', 'value' => $offerDept, 'type' => PDO::PARAM_INT);
}

$offerNumber = Request::getInstance()->varCleanFromInput('offerNumber');
if ($offerNumber && strlen($offerNumber) > 0) {
    $sqlWhere .= " and dm.Field_7 = :offerNumber";
    $PDOParams[] = array('name' => 'offerNumber', 'value' => $offerNumber, 'type' => PDO::PARAM_INT);
}

$offerStatus = Request::getInstance()->varCleanFromInput('offerStatus');
if ($offerStatus && intval($offerStatus) > 0) {
    $sqlWhere .= " and dm.Field_15 = :offerStatus";
    $PDOParams[] = array('name' => 'offerStatus', 'value' => $offerStatus, 'type' => PDO::PARAM_INT);
}

$offerTitle = Request::getInstance()->varCleanFromInput('offerTitle');
if ($offerTitle && strlen($offerTitle) > 0) {
    $sqlWhere .= " and dm.Field_9 like :offerTitle";
    $PDOParams[] = array('name' => 'offerTitle', 'value' => "%$offerTitle%", 'type' => PDO::PARAM_STR);
}

$offerDesc = Request::getInstance()->varCleanFromInput('offerDesc');
if (strlen($offerDesc) > 0) {
    $sqlWhere .= " and dm.Field_10 like :offerDesc";
    $PDOParams[] = array('name' => 'offerDesc', 'value' => "%$offerDesc%", 'type' => PDO::PARAM_STR);
}

$offerMethodOfRun = Request::getInstance()->varCleanFromInput('offerMethodOfRun');
if ($offerMethodOfRun && strlen($offerMethodOfRun) > 0) {
    $sqlWhere .= " and dm.Field_11 like :offerMethodOfRun";
    $PDOParams[] = array('name' => 'offerMethodOfRun', 'value' => "%$offerMethodOfRun%", 'type' => PDO::PARAM_STR);
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

/* search in actions */
$runDateForecast = Request::getInstance()->varCleanFromInput('runDateForecast');
$offerActionDesc = Request::getInstance()->varCleanFromInput('offerActionDesc');
$offerActionResults = Request::getInstance()->varCleanFromInput('offerActionResults');
if (($runDateForecast && strlen($runDateForecast) > 0) || ($offerActionDesc && strlen($offerActionDesc) > 0) || ($offerActionResults && strlen($offerActionResults) > 0)) {
    $sql .= " INNER JOIN dm_datastoretable_1058 actions on(actions.MasterID = dm.DocID) ";

    if ($runDateForecast && strlen($runDateForecast) > 0) {
        $runDateForecast = Date::JalaliToGreg($runDateForecast);
        $sqlWhere .= " and actions.Field_1 = :runDateForecast";
        $PDOParams[] = array('name' => 'runDateForecast', 'value' => $runDateForecast, 'type' => PDO::PARAM_STR);
    }


    if ($offerActionDesc && strlen($offerActionDesc) > 0) {
        $sqlWhere .= " and actions.Field_2 = :offerActionDesc";
        $PDOParams[] = array('name' => 'offerActionDesc', 'value' => $offerActionDesc, 'type' => PDO::PARAM_STR);
    }


    if ($offerActionResults && strlen($offerActionResults) > 0) {
        $sqlWhere .= " and actions.Field_3 = :offerActionResults";
        $PDOParams[] = array('name' => 'offerActionResults', 'value' => $offerActionResults, 'type' => PDO::PARAM_STR);
    }

}

$pageNumber = Request::getInstance()->varCleanFromInput('pageNumber');
$pageNumber = intval($pageNumber);
if ($pageNumber == 0)
    $pageNumber = 1;
$rowsStart = ($pageNumber - 1) * 50;

$sqlList = "SELECT
dm.*,
oa_doc_refer.ReferDate,
concat(oa_users.fname,' ',oa_users.lname,'(',oa_depts_roles.Name,')') as creator $sql $sqlWhere
group by dm.RowID order by oa_doc_refer.ReferDate Desc,Field_15 Desc ";


$body = '';
$i = 0;
$statusArray = array(
    'پيشنويس',
    'در دست بررسي قائم مقام',
    'عدم تاييد قائم مقام',
    'تاييد قائم مقام',
    'دستور بررسي در نظام پيشنهادات',
    'در دست اقدام توسط مجري'
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

    $referDate = Date::GregToJalali($row['ReferDate']) . '<br>' . ((explode(' ', $row['ReferDate'])[1]));
    $status = $statusArray[intval($row['Field_15'])];
    $body .= '
<tr id="accessRow_' . (++$i) . '" >
    <td style="padding: 2px;border: 1px solid #ccc;" >' . $rowIndex++ . '</td>
    <td style="padding: 2px;border: 1px solid #ccc;" >' . $row['DocID'] . '</td>
    <td style="padding: 2px;border: 1px solid #ccc;" >' . $row['Field_7'] . '</td>
    <td style="padding: 2px;border: 1px solid #ccc;" >' . $row['creator'] . '</td>
    <td style="padding: 2px;border: 1px solid #ccc;"
        onmouseover="Tooltip.show(this, \'<b>عنوان پيشنهاد:</b> ' . filter_var($row['Field_9'], FILTER_SANITIZE_STRING) . '<hr><b>بيان مساله و آسيب هاي آن:</b> ' . $row['Field_10'] . '\',\'500px\')" onmouseout="Tooltip.hide()" >
        ' . filter_var(mb_substr($row['Field_9'], 0, 30), FILTER_SANITIZE_STRING) . ((mb_strlen($row['Field_9']) > 30) ? '...' : '') . '
    </td>
    <td style="padding: 2px;border: 1px solid #ccc;" >' . $referDate . '</td>
    <td style="padding: 2px;border: 1px solid #ccc;" >' . $status . '</td>
</tr>
';
}

$sqlForChart = "SELECT
IFNULL (dm.Field_15, 0) as Field_15,
count(distinct(dm.RowID)) as offerCount $sql $sqlWhere group by Field_15";
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
        <th width="5%" style="padding: 2px; ">شماره پيشنهاد</th>
        <th width="30%" style="padding: 2px; ">پيشنهاد دهنده</th>
        <th width="30%" style="padding: 2px; ">عنوان پيشنهاد</th>
        <th width="10%" style="padding: 2px; ">تاريخ ايجاد</th>
        <th width="17%" style="padding: 2px; ">وضعيت</th>
    </tr>
    ' . $body . '
    </tbody>
</table>
<div id="chartData" style="display: none;">' . $counts . '</div>
<div id="chartDataSql" style="display: none;">' . $sqlList . '</div>
<div id="chartDataParams" style="display: none;">' . json_encode($PDOParams, true) . '</div>';


Response::getInstance()->response = $html;