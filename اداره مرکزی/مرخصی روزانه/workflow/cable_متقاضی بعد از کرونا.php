<?php
class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution,ezcWorkflowNodeAction $caller)
    {
        /*$referID=Request::getInstance()->varCleanFromInput("referID");
        $referID = $execution->workflow->myForm->referInsID;
        $referID=$caller->newReferID;
        $newReferNote="امکان مرخصی کرونا برای پرسنل سازمان حرم غیرفعال شده است";

        $sql = "UPDATE oa_doc_refer SET NoteDesc='".$newReferNote."' WHERE oa_doc_refer.ParentID = $referID limit 1";
        $db = WFPDOAdapter::getInstance();
        $db->execute($sql);*/
        $caller->setReferNote("امکان مرخصی کرونا برای پرسنل سازمان حرم غیرفعال شده است");
    }


}

