<?php

class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {

        //برای اینکه کد حوزه سمت لاگین شده رو در فرم نشون بده//
        $rid = AccessControlManager::getInstance()->getRoleID(); // سمت شخص جاری رو می گیره//
        $db = MySQLAdapter::getInstance(); // برای اجرای کوئری ها//
        $sql = "select deptID from oa_depts_roles where RowID=$rid";
        $deptID = $db->executeScalar($sql);
        $execution->workflow->myForm->setFieldValueByName('Field_0', $deptID);
        $execution->workflow->myForm->setFieldValueByName('Field_16', "1");//ست کردن مرحله//


    }

}


?>

