<?php


class MainAjax
{



    public function main()
    {
        $userInfo=$this->getCurrentUserInfo();
        return $userInfo;
    }

    private function getCurrentUserInfo(){
        $ACM = AccessControlManager::getInstance();
        $UID = $ACM->getUserID();
        $userInfo="";

        $db = PDOAdapter::getInstance();

        $sql = "SELECT * FROM oa_users WHERE UserID = ".$UID;
        $db->executeSelect($sql);
        $person = $db->fetchAssoc();

        $sex="";
       /* if($person['sex'] == '1')
            $sex = 'آقاي';
        else $sex = 'خانم';*/

        $userInfo->name=$sex.' '.$person['fname'].' '.$person['lname'];
        $userInfo->naturalId = $person['NationalCode'] != NULL ? $person['NationalCode'] : '-';
        $userInfo->phone = $person['mobile'] != NULL ? $person['mobile'] : '';
        $userInfo->address = $person['address'] != NULL ? $person['address'] : '';
        return $userInfo;
    }




}

$mainAjax = new MainAjax();
Response::getInstance()->response = $mainAjax->main();
return $mainAjax;




