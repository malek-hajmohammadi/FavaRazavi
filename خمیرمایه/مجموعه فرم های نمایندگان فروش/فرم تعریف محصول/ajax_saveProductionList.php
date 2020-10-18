<?php
class MainAjax
{
    public $tableArray = [];/*آرایه ای که می خواهد در دیتابیس ذخیره شود*/
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

        }


    }

    public function getDataFromInput()
    {
        if (Request::getInstance()->varCleanFromInput('tableArray')) {
            $this->tableArray = Request::getInstance()->varCleanFromInput('tableArray');
            $this->tableArray = json_decode($this->tableArray);
            return true;
        }

        return false;
        /*$this->tableArray[0]=["خمیر مایه نوع یک","asl","23"];
        $this->tableArray[1]=["خمیر مایه نوع یک","asl","25"];*/

    }
    private function deleteAllDataInAdvance(){
        $db = PDOAdapter::getInstance();
        $sql = "DELETE from dm_datastoretable_26 where 1";
        $db->execute($sql);
    }
    private function considerValidation(){
        return true;

    }
    private function saveDataToDb(){
        $db =PDOAdapter::getInstance();
        for ($count = 1; $count < count($this->tableArray); $count++) {
            $this->tableArray[$count][2] = str_replace( ',', '', $this->tableArray[$count][2] );

            $sql = "INSERT INTO dm_datastoretable_26 (Field_0,Field_1,Field_2)
 VALUES (:productName,:productType,:productPrice)";
            $PDOParams = array(
                array('name' => 'productName', 'value' => $this->tableArray[$count][0], 'type' => PDO::PARAM_STR),
                array('name' => 'productType', 'value' => $this->tableArray[$count][1], 'type' => PDO::PARAM_STR),
                array('name' => 'productPrice','value' => $this->tableArray[$count][2], 'type' => PDO::PARAM_STR)

            );

           /* $sql='INSERT INTO dm_datastoretable_26 (Field_0,Field_1,Field_2)
 VALUES ("Ali","cartoon","590909")';*/


            $db->execute($sql,$PDOParams);

        }

    }
}
$mainAjax = new MainAjax();
$mainAjax->save();





