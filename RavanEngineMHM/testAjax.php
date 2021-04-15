<?php
class MainAjax
{


    public function main()
    {
        $output="";
        $output=$this->a();
        return $output;

    }

    private function a(){
        $date = new DateTime("now", new DateTimeZone("Asia/Tehran"));
        $timezone = $date->format("H:i");

        $vade1=0;
        $vade2=3;
        $date = new DateTime("now", new DateTimeZone("Asia/Tehran"));
        $timezone = $date->format("H");


        if (($vade1 == 0)||($vade2 == 0) && $timezone>=14 )
        {
            return ('کاربرگرامی از ساعت 14 به بعد درخواست فرم غذا برای ناهار امکان پذیر نمی باشد');

        }
        else
            return false;


    }

}

$mainAjax = new MainAjax();
Response::getInstance()->response = $mainAjax->main();
return $mainAjax;



