<?php


class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {
        //body//
        $execution->workflow->myForm->setFieldValueByName('Field_9', "level3");


    }
}


