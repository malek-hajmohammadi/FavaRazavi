<?php
class MainAjax
{
    private $searchFields;

    public function main()
    {
        $this->getInputArguments();
        $answers = $this->getDataFromDatabase();


        return $answers;
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

    private function getDataFromDatabase()
    {


        $dataInTable = array();




        $stForHavingProductName="";

        $db = PDOAdapter::getInstance();

        if($this->searchFields->productName!="-1")
            $stForHavingProductName="INNER JOIN dm_datastoretable_24 as dmDetail on (dmDetail.MasterID=dm.docid) ";

        $sql = "select dm.Field_6,dm.Field_7,dm.Field_8,dm.Field_9,dm.Field_10,dm.Field_11,dm.Field_12,dm.DocID,CreateDate FROM dm_datastoretable_25 as dm 
INNER JOIN oa_document on (oa_document.rowid = dm.docId) ".$stForHavingProductName;
        $sqlWhereClause=$this->whereClause();

        /*
        $PDOParams = array(
            array('name' => 'docId', 'value' => $this->docId, 'type' => PDO::PARAM_INT)
        );
*/


        $sql=$sql.$sqlWhereClause;

        $db->executeSelect($sql);

        $count = 0;
        $dataInTable=$this->initialAnswers($dataInTable);
        while ($row = $db->fetchAssoc()) {

            $q=$row['Field_6'];
            if(is_numeric($q) && ($q=="1" || $q=="2" || $q=="3")) {
                if ($q == "1")
                    $dataInTable[0][0]++;
                else if ($q == "2")
                    $dataInTable[1][0]++;
                else if ($q == "3")
                    $dataInTable[2][0]++;
            }


            $q=$row['Field_7'];
            if(is_numeric($q) && ($q=="1" || $q=="2" || $q=="3")) {
                if ($q == "1")
                    $dataInTable[0][1]++;
                else if ($q == "2")
                    $dataInTable[1][1]++;
                else if ($q == "3")
                    $dataInTable[2][1]++;
            }
            $q=$row['Field_8'];
            if(is_numeric($q) && ($q=="1" || $q=="2" || $q=="3")) {
                if ($q == "1")
                    $dataInTable[0][2]++;
                else if ($q == "2")
                    $dataInTable[1][2]++;
                else if ($q == "3")
                    $dataInTable[2][2]++;
            }
            $q=$row['Field_9'];
            if(is_numeric($q) && ($q=="1" || $q=="2" || $q=="3")) {
                if ($q == "1")
                    $dataInTable[0][3]++;
                else if ($q == "2")
                    $dataInTable[1][3]++;
                else if ($q == "3")
                    $dataInTable[2][3]++;
            }
            $q=$row['Field_10'];
            if(is_numeric($q) && ($q=="1" || $q=="2" || $q=="3")) {
                if ($q == "1")
                    $dataInTable[0][4]++;
                else if ($q == "2")
                    $dataInTable[1][4]++;
                else if ($q == "3")
                    $dataInTable[2][4]++;
            }
            $q=$row['Field_11'];
            if(is_numeric($q) && ($q=="1" || $q=="2" || $q=="3")) {
                if ($q == "1")
                    $dataInTable[0][5]++;
                else if ($q == "2")
                    $dataInTable[1][5]++;
                else if ($q == "3")
                    $dataInTable[2][5]++;
            }
            $q=$row['Field_12'];
            if(is_numeric($q) && ($q=="1" || $q=="2" || $q=="3")) {
                if ($q == "1")
                    $dataInTable[0][6]++;
                else if ($q == "2")
                    $dataInTable[1][6]++;
                else if ($q == "3")
                    $dataInTable[2][6]++;
            }


        }
        return $dataInTable;
        //return $sql;
    }

    private function whereClause()
    {
        $whereClause=" where ";
        $firstDate=$this->getFirstDateString();
        $endDate=$this->getEndDateString();
        $userIdSt=$this->getUserIdString();
        $productNameSt=$this->getProductNameString();

        $whereClause=$whereClause.$firstDate.$endDate.$userIdSt.$productNameSt;
        $whereClause.="1 ";

        return $whereClause;

    }

    private function getFirstDateString(){
        $dateClause="";

        $firstDate=  $this->searchFields->firstDate;

        if($firstDate=="-1" || strlen($firstDate)<8 )
            return "";

        $firstDate = Date::JalaliToGreg($firstDate);
        $dateClause .= "createDate >= '$firstDate' and ";
        return $dateClause;
    }
    private function getEndDateString(){
        $dateClause="";


        $endDate=  $this->searchFields->endDate;
        if($endDate=="-1" || strlen($endDate)<8)
            return "";

        $endDate = Date::JalaliToGreg($endDate);
        $dateClause .= "createDate <= '$endDate' and ";
        return $dateClause;

    }

    private function getUserIdString(){
        $userId= $this->searchFields->userId;
        if($userId=="-1"){
            return "";
        }

        return "CreatorUserID=$userId and ";

    }

    private function getProductNameString(){

        $productName=$this->searchFields->productName;
        if($productName=="-1")
            return "";
        return "dmDetail.Field_0='$productName' and ";

    }



    private function initialAnswers($ar)
    {
        for ($i = 0; $i < 3; $i++)
            for ($j = 0; $j < 7; $j++)
                $ar[$i][$j] = 0;
        return $ar;

    }



}

$mainAjax = new MainAjax();
Response::getInstance()->response = $mainAjax->main();
return $mainAjax;



