<?php
class MainAjax
{

    public function main()
    {
        return $this->getFlag();

    }

    private function getFlag()
    {
        $result="-1";
        $db = WFPDOAdapter::getInstance();
        $sql="SELECT * FROM `dm_datastoretable_26` ";

        $db->executeSelect($sql);


        if($row = $db->fetchAssoc())
            $result= $row['Field_3'];

        return $result;

    }


}

$mainAjax = new MainAjax();
Response::getInstance()->response = $mainAjax->main();
return $mainAjax;
