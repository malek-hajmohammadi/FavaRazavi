<?php

$db = MySQLAdapter::getInstance();

$month = Request::getInstance()->varCleanFromInput('mm');
$yy= Request::getInstance()->varCleanFromInput('yy');

$rid = AccessControlManager::getInstance()->getRoleID();
$uid = AccessControlManager::getInstance()->getUserID();
$sql2 = "SELECT employeeID FROM oa_users WHERE UserID=" . $uid;
$db->executeSelect($sql2);
$person = $db->fetchAssoc();

$pid= $person['employeeID'];


//$pid= Request::getInstance()->varCleanFromInput('pid');

$wfid = $db->executeScalar("SELECT `workflow_id` FROM `wf_workflow` WHERE `workflow_enable` = 1 AND `workflow_formtypeid` = 790");


$db->close();
$s1="SELECT * from InOutData.dbo.[fnGetMonthTaradodList] ('".$pid."', '".$yy."', '".$month."')";
$s1 = urlencode($s1);
$client     = new SoapClient('http://10.10.100.15/WSTuralInOut/TuralInOut.asmx?wsdl'); 		$html     = '';
$param      = array(             'username' => '8bfc0e61722d9e9c9bb2138cb359fef9',             'password' => '085c734188fb09a96eba5d22893a44c4',             'objStr' => $s1         );
$resp1      = $client->RunSelectQuery($param);
$recs = $resp1->RunSelectQueryResult->cols;
$titles     = array(             1 => 'تاريخ',     2=> 'روز هفته', 	   3 => 'وضعيت تردد',         4 => 'تردد-1',            5 => ' تردد-2',             6 => 'تردد-3',    		7=> ' تردد-4',
    8 => 'تردد-5',             9 => 'تردد-6'  ,    10 => 'تردد-7',             11 => 'تردد-8'    );
$titles     = implode('</th><th>', $titles);         $th         = "<tr ><th  >" . $titles . "</th></tr>";



foreach ($recs as $key => $value) {
    $value = (array)$value;
    foreach ($value as $key2 => $value2) {
        $value[$key2] = (array)$value2;
    }
    $recs[$key] = $value;
}
$p[0]=1;$p[1]=0;
foreach ($recs as $v) {                          $cl  = '';        $aa=array_sum($p);     $p[1]=$aa;   $row =$v['recs']['string'];
    if (!($aa% 2))                 $cl = '#ddd';      if(urldecode($row[2])=="True") $cl  = '#60ff70';
    /*$row  = implode("</td><td style='background-color:$cl;'>", $row);


    $td[] = "<tr  ><td style='background-color:$cl;'>" .urldecode( $row) . "</td></tr>"; 	*/ $dttt=explode('/',urldecode($row[0]));filerecorder::recorder("dtt:".$dttt,"homaaa123");
    $k=$p[1]-1; $td[$k] ="<tr  style='background-color:$cl;' ><td>".urldecode($row[0])."</td><td>".urldecode($row[1])."</td><td>".urldecode($row[3])."</td>";
    $cl1=''; if(urldecode($row[5])==2) $cl1='rgb(128, 182, 255)'; if(urldecode($row[5])==1) $cl1='#ff6b6b';    $td[$k] .="<td  style='background-color:$cl1;'>".urldecode($row[4])."</td>";
    $cl1=''; if(urldecode($row[7])==2) $cl1='rgb(128, 182, 255)'; if(urldecode($row[7])==1) $cl1='#ff6b6b';    $td[$k] .="<td  style='background-color:$cl1;'>".urldecode($row[6])."</td>";
    $cl1=''; if(urldecode($row[9])==2) $cl1='rgb(128, 182, 255)'; if(urldecode($row[9])==1) $cl1='#ff6b6b';    $td[$k] .="<td  style='background-color:$cl1;'>".urldecode($row[8])."</td>";
    $cl1=''; if(urldecode($row[11])==2) $cl1='rgb(128, 182, 255)'; if(urldecode($row[11])==1) $cl1='#ff6b6b';    $td[$k] .="<td  style='background-color:$cl1;'>".urldecode($row[10])."</td>";
    $cl1=''; if(urldecode($row[12])==2) $cl1='rgb(128, 182, 255)f'; if(urldecode($row[12])==1) $cl1='#ff6b6b';    $td[$k] .="<td  style='background-color:$cl1;'>".urldecode($row[12])."</td>";
    $cl1=''; if(urldecode($row[13])==2) $cl1='rgb(128, 182, 255)'; if(urldecode($row[13])==1) $cl1='#ff6b6b';    $td[$k] .="<td  style='background-color:$cl1;'>".urldecode($row[14])."</td>";
    $cl1=''; if(urldecode($row[15])==2) $cl1='rgb(128, 182, 255)'; if(urldecode($row[15])==1) $cl1='#ff6b6b';    $td[$k] .="<td  style='background-color:$cl1;'>".urldecode($row[16])."</td>";
    $editLink = "<td><a  style='cursor:pointer;color:#0000ff;' onclick='FormOnly.allFieldsContianer[10].eslah($wfid ,$dttt[0],$dttt[1],$dttt[2])'>اصلاح تردد</a></td>";
    $gDate = Date::jalali_to_gregorian($dttt[0],$dttt[1],$dttt[2]);
    $gDate = implode('-', $gDate);
    if(strtotime($gDate) > time())
        $editLink = "<td></td>";
    $cl1=''; if(urldecode($row[17])==2) $cl1='#ff6b6b'; if(urldecode($row[17])==1) $cl1='#ff6b6b';   $td[$k] .="<td  style='background-color:$cl1;'>".urldecode($row[18])."</td>$editLink</tr>";
    filerecorder::recorder("----------".$dttt." ".$rid,"homaaa123");


}         $html .= $th . (implode('', $td));
$html    = '<style>.pers_report *{text-align:center;border-collapse:collapse;font-family:b nazanin,nazanin;}.pers_report li{margin-right:10px;text-align:right;font-size:14px;}.pers_report th{background-color:#ccc;font-size:13px;}.pers_report td{font-size:17px;}</style><div style="background-color:white;" class="pers_report"  id="listtashvighat"><center><table cellpadding="1" cellspacing="0" border="1" width="99%" align="center">' . $html . '</table></center>                    </div>';
$final   =  $html . ' ';

Response::getInstance()->response = $final;
