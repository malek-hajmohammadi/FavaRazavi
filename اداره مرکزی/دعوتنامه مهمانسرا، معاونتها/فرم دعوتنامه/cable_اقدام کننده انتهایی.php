<?php

class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {


        $execution->workflow->myForm->setFieldValueByName('Field_16', "5");//ست کردن مرحله//


    }

}


?>




