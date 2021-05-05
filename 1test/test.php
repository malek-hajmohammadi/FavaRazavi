<?php

class MainAjax
{


    public

    function main()
    {
        $output = "";
        $output = $this -> ifTime();
              return $output;

      }

    private

    function ifTime($wfid = "")
    {

        $db = MySQLAdapter::getInstance();
        $vade1= $execution->workflow->myForm->getFieldValueByName('Field_2');

        $vade1 = "SELECT Field_2   FROM dm_datastoretable_98   ";
        $vade2 = "SELECT Field_7   FROM dm_datastoretable_98   ";
        $flagID = $db -> executeScalar($vade1, $vade2);
              $date = new DateTime("now", new DateTimeZone("Asia/Tehran"));
              $timezone = $date -> format("H");
              if ((intval($flagID) == 0) && $timezone >= 14) {
        return ('كاربرگرامي از ساعت 14 به بعد درخواست فرم غذا براي ناهار امكان پذير نمي باشد');

    } else {
        return false;
    }
}
}


$mainAjax = new MainAjax();
    Response::getInstance() -> response = $mainAjax -> main();
return $mainAjax;


