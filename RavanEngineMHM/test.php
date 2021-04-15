<?php

class calssName
{
    public function execute($self)
    {
       /* $Vade1 = FormView . myForm . getItemByName('Field_2') . getData();
        $Vade2 = FormView . myForm . getItemByName('Field_7') . getData();*/
        $vade1=2;
        $vade2=3;
        $date = new DateTime("now", new DateTimeZone("Asia/Tehran"));
        $timezone = $date->format("H:i");
        if (($vade1 == 0)||($vade2 == 0) && $timezone="14:00" )
    {
                      return ('کاربرگرامی از ساعت 14 به بعد درخواست فرم غذا برای ناهار امکان پذیر نمی باشد');
      return false;
              }





      /*return array("res" => true);*/}
}


class calssName // do not change this line
{
    //protected $variable = null; define vars sample

    public function __construct()
    {
        // must be empty
    }

    public function execute(ezcWorkflowExecution $execution) // $execution refer to active workflow that call this class
    {

        $date = new DateTime("now", new DateTimeZone("Asia/Tehran"));
        $timezone = $date->format("H:i");

        $execution->workflow->myForm->setFieldValueByName('Field_13', $timezone);
    }

    //protected function someMethod(){} // if code must be modular you can define some mothods and use it in execute
}






