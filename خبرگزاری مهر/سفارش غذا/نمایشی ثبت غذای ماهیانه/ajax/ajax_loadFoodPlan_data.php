<?php


class MainAjax
{
    private $year;
    private $month;
    private $dataTable;

    public function main()
    {
         $this->getInputParameters();

          $this->getDataTableFromDatabase();

        return $this->dataTable;

    }

    private function getInputParameters(){
        if (Request::getInstance()->varCleanFromInput('year'))
            $this->year = Request::getInstance()->varCleanFromInput('year');
        else {
            Response::getInstance()->response = "There is no year";
            return;
        }

        if (Request::getInstance()->varCleanFromInput('month'))
            $this->month = Request::getInstance()->varCleanFromInput('month');
        else {
            Response::getInstance()->response = "There is no month";
            return;
        }
    }




    private function getDataTableFromDatabase()
    {

        $db = WFPDOAdapter::getInstance();

        $sql = "select * FROM dm_datastoretable_30" .
            " where Field_0='$this->year' and Field_1='$this->month' ORDER BY RowID";

        $db->executeSelect($sql);
        $count = 0;
        while ($row = $db->fetchAssoc()) {
            $this->dataTable[$count] = array(
                $row['Field_0'], $row['Field_1'],$row['Field_2'], $row['Field_3'],$row['Field_4'],$row['Field_5']
            ); //0:userId and 1: roledId
            $count++;
        }
    }

}

$mainAjax = new MainAjax();
Response::getInstance()->response = $mainAjax->main();
return $mainAjax;




