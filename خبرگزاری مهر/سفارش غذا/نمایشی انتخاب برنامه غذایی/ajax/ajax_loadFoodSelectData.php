<?php


class MainAjax
{
    private $year;
    private $month;
    private $dataTable;
    private $userId;

    private $sqlString;

    public function main()
    {
        $this->getInputParameters();

        $this->getDataTableFromDatabase();

        return $this->dataTable;
        //return $this->sqlString;

    }

    private function getInputParameters()
    {
        if (Request::getInstance()->varCleanFromInput('year'))
            $this->year = Request::getInstance()->varCleanFromInput('year');
        else {
            Response::getInstance()->response = "There is no year";
            return;
        }

        if (Request::getInstance()->varCleanFromInput('month'))
            $this->month = Request::getInstance()->varCleanFromInput('month');
        else {
            Response::getInstance()->response = "There is no month";
            return;
        }

        $acm = AccessControlManager::getInstance();
        $this->userId = $acm->getUserID();



    }


    private function getDataTableFromDatabase()
    {

        $db = WFPDOAdapter::getInstance();

      /*  $sql = "select listFood.Field_2 as weekDay,listFood.Field_3 as dateDay ".
            ",listFood.Field_4 as type1,listFood.Field_5 as type2 ".
            ",selectedFood.Field_2 as selectedType FROM dm_datastoretable_30 as listFood".
            " left join dm_datastoretable_32 as selectedFood ON (listFood.RowID = selectedFood.Field_0 )" .
            "  where listFood.Field_0='$this->year' and Field_1='$this->month'".
        "AND (selectedFood.Field_1='$this->userId'|| selectedFood.Field_1 IS NULL) ORDER BY RowID";*/

        $sql = "select listFood.Field_2 as weekDay,listFood.Field_3 as dateDay,selectedFood.Field_3 as count ".
            ",listFood.Field_4 as type1,listFood.Field_5 as type2,selectedFood.Field_2 as selectedType,listFood.RowID as rowId  ".
            "FROM dm_datastoretable_30 as listFood  left join dm_datastoretable_32 as selectedFood ON (listFood.RowID = selectedFood.Field_0 )".
            " where listFood.Field_0='$this->year' and listFood.Field_1='$this->month'";
        $db->executeSelect($sql);
        $count = 0;
        while ($row = $db->fetchAssoc()) {

            $dateDay=$this->alterShamsiDate($row['dateDay']);
            $biggerThanToday=$this->isBiggerThanToday($row['dateDay']);
            $this->dataTable[$count] = array(
                $row['weekDay'], $dateDay, $row['type1'], $row['type2'],
                $row['rowId'],$row['selectedType'],$biggerThanToday,$row['count']
            ); //0:userId and 1: roledId  //, $row['selectedType']
            $count++;
        }

        $this->sqlString=$sql;
    }
    private function alterShamsiDate($date){
        $date = str_replace("-", "/", $date);
        return $date;
    }
    private function isBiggerThanToday($date){
        $today = date("Y-m-d");
        $todayArray=explode('-',$today);

        $date = explode('-', $date);
        list($year, $month, $day) = Date::jalali_to_gregorian($date[0], $date[1], $date[2]);


        if ($year > $todayArray[0])
            return "e";
        if ($year == $todayArray[0] && $month > $todayArray[1])
            return "e";
        if ($year == $todayArray[0] && $month == $todayArray[1] && $day > $todayArray[2])
            return ("e");
        return "r";

    }

}

$mainAjax = new MainAjax();
Response::getInstance()->response = $mainAjax->main();
return $mainAjax;





