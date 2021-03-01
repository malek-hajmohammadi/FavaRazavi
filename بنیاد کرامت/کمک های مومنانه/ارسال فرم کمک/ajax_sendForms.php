<?php
class MainAjax
{
    private $deptsArray;
    private $userArray;
    private $title;

    public function main()
    {
        $this->getInput();
        $this->gatherUsersInDepts();
        $this->sendForms();
        $this->saveRecordForReport();



      // $address=$address['4723']['uid'];

       //return $this->roleArray;


        return $this->userArray;

    }



    private function gatherUsersInDepts(){

        $count = 0;
        foreach ($this->deptsArray as $dept) {

            $address = AddressBook::GetAddressBookByDeptID($dept);

            foreach ($address as $user) {
                $this->userArray[$count]['uid']  = $user['uid'];
                $this->userArray[$count]['rid']  = $user['roles'][0]['rid'];
                $this->userArray[$count]['rid']  = $user['roles'][0]['name'];
                $this->userArray[$count]['name'] = $user['name'];

                $count++;

            }
        }

    }

    private function getInput(){
        if (Request::getInstance()->varCleanFromInput('depts')) {
            $this->deptsArray = Request::getInstance()->varCleanFromInput('depts');
            $this->deptsArray= json_decode($this->deptsArray, false);
        } else {

            Response::getInstance()->response = "There is no specefic input for depts";
            return;
        }

        if (Request::getInstance()->varCleanFromInput('title')) {
            $this->title = Request::getInstance()->varCleanFromInput('title');

        } else {

            Response::getInstance()->response = "There is no specefic input for title";
            return;
        }



    }

    private function sendForms(){
        $formID = 1419;

        $db = WFPDOAdapter::getInstance();
        $workFlowID = $db->executeScalar("SELECT `workflow_id` FROM `wf_workflow` WHERE `workflow_enable` = 1 AND `workflow_formtypeid` =  $formID");



        foreach ($this->userArray as $person) {
            $puid = $person['uid'];
            $prid = $person['rid'];
            $resp = WorkFlowManager::stratNewWorkFlow($workFlowID);
            $referID = $resp['referID'];
            $docID = $resp['docID'];

            $user = array(array('uid' => $puid, 'rid' => $prid));
            $myForm = new DMSNFC_form(array('fieldid' => $formID, 'docid' => $docID, 'referid' => null));

            $fdata = array(
                "17684" => $user,
                "17688" => $this->title
            );
            $myForm->setData($fdata);

            $sql2 = "SELECT sex FROM oa_users WHERE UserID=".$puid;
            $db->executeSelect($sql2);
            $personGender = $db->fetchAssoc();


            if (intval($personGender['sex']) == 1) $sex = ' آقاي ';
            else $sex = '  خانم ';

            $sql_title = "update oa_document set Subject=concat(Subject,' - " . $sex . $person['name'] . "') where RowID=" . $docID . " limit 1";



            $db->execute($sql_title);



            $request = Request::getInstance();
            echo $referID.':'.$formID.':'.$docID;

            $request->setParameter('referID', $referID);
            $request->setParameter('structID', $formID);
            $request->setParameter('docID', $docID);
            $request->setParameter('commandKey', '1_3');
            $request->setParameter('referNote', 'باسلام اگر مایل به مشارکت در طرح «'.$this->title.'» لطفا این فرم را تکمیل و ارسال نمایید');
            ModWorkFlowManager::workflowAction();

        }



    }

}

$mainAjax = new MainAjax();
Response::getInstance()->response = $mainAjax->main();
return $mainAjax;







class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {
        $masterID = $execution->workflow->myForm->instanceID;
        $db = MySQLAdapter::getInstance();

        $sql = "SELECT
      oa_users.UserID as uid,oa_users.sex as sex,
      concat(oa_users.fname,' ',oa_users.lname) as name,
      oa_depts_roles.RowID as rid ,oa_users.EmployeeID
      FROM oa_users
      INNER JOIN oa_depts_roles ON (oa_depts_roles.UserID = oa_users.UserID and oa_depts_roles.IsEnable = 1)
      INNER JOIN oa_group_members on(oa_group_members.RoleID = oa_depts_roles.RowID and oa_group_members.UserGroupID = 16)
      WHERE oa_users.IsEnable = 1 and oa_depts_roles.IsDefault = 1 and oa_users.UserID not in (1) ";
        $db->executeSelect($sql);

        $persons = array();
        while ($row = $db->fetchAssoc()) {
            $persons[] = $row;
        }

        $formID = 1044;
        $workFlowID = $db->executeScalar("SELECT `workflow_id` FROM `wf_workflow` WHERE `workflow_enable` = 1 AND `workflow_formtypeid` =  $formID");

        foreach ($persons as $person) {
            $puid = $person['uid'];
            $prid = $person['rid'];
            $resp = WorkFlowManager::stratNewWorkFlow($workFlowID);
            $referID = $resp['referID'];
            $docID = $resp['docID'];

            $user = array(array('uid' => $puid, 'rid' => $prid));
            $myForm = new DMSNFC_form(array('fieldid' => $formID, 'docid' => $docID, 'referid' => null));

            $fdata = array(
                "12917" => $user,
            );

            $myForm->setData($fdata);

            if (intval($person['sex']) == 1) $sex = ' آقاي ';
            else $sex = '  خانم ';

            $sql_title = "update oa_document set Subject=concat(Subject,' - " . $sex . $person['name'] . "') where RowID=" . $docID . " limit 1";
            $db->execute($sql_title);

            $request = Request::getInstance();

            $request->setParameter('referID', $referID);
            $request->setParameter('structID', $formID);
            $request->setParameter('docID', $docID);
            $request->setParameter('commandKey', '1_3');
            $request->setParameter('referNote', 'باسلام اگر مایل به مشارکت در طرح «آقا حساب كرده‌اند» لطفا این فرم را تکمیل و ارسال نمایید');
            ModWorkFlowManager::workflowAction();

        }
    }
}


