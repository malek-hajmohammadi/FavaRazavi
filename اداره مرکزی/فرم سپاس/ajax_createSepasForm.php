<?php

$name = "name";
$family = "family";
$nationalCode = "nationalCode";
$birthDate = "1398/05/05";
$tel = "tel";
$mobile = "mobile";
$city = "city";
$province = "province";
$gender = "gender";
$ageGroup = "ageGroup";
$subject = "subject";
$message = "message";
$priority = "priority";
$tags = "tags";
$situationPlace = "situationPlace";
$grouping = "grouping";
$registerDate = "1398/05/05";
$relatedDomain = "relatedDomain";
$employeeID = 0;
if (Request::getInstance()->varCleanFromInput('name'))
    $name = Request::getInstance()->varCleanFromInput('name');
if (Request::getInstance()->varCleanFromInput('family'))
    $family = Request::getInstance()->varCleanFromInput('family');
if (Request::getInstance()->varCleanFromInput('nationalCode'))
    $nationalCode = Request::getInstance()->varCleanFromInput('nationalCode');
if (Request::getInstance()->varCleanFromInput('birthDate'))
    $birthDate = Request::getInstance()->varCleanFromInput('birthDate');
if (Request::getInstance()->varCleanFromInput('tel'))
    $tel = Request::getInstance()->varCleanFromInput('tel');
if (Request::getInstance()->varCleanFromInput('mobile'))
    $mobile = Request::getInstance()->varCleanFromInput('mobile');
if (Request::getInstance()->varCleanFromInput('city'))
    $city = Request::getInstance()->varCleanFromInput('city');
if (Request::getInstance()->varCleanFromInput('province'))
    $province = Request::getInstance()->varCleanFromInput('province');
if (Request::getInstance()->varCleanFromInput('gender'))
    $gender = Request::getInstance()->varCleanFromInput('gender');
if (Request::getInstance()->varCleanFromInput('ageGroup'))
    $ageGroup = Request::getInstance()->varCleanFromInput('ageGroup');
if (Request::getInstance()->varCleanFromInput('subject'))
    $subject = Request::getInstance()->varCleanFromInput('subject');
if (Request::getInstance()->varCleanFromInput('message'))
    $message = Request::getInstance()->varCleanFromInput('message');
if (Request::getInstance()->varCleanFromInput('priority'))
    $priority = Request::getInstance()->varCleanFromInput('priority');
if (Request::getInstance()->varCleanFromInput('tags'))
    $tags = Request::getInstance()->varCleanFromInput('tags');
if (Request::getInstance()->varCleanFromInput('situationPlace'))
    $situationPlace = Request::getInstance()->varCleanFromInput('situationPlace');
if (Request::getInstance()->varCleanFromInput('grouping'))
    $grouping = Request::getInstance()->varCleanFromInput('grouping');
if (Request::getInstance()->varCleanFromInput('registerDate'))
    $registerDate = Request::getInstance()->varCleanFromInput('registerDate');
if (Request::getInstance()->varCleanFromInput('relatedDomain'))
    $relatedDomain = Request::getInstance()->varCleanFromInput('relatedDomain');


//---------------make draft email--------------------------------------//
///////////////////////////////////////////////////////


$formID = 1095;
$db = MySQLAdapter::getInstance();
$workFlowID = $db->executeScalar("SELECT `workflow_id` FROM `wf_workflow` WHERE `workflow_enable` = 1 AND `workflow_formtypeid` =  $formID");
$resp = WorkFlowManager::stratNewWorkFlow($workFlowID);
$referID = $resp['referID']; //آي دي ارجاع است
$docID = $resp['docID'];

//////////////////////////////////////////////////////////

$fdata = array(

    "13431" => $name,
    "13432" => $family,
    "13433" => $nationalCode,
    "13434" => $birthDate,
    "13435" => $tel,
    "13436" => $mobile,
    "13437" => $city,
    "13438" => $province,
    "13439" => $gender,
    "13440" => $ageGroup,
    "13441" => $subject,
    "13442" => $message,
    "13443" => $relatedDomain,
    "13444" => $priority,
    "13445" => $tags,
    "13446" => $situationPlace,
    "13447" => $grouping,
    "13448" => $registerDate

);
$myForm = new DMSNFC_form(array('fieldid' => $formID, 'docid' => $docID, 'referid' => null));

$myForm->setData($fdata);


