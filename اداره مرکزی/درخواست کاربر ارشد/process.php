<?php
class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {

        $treeValue=$execution->workflow->myForm->getFieldValueByName( 'Field_2');
        $treeValue = json_decode(json_encode($treeValue), true);
        $treeValue=$treeValue[0];

        $execution->setVariable('band', $treeValue);
    }

}
