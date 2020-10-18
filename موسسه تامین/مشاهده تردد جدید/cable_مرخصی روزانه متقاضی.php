<?php

class calssName
{
    public function __construct(){}
    public function execute(ezcWorkflowExecution $execution, ezcWorkflowNodeAction $caller = null)
    {

       self::findManager($execution);



        $newReferNote = $execution->getVariable('newReferNote');
        if($newReferNote != '' || $newReferNote != NULL || $newReferNote != FALSE)
        {
            $referID = $execution->workflow->myForm->referInsID;
            $sql = "UPDATE oa_doc_refer SET NoteDesc='".$newReferNote."' WHERE oa_doc_refer.ParentID = $referID";
            $db = MySQLAdapter::getInstance();
            $db->execute($sql);
        }
        else
        {
            $execution->setVariable('newReferNote','');
        }
    }
    protected  function findManager($execution){

        //toprole=0 كاربر
        //toprole=1 ريس به بالا
        //toprole=99 اتمام
        $execution->setVariable('toprole',0);



        $Creator = $execution->workflow->myForm->getCreator();
        $UID = $Creator['uid'];
        $RID = $Creator['rid'];
        $path = MySQLAdapter::getInstance()->executeScalar("SELECT path FROM oa_depts_roles WHERE RowID = $RID");

        $parent[0]['uid'] = $UID;
        $parent[0]['rid'] = $RID;

        $Level = Chart::getLevel($RID);
        $Level = intval($Level);




        if($Level>34 || $Level==0)
        {
            $execution->setVariable('toprole',0);
            while($Level>34 || $Level==0)
            {
                $parent = array();
                $parent[] = Chart::getTopRole($RID,1);
                $RID = $parent[0]['RID'];
                $Level = Chart::getLevel($RID);
                $Level = intval($Level);
            }
}
else
{
    $execution->setVariable('toprole',1);
}

$execution->workflow->myForm->setFieldValueByName('Field_9',$parent);


}
}


?>