<?php

class calssName
{
    public function __construct(){}
    public function execute(ezcWorkflowExecution $execution)
    {

        $execution->workflow->myForm->setFieldValueByName( 'Field_12', 1); //مرحله//
        $execution->workflow->myForm->setFieldValueByName('Field_8', 1);//کدام مهمانسرا//
        $deptID = $execution->workflow->myForm->getFieldValueByName('Field_0');
        if(intval($deptID) <= 0) {
            $rid = AccessControlManager::getInstance()->getRoleID();
            $db = MySQLAdapter::getInstance();
            $docID = $execution->workflow->myForm->instanceID;

            $depts = array(3438,3439,3440,3441,3442,3443,3567,6488,8674,9533,3433,9724,9548,3464,3444);
            $selectedDept = 0;
            foreach ($depts as $deptID){
                $sql = "select RowID 
                        from oa_depts_roles
                        WHERE RowID = $rid and path like '%/$deptID/%'
                    ";
                $test = $db->executeScalar($sql);
                if(intval($test) > 0){
                    $execution->workflow->myForm->setFieldValueByName('Field_0', $deptID);
                    $selectedDept = $deptID;
                    break;
                }
            }

            if($selectedDept) {
                $sql = "select Name 
                        from oa_depts_roles
                        WHERE RowID = $selectedDept";
                $name = $db->executeScalar($sql);
                $dept = ' حوزه ' . $name;
                $sql = "update oa_document set Subject = concat(Subject,'$dept') where RowID = $docID";
                $db->execute($sql);
            }

        }

    }
}

?>
