<?php


class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {
        if ($this->isLetterNecessaryToCopy($execution))
            $this->copyTheLetter();


    }

    private function copyTheLetter()
    {
        /*جهت ارسال رونوشت به پیام برادران رحیمی (منابع انسانی)*/
        $referID = Request::getInstance()->varCleanFromInput("referID");

        $receivers = array();
        $receivers[0] = array();
        $receivers[0]['type'] = 2;
        $receivers[0]['uid'] = 10590;
        $receivers[0]['rid'] = 13503;
        $receivers[0]['oid'] = 'null';
        $receivers[0]['oname'] = '';
        $receivers[0]['isCC'] = 1;
        $newRefer = DocRefer::ReferDocRefer($referID, $receivers, 'احتراما جهت استحضار');

    }

    private function isLetterNecessaryToCopy($execution)
    {
        //$execution->setVariable('emp', '' . intval($emp));

        $applicantRoleId = $execution->getVariable('applicantRoleId');


        $RID = AccessControlManager::getInstance()->getRoleID();
        $path = PDOAdapter::getInstance()->executeScalar("SELECT path FROM oa_depts_roles WHERE RowID = $applicantRoleId");

        /*
         * path
         * املاک اراضی :3439
         * حقوقی : 3440
         * فنی و عمرانی 3443
         * */

        if (strpos($path, '/3439/') || strpos($path, '/3440/') || strpos($path, '/3443/')) {
            return 1;
        } else {
            return 0;
        }

    }
}