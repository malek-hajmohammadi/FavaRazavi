<?php

class calssName
{

    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {
        $date = Date::GregToJalali(date("Y-m-d"));


        $execution->workflow->myForm->setFieldValueByName('Field_29', $date);

        date("h:i:sa");

        $execution->workflow->myForm->setFieldValueByName('Field_30', date("h:i:sa"));
    }//end func

}

