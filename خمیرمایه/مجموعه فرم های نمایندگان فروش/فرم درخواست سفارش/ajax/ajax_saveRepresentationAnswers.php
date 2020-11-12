<?php

class MainAjax
{

    public $docId = "";/*شماره فرم مستر*/
    public $answers = [];/*آرایه پاسخ به نظرسنجی*/

    public function save()
    {

        if ($this->getDataFromInput() && $this->considerValidation()) {


            $this->saveAnswersToDb();

            Response::getInstance()->response = "ذخیره سازی با موفقیت انجام شد";
        } else {
            Response::getInstance()->response = "ذخیره اطلاعات با مشکل مواجه شد!";

        }


    }

    public function getDataFromInput()
    {

        if (Request::getInstance()->varCleanFromInput('docId')) {
            $this->docId = Request::getInstance()->varCleanFromInput('docId');
        } else
            return false;

        if (Request::getInstance()->varCleanFromInput('answers')) {
            $this->answers = Request::getInstance()->varCleanFromInput('answers');
            $this->answers = json_decode($this->answers);
        } else
            return false;


        return true;


    }



    private function considerValidation()
    {
        return true;

    }



    private function saveAnswersToDb()
    {
        $db = PDOAdapter::getInstance();

        $sql = "UPDATE dm_datastoretable_25 SET Field_6=:q1,Field_7=:q2,Field_8=:q3,Field_9=:q4,Field_10=:q5,Field_11=:q6,Field_12=:q7 WHERE DocID=:docId";

        $PDOParams = array(
            array('name' => 'q1', 'value' => $this->answers[0]->answer),
            array('name' => 'q2', 'value' => $this->answers[1]->answer),
            array('name' => 'q3', 'value' => $this->answers[2]->answer),
            array('name' => 'q4', 'value' => $this->answers[3]->answer),
            array('name' => 'q5', 'value' => $this->answers[4]->answer),
            array('name' => 'q6', 'value' => $this->answers[5]->answer),
            array('name' => 'q7', 'value' => $this->answers[6]->answer),
            array('name' => 'docId', 'value' => $this->docId)

        );

        $db->execute($sql, $PDOParams);

    }
}

$mainAjax = new MainAjax();
$mainAjax->save();







