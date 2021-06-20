<?php
/*
//اسم غذاها رو از دو جدول بگیرم و یک آرایه برگردونم و اونطرف در یک لیست باکس بگذارم و مقدارش هم همین غذا رو بگذارم تا در کوئری جستجو بگذاریم//
SELECT DocID FROM `dm_datastoretable_1001` UNION DISTINCT SELECT DocID FROM `dm_datastoretable_1003`
*/

class MainAjax
{
    public function getData()
    {
        // $list = [];

        $list=$this->getArrayFromDB();
       /* $list[0] = "خميرمايه نوع يك";


        $list[1]= "خميرمايه نوع دو";*/



        return $list;
    }
    private function getArrayFromDB(){

        $db = WFPDOAdapter::getInstance();
        $sql="SELECT Field_4 as foodName FROM `dm_datastoretable_30` UNION DISTINCT SELECT Field_5 as foodName FROM `dm_datastoretable_30`";

        $db->executeSelect($sql);

        $count = 0;
        $arrayList=[];
        while ($row = $db->fetchAssoc()) {
            $arrayList[$count]= $row['foodName'];
            $count++;
        }
        $db->close();
        return $arrayList;

    }

}

$mainAjax = new MainAjax();
Response::getInstance()->response = $mainAjax->getData();




