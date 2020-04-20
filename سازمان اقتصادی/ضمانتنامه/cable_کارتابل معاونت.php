<?php


class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {




        $tashilat=$execution->workflow->myForm->getFieldValueByName( 'Field_2');
        $company=$execution->workflow->myForm->getFieldValueByName( 'Field_0');

        $tashilat=number_format($tashilat, 0);




        $draft="نظر به اینکه شرکت $company  متعلق به آستان قدس رضوی تقاضای اخذ تسهیلات به مبلغ $tashilat ریال را از آن بانک دارد، لذا بدینوسیله اعلام میدارد در صورت عدم پرداخت تعهدات توسط شرکت مذکور، این سازمان بازپرداخت بدهی فوق الذکر (اصل ، سود و وجه التزام) را در موعد مقرر تعهد و تضمین می نماید. لازم به ذکر است که این ضمانتنامه بابت یک نوبت می باشد.";
        $execution->workflow->myForm->setFieldValueByName('Field_30', $draft);//ست کردن مرحله//

        $execution->workflow->myForm->setFieldValueByName( 'Field_32', 2);


    }

}







