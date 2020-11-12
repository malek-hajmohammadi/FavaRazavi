<?php




class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {
        self::setStage($execution);
        self::sendSMS($execution);

    }

    protected function setStage($execution){
        $execution->workflow->myForm->setFieldValueByName('Field_5', "raiesFroosh");

    }

    protected function sendSMS($execution){
        $message="ضمن تشکر, سفارش شما در سامانه فروش شرکت خمیرمایه رضوی ثبت شد";
        $url = "https://ippanel.com/services.jspd";

        /*برای استفاده در sms*/
        $phone=$execution->getVariable('repMobile');

        $rcpt_nm = array($phone);
        $param = array
        (
            'uname'=>'9151157075',
            'pass'=>'A13581360a@',
            'from'=>'3000505',
            'message'=>$message,
            'to'=>json_encode($rcpt_nm),
            'op'=>'send'
        );

        $handler = curl_init($url);
        curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($handler, CURLOPT_POSTFIELDS, $param);
        curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
        $response2 = curl_exec($handler);
/*
        $response2 = json_decode($response2);
        $res_code = $response2[0];
        $res_data = $response2[1];


        echo $res_data;*/

    }

}


