<?php
class MainAjax
{

    public function main()
    {
        $output = "";
        $this->deleteAll();
        $this->insertONce();
        return $output;

    }

    private function deleteAll()
    {
        $db = WFPDOAdapter::getInstance();
        $sql = "DELETE from dm_datastoretable_26 where 1";
        $db->execute($sql);
    }
    private function insertONce(){

        $sql = "INSERT INTO dm_datastoretable_26  (Field_3) VALUES (:stopFlag)";

        $db = WFPDOAdapter::getInstance();

        $PDOParams = array(
            array('name' => 'stopFlag', 'value' => 0, 'type' => PDO::PARAM_INT),
        );
        $db->execute($sql,$PDOParams);
    }

}

$mainAjax = new MainAjax();
Response::getInstance()->response = $mainAjax->main();
return $mainAjax;

