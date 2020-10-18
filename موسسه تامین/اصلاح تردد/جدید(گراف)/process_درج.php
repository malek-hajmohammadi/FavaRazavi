<?php

class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {
        //statuslevel=3 غلط
        //statuslevel=4 درست

        $execution->setVariable('statuslevel', 3);

        $stID = 895;//فرم اصلاح تردد
        $type = $execution->workflow->myForm->getFieldValueByName('Field_13');
        $codem = $execution->workflow->myForm->getFieldValueByName('Field_8');
        $date = $execution->workflow->myForm->getFieldValueByName('Field_2');
        $time = $execution->workflow->myForm->getFieldValueByName('Field_3');

        /* --- */
        $tedad = 0;
        $dateE = explode('/', $date);
        $date1 = $dateE[0] . "/" . $dateE[1] . "/01";
        if ($dateE[1] >= 1 && $dateE[1] <= 6) $date2 = $dateE[0] . "/" . $dateE[1] . "/31";
        else if ($dateE[1] >= 7 && $dateE[1] <= 11) $date2 = $dateE[0] . "/" . $dateE[1] . "/30";
        else if ($dateE[1] == 12) $date2 = $dateE[0] . "/" . $dateE[1] . "/29";

        $dd1 = Date::JalaliToGreg($date1);
        $dd2 = Date::JalaliToGreg($date2);

        $sql = "SELECT count(docid) FROM `dm_datastoretable_895` 
        LEFT JOIN oa_document on (oa_document.rowid = dm_datastoretable_895.docid) 
        WHERE  oa_document.isenable = 1 
        /*AND oa_document.DocStatus = 0 */
        AND (`Field_7` = '1' OR `Field_7` = 'در گراف ثبت شد') 
        AND `Field_8` = '$codem' 
        AND `Field_2` between '$dd1' AND '$dd2' 
        ";
        $tedad = MySQLAdapter::getInstance()->executeScalar($sql);
        $ACM = AccessControlManager::getInstance();
        $RID = $ACM->getRoleID();
        //RID = 8651 مدير امور اداري موسسه تامين و پشتيباني رضوي

        if(false){ //چون فعلا نمی خواهیم که شرط تعداد رو در فرم نداشته باشیم این رو بلاک کردم//
        // if ($RID != 8651 && $tedad > 4) {
            $execution->setVariable('statuslevel', 90);
            $execution->workflow->myForm->setFieldValueByName('Field_9', $tedad . ' / تعداد اصلاح بیش از حد است!');
            $execution->workflow->myForm->setFieldValueByName('Field_7', 'در گراف ثبت نشد / برگشت از پردازش درج');
        } else {

            if (intval($type) == 11) /*افزودن تردد*/ {
                $result=self::insertToGraph($execution);
                if ($result) {
                    $execution->setVariable('statuslevel', 4);
                    $execution->workflow->myForm->setFieldValueByName('Field_7', 'در گراف ثبت شد');
                } else {
                    $execution->setVariable('statuslevel', 3);
                    $execution->workflow->myForm->setFieldValueByName('Field_7', 'در گراف ثبت نشد / برگشت از پردازش درج');
                }
            } else if (intval($type) == 12) /*حذف تردد*/
            {
                $result=self::deleteFromGraph($execution);
                if ($result) {
                    $execution->setVariable('statuslevel', 4);
                    $execution->workflow->myForm->setFieldValueByName('Field_7', 'در گراف ثبت شد');
                } else {
                    $execution->setVariable('statuslevel', 3);
                    $execution->workflow->myForm->setFieldValueByName('Field_7', 'در گراف ثبت نشد / برگشت از پردازش درج');
                }
            }

            $sql = "SELECT count(docid) FROM `dm_datastoretable_895` 
				LEFT JOIN oa_document on (oa_document.rowid = dm_datastoretable_895.docid) 
				WHERE  oa_document.isenable = 1 
				/*AND oa_document.DocStatus = 0 */
				AND (`Field_7` = '1' OR `Field_7` = 'در گراف ثبت شد') 
				AND `Field_8` = '$codem' 
				AND `Field_2` between '$dd1' AND '$dd2' 
				";
            $tedad = MySQLAdapter::getInstance()->executeScalar($sql);
            $execution->workflow->myForm->setFieldValueByName('Field_9', $tedad);
        }
    }

    protected function insertToGraph($execution)
    {

        $GID = $execution->workflow->myForm->getFieldValueByName('Field_11');
        $date = $execution->workflow->myForm->getFieldValueByName('Field_2');
        $time = $execution->workflow->myForm->getFieldValueByName('Field_3');
        $time = explode(':', $time);
        $time = $time[0] * 60 + $time[1];
        $typeMorkhasi = 0;/*عادی*/
        $docID = $execution->workflow->myForm->instanceID;


        $client = new SoapClient('http://10.10.100.15/WSTuralInOut/TuralInOut.asmx?wsdl');
        $s1 = "EXEC [Timex_TaminPoshtibani].[adon].[IOData_ins] " . $GID . ",'" . $date . "',$time,$typeMorkhasi,'WB$docID'";

        // $s="EXEC [adon].[IOData_ins] EMPID, '13990504','480', 1";

        $param = array(
            'username' => '3ef1b48067e4f2ac9913141d77e847dd',
            'password' => '9a3f5b14f1737c15e86680d9cd40b840',
            'objStr' => $s1
        );
        //$execution->workflow->myForm->setFieldValueByName('Field_4',$s1);
        $res = $client->RunQuery($param);
        $res = $res->RunQueryResult;

        $res = json_decode(json_encode($res), true);
        return $res;

    }

    protected function deleteFromGraph($execution)
    {

        $GID = $execution->workflow->myForm->getFieldValueByName('Field_11');
        $date = $execution->workflow->myForm->getFieldValueByName('Field_2');

        $time = $execution->workflow->myForm->getFieldValueByName('Field_3');
        $time = explode(':', $time);
        $time = $time[0] * 60 + $time[1];


        $client = new SoapClient('http://10.10.100.15/WSTuralInOut/TuralInOut.asmx?wsdl');

        $s1 = "EXEC [Timex_TaminPoshtibani].[adon].[IOData_del] " . $GID . ",'" . $date . "',$time";

        // $s="EXEC [adon].[IOData_ins] EMPID, '13990504','480', 1";

        $param = array(
            'username' => '3ef1b48067e4f2ac9913141d77e847dd',
            'password' => '9a3f5b14f1737c15e86680d9cd40b840',
            'objStr' => $s1
        );
        //$execution->workflow->myForm->setFieldValueByName('Field_4',$s1);
        $res = $client->RunQuery($param);
        $res = $res->RunQueryResult;

        $res = json_decode(json_encode($res), true);
        return $res;

    }
}

