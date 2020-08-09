<?php


class calssName
{
    public function __construct()
    {
    }

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

    public function execute(ezcWorkflowExecution $execution)
    {
        filerecorder::recorder("MS_TRACE_start_P2", 1); //این خط چه کار می کند//

        $error_field = 'Field_47';//پیام سیستم//
        $execution->workflow->myForm->setFieldValueByName($error_field, '');
        $DocID = $execution->workflow->myForm->instanceID;//آیا این خط همیشه docId رو می ده//
        $execution->setVariable('emp', '0');
        $execution->setVariable('accept', '1');
        filerecorder::recorder("MS_TRACE_start_P22", 1);//این خط به شکل دیگر دوباره امده//


        $s0 = 'SELECT `uid`,`rid` FROM `vi_form_userrole`  where docID="' . $DocID . '"  and `FieldName`="Field_24" ';
//این جدول vi_form_userrole چه جدولی است//
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
            } else {
                $error = 'کاربر متقاضی نامشخص است';
                $execution->workflow->myForm->setFieldValueByName($error_field, $error);
//return 0;
            }
        } else {
            $error = 'کاربر متقاضی نامشخص است';
            $execution->workflow->myForm->setFieldValueByName($error_field, $error);
//return 0;
        }

        if (intval($emp) <= 0) {
            $error = 'شماره پرسنلی متقاضی نامشخص است';
            $execution->workflow->myForm->setFieldValueByName($error_field, $error);
//return 0;
        } else
            $execution->setVariable('emp', '' . intval($emp));

        $s00 = "SELECT Radif,prsCode,prsFamily,prsName,madrakTitle,madrakAkhzDate,madrakStartDate
FROM Rotbe_Madarek where cast(prsCode as int)='" . (intval($emp)) . "' ";
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
        filerecorder::recorder("MS_SQL_S1:" . var_export($s1, true), 1);
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
        $param = array('objStr' => $s1);
        $resp1 = $client->RunSelectQuery($param);
        $resp2 = $this->objToArray($resp1);
        $result = $resp2['RunSelectQueryResult']['cols'];
//filerecorder::recorder("MS_SQL_RESULT:".var_export( $result ,true) , 1);
        $html = '';
        $th0 = '';
        $td0 = '';
        foreach ($titles as $t)
            $th0 .= '<th>' . $t . '</th>';
        $th = '<tr>' . $th0 . '</tr>';
//$th="<tr><th> ".$titles[0]."</th>"."<th> ".$titles[4]."</th>"."<th> ".$titles[5]."</th>"."<th> ".$titles[6]."</th></tr>";
        if (is_array($result))
            foreach ($result as $i => $v) {
                if ($i == 'recs')
                    $row = $v['string'];
                else
                    $row = $v['recs']['string'];
                $html2 = '';
                foreach ($titles as $ind => $val)
                    $html2 .= $val . ":" . urldecode($row[$ind]) . "  ";
                foreach ($row as $val)
                    $td0 .= '<td>' . urldecode($val) . '</td>';
                $td = $td0;
// $td="<td> ".urldecode($row[0])."</td>"."<td> ".urldecode($row[4])."</td>"."<td> ".urldecode($row[5])."</td>"."<td> ".urldecode($row[6])."</td>";
                $html .= '<tr  title="' . (addslashes($html2)) . '" >' . $td . '</tr>';
            }
        $html = '<div class="pers_report" ><center><table cellpadding="1" cellspacing="0" border="1">' . $th . $html . '</table></center></div>';
//return array("res" => $html.var_export($emp,true));
        filerecorder::recorder("MS_SQL_WEBARRAY:" . var_export($html, true), 1);
        if (is_array($row)) {
            $execution->workflow->myForm->setFieldValueByName('Field_0', urldecode($row[5]));
            $execution->workflow->myForm->setFieldValueByName('Field_1', urldecode($row[4]));
            $execution->workflow->myForm->setFieldValueByName('Field_2', urldecode($row[3]));
            /*
            $execution->workflow->myForm->setFieldValueByName('Field_25', urldecode($row[2]));
            چون گفتند که فقط این فیلد گاهی مشکل داره  این رو کامنت کردم و مقدار رو از همینجا می گیرم
            به جای انیکه از وب سرویس بگیرم
            */
            $execution->workflow->myForm->setFieldValueByName('Field_25', urldecode($emp));

            $execution->workflow->myForm->setFieldValueByName('Field_5', urldecode($row[6]));
            $execution->workflow->myForm->setFieldValueByName('Field_4', urldecode($row[10]) . ' _ ' . urldecode($row[11]));
            $execution->workflow->myForm->setFieldValueByName('Field_9', urldecode($row[15]));
            $execution->workflow->myForm->setFieldValueByName('Field_10', urldecode($row[16]));
            $execution->workflow->myForm->setFieldValueByName('Field_12', urldecode($row[17]));
            $execution->workflow->myForm->setFieldValueByName('Field_13', urldecode($row[18]));
//$execution->workflow->myForm->setFieldValueByName( 'Field_28',urldecode($row[25] ));
// $execution->workflow->myForm->setFieldValueByName( 'Field_29',urldecode($row[26] ));
            if (isset($row[26]) && intval($row[26]) > 0)
                $execution->setVariable('accept', '1');
        }//end if
        else {
            $execution->setVariable('accept', '1');
        }

        filerecorder::recorder("MS_TRACE_end_P2", 1);
    }//end func
}


