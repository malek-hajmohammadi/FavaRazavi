<?php


class MainAjax
{




    public function main()
    {
        $personalId = $this->getPersonalId();

        if($personalId==1511){
            return 0;
            /*آقای حسین آبای مدیر حراست*/
        }

        $isValidUnit=$this->isPersonInValidUnit($personalId);
        if($isValidUnit==0){
            return 0;
            /*
             * کاربر در واحد نمایندگان فروش هست
             * */
        }

        $existUserIdInSurvey=$this->isExistInTable($personalId);


        $isNeeded = 0;
        if ($existUserIdInSurvey == 0)
            $isNeeded = 1;
        else
            $isNeeded = 0;

        return $isNeeded;


    }

    private function isPersonInValidUnit($personalId)
    {
        $RID = AccessControlManager::getInstance()->getRoleID();
        $path = PDOAdapter::getInstance()->executeScalar("SELECT path FROM oa_depts_roles WHERE RowID = $RID");

        if (strpos($path, '/2222/'))/*مسیر برای نمایندگان فروش در چارت*/
        {
            return 0;
        } else {
            return 1;
        }


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























