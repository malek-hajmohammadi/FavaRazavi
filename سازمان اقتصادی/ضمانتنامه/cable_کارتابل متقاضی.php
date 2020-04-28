<?php
class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {


        $rid = AccessControlManager::getInstance()->getRoleID();
        $db = MySQLAdapter::getInstance();
        $docID = $execution->workflow->myForm->instanceID;

        $depts = array(1848, 3159, 3158);
        $selectedDept = 0;
        $sql = "select path 
                        from oa_depts_roles
                        WHERE RowID = $rid
                    ";
        $path = $db->executeScalar($sql);
        foreach ($depts as $deptID) {

            if (strpos($path, "/$deptID/") > 0) {
                $execution->workflow->myForm->setFieldValueByName('Field_33', $deptID);
                $selectedDept = $deptID;
                break;
            }
        }

        if ($selectedDept) {
            $sql = "select Name 
                        from oa_depts_roles
                        WHERE RowID = $selectedDept";
            $name = $db->executeScalar($sql);
            $dept = ' ' . $name;
            $sql = "update oa_document set Subject = concat(Subject,'$dept') where RowID = $docID";
            $db->execute($sql);
        }

        $execution->setVariable('companyName', $dept);

        $execution->workflow->myForm->setFieldValueByName( 'Field_32', 0);

    }
}


?>


