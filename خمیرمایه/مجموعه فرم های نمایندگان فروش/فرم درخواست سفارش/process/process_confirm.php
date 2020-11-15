<?php
class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {
        /*فلگی که  بعدا چک می کنم که ایا سفارش تایید شده هنوز یا نه*/
        $execution->workflow->myForm->setFieldValueByName('Field_16', 1);
    }


}

