<?php
class MainAjax
{


    public function main()
    {
        $sql="DELETE FROM `dm_datastoretable_24` WHERE RowID=10";
        $db = PDOAdapter::getInstance();
        $output=$db->executeSelect($sql);
        return $output;
    }




}

$mainAjax = new MainAjax();
Response::getInstance()->response = $mainAjax->main();
return $mainAjax;


