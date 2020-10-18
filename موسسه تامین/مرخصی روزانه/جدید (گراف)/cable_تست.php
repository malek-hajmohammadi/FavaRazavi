<?php
class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {
        $topRole=$execution->getVariable('toprole');
        $execution->workflow->myForm->setFieldValueByName('Field_13', $topRole);
    }


}
