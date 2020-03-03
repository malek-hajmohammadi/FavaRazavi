
<?php
//---------------------------------کد aJax برای گرفتن وب سرویس------------------------------//
$url = "http://10.10.10.94:8052/AjaxModules/AjaxRequest.aspx";
$url .= "?Action=JAM_DoreAmouzeshi";
$url .= "&year=1398";

$process = curl_init($url);
curl_setopt($process, CURLOPT_TIMEOUT, 30);
curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
$return = curl_exec($process);
curl_close($process);
$return = json_decode($return, true);

//--------------------------ساخت آرایه برای منبع داده فرم تکمیل شونده---------------------------

$arForCompletedField=[];
foreach($return as $record){
    $arForCompletedField[]=array($record['class_id'],$record['class_name']);

}
//----------------------------------------------------------------------

Response::getInstance()->response = $arForCompletedField;

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
        $sql2 = "SELECT employeeID,NationalCode,sex,fname,lname FROM oa_users WHERE UserID=" . $UID;
        $db->executeSelect($sql2);
        $person = $db->fetchAssoc();

        $execution->workflow->myForm->setFieldValueByName('Field_2', $person['employeeID']);
        $execution->workflow->myForm->setFieldValueByName('Field_1', $person['NationalCode']);




    }
}