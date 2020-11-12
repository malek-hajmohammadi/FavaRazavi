<?php
class MainAjax
{
    public $tableArray = [];/*آرایه ای که می خواهد در دیتابیس ذخیره شود*/
    public $docId="";/*شماره فرم مستر*/


    public function save()
    {

        if ($this->getDataFromInput() && $this->considerValidation() )
        {
            $this->deletePreviousData();/*حذف داده های قبلی قبل از ذخیره سازی، در غیر این صورت اطلاعات تکراری می شود*/
            $this->saveOrdersToDb();


            Response::getInstance()->response = "ذخیره سازی با موفقیت انجام شد";
        }
        else{
            Response::getInstance()->response = "ذخیره اطلاعات با مشکل مواجه شد!";

        }


    }

    public function getDataFromInput()
    {
        if (Request::getInstance()->varCleanFromInput('tableArray')) {
            $this->tableArray = Request::getInstance()->varCleanFromInput('tableArray');
            $this->tableArray = json_decode($this->tableArray);
        }
        else
            return false;

        if (Request::getInstance()->varCleanFromInput('docId')) {
            $this->docId = Request::getInstance()->varCleanFromInput('docId');
        }
        else
            return false;



        return true;

    }
    private function deletePreviousData(){
        $db =PDOAdapter::getInstance();

        $sql = "DELETE from dm_datastoretable_24 where MasterID=:docId";
        $PDOParams = array(
            array('name' => 'docId', 'value' => $this->docId, 'type' => PDO::PARAM_INT)
        );
        $db->execute($sql,$PDOParams);


    }

    private function considerValidation(){
        return true;

    }
    private function saveOrdersToDb(){
        $db =PDOAdapter::getInstance();
        for ($count = 1; $count < count($this->tableArray); $count++) {


            $sql = "INSERT INTO dm_datastoretable_24 (MasterID,Field_0,Field_1,Field_2,Field_3,Field_4)
 VALUES (:docId,:productName,:productType,:productPrice,:productNum,:productSum)";
            $PDOParams = array(
                array('name' => 'docId', 'value' => $this->docId, 'type' => PDO::PARAM_INT),
                array('name' => 'productName', 'value' => $this->tableArray[$count][0], 'type' => PDO::PARAM_STR),
                array('name' => 'productType', 'value' => $this->tableArray[$count][1], 'type' => PDO::PARAM_STR),
                array('name' => 'productPrice','value' => $this->tableArray[$count][2], 'type' => PDO::PARAM_INT),
                array('name' => 'productNum','value' => $this->tableArray[$count][3], 'type' => PDO::PARAM_INT),
                array('name' => 'productSum','value' => $this->tableArray[$count][4], 'type' => PDO::PARAM_INT)

            );

            /* $sql='INSERT INTO dm_datastoretable_26 (Field_0,Field_1,Field_2)
  VALUES ("Ali","cartoon","590909")';*/


            $db->execute($sql,$PDOParams);


        }

    }

}
$mainAjax = new MainAjax();
$mainAjax->save();






