<?php

class calssName // do not change this line
{
    //protected $variable = null; define vars sample

    public function __construct()
    {
        // must be empty
    }

    public function sendMessage($subject, $content, $referDesc, $fromUID, $fromRID, $ToUID, $ToRID)
    {
        $rid = $fromRID;//from
        $uid = $fromUID;//from
        $start = 1;
        $did = Document::Create($uid, $rid, $subject, 0, '', 2, 0, 1);
        TextContent::UpdateDocContent($did, $content, $start);
        $referId = DocRefer::ReferDraft($did, $uid, $rid, 0);
        $refer = $referId;
        $recs2 = array(
            0 => array(
                'type'        => 0,
                'uid'         => $ToUID,//to
                'rid'         => $ToRID,//to
                'oid'         => 'null',
                'oname'       => '',
                'iscc'        => NULL,
                'isMultiSend' => '',
                'noteid'      => '',
                'notedesc'    => $referDesc,
            )
        );
        $order_note = '';
        $urg = '';
        $timeout = '';
        $secureF = 0;
        $secureB = 0;
        $track = 0;
        $attachs = array();
        $IsPerRefer = '';
        DocRefer::MessageReferDocRefer($refer, $recs2, $order_note, $urg, $timeout, $secureF, $secureB, $track, $attachs, $IsPerRefer);
    }

    public function execute(ezcWorkflowExecution $execution) // $execution refer to active workflow that call this class
    {
        // code body
        $stID = 790;//فرم اصلاح تردد
        $type = $execution->workflow->myForm->getFieldValueByName('Field_4');
        $pid = $execution->workflow->myForm->getFieldValueByName('Field_1');
        $date = $execution->workflow->myForm->getFieldValueByName('Field_2');
        $time = $execution->workflow->myForm->getFieldValueByName('Field_13');
        $error_msg = false;

        $d = explode('/', $date);
        $dd = $d[0] . "-" . $d[1] . "-" . $d[2];
        $date11 = Date::JalaliToGreg($date);
//filerecorder::recorder(" pid:".$pid." date:". $date. " dd:".$dd." date11:".$date11 ,"homaaa123");
        $sqlcheck = "SELECT count( `RowID` ) FROM `dm_datastoretable_$stID` WHERE `Field_2` = '" . $date11 . "' AND `Field_7` = '1' AND `Field_1` = '" . $pid . "' AND `Field_4` = '0' ";
        $tedad = WFPDOAdapter::getInstance()->executeScalar($sqlcheck);
        if ($tedad < 2 || ($tedad >= 2 && $type == 1) || 1) {
            $time = explode(':', $time);
            if (intval($time[0]) < 10) {
                $time[0] = trim($time[0], '0');
                $time[0] = '0' . $time[0];
            }

            if ($time[0] == '0')
                $time[0] = '00';

            if (intval($time[1]) < 10) {
                $time[1] = trim($time[1], '0');
                $time[1] = '0' . $time[1];
            }

            if ($time[1] == '0') $time[1] = '00';

            $time = implode(':', $time);


            //filerecorder::recorder("s--ssstart type: ".$type." pid:".$pid." date:".$date." time:".$time." count:".$count,"homaaa123");
            $docID = $execution->workflow->myForm->instanceID;

            if (intval($type) == 0) {
                $s1 = "EXEC InOutData.dbo.[iNormalTaradod] '" . $pid . "', '" . $date . "', '" . $time . "' , '400' ";
                $s1 = urlencode($s1);
                $client = new SoapClient('http://10.10.100.15/WSTuralInOut/TuralInOut.asmx?wsdl');
                $html = '';
                $param = array('username' => '8bfc0e61722d9e9c9bb2138cb359fef9', 'password' => '085c734188fb09a96eba5d22893a44c4', 'objStr' => $s1);
                $resp1 = $client->RunQuery($param);
                $result1 = json_decode(json_encode($resp1), true);
                if (is_array($result1) && isset($result1['RunQueryResult']) && $result1['RunQueryResult']) {
                } else {
                    $error_msg = true;
                }
                //filerecorder::recorder("e--end type=1","homaaa123");
            } else if (intval($type) == 1) {
                $s1 = "EXEC InOutData.dbo.[dTaradod] '" . $pid . "', '" . $date . "', '" . $time . "'";
                $s1 = urlencode($s1);
                $client = new SoapClient('http://10.10.100.15/WSTuralInOut/TuralInOut.asmx?wsdl');
                $html = '';
                $param = array('username' => '8bfc0e61722d9e9c9bb2138cb359fef9', 'password' => '085c734188fb09a96eba5d22893a44c4', 'objStr' => $s1);
                $resp1 = $client->RunQuery($param);
                $result1 = json_decode(json_encode($resp1), true);
                if (is_array($result1) && isset($result1['RunQueryResult']) && $result1['RunQueryResult']) {
                } else {
                    $error_msg = true;
                }

                //filerecorder::recorder("e--end type=2","homaaa123");
            }
            //--------960308 abdollahi جهت كنترل خطا -----------------
            if ($error_msg) {
                /////////////ارسال پيام خطا به كارتابل فدايي////////
                $MUID = '1024';
                $MRID = '1270';
                $docID = $execution->workflow->myForm->instanceID;
                $subject = 'گزارش خطا در هنگام ثبت تردد سستم تورال بشماره فرم:' . $docID;
                $content = 'خطا در فرم ثبت تردد بشماره :' . $docID . '<br>' . 'خطا در هنگام ثبت اطلاعات تردد متقاضي در سيستم تورال. اين پيام بصورت خودكار براي شما ارسال گرديده است' . '<br>Query:' . $s1;
                $referDesc = 'ارجاع اتوماتيك جهت اقدام';
                $this->sendMessage($subject, $content, $referDesc, 1, 1, $MUID, $MRID);
                //////////////////////////////////
            }
            //-----------------------------------------------------------------
            $db2 = WFPDOAdapter::getInstance();
            $up_sql = "UPDATE `dm_datastoretable_$stID` SET Field_7='1' WHERE  DocID=$docID";
            $db2->execute($up_sql);
        }
    }


    //protected function someMethod(){} // if code must be modular you can define some mothods and use it in execute
}


