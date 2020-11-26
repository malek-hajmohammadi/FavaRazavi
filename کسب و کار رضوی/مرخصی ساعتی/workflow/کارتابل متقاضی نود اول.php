<?php
class calssName
{
    public function __construct(){}
    public function execute(ezcWorkflowExecution $execution)
    {
        $Creator = $execution->workflow->myForm->getCreator();
        $uid = $Creator['uid'];
        $rid = $Creator['rid'];
        $docID = $execution->workflow->myForm->instanceID;
        $db = MySQLAdapter::getInstance();

        $sql2 = "SELECT fname,lname,sex,employeeID FROM oa_users WHERE UserID=".$uid;
        $db->executeSelect($sql2);
        $person = $db->fetchAssoc();
        $execution->workflow->myForm->setFieldValueByName('Field_1', $person['employeeID']);
        if($person['sex'] == 1) $sex = 'آقاي';
        else if($person['sex'] == 2) $sex = 'خانم';
        else $sex = '';
        $Name = $sex.' '.$person['fname'].' '.$person['lname'];
        $SecName = '(کسب و کار رضوی)';
        $newTitle = 'مرخصی ساعتی'.' '.$SecName.' '.$Name;
        $sql_title = "update oa_document set DocDesc='".$newTitle."',Subject='".$newTitle."' where RowID=".$docID."  limit 1";
        $db->execute($sql_title);
    }
}
?>
