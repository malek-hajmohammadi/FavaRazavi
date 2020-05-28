<?php


class calssName // do not change this line
{
    //protected $variable = null; define vars sample

    public function __construct()
    {
        // must be empty
    }

    public function execute(ezcWorkflowExecution $execution) // $execution refer to active workflow that call this class
    {

        $first = $execution->getVariable('first-run');
        if ($first != '1') {


            $u = $execution->workflow->myForm->getFieldValueByName('Field_0');
            $uid = $u[0]['uid'];
            $rid = $u[0]['rid'];

            $employeeID = '';
            $sql = "SELECT fname,lname,employeeID,sex,mobile FROM oa_users WHERE UserID=" . $uid;
            $db = MySQLAdapter::getInstance();
            $db->executeSelect($sql);
            $person = $db->fetchAssoc();
            if ($person) {
                $employeeID = $person['employeeID'];
            }

            $execution->workflow->myForm->setFieldValueByName('Field_1', $employeeID);
            $level = intval(Chart::getLevel($rid));

            $execution->setVariable('has-boss', '1');/*if($level > 34)
			else $execution->setVariable('has-boss', '0' );*/

            $execution->setVariable('first-run', '1');
            /////اصلاح حاج محمدی : جهت افزودن نام و تاریخ متقاضی در موضوع و چکیده نامه////

            $name=$person['fname']." ".$person['lname'];
            $date=$execution->workflow->myForm->getFieldValueByName('Field_12');
            $subject="درخواست اصلاح تردد ".$name;
            if (strlen($date)>5)
                $subject.=" مورخه ".$date;
            MySQLAdapter::getInstance()->execute("UPDATE oa_document SET Subject = '$subject', DocDesc='$subject' WHERE RowID = " . $execution->workflow->myForm->instanceID);

            ///اتمام ////
        }


        // code body
    }

    //protected function someMethod(){} // if code must be modular you can define some mothods and use it in execute
}

