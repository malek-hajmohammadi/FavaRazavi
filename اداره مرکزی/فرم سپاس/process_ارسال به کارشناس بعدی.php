<?php


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

