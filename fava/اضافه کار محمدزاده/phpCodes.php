<?php

$detailStID = 55;
$masterID = Request::getInstance()->varCleanFromInput('masterID');

$form = new DMSNFC_form(array('structid' => null, 'fieldid' => $detailStID, 'docid' => null, 'referid' => null, 'masterid' => $masterID));
$fdata = array(
    "251" => '111',
    "262" => '222'
);
$form->setData($fdata);
Response::getInstance()->response = '1';