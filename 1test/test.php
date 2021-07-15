<?php
class calssName
{
    public function __construct(){}
    public function execute(ezcWorkflowExecution $execution, ezcWorkflowNodeAction $caller = null)
    {

        $tamir = $execution->workflow->myForm->getFieldValueByName('Field_4');//فیلد تعمیر

        if ($tamir == 1)//اگر فیلد تعمیر دارد باشد
            $execution->setVariable('choice',1);//متغییر خط انتخاب شود خط 1
        if ($tamir == 2) //اگر فیلد تعمیر ندارد باشد
            $execution->setVariable('choice',10);//متغییر خط انتخاب ست شود 10

        $dakhelOrKHarej = $execution->workflow->myForm->getFieldValueByName('Field_6');//فیلد داخل کارخانه/بیرون

        if ($dakhelOrKHarej == 1) //اگر فیلد تعمیر داخل کارخانه دارد باشد
            $execution->setVariable('choice',20);//متفییر خط انتخاب ست شود 20
        if ($dakhelOrKHarej == 2)   //اگر فیلد تعمیر داخل کارخانه ندارد باشد
            $execution->setVariable('choice',30);//متفییر خط انتخاب ست شود 30


        $Kharid = $execution->workflow->myForm->getFieldValueByName('Field_5');

        if ($Kharid == 1) //اگر فیلد خرید دارد باشد
            $execution->setVariable('choice',40);//متفییر خط انتخاب ست شود 40
        if ($Kharid == 2)//اگر فیلد خرید ندارد باشد
            $execution->setVariable('choice', 50);//متفییر خط انتخاب ست شود 50

    }//end func
}
?>