<?php

class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {
        $res = WorkFlowAjaxFunc::getCreatorLevel();
        Response::getInstance()->response = $res;

    }




}
