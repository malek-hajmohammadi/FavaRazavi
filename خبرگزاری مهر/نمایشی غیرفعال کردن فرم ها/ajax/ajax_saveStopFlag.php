<?php

class MainAjax
{

    private $situationFlag=0;
    public function main()
    {
        $this->getInputArguments();
        $this->saveIntoTable();
        return $this->situationFlag;

    }

    private function getInputArguments()
    {
        if (Request::getInstance()->varCleanFromInput('situationFlag')) {
            $this->situationFlag=Request::getInstance()->varCleanFromInput('situationFlag');

        }
        else {

            Response::getInstance()->response = "There is no specefic input";
            return;
        }
    }

    private function saveIntoTable(){

        $db = WFPDOAdapter::getInstance();
        $sql="UPDATE dm_datastoretable_26 SET `Field_3`=$this->situationFlag WHERE 1 limit 1 ";
        $db->execute($sql);

    }


}

$mainAjax = new MainAjax();
Response::getInstance()->response = $mainAjax->main();
return $mainAjax;
