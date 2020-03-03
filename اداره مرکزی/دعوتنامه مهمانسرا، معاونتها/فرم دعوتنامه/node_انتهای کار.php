<?php

class calssName // do not change this line
{
    //protected $variable = null; define vars sample


    public function __construct()
    {
        // must be empty
    }


    public function execute(ezcWorkflowExecution $execution) // $execution refer to active workflow that call this class
    {

        $db = MySQLAdapter::getInstance();

        $docID = $execution->workflow->myForm->instanceID;
        $referID = Request::getInstance()->varCleanFromInput("referID");

        $sql = "select CreatorUserID, CreatorRoleID from oa_document where RowID = $docID";
        $db->executeSelect($sql);
        $row = $db->fetchAssoc();

        $receivers[0]['type'] = 2;
        $receivers[0]['uid'] = $row['CreatorUserID'];
        $receivers[0]['rid'] = $row['CreatorRoleID'];
        $receivers[0]['oid'] = 'null';
        $receivers[0]['oname'] = '';
        $receivers[0]['iscc'] = 1;
        DocRefer::ReferDocRefer($referID, $receivers, 'احتراما جهت اطلاع');

        // remote regosters
        $resDate = $execution->workflow->myForm->getFieldValueByName('Field_1');
        $guestNo = $execution->workflow->myForm->getFieldValueByName('Field_8');
        $vade = $execution->workflow->myForm->getFieldValueByName('Field_5');
        $azNo = $execution->workflow->myForm->getFieldValueByName('Field_9');
        $taNo = $execution->workflow->myForm->getFieldValueByName('Field_10');
        $dept = $execution->workflow->myForm->getFieldValueByName('Field_0');
        $rabet = $execution->workflow->myForm->getFieldValueByName('Field_2');

        $sql = "SELECT count(dm.RowID) FROM dm_datastoretable_875 dm 
                INNER JOIN oa_document on(oa_document.RowID = dm.DocID and oa_document.IsEnable = 1)
                WHERE dm.MasterID = $docID";
        $count = $db->executeScalar($sql);;

        $url = "http://10.10.10.94:8052/AjaxModules/AjaxRequest.aspx?Action=TBM_RavanFood";
        $url .= "&Date=$resDate";
        $url .= "&GuestNo=$guestNo";
        $url .= "&vade=$vade";
        $url .= "&Tedad=$count";
        $url .= "&AzNo=$azNo";
        $url .= "&Tano=$taNo";
        $url .= "&NoForm=$docID";
        $url .= "&CodeHoze=$dept";
        $url .= "&NameDaryaftKo=".urlencode($rabet);

        $process = curl_init($url);
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
        $return = curl_exec($process);
        curl_close($process);
        $return = json_decode($return, true);
        $return = $return[0];
        if($return['retval'] == 0){
            $execution->setVariable('exist', 0);
            $execution->workflow->myForm->setFieldValueByName('Field_11', 'نتیجه عملیات ثبت: '.$return['message']);
        }
        else{
            $execution->setVariable('exist', $return['retval']);
            $execution->workflow->myForm->setFieldValueByName('Field_11', 'نتیجه عملیات ثبت: '.$return['message']);
        }


    }

    //protected function someMethod(){} // if code must be modular you can define some mothods and use it in execute
}

?>
