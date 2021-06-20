<?php


class MainAjax
{
    /*
     * Malek Hajmohammadi
     * 1400/03/26
     * */


    public function main()
    {
        $output = "";
        $this->a();
        return $output;

    }

    private function a()
    {
        $a = "malek";
    }

}

$mainAjax = new MainAjax();
Response::getInstance()->response = $mainAjax->main();
return $mainAjax;



