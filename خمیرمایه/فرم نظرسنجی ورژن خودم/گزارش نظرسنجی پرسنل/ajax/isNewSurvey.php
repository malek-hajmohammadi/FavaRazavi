<?php

class MainAjax
{


    public function main()
    {
       $userId=$this->getCurrentUserId();
       return $this->isNewInSurvey($userId);

    }
    private function getCurrentUserId(){
        $ACM = AccessControlManager::getInstance();
        $UID = $ACM->getUserID();
        return $UID;
    }

    private function isNewInSurvey($userId)
    {
        $db = PDOAdapter::getInstance();
        $sql = 'select * from dm_datastoretable_33 where Field_2=:userId';
        $PDOParams[] = array('name' => 'userId', 'value' => $userId, 'type' => PDO::PARAM_INT);
        $db->executeSelect($sql,$PDOParams);
        $res = $db->fetchAssoc();
        if ($res)
            return false;

        return true;
    }


}

$mainAjax = new MainAjax();
$output=$mainAjax->main();
Response::getInstance()->response =$output;
return $output;


