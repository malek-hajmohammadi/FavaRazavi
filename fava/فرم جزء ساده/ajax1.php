<?php


$masterId = Request::getInstance()->varCleanFromInput('masterId');

$form = new DMSNFC_form(array('structid' => null, 'fieldid' => 871, 'docid' => null, 'referid' => null, 'masterid' => $masterId));
$fdata = array(
    "4092" => "Malek",
    "4093" => "Hajmohammadi",
    "4094" => "09155227340"
);
if($form->setData($fdata))
    $return='success';
else
    $return='fail';

Response::getInstance()->response = $return;

/*******************
setData: is to add the record to the detailed form.
 * the array,$fdata,has indexes for its array which is rowId. you can see them in explorer
 * masterId is the id of the parent form which is active and the user has used it. By FormView.did or docId I can access to that.
 * fieldid is the id of  detailed form which is fix so we chan set it as an constant.
 * That's a good habit to return 'success' or 'fail' as a result  to parent form.
 * */