<?php
//////////////get data from the table/////////////////
// the detailed table of information is 1101
//main table is 1100
//we need docId
//////////////////////////////
$docId=1;
if (Request::getInstance()->varCleanFromInput('docId') && Request::getInstance()->varCleanFromInput('mode')) {
    $docId = Request::getInstance()->varCleanFromInput('docId');
    $mode=Request::getInstance()->varCleanFromInput('mode'); //edit or readOnly
    if($mode!='edit' and $mode!='readOnly')
    {
      
        $message = RavanResult::raiseError(0, "مد کاری جدول مشخص نیست", "فراخوانی آیجکس");
        Response::getInstance()->response = $message;
        return;  
    }
      
    
   
} else {

    $message = RavanResult::raiseError(0, "لطفا پارامترهای آیجکس رو مقدار دهی کنید", "فراخوانی آیجکس");
    Response::getInstance()->response = $message;
    return;
}


$dataInTable = array();

$db = MySQLAdapter::getInstance();



$sql = "
select *
FROM dm_datastoretable_1101 
where MasterID=$docId ORDER BY RowID";
$db->executeSelect($sql);

//Response::getInstance()->response=$sql;
//return;
$count = 0;
while ($row = $db->fetchAssoc()) {
    $dataInTable[$count] = array(
        $row['Field_0'], $row['Field_1'], $row['Field_2'], $row['Field_3'],
        $row['Field_4'], $row['Field_5']);
    $count++;
}


/*

$dataInTable = array(
    array("تلویزیون", "1212312", 6, 1, "120000", "نیاز به تعمیر دارد"),
    array("تلویزیون", "1212312", 6, 1, "120000", "نیاز به تعمیر دارد"),
    array("تلویزیون", "1212312", 6, 1, "120000", "نیاز به تعمیر دارد"),
    array("تلویزیون", "1212312", 6, 1, "120000", "نیاز به تعمیر دارد"),
    array("تلویزیون", "1212312", 6, 1, "120000", "نیاز به تعمیر دارد"),


);
*/


/////////////////////////////////
$style = "<style>


    .f-box td {
        padding: 2px;
        font: normal 12pt/normal 'B Nazanin' !important;
        text-overflow: clip;
    }

    .f-header-title {
        text-align: center;
        vertical-align: middle;
        font: normal 25pt/normal 'B Titr';
        cursor: help;
        color: rgba(0, 0, 0, .8);
        text-shadow: 0 1px 0 rgb(204, 204, 204), 0 2px 0 rgb(201, 201, 201), 0 3px 0 rgb(187, 187, 187), 0 4px 0 rgb(185, 185, 185), 0 5px 0 rgb(170, 170, 170), 0 6px 1px rgba(0, 0, 0, 0.0980392), 0 0 5px rgba(0, 0, 0, 0.0980392), 0 1px 3px rgba(0, 0, 0, 0.298039), 0 3px 5px rgba(0, 0, 0, 0.2), 0 5px 10px rgba(0, 0, 0, 0.247059), 0 10px 10px rgba(0, 0, 0, 0.2), 0 20px 20px rgba(0, 0, 0, 0.14902);
    }

    .f-box fieldset {
        border: 1px solid rgb(16, 118, 165);
        border-radius: 5px !important;
        margin: 7px 0;
        text-align: right;
    }

    .f-box legend {
        font: normal 900 12pt/normal 'B Nazanin' !important;
        border: 1px solid rgb(16, 118, 165);
        border-radius: 5px;
        padding: 0 5px;
        background-color: rgba(16, 118, 165, .1);
        color: rgb(16, 118, 165);
    }

    .f-box-title {
        padding-left: 5px !important;
        text-align: left;
    }

    .f-input, input[id^=MosFormFields], input[id^=mosFieldTxt], .f-inputdate input, .f-inputtime input {
        border: 1px solid #888;
        box-shadow: 0 0px 5px #ddd inset;
        border-radius: 3px;
        font: normal 12pt/normal 'B Nazanin';
        padding: 2px 5px 0 0;
        transition: all .3s ease-in-out;
        outline: none;
        cursor: pointer;
        width: 100%;
        height: 30px;
    }

    input[id^=MosFormFields] {
        width: 50px;
        text-align: right !important;
        padding: 0;
    }

    input[id^=mosFieldTxt] {
        padding: 0 2px 0 0;
        text-align: right !important;
    }

    textarea.f-input {
        height: 80px;
    }

    .f-inputdate input, .f-inputtime input {
        text-align: center !important;
        padding: 0;
    }

    .f-inputtime input {
        width: 24px !important;
        height: 24px !important;
    }

    .f-inputtime td {
        padding: 0 !important;
    }

    .f-box button {
        border: 1px solid #aaa;
        box-shadow: 1px 1px 1px rgba(0, 0, 0, .1);
        background-color: rgba(255, 255, 255, .8);
        cursor: pointer;
        padding: 1px 15px;
        border-radius: 5px;
        font: normal 12pt/normal 'B Nazanin' !important;
        transition: all .2s ease-in-out;
        color: rgba(0, 0, 0, .8);
        text-decoration: none;
    }

    .f-box button:hover {
        background-color: rgba(16, 118, 165, .8);
        color: rgba(255, 255, 255, 1);
    }

    td[moslabelstype=\"sign\"] div {
        z-index: 10 !important;
        margin: 0 60px;
    }

    table.f-table {
        width: 100%;
    }

    table.f-table tr {
        background-color: #f8f8f8;
        transition: background-color .15s ease-in-out;
    }

    table.f-table tr:hover {
        background-color: #f3f3f3;
    }

    table.f-table tr td, table.f-table tr th, table.f-table div, table.f-table span {
        font: normal 12pt/normal 'B Nazanin';
        text-align: center;
        vertical-align: middle;
        border-radius: 5px;
        min-width: 50px;
    }

    table.f-table tr td {
        position: relative;
    }

    table.f-table span {
        border: 0;
    }

    /*table.f-table span{     font-weight: bold; } */
    table.f-table tr th {
        background-color: rgb(16, 118, 165);
        color: #fff;
        cursor: default;
    }

    #dvQuery { /*margin-top: 15px;*/
        font: normal 12pt/normal 'B Nazanin';
        padding: 5px;
        text-align: center;
        font-weight: bold;
    }

    #dvQueryTrue {
        font: normal 12pt/normal 'B Nazanin';
        background: linear-gradient(to right, rgba(0, 0, 0, 0) 0%, rgb(139, 255, 106) 51%, rgba(0, 0, 0, 0) 100%);
        padding: 5px;
        text-align: center;
        font-weight: bold;
    }

    #dvQueryFalse {
        font: normal 12pt/normal 'B Nazanin';
        background: linear-gradient(to right, rgba(0, 0, 0, 0) 0%, rgb(255, 144, 144) 51%, rgba(0, 0, 0, 0) 100%);
        padding: 5px;
        text-align: center;
        font-weight: bold;
    }

    .f-button-0, .f-button-1, .f-button-2 {
        border: 0;
        cursor: pointer;
        padding: 2px 10px;
        border-radius: 5px;
        font: normal 12pt/normal 'B Nazanin';
        text-decoration: none;
        transition: all .2s ease-in-out;
        color: #fff !important;
    }

    .f-button-1 {
        background: #6610f2;
    }

    .f-button-1:hover {
        background: #430c9c;
    }

    .f-button-1:disabled {
        background: #c5c5c5;
    }

    .f-button-2 {
        background: #28a745;
    }

    .f-button-2:hover {
        background: #1a682c;
    }

    .f-table-Adi {
        background-color: rgba(255, 255, 255, 0.7) !important;
        color: #000 !important;
        width: 60px;
    }

    .f-table-Mor {
        background-color: #28a745 !important;
        color: #fff !important;
        width: 60px;
    }

    .f-table-Mam {
        background-color: #0057e7 !important;
        color: #fff !important;
        width: 60px;
    }

    .f-table-MorR {
        background-color: #ffc107 !important;
        color: #fff !important;
        width: 60px;
    }

    .f-table-Tat {
        background-color: #dc3545 !important;
        color: #fff !important;
        width: 60px;
    }

    .f-table-Adi:hover, .f-table-Mor:hover, .f-table-Mam:hover {
        cursor: pointer;
        border: 1px rgba(0, 0, 0, .3) solid;
    }

    .f-tooltip {
        position: relative;
    }

    .f-tooltip .f-tooltiptext, .f-tooltip .f-tooltiptext-2 {
        visibility: hidden;
        width: 100px;
        background-color: black;
        color: #fff;
        font: normal 12pt/normal 'B Nazanin';
        text-align: center;
        border-radius: 6px;
        padding: 5px 0;
        position: absolute;
        z-index: 1;
        top: -30px;
        left: 65%;
        margin-left: -60px;
    }

    .f-tooltip .f-tooltiptext-2 {
        width: 150px;
        left: -75%;
        margin-left: 0;
    }

    .f-tooltip .f-tooltiptext::after, .f-tooltip .f-tooltiptext-2::after {
        content: \"\";
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: black transparent transparent transparent;
    }

    .f-tooltip:hover > .f-tooltiptext, .f-tooltip:hover > .f-tooltiptext-2 {
        visibility: visible;
    }

    div#tat_table td {
        font: inherit !important;
    }

    .f-modal-modalDialog {
        position: absolute;
        font: normal 12pt/normal 'B Nazanin';
        top: 0; /*right: 0;*/
        bottom: 0;
        left: 0; /*background: rgba(0, 0, 0, 0.3);*/
        z-index: 99999;
        opacity: 0;
        transition: all .2s ease-in-out;
        pointer-events: none;
        border: 0 !important;
    }

    .f-modal-modalDialog:target {
        opacity: 1;
        pointer-events: auto;
    }

    .f-modal-modalDialog > div {
        width: 650px;
        position: relative;
        border-radius: 10px;
        background: rgba(255, 255, 255, 1);
        box-shadow: 0px 0px 50px rgba(0, 0, 0, .5);
        color: #000;
    }

    .f-modal-close {
        background: rgb(255, 0, 0);
        color: #FFFFFF;
        line-height: 25px;
        position: absolute;
        left: -12px;
        text-align: center;
        top: -10px;
        width: 24px;
        text-decoration: none;
        font-weight: bold;
        border-radius: 12px;
        box-shadow: 1px 1px 3px #000;
        transition: all .1s ease-in-out;
    }

    .f-modal-close:hover {
        background: rgb(123, 8, 8);
    }

    .f-modal-modalDialog-2 {
        position: fixed;
        font: normal 12pt/normal 'B Nazanin';
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        background: rgba(0, 0, 0, 0.3);
        z-index: 99999;
        opacity: 0;
        transition: all .2s ease-in-out;
        pointer-events: none;
    }

    .f-modal-modalDialog-2:target {
        opacity: 1;
        pointer-events: auto;
    }

    .f-modal-modalDialog-2 > div {
        width: 400px;
        position: relative;
        margin: 10% auto;
        padding: 5px 20px 13px 20px;
        border-radius: 10px;
        background: #FFD3A5;
        background-image: linear-gradient(135deg, #f6fbfd 10%, #e6f6fd 100%);
        box-shadow: 0px 0px 50px rgba(0, 0, 0, .5)
    }

    .f-modal-close-2 {
        background: rgb(255, 0, 0);
        color: #FFFFFF;
        line-height: 25px;
        position: absolute;
        right: -12px;
        text-align: center;
        top: -10px;
        width: 24px;
        text-decoration: none;
        font-weight: bold;
        border-radius: 12px;
        box-shadow: 1px 1px 3px #000;
        transition: all .1s ease-in-out;
    }

    .f-modal-close-2:hover {
        background: rgb(123, 8, 8);
    }

    :root {
        --blue: #007bff;
        --indigo: #6610f2;
        --purple: #6f42c1;
        --pink: #e83e8c;
        --red: #dc3545;
        --orange: #fd7e14;
        --yellow: #ffc107;
        --green: #28a745;
        --teal: #20c997;
        --cyan: #17a2b8;
        --white: #fff;
        --gray: #6c757d;
        --gray-dark: #343a40;
        --primary: #007bff;
        --secondary: #6c757d;
        --success: #28a745;
        --info: #17a2b8;
        --warning: #ffc107;
        --danger: #dc3545;
        --light: #f8f9fa;
        --dark: #343a40;
    }

    .f-bgcolor-teal {
        background-color: #20c997 !important;
    }

    .f-bgcolor-white {
        background-color: #ffffff !important;
    }
    table.f-table tr:hover td {
        background-color: #bbdefb
    }
    </style>";
$defineTableTag = "<table width=\"100%\" class=\"f-table goodsTable\" cellpadding=\"0\" cellspacing=\"1\" dir=\"rtl\">
    <tbody>";


if($mode=='edit')
  $alternative="<th width=\"3%\" style=\"padding: 2px; \">حذف</th>";
else
  $alternative="";
$header = "<tr>
        <th width=\"3%\" style=\"padding: 2px; \">ردیف</th>
        <th width=\"25%\" style=\"padding: 2px; \">شرح کالا</th>
        <th width=\"10%\" style=\"padding: 2px; \">شماره اموال</th>
        <th width=\"3%\" style=\"padding: 2px; \">تعداد</th>
        <th width=\"5%\" style=\"padding: 2px; \">وضعیت کالا</th>
        <th width=\"12%\" style=\"padding: 2px; \">ارزش کارشناسی</th>
        <th width=\"25%\" style=\"padding: 2px; \">ملاحظات</th>
        $alternative


    </tr>";
$radif = 0;

$alternativeReadOnly=($mode=='edit')?" ":"readonly";
$table = "";
foreach ($dataInTable as $value) {
    $radif++;
    $alternative=($mode=='edit')?" ":"disabled";
    
    $sel = $value[3] == 1 ? ' selected="selected"' : '';
    $selectBox = '<select '.$alternative.' >
    <option value="1" ' . $sel . '>نو</option>
    <option value="2" ' . $sel . '>مستعمل</option>
    <option value="3" ' . $sel . '>اسقاط</option>
    </select>';




    $alternative=($mode=='edit')?"<td class=\"tdDeleteImg\" style=\"padding: 2px;background-color: #c5e1a5;border: 1px solid #ccc;\"><img onclick=\"FormView.myForm.getItemByName('Field_1').removeRow($radif)\"
    src=\"gfx/toolbar/cross.png\" style=\"cursor: pointer;\"/>
</td>":"";
    $table .= "
        
   
    
    
    <tr class=\"tableRow_$radif\">
            <td style=\"padding: 2px;border: 1px solid #ccc;\">$radif</td>
            <td style=\"padding: 2px;border: 1px solid #ccc;\"> <input $alternativeReadOnly  type=\"text\" name=\"goods\" value=\"$value[0]\"></td>
            <td style=\"padding: 2px;border: 1px solid #ccc;\"><input $alternativeReadOnly  dir=\"ltr\" type=\"text\" name=\"goodsNum\" value=\"$value[1]\"></td>
            <td style=\"padding: 2px;border: 1px solid #ccc;\"><input $alternativeReadOnly class=\"RavanMask\" data-inputmask-regex=\"([0-9]){0,5}\" dir=\"ltr\"  type=\"text\" name=\"amount\" value=\"$value[2]\"></td>
            <td class=\"sit\" style=\"padding: 2px;border: 1px solid #ccc;\">$selectBox</td>
            
            <td style=\"padding: 2px;border: 1px solid #ccc;\"><input $alternativeReadOnly class=\"RavanMask\" onkeyup=\"FormView.myForm.getItemByName('Field_1').separateNum(this.value,this);\"      dir=\"ltr\" type=\"text\" name=\"value\" value=\"$value[4]\">
            
            </td>
            <td style=\"padding: 2px;border: 1px solid #ccc;\"><input $alternativeReadOnly type=\"text\" name=\"comment\" value=\"$value[5]\"></td>
            $alternative
            </td>
        </tr>";
}
if($mode=='edit')
 $btnTableTag = "
    <tr>
            <td style=\"padding: 2px;padding-top: 7px;
        padding-bottom: 7px;border: 1px solid #ccc;background-color: #c5e1a5;\"><img onclick=\"FormView.myForm.getItemByName('Field_1').addRow()\"
                                                                  src=\"gfx/toolbar/plus.png\" style=\"cursor: pointer;\"/></td>
             <td style=\"padding: 2px;padding-top: 7px;
        padding-bottom: 7px;border: 1px solid #ccc;background-color: #c5e1a5;\">
       ذخیره
        <img onclick=\"FormView.myForm.getItemByName('Field_1').saveMyList()\"
                                                                  src=\"gfx/toolbar/out-regletters.png\" style=\"cursor: pointer;\"/>
                                                                  </a>
                                                                  </td></tr>";   
 else
   $btnTableTag="";                                                  
 $endTableTag="                                                                 
     </tbody>
    </table>";


Response::getInstance()->response = $style . $defineTableTag . $header . $table .$btnTableTag. $endTableTag;
