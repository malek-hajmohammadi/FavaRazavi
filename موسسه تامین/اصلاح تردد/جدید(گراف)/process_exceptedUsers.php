<?php
class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {
        //$Creator = $execution->workflow->myForm->getCreator();
        $ACM = AccessControlManager::getInstance();
        //$RID = $ACM->getRoleID();
        $UID = $ACM->getUserID();
        //$UID = $Creator['uid'];
        /*
         * مریم السادات عرفانیان 1106
         * حسن کبیری
         * جواد کیوان پور 7353
         *
         * */
        if ($UID==1106 || $UID==7353 ){
            $parent = array();
            $parent[0]['rid']=4820;
            $parent[0]['uid']=1308;
            $execution->workflow->myForm->setFieldValueByName('Field_15', $parent);

        }

    }

}


?>
