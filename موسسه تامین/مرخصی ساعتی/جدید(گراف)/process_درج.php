<?php

class calssName
{
    protected $mondehMorakhasiShow; //مانده مرخصی بصورت نمایشی//
    protected $mondehMorakhasiNum;//مانده مرخصی عددی برای مقایسه و به ساعت//

    public function __construct() {}

    public function execute(ezcWorkflowExecution $execution)
    {


        self::getMondehMorakhasi($execution);
        $execution->workflow->myForm->setFieldValueByName('Field_10', $this->mondehMorakhasiShow);

        $ModatMorkhasi = $execution->workflow->myForm->getFieldValueByName('Field_15');
        $ModatMorkhasi = explode(':', $ModatMorkhasi);
        $ModatMorkhasi = (intval($ModatMorkhasi[0]) * 60) + $ModatMorkhasi[1];

        if ($ModatMorkhasi > $this->mondehMorakhasiNum * 60) {
            $execution->setVariable('statuslevel', 90);
            $execution->workflow->myForm->setFieldValueByName('Field_9', ' مدت مرخصی بیشتر از مانده مرخصی است ');
        } else {
            $correctOperation = self::setCommuteToGraph($execution);
            if ($correctOperation) {
                $execution->setVariable('statuslevel', 6);
                $execution->workflow->myForm->setFieldValueByName('Field_9', ' تردد با موفقیت اعمال شد ');
            } else {
                $execution->setVariable('statuslevel', 3);
                $execution->workflow->myForm->setFieldValueByName('Field_9', ' ثبت تردد با مشکل مواجه شد ');
            }

        }

        /*$temp=$execution->workflow->myForm->getFieldValueByName('Field_9');
        $temp.=",statusLevel=". $execution->getVariable('statuslevel');
        $execution->workflow->myForm->setFieldValueByName('Field_9',$temp);*/

    }

    protected function setCommuteToGraph($execution)
    {


        if (1)/*دریافت مقادیر از فرم*/ {

            $timeR = $execution->workflow->myForm->getFieldValueByName('Field_2');
            $timeR = explode(':', $timeR);
            $timeR=$timeR[0]*60+$timeR[1];

            $timeB = $execution->workflow->myForm->getFieldValueByName('Field_14');
            $timeB = explode(':', $timeB);
            $timeB=$timeB[0]*60+$timeB[1];

            $typeMorakhasi = $execution->workflow->myForm->getFieldValueByName('Field_3'); /*نوع مرخصی*/
            $GID = $execution->workflow->myForm->getFieldValueByName('Field_5');/*کد پرسنلی در گراف*/
            $date=$execution->workflow->myForm->getFieldValueByName('Field_1');/*تاریخ مرخصی*/
            $docID = $execution->workflow->myForm->instanceID;

            $correctOperation=true;  /*چک می کند که آیا توابع گراف به درستی در گراف ذخیره می شود یا نه*/


        }


        /*[1, "مرخصي اول وقت"],
        [2, "مرخصي ميان وقت"],
        [3, "مرخصي آخر وقت"]*/

        switch ($typeMorakhasi) {
            case 1:
                self::deleteFromGraph($GID,$date,$timeB);
                $correctOperation=self::insertToGraph($GID,$date,$timeB,"morkhasi",$execution,$docID);
                break;
            case 2:
                self::deleteFromGraph($GID,$date,$timeR);
                self::deleteFromGraph($GID,$date,$timeB);

                $correctOperationA=self::insertToGraph($GID,$date,$timeR,"morkhasi",$execution,$docID);
                $correctOperationB=self::insertToGraph($GID,$date,$timeB,"adi",$execution,$docID);
                $correctOperation=$correctOperationA && $correctOperationB;
                break;
            case 3:
                self::deleteFromGraph($GID,$date,$timeR);
                $correctOperation=self::insertToGraph($GID,$date,$timeR,"morkhasi",$execution,$docID);
                break;
        }
        return $correctOperation;



    }

    protected function getMondehMorakhasi($execution)
    {
        $remainMorakhasi = "";
        $dateEnd = '1399/12/30';
        $client = new SoapClient('http://10.10.100.15/WSTuralInOut/TuralInOut.asmx?wsdl');

        $GID = $execution->workflow->myForm->getFieldValueByName('Field_18');
        $s1 = "SELECT * FROM [Timex_TaminPoshtibani].adon.Kardex('" . $GID . "', '" . $dateEnd . "')";

        $param = array(
            'username' => '3ef1b48067e4f2ac9913141d77e847dd',
            'password' => '9a3f5b14f1737c15e86680d9cd40b840',
            'objStr' => $s1
        );

        //$execution->workflow->myForm->setFieldValueByName('Field_4',$s1);

        $res = $client->RunSelectQuery($param);
        $res = $res->RunSelectQueryResult->cols;
        $res = json_decode(json_encode($res), true);
        $MandeMorkhasiString = urldecode($res['recs']['string'][30]);

        $MandeMorkhasiAr = explode(':', $MandeMorkhasiString);

        $this->mondehMorakhasiShow = $MandeMorkhasiAr[0] . ' روز و ' . $MandeMorkhasiAr[1] . ' ساعت ';
        $this->mondehMorakhasiNum = $MandeMorkhasiAr[0] * 24 + $MandeMorkhasiAr[1];
    }

    protected function deleteFromGraph($GID,$date,$time){

        $client = new SoapClient('http://10.10.100.15/WSTuralInOut/TuralInOut.asmx?wsdl');
        $s1 = "EXEC [Timex_TaminPoshtibani].[adon].[IOData_del] " . $GID . ",'" . $date . "',$time";
       // $s="EXEC [Timex_TaminPoshtibani].[adon].[IOData_del] EMPID,'13990504','480'";


        $param = array(
            'username' => '3ef1b48067e4f2ac9913141d77e847dd',
            'password' => '9a3f5b14f1737c15e86680d9cd40b840',
            'objStr' => $s1
        );
        $res = $client->RunQuery($param);

    }

    protected function insertToGraph($GID,$date,$time,$typeMorkhasi,$execution,$docID){

        if($typeMorkhasi=="morkhasi")
            $typeMorkhasi=1;
        else if($typeMorkhasi=="adi")
            $typeMorkhasi=0;
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


}


