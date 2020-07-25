<?php

class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {
        /*Insert Code Here*/
        if (1) {

            $date = $execution->workflow->myForm->getFieldValueByName('Field_4');
            if (strlen($date) >= 8) {
                $dateArray = explode('/', $date);
                $year = $dateArray[0];
                $month = $dateArray[1];
                if (strlen($month) == 1)
                    $month = "0" . $month;
                $day = $dateArray[2];
                if (strlen($day) == 1)
                    $day = "0" . $day;

                $date = $year . "/" . $month . "/" . $date;
            }
        }//about getting date
        if (2) {
            $startTime = $execution->workflow->myForm->getFieldValueByName('Field_5');
            $startTimeArray = explode(':',$startTime);
            $startHour = strlen(intval($startTimeArray[0]))==1 ? "0".intval($startTimeArray[0]) : intval($startTimeArray[0]);
            $startMinute = strlen(intval($startTimeArray[1]))==1 ? "0".intval($startTimeArray[1]) : intval($startTimeArray[1]);

            $endTime = $execution->workflow->myForm->getFieldValueByName('Field_6');
            $endTimeArray = explode(':',$endTime);
            $endtHour = strlen(intval($endTimeArray[0]))==1 ? "0".intval($endTimeArray[0]) : intval($endTimeArray[0]);
            $endMinute = strlen(intval($endTimeArray[1]))==1 ? "0".intval($endTimeArray[1]) : intval($endTimeArray[1]);

            $diffTime=($endtHour*60+$endMinute)-($startHour*60+$startMinute);
            $diffHour=floor($diffTime/60);
            $diffMin=$diffTime%60;

            $diffHour=strlen($diffHour)==1 ? "0".$diffHour : $diffHour;
            $diffMin=strlen($diffMin)==1 ? "0".$diffMin : $diffMin;
            $timeMorakhasi=$diffHour.":".$diffMin;

        }//about making time

        $empId = $execution->workflow->myForm->getFieldValueByName('Field_1');
        $type = 1; //for morakhasi saati
        $res = "EXEC [adon].[IOData_ins]" . $empId . ",'" . $date . "','" . $timeMorakhasi . "'," . $type;
        
    }
}
