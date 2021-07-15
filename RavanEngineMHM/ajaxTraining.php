<?php

class MainAjax
{


    public function main()
    {
        $output="";
        $this->a();
        return $output;

    }

    private function a(){
        $a="malek";
    }

}

$mainAjax = new MainAjax();
Response::getInstance()->response = $mainAjax->main();
return $mainAjax;


/////////////////////////////////////////////////

class MainAjax
{
    private $resultJson=[];


    public function main()
    {
        $output = "";
        $this->a();
        return $output;

    }
    private function test(){

    }

    private function a()
    {
        $a = "malek";
    }

}

$mainAjax = new MainAjax();
Response::getInstance()->response = $mainAjax->main();
return $mainAjax;






























/////////////////////////

//دریافت پارامتر از بیرون که بهتره اول چک کنیم که چنین مقداری در فراخوانی هست یا نه//
if (Request::getInstance()->varCleanFromInput('placeHolder'))
    $varTemp = Request::getInstance()->varCleanFromInput('placeHolder');
else {

    Response::getInstance()->response = "There is no specefic input";
    return;
}

//ارسال خروجی به بیرون//

$a="PHP";

Response::getInstance()->response=$a;

//آرایه ها و لیست ها رو هم به همین صورت می دیم به خروجی ، که آنطرف بصورت آبجکتی از json می شه استفاده کرد//

