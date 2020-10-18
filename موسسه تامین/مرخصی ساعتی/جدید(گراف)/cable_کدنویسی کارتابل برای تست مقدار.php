<?php
class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {
        $temp=$execution->getVariable('statuslevel');
        $execution->workflow->myForm->setFieldValueByName('Field_4',$temp);


        /*Insert Code  Here*/
    }


}

