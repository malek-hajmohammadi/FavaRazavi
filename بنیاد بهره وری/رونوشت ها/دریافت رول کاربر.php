<?php

class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {
        $ACM = AccessControlManager::getInstance();
        $RID = $ACM->getRoleID();
        $execution->setVariable('applicantRoleId', $RID);

    }

}


