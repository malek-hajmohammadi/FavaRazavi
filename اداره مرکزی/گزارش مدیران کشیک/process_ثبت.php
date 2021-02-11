<?php


class calssName // do not change this line
{


    public function __construct()
    {

    }

    public function execute(ezcWorkflowExecution $execution, ezcWorkflowNodeAction $caller = null) // $execution refer to active workflow that call this class
    {

        $this->set_shomarehSabti($execution);

       /* $this->ronevesht_to_moavenatAmaken();

        $this->ronvesht_to_moavenatKhadamat();*/

    }
    private function set_shomarehSabti($execution){

        $docID= $execution->workflow->myForm->instanceID;
        WorkFlowSecRegister::regOutForm($docID);
        $res = Letter::GetRegInfo($docID);
        $regCode = $res['completeRegCode'];
        $execution->workflow->myForm->setFieldValueByName('Field_41', $regCode);
    }

    private function ronevesht_to_moavenatAmaken(){

        //محمد توکلی فریمانی
        //uid:878
        //rid:9

        //تحصیلدار تستی
        //uid:4816
        //rid:6305


        $receivers = array();
        $receivers[0] = array();
        $receivers[0]['type'] = 2;
        $receivers[0]['uid'] = 878;
        $receivers[0]['rid'] = 9;
        $receivers[0]['oid'] = 'null';
        $receivers[0]['oname'] = '';
        $receivers[0]['isCC'] = 1;

        $referID = Request::getInstance()->varCleanFromInput('referID');


        $newRefer = DocRefer::ReferDocRefer($referID, $receivers, 'احتراما جهت استحضار');

    }

    private function ronvesht_to_moavenatKhadamat(){

        //امنین بهنام
        //uid:9591
        //rid:13134

        //تحصیلدار تستی
        //uid:4816
        //rid:6305


        $receivers = array();
        $receivers[0] = array();
        $receivers[0]['type'] = 2;
        $receivers[0]['uid'] = 9591;
        $receivers[0]['rid'] = 13134;
        $receivers[0]['oid'] = 'null';
        $receivers[0]['oname'] = '';
        $receivers[0]['isCC'] = 1;

        $referID = Request::getInstance()->varCleanFromInput('referID');

        $newRefer = DocRefer::ReferDocRefer($referID, $receivers, 'احتراما جهت استحضار');


    }
}

