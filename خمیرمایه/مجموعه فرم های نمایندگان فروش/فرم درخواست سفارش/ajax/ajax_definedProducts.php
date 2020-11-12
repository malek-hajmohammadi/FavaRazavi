<?php

class MainAjax
{
    public function getData()
    {
       // $list = [];

        $list=$this->getArrayFromDB();
        /*$list[0]['productName'] = "خمیرمایه نوع یک";
        $list[0]['productType'] = "کیلو";
        $list[0]['productPrice'] = "120000";

        $list[1]['productName'] = "خمیرمایه نوع دو";
        $list[1]['productType'] = "کیلو";
        $list[1]['productPrice'] = "120000";*/


        return $list;
    }
    private function getArrayFromDB(){

        $db = PDOAdapter::getInstance();
        $sql="SELECT * FROM `dm_datastoretable_26`";

        $db->executeSelect($sql);

        $count = 0;
        $arrayList=[];
        while ($row = $db->fetchAssoc()) {
            $arrayList[$count]['productName']= $row['Field_0'];
            $arrayList[$count]['productType']= $row['Field_1'];
            $arrayList[$count]['productPrice']= $row['Field_2'];
            $count++;
        }
        $db->close();
        return $arrayList;

    }

}

$mainAjax = new MainAjax();
Response::getInstance()->response = $mainAjax->getData();




