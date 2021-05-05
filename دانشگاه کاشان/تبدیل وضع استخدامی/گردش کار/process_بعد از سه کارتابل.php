<?php
class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {
        $fillNumber=$execution->getVariable('fillFormFlag');
        $fillNumber++;
        $execution->setVariable('fillFormFlag',$fillNumber);
    }


}
