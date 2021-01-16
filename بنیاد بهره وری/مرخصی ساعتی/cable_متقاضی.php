<?php
class calssName
{
    public function __construct(){}
    public function execute(ezcWorkflowExecution $execution, ezcWorkflowNodeAction $caller = null)
    {
        $Creator = $execution->workflow->myForm->getCreator();
        $UID = $Creator['uid'];
        $RID = $Creator['rid'];
        $user[0]['uid'] = $UID;
        $user[0]['rid'] = $RID;
        $execution->workflow->myForm->setFieldValueByName('Field_0',$user);

        $docID = $execution->workflow->myForm->instanceID;
        $db = MySQLAdapter::getInstance();

        $sql2 = "SELECT fname,lname,sex,employeeID FROM oa_users WHERE UserID=".$UID;
        $db->executeSelect($sql2);
        $person = $db->fetchAssoc();
        $PID = $person['employeeID'];
        $execution->workflow->myForm->setFieldValueByName('Field_7', $PID);

       // $client = new SoapClient('http://192.168.5.96/Timex.asmx?wsdl');
        $client = new SoapClient('http://185.23.128.168/Timex.asmx?wsdl');
        $s = "SELECT [empid] 
        FROM [Timex].[adon].[Tblprs] 
        WHERE cardno = $PID";
        $param = array(
            'username' => '3ef1b48067e4f2ac9913141d77e847dd',
            'password' => '9a3f5b14f1737c15e86680d9cd40b840',
            'objStr' => $s
        );
        $resp1 = $client->RunSelectQuery($param);
        $recs = $resp1->RunSelectQueryResult->cols->recs->string;
        //Response::getInstance()->response = $recs;
        $GID = $recs;
        $execution->workflow->myForm->setFieldValueByName('Field_12', $GID);

        if($person['sex'] == 1) $sex = 'آقاي';
        else if($person['sex'] == 2) $sex = 'خانم';
        else $sex = '';
        $Name = $sex.' '.$person['lname'];

        $newTitle = 'مرخصی ساعتی (سازمان) '.$Name;
        $sql_title = "UPDATE oa_document SET DocDesc='".$newTitle."',Subject='".$newTitle."' WHERE RowID=".$docID."  limit 1";
        $db->execute($sql_title);

        $execution->workflow->myForm->setFieldValueByName('Field_10','درج نشده است');
        $execution->workflow->myForm->setFieldValueByName('Field_8','1');
    }
}
?>
