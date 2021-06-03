<?php

class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution, ezcWorkflowNodeAction $caller)
    {

        $caller->setReferNote("امکان ثبت اصلاح تردد بیش از 8 بار در یک ماه میسر نمی باشد.");
    }


}

