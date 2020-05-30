<?php
class calssName {
    public function __construct(){}
    public function execute(ezcWorkflowExecution $execution) {
        $execution->workflow->myForm->setFieldValueByName('Field_8', '1');
        $subformID = 783;//سرور عملياتي
        $uid =  $execution->getVariable('uid');
        $rid =  $execution->getVariable('rid');
        $db = MySQLAdapter::getInstance();
        $result = $db->executeSelect('select oa_depts_roles.path, oa_users.employeeID, oa_users.fname, oa_users.lname, oa_users.sex,oa_users.mobile from oa_users inner join oa_depts_roles on oa_depts_roles.UserID = oa_users.UserID where oa_users.UserID = '. $uid);
        $row = $db->fetchAssoc();
        $DocID = $execution->workflow->myForm->instanceID;

        $mobileForSMS=$row['mobile'];

        $sex = 'آقاي';
        if ($row['sex']!='1') {
            $sex = 'خانم';
        }
        $style = '<style>
        .mtable {
            border-collapse: collapse;
        }
        .mtable  th, .mtable  td {
            border: 1px solid black;
            text-align: center; 
			font-family: "B Nazanin","Nazanin"!important; 
			font-size: 12pt!important;
			padding: 5px;
        }
        .mtable  th{
            font-weight: bold;
            background-color: #bbb;
        }
        .dv *{
            text-align: justify;
            text-justify: inter-word;
            font-family: "B nazanin","nazanin"!important;
            font-size: 12pt !important;
        }
        </style>';
        $fname = $sex . ' ' . $row['fname'] . ' ' . $row['lname'];
        $sql = "select count(*) from dm_datastoretable_$subformID inner join oa_document on oa_document.RowID = dm_datastoretable_$subformID.DocID where oa_document.IsEnable = 1 and dm_datastoretable_$subformID.MasterID=".$DocID;
        $pcount = $db->executeScalar($sql);
        $pcount = $pcount - 1;
        $type = $execution->workflow->myForm->getFieldValueByName('Field_6');
        $title = '';
        $html = '';
        $title = 'احتراما بدينوسيله تعداد ';
        $title .= $pcount;
        $title .= ' نفر افراد تحت  تكفل ';
        $title .= $fname;
        $title .= ' داراي شماره پرسنلي ';
        $title .= $row['employeeID'];
        $title .= ' جهت استفاده از خدمات مشاوره اي، به حضور معرفي مي گردد. خواهشمند است دستور فرمایيد وفق مقررات، همكاري لازم را مبذول نمايند.';
        $sql = '';
        if ($type != 1) {
            $sql = "select dm_datastoretable_$subformID.* from dm_datastoretable_$subformID inner join oa_document on oa_document.RowID = dm_datastoretable_$subformID.DocID where  oa_document.IsEnable = 1 and dm_datastoretable_$subformID.MasterID = " . $DocID;
            $db->executeSelect($sql);
            $html = '
        <table class="mtable" border="0" cellpadding="0" width="100%" align="center">
        <tr>
            <th>نام و نام خانوادگی</th>
            <th>كد ملي</th>
            <th>تلفن</th>
            <th>نسبت</th>
            <th>تلفن همراه</th>
            <th>تاريخ تولد</th>
        </tr>';
            while ($row = $db->fetchAssoc()) {
                $html .= '<tr><td >'.$row['Field_0'].'</td><td >'.$row['Field_2'].'</td><td >'.$row['Field_3'].'</td><td  >'.$row['Field_4'].'</td><td >'.$row['Field_5'].'</td><td  style="direction:rtl">'.$row['Field_7'].'</td></tr>';
            }
            $html .= '</table>';
            $html =  $style.'<div class="dv">  با سلام<br>' . $title .' <br>' . $html . '<br>خواهشمند است دستور فرماييد وفق مقررات، همكاري لازم را مبذول نمايند.</div>';
        } else {
            $html =  $style.'<div class="dv">  با سلام<br>' . $title .' <br>خواهشمند است دستور فرماييد وفق مقررات، همكاري لازم را مبذول نمايند.</div>';
        }
        $execution->workflow->myForm->setFieldValueByName('Field_4', $title);
        $acm = AccessControlManager::getInstance();
        $uid = $acm->getUserID();
        $rid = $acm->getRoleID();
        $uid1 = '551';
        $rid1 = '93';
        $did = Document::Create($uid, $rid, 'معرفي جهت خدمات مشاوره اي ' . $fname, 1, 'معرفي جهت خدمات مشاوره اي ' . $fname, 1, 0, 1);
        $lid = Letter::Create($did, 2, 1,'0,0',0,'','',0,0,2);
        Letter::SetSigners($lid, $uid1.','.$rid1);
        $rid = DocRefer::ReferDraft($did, $uid, $rid, 0);
        ModCompose::updateTypedContent($did, $rid, $html);
        WorkFlowSecRegister::regOutForm($DocID);
        $regInfo = Letter::GetRegInfo($DocID);
        DocLink::AddLink($regInfo['regcode'], $did, $referId, 'معرفي جهت خدمات مشاوره اي ' . $fname, 1, $DocID, null);
        $referID = $rid;
        $uid = '551';//صراف
        $rid ='93';
        /**/
        $uid2 =  $execution->getVariable('uid');
        $rid2 =  $execution->getVariable('rid');
        /*$titles = ''.','.''.',بين سروري موسسه خدمات مشاوره اي جوانان و پژوهشهاي اجتماعي آستان قدس رضوي,0,موسسه محترم خدمات مشاوره اي جوانان و پژوهشهاي اجتماعي آستان قدس رضوی,false,,,,,,';*/
        $titles = '7579,9870,,1,مديريت محترم مركز جامع مشاوره آستان قدس رضوي,false,,,,,,';
        $copies = $uid2.','.$rid2.',,1,'.$fname.',true,,,,,,';
        DocTitle::SetDocTitles($did, $titles, $copies);
        /**/
        $receivers[0]['type'] = 2;
        $receivers[0]['uid'] = $uid;
        $receivers[0]['rid'] = $rid;
        $receivers[0]['oid'] = 'null';
        $receivers[0]['oname'] = '';
        $receivers[0]['iscc'] = 0;
        DocRefer::ReferDocRefer($referID, $receivers, ' ');
        /*sending SMS*/

        $letterNumForSMS=$did;
        $fname."<br>"; /*آقای جواد جوادی*/
        $text=$fname;
        $text.="معرفی نامه به مراکز مشاوره صادر شد"."<br>"."شماره نامه: ".$letterNumForSMS;
        if(strlen($mobileForSMS)>0) {
            /*ثابت*/
            $db = PDOAdapter::getInstance();
            $sid = SecUser::GetCurrUserSecID();
            $sql = "select smsAction from oa_secretariat where RowID=" . intval($sid);
            $phpcode = $db->executeScalar($sql);
            $uniqID = uniqid();
            $calssName = 'calssName' . $uniqID;
            $code = $phpcode;
            $code = str_replace('<?php', '', $code);
            $code = str_replace('?>', '', $code);
            $code = str_replace('calssName', $calssName, $code);
            eval($code);
            $myInstance = new $calssName();

            $result = $myInstance->sendWithTracking($mobileForSMS, $text);


        }

        /*End sending SMS*/




    }
}
?>
