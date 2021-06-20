<?php

class MainAjax
{
    /*
     * Malek Hajmohammadi
     * 1400/03/26
     * */
    private $searchFields;
    private $reportData=[];


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
    private function getDataFromDatabase(){
        $dataTable=[[]];

        $rowStart = ($this->searchFields->pageNumber - 1) * $this->searchFields->pageSize;

        $db = WFPDOAdapter::getInstance();

        $sql = "SELECT listFood.Field_2 as weekDay,
            listFood.Field_3 as dateDay,
            selectedFood.Field_3 as count,
            listFood.Field_4 as type1,
            listFood.Field_5 as type2,
            selectedFood.Field_2 as selectedType,
            listFood.RowID as rowId,
            concat(oa_users.fname,' ',oa_users.lname) as user ,
            if(selectedFood.Field_2 = '1',listFood.Field_4,listFood.Field_5) as foodName,
            if(selectedFood.Field_2 = '1','نوع اول','نوع دوم')as typeFood
            
            
            FROM dm_datastoretable_30 as listFood  
            left join dm_datastoretable_32 as selectedFood ON (listFood.RowID = selectedFood.Field_0 )
            left join oa_users on (oa_users.userid=selectedFood.Field_1)
            
            where selectedFood.Field_2 <> '0' and ".$this->whereClause().
            
            " ORDER BY oa_users.fname LIMIT $rowStart,".$this->searchFields->pageSize;


        $db->executeSelect($sql);
        $count = 0;
        while ($row = $db->fetchAssoc()) {

            $dateDay=$this->alterShamsiDate($row['dateDay']);

            $dataTable[$count] = array(
                $row['empId'],$row['user'],$row['weekDay'],$dateDay,$row['typeFood'],
                $row['foodName'],$row['count'],$row['total']

            ); //0:userId and 1: roledId  //, $row['selectedType']
            $count++;
        }

        $this->reportData['dataTable']=$dataTable;


        $sql = "SELECT count(*) as total 
            
            FROM dm_datastoretable_30 as listFood  
            left join dm_datastoretable_32 as selectedFood ON (listFood.RowID = selectedFood.Field_0 )
            left join oa_users on (oa_users.userid=selectedFood.Field_1)
            
            where selectedFood.Field_2 <> '0' and ".$this->whereClause();

        $total=$db->executeScalar($sql);
        $this->reportData['total']=$total;



    }

    private function whereClause(){
        /*
         *    this.searchFields.userId = this.getUserId();

            this.searchFields.firstDate = this.getFristDate();

            this.searchFields.endDate = this.getSecondDate();

            this.searchFields.foodType = this.getFoodTypes();

            this.searchFields.foodName = this.getFoodName();

            this.searchFields.pageNumber=1;

         *
         * */
        $whereString="";
        $userId=$this->searchFields->userId;
        $userId=trim($userId);
        if($userId!=-1 && is_numeric($userId))
            $whereString.="selectedFood.Field_1=$userId and ";

        $firstDate=$this->searchFields->firstDate;
        $firstDate=trim($firstDate);
        if($firstDate!=-1 && strlen($firstDate)>=8)
            $whereString.="listFood.Field_3 >='$firstDate' and ";

        $endDate=$this->searchFields->endDate;
        $endDate=trim($endDate);
        if($endDate!=-1 && strlen($endDate)>=8)
            $whereString.="listFood.Field_3 <='$endDate' and ";

        $foodType=$this->searchFields->foodType;
        $foodType=trim($foodType);
        if($foodType !=-1)
            $whereString.="selectedFood.Field_2 = '$foodType' and ";

        $foodName=$this->searchFields->foodName;
        $foodName=trim($foodName);
        if($foodName!= -1)
            $whereString.="(listFood.Field_4 like '$foodName' or listFood.Field_5 like '$foodName') and ";


        $whereString.="1";
        return $whereString;


    }

    private function alterShamsiDate($date){
        $date = str_replace("-", "/", $date);
        return $date;
    }

    private function getInputArguments(){

        if (Request::getInstance()->varCleanFromInput('searchFields')) {
            $this->searchFields = Request::getInstance()->varCleanFromInput('searchFields');
            $this->searchFields = json_decode($this->searchFields,false);
        }
        else {

            Response::getInstance()->response = "There is no specefic input";
            return;
        }

    }

}

$mainAjax = new MainAjax();
Response::getInstance()->response = $mainAjax->main();
return $mainAjax;


