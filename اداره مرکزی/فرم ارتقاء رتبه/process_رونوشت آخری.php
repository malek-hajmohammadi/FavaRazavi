<?php
class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {
        /*جهت ارسال رونوشت به خانم کشمیری*/
        $referID = Request::getInstance()->varCleanFromInput("referID");

        $receivers = array();
        $receivers[0] = array();
        $receivers[0]['type'] = 2;
        $receivers[0]['uid'] = 3769;
        $receivers[0]['rid'] = 2643;
        $receivers[0]['oid'] = 'null';
        $receivers[0]['oname'] = '';
        $receivers[0]['isCC'] = 1;
        $newRefer = DocRefer::ReferDocRefer($referID, $receivers, 'احتراما جهت استحضار');

    }
}
