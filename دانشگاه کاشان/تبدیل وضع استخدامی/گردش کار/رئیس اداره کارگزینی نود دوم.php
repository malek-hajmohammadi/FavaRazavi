<?php

class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {
        $maFogh=$execution->workflow->myForm->getFieldValueByName( 'Field_0');
        $uid = $maFogh[0]['uid'];
        $rid = $maFogh[0]['rid'];

        $docID = $execution->workflow->myForm->instanceID;
        $db = WFPDOAdapter::getInstance();

        $sql2 = "SELECT fname,lname,sex,employeeID FROM oa_users WHERE UserID=" . $uid;
        $db->executeSelect($sql2);
        $person = $db->fetchAssoc();
        //$execution->workflow->myForm->setFieldValueByName('Field_0', $person['employeeID']);
        if ($person['sex'] == 1) $sex = 'آقاي';
        else if ($person['sex'] == 2) $sex = 'خانم';
        else $sex = '';
        $Name = $sex . ' ' . $person['fname'] . ' ' . $person['lname'];

        $newTitle = 'تبدیل وضع استخدامی  ' . ' ' . $Name;
        $sql_title = "update oa_document set DocDesc='" . $newTitle . "',Subject='" . $newTitle . "' where RowID=" . $docID . "  limit 1";
        $db->execute($sql_title);
        $execution->workflow->myForm->setFieldValueByName('Field_47', "0");
    }
}



