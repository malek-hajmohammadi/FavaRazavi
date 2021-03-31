<?php
class MainAjax
{
    public $tableArray = [];/*آرایه ای که می خواهد در دیتابیس ذخیره شود*/
    public function save()
    {

        if ($this->getDataFromInput() && $this->considerValidation() )
            {

                $this->deletePreviousData();
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
            /*return true;*/
            return $this->tableArray;
        }

        return false;
        /*$this->tableArray[0]=["خمیر مایه نوع یک","asl","23"];
        $this->tableArray[1]=["خمیر مایه نوع یک","asl","25"];*/

    }

    private function considerValidation(){
        return true;

    }

    private function deletePreviousData(){
        $db =PDOAdapter::getInstance();

        $sql = "DELETE from dm_datastoretable_40 ";

        $db->execute($sql);


    }

    private function saveDataToDb(){
        $db =PDOAdapter::getInstance();
        for ($count = 1; $count < count($this->tableArray); $count++) {
            $this->tableArray[$count][1] = str_replace( ',', '', $this->tableArray[$count][1] );

            $sql = "INSERT INTO dm_datastoretable_40 (Field_0,Field_1)
 VALUES (:repName,:repMax)";
            $PDOParams = array(
                array('name' => 'repName', 'value' => $this->tableArray[$count][0], 'type' => PDO::PARAM_STR),
                array('name' => 'repMax', 'value' => $this->tableArray[$count][1], 'type' => PDO::PARAM_STR),

            );

           /* $sql='INSERT INTO dm_datastoretable_26 (Field_0,Field_1,Field_2)
 VALUES ("Ali","cartoon","590909")';*/


            $db->execute($sql,$PDOParams);

        }

    }
}
$mainAjax = new MainAjax();
$mainAjax->save();





