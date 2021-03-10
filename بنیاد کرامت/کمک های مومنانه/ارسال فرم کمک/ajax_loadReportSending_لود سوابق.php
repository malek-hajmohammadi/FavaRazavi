<?php

class MainAjax
{


    private $inputParams;
    const PAGE_LENGTH = 20;

    public function main()
    {
        $this->getInputArguments();

        $dataFromDatabase = $this->getDataFromDatabase();

        $endHtml = $this->pagingPart() . $this->header() . $this->body($dataFromDatabase) . $this->footer();

        return $endHtml;

    }

    private function getInputArguments()
    {

        if (Request::getInstance()->varCleanFromInput('inputParams')) {
            $this->inputParams = Request::getInstance()->varCleanFromInput('inputParams');
            $this->inputParams = json_decode($this->inputParams, false);
        } else {

            Response::getInstance()->response = "There is no specefic input";
            return;
        }
        $this->getPageNumber();

    }

    private function getPageNumber()
    {
        $pageNumber = $this->inputParams->pageNumber;
        $pageNumber = intval($pageNumber);
        if ($pageNumber == 0)
            $pageNumber = 1;
        $this->inputParams->pageNumber = $pageNumber;
        $this->inputParams->rowsStart = ($pageNumber - 1) * self::PAGE_LENGTH;
        $this->inputParams->rowsEnd = $pageNumber * self::PAGE_LENGTH;
    }


    private function getDataFromDatabase()
    {


        $dataInTable = array();
        $db = WFPDOAdapter::getInstance();
        $sql = "select dm.Field_0,dm.Field_1,dm.Field_2,dm.Field_3 FROM dm_datastoretable_1421 as dm ";
        $sqlLimit = "ORDER BY dm.RowID DESC limit {$this->inputParams->rowsStart}," . $this->inputParams->rowsEnd;

        $sql = $sql . $sqlLimit;
        $db->executeSelect($sql);

        $count = 0;
        while ($row = $db->fetchAssoc()) {
            $dataInTable[$count] = array(
                $row['Field_0'], $row['Field_1'], $row['Field_2'], $row['Field_3']

            );
            $count++;
        }


        $sqlC = "select count(*) as ct FROM dm_datastoretable_1421 as dm ";


        $sqlC = $sqlC;// . $sqlLimit;
        $db->execute($sqlC);
        $res = $db->fetchAssoc();


        $this->inputParams->sumCount = $res['ct'];
        $this->inputParams->pages = ceil($this->inputParams->sumCount / self::PAGE_LENGTH);


        return $dataInTable;
    }


    private function pagingPart()
    {

        $html = '
<div style="text-align: center;padding: 2px;">
    <span style="float: right;padding: 5px;font-weight: bold;">' . $this->inputParams->sumCount . ' مورد يافت شد</span>
    <button onclick="window.codeSet.prevPage()" id="prevPage">صفحه قبل </button>
    صفحه
    <input id="pageNumber" class="f-input" tabindex="6001" style="width: 50px;" value="' . $this->inputParams->pageNumber . '">
    از ' . $this->inputParams->pages . '
    <input type="hidden" value="' . $this->inputParams->pages . '" id="maxPage" >
    <button onclick="window.codeSet.getReport()" id="showPage" style="background-color:#b7f7ab">نمايش صفحه وارد  شده  </button>
    <button tabindex="6001" onclick="window.codeSet.nextPage()" onkeydown="return FormBuilder.designFormModal.setLFocus(event)" id="textPage">صفحه بعد </button> </td></div>';
        return $html;

    }

    private function header()
    {

        $html = '
                <table width="100%" class="f-table accessTable" cellpadding="0" cellspacing="1" >     
                    <tbody>
                        <tr>
                            <th width="3%" style="padding: 2px; ">رديف</th>
                            <th width="10%" style="padding: 2px; ">تاریخ</th>
                            <th width="10%" style="padding: 2px; ">عنوان</th>
                            <th width="10%" style="padding: 2px; ">حوزه های هدف</th>
                            <th width="10%" style="padding: 2px; ">تعداد فرم های ارسالی</th>
                            
                            
                        </tr>';

        return $html;

    }

    private function body($dataFromDatabase)
    {
        $radif = 0 + (($this->inputParams->pageNumber - 1) * self::PAGE_LENGTH);
        $bodyPart = "";

        foreach ($dataFromDatabase as $value) {
            $radif++;

            $createDate = $value[0];
            $createDate = Date::GregToJalali((new DateTime($createDate))->format('Y-m-d'));

            $title = $value[1];

            $destinationString = $value[2];

            $lengthOfDsc = strlen($destinationString);
            $shortDsc = $lengthOfDsc > 50 ? substr($destinationString, 0, 50) . "..." : $destinationString;

            $numberOfForms = $value[3];

            $bodyPart .= '<tr id="accessRow_' . ($radif) . '" >
                        <td style="padding: 2px;border: 1px solid #ccc;" >' . $radif . '</td>
                        <td style="padding: 2px;border: 1px solid #ccc;" >' . $createDate . '</td>
                        <td style="padding: 2px;border: 1px solid #ccc;"  >' . $title . '</td>
                        <td style="padding: 2px;border: 1px solid #ccc;" title="' . $destinationString . '">' . $shortDsc . '</td>
                        <td style="padding: 2px;border: 1px solid #ccc;" >' . $numberOfForms . '</td>
                       
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

}

$mainAjax = new MainAjax();
Response::getInstance()->response = $mainAjax->main();
return $mainAjax;


