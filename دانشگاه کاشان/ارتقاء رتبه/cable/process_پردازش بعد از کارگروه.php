<?php
class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {

        $workGroupConfirmCount=$execution->getVariable('workGroupConfirmCount');
        $workGroupUsersSize=count( $execution->getVariable('workGroupUsers'));
        if($workGroupConfirmCount>=($workGroupUsersSize-1)){

            $execution->setVariable('confirmCountFlag', 1);
        }
        else{

            $execution->setVariable('confirmCountFlag', 0);
        }

        $execution->setVariable('workGroupConfirmCount', $workGroupConfirmCount+1);
    }

}




