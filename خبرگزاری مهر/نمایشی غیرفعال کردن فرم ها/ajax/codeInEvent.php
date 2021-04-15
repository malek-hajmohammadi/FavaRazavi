<?php

class calssName
{
    public function __construct()
    {
    }

    public function execute($wfid = "")
    {
        $result=-1;
        $db = WFPDOAdapter::getInstance();
        $sql="SELECT * FROM `dm_datastoretable_26` ";

        $db->executeSelect($sql);


        if($row = $db->fetchAssoc())
            $result= $row['Field_3'];

        if($result=="2"){
            return "همکار محترم امکان اصلاح تردد غیرفعال شده است";
        }

        return true;

    }
}
