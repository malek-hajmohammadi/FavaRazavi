<?php
/*--کد نویسی کارتابل برای داشتن مرحله یا کارتابل فعال--*/
$execution->workflow->myForm->setFieldValueByName('Field_1',$user);

class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {

        $execution->workflow->myForm->setFieldValueByName('Field_20',0);


    }
}
