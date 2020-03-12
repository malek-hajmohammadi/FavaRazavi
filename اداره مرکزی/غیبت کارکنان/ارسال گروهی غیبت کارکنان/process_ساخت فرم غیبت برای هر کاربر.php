<?php

class calssName
{

    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {


        /**
         * get main form users list
         */
        $db = PDOAdapter::getInstance();
        $masterID = $execution->workflow->myForm->instanceID;/*Doc Id form feli migire*/
        $sql = "select dm.*,oa_users.UserID as uid,oa_depts_roles.RowID as rid,oa_depts_roles.DeptID
                from dm_datastoretable_1064 dm
                INNER JOIN oa_document on(oa_document.RowID = dm.DocID and oa_document.IsEnable = 1)
                inner join oa_users on(oa_users.EmployeeID = dm.Field_0)
                inner join oa_depts_roles on(oa_depts_roles.UserID = oa_users.UserID)
                where dm.MasterID = $masterID
                order by dm.Field_0";
        $db->executeSelect($sql);

        $result = array();
        while ($row = $db->fetchAssoc()) {
            $result[] = $row;
        }

        $createdForms=array();



        /**
         * create form for each user
         */
        $mainFormID = 1089;
        foreach ($result as $person) {

            if(isset($createdForms[$person['Field_0']])) {
                $docID = $createdForms[$person['Field_0']];
            }
            else{
                /**
                 * get last active workflow related to mainFormID
                 */
                $workFlowID = $db->executeScalar("SELECT `workflow_id` FROM `wf_workflow` WHERE `workflow_enable` = 1 AND `workflow_formtypeid` =  $mainFormID");
                $resp = WorkFlowManager::stratNewWorkFlow($workFlowID);
                $docID = $resp['docID'];
                $referID = $resp['referID']; //آي دي ارجاع است
                /**
                 * save user data in main form
                 */
                $myMainForm = new DMSNFC_form(array('fieldid' => $mainFormID, 'docid' => $docID));
                $data = [
                    "13371" => [['uid' => $person['uid'], 'rid' => $person['rid']]], //-متقاضی-
                    "13372" => $person['Field_0'], //شماره پرسنلی
                    "13373" => $person['DeptID']// نام واحد
                    /*
                     *
                     */
                ];
                $myMainForm->setData($data);
                //////////////////////////

                $request = Request::getInstance();
                $request->setParameter('referID', $referID);
                $request->setParameter('structID', $mainFormID);
                $request->setParameter('docID', $docID);
                $request->setParameter('commandKey', '1_3');
                $request->setParameter('referNote', 'جهت بررسي');
                ModWorkFlowManager::workflowAction();

/// ////////////////////////////
                $createdForms[$person['Field_0']] = $docID;
            }

            //----ساخت فرم جزء ---
            $myDetailForm = new DMSNFC_form(array('structid' => NULL, 'fieldid' => 1088, 'docid' => NULL, 'referid' => NULL, 'masterid' => $docID));
            $data = [
                "13365" => Date::GregToJalali($person['Field_5'])
                /*
                 *
                 */
            ];
            $myDetailForm->setData($data);



        }


    }//end func

}