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
        $execution->workflow->myForm->setFieldValueByName('Field_12', 0);

        $first = $execution->getVariable('first-run');

        filerecorder::recorder("first process", "homaaa123");

        if ($first != '1') {

            $execution->workflow->myForm->setFieldValueByName('Field_14', 'حوزه :');
            $execution->workflow->myForm->setFieldValueByName('Field_15', 'سال :');
            $execution->workflow->myForm->setFieldValueByName('Field_16', 'ماه :');
            $execution->workflow->myForm->setFieldValueByName('Field_10', 'کاربر  متصدی اداره/ مسئول دفتر :');
//$execution->workflow->myForm->setFieldValueByName( 'Field_20','شماره پرسنلی را جهت افزودن وارد نمایید:');

            $today = date("Y-m-d");

            $gToday = Date::GregToJalaliDisplay($today);

            $m = explode("/", $gToday);
            $k = intval($m[0]) - 1395;
            $kk = intval($m[1]) - 2;
            if ($k < 0) $k = 0;
            if ($kk < 0) $kk = 0;
            $execution->workflow->myForm->setFieldValueByName('Field_3', $k);

            $execution->workflow->myForm->setFieldValueByName('Field_4', $kk);
            $execution->setVariable('first-run', '1');


        }


        filerecorder::recorder("end process", "homaaa123");
        // code body
    }

    //protected function someMethod(){} // if code must be modular you can define some mothods and use it in execute
}


