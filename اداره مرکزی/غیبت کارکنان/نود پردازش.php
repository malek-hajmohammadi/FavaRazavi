<?php
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
        $kindCode = "-1";
        switch ($kind) {
            case "1":
                $kindCode = "12";
                break;
            case "2":
                $kindCode = "1";
                break;
            case "3":
                $kindCode = "12";
                break;
            case "4":
                $kindCode = "0";
                break;

        }
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
            $this->addToToral($roleId, $date, $date, $kindCode);


        }
        Response::getInstance()->response = $this->showReturn;
    }
}

