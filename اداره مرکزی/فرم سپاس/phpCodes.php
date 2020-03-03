<?php
//--------------کد آیجکس برای ساخت فرم پیامهای سپاس-----------//

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


/*--------------------------------------------------------------انتهای آیجکس---------------------------------------------------------------*/


/*-----------------------------------------------کد در کارتابل اولی برای داشتن شخص شروع کننده در فرم---------------------------------------------*/

//------------------------------کد در گردش کار متقاضی برای نشان دادن کد پرسنلی و کد ملی----------------------------
class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution, ezcWorkflowNodeAction $caller = null)
    {
        //----

        $Creator = $execution->workflow->myForm->getCreator(); //get creator's record
        $UID = $Creator['uid'];


        $db = MySQLAdapter::getInstance(); //connect to the db
        $sql2 = "SELECT fname,lname FROM oa_users WHERE UserID=" . $UID;
        $db->executeSelect($sql2);
        $person = $db->fetchAssoc();

        $execution->workflow->myForm->setFieldValueByName('Field_18', $person['fname'] . " " . $person['lname']);

    }
}

/*--------------------------کد در گردش کار نود پردازش : ارسال به کارشناس بعدی---------------*/


class calssName
{

    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {

        $actors = $execution->getVariable('actors');

        $actor = $execution->workflow->myForm->getFieldValueByName('Field_21');
        if (is_array($actors)) {
            array_push($actors, $actor[0]);
        } else
            $actors = $actor;

        $execution->setVariable('actors', $actors);
        /*-------------------- save activity desc ---------------------------------*/

        $docID = $execution->workflow->myForm->instanceID;
        $act = $execution->workflow->myForm->getFieldValueByName('Field_22');
        /*--it's the comment of the actor--*/

        $acm = AccessControlManager::getInstance();
        $uid = $acm->getUserID();
        $rid = $acm->getRoleID();
        $person = array(array('uid' => $uid, 'rid' => $rid));

        $date = date('Y-m-d H:i:s');
        $date = explode(' ', $date);
        $date[0] = Date::GregToJalali($date[0]);
        $jdate = implode(' ', $date);

        $myForm = new DMSNFC_form(array('structid' => NULL, 'fieldid' => 1097, 'docid' => NULL, 'referid' => NULL, 'masterid' => $docID));
        $data = ["13479" => $act, "13480" => $jdate, "13481" => $person];
        $myForm->setData($data);
        $execution->workflow->myForm->setFieldValueByName('Field_22', '');
        /*-----------------------------------end save activity  desc ------------------*/


    }//end func

}

/*-------------------کد در نود push که فعلا دقیقا مثله کد ارسال به کارشناس بعدی است------------------------*/

class calssName
{

    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {

        $actors = $execution->getVariable('actors');

        $actor = $execution->workflow->myForm->getFieldValueByName('Field_21');
        if (is_array($actors)) {
            array_push($actors, $actor[0]);
        } else
            $actors = $actor;

        $execution->setVariable('actors', $actors);
        /*-------------------- save activity desc ---------------------------------*/

        $docID = $execution->workflow->myForm->instanceID;
        $act = $execution->workflow->myForm->getFieldValueByName('Field_22');
        /*--it's the comment of the actor--*/

        $acm = AccessControlManager::getInstance();
        $uid = $acm->getUserID();
        $rid = $acm->getRoleID();
        $person = array(array('uid' => $uid, 'rid' => $rid));

        $date = date('Y-m-d H:i:s');
        $date = explode(' ', $date);
        $date[0] = Date::GregToJalali($date[0]);
        $jdate = implode(' ', $date);

        $myForm = new DMSNFC_form(array('structid' => NULL, 'fieldid' => 1097, 'docid' => NULL, 'referid' => NULL, 'masterid' => $docID));
        $data = ["13479" => $act, "13480" => $jdate, "13481" => $person];
        $myForm->setData($data);
        $execution->workflow->myForm->setFieldValueByName('Field_22', '');
        /*-----------------------------------end save activity  desc ------------------*/


    }//end func

}

/*-----------------end----------------------*/

/*-------------کد در گردش کار نود pop----------------*/

class calssName
{

    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {

        /* save activity desc */

        $docID = $execution->workflow->myForm->instanceID;
        $act = $execution->workflow->myForm->getFieldValueByName('Field_22');

        $acm = AccessControlManager::getInstance();
        $uid = $acm->getUserID();
        $rid = $acm->getRoleID();
        $person = array(array('uid' => $uid, 'rid' => $rid));

        $date = date('Y-m-d H:i:s');
        $date = explode(' ', $date);
        $date[0] = Date::GregToJalali($date[0]);
        $jdate = implode(' ', $date);

        $myForm = new DMSNFC_form(array('structid' => NULL, 'fieldid' => 1097, 'docid' => NULL, 'referid' => NULL, 'masterid' => $docID));
        $data = ["13479" => $act, "13480" => $jdate, "13481" => $person];
        $myForm->setData($data);
        $execution->workflow->myForm->setFieldValueByName('Field_22', '');

        /* set next path */
        $actors = $execution->getVariable('actors');
        array_pop($actors);// remove the current actor from actors list for to prevent repetitious refer
        if (count($actors) > 0) {
            // get previews actor if exists
            $actor = array_pop($actors);
            $execution->workflow->myForm->setFieldValueByName('Field_21', array($actor));
            array_push($actors, $actor);
            $execution->setVariable('actors', $actors);
        } else
            $execution->setVariable('actors', 0);

    }//end func

}

/*----------------------end-------------------------------------------------*/

/*----------------------------کد در نود first push-------------------------*/

class calssName
{

    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {

        $actors = $execution->getVariable('actors');

        $actor = $execution->workflow->myForm->getFieldValueByName('Field_20');
        if (is_array($actors)) {
            array_push($actors, $actor[0]);
        } else
            $actors = $actor;

        $execution->setVariable('actors', $actors);
    }
}

/*------------------کد در کارتابل کارشناس اقدام کننده---------------*/

class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution, ezcWorkflowNodeAction $caller = null)
    {
        $execution->workflow->myForm->setFieldValueByName('Field_22', '');
        $execution->workflow->myForm->setFieldValueByName('Field_21', '');
    }
}
