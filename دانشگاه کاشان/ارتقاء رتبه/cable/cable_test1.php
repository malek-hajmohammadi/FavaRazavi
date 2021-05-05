<?php

class calssName
{

    private $workGroupUsers;
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {


    }
    private function setUpWorkGroupUsers(ezcWorkflowExecution $execution){
        $this->getListOfDetailForm($execution);
        $execution->setVariable('workGroupUsers', $this->workGroupUsers);


        $execution->setVariable('workGroupUsersCounter', -1);
        $execution->setVariable('workGroupConfirmCount', 0);
    }

    private function getListOfDetailForm($execution)
    {

        $docId = $execution->workflow->myForm->instanceID;
        $db = WFPDOAdapter::getInstance();

        $test = '';


        $sql = "select vi.uid,vi.rid from vi_form_userrole vi
inner join dm_datastoretable_37 dm on(dm.DocID = vi.docID)
where dm.MasterID = $docId" ;

        $db->executeSelect($sql);



        $count=0;
        while ($row = $db->fetchAssoc()) {

            $this->workGroupUsers[$count] = array($row['uid'], $row['rid']);
            $test .= $row['uid'] . ' ' . $row['rid'] . '---';
            $count++;

        }
        $execution->workflow->myForm->setFieldValueByName('Field_8', $test);

    }

}




