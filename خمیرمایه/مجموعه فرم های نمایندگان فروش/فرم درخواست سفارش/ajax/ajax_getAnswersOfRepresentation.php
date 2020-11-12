<?php


class MainAjax
{

    private $docId;

    private function getDataFromDatabase()
    {

        $db = PDOAdapter::getInstance();

        $sql = "select * FROM dm_datastoretable_25 where DocID=:docId ORDER BY RowID";
        $PDOParams = array(
            array('name' => 'docId', 'value' => $this->docId, 'type' => PDO::PARAM_INT)
        );
        $db->executeSelect($sql, $PDOParams);


       $row = $db->fetchAssoc();
        $dataInTable = array(
                $row['Field_6'], $row['Field_7'], $row['Field_8'], $row['Field_9'],
                $row['Field_10'],$row['Field_11'], $row['Field_12']
            );

        return $dataInTable;
    }

    private function getInputArguments()
    {


        if (Request::getInstance()->varCleanFromInput('docId'))
            $this->docId = Request::getInstance()->varCleanFromInput('docId');
        else {

            Response::getInstance()->response = "There is no specefic input";
            return;
        }

    }

    public function mainMethod()
    {
        $this->getInputArguments();
        $answerList= $this->getDataFromDatabase();
        return $answerList;

    }




}

$mainAjax = new MainAjax();
Response::getInstance()->response = $mainAjax->mainMethod();



