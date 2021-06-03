<?php

class calssName
{
public function __construct()
{
}

public function execute(ezcWorkflowExecution $execution)
{

//---------------------------------------
$message = 'نتيجه اجراي عمليات براي كاربران';
$strHTML1 = '<h5>' . $message . '</h5><table dir="rtl" style="" cellpadding="1" cellspacing="0" border="1" >';
    $strHTML1 .= '<tr><th>شماره پرسنلي</th><th width="120"> نام و نام خانوادگي</th><th>شماره فرم</th><th>نتيجه عمليات</th></tr>';
    //---------------------------------------


    $pathst = "/opt/storage/Fish/";
    $pathstdes = "";


    if (is_dir($pathst)) {
    if ($handle = opendir($pathst)) {
    $request = Request::getInstance();
    list($lastDocID, $lastReferID, $lastFormID, $lastReferNote) = $request->varCleanFromInput('docID', 'referID', 'structID', 'referNote');
    $db = WFPDOAdapter::getInstance();
    while (($file = readdir($handle)) !== false) {
    # Make sure we don't push parental directories or dotfiles (unix) into the arrays
    if ($file != "." && $file != ".." && $file != "") {

    $real_name = $file;
    $userFamily = '';
    $newTitle = "فيش حقوقي ";
    $file_arr = explode(".", $file);
    $fileARR = explode('_', trim($file_arr[0]));
    //شماره پرسنلي
    $personID = $fileARR[0];
    $personID = intval($personID);
    $year = $fileARR[1];
    $mah = $fileARR[2];

    //uid $ rid

    $sql2 = 'select oa_users.UserID as UID,oa_depts_roles.RowID as RID,oa_users.fname,oa_users.lname,oa_depts_roles.Name,oa_users.topPersonalID from oa_users
    inner join oa_depts_roles ON (oa_users.UserID = oa_depts_roles.UserID and IsDefault = 1) where oa_users.employeeID="' . $personID . '" limit 0,1';

    $db->executeSelect($sql2);
    $person = $db->fetchAssoc();
    if ($person && intval($person['UID']) > 0 && intval($person['RID']) > 0) {

    $UID = $person['UID'];

    $RID = $person['RID'];

    $fname = $person['fname'];
    $lname = $person['lname'];
    $formID = 298;
    //بدست اوردن شماره كردشكار جاري
    $wfid = $db->executeScalar("SELECT `workflow_id` FROM `wf_workflow` WHERE `workflow_enable` = 1 AND `workflow_formtypeid` =  $formID");

    $workFlowID = $wfid;
    $acm = AccessControlManager::getInstance();
    $uid = $acm->getUserID();
    $sql2 = "select pass,OldUserID FROM  oa_users where UserID=$uid";

    $db->executeSelect($sql2);
    $person = $db->fetchAssoc();

    if ($person) {
    $pass = $person['pass'];

    $user = $person['OldUserID'];

    }

    $user = urlencode($user);
    //ايجاد فرم
    $arr = WorkFlowManager::stratNewWorkFlow($workFlowID);
    if(is_array($arr) && isset($arr['docID'])) {
    $docID = $arr['docID'];
    $referID = $arr['referID'];

    //ايجاد پيوست براي فرم ايجاد شده
    $did = $docID;
    $myFile = $pathst . $real_name;

    $aws_fcontent = base64_encode(file_get_contents($myFile));
    $aid = DocAttach::AddAttachFroBetweenServer($did, $real_name, "image/png", $aws_fcontent, 3, 0);
    if(intval($aid) > 0) {
    //مقداردهي به فيلدهاي فرم

    $yeartmp = intval($year) - 93;
    $mahtmp = intval($mah) - 1;

    $formData = ["5113" => "$yeartmp", "5114" => "$mahtmp", "5110" => "$referID", "5111" => "$aid", "5112" => [["uid" => $UID, "rid" => $RID]]];

    $myForm = new DMSNFC_form(array('fieldid' => $formID, 'docid' => $docID, 'referid' => $referID));
    $result2 = $myForm->setData($formData);

    if ($result2) {
    $err = 'فرم با موفقيت ايجاد شد';

    $request->setParameter('docID', $docID);
    $request->setParameter('referID', $referID);
    $request->setParameter('structID', $formID);
    $request->setParameter('referNote', 'جهت استحضار');
    WorkFlowManager::resumeWorkflow($docID, $referID, '1_4');

    $userFamily = ' - ' . $lname . ' ' . $fname;

    $strHTML1 .= '<tr><td>' . $personID . '</td><td>' . $fname . '  ' . $lname . '</td><td>' . $docID . '</td><td>' . $err . '</td></tr>';

    $newTitle = $newTitle . $userFamily . ' مورخ ' . $year . '/' . $mah;

    $sql_title = "update oa_document set Subject='" . $newTitle . "' where RowID=" . $did . "  limit 1";
    $db->execute($sql_title);
    $sql = "update oa_doc_refer set ToRoleID = 0 where ParentID = $referID limit 1";
    $db->execute($sql);
    if (is_dir($pathstdes)) {
    $pfile = $pathst . $real_name;
    $desfile = $pathstdes . $real_name;
    //filerecorder::recorder("pfile:".var_export($pfile,true) ,"mm" );
    copy($pfile, $desfile);
    unlink($pfile);
    } else {
    $pfile = $pathst . $real_name;
    unlink($pfile);
    }


    } else
    $err = 'خطا در دخيره اطلاعات فرم.';
    } else {
    $err = 'خطا در در درج پيوست.';
    }
    } else {
    $err = 'خطا در در ايجاد فرايند.';
    }

    } else {
    $err = 'خطا در در دريافت اطلاعات كاربر.';
    }
    $strHTML1 .= '<tr><td>' . $real_name . '</td><td></td><td></td><td>' . $err . '</td></tr>';
    }//end if
    }//end while
    $request->setParameter('docID', $lastDocID);
    $request->setParameter('referID', $lastReferID);
    $request->setParameter('structID', $lastFormID);
    $request->setParameter('referNote', $lastReferNote);
    $strHTML1 .= '</table>';

$execution->workflow->myForm->setFieldValueByName('Field_1', $strHTML1);

}//end if
}//endif

}
}


 