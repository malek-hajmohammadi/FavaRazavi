<?php


class MainAjax
{
    private $year="";
    private $month="";
    private $tableArray=[];


    public function main()
    {
        $output = "";
        $this->getInputParameters();

        $this->deletePreviousData();

        $this->saveToDb();



    }

    private function deletePreviousData()
    {
        $db = WFPDOAdapter::getInstance();
        $sql = "DELETE from dm_datastoretable_30 where Field_0='$this->year' and Field_1='$this->month'";
        $db->execute($sql);
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

        if (Request::getInstance()->varCleanFromInput('tableArray')) {
            $this->tableArray = Request::getInstance()->varCleanFromInput('tableArray');
            $this->tableArray = json_decode($this->tableArray, false);
        }
        else {
            Response::getInstance()->response = "There is no tableArray";
            return;
        }
    }

    private function saveToDb()
    {

        $db = WFPDOAdapter::getInstance();

        foreach ($this->tableArray as $row) {
            $sql = "INSERT INTO dm_datastoretable_30 (Field_0,Field_1,Field_2,Field_3,Field_4,Field_5)
 VALUES (:year,:month,:weekDay,:date,:firstFood,:secondFood)";
            $PDOParams = array(
                array('name' => 'year', 'value' => $this->year, 'type' => PDO::PARAM_INT),
                array('name' => 'month', 'value' => $this->month, 'type' => PDO::PARAM_STR),
                array('name' => 'weekDay', 'value' => $row->weekDay, 'type' => PDO::PARAM_STR),
                array('name' => 'date', 'value' => $row->date, 'type' => PDO::PARAM_STR),
                array('name' => 'firstFood', 'value' => $row->type1, 'type' => PDO::PARAM_STR),
                array('name' => 'secondFood', 'value' => $row->type2, 'type' => PDO::PARAM_STR)

            );
            $db->execute($sql, $PDOParams);
        }




    }

}

$mainAjax = new MainAjax();
Response::getInstance()->response = $mainAjax->main();
return ;



