<?php


class MainAjax
{

    private $userId;
    private $roleId;
    private $tableArray = [];


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
        foreach ($this->tableArray as $row) {
            $sql = "DELETE from dm_datastoretable_32 where Field_0=$row->rowId and Field_1=$this->userId";
            $db->execute($sql);
        }

    }

    private function getInputParameters()
    {


        if (Request::getInstance()->varCleanFromInput('outputArray')) {
            $this->tableArray = Request::getInstance()->varCleanFromInput('outputArray');
            $this->tableArray = json_decode($this->tableArray, false);
        } else {
            Response::getInstance()->response = "There is no outputArray";
            return;
        }
        $acm = AccessControlManager::getInstance();
        $this->userId = $acm->getUserID();
        $this->roleId=$acm->getRoleID();
    }

    private function saveToDb()
    {

        $db = WFPDOAdapter::getInstance();

        foreach ($this->tableArray as $row) {
           // die('rowId='.$row->rowId.' ,userId='.$this->userId.' ,selected='.$row->selected);
            $sql = "INSERT INTO dm_datastoretable_32 (Field_0,Field_1,Field_2,Field_3,Field_4)
 VALUES (:rowId,:userId,:selected,:count,:roleId)";
            $PDOParams = array(
                array('name' => 'rowId', 'value' => $row->rowId, 'type' => PDO::PARAM_INT),
                array('name' => 'userId', 'value' => $this->userId, 'type' => PDO::PARAM_INT),
                array('name' => 'selected', 'value' => $row->selected, 'type' => PDO::PARAM_INT),
                array('name' => 'count', 'value' => $row->count, 'type' => PDO::PARAM_INT),
                array('name' => 'roleId', 'value' => $this->roleId, 'type' => PDO::PARAM_INT),

            );
            $db->execute($sql, $PDOParams);
        }


    }

}

$mainAjax = new MainAjax();
Response::getInstance()->response = $mainAjax->main();
return;



