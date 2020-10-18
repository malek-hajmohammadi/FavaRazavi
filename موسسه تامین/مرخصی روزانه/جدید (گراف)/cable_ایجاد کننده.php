<?php
class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution, ezcWorkflowNodeAction $caller = null)
    {

        //toprole=0 كاربر
        //toprole=1 ريس به بالا
        //toprole=99 اتمام
        $execution->setVariable('toprole', 0);/*یعنی کارشناس*/



        /*
        $ACM = AccessControlManager::getInstance();
        $RID = $ACM->getRoleID();
        $UID = $ACM->getUserID();
        */

        $Creator = $execution->workflow->myForm->getCreator();
        $UID = $Creator['uid'];
        $RID = $Creator['rid'];
        $path = MySQLAdapter::getInstance()->executeScalar("SELECT path FROM oa_depts_roles WHERE RowID = $RID");

        $parent[0]['uid'] = $UID;
        $parent[0]['rid'] = $RID;

        $Level = Chart::getLevel($RID);
        $Level = intval($Level);



        $arr = array(/*'/8682/' => array('uid' => '996', 'rid' => '8651') // babak soltanpoor*/
        );

        foreach ($arr as $key => $value) {
            if (strpos($path, $key) !== false) {
                $execution->workflow->myForm->setFieldValueByName('Field_9', array($value));
                return true;
            }
        }

        if ($Level > 34 || $Level == 0) {
            $execution->setVariable('toprole', 0);
            while ($Level > 34 || $Level == 0) {

                $parent = Chart::getTopRole($RID, 1);
                $RID = $parent['RID'];
                $Level = Chart::getLevel($RID);
                $Level = intval($Level);
            }
        } else {
            $execution->setVariable('toprole', 1);
        }

        $execution->workflow->myForm->setFieldValueByName('Field_9', $parent);

        $newReferNote = $execution->getVariable('newReferNote');
        if ($newReferNote != '' || $newReferNote != NULL || $newReferNote != FALSE) {
            $referID = $execution->workflow->myForm->referInsID;
            $sql = "UPDATE oa_doc_refer SET NoteDesc='" . $newReferNote . "' WHERE oa_doc_refer.ParentID = $referID";
            $db = MySQLAdapter::getInstance();
            $db->execute($sql);
        } else {
            $execution->setVariable('newReferNote', '');
        }
    }
}



