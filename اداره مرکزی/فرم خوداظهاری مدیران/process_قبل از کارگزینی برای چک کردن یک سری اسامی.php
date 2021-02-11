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
    private $userArray=[];
    private $execution;
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {
        /*تعریف کاربرانی که به درخواست بنیاد کرامت باید از کانال دیگری عبور کنند*/
        $this->execution=$execution;
        $isUserInList=$this->isUserInListManagerKeramat();

        if($isUserInList){
            $execution->setVariable('isKeramatManager', 1);
        }
        else{
            $execution->setVariable('isKeramatManager', 0);

        }




    }
     private function isUserInListManagerKeramat(){

         $this->fillUserArray();
         $currentUserId= $this->execution->workflow->myForm->getFieldValueByName('Field_13');
         foreach ($this->userArray as $itemInList){
             if($currentUserId==$itemInList)
                 return true;
         }
         return false;
     }


    private function fillUserArray(){

        array_push($this->userArray,7316);
        array_push($this->userArray,6374);
        array_push($this->userArray,7444);
        array_push($this->userArray,10536);
        array_push($this->userArray,7408);
        array_push($this->userArray,5166);

    }

}


