<?php
//----------------------------------------------------کد در گردش کار ارسال گروهی ، نود پردازش----------------------------------------


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
                //////////////////////////to send to next cable
                /*
                $request = Request::getInstance();
                $request->setParameter('referID', $referID);
                $request->setParameter('structID', $mainFormID);
                $request->setParameter('docID', $docID);
                $request->setParameter('commandKey', '1_3');
                $request->setParameter('referNote', 'جهت بررسي');
                ModWorkFlowManager::workflowAction();
                */
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

//--نود پردازش کپی دوباره از اتوماسیون--//
class calssName
{

    public $showReturn=" ";
    public function __construct()
    {
    }

    public function addToToral($deptID, $startDate, $endDate,$kind){
        //EXEC [TimeRecordMV].[dbo].[uAbsents] '00007109', '1398/09/02', '1398/09/03', 4
//        $startDate = '1398/09/02';
//        $endDate = '1398/09/03';
//        $kind = 4;
//        $deptID='00008999';
        $client = new SoapClient('http://10.10.100.15/WSTuralInOut/TuralInOut.asmx?wsdl');
        $s1 = "EXEC [TimeRecordMV].[dbo].[uAbsents] '$deptID', '$startDate', '$endDate',$kind";
        $param = array('username' => '8bfc0e61722d9e9c9bb2138cb359fef9', 'password' => '085c734188fb09a96eba5d22893a44c4', 'objStr' => $s1);



        $resp1 = $client->RunSelectQuery($param);

        $resp1 = json_decode(json_encode($resp1), true);

        $result = $resp1['RunSelectQueryResult']['cols']['recs']['string'];

        $this->showReturn.="Resulf of Toral:$result \n";
        $this->showReturn.="DeptID:$deptID ,StartDate:$startDate,endDate:$endDate ,Kind:$kind \n";



    }

    public function execute(ezcWorkflowExecution $execution)
    {

        $kind=$execution->workflow->myForm->getFieldValueByName('Field_3');
        $roleId=$execution->workflow->myForm->getFieldValueByName('Field_1');

        $db = PDOAdapter::getInstance();

        $masterID = $execution->workflow->myForm->instanceID;/*Doc Id form feli migire*/
        //filerecorder::recorder("1**********21****".var_export($moa,true),"homaaa");
        $sql = "select dm.*
                from dm_datastoretable_1088 dm /*--آی دی فرم جزء--*/
                INNER JOIN oa_document on(oa_document.RowID = dm.DocID and oa_document.IsEnable = 1)
                where dm.MasterID = $masterID
                order by dm.Field_0";
        $db->executeSelect($sql);

//        addToToral($deptID, $startDate, $endDate,$kind)
        while ($dateAbsence = $db->fetchAssoc()) {

            $date=Date::GregToJalali((new DateTime($dateAbsence['Field_0']))->format('Y-m-d'));
            $this->addToToral($roleId, $date,$date,$kind);


        }
        Response::getInstance()->response =$this->showReturn;
    }
}



/*-------------------------------------------------------------کد آیجکس غیبت کارکنان-----------------------------------------------------------------*/





$deptID = Request::getInstance()->varCleanFromInput('pid');
$year = Request::getInstance()->varCleanFromInput('y');
$month = Request::getInstance()->varCleanFromInput('m');
$DocID = Request::getInstance()->varCleanFromInput('mid');
if(intval($DocID) == 0) {
    Response::getInstance()->response = 'invalid doc id';
    return;
}

$client = new SoapClient('http://10.10.100.15/WSTuralInOut/TuralInOut.asmx?wsdl');
$s1 = "EXEC [TimeRecordMV].[dbo].[sAbsents] '$deptID', $year, $month";
$param=array('username' => '8bfc0e61722d9e9c9bb2138cb359fef9','password'=>'085c734188fb09a96eba5d22893a44c4','objStr' =>$s1);


$resp1 = $client->RunSelectQuery($param);

$resp1 = json_decode(json_encode($resp1), true);


$db = MySQLAdapter::getInstance();

$sql = "update oa_document 
            INNER JOIN `dm_datastoretable_1064` dm on (dm.DocID = oa_document.RowID and oa_document.IsEnable=1 and dm.MasterID = $DocID)
        set oa_document.IsEnable = 0";

$db->execute($sql);

$res = urldecode(json_encode($resp1));
$res = json_decode($res, true);
$res = $res['RunSelectQueryResult']['cols'];
$eids = array();
foreach( $res as $user)
{
    $eids[] = ltrim( $user['recs']['string'][1], '0');
}
$eids = implode(',', $eids);
$sql = "select oa_users.employeeID, oa_depts_roles.RowID as rid 
              from oa_users
                inner join oa_depts_roles on(oa_depts_roles.UserID = oa_users.UserID)
                where oa_users.employeeID in ($eids)
                ";
$db->executeSelect($sql);
$userRoles = array();
while ($row = $db->fetchAssoc()){
    $userRoles[$row['employeeID']] = $row['rid'];
}

$i =  0;
foreach( $res as $user)
{

    $formID =1064 ;
    $myForm = new DMSNFC_form(array('fieldid' => $formID, 'masterid' => $DocID));
    $employeeID = ltrim( $user['recs']['string'][1], '0');
    if(!isset($userRoles[$employeeID]))
        continue;
    $data = [
        "13115" => $employeeID,
        "13116" => $user['recs']['string'][2],
        "13117" => $user['recs']['string'][3],
        "13187" => $user['recs']['string'][4],

    ];
    $myForm->setData($data);
    $i++;
}
Response::getInstance()->response = $res;


/*-------------------------------------------------------دکمه نمایش گزارش-----------------------------------------------------*/

class calssName
{
    public function execute($self)
    {
        $res = '<button id="searchbtn" type="button"  onclick="FormView.myForm.getItemByName(\'Field_7\').search(FormView)">گزارش</button>';
        return array("res" => $res);
    }
}

/*--------------------------------------نود پردازش برای ثبت اصلاح غیبت در تورال-----------------------------------------*/
class calssName
{

    public $showReturn=" ";
    public function __construct()
    {
    }

    public function addToToral($deptID, $startDate, $endDate,$kind){
        //EXEC [TimeRecordMV].[dbo].[uAbsents] '00007109', '1398/09/02', '1398/09/03', 4
//        $startDate = '1398/09/02';
//        $endDate = '1398/09/03';
//        $kind = 4;
//        $deptID='00008999';
        $client = new SoapClient('http://10.10.100.15/WSTuralInOut/TuralInOut.asmx?wsdl');
        $s1 = "EXEC [TimeRecordMV].[dbo].[uAbsents] '$deptID', '$startDate', '$endDate',$kind";
        $param = array('username' => '8bfc0e61722d9e9c9bb2138cb359fef9', 'password' => '085c734188fb09a96eba5d22893a44c4', 'objStr' => $s1);



        $resp1 = $client->RunSelectQuery($param);

        $resp1 = json_decode(json_encode($resp1), true);

        $result = $resp1['RunSelectQueryResult']['cols']['recs']['string'];

        $this->showReturn.="Resulf of Toral:$result \n";
        $this->showReturn.="DeptID:$deptID ,StartDate:$startDate,endDate:$endDate ,Kind:$kind \n";



    }

    public function execute(ezcWorkflowExecution $execution)
    {

        $kind=$execution->workflow->myForm->getFieldValueByName('Field_3');
        $roleId=$execution->workflow->myForm->getFieldValueByName('Field_1');

        $db = PDOAdapter::getInstance();

        $masterID = $execution->workflow->myForm->instanceID;/*Doc Id form feli migire*/
        //filerecorder::recorder("1**********21****".var_export($moa,true),"homaaa");
        $sql = "select dm.*
                from dm_datastoretable_1088 dm /*--آی دی فرم جزء--*/
                INNER JOIN oa_document on(oa_document.RowID = dm.DocID and oa_document.IsEnable = 1)
                where dm.MasterID = $masterID
                order by dm.Field_0";
        $db->executeSelect($sql);

//        addToToral($deptID, $startDate, $endDate,$kind)
        while ($dateAbsence = $db->fetchAssoc()) {

            $date=Date::GregToJalali((new DateTime($dateAbsence['Field_0']))->format('Y-m-d'));
            $this->addToToral($roleId, $date,$date,$kind);


        }
        Response::getInstance()->response =$this->showReturn;
    }
}
/*---------کد در نود اول ارسال گروهی که سال جاری و یک ماه قبل رو نشون بده---------*/
class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution, ezcWorkflowNodeAction $caller = null)
    {
        //----


        //$dateFA = '1397/07/01';


        $dateFaNow = Date::GregToJalali((new DateTime())->format('Y-m-d'));//تاريخ امروز

        $monthToday = (int)$dateFaNow[5] . $dateFaNow[6];
       $monthToday--;
       if($monthToday>=0)
           $monthToday--;

        $execution->workflow->myForm->setFieldValueByName('Field_2', $monthToday);
        $execution->workflow->myForm->setFieldValueByName('Field_1', 1);

        /*
         * فیلد 2 مربوط به ماه است که قرار شد ماه قبل رو نشون بده
         * و فیلد 1 سال است که سال جاری رو خودم دستی برای هر سال ست می کنم
         */




    }
}

/*----کد در نود ثبت تورال تاریخ 29 دیماه----*/

class calssName
{

    public $showReturn = " ";

    public function __construct()
    {
    }

    public function addToToral($deptID, $startDate, $endDate, $kind)
    {
        //EXEC [TimeRecordMV].[dbo].[uAbsents] '00007109', '1398/09/02', '1398/09/03', 4
//        $startDate = '1398/09/02';
//        $endDate = '1398/09/03';
//        $kind = 4;
//        $deptID='00008999';
        $client = new SoapClient('http://10.10.100.15/WSTuralInOut/TuralInOut.asmx?wsdl');
        $s1 = "EXEC [TimeRecordMV].[dbo].[uAbsents] '$deptID', '$startDate', '$endDate',$kind";
        $param = array('username' => '8bfc0e61722d9e9c9bb2138cb359fef9', 'password' => '085c734188fb09a96eba5d22893a44c4', 'objStr' => $s1);


        $resp1 = $client->RunSelectQuery($param);

        $resp1 = json_decode(json_encode($resp1), true);

        $result = $resp1['RunSelectQueryResult']['cols']['recs']['string'];

        $this->showReturn .= "Resulf of Toral:$result \n";
        $this->showReturn .= "DeptID:$deptID ,StartDate:$startDate,endDate:$endDate ,Kind:$kind \n";


    }

    public function execute(ezcWorkflowExecution $execution)
    {

        $kind = $execution->workflow->myForm->getFieldValueByName('Field_3');
        $roleId = $execution->workflow->myForm->getFieldValueByName('Field_1');

        $db = PDOAdapter::getInstance();

        $masterID = $execution->workflow->myForm->instanceID;/*Doc Id form feli migire*/
//filerecorder::recorder("1**********21****".var_export($moa,true),"homaaa");
        $sql = "select dm.*
                from dm_datastoretable_1088 dm /*--آی دی فرم جزء--*/
                INNER JOIN oa_document on(oa_document.RowID = dm.DocID and oa_document.IsEnable = 1)
                where dm.MasterID = $masterID
                order by dm.Field_0";
        $db->executeSelect($sql);

//        addToToral($deptID, $startDate, $endDate,$kind)
        while ($dateAbsence = $db->fetchAssoc()) {

            $date = Date::GregToJalali((new DateTime($dateAbsence['Field_0']))->format('Y-m-d'));
            $this->addToToral($roleId, $date, $date, $kind);


        }
        Response::getInstance()->response = $this->showReturn;
    }
}
