<?php
class MainAjax
{
    public $docId="";/*شماره فرم مستر*/
    public $answers=[];/*آرایه پاسخ به نظرسنجی*/




    public function main()
    {

        if ($this->getDataFromInput() && $this->considerValidation() )
        {
            $this->deletePreviousData();/*حذف داده های قبلی قبل از ذخیره سازی، در غیر این صورت اطلاعات تکراری می شود*/
            $this->saveOrdersToDb();
            $this->saveAnswersToDb();

            Response::getInstance()->response = "ذخیره سازی با موفقیت انجام شد";
        }
        else{
            Response::getInstance()->response = "ذخیره اطلاعات با مشکل مواجه شد!";

        }


    }

    /*
     * گرفتن داده ها از ورودی
     * */
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

        if (Request::getInstance()->varCleanFromInput('answers')) {
            $this->answers = Request::getInstance()->varCleanFromInput('answers');
            $this->answers = json_decode($this->answers);
        }
        else
            return false;






        return true;
        /*$this->tableArray[0]=["خمیر مایه نوع یک","asl","23"];
        $this->tableArray[1]=["خمیر مایه نوع یک","asl","25"];*/

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
    private function saveAnswersToDb(){
        $db =PDOAdapter::getInstance();

        $sql = "UPDATE dm_datastoretable_25 SET Field_6=:q1,Field_7=:q2,Field_8=:q3,Field_9=:q4,Field_10=:q5,Field_11=:q6,Field_12=:q7 WHERE DocID=:docId";

        $PDOParams = array(
            array('name' => 'q1','value' => $this->answers[0]->answer),
            array('name' => 'q2','value' => $this->answers[1]->answer),
            array('name' => 'q3','value' => $this->answers[2]->answer),
            array('name' => 'q4','value' => $this->answers[3]->answer),
            array('name' => 'q5','value' => $this->answers[4]->answer),
            array('name' => 'q6','value' => $this->answers[5]->answer),
            array('name' => 'q7','value' => $this->answers[6]->answer),
            array('name' => 'docId','value' => $this->docId)

        );

        $db->execute($sql,$PDOParams);

    }
}
$mainAjax = new MainAjax();
$mainAjax->main();






