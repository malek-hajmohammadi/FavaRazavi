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

        self::considerTheNumberOfCommute($execution);

        $execution->setVariable('toprole', 0);

        $ACM = AccessControlManager::getInstance();
        $RID = $ACM->getRoleID();
        $UID = $ACM->getUserID();
        $path = MySQLAdapter::getInstance()->executeScalar("SELECT path FROM oa_depts_roles WHERE RowID = $RID");

        $parent[0]['uid'] = $UID;
        $parent[0]['rid'] = $RID;

        $Level = Chart::getLevel($RID);
        $Level = intval($Level);

        $arr = array(/*'/8682/' => array('uid' => '996', 'rid' => '8651') // babak soltanpoor*/
        );

        foreach ($arr as $key => $value) {
            if (strpos($path, $key) !== false) {
                $execution->workflow->myForm->setFieldValueByName('Field_15', array($value));
                return true;
            }
        }

        if ($Level > 34 || $Level == 0) {
            $execution->setVariable('toprole', 0);
            while ($Level > 34 || $Level == 0) {
                $parent = array();
                $parent[] = Chart::getTopRole($RID, 1);
                $RID = $parent[0]['RID'];
                $Level = Chart::getLevel($RID);
                $Level = intval($Level);
            }
        } else {
            $execution->setVariable('toprole', 1);
        }

        $execution->workflow->myForm->setFieldValueByName('Field_15', $parent);
    }

    protected function considerTheNumberOfCommute($execution){

        $codem = $execution->workflow->myForm->getFieldValueByName('Field_8');
        $date = $execution->workflow->myForm->getFieldValueByName('Field_2');

        /* --- */
        $tedad = 0;
        $dateE = explode('/', $date);
        $date1 = $dateE[0] . "/" . $dateE[1] . "/01";
        if ($dateE[1] >= 1 && $dateE[1] <= 6)
            $date2 = $dateE[0] . "/" . $dateE[1] . "/31";
        else if ($dateE[1] >= 7 && $dateE[1] <= 11)
            $date2 = $dateE[0] . "/" . $dateE[1] . "/30";
        else if ($dateE[1] == 12)
            $date2 = $dateE[0] . "/" . $dateE[1] . "/29";

        $dd1 = Date::JalaliToGreg($date1);
        $dd2 = Date::JalaliToGreg($date2);


        $sql = "SELECT count(docid) FROM `dm_datastoretable_895` 
        LEFT JOIN oa_document on (oa_document.rowid = dm_datastoretable_895.docid) 
        WHERE  oa_document.isenable = 1 
        /*AND oa_document.DocStatus = 0 */
        AND (`Field_7` = '1' OR `Field_7` = 'در تورال ثبت شد') 
        AND `Field_8` = '$codem' 
        AND `Field_2` between '$dd1' AND '$dd2' 
        ";
        $tedad = MySQLAdapter::getInstance()->executeScalar($sql);
        $ACM = AccessControlManager::getInstance();
        $RID = $ACM->getRoleID();
        //RID = 8651 مدير امور اداري موسسه تامين و پشتيباني رضوي
        if ($RID != 8651 && $tedad > 4) {
            $execution->workflow->myForm->setFieldValueByName('Field_9', $tedad . ' / تعداد اصلاح بیش از حد است!');
        } else {
            $execution->workflow->myForm->setFieldValueByName('Field_9', $tedad );
        }

    }
}


