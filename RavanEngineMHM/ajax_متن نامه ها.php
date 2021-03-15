<?php
class MainAjax
{


    public function main()
    {
        $sql="select * from `oa_content` where RoleID=493 order by `RowID` desc limit 50";
        $db = WFPDOAdapter::getInstance();
        $db->executeSelect($sql);


        $res = array();
        while ($row = $db->fetchAssoc())
        {
            $res[] = $row;
            
        };

        return $res;
    }




}

$mainAjax = new MainAjax();
Response::getInstance()->response = $mainAjax->main();
return $mainAjax;


