<?php


class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution, ezcWorkflowNodeAction $caller = null)
    {


        $beginDateAndTime = $execution->workflow->myForm->getFieldValueByName('Field_1');
        $endDate = $execution->workflow->myForm->getFieldValueByName('Field_2');

        $beginDateArray=explode(' ',$beginDateAndTime);


        $execution->workflow->myForm->setFieldValueByName('Field_3', $beginDateArray[0]);


    }
}


