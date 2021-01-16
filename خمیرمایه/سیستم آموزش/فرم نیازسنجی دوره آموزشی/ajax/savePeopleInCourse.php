<?php
class MainAjax
{
    public $tableArray = [];/*آرایه ای که می خواهد در دیتابیس ذخیره شود*/
    public $docId;
    public function save()
    {
        if ($this->getDataFromInput() && $this->considerValidation() )
        {

            $this->deleteAllDataInAdvance();
            $this->saveDataToDb();
            Response::getInstance()->response = "ذخیره سازی با موفقیت انجام شد";
        }
        else{
            Response::getInstance()->response = "ذخیره اطلاعات با مشکل مواجه شد!";
            return false;

        }
        return ture;


    }

    public function getDataFromInput()
    {
        if (Request::getInstance()->varCleanFromInput('tableArray')) {
            $this->tableArray = Request::getInstance()->varCleanFromInput('tableArray');
            $this->tableArray = json_decode($this->tableArray);


        }
        else{

            return false;
        }

        if (Request::getInstance()->varCleanFromInput('docId')) {
            $this->docId = Request::getInstance()->varCleanFromInput('docId');
            return true;
        }
        else{

            return false;
        }



        return false;
        /*$this->tableArray[0]=["خمیر مایه نوع یک","asl","23"];
        $this->tableArray[1]=["خمیر مایه نوع یک","asl","25"];*/

    }
    private function deleteAllDataInAdvance(){
        $db = PDOAdapter::getInstance();
        $sql = "DELETE from dm_datastoretable_36 where MasterID=$this->docId";
        $db->execute($sql);
    }
    private function considerValidation(){
        return true;

    }
    private function saveDataToDb(){

        $db =PDOAdapter::getInstance();
        for ($count = 0; $count < count($this->tableArray); $count++) {

            $item=$this->tableArray[$count];
            $item=explode(",",$item);

            echo("item0:".$item[0]."  item1:".$item[1]);

            $sql = "INSERT INTO dm_datastoretable_36 (Field_0,Field_1,MasterID)
 VALUES (:userId,:roleId,:docId)";
            $PDOParams = array(
                array('name' => 'userId', 'value' => $item[0], 'type' => PDO::PARAM_INT),
                array('name' => 'roleId', 'value' => $item[1], 'type' => PDO::PARAM_INT),
                array('name' => 'docId','value' => $this->docId, 'type' => PDO::PARAM_INT)

            );

            $db->execute($sql,$PDOParams);

        }

    }
}
$mainAjax = new MainAjax();
$mainAjax->save();





