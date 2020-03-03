<?php
class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {

        $acm = AccessControlManager::getInstance();
        $uid = $acm->getUserID();
        $rid = $acm->getRoleID();
        
        // set creator
        $creator = array(array('uid' => $uid, 'rid' => $rid));
        $execution->workflow->myForm->setFieldValueByName('Field_0', $creator);


         //برای اینکه کد حوزه سمت لاگین شده رو در فرم نشون بده//
         
         $db = MySQLAdapter::getInstance(); // برای اجرای کوئری ها//
         $sql = "select deptID from oa_depts_roles where RowID=$rid";
         $deptID = $db->executeScalar($sql);
         $execution->workflow->myForm->setFieldValueByName('Field_2', $deptID);

         //مرحله
         $execution->workflow->myForm->setFieldValueByName('Field_3','edit');
 
        
    }

}