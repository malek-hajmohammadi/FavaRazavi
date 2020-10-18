<?php
class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {
        /*گرفتن ui کاربر جاری که همان مدیر فنی هست و گذاشتن در متغییر گردش کار برای چک کردن در شرط بعدی*/
        $ACM = AccessControlManager::getInstance();
        $UID = $ACM->getUserID();

        $execution->setVariable('modirGhesmatUID', $UID);

    }

}
