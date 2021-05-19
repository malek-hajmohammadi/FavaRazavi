<?php


class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {


        $masterID = $execution->workflow->myForm->instanceID;
        $detailStID = 792;
        $db = WFPDOAdapter::getInstance();

        $sql = "SELECT dm.*,
                      oa_users.UserID as uid,
                      oa_depts_roles.RowID as rid,
                      concat(oa_users.fname,' ',oa_users.lname) as name
                  FROM `dm_datastoretable_$detailStID` dm 
                      INNER JOIN `oa_document` on (dm.DocID = oa_document.RowID)
                      INNER JOIN oa_users ON (dm.Field_0 = concat(REVERSE(SUBSTRING(REVERSE(concat('0000',oa_users.EmployeeID)),1,8))) and oa_users.IsEnable = 1)
                      INNER JOIN oa_depts_roles ON (oa_depts_roles.UserID = oa_users.UserID and oa_depts_roles.IsDefault = 1)
                  WHERE dm.MasterID = $masterID  and oa_document.IsEnable=1";
        $db->executeSelect($sql);

        $persons = array();
        while ($row = $db->fetchAssoc()) {
            $persons[] = $row;
        }

        $year = $execution->workflow->myForm->getFieldValueByName('Field_3');
        $year = intval($year) + 1399;
        $month = $execution->workflow->myForm->getFieldValueByName('Field_4');
        foreach ($persons as $person) {

            $puid = $person['uid'];
            $prid = $person['rid'];
            $formID = 843;
            //بدست اوردن شماره كردشكار جاري
            $workFlowID = $db->executeScalar("SELECT `workflow_id` FROM `wf_workflow` WHERE `workflow_enable` = 1 AND `workflow_formtypeid` =  $formID");

            $resp = WorkFlowManager::stratNewWorkFlow($workFlowID);

            $referID = $resp['referID'];
            $docID = $resp['docID'];

            $user = array(array('uid'=>$puid , 'rid'=>$prid));

            $myForm = new DMSNFC_form(array('fieldid' => $formID, 'docid' => $docID, 'referid' => null));

            $fdata = array(
                "8299" => $person['Field_0'],
                "8302" => $person['Field_12'],
                "8303" => $person['Field_3'],
                "8304" => $person['Field_5'],
                "8305" => $person['Field_6'],
                "8306" => $person['Field_16'],
                "8307" => $person['Field_17'],
                "8308" => $person['Field_15'],
                "8309" => $person['Field_14'],
                "8310" => $person['Field_13'],
                "8311" => $person['Field_18'],
                "8312" => $user,
                "8313" => $year,
                "8315" => $month
            );
            $myForm->setData($fdata);

            $sql_title = "update oa_document set Subject=concat(Subject,'_" . $person['name'] . "') where RowID=" . $docID . "  limit 1";
            $db->execute($sql_title);

            $request = Request::getInstance();

            $request->setParameter('referID', $referID);
            $request->setParameter('structID', $formID);
            $request->setParameter('docID', $docID);
            $request->setParameter('commandKey','1_3');
            $request->setParameter('referNote','احتراما جهت استحضار');
            ModWorkFlowManager::workflowAction();


        }//end while



    }
}


