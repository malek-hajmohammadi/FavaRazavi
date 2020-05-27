<?php

class calssName // do not change this line
{
    //protected $variable = null; define vars sample

    public function __construct()
    {
        // must be empty
    }

    public function execute(ezcWorkflowExecution $execution) // $execution refer to active workflow that call this class
    {
        $person = $execution->workflow->myForm->getFieldValueByName('Field_0');
        $recs = array();
        $recs[] = Chart::getTopRole($person[0]['rid'], 1);
        $execution->workflow->myForm->setFieldValueByName('Field_14', $recs);

    }


}


