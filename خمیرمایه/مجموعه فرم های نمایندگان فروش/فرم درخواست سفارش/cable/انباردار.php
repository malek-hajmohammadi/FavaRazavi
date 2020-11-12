<?php


class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {
        self::setStage($execution);

    }

    protected function setStage($execution)
    {
        $execution->workflow->myForm->setFieldValueByName('Field_5', "anbardar");

    }

}



