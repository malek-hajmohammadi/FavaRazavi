<?php
class MainAjax
{


    private $docId='';
    public function main()
    {

       $this->getInput();
        $output=$this->getData();
        return $output;

    }
    private function getInput(){

        if (Request::getInstance()->varCleanFromInput('docId')) {
            $this->docId = Request::getInstance()->varCleanFromInput('docId');
        }

        return false;


    }

    private function getData(){
        $db = WFPDOAdapter::getInstance();
        $test = '';

//        $sql = "select * FROM dm_datastoretable_37 where MasterID=$this->docId ORDER BY RowID";
//        $db->executeSelect($sql);

     //   $row = $db->fetchAssoc();
    //    $detailedFormDocId = $row['DocID'];
     //   $test .= $detailedFormDocId . '--';
        $sq = "SELECT * FROM `vi_form_userrole` where true  ";
        //docID='$masterFormDocId'  and `FieldName`='Field_1'
        $db->executeSelect($sq);

        while ($subRow = $db->fetchAssoc()) {
            // $dataInTable[$count] = array($row['Field_0'], $row['Field_1'], $row['Field_2']);
            $test .= $subRow['uid'] . ' ' . $subRow['rid'] . '---';

        }
        return $test;

    }

}

$mainAjax = new MainAjax();
Response::getInstance()->response = $mainAjax->main();
return $mainAjax;





