<?php
///////////////////////حالت جدید که با کلاس هست
class MainAjax
{


    public function main()
    {
        $output="";
        return $output;
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

