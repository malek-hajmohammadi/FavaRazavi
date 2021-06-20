<?php


class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution, ezcWorkflowNodeAction $caller = null)
    {

        if ($execution->workflow->myForm->getFieldValueByName('Field_0') == null || $execution->workflow->myForm->getFieldValueByName('Field_0') == "") {
            $ACM = AccessControlManager::getInstance();
            //$RID = $ACM->getRoleID();
            $uId = $ACM->getUserID();

            $sql = "SELECT fname,lname,employeeID from oa_users where UserID=" . $uId;
            $db = WFPDOAdapter::getInstance();
            $db->executeSelect($sql);
            $person = $db->fetchAssoc();

            if ($person) {
                $employeeID = $person['employeeID'];
            }

            $execution->workflow->myForm->setFieldValueByName('Field_0', $employeeID);

            /////اصلاح حاج محمدی : جهت افزودن نام و تاریخ متقاضی در موضوع و چکیده نامه////

            $name = $person['fname'] . " " . $person['lname'];

            $subject = "درخواست افزودن تردد " . $name;

            WFPDOAdapter::getInstance()->execute("UPDATE oa_document SET Subject = '$subject', DocDesc='$subject' WHERE RowID = " . $execution->workflow->myForm->instanceID . " limit 1;");


        }

    }
}


