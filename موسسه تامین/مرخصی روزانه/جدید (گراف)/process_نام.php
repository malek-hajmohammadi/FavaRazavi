<?php

class calssName
{
    protected $mondehMorakhasiShow; //مانده مرخصی بصورت نمایشی//
    protected $mondehMorakhasiNum;//مانده مرخصی عددی برای مقایسه و به ساعت//
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution, ezcWorkflowNodeAction $caller = null)
    {
        self::setSubjectInCable($execution);

        //toprole=0 کاربر
        //toprole=1 ریس به بالا
        //toprole=99 اتمام


        self::getMondehMorakhasi($execution);
        $execution->workflow->myForm->setFieldValueByName('Field_15', $this->mondehMorakhasiShow);

        /*چک کنم که اگر مانده مرخصی بعد از این فرم منفی شد متغیری به نام toprole رو مقدار 90 بده که در مرحله بعدی مسیرش تمام بشه*/
        self::checkMondehMorakhasiAfterThisForm($execution);

    }


    protected function setSubjectInCable($execution)
    {
        $docID = $execution->workflow->myForm->instanceID;
        $Name = $execution->workflow->myForm->getFieldValueByName('Field_1');

        $Type = $execution->workflow->myForm->getFieldValueByName('Field_10');
        if ($Type == 1) $Type2 = 'استحقاقی';
        if ($Type == 3 || $Type == 33) $Type2 = 'استعلاجی';
        if ($Type == 4 || $Type == 44) $Type2 = 'بدون حقوق';
        if ($Type == 11) $Type2 = 'زایمان';
        if ($Type == 111) $Type2 = 'اضطراری';
        if ($Type == 10) $Type2 = 'استراحت';

        $newTitle = 'مرخصی ' . $Type2 . ' (تامین) ' . $Name;

        $sql_title = "update oa_document set DocDesc='" . $newTitle . "',Subject='" .
            $newTitle . "' where RowID=" . $docID . "  limit 1";

        MySQLAdapter::getInstance()->execute($sql_title);
    }

    protected function getMondehMorakhasi($execution)
    {
        $remainMorakhasi = "";
        $dateEnd = '1399/12/30';
        $client = new SoapClient('http://10.10.100.15/WSTuralInOut/TuralInOut.asmx?wsdl');

        $GID = $execution->workflow->myForm->getFieldValueByName('Field_5');
        $s1 = "SELECT * FROM [Timex_TaminPoshtibani].adon.Kardex('" . $GID . "', '" . $dateEnd . "')";

        $param = array(
            'username' => '3ef1b48067e4f2ac9913141d77e847dd',
            'password' => '9a3f5b14f1737c15e86680d9cd40b840',
            'objStr' => $s1
        );
        $res = $client->RunSelectQuery($param);
        $res = $res->RunSelectQueryResult->cols;
        $res = json_decode(json_encode($res), true);
        $MandeMorkhasiString = urldecode($res['recs']['string'][30]);

        $MandeMorkhasiAr = explode(':', $MandeMorkhasiString);

        $this->mondehMorakhasiShow = $MandeMorkhasiAr[0] . ' روز و ' . $MandeMorkhasiAr[1] . ' ساعت ';
        $this->mondehMorakhasiNum = $MandeMorkhasiAr[0];


    }

    protected function checkMondehMorakhasiAfterThisForm($execution)
    {
        $ModatMorkhasiInthisForm = $execution->workflow->myForm->getFieldValueByName('Field_4');
        $ModatMorkhasiInthisForm= intval(substr($ModatMorkhasiInthisForm, 0, stripos($ModatMorkhasiInthisForm, 'روز')));

        $modatMorakhasiAfterThisForm=$this->mondehMorakhasiNum-$ModatMorkhasiInthisForm;

       // $temp=$execution->workflow->myForm->getFieldValueByName('Field_13');
       // $temp=$temp."modatMorakhasiAfterThisForm:".$modatMorakhasiAfterThisForm." \n";
       // $execution->workflow->myForm->setFieldValueByName('Field_13', $temp);


        if ($modatMorakhasiAfterThisForm < 0) {
            $execution->setVariable('toprole', 90);
        }

    }
}


/*

        $execution->setVariable('newReferNote', '');
        $ACM = AccessControlManager::getInstance();
        $RID = $ACM->getRoleID();
        //RID = 8651 مدير امور اداري موسسه تامين و پشتيباني رضوي
        //if($RID != 8651 && ...)
        $docID = $execution->workflow->myForm->instanceID;
        $Name = $execution->workflow->myForm->getFieldValueByName('Field_1');
        $Type = $execution->workflow->myForm->getFieldValueByName('Field_10');
        $codem = $execution->workflow->myForm->getFieldValueByName('Field_6');
        $ModatMorkhasi = $execution->workflow->myForm->getFieldValueByName('Field_4');

        if ($Type == 1) $Type2 = 'استحقاقی';
        if ($Type == 3 || $Type == 33) $Type2 = 'استعلاجی';
        if ($Type == 4 || $Type == 44) $Type2 = 'بدون حقوق';
        if ($Type == 11) $Type2 = 'زایمان';
        if ($Type == 111) $Type2 = 'اضطراری';
        if ($Type == 10) $Type2 = 'استراحت';

        $newTitle = 'مرخصی ' . $Type2 . ' (تامین) ' . $Name;
        $sql_title = "UPDATE oa_document SET DocDesc='" . $newTitle . "', Subject='" . $newTitle . "' WHERE RowID=" . $docID . " LIMIT 1";
        MySQLAdapter::getInstance()->execute($sql_title);


        $tedad = 0;
        $dateR = $execution->workflow->myForm->getFieldValueByName('Field_2');
        $dateE = explode('/', $dateR);
        $date1 = $dateE[0] . "/" . $dateE[1] . "/01";
        if ($dateE[1] >= 1 && $dateE[1] <= 6) $date2 = $dateE[0] . "/" . $dateE[1] . "/31";
        else if ($dateE[1] >= 7 && $dateE[1] <= 11) $date2 = $dateE[0] . "/" . $dateE[1] . "/30";
        else if ($dateE[1] == 12) $date2 = $dateE[0] . "/" . $dateE[1] . "/29";
        $dd1 = Date::JalaliToGreg($date1);
        $dd2 = Date::JalaliToGreg($date2);

        $sql = "SELECT count(docid) FROM `dm_datastoretable_931`
        LEFT JOIN oa_document on (oa_document.rowid = dm_datastoretable_931.docid)
        WHERE  oa_document.isenable = 1
        AND `Field_8` = '6 / تورال ثبت شد / آرمان لازم نیست'
        AND `Field_6` = '$codem'
        AND `Field_2` between '$dd1' AND '$dd2'
        ";


        $tedad = MySQLAdapter::getInstance()->executeScalar($sql);
        $execution->workflow->myForm->setFieldValueByName('Field_8', $tedad);
        if ($RID != 8651 && $tedad > 4) {
            $execution->setVariable('toprole', 90);
            $execution->workflow->myForm->setFieldValueByName('Field_8', $tedad . ' / تعداد استراحت بیش از حد است / !برگشت از پردازش نام');
        } else {
            //toprole=0 کاربر
            //toprole=1 ریس به بالا
            //toprole=99 اتمام
            //toprole=90 مدت بیش از مجاز

            $Status = $execution->workflow->myForm->getFieldValueByName('Field_8');
            if ($Status == '') $execution->setVariable('toprole', 99);
            else {


                //--------------------
                $MandeMorkhasiNahaee = $MandeMorkhasiOO + $MandeMorkhasi2OO;
                if ($MandeMorkhasiNahaee != 0) {
                    $MandeMorkhasiNahaeeStatus = 'mosbat';
                    $offset = strpos($MandeMorkhasiNahaee, '-');
                    if ($offset !== FALSE) {
                        $MandeMorkhasiNahaee = substr($MandeMorkhasiNahaee, 1);
                        $MandeMorkhasiNahaeeStatus = 'manfi';
                    }
                    $MandeMorkhasiNahaee = explode('.', $MandeMorkhasiNahaee);
                    $day = $MandeMorkhasiNahaee[0];
                    $day = strlen($day) == 1 ? "0" . $day : $day;
                    if ($MandeMorkhasiNahaee[1] != NULL) $hourmin = '0.' . $MandeMorkhasiNahaee[1];
                    else $hourmin = 0;
                    $hourmin = $hourmin * 440;
                    $hourmin = $hourmin / 60;
                    $hourmin = explode('.', $hourmin);
                    $min = "0." . $hourmin[1];
                    $min = $min * 60;
                    $min = $min % 60;
                    $min = strlen($min) == 1 ? "0" . $min : $min;
                    $hour = $hourmin[0];
                    $hour = $hour + floor($min / 60);
                    $hour = strlen($hour) == 1 ? "0" . $hour : $hour;
                    if ($day != 00 && $hour != 00 && $min != 00) $MandeMorkhasiNahaee = $day . 'روز و ' . $hour . 'ساعت و ' . $min . 'دقيقه';
                    elseif ($day != 00 && $hour != 00 && $min == 00) $MandeMorkhasiNahaee = $day . 'روز و ' . $hour . 'ساعت';
                    elseif ($day != 00 && $hour == 00 && $min != 00) $MandeMorkhasiNahaee = $day . 'روز و ' . $min . 'دقيقه';
                    elseif ($day == 00 && $hour != 00 && $min != 00) $MandeMorkhasiNahaee = $hour . 'ساعت و ' . $min . 'دقيقه';
                    elseif ($day == 00 && $hour == 00 && $min != 00) $MandeMorkhasiNahaee = $min . 'دقيقه';
                    else $MandeMorkhasiNahaee = '0';
                    if ($MandeMorkhasiNahaeeStatus == 'manfi') $MandeMorkhasiNahaee = 'منفی ' . $MandeMorkhasiNahaee;
                    $MandeMorkhasiNahaeeMerg = ((intval($day) * 24) * 60) + (intval($hour) * 60) + intval($min);
                    $MandeMorkhasiNahaeeMerg = $day . '-' . $hour . '-' . $min . '-' . $MandeMorkhasiNahaeeMerg . '-' . $MandeMorkhasiNahaeeStatus;
                } else {
                    $MandeMorkhasiNahaeeMerg = 0;
                }
                //--------------------
                $execution->workflow->myForm->setFieldValueByName('Field_15', $MandeMorkhasi);
                $execution->workflow->myForm->setFieldValueByName('Field_16', $MandeMorkhasiMerg);

                $ModatMorkhasiINT = intval(substr($ModatMorkhasi, 0, stripos($ModatMorkhasi, 'روز')));
                if ($Type == 1 && $RID != 8651 && $MandeMorkhasi == 'خطا') {
                    $newReferNote = 'مانده مرخصی خطا دارد';
                    $execution->workflow->myForm->setFieldValueByName('Field_8', '90 BPN / ' . $newReferNote);
                    $execution->setVariable('toprole', 90);
                    $execution->setVariable('newReferNote', $newReferNote);
                } else if ($Type == 1 && $RID != 8651 && $MandeMorkhasiStatus == 'manfi') {
                    $newReferNote = 'مانده مرخصی منفی است';
                    $execution->workflow->myForm->setFieldValueByName('Field_8', '90 BPN / ' . $newReferNote);
                    $execution->setVariable('toprole', 90);
                    $execution->setVariable('newReferNote', $newReferNote);
                } else if ($Type == 1 && $RID != 8651 && $ModatMorkhasiINT > intval($dayMandeMorkhasi)) {
                    $newReferNote = 'مدت مرخصی بیشتر از مانده مرخصی است ';
                    $execution->workflow->myForm->setFieldValueByName('Field_8', '90 BPN / ' . $newReferNote);
                    $execution->setVariable('toprole', 90);
                    $execution->setVariable('newReferNote', $newReferNote);
                }

                else if($Type == 1 && $RID != 8651 && $ModatMorkhasiINT > 6)
                {
                    $newReferNote = 'مدت مرخصی نباید بیش از ۵ روز باشد ';
                    $execution->workflow->myForm->setFieldValueByName('Field_8','90 BPN / '.$newReferNote);
                    $execution->setVariable('toprole',90);
                    $execution->setVariable('newReferNote',$newReferNote);
                }

            }
        }
      */