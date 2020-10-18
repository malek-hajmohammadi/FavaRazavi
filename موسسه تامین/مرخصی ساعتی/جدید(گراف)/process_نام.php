<?php


/*
موضوع فرم رو دستکاری می کنیم که به اسم طرف باشه
* در این نود مانده مرخصی رو محاسبه و در فرم می گذاریم
 *
 *
 *
 * */

class calssName
{

    protected $mondehMorakhasiShow; //مانده مرخصی بصورت نمایشی//
    protected $mondehMorakhasiNum;//مانده مرخصی عددی برای مقایسه و به ساعت//
    protected $tempForQuery;
    public function __construct(){

    }

    public function execute(ezcWorkflowExecution $execution, ezcWorkflowNodeAction $caller = null)
    {

        self::setSubjectInCable($execution);

        /*toprole=0 کاربر
        toprole=1 ریس به بالا
        toprole=99 اتمام*/

        $Status = $execution->workflow->myForm->getFieldValueByName('Field_8');
        if ($Status == '') $execution->setVariable('toprole', 99);
        else {


           self::getMondehMorakhasi($execution);
            $execution->workflow->myForm->setFieldValueByName('Field_10', $this->mondehMorakhasiShow);


            /*چک کنم که اگر مانده مرخصی بعد از این فرم منفی شد متغیری به نام toprole رو مقدار 90 بده که در مرحله بعدی مسیرش تمام بشه*/
            self::checkMondehMorakhasiAfterThisForm($execution);





            $execution->workflow->myForm->setFieldValueByName('Field_4','topRoles='. $execution->getVariable('toprole'));



        }
    }
    protected function setSubjectInCable($execution){

        $docID = $execution->workflow->myForm->instanceID;
        $Name = $execution->workflow->myForm->getFieldValueByName('Field_0');


        $newTitle = 'مرخصی ساعتی (تامین) ' . $Name;
        $sql_title = "update oa_document set DocDesc='" . $newTitle . "',Subject='" .
            $newTitle . "' where RowID=" . $docID . "  limit 1";

        MySQLAdapter::getInstance()->execute($sql_title);
    }
    protected function getMondehMorakhasi($execution){
        $remainMorakhasi="";
        $dateEnd = '1399/12/30';
        $client = new SoapClient('http://10.10.100.15/WSTuralInOut/TuralInOut.asmx?wsdl');

        $GID=$execution->workflow->myForm->getFieldValueByName('Field_18');
        $s1 = "SELECT * FROM [Timex_TaminPoshtibani].adon.Kardex('".$GID."', '".$dateEnd."')";

        $param = array(
            'username' => '3ef1b48067e4f2ac9913141d77e847dd',
            'password' => '9a3f5b14f1737c15e86680d9cd40b840',
            'objStr' => $s1
        );
        $this->tempForQuery=$s1;

        $res = $client->RunSelectQuery($param);
        $res = $res->RunSelectQueryResult->cols;
        $res = json_decode(json_encode($res), true);
        $MandeMorkhasiString = urldecode($res['recs']['string'][30]);

        $MandeMorkhasiAr=explode(':',$MandeMorkhasiString);

        $this->mondehMorakhasiShow= $MandeMorkhasiAr[0].' روز و '.$MandeMorkhasiAr[1].' ساعت ';
        //$this->tempForQuery= $s1;
        $this->mondehMorakhasiNum=$MandeMorkhasiAr[0]*24+$MandeMorkhasiAr[1];//برحسب ساعت//

    }
    protected function checkMondehMorakhasiAfterThisForm($execution){
        $ModatMorkhasiInthisForm = $execution->workflow->myForm->getFieldValueByName('Field_15');
        $ModatMorkhasiInthisForm = explode(':',$ModatMorkhasiInthisForm);
        $ModatMorkhasiInthisForm = (intval($ModatMorkhasiInthisForm[0]) * 60) + $ModatMorkhasiInthisForm[1]; //برحسب دقیقه//

        $modatMorakhasiAfterThisForm=($this->mondehMorakhasiNum * 60)-$ModatMorkhasiInthisForm;

         $temp=$execution->workflow->myForm->getFieldValueByName('Field_9');
         $temp=$temp."modatMorakhasiAfterThisForm:".$modatMorakhasiAfterThisForm." \n";
         $execution->workflow->myForm->setFieldValueByName('Field_9', $temp);


        if ($modatMorakhasiAfterThisForm < 0) {
            $execution->setVariable('toprole', 90);
            $execution->workflow->myForm->setFieldValueByName('Field_9', ' مدت مرخصی بیشتر از مانده مرخصی است ');

        }


    }
}

