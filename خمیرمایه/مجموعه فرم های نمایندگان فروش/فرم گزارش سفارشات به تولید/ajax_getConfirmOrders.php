<?php

class MainAjax
{

    private $showMode; //value can be edit or readOnly
    private $docId;
    private $totalPrice = 0;
    private $searchFields;
   
    const PAGE_LENGTH = 20;

    public function main()
    {
        $this->getInputArguments();


        $dataFromDatabase = $this->getDataFromDatabaseV2();


        $endHtml = $this->header() . $this->body($dataFromDatabase) . $this->footer();

        return $endHtml;


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
        $this->getPageNumber();


        /*
                $pageNumber = $this->searchFields['pageNumber'];
                $pageNumber = intval($pageNumber);
                if ($pageNumber == 0)
                    $pageNumber = 1;
                $this->searchFields['pageNumber']=$pageNumber;
                $this->searchFields['rowsStart']=($pageNumber-1)*10;*/


        // $rowsStart = ($pageNumber - 1) * 50;

    }

    private function getPageNumber()
    {
        $pageNumber = $this->searchFields->pageNumber;
        $pageNumber = intval($pageNumber);
        if ($pageNumber == 0)
            $pageNumber = 1;
        $this->searchFields->pageNumber = $pageNumber;
        $this->searchFields->rowsStart = ($pageNumber - 1) * self::PAGE_LENGTH;
    }

    private function whereClause()
    {
        $whereClause = " where ";
        $formNumberSt = $this->getFormNumberString();
        $firstDate = $this->getFirstDateString();
        $endDate = $this->getEndDateString();
        $cableState = $this->getCableState();
        $userIdSt = $this->getUserIdString();
        $productNameSt = $this->getProductNameString();
        $confirmState=$this->getConfirmState();

        $whereClause = $whereClause . $formNumberSt . $firstDate . $endDate . $cableState .
            $confirmState. $userIdSt . $productNameSt;
        $whereClause .= "1 ";

        return $whereClause;

    }

    private function getFormNumberString()
    {
        $formNumber = $this->searchFields->formNumber;
        $formNumber = trim($formNumber);
        if ($formNumber != -1 && is_numeric($formNumber))
            return "dm.DocID=$formNumber and ";
        return "";
    }

    private function getFirstDateString()
    {
        $dateClause = "";

        $firstDate = $this->searchFields->firstDate;

        if ($firstDate == "-1" || strlen($firstDate) < 8)
            return "";

        $firstDate = Date::JalaliToGreg($firstDate);
        $dateClause .= "createDate >= '$firstDate' and ";
        return $dateClause;
    }

    private function getEndDateString()
    {
        $dateClause = "";


        $endDate = $this->searchFields->endDate;
        if ($endDate == "-1" || strlen($endDate) < 8)
            return "";

        $endDate = Date::JalaliToGreg($endDate);
        $dateClause .= "createDate <= '$endDate' and ";
        return $dateClause;

    }

    private function getCableState()
    {
        $cableStateString = "";
        $state = "";
        $cableState = $this->searchFields->cableState;
        if ($cableState == "-1")
            return "";

        switch ($cableState) {
            case "2":
                $state = "namayandeh";
                break;
            case "3":
                $state = "raiesFroosh";
                break;
            case "4":
                $state = "mali";
                break;
            case "5":
                $state = "modirMali";
                break;
            case "6":
                $state = "modirBazargani";
                break;
            case "7":
                $state = "modirAmel";
                break;
            case "8":
                $state = "anbardar";
                break;
            case "9":
                $state = "pakhsh";
                break;
            case "10":
                $state = "entezamat";
                break;
            case "11":
                $state = "namayandehNahayee";
                break;
            case "12":
                $state = "pakhshAfterNamayandehNahayee";
                break;
            case "13":
                $state = "pakhshDarAdamDaryaft";
                break;

        }

        $cableStateString = "Field_5='$state' and ";
        return $cableStateString;

    }

    private function getConfirmState(){
        $confirmState = $this->searchFields->confirmState;
        if ($confirmState == "-1")
            return "";
        $confirmStateString = "Field_16=1 and ";
        return $confirmStateString;

    }

    private function getUserIdString()
    {
        $userId = $this->searchFields->userId;
        if ($userId == "-1") {
            return "";
        }

        return "CreatorUserID=$userId and ";

    }

    private function getProductNameString()
    {

        $productName = $this->searchFields->productName;
        if ($productName == "-1")
            return "";
        return "dmDetail.Field_0='$productName' and ";

    }

    private function getDataFromDatabaseV2()
    {
        $dataInTable = array();

        $db = PDOAdapter::getInstance();

        $stForHavingProductName = "";
        $whereOut="";
        if ($this->searchFields->productName != "-1") {
            $productName = $this->searchFields->productName;
            $stForHavingProductName = "INNER JOIN dm_datastoretable_24 as dmDetail on (dmDetail.MasterID=dm.docid) ";
            $whereOut="and Field_0='$productName'";
        }


        $sqlIn = "select dm.DocID FROM dm_datastoretable_25 as dm " .
            "INNER JOIN oa_document on (oa_document.rowid = dm.docId) " . $stForHavingProductName;

        $sqlWhereClause = $this->whereClause();
        $sqlIn = $sqlIn . $sqlWhereClause;


        $sqlOut="SELECT SUM(Field_4) as sm,Field_0,Field_1 FROM `dm_datastoretable_24` WHERE MasterID in ($sqlIn) $whereOut GROUP BY Field_0,Field_1";

       // die($sqlOut);

        $db->executeSelect($sqlOut);

        $count = 0;
        while ($row = $db->fetchAssoc()) {
            $dataInTable[$count] = array(
                $row['sm'], $row['Field_0'], $row['Field_1']
            );
            $count++;
        }

        return $dataInTable;


    }
    private function getDataFromDatabase()
    {


        $dataInTable = array();
        $stForHavingProductName = "";

        $db = PDOAdapter::getInstance();

        if ($this->searchFields->productName != "-1")
            $stForHavingProductName = "INNER JOIN dm_datastoretable_24 as dmDetail on (dmDetail.MasterID=dm.docid) ";

        $sql = "select dm.Field_0,dm.Field_1,dm.Field_2,dm.Field_3,dm.Field_4,dm.Field_5,dm.DocID,CreateDate FROM dm_datastoretable_25 as dm 
INNER JOIN oa_document on (oa_document.rowid = dm.docId) " . $stForHavingProductName;
        $sqlWhereClause = $this->whereClause();
        $sqlLimit = "ORDER BY dm.RowID limit {$this->searchFields->rowsStart}," . self::PAGE_LENGTH;
        /*
        $PDOParams = array(
            array('name' => 'docId', 'value' => $this->docId, 'type' => PDO::PARAM_INT)
        );
*/


        $sql = $sql . $sqlWhereClause . $sqlLimit;

        $db->executeSelect($sql);

        $count = 0;
        while ($row = $db->fetchAssoc()) {
            $dataInTable[$count] = array(
                $row['Field_0'], $row['Field_1'], $row['Field_2'], $row['Field_3'],
                $row['Field_4'], $row['Field_5'], $row['DocID'], $row['CreateDate']
            );
            $count++;
        }

        /*برای شمردن تعداد جهت استفاده در صفحه بندی*/
        if ($this->searchFields->productName != "-1")
            $stForHavingProductName = "INNER JOIN dm_datastoretable_24 as dmDetail on (dmDetail.MasterID=dm.docid) ";

        $sqlC = "select count(*) as ct FROM dm_datastoretable_25 as dm 
INNER JOIN oa_document on (oa_document.rowid = dm.docid) " . $stForHavingProductName;
        $sqlWhereClause = $this->whereClause();
        $sqlLimit = "ORDER BY dm.RowID limit {$this->searchFields->rowsStart}," . self::PAGE_LENGTH;

        $sqlC = $sqlC . $sqlWhereClause . $sqlLimit;
        $db->execute($sqlC);
        $res = $db->fetchAssoc();


        $this->searchFields->sumCount = $res['ct'];
        $this->searchFields->pages = ceil($this->searchFields->sumCount / self::PAGE_LENGTH);


        return $dataInTable;
        //return $sql;
    }




    private function header()
    {

        $html = '
                <table width="100%" class="f-table accessTable" cellpadding="0" cellspacing="1" >     
                    <tbody>
                        <tr>
                            <th width="3%" style="padding: 2px; ">رديف</th>
                            <th width="30%" style="padding: 2px; ">نام محصول</th>
                            <th width="10%" style="padding: 2px; ">واحد</th>
                            <th width="10%" style="padding: 2px; ">جمع سفارش</th>
                        </tr>';

        return $html;

    }

    private function body($dataFromDatabase)
    {
        $radif = 0 + (($this->searchFields->pageNumber - 1) * self::PAGE_LENGTH);
        $bodyPart = "";
        $table = "";
        /*      1:barrasi 2:mojaz 3:na motaber        */
        foreach ($dataFromDatabase as $value) {
            $radif++;
            $productName = $value[1];
            $productUnit = $value[2];
            $sum=number_format($value[0]);

            $bodyPart .= '<tr id="accessRow_' . ($radif) . '" >
                        <td style="padding: 2px;border: 1px solid #ccc;" >' . $radif . '</td>
                        <td style="padding: 2px;border: 1px solid #ccc;text-align: right;padding-right: 10px" >' . $productName . '</td>
                        <td style="padding: 2px;border: 1px solid #ccc;" >' . $productUnit . '</td>
                        <td style="padding: 2px;border: 1px solid #ccc;direction: ltr;text-align: left;padding-left: 10px" >' . $sum . '</td>
                      
                       
                         </tr>';
        }
        return $bodyPart;
    }

    private function footer()
    {
        $ft = ' </tbody>
                </table>';
        return $ft;

    }

    /*تنظیم کردن مرحله فرم یا کارتابل*/
    private function getFormState($stateInDb)
    {
        $state = "";
        switch ($stateInDb) {
            case "namayandeh":
                $state = "نماینده جهت درخواست";
                break;
            case "raiesFroosh":
                $state = "کارتابل رئیس فروش";
                break;
            case "mali":
                $state = "امور مالی";
                break;
            case "modirMali":
                $state = "مدیر مالی";
                break;
            case "modirBazargani":
                $state = "مدیر بازرگانی";
                break;
            case "modirAmel":
                $state = "مدیر عامل";
                break;
            case "anbardar":
                $state = "انباردار";
                break;
            case "pakhsh":
                $state = "پخش";
                break;
            case "entezamat":
                $state = "انتظامات";
                break;
            case "namayandehNahayee":
                $state = "نماینده (دریافت کالاها)";
                break;
            case "pakhshAfterNamayandehNahayee":
                $state = "پخش (بعد از ارسال کالا)";
                break;
            case "pakhshDarAdamDaryaft":
                $state = "پخش (بررسی عدم دریافت)";
                break;


        }

        return $state;


    }

}

$mainAjax = new MainAjax();
Response::getInstance()->response = $mainAjax->main();
return $mainAjax;



