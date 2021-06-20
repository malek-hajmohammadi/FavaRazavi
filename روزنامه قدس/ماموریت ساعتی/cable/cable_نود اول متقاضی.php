<?php

class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {


        if ($execution->workflow->myForm->getFieldValueByName('Field_1') == null || $execution->workflow->myForm->getFieldValueByName('Field_1') == "") {
            $ACM = AccessControlManager::getInstance();
            //$RID = $ACM->getRoleID();
            $userId = $ACM->getUserID();


            $employeeID = '';
            $sql = "SELECT fname,lname,employeeID,sex,mobile FROM oa_users WHERE UserID=" . $userId;
            $db = WFPDOAdapter::getInstance();
            $db->executeSelect($sql);
            $person = $db->fetchAssoc();
            if ($person) {
                $employeeID = $person['employeeID'];
            }

            $execution->workflow->myForm->setFieldValueByName('Field_1', $employeeID);

            /////اصلاح حاج محمدی : جهت افزودن نام و تاریخ متقاضی در موضوع و چکیده نامه////

            $name = $person['fname'] . " " . $person['lname'];

            $subject = "درخواست ماموریت ساعتی " . $name;

            WFPDOAdapter::getInstance()->execute("UPDATE oa_document SET Subject = '$subject', DocDesc='$subject' WHERE RowID = " . $execution->workflow->myForm->instanceID . " limit 1;");

            ///اتمام ////
        }

    }

}


