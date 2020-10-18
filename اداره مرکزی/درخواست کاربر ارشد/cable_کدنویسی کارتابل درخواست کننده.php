<?php

class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution, ezcWorkflowNodeAction $caller = null)
    {
        $Creator = $execution->workflow->myForm->getCreator();
        $UID = $Creator['uid'];

        $RID = $Creator['rid'];
        $user[0]['uid'] = $UID;
        $user[0]['rid'] = $RID;
        $execution->workflow->myForm->setFieldValueByName('Field_0', $user);

        $ACM = AccessControlManager::getInstance();
        if(!$ACM->isAssist())

         $RId=Chart::getDefaultRoleID();
         $roleName=Chart::GetRoleName($RId);

        /*
        $docID = $execution->workflow->myForm->instanceID;
        $db = MySQLAdapter::getInstance();

        $sql2 = "SELECT * FROM oa_users WHERE UserID=" . $UID;
        $db->executeSelect($sql2);
        $person = $db->fetchAssoc();

        if ($person['sex'] == 1) $sex = 'آقاي';
        else if ($person['sex'] == 2) $sex = 'خانم';
        else $sex = '';
        $Name = $sex . ' ' . $person['lname'];

        $newTitle = 'درخواست خدمات کاربر ارشد - ' . $Name;
        $sql_title = "UPDATE oa_document SET DocDesc='" . $newTitle . "', Subject='" . $newTitle . "' WHERE RowID=" . $docID . "  limit 1";
        $db->execute($sql_title);
*/
        $execution->workflow->myForm->setFieldValueByName('Field_5', '1 / در حال ایجاد درخواست');

    }
}

