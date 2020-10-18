<?php
class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {
        $Creator = $execution->workflow->myForm->getCreator();
        $UID = $Creator['uid'];
        /*
         * مریم السادات عرفانیان 1106
         * حسن کبیری
         * جواد کیوان پور 7353
         *
         * */
        if ($UID==1106 || $UID==7353 ){
            $parent = array();
            $parent[0]['RID']=4820;
            $parent[0]['UID']=1308;
            $execution->workflow->myForm->setFieldValueByName('Field_11', $parent);

        }

    }

}

