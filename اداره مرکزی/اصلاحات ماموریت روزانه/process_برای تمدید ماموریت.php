<?php

class calssName
{
    public function __construct(){}
    public function execute(ezcWorkflowExecution $execution, ezcWorkflowNodeAction $caller = null)
    {
        //added by abdollahi 960209
        $docID = $execution->workflow->myForm->instanceID;
        $regInfo= Letter::GetRegInfo($docID);
        $dateStart=$execution->workflow->myForm->getFieldValueByName( 'Field_3');
        $dateEnd=$execution->workflow->myForm->getFieldValueByName( 'Field_4');
        $empID=$execution->workflow->myForm->getFieldValueByName( 'Field_2');
        $duration=$execution->workflow->myForm->getFieldValueByName( 'Field_5');
        $duration=intval($duration);
        $newdate=$execution->workflow->myForm->getFieldValueByName( 'Field_15');
        $client=new SoapClient('http://10.10.100.15/WSTural/Tural.asmx?wsdl');
        $sql="EXEC uVacation '$empID', '$dateStart', '$dateEnd', $duration , '$newdate' ";
        $param=array('username'=>'8bfc0e61722d9e9c9bb2138cb359fef9','password'=>'085c734188fb09a96eba5d22893a44c4','objStr'=>$sql);
        $resp=$client->RunQuery($param);

        if ($resp==0) {
            $execution->setVariable('checkExtend', '0');
            $referID = $execution->workflow->myForm->referInsID;
            $newReferNote="تمدید ماموریت در تورال ثبت نشد ، لطفا به صورت دستی انجام دهید";
            $sql = "UPDATE oa_doc_refer SET NoteDesc='".$newReferNote."' WHERE oa_doc_refer.ParentID = $referID limit 1";
            $db = MySQLAdapter::getInstance();
            $db->execute($sql);

        }
        else
            $execution->setVariable('checkExtend', '1');



    }
}

?>
