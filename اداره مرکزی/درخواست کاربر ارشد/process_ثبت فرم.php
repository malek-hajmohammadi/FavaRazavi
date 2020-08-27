<?php
class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {
        /*جهت دریافت شماره ثبتی و گذاشتن در فرم*/
        $docID= $execution->workflow->myForm->instanceID;
        WorkFlowSecRegister::regOutForm($docID);
        $res = Letter::GetRegInfo($docID);
        $regCode = $res['completeRegCode'];
        $execution->workflow->myForm->setFieldValueByName('Field_7', $regCode);
        /*انتهای دریافت شماره ثبتی و گذاشتن در فرم*/

    }


}




