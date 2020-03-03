<?php
class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {

        
         //مرحله
         $execution->workflow->myForm->setFieldValueByName('Field_3','readOnly');
 
        
    }

}