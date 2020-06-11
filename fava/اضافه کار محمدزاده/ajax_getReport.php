<?php

$db = PDOAdapter::getInstance();
$acm = AccessControlManager::getInstance();
$rid = $acm->getRoleID();
$uid = $acm->getUserID();

$PDOParams = array();

$sql = "
        from  dm_datastoretable_61 dm
        INNER JOIN oa_document on(oa_document.RowID = dm.DocID and oa_document.IsEnable = 1)
        INNER JOIN dm_datastoretable_62 masterForm on(masterForm.DocID = dm.MasterID )
        INNER JOIN oa_document masterDoc on(masterDoc.RowID = masterForm.DocID and masterDoc.IsEnable = 1)
        INNER JOIN wf_execution on(wf_execution.execution_doc_id = masterDoc.RowID AND wf_execution.is_enable = 1)
        INNER JOIN oa_depts_roles on(oa_depts_roles.RowID = masterForm.Field_6)
        ";
$sqlWhere .= " where masterForm.Field_8 > 0 ";


$deptID = Request::getInstance()->varCleanFromInput('deptID');
if($deptID && intval($deptID) != 2){
$sqlWhere .= " and masterForm.Field_6 = :deptID";
$PDOParams[] = array('name' => 'deptID', 'value' => $deptID, 'type' => PDO::PARAM_INT);
}

$year = Request::getInstance()->varCleanFromInput('year');
if($year && intval($year) != 2){
    $sqlWhere .= " and masterForm.Field_5 = :year";
    $PDOParams[] = array('name' => 'year', 'value' => $year, 'type' => PDO::PARAM_INT);
}

$month = Request::getInstance()->varCleanFromInput('month');
if($month && intval($month) != 2){
    $sqlWhere .= " and masterForm.Field_4 = :month";
    $PDOParams[] = array('name' => 'month', 'value' => $month, 'type' => PDO::PARAM_INT);
}

$pageNumber = Request::getInstance()->varCleanFromInput('pageNumber');
$pageNumber = intval($pageNumber);
if($pageNumber == 0)
    $pageNumber = 1;
$rowsStart = ($pageNumber-1)*50;


$body = '';
$i = 0;

/**
 * send date for create excel output
 */
$CSV = intval(Request::getInstance()->varCleanFromInput('CSV'));
if($CSV){
    $sqlList = "SELECT 
          dm.*,
          oa_depts_roles.Name as deptName,
          masterForm.Field_5 as year,
          masterForm.Field_4 as month
          $sql $sqlWhere 
          order by dm.MasterID ";
    $db->executeSelect($sqlList, $PDOParams);
    $dataRows = array();
    while ($row = $db->fetchAssoc()){
        $dataRows[] = array(
            'deptName' => $row['deptName'],
            'year' => $row['year'],
            'month' => $row['month'],
            'Field_0' => $row['Field_0'],
            'Field_18' => $row['Field_18'],
            'Field_19' => $row['Field_19'],
            'Field_2' => $row['Field_2'],
            'Field_4' => $row['Field_4'],
            'Field_5' => $row['Field_5'],
            'Field_6' => $row['Field_6'],
            'Field_7' => $row['Field_7'],
            'Field_8' => $row['Field_8'],
            'Field_9' => $row['Field_9'],
            'Field_16' => $row['Field_16'],
            'Field_17' => $row['Field_17'],
            'Field_10' => $row['Field_10'],
            'Field_11' => $row['Field_11'],
            'Field_12' => $row['Field_12'],
            'Field_13' => $row['Field_13'],
            'Field_14' => $row['Field_14'],
            'Field_15' => $row['Field_15']
        );
    }

    $title = array();
    $content = array();
    $title[] = "حوزه";
    $title[] = "سال";
    $title[] = "ماه";
    $title[] = "شماره پرسنلي";
    $title[] = "نام كاربر";
    $title[] = "سمت";
    $title[] = "رده";
    $title[] = "اضافه كار ماه قبل";
    $title[] = "كاركرد موثر";
    $title[] = "كاركرد ساعتي";
    $title[] = "اضافه كار فيزيكي";
    $title[] = "اضافه كار فيزيكي به عدد";
    $title[] = "اضافه كار تشويقي مدير";
    $title[] = "جمع اضافه كار فيزيكي و تشويقي مديريت";
    $title[] = "اضافه كار تشويقي مديرعامل";
    $title[] = "اضافه كار نهايي";
    $title[] = "سرانه";
    $title[] = "انحراف سرانه";
    $title[] = "ماموريت";
    $title[] = "ناهار ماموريت";
    $title[] = "اضافه كار ماموريت";

    //++++++++++++++++++++++++++
    $content[] = "deptName";
    $content[] = "year";
    $content[] = "month";
    $content[] = "Field_0";
    $content[] = "Field_18";
    $content[] = "Field_19";
    $content[] = "Field_2";
    $content[] = "Field_4";
    $content[] = "Field_5";
    $content[] = "Field_6";
    $content[] = "Field_7";
    $content[] = "Field_8";
    $content[] = "Field_9";
    $content[] = "Field_16";
    $content[] = "Field_17";
    $content[] = "Field_10";
    $content[] = "Field_11";
    $content[] = "Field_12";
    $content[] = "Field_13";
    $content[] = "Field_14";
    $content[] = "Field_15";

    ModReport::createOutCSV($title, $content, $dataRows);
    exit();
}
/**
 * end send date for create excel output
 */
$sqlList = "SELECT 
          dm.*,
          masterForm.Field_6 as deptID,
          oa_depts_roles.Name as deptName,
          masterForm.Field_5 as year,
          masterForm.Field_4 as month
          $sql $sqlWhere 
          order by dm.MasterID 
           limit $rowsStart,50";
$db->executeSelect($sqlList, $PDOParams);

/**
 * create list table body
 */
$mainSums = array('deptName' => 'جمع كل');
$deptSums = array();
$rowIndex = $rowsStart;
while ($row = $db->fetchAssoc()){
    for ($i=4; $i< 18; $i++){
        if(!isset($row['Field_'.$i]))
            continue;
        $fieldName = 'Field_'.$i;

        if(!isset($mainSums[$fieldName]))
            $mainSums[$fieldName] = 0;
        $mainSums[$fieldName] += floatval($row[$fieldName]);

        $deptID = $row['deptID'];
        if(!isset($deptSums[$deptID]))
            $deptSums[$deptID] = ['deptName' => $row['deptName']];

        if(!isset($deptSums[$deptID][$fieldName]))
            $deptSums[$deptID][$fieldName] = 0;
        $deptSums[$deptID][$fieldName] += floatval($row[$fieldName]);

    }
    $body .= '
    <tr id="accessRow_'.(++$i).'" >
        <td style="padding: 2px;border: 1px solid #ccc;" >'.$rowIndex++.'</td>
        <td style="padding: 2px;border: 1px solid #ccc;" >'.$row['Field_0'].'</td>
        <td style="padding: 2px;border: 1px solid #ccc;" >'.$row['Field_18'].'</td>
        <td style="padding: 2px;border: 1px solid #ccc;" >'.$row['Field_19'].'</td>
        <td style="padding: 2px;border: 1px solid #ccc;" >'.$row['Field_2'].'</td>
        <td style="padding: 2px;border: 1px solid #ccc;" >'.$row['Field_4'].'</td>
        <td style="padding: 2px;border: 1px solid #ccc;" >'.$row['Field_5'].'</td>
        <td style="padding: 2px;border: 1px solid #ccc;" >'.$row['Field_6'].'</td>
        <td style="padding: 2px;border: 1px solid #ccc;" >'.$row['Field_7'].'</td>
        <td style="padding: 2px;border: 1px solid #ccc;" >'.$row['Field_8'].'</td>
        <td style="padding: 2px;border: 1px solid #ccc;" >'.$row['Field_9'].'</td>
        <td style="padding: 2px;border: 1px solid #ccc;" >'.$row['Field_16'].'</td>
        <td style="padding: 2px;border: 1px solid #ccc;" >'.$row['Field_17'].'</td>
        <td style="padding: 2px;border: 1px solid #ccc;" >'.$row['Field_10'].'</td>
        <td style="padding: 2px;border: 1px solid #ccc;" >'.$row['Field_11'].'</td>
        <td style="padding: 2px;border: 1px solid #ccc;" >'.$row['Field_12'].'</td>
        <td style="padding: 2px;border: 1px solid #ccc;" >'.$row['Field_13'].'</td>
        <td style="padding: 2px;border: 1px solid #ccc;" >'.$row['Field_14'].'</td>
        <td style="padding: 2px;border: 1px solid #ccc;" >'.$row['Field_15'].'</td>
    </tr>
    ';
}

/**
 * create sums table body
 */
$deptSums[] = $mainSums;
$deptSumsBody = '';
foreach ($deptSums as $item){
    $deptSumsBody .= '
    <tr >
        <td style="padding: 2px;border: 1px solid #ccc;" >'.$item['deptName'].'</td>
        <td style="padding: 2px;border: 1px solid #ccc;" >'.$item['Field_4'].'</td>
        <td style="padding: 2px;border: 1px solid #ccc;" >'.$item['Field_5'].'</td>
        <td style="padding: 2px;border: 1px solid #ccc;" >'.$item['Field_6'].'</td>
        <td style="padding: 2px;border: 1px solid #ccc;" >'.$item['Field_7'].'</td>
        <td style="padding: 2px;border: 1px solid #ccc;" >'.$item['Field_8'].'</td>
        <td style="padding: 2px;border: 1px solid #ccc;" >'.$item['Field_9'].'</td>
        <td style="padding: 2px;border: 1px solid #ccc;" >'.$item['Field_16'].'</td>
        <td style="padding: 2px;border: 1px solid #ccc;" >'.$item['Field_17'].'</td>
        <td style="padding: 2px;border: 1px solid #ccc;" >'.$item['Field_10'].'</td>
        <td style="padding: 2px;border: 1px solid #ccc;" >'.$item['Field_11'].'</td>
        <td style="padding: 2px;border: 1px solid #ccc;" >'.$item['Field_12'].'</td>
        <td style="padding: 2px;border: 1px solid #ccc;" >'.$item['Field_13'].'</td>
        <td style="padding: 2px;border: 1px solid #ccc;" >'.$item['Field_14'].'</td>
        <td style="padding: 2px;border: 1px solid #ccc;" >'.$item['Field_15'].'</td>
    </tr>
    ';
}

$sqlForSum = "SELECT 
          count(dm.RowID)
          $sql $sqlWhere";
$sumCount = $db->executeScalar($sqlForSum, $PDOParams);
$pages = ceil($sumCount/50);
$html = '
        <div style="text-align: center;padding: 2px;">
        <span style="float: right;padding: 5px;font-weight: bold;">'.$sumCount.' مورد يافت شد</span>
            <button onclick="FormOnly.allFieldsContianer[0].prevPage()" id="prevPage">صفحه قبل </button>
            صفحه
            <input id="pageNumber" class="f-input" tabindex="6001" style="width: 50px;" value="'.$pageNumber.'">
            از '.$pages.'
            <input type="hidden" value="'.$pages.'" id="maxPage" >
            <button onclick="FormOnly.allFieldsContianer[0].getReport()" id="showPage" style="background-color:#b7f7ab">نمايش صفحه وارد  شده  </button>     
            <button tabindex="6001" onclick="FormOnly.allFieldsContianer[0].nextPage()" onkeydown="return FormBuilder.designFormModal.setLFocus(event)" id="textPage">صفحه بعد </button> </td></div>
        <table width="100%" class="f-table accessTable" cellpadding="0" cellspacing="1">     
            <tbody>
                <tr>
                    <th style="padding: 2px; ">رديف</th>
                    <th style="padding: 2px; ">شماره پرسنلي</th>
                    <th style="padding: 2px; ">نام كاربر</th>
                    <th style="padding: 2px; ">سمت</th>
                    <th style="padding: 2px; ">رده</th>
                    <th style="padding: 2px; ">اضافه كار ماه قبل</th>
                    <th style="padding: 2px; ">كاركرد موثر</th>
                    <th style="padding: 2px; ">كاركرد ساعتي</th>
                    <th style="padding: 2px; ">اضافه كار فيزيكي</th>
                    <th style="padding: 2px; ">اضافه كار فيزيكي به عدد</th>
                    <th style="padding: 2px; ">اضافه كار تشويقي مدير</th>
                    <th style="padding: 2px; ">جمع اضافه كار فيزيكي و تشويقي مديريت</th>
                    <th style="padding: 2px; ">اضافه كار تشويقي مديرعامل</th>
                    <th style="padding: 2px; ">اضافه كار نهايي</th>
                    <th style="padding: 2px; ">سرانه</th>
                    <th style="padding: 2px; ">انحراف سرانه</th>
                    <th style="padding: 2px; ">ماموريت</th>
                    <th style="padding: 2px; ">ناهار ماموريت</th>
                    <th style="padding: 2px; ">اضافه كار ماموريت</th>
                    
                </tr>
                '.$body.'
            </tbody>
        </table>
        <table width="100%" class="f-table accessTable" cellpadding="0" cellspacing="1">     
            <tbody>
                <tr>
                    <th style="padding: 2px; " colspan="15" style="text-align: center;">گزارش تجميعي به تفكيك حوزه</th>
                </tr>
                <tr>
                    <th style="padding: 2px; ">حوزه</th>
                    <th style="padding: 2px; ">اضافه كار ماه قبل</th>
                    <th style="padding: 2px; ">كاركرد موثر</th>
                    <th style="padding: 2px; ">كاركرد ساعتي</th>
                    <th style="padding: 2px; ">اضافه كار فيزيكي</th>
                    <th style="padding: 2px; ">اضافه كار فيزيكي به عدد</th>
                    <th style="padding: 2px; ">اضافه كار تشويقي مدير</th>
                    <th style="padding: 2px; ">جمع اضافه كار فيزيكي و تشويقي مديريت</th>
                    <th style="padding: 2px; ">اضافه كار تشويقي مديرعامل</th>
                    <th style="padding: 2px; ">اضافه كار نهايي</th>
                    <th style="padding: 2px; ">سرانه</th>
                    <th style="padding: 2px; ">انحراف سرانه</th>
                    <th style="padding: 2px; ">ماموريت</th>
                    <th style="padding: 2px; ">ناهار ماموريت</th>
                    <th style="padding: 2px; ">اضافه كار ماموريت</th>
                </tr>
                '.$deptSumsBody.'
            </tbody>
        </table>
        <div id="chartDataSql" style="display: none;">'.$sqlList.'</div>
        <div id="chartDataParams" style="display: none;">'.json_encode($PDOParams, true).'</div>';


Response::getInstance()->response = $html;

