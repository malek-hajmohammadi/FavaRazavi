<?php

class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution, ezcWorkflowNodeAction $caller = null)
    {
        $docID = $execution->workflow->myForm->instanceID;
        $stID = 895;//فرم اصلاح تردد
        $Name = $execution->workflow->myForm->getFieldValueByName('Field_10');
        $Type = $execution->workflow->myForm->getFieldValueByName('Field_13');
        $codem = $execution->workflow->myForm->getFieldValueByName('Field_8');

        //1400/04/05
        $date = $execution->workflow->myForm->getFieldValueByName('Field_2');

        $time = $execution->workflow->myForm->getFieldValueByName('Field_3');

        if ($Type == 11) $Type = 'افزودن تردد';
        if ($Type == 12) $Type = 'حذف تردد';
        $newTitle = $Type . ' (تامين) ' . $Name;
        $sql_title = "update oa_document set DocDesc='" . $newTitle . "',Subject='" . $newTitle . "' where RowID=" . $docID . "  limit 1";
        WFPDOAdapter::getInstance()->execute($sql_title);

//toprole=0 كاربر
//toprole=1 ريس به بالا
//toprole=99 اتمام
//toprole=90 تعداد بيش از مجاز

        $tedad = 0;
        $dateE = explode('/', $date);

        $date1 = $dateE[0] . "/" . $dateE[1] . "/01";

        /*با توجه به روزهای ماه در سال*/
        if ($dateE[1] >= 1 && $dateE[1] <= 6) $date2 = $dateE[0] . "/" . $dateE[1] . "/31";
        else if ($dateE[1] >= 7 && $dateE[1] <= 11) $date2 = $dateE[0] . "/" . $dateE[1] . "/30";
        else if ($dateE[1] == 12) $date2 = $dateE[0] . "/" . $dateE[1] . "/29";

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
        $tedad = WFPDOAdapter::getInstance()->executeScalar($sql);
        $execution->workflow->myForm->setFieldValueByName('Field_12', $tedad);

        $ACM = AccessControlManager::getInstance();
        $RID = $ACM->getRoleID();
//RID = 8651 مدير امور اداري موسسه تامين و پشتيباني رضوي

        $shahrestan = 0;
        $sql5 = "SELECT path FROM oa_depts_roles WHERE RowID = $RID";
        $path = WFPDOAdapter::getInstance()->executeScalar($sql5);
        if (strpos($path, '/6597/') !== false) $shahrestan = 1;
        else $shahrestan = 0;


        /*منظور از شهرستان برابر با یک چیست ؟*/
        if ($shahrestan == 1) {

            $execution->workflow->myForm->setFieldValueByName('Field_9', $tedad);
            $execution->workflow->myForm->setFieldValueByName('Field_7', 'شهرستان');
        }
        else if ($RID != 8651 && $tedad > 5 && $shahrestan == 0) {

            /*زمانیکه toprole=90 باشد از نود شرط بر می گردد به نود متقاضی*/
            ezcWorkflowExecution $execution

            $execution->workflow->myForm->setFieldValueByName('Field_9', $tedad . ' / تعداد اصلاح بيش از حد است!');

            $execution->workflow->myForm->setFieldValueByName('Field_7', 'در تورال ثبت نشد / برگشت از پردازش نام / ' . $shahrestan);
        }
        else {

            $execution->workflow->myForm->setFieldValueByName('Field_9', $tedad);
        }

        $Status = $execution->workflow->myForm->getFieldValueByName('Field_7'); //وضعيت
        if ($Status == '') $execution->setVariable('toprole', 99);
    }
}
       