<?php
class calssName
{

    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {

        $actors = $execution->getVariable('actors');

        $actor = $execution->workflow->myForm->getFieldValueByName('Field_20');
        if (is_array($actors)) {
            array_push($actors, $actor[0]);
        } else
            $actors = $actor;

        $execution->setVariable('actors', $actors);
    }
}


