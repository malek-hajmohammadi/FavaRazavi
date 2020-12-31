<?php

class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {
      $obj= ModWorkFlowAjaxFunc();

      $res=$obj->malekTest();


        Response::getInstance()->response = "malek in response and res is :".$res;

        echo($res."this is in the cable");

    }

}
