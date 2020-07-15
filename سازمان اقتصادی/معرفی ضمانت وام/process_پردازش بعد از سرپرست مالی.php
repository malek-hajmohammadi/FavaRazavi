<?php
class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {

        /*جهت ارسال رونوشت*/
        $referID = Request::getInstance()->varCleanFromInput("referID");

        $receivers = array();
        $receivers[0] = array();
        $receivers[0]['type'] = 2;
        $receivers[0]['uid'] = 1;
        $receivers[0]['rid'] = 666;
        $receivers[0]['oid'] = 'null';
        $receivers[0]['oname'] = '';
        $receivers[0]['isCC'] = 1;
     //   $newRefer = DocRefer::ReferDocRefer($referID, $receivers, 'احتراما جهت استحضار');

//        رونوشت برای شروع کننده
        $starterUId=$execution->getVariable('uId');
        $starterRId=$execution->getVariable('rId');
        $receivers[0]['uid'] = $starterUId;
        $receivers[0]['rid'] = $starterRId;
        $newRefer = DocRefer::ReferDocRefer($referID, $receivers, 'احتراما جهت استحضار');

//        رونوشت برای رئیس حسابداری آقای رضائی
        $receivers[0]['uid'] = 2002;
        $receivers[0]['rid'] = 92;
        $newRefer = DocRefer::ReferDocRefer($referID, $receivers, 'احتراما جهت استحضار');
/*
//        /*رونوشت برای آقای برادران رحیمی سرپرست طرح و برنامه و منابع انسانی
        $receivers[0]['uid'] = 806;
        $receivers[0]['rid'] = 2374;
        $newRefer = DocRefer::ReferDocRefer($referID, $receivers, 'احتراما جهت استحضار');
*/
//        /*رونوشت برای خانم قمصریان مسئول کارگزینی
        $receivers[0]['uid'] = 18;
        $receivers[0]['rid'] = 96;
        $newRefer = DocRefer::ReferDocRefer($referID, $receivers, 'احتراما جهت استحضار');

        //        /*رونوشت برای آقای خشرو
        $receivers[0]['uid'] = 130;
        $receivers[0]['rid'] = 95;
        $newRefer = DocRefer::ReferDocRefer($referID, $receivers, 'احتراما جهت استحضار');




        /*انتهای ارسال رونوشت*/





        /*جهت دریافت شماره ثبتی و گذاشتن در فرم*/
        $docID= $execution->workflow->myForm->instanceID;
        WorkFlowSecRegister::regOutForm($docID);
        $res = Letter::GetRegInfo($docID);
        $regCode = $res['completeRegCode'];
        $execution->workflow->myForm->setFieldValueByName('Field_5', $regCode);
        /*انتهای دریافت شماره ثبتی و گذاشتن در فرم*/

    }
}
