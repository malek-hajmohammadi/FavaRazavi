<?php

$detailStID = 61;
$masterID = Request::getInstance()->varCleanFromInput('masterID');
$deptID = Request::getInstance()->varCleanFromInput('deptID');


if (intval($masterID) == 0) {
Response::getInstance()->response = 'شماره فرم نامعتبر مي باشد';
return;
}

if (intval($deptID) == 0) {
    Response::getInstance()->response = 'حوزه نامعتبر مي باشد';
    return;
}


$db = MySQLAdapter::getInstance();

$sql = "update oa_document 
            INNER JOIN `dm_datastoretable_$detailStID` dm on (dm.DocID = oa_document.RowID and oa_document.IsEnable=1 and dm.MasterID = $masterID)
        set oa_document.IsEnable = 0";
$db->execute($sql);

$sql = "select DocID
                from dm_datastoretable_62 dm
                INNER JOIN oa_document on(oa_document.RowID = dm.DocID and oa_document.IsEnable = 1)
                inner join wf_execution on(wf_execution.execution_doc_id = dm.DocID AND wf_execution.is_enable = 1)
                and DocID <> $masterID
                order by Field_8 DESC limit 1 ";
$db->executeSelect($sql);
$row = $db->fetchAssoc();

$insertCount = 0;

if($row && isset($row['DocID'])) {

    $sql = "select Field_0, Field_18, Field_19 
                from dm_datastoretable_$detailStID dm
                INNER JOIN oa_document on(oa_document.RowID = dm.DocID and oa_document.IsEnable = 1)
                where dm.MasterID = $row[DocID]";

    $db->executeSelect($sql);

    $persons = array();
    while ($row = $db->fetchAssoc()) {
        $persons[] = $row;
    }

    foreach ($persons as $person) {

        $form = new DMSNFC_form(array('structid' => null, 'fieldid' => $detailStID, 'docid' => null, 'referid' => null, 'masterid' => $masterID));
        $fdata = array(
            "251" => $person['Field_0'],
            "317" => $person['Field_18'],
            "318" => $person['Field_19'],
            "262" => $person['Field_11']
        );
        if ($form->setData($fdata))
            $insertCount++;
    }
}

if ($insertCount > 0)
    $insertCount = 'success';
else
    $insertCount = 'كاربري براي درج در ليست يافت نشد';

Response::getInstance()->response = $insertCount;


