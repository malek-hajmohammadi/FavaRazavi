<?php

class calssName
{
    public function objToArray($d)
    {
        if (is_object($d)) {
            $d = (array)$d;
        }
        if (is_array($d)) {
            foreach ($d as $i => $v) {
                $d[$i] = $this->objToArray($v);
            }
        }
        return $d;
    }

    public function execute($self)
    {
        $s0 = 'SELECT `uid`,`rid` FROM `vi_form_userrole`  where docID="' . ($self->docid) . '"  and `FieldName`="Field_24" ';
        $db = MySQLAdapter::getInstance();
        $db->executeSelect($s0);
        $user_row = $db->fetchAssoc();
        $emp = 0;
        if (is_array($user_row) && isset($user_row['uid'])) {
            $sql = 'SELECT  fname ,lname ,employeeID   FROM  oa_users WHERE  UserID =' . intval($user_row['uid']) . '  LIMIT 1';
            $db = MySQLAdapter::getInstance();
            $db->executeSelect($sql);
            $user_info = $db->fetchAssoc();
            if (is_array($user_info) && isset($user_info['employeeID'])) {
                $emp = intval($user_info['employeeID']);
            }
        }
        $s1 = "SELECT   Radif ,prsCode ,prsFamily ,prsName ,khedmat_Vaziyat ,khedmat_Mahal ,prsPost ,prsEstekhdam ,prsMadrak ,doreName ,doreTime  FROM Rotbe_DoreAmozeshi where prsCode='" . $emp . "' ";
        $titles = array(0 => 'رديف', 1 => 'پرسنلي', 2 => 'نام خانوادگي', 3 => 'نام', 4 => 'وضعيت خدمت', 5 => 'محل خدمت', 6 => 'سمت', 7 => 'استخدامي', 8 => 'مدارك', 9 => 'عنوان دوره', 10 => 'ساعت');
        $s1 = urlencode($s1);
        $client = new SoapClient('http://10.10.100.15/WSEvaluation/evaluation.asmx?wsdl');
        $html = '';
        $param = array('objStr' => $s1);
        $resp1 = $client->RunSelectQuery($param);
        $resp2 = $this->objToArray($resp1);
        $result = $resp2['RunSelectQueryResult']['cols'];
        $html = '';
        $th = "<tr><th> " . $titles[0] . "</th><th> " . $titles[9] . "</th><th> " . $titles[10] . "</th></tr>";
        $rows = 1;
        $sum = 0;
        $sarr = array();
        $resultcp = $result;
        if (is_array($result)) foreach ($result as $inde => $v) {
            $row = '';
            $row = $v['recs']['string'];
            $html2 = '';
            foreach ($titles as $ind => $val) {
                $html2 .= $val . ":" . urldecode($row[$ind]) . "  ";
            }
            $td[] = "<tr title=\"" . (addslashes($html2)) . "\"><td>$inde</td><td> " . urldecode($row[9]) . "</td><td> " . urldecode($row[10]) . "</td></tr>";
        }
        if (is_array($resultcp)) foreach ($resultcp as $inde => $v) {
            $row = '';
            $row = $v['recs']['string'];
            $sarr[] = "" . urldecode($row[10]) . "";
        }
        $timetext = 'مجموع ساعات آموزش:';
        $tdcounter = count($td);
        if (is_array($td)) {
            $counter = count($td);
            $firsthalf = array_slice($td, 0, round($counter / 2));
            $secondhalf = array_slice($td, round($counter / 2));
            $table1 = '<table  cellpadding="1" cellspacing="0"  border="1">' . $th . implode('', $firsthalf) . '</table>';
            $table2 = '<table  cellpadding="1" cellspacing="0"  border="1">' . $th . implode('', $secondhalf) . '</table>';
            $td = '<tr><td valign="top">' . $table1 . '</td><td></td><td valign="top" >' . $table2 . '</td></tr>';
            $html .= $td;
        } else $html .= $th . implode('', $td);
        $html = '<div class="pers_report" ><center><table cellpadding="1" cellspacing="0" border="0">' . $html . '</table><h3 dir="rtl">' . $timetext . (array_sum($sarr)) . '</h3></center></div>';
        return array("res" => $html . var_export($emp, true));
    }
}
