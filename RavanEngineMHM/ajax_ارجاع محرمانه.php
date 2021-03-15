<?php
class MainAjax
{


    public function main()
    {
        $sql="select * from `oa_doc_refer` WHERE RowID=1471278";
        $db = WFPDOAdapter::getInstance();
        $db->executeSelect($sql);

        $row = $db->fetchAssoc();

        return $row;

    }




}

$mainAjax = new MainAjax();
Response::getInstance()->response = $mainAjax->main();
return $mainAjax;


