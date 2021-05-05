<?php

class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {
        $needToSetUpWorkGroupUsers = $execution->getVariable('needToSetUpWorkGroupUsers');

        if ($needToSetUpWorkGroupUsers == 1) {

            $this->setUpWorkGroupUsers($execution);
            $execution->setVariable('needToSetUpWorkGroupUsers', 0);
        } else {

            $this->currentProcess($execution);
        }
    }

    private function currentProcess(ezcWorkflowExecution $execution)
    {
        $workGroupUsers = $execution->getVariable('workGroupUsers');

        $workGroupUsersCounter = $execution->getVariable('workGroupUsersCounter');
        $workGroupUsersCounter++;//we start it by -1 in previous cable
        $execution->setVariable('workGroupUsersCounter',$workGroupUsersCounter);


        $continueWorkGroup = 0; //flag to continue

        $workGroupUsersSize=count($workGroupUsers);

        if ($workGroupUsersSize > $workGroupUsersCounter ) {
            $continueWorkGroup =$workGroupUsersSize-$workGroupUsersCounter ;

            //128
            $actor = array(array('uid' => $workGroupUsers[$workGroupUsersCounter][0], 'rid' => $workGroupUsers[$workGroupUsersCounter][1]));
            $execution->workflow->myForm->setFieldValueByName('Field_128', $actor);


            /* $test='';
             for($i=0;$i<$workGroupUsersSize;$i++) {
                 $test .= 'uid:' . $workGroupUsers[$i][0] . ' rid:' . $workGroupUsers[$i][1].'||';
             }*/


            //$execution->workflow->myForm->setFieldValueByName('Field_8', $test.'workgroupCounter:'.$workGroupUsersCounter.'-');

        }

        $execution->setVariable('continueWorkGroup', $continueWorkGroup);

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



