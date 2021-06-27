<?php


class MainAjax
{
    /*
     * Malek Hajmohammadi
     * 1400/04/02
     * */
    private $searchFields;
    private $reportData = [];


    public function main()
    {

        $this->getInputArguments();
        $this->getDataFromDatabase();
        //return $this->searchFields;
        /* $reportData=[
             ["Majid",4,1,"togkham","1400/03/02",1],
             ["Reza",2,5,"asdf","1399/03/05",2],
             ["taghi",6,8,"omlet","1388/04/06",2]
         ];*/
        return $this->reportData;


    }

    private function getInputArguments()
    {

        if (Request::getInstance()->varCleanFromInput('searchFields')) {
            $this->searchFields = Request::getInstance()->varCleanFromInput('searchFields');
            $this->searchFields = json_decode($this->searchFields, false);
        } else {

            Response::getInstance()->response = "There is no specefic input";
            return;
        }

    }

    private function getDataFromDatabase()
    {
        $dataTable = [[]];

        $rowStart = ($this->searchFields->pageNumber - 1) * $this->searchFields->pageSize;

        $db = WFPDOAdapter::getInstance();

        $sql = "SELECT listFood.Field_2 as weekDay,
            listFood.Field_3 as dateDay,
            selectedFood.Field_3 as count,
            listFood.Field_4 as type1,
            listFood.Field_5 as type2,
            selectedFood.Field_2 as selectedType,
            listFood.RowID as rowId,
            selectedFood.Field_1 as userId,
            selectedFood.Field_4 as roleId,
            selectedFood.Field_2 as foodType,
            listFood.Field_4 as firstFood,
            listFood.Field_5 as secondFood
                        
            FROM dm_datastoretable_30 as listFood  
            left join dm_datastoretable_32 as selectedFood ON (listFood.RowID = selectedFood.Field_0 )
            
            where selectedFood.Field_2 <> '0' and " . $this->whereClause() .

            " ORDER BY listFood.RowID LIMIT $rowStart," . $this->searchFields->pageSize;


        $db->executeSelect($sql);
        $count = 0;
        while ($row = $db->fetchAssoc()) {

            $dataTable[$count] = array(


                $row['rowId'], $row['userId'],$row['roleId'], $row['foodType'],
                $row['firstFood'],$row['secondFood'], $row['count']

            ); //0:userId and 1: roledId  //, $row['selectedType']
            $count++;
        }

        $this->reportData['dataTable'] = $dataTable;

    }

    private function whereClause()
    {
        /*
         *    this.searchFields.userId = this.getUserId();

            this.searchFields.firstDate = this.getFristDate();

            this.searchFields.endDate = this.getSecondDate();

            this.searchFields.foodType = this.getFoodTypes();

            this.searchFields.foodName = this.getFoodName();

            this.searchFields.pageNumber=1;

         *
         * */
        $whereString = "";


        $firstDate = $this->searchFields->date;
        $firstDate = trim($firstDate);
        if ($firstDate != -1 && strlen($firstDate) >= 8)
            $whereString .= "listFood.Field_3 ='$firstDate' and ";

        $whereString .= "1";
        return $whereString;


    }

    private function alterShamsiDate($date)
    {
        $date = str_replace("-", "/", $date);
        return $date;
    }

}

$mainAjax = new MainAjax();
Response::getInstance()->response = $mainAjax->main();
return $mainAjax;



