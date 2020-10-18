<?php
$db = MySQLAdapter::getInstance();
$wfidMorkhasiS = $db->executeScalar("SELECT `workflow_id` FROM `wf_workflow` WHERE `workflow_enable` = 1 AND `workflow_formtypeid` = 34");
$wfidMorkhasiR = $db->executeScalar("SELECT `workflow_id` FROM `wf_workflow` WHERE `workflow_enable` = 1 AND `workflow_formtypeid` = 66");
$wfidMamoriatS = $db->executeScalar("SELECT `workflow_id` FROM `wf_workflow` WHERE `workflow_enable` = 1 AND `workflow_formtypeid` = 34");
$wfidMamoriatR = $db->executeScalar("SELECT `workflow_id` FROM `wf_workflow` WHERE `workflow_enable` = 1 AND `workflow_formtypeid` = 31");
$status = Request::getInstance()->varCleanFromInput('status');
$mm = Request::getInstance()->varCleanFromInput('mm');
$yy = Request::getInstance()->varCleanFromInput('yy');
$aztd = Request::getInstance()->varCleanFromInput('aztd');
$aztm = Request::getInstance()->varCleanFromInput('aztm');
$azty = Request::getInstance()->varCleanFromInput('azty');
$tatd = Request::getInstance()->varCleanFromInput('tatd');
$tatm = Request::getInstance()->varCleanFromInput('tatm');
$taty = Request::getInstance()->varCleanFromInput('taty');
//$level = Request::getInstance()->varCleanFromInput('level');
$PID = Request::getInstance()->varCleanFromInput('pid');
$GID = $PID;
$today = Date::GregToJalali(date("Y-m-d"));

$P = '';
$html = '';
$TimexUpdate = '';
$creat = '';
$TedadGheibat = 0;
$TedadNaghes = 0;
$TNahar = 0;

if($status == 1){
    switch ($mm){
        case 12:
            $m = (int)$mm - 1;
            $dt1 = $yy."/".$m."/21";
            $dt2 = $yy."/".$mm."/15";
            break;
        case 13:
            $m = (int)$mm - 1;
            $dt1 = $yy."/".$m."/16";
            $dt2 = $yy."/".$m."/30";
            break;
        default:
            $m = (int)$mm - 1;
            if(strlen($m) == 1)  $m = "0".$m;
            if(strlen($mm) == 1)  $mm = "0".$mm;
            $dt1 = $yy."/".$m."/21";
            $dt2 = $yy."/".$mm."/20";
            break;
    }
}
else if($status == 2){
    $dt1 = $azty."/".$aztm."/".$aztd;
    $dt2 = $taty."/".$tatm."/".$tatd;
}

try{


    // خارج از كارت
    $st="";
    $st.=date("h:i:sa")."*1*";
    $client2 = new SoapClient('http://172.16.61.253/Timex.asmx?wsdl');
    $s2 = "SELECT * FROM [adon].[RpgPrsKar]('".$dt1."','".$dt2."',1) WHERE empid=".$PID;
    $param2 = array(
        'username' => '3ef1b48067e4f2ac9913141d77e847dd',
        'password' => '9a3f5b14f1737c15e86680d9cd40b840',
        'objStr' => $s2
    );

    $resp2 = $client2->RunSelectQuery($param2);
    $recs2 = $resp2->RunSelectQueryResult->cols;
    $st.=date("h:i:sa")."***";
    $recs2 = json_decode(json_encode($recs2), true);
    $MorkhasiRozane = (int)($recs2['recs']['string'][18]);
    $recs2 = (int)($recs2['recs']['string'][4]);
    $KharejKartH = (int)($recs2/60);
    $KharejKartH = strlen($KharejKartH)==1 ? "0".$KharejKartH : $KharejKartH;
    $KharejKartM = (int)($recs2%60);
    $KharejKartM = strlen($KharejKartM)==1 ? "0".$KharejKartM : $KharejKartM;

    //---
    $st.=date("h:i:sa")."*2*";
    $client = new SoapClient('http://172.16.61.253/Timex.asmx?wsdl');
    $s = "SELECT [CreateDate] 
    FROM [Timex].[adon].[TblIOData] 
    WHERE IOTypeID>0 AND CreateDate>'2018-10-20 0:0:0' ORDER BY [CreateDate] DESC";
    $param = array(
        'username' => '3ef1b48067e4f2ac9913141d77e847dd',
        'password' => '9a3f5b14f1737c15e86680d9cd40b840',
        'objStr' => $s
    );
    $resp1 = $client->RunSelectQuery($param);
    $recs1 = $resp1->RunSelectQueryResult->cols;
    $st.=date("h:i:sa")."***";
    $recs1 = json_decode(json_encode($recs1), true);
    $recs1 = urldecode($recs1[0]['recs']['string']);
    $recs1 = explode(' ',$recs1);
    $date = Date::GregToJalali((new DateTime($recs1[0]))->format('Y-m-d'));
    $time = explode('.',$recs1[1]);
    $time = $time[0].':'.$time[1];
    $TimexUpdate = $date.' ساعت: '.$time;

    //--
    $st.=date("h:i:sa")."*3*";
    $client = new SoapClient('http://172.16.61.253/Timex.asmx?wsdl');
    $s1 = "EXEC [adon].[TimeSheetView] ".$GID.",'".$dt1."','".$dt2."'";
    $param = array(
        'username' => '3ef1b48067e4f2ac9913141d77e847dd',
        'password' => '9a3f5b14f1737c15e86680d9cd40b840',
        'objStr' => $s1
    );
    $resp1 = $client->RunSelectQuery($param);
    $recs = $resp1->RunSelectQueryResult->cols;
    $st.=date("h:i:sa")."***";
    $recs = json_decode(json_encode($recs), true);
    //Response::getInstance()->response = var_export($recs,true);
    //$P = $P.' -*- '.$s1;

    $SoapStatus = 1;
}
catch(SoapFaultException $e){
    $SoapStatus = 0;
}
catch(Exception $e){
    $SoapStatus = 0;
}

$count = count($recs);
$RoozMorkhasiT = 0;
$ModalID = 0;
$DocIDFroms = 0;
for($i = 0; $i < $count; $i++)
{
    $ModalID++;
    $taradodha = '';
    $class = '';
    $class2 = '';
    $Forms = '';
    $RoozMorkhasi = 0;
    $NaharNA = 0;
    if($count==1) $row = $recs['recs']['string'];
    else $row = $recs[$i]['recs']['string'];

    // تاريخ و روز
    $date = urldecode($row[2]);
    $date1 = explode('/',$date);
    $date2 = $date1;
    $date1 = Date::jalali_to_gregorian(("13".$date1[0]), $date1[1], $date1[2]);
    $date1 = implode('-',$date1);
    $rooz = $date2[0].$date2[1].$date2[2];
    $a = explode(',',urldecode($row[3]));
    switch(urldecode($row[1])){
        default: $class = ''; break;
        case "15": $class = 'f-table-Shift'; break;
        case "16": $class = 'f-table-Shift'; break;
    }
    switch($a[1]){
        case "1": $class = 'f-table-Tat'; $RoozTatile = 1; break;
        case "2": $class = 'f-table-Tat'; $RoozTatile = 1; break;
    }
    $Check = strpos(urldecode($row[15]),'مرخص');
    if($Check !== false) {$class2 = 'f-table-Mor'; $NaharNA = 1; $RoozMorkhasiT++;}

    $Check = strpos(urldecode($row[15]),'استعلا');
    if($Check !== false) {$class2 = 'f-table-Mor'; $NaharNA = 1; $RoozMorkhasiT++;}

    $Check = strpos(urldecode($row[15]),'مامور');
    if($Check !== false) {$class2 = 'f-table-Mam'; $NaharNA = 1;}

    $Check = strpos(urldecode($row[15]),'ناقص');
    if($Check !== false) {$class2 = 'f-table-Nagh-N'; $TedadNaghes++;}

    $Check = strpos(urldecode($row[15]),'بت');
    if($Check !== false) {$class2 = 'f-table-Tat'; $TedadGheibat++;}

    $html .= "<tr><td class='$class f-tooltip'>".$a[0]."<br>".$date."<span class='f-tooltiptext'>شيفت: ".urldecode($row[1])."<br>".urldecode($row[4])."</span></td>";

    // وضعيت
    $html .= "<td class='$class2'>".urldecode($row[15])."</td>";

    // ترددها
    $Nahar = 0;
    for($j = 5; $j<=14; $j++)
    {
        $a = explode(',',urldecode($row[$j]));
        switch($a[1])
        {
            default: $class = 'f-table-Adi'; $tooltip = 'فاقد تردد'; $type = 'N'; break;
            case "0": $class = 'f-table-Adi'; $tooltip = 'عادي'; $type = 'Adi'; break;
            case "-16744156": $class = 'f-table-Mor-N'; $tooltip = 'مرخصي<br>بدون فرم'; $type = 'MorS'; break;
            case "-32768": $class = 'f-table-Mam-N'; $tooltip = 'ماموريت<br>بدون فرم'; $type = 'MamS'; break;
        }
        if($a[0]!=NULL)
        {
            $time = $a[0].':00';

            if($type == 'MorS')
            {
                //---
                $sql = "SELECT * FROM `dm_datastoretable_34`
                            left join oa_document on (oa_document.rowid = dm_datastoretable_34.docid) 
                            left join oa_name_value nv1 on (nv1.parentid = dm_datastoretable_34.docid and nv1.fieldname = 'Field_0') 
                            left join oa_name_value nv2 on (nv2.parentid = nv1.rowid) 
                            where oa_document.isenable = 1 
                            and nv2.fieldname = 'uid' 
                            and oa_document.DocStatus = 0 
                            and Field_20 = '$PID' 
                            and Field_9 = '$date1' 
                            and Field_11 = 17 
                            and (Field_2 = '$time' OR Field_3 = '$time' OR Field_4 = '$time' OR Field_5 = '$time')";
                $db->executeSelect($sql);
                $row1 = $db->fetchAssoc();
                if($row1 != NULL)
                {
                    if((int)$DocIDFroms != (int)$row1['DocID'])
                    {
                        $sqlr = "SELECT max(RowID) as ReferID FROM `oa_doc_refer` WHERE `DocID` = ".$row1['DocID'];
                        $ReferID = $db->executeScalar($sqlr);

                        $Forms .= "<td class='f-table-Mor f-tooltip'>مرخصي ساعتي
                            <span class='f-tooltiptext'>شماره: ".$row1['DocID']."<br>از ساعت: ".$row1['Field_2']."<br>تا ساعت: ".$row1['Field_3']."<br><br>واقعي<br>از ساعت: ".$row1['Field_4']."<br>تا ساعت: ".$row1['Field_5']."</span>
                            <img class='f-history' onclick='History.init($ReferID)' src='gfx/toolbar/history.gif' title='نمايش سابقه فرم'></img>
                            </td>";

                    }
                    $class = 'f-table-Mor';
                    $tooltip = 'مرخصي';
                    $DocIDFroms = $row1['DocID'];
                }

            }
            else if($type == 'MamS')
            {
                //---
                $sql = "SELECT * FROM `dm_datastoretable_34`
                            left join oa_document on (oa_document.rowid = dm_datastoretable_34.docid) 
                            left join oa_name_value nv1 on (nv1.parentid = dm_datastoretable_34.docid and nv1.fieldname = 'Field_0') 
                            left join oa_name_value nv2 on (nv2.parentid = nv1.rowid) 
                            where oa_document.isenable = 1 
                            and nv2.fieldname = 'uid' 
                            and oa_document.DocStatus = 0 
                            and Field_20 = '$PID' 
                            and Field_9 = '$date1' 
                            and Field_11 = 9 
                            and (Field_2 = '$time' OR Field_3 = '$time' OR Field_4 = '$time' OR Field_5 = '$time')";
                $db->executeSelect($sql);
                $row1 = $db->fetchAssoc();
                if($row1 != NULL)
                {
                    if((int)$DocIDFroms != (int)$row1['DocID'])
                    {
                        $sqlr = "SELECT max(RowID) as ReferID FROM `oa_doc_refer` WHERE `DocID` = ".$row1['DocID'];
                        $ReferID = $db->executeScalar($sqlr);

                        $Forms .= "<td class='f-table-Mam f-tooltip'>ماموريت ساعتي
                            <span class='f-tooltiptext'>شماره: ".$row1['DocID']."<br>از ساعت: ".$row1['Field_2']."<br>تا ساعت: ".$row1['Field_3']."<br><br>واقعي<br>از ساعت: ".$row1['Field_4']."<br>تا ساعت: ".$row1['Field_5']."</span>
                            <img class='f-history' onclick='History.init($ReferID)' src='gfx/toolbar/history.gif' title='نمايش سابقه فرم'></img>
                            </td>";

                    }
                    $class = 'f-table-Mam';
                    $tooltip = 'ماموريت';
                    $DocIDFroms = $row1['DocID'];
                }

            }

            $taradodha .= "<td class='$class f-tooltip'>".$a[0]."<span class='f-tooltiptext'>$tooltip</span></td>";
        }
    }

    $DocIDFroms = 0;
    //--- مرخصي ساعتي

    /*
    $sql = "SELECT * FROM `dm_datastoretable_34`
    left join oa_document on (oa_document.rowid = dm_datastoretable_34.docid)
    left join oa_name_value nv1 on (nv1.parentid = dm_datastoretable_34.docid and nv1.fieldname = 'Field_0')
    left join oa_name_value nv2 on (nv2.parentid = nv1.rowid)
    where oa_document.isenable = 1
    and nv2.fieldname = 'uid'
    and oa_document.DocStatus = 0
    and Field_20 = '$PID'
    and Field_9 = '$date1'
    and Field_11 = 17 ";
    $db->executeSelect($sql);
    while($row1 = $db->fetchAssoc())
    {
        if((int)$DocIDFroms != (int)$row1['DocID'])
        {
            $sqlr = "SELECT max(RowID) as ReferID FROM `oa_doc_refer` WHERE `DocID` = ".$row1['DocID'];
            $ReferID = $db->executeScalar($sqlr);

            $Forms .= "<td class='f-table-Mor f-tooltip'>مرخصي ساعتي
            <span class='f-tooltiptext'>شماره: ".$row1['DocID']."<br>از ساعت: ".$row1['Field_2']."<br>تا ساعت: ".$row1['Field_3']."<br><br>واقعي<br>از ساعت: ".$row1['Field_4']."<br>تا ساعت: ".$row1['Field_5']."</span>
            <img class='f-history' onclick='History.init($ReferID)' src='gfx/toolbar/history.gif' title='نمايش سابقه فرم'></img>
            </td>";

            $DocIDFroms = $row1['DocID'];
        }
    }
    */

    //--- ماموريت ساعتي
    /*
    $sql = "SELECT * FROM `dm_datastoretable_34`
    left join oa_document on (oa_document.rowid = dm_datastoretable_34.docid)
    left join oa_name_value nv1 on (nv1.parentid = dm_datastoretable_34.docid and nv1.fieldname = 'Field_0')
    left join oa_name_value nv2 on (nv2.parentid = nv1.rowid)
    where oa_document.isenable = 1
    and nv2.fieldname = 'uid'
    and oa_document.DocStatus = 0
    and Field_20 = '$PID'
    and Field_9 = '$date1'
    and Field_11 = 9 ";
    $db->executeSelect($sql);
    while($row1 = $db->fetchAssoc())
    {
        if((int)$DocIDFroms != (int)$row1['DocID'])
        {
            $sqlr = "SELECT max(RowID) as ReferID FROM `oa_doc_refer` WHERE `DocID` = ".$row1['DocID'];
            $ReferID = $db->executeScalar($sqlr);

            $Forms .= "<td class='f-table-Mam f-tooltip'>ماموريت ساعتي
            <span class='f-tooltiptext'>شماره: ".$row1['DocID']."<br>از ساعت: ".$row1['Field_2']."<br>تا ساعت: ".$row1['Field_3']."<br><br>واقعي<br>از ساعت: ".$row1['Field_4']."<br>تا ساعت: ".$row1['Field_5']."</span>
            <img class='f-history' onclick='History.init($ReferID)' src='gfx/toolbar/history.gif' title='نمايش سابقه فرم'></img>
            </td>";

            $DocIDFroms = $row1['DocID'];
        }
    }
    */

    //--- افزودن تردد
    $sql = "SELECT * FROM `dm_datastoretable_34`
        left join oa_document on (oa_document.rowid = dm_datastoretable_34.docid) 
        left join oa_name_value nv1 on (nv1.parentid = dm_datastoretable_34.docid and nv1.fieldname = 'Field_0') 
        left join oa_name_value nv2 on (nv2.parentid = nv1.rowid) 
        where oa_document.isenable = 1 
        and nv2.fieldname = 'uid' 
        and oa_document.DocStatus = 0 
        and Field_20 = '$PID' 
        and Field_9 = '$date1' 
        and Field_11 = 200";
    $db->executeSelect($sql);
    $row1 = $db->fetchAssoc();
    if($row1 != NULL)
    {
        if((int)$DocIDFroms != (int)$row1['DocID'])
        {
            $sqlr = "SELECT max(RowID) as ReferID FROM `oa_doc_refer` WHERE `DocID` = ".$row1['DocID'];
            $ReferID = $db->executeScalar($sqlr);

            $Forms .= "<td class='f-table-Afz f-tooltip'>افزودن تردد
                <span class='f-tooltiptext'>شماره: ".$row1['DocID']."<br>ساعت اول: ".$row1['Field_2']."<br>ساعت دوم: ".$row1['Field_3']."</span>
                <img class='f-history' onclick='History.init($ReferID)' src='gfx/toolbar/history.gif' title='نمايش سابقه فرم'></img>
                </td>";
        }
        $DocIDFroms = $row1['DocID'];
    }

    //--- حذف تردد
    $sql = "SELECT * FROM `dm_datastoretable_34`
        left join oa_document on (oa_document.rowid = dm_datastoretable_34.docid) 
        left join oa_name_value nv1 on (nv1.parentid = dm_datastoretable_34.docid and nv1.fieldname = 'Field_0') 
        left join oa_name_value nv2 on (nv2.parentid = nv1.rowid) 
        where oa_document.isenable = 1 
        and nv2.fieldname = 'uid' 
        and oa_document.DocStatus = 0 
        and Field_20 = '$PID' 
        and Field_9 = '$date1' 
        and Field_11 = 300";
    $db->executeSelect($sql);
    $row1 = $db->fetchAssoc();
    if($row1 != NULL)
    {
        if((int)$DocIDFroms != (int)$row1['DocID'])
        {
            $sqlr = "SELECT max(RowID) as ReferID FROM `oa_doc_refer` WHERE `DocID` = ".$row1['DocID'];
            $ReferID = $db->executeScalar($sqlr);

            $Forms .= "<td class='f-table-Tat f-tooltip'>حذف تردد
                <span class='f-tooltiptext'>شماره: ".$row1['DocID']."<br>ساعت اول: ".$row1['Field_2']."<br>ساعت دوم: ".$row1['Field_3']."</span>
                <img class='f-history' onclick='History.init($ReferID)' src='gfx/toolbar/history.gif' title='نمايش سابقه فرم'></img>
                </td>";
        }
        $DocIDFroms = $row1['DocID'];
    }

    //--- مرخصي روزانه
    $sql = "SELECT * FROM `dm_datastoretable_66`
        left join oa_document on (oa_document.rowid = dm_datastoretable_66.docid) 
        left join oa_name_value nv1 on (nv1.parentid = dm_datastoretable_66.docid and nv1.fieldname = 'Field_0') 
        left join oa_name_value nv2 on (nv2.parentid = nv1.rowid) 
        where oa_document.isenable = 1 
        and nv2.fieldname = 'uid' 
        and oa_document.DocStatus = 0 
        and Field_11 = '$PID' 
        and Field_2 = '$date1' 
        and Field_6 = '8'";
    $db->executeSelect($sql);
    $row1 = $db->fetchAssoc();
    if($row1 != NULL)
    {
        if((int)$DocIDFroms != (int)$row1['DocID'])
        {
            $sqlr = "SELECT max(RowID) as ReferID FROM `oa_doc_refer` WHERE `DocID` = ".$row1['DocID'];
            $ReferID = $db->executeScalar($sqlr);

            $TR = Date::GregToJalali((new DateTime($row1['Field_2']))->format('Y-m-d'));
            $TB = Date::GregToJalali((new DateTime($row1['Field_3']))->format('Y-m-d'));
            $Forms .= "<td class='f-table-Mor f-tooltip'>مرخصي روزانه
                <span class='f-tooltiptext'>شماره: ".$row1['DocID']."<br>رفت: ".$TR."<br>برگشت: ".$TB."</span>
                <img class='f-history' onclick='History.init($ReferID)' src='gfx/toolbar/history.gif' title='نمايش سابقه فرم'></img>
                </td>";
        }
        $DocIDFroms = $row1['DocID'];
    }

    //--- ماموريت روزانه
    $sql = "SELECT * FROM `dm_datastoretable_31`
        left join oa_document on (oa_document.rowid = dm_datastoretable_31.docid) 
        left join oa_name_value nv1 on (nv1.parentid = dm_datastoretable_31.docid and nv1.fieldname = 'Field_24') 
        left join oa_name_value nv2 on (nv2.parentid = nv1.rowid) 
        left join oa_users on (oa_users.userid=nv2.value) 
        where oa_document.isenable = 1 
        and nv2.fieldname = 'uid' 
        and oa_document.DocStatus = 0 
        and oa_users.employeeid='$PID' 
        and Field_32 = '$date1'";
    $db->executeSelect($sql);
    $row1 = $db->fetchAssoc();
    if($row1 != NULL)
    {
        if((int)$DocIDFroms != (int)$row1['DocID'])
        {
            $sqlr = "SELECT max(RowID) as ReferID FROM `oa_doc_refer` WHERE `DocID` = ".$row1['DocID'];
            $ReferID = $db->executeScalar($sqlr);

            $TR = Date::GregToJalali((new DateTime($row1['Field_32']))->format('Y-m-d'));
            $TB = Date::GregToJalali((new DateTime($row1['Field_33']))->format('Y-m-d'));
            $Forms .= "<td class='f-table-Mam f-tooltip'>ماموريت روزانه
                <span class='f-tooltiptext'>شماره: ".$row1['DocID']."<br>رفت: ".$TR."<br>برگشت: ".$TB."</span>
                <img class='f-history' onclick='History.init($ReferID)' src='gfx/toolbar/history.gif' title='نمايش سابقه فرم'></img>
                </td>";
        }
        $DocIDFroms = $row1['DocID'];
    }
    //---

    $creat  = "<div class='f-button-1' onmousedown='FormOnly.allFieldsContianer[10].CreateFormMamoriatR($wfidMamoriatR,$date2[0],$date2[1],$date2[2],$PID)'>ماموريت<br>روزانه</div>";
    $creat .= "<div class='f-button-1' onmousedown='FormOnly.allFieldsContianer[10].CreateFormMorkhasiR($wfidMorkhasiR,$date2[0],$date2[1],$date2[2],$PID)'>مرخصي<br>روزانه</div>";
    $creat .= "<div class='f-button-1' onmousedown='FormOnly.allFieldsContianer[10].CreateFormTaradodH($wfidMorkhasiS,$date2[0],$date2[1],$date2[2],$PID)'>حذف<br>تردد</div>";
    $creat .= "<div class='f-button-1' onmousedown='FormOnly.allFieldsContianer[10].CreateFormTaradodA($wfidMorkhasiS,$date2[0],$date2[1],$date2[2],$PID)'>افزودن<br>تردد</div>";
    $creat .= "<div class='f-button-1' onmousedown='FormOnly.allFieldsContianer[10].CreateFormMamoriatS($wfidMamoriatS,$date2[0],$date2[1],$date2[2],$PID)'>ماموريت<br>ساعتي</div>";
    $creat .= "<div class='f-button-1' onmousedown='FormOnly.allFieldsContianer[10].CreateFormMorkhasiS($wfidMorkhasiS,$date2[0],$date2[1],$date2[2],$PID)'>مرخصي<br>ساعتي</div>";
    $creat = "
        <a class='f-button-float-1' href='#Modal$ModalID'>ايجاد فرم</a>
        <div id='Modal$ModalID' class='f-modal-modalDialog'>
            <div>
            <a href='#close' class='f-modal-close'>✖</a>
            $creat
            </div>
        </div>
        </td>
        ";
    $html  .= "<td align='right' style='width:100%; position: relative;'><table cellpadding='0' cellspacing='3'><tr>$creat $taradodha</tr></table></td>";

    // تاخير و تعجيل
    $s16 = urldecode($row[16]) == NULL ? "00:00" : urldecode($row[16]);
    $s16a = explode(':',$s16);
    $H = strlen(intval($s16a[0]))==1 ? "0".intval($s16a[0]) : intval($s16a[0]);
    $M = strlen(intval($s16a[1]))==1 ? "0".intval($s16a[1]) : intval($s16a[1]);
    $s16 = $H.":".$M;
    $s16a = explode(':',$s16);
    $s16j = "00:00";
    $FirstHour = 0;
    $JJJ = $s16;
    $JJJ = explode(':',$JJJ);

    $s17 = urldecode($row[17]) == NULL ? "00:00" : urldecode($row[17]);
    $s17a = explode(':',$s17);
    $H = strlen(intval($s17a[0]))==1 ? "0".intval($s17a[0]) : intval($s17a[0]);
    $M = strlen(intval($s17a[1]))==1 ? "0".intval($s17a[1]) : intval($s17a[1]);
    $s17 = $H.":".$M;
    $s17a = explode(':',$s17);

    $s18 = urldecode($row[18]) == NULL ? "00:00" : urldecode($row[18]);
    $s18a = explode(':',$s18);
    $H = strlen(intval($s18a[0]))==1 ? "0".intval($s18a[0]) : intval($s18a[0]);
    $M = strlen(intval($s18a[1]))==1 ? "0".intval($s18a[1]) : intval($s18a[1]);
    $s18 = $H.":".$M;
    $s18a = explode(':',$s18);

    $KasrVasat1 = (int)$s16a[1] + ((int)$s16a[0] * 60) + (int)$s17a[1] + ((int)$s17a[0] * 60);
    $KasrVasat2 = (int)$s18a[1] + ((int)$s18a[0] * 60);
    $KasrVasat = (int)$KasrVasat2 - (int)$KasrVasat1;
    $KasrVasat = (int)$KasrVasat/60;
    $KasrVasat = explode('.',(string)$KasrVasat);
    $H = (int)$KasrVasat[0];
    $M = "0.".$KasrVasat[1];
    $M = $M * 60;
    $H = $H + floor($M/60);
    $H = strlen(intval($H))==1 ? "0".intval($H) : intval($H);
    $M = $M%60;
    $M = strlen(intval($M))==1 ? "0".intval($M) : intval($M);
    $KasrVasat = $H.":".$M;
    $KasrVasata = explode(':',$KasrVasat);

    //---
    $JarimeOK = 0;
    $JarimeH = $s16a[0];
    $JarimeM = $s16a[1];

    if((intval($JarimeH)>0) || $JarimeOK == 1)
    {
        $TH = intval($JarimeH);
        $TM = intval($JarimeM);
        $diff_time = ($TH*60)+$TM;
        if((int)$rooz >= 980221 && (int)$rooz <= 980320)
        {
            $diff_time = $diff_time;
            switch($s16a[0]){
                case 0: $FirstHour = 0; break;
                case 1: $FirstHour = 0; break;
                case 2: $FirstHour = 2; break;
                case 3: $FirstHour = 2; break;
                case 4: $FirstHour = 2; break;
                case 5: $FirstHour = 2; break;
            }
        }
        else
        {
            $diff_time = (int)$diff_time-60;
            $FirstHour = 1;
        }

        $diff_time = (int)$diff_time*1.5;
        $diff_time = (int)$diff_time/60;
        $diff_time = explode('.',(string)$diff_time);
        $H = (int)$diff_time[0];
        $M = "0.".$diff_time[1];
        $M = $M * 60;
        $H = $H + floor($M/60);
        $H = strlen(intval($H))==1 ? "0".intval($H) : intval($H);
        $M = $M%60;
        $M = strlen(intval($M))==1 ? "0".intval($M) : intval($M);
        $s16j = $H.":".$M;
        $JJJ = $s16j;
        $JJJ = explode(':',$JJJ);
    }

    if($PID == 881323 || $PID == 831225 || $PID == 811209 || $PID == 881323)
    {
        $H = (int)$s16a[0] + (int)$s17a[0] + (int)$KasrVasata[0];
        $M = (int)$s16a[1] + (int)$s17a[1] + (int)$KasrVasata[1];
    }
    else
    {
        if((int)$rooz >= 980221 && (int)$rooz <= 980320)
        {
            $JJJ[0] = $s16a[0];
            $JJJ[1] = $s16a[1];
            $FirstHour = 0;
        }
        $H = (int)$JJJ[0] + (int)$s17a[0] + (int)$KasrVasata[0] + $FirstHour;
        $M = (int)$JJJ[1] + (int)$s17a[1] + (int)$KasrVasata[1];
        //$H = (int)$s16a[0] + (int)$s17a[0] + (int)$KasrVasata[0];
        //$M = (int)$s16a[1] + (int)$s17a[1] + (int)$KasrVasata[1];
    }
    //---

    $H = $H + floor($M/60);
    $H = strlen(intval($H))==1 ? "0".intval($H) : intval($H);
    $M = $M%60;
    $M = strlen(intval($M))==1 ? "0".intval($M) : intval($M);
    $TjT = $H.":".$M;

    //$html .= "<td class='f-tooltip'>$TjT<span class='f-tooltiptext'>تاخير اول: $s16<br>كسر وسط: $KasrVasat<br>تعجيل آخر: $s17</span></br>";
    $html .= "<td class='f-tooltip'>$TjT<span class='f-tooltiptext'>تاخير اول: $s16<br>جريمه: $s16j<br>كسر وسط: $KasrVasat<br>تعجيل آخر: $s17</span></br>";

    $TKasr[$i] = $TjT;

    // اضافه‌كاري و شب كاري و تعطيل كاري
    /*اضافه*/$s26 = urldecode($row[26]) == NULL ? "00:00" : urldecode($row[26]);
    /*شب*/$s30 = urldecode($row[31]) == NULL ? "00:00" : urldecode($row[31]);
    /*تعطيل*/$s28 = urldecode($row[28]) == NULL ? "00:00" : urldecode($row[28]);
    $html .= "<td class='f-tooltip'>$s26<span class='f-tooltiptext'>شب‌كار: $s30<br>تعطيل‌كار: $s28</span></br>";
    /*
    $html .= "<td class='f-tooltip'>$s26</td>";
    $html .= "<td class='f-tooltip'>$s30</td>";
    $html .= "<td class='f-tooltip'>$s28</td>";
    */
    $TEzafe[$i] = $s26;
    $TShab[$i] = $s30;
    $TTatil[$i] = $s28;

    // مرخصي و ماموريت
    $s19 = urldecode($row[19]) == NULL ? "00:00" : urldecode($row[19]);
    $s20 = urldecode($row[20]) == NULL ? "00:00" : urldecode($row[20]);
    $s23 = urldecode($row[23]) == NULL ? "00:00" : urldecode($row[23]);
    $TMorkhasi[$i] = $s19;
    $TMamoriat[$i] = $s20;
    $html .= "<td class='f-tooltip'>$s23<span class='f-tooltiptext'>مرخصي: $s19<br>ماموريت: $s20</br></span></td>";

    // ناهار
    $s26 = urldecode($row[26]) == NULL ? "0000" : str_replace(":","",urldecode($row[26]));
    $s28 = urldecode($row[28]) == NULL ? "0000" : str_replace(":","",urldecode($row[28]));
    if($NaharNA == 0){
        if((int)$rooz >= 990421){
            if((int)$s26 >= 300) $Nahar++;
            if((int)$s28 >= 300) $Nahar++;
        }
        else{
            if((int)$s26 >= 200) $Nahar++;
            if((int)$s28 >= 200) $Nahar++;
        }
    }

    if($Nahar > 0)
    {
        $TNahar++;
        $html .= "<td><img src='gfx/toolbar/tick.png'></td>";
    }
    else $html .= "<td></td>";

    $html .= $Forms."</tr>";

}

$TMorkhasiH = (int)0;
$TMorkhasiM = (int)0;
foreach($TMorkhasi as $time){
    $time = explode(':',$time);
    $TMorkhasiH = $TMorkhasiH + (int)$time[0];
    $TMorkhasiM = $TMorkhasiM + (int)$time[1];
}
$TMorkhasiH = $TMorkhasiH + floor($TMorkhasiM/60);
$TMorkhasiH = strlen($TMorkhasiH)==1 ? "0".$TMorkhasiH : $TMorkhasiH;
$TMorkhasiM = $TMorkhasiM%60;
$TMorkhasiM = strlen($TMorkhasiM)==1 ? "0".$TMorkhasiM : $TMorkhasiM;

$TMorkhasiKol = number_format(($RoozMorkhasiT + (((($TMorkhasiH * 60) + $TMorkhasiM) / 60) / 7.33)), 2);


$TMamoriatH = (int)0;
$TMamoriatM = (int)0;
foreach($TMamoriat as $time){
    $time = explode(':',$time);
    $TMamoriatH = $TMamoriatH + (int)$time[0];
    $TMamoriatM = $TMamoriatM + (int)$time[1];
}
$TMamoriatH = $TMamoriatH + floor($TMamoriatM/60);
$TMamoriatH = strlen($TMamoriatH)==1 ? "0".$TMamoriatH : $TMamoriatH;
$TMamoriatM = $TMamoriatM%60;
$TMamoriatM = strlen($TMamoriatM)==1 ? "0".$TMamoriatM : $TMamoriatM;


$TKasrH = (int)0;
$TKasrM = (int)0;
foreach($TKasr as $time){
    $time = explode(':',$time);
    $TKasrH = $TKasrH + (int)$time[0];
    $TKasrM = $TKasrM + (int)$time[1];
}
$TKasrH = $TKasrH + floor($TKasrM/60);
$TKasrH = strlen($TKasrH)==1 ? "0".$TKasrH : $TKasrH;
$TKasrM = $TKasrM%60;
$TKasrM = strlen($TKasrM)==1 ? "0".$TKasrM : $TKasrM;


$TEzafeH = (int)0;
$TEzafeM = (int)0;
foreach($TEzafe as $time){
    $time = explode(':',$time);
    $TEzafeH = $TEzafeH + (int)$time[0];
    $TEzafeM = $TEzafeM + (int)$time[1];
}
$TEzafeH = $TEzafeH + floor($TEzafeM/60);
$TEzafeH = strlen($TEzafeH)==1 ? "0".$TEzafeH : $TEzafeH;
$TEzafeM = $TEzafeM%60;
$TEzafeM = strlen($TEzafeM)==1 ? "0".$TEzafeM : $TEzafeM;


$TShabH = (int)0;
$TShabM = (int)0;
foreach($TShab as $time){
    $time = explode(':',$time);
    $TShabH = $TShabH + (int)$time[0];
    $TShabM = $TShabM + (int)$time[1];
}
$TShabH = $TShabH + floor($TShabM/60);
$TShabH = strlen($TShabH)==1 ? "0".$TShabH : $TShabH;
$TShabM = $TShabM%60;
$TShabM = strlen($TShabM)==1 ? "0".$TShabM : $TShabM;


$TTatilH = (int)0;
$TTatilM = (int)0;
foreach($TTatil as $time){
    $time = explode(':',$time);
    $TTatilH = $TTatilH + (int)$time[0];
    $TTatilM = $TTatilM + (int)$time[1];
}
$TTatilH = $TTatilH + floor($TTatilM/60);
$TTatilH = strlen($TTatilH)==1 ? "0".$TTatilH : $TTatilH;
$TTatilM = $TTatilM%60;
$TTatilM = strlen($TTatilM)==1 ? "0".$TTatilM : $TTatilM;


//محاسبات

//$TEzafe2H = $TEzafeH + $TTatilH + $TShabH + $KharejKartH;
$TEzafe2H = $TEzafeH + $KharejKartH;
//$TEzafe2M = $TEzafeM + $TTatilM + $TShabM + $KharejKartM;
$TEzafe2M = $TEzafeM + $KharejKartM;
$TEzafe2H = $TEzafe2H + floor($TEzafe2M/60);
$TEzafe2H = strlen($TEzafe2H)==1 ? "0".$TEzafe2H : $TEzafe2H;
$TEzafe2M = $TEzafe2M%60;
$TEzafe2M = strlen($TEzafe2M)==1 ? "0".$TEzafe2M : $TEzafe2M;

$TShab2 = ((int)$TShabH*60)+(int)$TShabM;
$TKasr2 = ((int)$TKasrH*60)+(int)$TKasrM;
$TEzafe = ((int)$TEzafeH*60)+(int)$TEzafeM;
$TEzafe2 = ((int)$TEzafe2H*60)+(int)$TEzafe2M;

$diff_time = (int)$TEzafe2-(int)$TShab2-(int)$TKasr2;
if($diff_time < 0) $manfi = '-';
else $manfi = '';
$diff_time = (int)$diff_time/60;
$diff_time = explode('.',(string)$diff_time);
$h = (int)$diff_time[0];
$m = "0.".$diff_time[1];
$m = $m * 60;
$h = $h + floor($m/60);
$TNahaeeH = strlen($h)==1 ? "0".$h : $h;
$m = $m%60;
$TNahaeeM = strlen($m)==1 ? "0".$m : $m;

if($TNahaeeH == '00' && $manfi == '-') $TNahaee = $TNahaeeH.':'.$TNahaeeM.$manfi;
else $TNahaee = $TNahaeeH.':'.$TNahaeeM;

$diff_time = (int)$TEzafe-(int)$TShab2;
if($diff_time < 0) $manfi = '-';
else $manfi = '';
$diff_time = (int)$diff_time/60;
$diff_time = explode('.',(string)$diff_time);
$h = (int)$diff_time[0];
$m = "0.".$diff_time[1];
$m = $m * 60;
$h = $h + floor($m/60);
$TEzafeKhalesH = strlen($h)==1 ? "0".$h : $h;
$m = $m%60;
$TEzafeKhalesM = strlen($m)==1 ? "0".$m : $m;

if($TEzafeKhalesH == '00' && $manfi == '-') $TEzafeKhales = $TEzafeKhalesH.':'.$TEzafeKhalesM.$manfi;
else $TEzafeKhales = $TEzafeKhalesH.':'.$TEzafeKhalesM;

$Kholase = "
    <table class='f-table' cellpadding='0' cellspacing='3' style='width:50%'><tbody>
    <tr>
        <td><strong>جمع اضافه‌كار خالص</strong>: $TEzafeKhales</td>
        <td style='background-color:#dcdcdc!important'>تعداد تردد ناقص: $TedadNaghes</td>
    </tr>
    <tr>
        <td><strong>جمع كسركار</strong>: $TKasrH:$TKasrM</td>
        <td style='background-color:#dcdcdc!important'>تعداد غيبت: $TedadGheibat</td>
    </tr>
    
    <tr>
        <td><strong>جمع خارج از كارت</strong>: $KharejKartH:$KharejKartM</td>
        <td style='background-color:#dcdcdc!important'>تعداد ناهار: $TNahar</td>
        <!--
        <td style='background-color:#dcdcdc!important'>جمع ماموريت ساعتي: $TMamoriatH:$TMamoriatM</td>
        -->
    </tr>

    <tr>
        <td><strong>اضافه‌كار پرداختي</strong>: $TNahaee</td>
        <td style='background-color:#dcdcdc!important'>تعداد مرخصي روزانه: $RoozMorkhasiT</td>
    </tr>

    <tr>
        <td>جمع تعطيل كار: $TTatilH:$TTatilM</td>
        <td style='background-color:#dcdcdc!important'>جمع مرخصي ساعتي: $TMorkhasiH:$TMorkhasiM</td>
    </tr>
    <tr>
        <td>جمع شب كار: $TShabH:$TShabM</td>
        <td style='background-color:#dcdcdc!important'>مرخصي استفاده شده: $TMorkhasiKol روز</td>
    </tr>

    </tbody></table>
    ";

$html = '<table class="f-table" cellpadding="0" cellspacing="3" style="width:100%"><thead><tr>
            <th>تاريخ</th>
            <th>وضعيت</th>
            <th>ترددها</th>
            <th>كسركار</th>
            <th>اضافه‌‌كار + شب‌كار</th>
            <th>حضور</th>
            <!--
            <th>شب‌كار</th>
            <th>تعطيل‌كار</th>
            -->
            <th>ناهار</th>
            </tr></thead><tbody>'.$html.'</tbody></table>';

if($SoapStatus == 1)
{
    Response::getInstance()->response = $st.'
        <div class="f-box">'.$Kholase.'</div>
        <divشماره class="accordion blue"><label class="accordion-label">آخرين تاريخ انجام بروزرساني سامانه گراف: '.$TimexUpdate.'
                                
        شماره پرسنلي: '.$PID.'</label></div>
        <div class="f-box">'.$html.'</div>
        '.$P;
}
else
{
    Response::getInstance()->response = $st.'<div class="f-box"><table class="f-table" cellpadding="0" cellspacing="3" style="width:100%"><tbody><tr><td>امكان ارتباط با سامانه گراف مقدور نمي باشد</td></tr></tbody></table></div>';
}
