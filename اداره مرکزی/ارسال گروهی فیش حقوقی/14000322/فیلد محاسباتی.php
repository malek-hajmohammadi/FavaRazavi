<?php

class calssName
{
    public function execute($self)
    {
        $db = WFPDOAdapter::getInstance();
        $message = 'كاربراني كه فرم برايشان ايجاد ميشود';
        $strHTML = '<h5 >' . $message . '</h5><div style="font-size:18px;font-weight:bold;"><table dir="rtl" style="font-size:18px;font-weight:bold;text-align:center" cellpadding="3" cellspacing="0" border="1" >';
        $strHTML .= '<tr><td>شماره پرسنلي</td><td width="120"> نام و نام خانوادگي</td><td style="direction:ltr">پيوست</td></tr>';
        //$pathst = "/mnt/fish/";
         $pathst="/opt/storage/Fish/";
         $output="";
         if (is_readable($pathst))
             $output="$pathst is readable2";         else             $output="$pathst is not readable";        // return array("res" => $output);          $res = array();         if (is_dir($pathst)) {             if ($handle = opendir($pathst)) {                 filerecorder::recorder("گردشكار فيش حقوق");                 while (false !== ($file = readdir($handle))) {                     filerecorder::recorder("گردشكار فيش حقوق");                     if ($file != "." && $file != ".." && $file[0] != ".") {                         filerecorder::recorder("گردشكار فيش حقوق");                         $real_name = $file;                         $file_arr = explode(".", $file);                         $fileARR = explode('_', trim($file_arr[0]));                         $personID = $fileARR[0];                         $personID = intval($personID);                         $sql2 = 'select oa_users.UserID as UID,oa_depts_roles.RowID as RID,oa_users.fname,oa_users.lname,oa_depts_roles.Name from oa_users left Outer join oa_depts_roles ON (oa_users.UserID = oa_depts_roles.UserID ) where oa_users.employeeID="' . $personID . '" limit 0,1';                         $db->executeSelect($sql2);                         $person = $db->fetchAssoc();                         $UID = $person['UID'];                         $RID = $person['RID'];                         $res[] = $person;                         $strHTML .= '<tr><td>' . $personID . '</td><td>' . $person["fname"] . '  ' . $person["lname"] . '</td><td>' . $real_name . '</td></tr>';                     }                 }                 $strHTML .= '</table></div>';             }         }         return array("res" => $strHTML);     } }
