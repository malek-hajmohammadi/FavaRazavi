<?php


class calssName
{
    public function __construct()
    {
    }


    public function execute(ezcWorkflowExecution $execution)
    {
        //---added by abdollahi 951216 ---------
        $acm = AccessControlManager::getInstance();
        $creatorUID = '' . ($acm->getUserID());
        //--------------------------------------
        $message = 'نتیجه اجرای عملیات برای كاربران';
        $strHTML1 = '<h5>' . $message . '</h5><table dir="rtl" style="" cellpadding="1" cellspacing="0" border="1" >';
        $strHTML1 .= '<tr><th>شماره پرسنلي</th><th width="120"> نام و نام خانوادگي</th><th>سمت</th><th>رئيس</th><th>شماره فرم</th><th>نتیجه عملیات</th></tr>';
        //---------------------------------------
        //$pathst="/datastorage/form-storage/ahkam/";
        $pathst = "/opt/storage/Ahkam/";
        $pathstdes = "/opt/storage/Ahkam/SendedFiles/";
        //$pathstdes="./SendedFiles/";

        if (is_dir($pathst)) {
            if ($handle = opendir($pathst)) {
                while (false !== ($file = readdir($handle))) {
                    # Make sure we don't push parental directories or dotfiles (unix) into the arrays
                    if ($file != "." && $file != ".." && $file[0] != ".") {

                        $real_name = $file;
                        $newTitle = "فرم احكام ";
                        $file_arr = explode(".", $file);
                        $fileARR = explode('_', trim($file_arr[0]));
                        //شماره پرسنلي
                        $personID = $fileARR[0];
                        $personID = intval($personID);
                        if (strlen($personID) == 3)
                            $personID = '0' . $personID;
                        $level_person = $fileARR[3];
                        $type = $fileARR[2];

                        //uid $ rid
                        $db = WFPDOAdapter::getInstance();
                        //$sql2 = 'select oa_users.UserID as UID,oa_depts_roles.RowID as RID,oa_users.fname,oa_users.lname,oa_depts_roles.Name from oa_users left Outer join oa_depts_roles ON (oa_users.UserID = oa_depts_roles.UserID ) where oa_users.employeeID="'.$personID.'" limit 0,1';
                        $sql2 = 'select oa_users.UserID as UID,oa_depts_roles.RowID as RID,oa_users.fname,oa_users.lname,oa_depts_roles.Name,oa_users.topPersonalID from oa_users left Outer join oa_depts_roles ON (oa_users.UserID = oa_depts_roles.UserID ) where oa_users.employeeID="' . $personID . '" limit 0,1';
                        $db->executeSelect($sql2);
                        $person = $db->fetchAssoc();
                        if ($person) {
                            $UID = $person['UID'];

                            $RID = $person['RID'];
                            $RIDSH = 0;
                            $RIDtest = $RID;
                            do {
                                $recs = array();
                                $recs[] = Chart::getTopRole($RIDtest, 1);
                                $parent_UID = $recs[0]['UID'];
                                $parent_RID = $recs[0]['RID'];
                                $sql6 = 'SELECT oa_users.fname, oa_users.lname, oa_depts_roles.level  FROM  oa_users,oa_depts_roles WHERE  oa_users.UserID =' . intval($parent_UID) . '  and  oa_users.UserID=oa_depts_roles.UserID LIMIT 1';
                                $db->executeSelect($sql6);
                                if ($row = $db->fetchAssoc()) {
                                    $parent_fname = $row['fname'];
                                    $parent_lname = $row['lname'];
                                    $level = $row['level'];
                                }
                                $RIDtest = $parent_RID;
                            } while (!(intval($level) >= 0 && intval($level) <= 34));

                            //واحد سازماني
                            $sql4 = 'SELECT oa_depts_roles.Name AS value FROM oa_depts_roles  INNER JOIN oa_depts_roles odr  ON (odr.DeptID=oa_depts_roles.RowID) WHERE oa_depts_roles.RowType = 2   AND odr.UserID=' . $UID . '  AND odr.RowID=' . $RID . '  AND oa_depts_roles.IsEnable = 1   AND odr.path LIKE CONCAT("%/", oa_depts_roles.RowID, "/%")  GROUP BY oa_depts_roles.RowID  ORDER BY LENGTH(oa_depts_roles.path) DESC LIMIT 1';
                            $vahed = WFPDOAdapter::getInstance()->executeScalar($sql4);

                            $formID = 277;
                            //بدست اوردن شماره كردشكار جاري
                            $db = WFPDOAdapter::getInstance();
                            $wfid = $db->executeScalar("SELECT MAX(`workflow_id`) FROM `wf_workflow` WHERE `workflow_enable` = 1 AND `workflow_formtypeid` =  $formID");
                            $workFlowID = $wfid;

                            //ايجاد فرم
                            $arr = WorkFlowManager::stratNewWorkFlow($workFlowID);
                            $docID = $arr['docID'];
                            $referID = $arr['referID'];
                            //ايحاد پيوست براي فرم ايجاد شده
                            $did = $docID;
                            $myFile = $pathst . $real_name;

                            $aws_fcontent = base64_encode(file_get_contents($myFile));
                            $aid = DocAttach::AddAttachFroBetweenServer($did, $real_name, "image/png", $aws_fcontent, 3, 0);

                            //نعيين اندازه ارتفاع فرم
                            //	$char=substr($personID,0,1);
                            switch ($type) {
                                case '2':

                                    $top = "1020";
                                    $left = "510";
                                    break;
                                case '3':
                                case '4':
                                    $top = "1050";
                                    $left = "260";
                                    break;
                                case '1':
                                case '5':
                                    $top = "950";
                                    $left = "260";
                                    break;


                                default :
                                    $top = "950";
                                    $left = "260";
                                    break;

                            }
                            //مقداردهي به فيلدهاي فرم
                            //set data in form

                            $fdata = array(
                                "4842" => "$referID",
                                "4843" => "$aid",
                                "4850" => "$top",
                                "4851" => "$left",
                                "4854" => array(array("uid" => $UID, "rid" => $RID)),
                                "7367" => array(array("uid" => $UID, "rid" => $RIDSH)),
                                "4858" => "$level_person",
                                "4859" => array(array("uid" => $parent_UID, "rid" => $parent_RID)),
                                "4863" => "$vahed"
                            );

                            $myForm = new DMSNFC_form(array('fieldid' => $formID, 'docid' => $docID, 'referid' => null));
                            $result2 = $myForm->setData($fdata);

                            if ($result2)
                                $err = 'فرم با موفقيت ايجاد شد';
                            else
                                $err = 'خطا در ايحاد فرم.';

                            switch ($level_person) {
                                /*case '01':
                                case '02':*/
                                case '03':
                                    $level = "مجموعه";
                                    break;
                                /*case '04':
                                    $level="سرپرست";
                                    break;*/
                                case '05':
                                    $level = "مديريت";
                                    break;
                                case '06':
                                    $level = "رئيس اداره";
                                    break;
                                case '07':
                                    $level = "رئيس بخش";
                                    break;
                                case '08':
                                    $level = "رئيس دفتر";
                                    break;
                                case '09':
                                    $level = "رئيس دفترمديريت";
                                    break;
                                case '10':
                                    $level = "معاون اداره";
                                    break;
                                case '11':
                                    $level = "معاون مديريت";
                                    break;
                                case '12':
                                    $level = "مسئول";
                                    break;
                                case '13':
                                    $level = "سرپرست دفترنمايندگي";
                                    break;
                                case '14':
                                    $level = "هيئت امنا";
                                    break;
                                case '15':
                                    $level = "مشاغل";
                                    break;
                                case '16':
                                    $level = "رئيس دفترهيئت امنا";
                                    break;
                                case '17':
                                    $level = "مدير عامل";
                                    break;
                                case '18':
                                    $level = "مشاور";
                                    break;
                                case '19':
                                    $level = "رئيس دبيرخانه معاونت";
                                    break;
                                case '20':
                                    $level = "رئيس دفترمعاونت";
                                    break;
                                case '21':
                                    $level = "رئيس دفترنمايندگي";
                                    break;
                                case '22':
                                    $level = "سرپرست";
                                    break;


                            }
                            // $userFamily= ' _' . $personID . '_ ' . $person['lname'] . ' ' . $person['fname'] . ' ( ' . $level . ' )';
                            $userFamily = ' _' . $personID . '_ ' . $person['lname'] . ' ' . $person['fname'];
                            $strHTML1 .= '<tr><td>' . $personID . '</td><td>' . $person["fname"] . '  ' . $person["lname"] . '</td><td>' . $person["Name"] . '</td><td>' . $parent_fname . '  ' . $parent_lname . '</td><td>' . $docID . '</td><td>' . $err . '</td></tr>';
                            $newTitle = $newTitle . $userFamily;
                            $sql_title = "update oa_document set Subject='" . $newTitle . "' where RowID=" . $did . "  limit 1";
                            $db->execute($sql_title);
                            if (is_dir($pathstdes)) {
                                $pfile = $pathst . $real_name;
                                $desfile = $pathstdes . $real_name;
                                copy($pfile, $desfile);
                                unlink($pfile);
                            } else {
                                $pfile = $pathst . $real_name;
                                unlink($pfile);
                            }
                        } else {
                            $err = 'خطا در ايحاد فرم.';
                            $strHTML1 .= '<tr><td>' . $real_name . '</td><td></td><td></td><td></td><td></td><td>' . $err . '</td></tr>';
                        }
                    }//end if
                    else {
                        $err = 'خطا در ايحاد فرم.';
                        $strHTML1 .= '<tr><td>' . $real_name . '</td><td></td><td></td><td></td><td></td><td>' . $err . '</td></tr>';
                    }

                }//end while
                $strHTML1 .= '</table>';
                $execution->workflow->myForm->setFieldValueByName('Field_1', $strHTML1);

            }//end if
        }//endif
    }
}


