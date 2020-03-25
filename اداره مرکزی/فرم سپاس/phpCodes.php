<?php
//--------------کد آیجکس برای ساخت فرم پیامهای سپاس-----------//

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
