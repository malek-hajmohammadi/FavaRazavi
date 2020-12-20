<?php

class MainAjax
{


    public function main()
    {

        $answers = $this->getDataFromDatabase();


        return $answers;
    }



    private function getDataFromDatabase()
    {


        $dataInTable = array();


        $stForHavingProductName = "";

        $db = PDOAdapter::getInstance();

        $sql="SELECT Field_2,Field_3,Field_4 FROM dm_datastoretable_33";

        $db->executeSelect($sql);

        $count = 0;

        while ($row = $db->fetchAssoc()) {

            $dataInTable[$count]->userId=$row['Field_2'];
            $dataInTable[$count]->question=$row['Field_3'];
            $dataInTable[$count]->answer=$row['Field_4'];
            $count++;

        }
        return $dataInTable;
        //return $sql;
    }





}

$mainAjax = new MainAjax();
Response::getInstance()->response = $mainAjax->main();
return $mainAjax;



