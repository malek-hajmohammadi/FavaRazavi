<?php

class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {


        $execution->workflow->myForm->setFieldValueByName('Field_16', "2");//ست کردن مرحله//


    }

}


?>


