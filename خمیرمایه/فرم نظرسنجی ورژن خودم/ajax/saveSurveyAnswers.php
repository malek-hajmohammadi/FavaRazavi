<?php

class MainAjax
{


    public $answers = [];

    public function main()
    {
        $result = $this->save();
        return $result;
    }


    public function getDataFromInput()
    {


        if (Request::getInstance()->varCleanFromInput('answers')) {
            $this->answers = Request::getInstance()->varCleanFromInput('answers');
            $this->answers = json_decode($this->answers);
        } else
            return false;


        return true;


    }

    private function considerValidation()
    {
        $ACM = AccessControlManager::getInstance();
        $uId = $ACM->getUserID();

        


        return true;

    }

    private function saveAnswersToDb()
    {
        $db = PDOAdapter::getInstance();

        $ACM = AccessControlManager::getInstance();
        $uId = $ACM->getUserID();
        //$uId = 2;



        for($i=0;$i<sizeof($this->answers);$i++){

            $placeHolder[] = "(:userId_$i, :questionId_$i, :answerId_$i)";

            $PDOParams[] = array('name' => 'userId_'.$i, 'value' =>$uId, 'type' => PDO::PARAM_INT);
            $PDOParams[] = array('name' => 'questionId_'.$i, 'value' =>$this->answers[$i]->question, 'type' => PDO::PARAM_STR);
            $PDOParams[] = array('name' => 'answerId_'.$i, 'value' =>$this->answers[$i]->answer, 'type' => PDO::PARAM_INT);

        }

        $values = implode(',', $placeHolder);

        $sqlString="INSERT INTO dm_datastoretable_33 (Field_2,Field_3,Field_4) VALUES $values";

        $db->execute($sqlString,$PDOParams);

        return $sqlString;

    }
    private function isNewUserInSurvey(){
        $ACM = AccessControlManager::getInstance();
        $userId = $ACM->getUserID();

        $db = PDOAdapter::getInstance();
        $sql = 'select * from dm_datastoretable_33 where Field_2=:userId';
        $PDOParams[] = array('name' => 'userId', 'value' => $userId, 'type' => PDO::PARAM_INT);
        $db->executeSelect($sql,$PDOParams);
        $res = $db->fetchAssoc();
        if ($res)
            return false;

        return true;


    }



    public function save()
    {

       $isNewUser=$this->isNewUserInSurvey();

        if($isNewUser) {


            if ($this->getDataFromInput() && $this->considerValidation()) {


                return $this->saveAnswersToDb();

                Response::getInstance()->response = "ذخیره سازی با موفقیت انجام شد";
            } else {

                return "there is a problem in if clause";
                Response::getInstance()->response = "ذخیره اطلاعات با مشکل مواجه شد!";

            }
        }
        else{

            return "قبلا فرم نظرسنجی توسط شما تکمیل شده است و امکان نظرسنجی مجدد میسر نمی باشد";

        }


    }


}

$mainAjax = new MainAjax();
$output=$mainAjax->main();

Response::getInstance()->response =$output;
return $output;