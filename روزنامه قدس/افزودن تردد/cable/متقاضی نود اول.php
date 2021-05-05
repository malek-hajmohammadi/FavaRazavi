<?php


class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution, ezcWorkflowNodeAction $caller = null)
    {

        if ($execution->workflow->myForm->getFieldValueByName('Field_0') == null || $execution->workflow->myForm->getFieldValueByName('Field_0') == "") {
            $rid = AccessControlManager::getInstance()->getRoleID();
            $uid = AccessControlManager::getInstance()->getUserID();

            $q = "SELECT employeeID from oa_users where UserID=" . $uid;
            $db = WFPDOAdapter::getInstance();
            $result = $db->executeScalar($q);
            $execution->workflow->myForm->setFieldValueByName('Field_0', $result);

        }

    }
}

