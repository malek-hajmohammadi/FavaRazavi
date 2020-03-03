<?php
class calssName
{
    public function __construct(){}
    public function execute(ezcWorkflowExecution $execution, ezcWorkflowNodeAction $caller = null)
    {

        $docID = $execution->workflow->myForm->instanceID;

        WorkFlowSecRegister::regOutForm($docID);
        $regInfo= Letter::GetRegInfo($docID);
    }
}
?>
