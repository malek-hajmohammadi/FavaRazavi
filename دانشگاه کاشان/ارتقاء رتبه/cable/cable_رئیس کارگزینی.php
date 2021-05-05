<?php
class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {
        $execution->setVariable('needToSetUpWorkGroupUsers',1);
    }


}
