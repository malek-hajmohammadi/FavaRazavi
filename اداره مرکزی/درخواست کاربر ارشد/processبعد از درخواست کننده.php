<?php
class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {

        $treeValue=$execution->workflow->myForm->getFieldValueByName( 'Field_2');
        $treeValue = json_decode(json_encode($treeValue), true);
        $treeValue=$treeValue[0];

        $execution->setVariable('band', $treeValue);
        /////
         /*برای ساخت موضوع*/
        $Creator = $execution->workflow->myForm->getCreator();
        $UID = $Creator['uid'];
        $RID = $Creator['rid'];
        $user[0]['uid'] = $UID;
        $user[0]['rid'] = $RID;
        $execution->workflow->myForm->setFieldValueByName('Field_0', $user);

        $docID = $execution->workflow->myForm->instanceID;
        $db = MySQLAdapter::getInstance();

        $sql2 = "SELECT * FROM oa_users WHERE UserID=" . $UID;
        $db->executeSelect($sql2);
        $person = $db->fetchAssoc();

        if ($person['sex'] == 1) $sex = 'آقاي';
        else if ($person['sex'] == 2) $sex = 'خانم';
        else $sex = '';
        $name = $sex . ' ' . $person['lname'];
        $prefix='درخواست خدمات کاربر ارشد - ';
        switch($treeValue){
            case "1":
                $newTitle=$prefix.'اطلاع رسانی';
                break;
            case "2":
                $newTitle=$prefix.$name;
                break;
            case "3":
                $newTitle=$prefix.$name;
                break;
            case "4":
                $newTitle=$prefix.'مکاتبه';
                break;
        }

        $sql_title = "UPDATE oa_document SET DocDesc='" . $newTitle . "', Subject='" . $newTitle . "' WHERE RowID=" . $docID . "  limit 1";
        $db->execute($sql_title);

    }

}
