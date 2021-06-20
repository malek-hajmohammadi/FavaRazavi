<?php


class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution, ezcWorkflowNodeAction $caller = null)
    {

        if ($execution->workflow->myForm->getFieldValueByName('Field_4') == null || $execution->workflow->myForm->getFieldValueByName('Field_4') == "") {
            $rid = AccessControlManager::getInstance()->getRoleID();
            $uid = AccessControlManager::getInstance()->getUserID();

            $employeeID="";
            $sql = "SELECT fname,lname,employeeID,sex,mobile FROM oa_users WHERE UserID=" . $uid;
            $db = WFPDOAdapter::getInstance();
            $db->executeSelect($sql);
            $person = $db->fetchAssoc();
            if ($person) {
                $employeeID = $person['employeeID'];
                $name=$person['fname']." ".$person['lname'];

                $subject="درخواست مرخصی روزانه ".$name;

                WFPDOAdapter::getInstance()->execute("UPDATE oa_document SET Subject = '$subject', DocDesc='$subject' WHERE RowID = " . $execution->workflow->myForm->instanceID . " limit 1;");
            }

            $execution->workflow->myForm->setFieldValueByName('Field_4', $employeeID);



        }

    }


}

