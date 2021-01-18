<?php

/*
 * سید وحید موسویان
 *
 * حمزه سپهریان
 * حامد صادقی
 * رضا آذریان
 * مهدی رضوی کیا
 * علی اکبر عصارنیا
 *
 */
class calssName
{
    public $userArray=[];
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {
        /*تعریف کاربرانی که به درخواست بنیاد کرامت باید از کانال دیگری عبور کنند*/
        $isUserInList=false;
        $this->fillUserArray();
        if ($this->isUserInList())
            $isUserInList=true;

    }


    private function fillUserArray(){

        array_push($this->userArray,7316);
        array_push($this->userArray,6374);
        array_push($this->userArray,7444);
        array_push($this->userArray,10536);
        array_push($this->userArray,7408);
        array_push($this->userArray,5166);

    }
    private function isUserInList(){
        $result=false;
        return $result;
    }


}


