<?php

class MainAjax
{

    private $title="";
    private $numberOfForms="";
    public $departs="";/*آرایه ای که می خواهد در دیتابیس ذخیره شود*/

    public function save()
    {

        if ($this->getDataFromInput() && $this->considerValidation()) {

            $this->saveToHistoryTable();


            Response::getInstance()->response = "ذخیره سازی با موفقیت انجام شد";
        } else {
            Response::getInstance()->response = "ذخیره اطلاعات با مشکل مواجه شد!";

        }


    }

    public function getDataFromInput()
    {
        if (Request::getInstance()->varCleanFromInput('title')) {
            $this->title = Request::getInstance()->varCleanFromInput('title');

        } else
            return false;

        if (Request::getInstance()->varCleanFromInput('departsArray')) {
            $this->departs = Request::getInstance()->varCleanFromInput('departsArray');

        } else
            return false;

        if (Request::getInstance()->varCleanFromInput('numberOfForms')) {
            $this->numberOfForms = Request::getInstance()->varCleanFromInput('numberOfForms');
        } else
            return false;

        return true;

    }

    private function considerValidation()
    {
        return true;
    }

    private function saveToHistoryTable()
    {
        $db = WFPDOAdapter::getInstance();
        $date=(new DateTime())->format('Y-m-d');

            $sql = "INSERT INTO dm_datastoretable_1421 (Field_0,Field_1,Field_2,Field_3)
 VALUES (:date,:title,:destinations,:numberOfForms)";
            $PDOParams = array(
                array('name' => 'date', 'value' => $date, 'type' => PDO::PARAM_STR),
                array('name' => 'title', 'value' => $this->title, 'type' => PDO::PARAM_STR),
                array('name' => 'destinations', 'value' => $this->departs, 'type' => PDO::PARAM_STR),
                array('name' => 'numberOfForms', 'value' => $this->numberOfForms, 'type' => PDO::PARAM_INT)
            );

            $result=$db->execute($sql, $PDOParams);

            return $result;

    }

}

$mainAjax = new MainAjax();
$mainAjax->save();

