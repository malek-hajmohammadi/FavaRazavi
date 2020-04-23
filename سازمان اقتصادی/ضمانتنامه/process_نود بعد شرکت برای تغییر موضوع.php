<?php

class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {


        $company=$execution->workflow->myForm->getFieldValueByName( 'Field_0' );
        $subject="درخواست ضمانتنامه بانکی شرکت  $company";


        MySQLAdapter::getInstance()->execute("UPDATE oa_document SET Subject = '$subject' WHERE RowID = " . $execution->workflow->myForm->instanceID);



    }


}