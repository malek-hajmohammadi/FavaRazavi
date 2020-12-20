<?php


class MainAjax
{




    public function main()
    {
        $personalId = $this->getPersonalId();
        $existUserIdInSurvey=$this->isExistInTable($personalId);


        $isNeeded = 0;
        if ($existUserIdInSurvey == 0)
            $isNeeded = 1;
        else
            $isNeeded = 0;

        return $isNeeded;


    }

    private function getPersonalId()
    {

            $UID = AccessControlManager::getInstance()->getUserID(); //آي دي كاربر جاري

        return $UID;

    }


    private function isExistInTable($personalId){
        $db = PDOAdapter::getInstance();
        $sql="SELECT count(*) as sum FROM dm_datastoretable_33 WHERE Field_2=$personalId";
        $count = $db->executeScalar($sql);
        if($count<1)
            return 0; //its needed to complete survey
        else
            return 1;


    }

}

$mainAjax = new MainAjax();
Response::getInstance()->response = $mainAjax->main();
return $mainAjax;


