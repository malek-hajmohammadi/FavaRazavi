<?php


class MainAjax
{
    private $saveItems;
    private $listFoodRowId;


    public function main()
    {
        $output = "";
        $this->getInputParameters();

        $this->listFoodRowId= $this->getListFoodRowId();

       $this->deletePreviousData();

        $this->saveToDb();


    }

    private function getListFoodRowId(){
        $db = WFPDOAdapter::getInstance();
        $sql="SELECT RowID From dm_datastoretable_30 where Field_3 ='{$this->saveItems->date}'";
        return $db->executeScalar($sql);
    }

    private function deletePreviousData()
    {
        $db = WFPDOAdapter::getInstance();
        $sql = "DELETE from dm_datastoretable_32 where Field_0='{$this->listFoodRowId}'";
        $db->execute($sql);
    }

    private function getInputParameters()
    {

        if (Request::getInstance()->varCleanFromInput('saveItems')) {
            $this->saveItems = Request::getInstance()->varCleanFromInput('saveItems');
            $this->saveItems = json_decode($this->saveItems, false);
        } else {

            Response::getInstance()->response = "There is no specefic input";
            return;
        }
    }

    private function saveToDb()
    {

        $db = WFPDOAdapter::getInstance();

        foreach ($this->saveItems->dataTable as $row) {
            // die('rowId='.$row->rowId.' ,userId='.$this->userId.' ,selected='.$row->selected);
            $sql = "INSERT INTO dm_datastoretable_32 (Field_0,Field_1,Field_2,Field_3,Field_4)
 VALUES (:rowId,:userId,:selected,:count,:roleId)";
            $PDOParams = array(
                array('name' => 'rowId', 'value' => $this->listFoodRowId, 'type' => PDO::PARAM_INT),
                array('name' => 'userId', 'value' => $row->userId, 'type' => PDO::PARAM_INT),
                array('name' => 'selected', 'value' => $row->foodType, 'type' => PDO::PARAM_INT),
                array('name' => 'count', 'value' => $row->count, 'type' => PDO::PARAM_INT),
                array('name' => 'roleId', 'value' => $row->roleId, 'type' => PDO::PARAM_INT),

            );
            $db->execute($sql, $PDOParams);
        }


    }

}

$mainAjax = new MainAjax();
Response::getInstance()->response = $mainAjax->main();
return;




