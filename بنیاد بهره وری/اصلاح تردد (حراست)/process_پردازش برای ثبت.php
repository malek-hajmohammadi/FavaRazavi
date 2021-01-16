<?php


class calssName
{
    private $startTimeQuery = "";
    private $endTimeQuery = "";

    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {
        self::createStringForGraph($execution);
        self::saveToGraph($execution);
    }

    protected function saveToGraph($execution)
    {
        try {
            $client = new SoapClient('http://185.23.128.168/Timex.asmx?wsdl');
            $param = array(
                'username' => '3ef1b48067e4f2ac9913141d77e847dd',
                'password' => '9a3f5b14f1737c15e86680d9cd40b840',
                'objStr' => $this->startTimeQuery
            );
            $resp1 = $client->RunQuery($param);
            $resp1 = $resp1->RunQueryResult;
            $resp1 = json_decode(json_encode($resp1), true);

            $param = array(
                'username' => '3ef1b48067e4f2ac9913141d77e847dd',
                'password' => '9a3f5b14f1737c15e86680d9cd40b840',
                'objStr' => $this->endTimeQuery
            );
            $resp2 = $client->RunQuery($param);
            $resp2 = $resp2->RunQueryResult;
            $resp2 = json_decode(json_encode($resp2), true);

            if (($resp1 == 'true' || $resp1 == true) && ($resp2 == 'true' || $resp2 == true))
                $execution->setVariable('status', 1);
            else
                $execution->setVariable('status', 0);

            $execution->workflow->myForm->setFieldValueByName('Field_3', 'recs1:' . $resp1 . '-Query1:' . $this->startTimeQuery . ' - recs2:' . $resp2 . ' - Query2:' . $this->endTimeQuery);
        } catch (SoapFaultException $e) {
            $execution->setVariable('status', 0);
        } catch (Exception $e) {
            $execution->setVariable('status', 0);
        }
    }

    protected function createStringForGraph($execution)
    {
        $queryString = "";
        $GID = $execution->workflow->myForm->getFieldValueByName('Field_8');
        $docID = $execution->workflow->myForm->instanceID;

        $date = $execution->workflow->myForm->getFieldValueByName('Field_4');
        $date = explode('/', $date);
        $year = intval($date[0]);
        $month = strlen(intval($date[1])) == 1 ? "0" . intval($date[1]) : intval($date[1]);
        $day = strlen(intval($date[2])) == 1 ? "0" . intval($date[2]) : intval($date[2]);
        $date = $year . "/" . $month . "/" . $day;

        $startTime = $execution->workflow->myForm->getFieldValueByName('Field_5');
        $startTime = explode(':', $startTime);
        $startTime = ($startTime[0] * 60) + $startTime[1];

        $endTime = $execution->workflow->myForm->getFieldValueByName('Field_6');
        $endTime = explode(':', $endTime);
        $endTime = ($endTime[0] * 60) + $endTime[1];

        $type = "0";

        $this->startTimeQuery = "EXEC [adon].[IOData_ins] " . $GID . ", '" . $date . "', " . $startTime . ", " . $type . ",'WB$docID';";
        $this->endTimeQuery = "EXEC [adon].[IOData_ins] " . $GID . ", '" . $date . "', " . $endTime . ", " . $type . ",'WB$docID';";

    }


}


