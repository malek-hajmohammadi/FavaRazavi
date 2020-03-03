<?php
//-----------------داخل آیجکس autoPassingFormCreation----------//


//---------------------------
// شماره پرسنلي
//---------------------------

if (Request::getInstance()->varCleanFromInput('emp')) {
    $emp = Request::getInstance()->varCleanFromInput('emp');
} else {
    $RID = AccessControlManager::getInstance()->getRoleID(); //آي دي سمت كاربر جاري
    $UID = AccessControlManager::getInstance()->getUserID(); //آي دي كاربر جاري
    /*if ($UID != 353)
    return;*/
    $SQL = "SELECT * FROM oa_users WHERE UserID=" . $UID;
    $db = MySQLAdapter::getInstance();
    $db->executeSelect($SQL);
    $res = $db->fetchAssoc();
    $emp = $res['employeeID']; //كد پرسنلي

}


//$emp = 771128;


//---------------------------
// تشخيص آخرين زمان بروزرساني
//آخرين زماني كه سيري در سيستم بروز رساني رو زده
//---------------------------
$client = new SoapClient('http://172.16.61.253/Timex.asmx?wsdl');
$s = "SELECT [CardNo]
,[IODate]
,[IOTime]
,[IOTypeID]
,[DeviceID]
,[FirstIOTime]
,[FirstIOType]
,[FirstDeviceID]
,[RCounter]
,[CreateDate]
FROM [Timex].[adon].[TblIOData]
where IOTypeID>0 and CreateDate>'2018-10-20 0:0:0' ";
$param = array(
    'username' => '3ef1b48067e4f2ac9913141d77e847dd',
    'password' => '9a3f5b14f1737c15e86680d9cd40b840',
    'objStr' => $s
);
$resp1 = $client->RunSelectQuery($param); //اجراي كوئري
$recs1 = $resp1->RunSelectQueryResult->cols; //فيلدها رو از اينجا خونده
$recs1 = json_decode(json_encode($recs1), true);
$recs1 = urldecode($recs1[0]['recs']['string']);//براي اينكه فارسي درست بشه . در اين خانه آرايه تاريخ برميگرده
$recs1 = explode(' ', $recs1);//تيكه تيكه براساس اسپيس چون خونه اول تاريخ است و خونه دوم ساعت

//**************************************/
$dateFA = Date::GregToJalali((new DateTime($recs1[0]))->format('Y-m-d'));
//$dateFA = '1397/07/01';
$dateEN = Date::JalaliToGreg($dateFA);
//$dateFA = '2019-11-14';
//************************************** * tested : $dateFA=1398/08/25 and $dateEN=2019-11-16

//$dateFA = '1397/07/01';
//يك تابع درست كنم براي ماه
//تاريخ در گراف به فارسي ذخيره مي شود
//---------------------------
// محاسبه تاريخ
//---------------------------


//-------------------------
//محاسبه تاريخ شروع

$updateDate = $dateFA;
$updateDay = (int)$dateFA[8] . $dateFA[9];
/////////////////////////روز و ماه امروز براي حذف از فرم درست كردن/////////////////////
$dateFaNow = Date::GregToJalali((new DateTime())->format('Y-m-d'));//تاريخ امروز
$dayToday = (int)$dateFA[8] . $dateFA[9];
$monthToday = (int)$dateFA[5] . $dateFA[6];
/// ///////////////////////////////////////////
$updateMonth = (int)$dateFA[5] . $dateFA[6];
$updateYear = (int)$dateFA[0] . $dateFA[1] . $dateFA[2] . $dateFA[3];

$startDay = 21;//چون هميشه از اين تاريخ براي حقوق محاسبه مي كنند
$startMonth = 0;
$startYear = 0;
$shortLogArray = array();

if ($updateDay >= $startDay) {
    $startMonth = $updateMonth;
    $startYear = $updateYear;
}

if ($updateDay < $startDay) {
    if ($updateMonth > 1) {
        $startMonth = $updateMonth - 1;
        $startYear = $updateYear;
    } else {
        $startMonth = 12;
        $startYear = $updateYear - 1;

    }
}

$startDate = $startYear . '/' . $startMonth . '/' . $startDay;
$updateDate = $dateFA;


//---------------------------
// ترددها
//---------------------------

$client = new SoapClient('http://172.16.61.253/Timex.asmx?wsdl');

$s3 = "EXEC [adon].[IOData_sel] " . $emp . ",'" . $startDate . "','" . $updateDate . "'";
$param3 = array(
    'username' => '3ef1b48067e4f2ac9913141d77e847dd',
    'password' => '9a3f5b14f1737c15e86680d9cd40b840',
    'objStr' => $s3
);
$resp3 = $client->RunSelectQuery($param3);
$recs3 = $resp3->RunSelectQueryResult->cols;
$recs3 = json_decode(json_encode($recs3), true);
/////////
Response::getInstance()->response = "recs3=$recs3,startDate:$startDate,Update:$updateDate,Emp:$emp";
return;
/// //////////
//$s3 = "EXEC [adon].[IOData_sel] ".$emp.",'1398/08/20',1398/08/25'";

$TimexCount = 90; //فعلا
$timexCounter = 2;
$timeTable = array(); //يك آرايه دو بعدي از ترددهاي مرخصي ساعتي و ماموريت ساعتي
$oddPassingADay = array();//بابت ترددهاي ناقص يا فرد در يك روز
$counterTest = -1;
$test = array(); //

if ($startDate == $updateDate)//يعني تاريخ به روز رساني بيستم ماه است
{
    $passCounter = 0;
    $TimexArray = $recs3['recs']['string'];
    $day = urldecode($TimexArray[$timexCounter - 1]);
    if (date_diff(date_create($dateEN), date_create(Date::JalaliToGreg($day)))->format('%d') > 1) {
        while ($timexCounter < 92) {

            $passStatus = urldecode($TimexArray[$timexCounter + 1]);

            if ($passStatus == "0" || $passStatus == "1" || $passStatus == "2") {
                $passCounter++; // count the number of passing
            }
            switch ($passStatus) {
//خانه يك : روز
//                case "1":
//                    $timeTable[] = array(urldecode($TimexArray[1]), Date::JalaliToGreg(urldecode($TimexArray[1])), urldecode($TimexArray[$timexCounter]), 17);
//                    break; // مرخصي ساعتي
                case "2":
                    $timeTable[] = array(urldecode($TimexArray[1]), Date::JalaliToGreg(urldecode($TimexArray[1])), urldecode($TimexArray[$timexCounter]), 9);
                    break; // ماموريت ساعتي

            }

            $timexCounter += 6;
        }
        if ($passCounter % 2 == 1)//يعني تردد در آن روز ناقص است
        {
            $oddPassingADay[] = array(urldecode($TimexArray[1]), Date::JalaliToGreg(urldecode($TimexArray[1])));
//1398/08/21   2019-11-12
        }
    }


} else {
    $test = $recs3;
    foreach ($recs3 as $dayTable) {
        $TimexArray = $dayTable['recs']['string']; //it's ok
        $timexCounter = 2;
        $passCounter = 0;

        $day = urldecode($TimexArray[$timexCounter - 1]);

        if (date_diff(date_create($dateEN), date_create(Date::JalaliToGreg($day)))->format('%d') > 1) {
            //--اگردو روز قبل از به روز رساني سيري بود --
            while ($timexCounter < 92) //it's ok (counter)
            {

                $passStatus = urldecode($TimexArray[$timexCounter + 1]);
                if ($passStatus == "0" || $passStatus == "1" || $passStatus == "2") {
                    $passCounter++; // count the number of passing
                }
                switch ($passStatus) {
//خانه يك : روز
//خانه اول تاريخ شمسي ، خانه دوم تاريخ به ميلادي براي گراف، خانه سوم زمان ، خانه چهارم نوع تردد
//                    case "1":
//                        $timeTable[] = array(urldecode($TimexArray[1]), Date::JalaliToGreg(urldecode($TimexArray[1])), urldecode($TimexArray[$timexCounter]), 17);
//                        break; // مرخصي ساعتي

                    case "2":
                        $timeTable[] = array(urldecode($TimexArray[1]), Date::JalaliToGreg(urldecode($TimexArray[1])), urldecode($TimexArray[$timexCounter]), 9);
                        break; // ماموريت ساعتي
                }


                $timexCounter += 6;
            }
            if ($passCounter % 2 == 1)//يعني تردد در آن روز ناقص است
            {
                $oddPassingADay[] = array(urldecode($TimexArray[1]), Date::JalaliToGreg(urldecode($TimexArray[1])));
//1398/08/21   2019-11-12

            }
        }


    }

}

//---------------------------
// جستجو فرم هاي تردد در تاريخ و زمان
//---------------------------
$counterNadard = 0;
if ($timeTable != NULL) {

    foreach ($timeTable as $time) {
        $sql = "SELECT count(dm_datastoretable_34.RowID)
FROM `dm_datastoretable_34`
inner join oa_document on (oa_document.rowid = dm_datastoretable_34.docid and oa_document.isenable=1)
inner join wf_execution on(wf_execution.execution_doc_id = oa_document.RowID AND wf_execution.is_enable = 1)
WHERE
oa_document.DocStatus=0
AND dm_datastoretable_34.Field_20='$emp'
AND field_9='$time[2]'
";
        $db = MySQLAdapter::getInstance();
        $count = $db->executeScalar($sql);
        if ($count > 0) {
// فرم دارد
            $shortLogArray[] = 'darad =>' . $time[0] . '-' . $time[1] . '-' . $time[2] . '-' . $time[3];
        } else {

// فرم ندارد
            $counterNadard++;
            $shortLogArray[] = 'nadarad =>' . $time[0] . '-' . $time[1] . '-' . $time[2] . '-' . $time[3];
//"ndarad =>1398/08/21 2019-11-12  09:53   9"

///////////////////////////////////////////////////////


            $formID = 34;
            $db = MySQLAdapter::getInstance();
            $workFlowID = $db->executeScalar("SELECT `workflow_id` FROM `wf_workflow` WHERE `workflow_enable` = 1 AND `workflow_formtypeid` =  $formID");
            $resp = WorkFlowManager::stratNewWorkFlow($workFlowID);
            $referID = $resp['referID']; //آي دي ارجاع است
            $docID = $resp['docID'];

//////////////////////////////////////////////////////////
            $sql_title = "update oa_document set Subject='" . "فرم تردد سيستمي" . "' where RowID=" . $docID . "   limit 1";
            $db->execute($sql_title);
//////////////////////////
            $fdata = array(
                "335" => $time[0], //تاريخ شمسي
                "337" => $time[3],//نوع تردد
                "328" => $time[2],//تايم
                "955" => 1 //خودكار ساخته شده
            );
            $myForm = new DMSNFC_form(array('fieldid' => $formID, 'docid' => $docID, 'referid' => null));

            $myForm->setData($fdata);


//////////////////////////
            $request = Request::getInstance();
            $request->setParameter('referID', $referID);
            $request->setParameter('structID', $formID);
            $request->setParameter('docID', $docID);
            $request->setParameter('commandKey', '1_3');
            $request->setParameter('referNote', 'جهت بررسي');
            ModWorkFlowManager::workflowAction();
/// /////////////////
// //////////////////////////////////////////////////////////
        }
    }
} else {
    $shortLogArray = -5;
}
//Response::getInstance()->response = $shortLogArray;
//return;
//////////////////////ساخت فرم براي ترددهاي ناقص///////////////////
$counterPartly = 0;
foreach ($oddPassingADay as $instance) {
///////////////////////////////////////////////////////
    $sql = "SELECT count(dm_datastoretable_34.RowID)
FROM `dm_datastoretable_34`
inner join oa_document on (oa_document.rowid = dm_datastoretable_34.docid and oa_document.isenable=1)
inner join wf_execution on(wf_execution.execution_doc_id = oa_document.RowID AND wf_execution.is_enable = 1)
WHERE
oa_document.DocStatus=0
AND dm_datastoretable_34.Field_20='$emp'
AND field_9='$instance[1]'
";

    $db = MySQLAdapter::getInstance();
    $count = $db->executeScalar($sql);
    if ($count == 0) {
        $counterPartly++;
        $formID = 34;
        $db = MySQLAdapter::getInstance();
        $workFlowID = $db->executeScalar("SELECT `workflow_id` FROM `wf_workflow` WHERE `workflow_enable` = 1 AND `workflow_formtypeid` =  $formID");
        $resp = WorkFlowManager::stratNewWorkFlow($workFlowID); //يك فرم از آخرين گردش كار جاري ايجاد كند و در پيش نويس مي گذار
        $referID = $resp['referID']; //آي دي ارجاع است
        $docID = $resp['docID'];
//////////////////////////////////////
        $sql_title = "update oa_document set Subject='فرم تردد سيستمي' where RowID=" . $docID . "   limit 1";
        $db->execute($sql_title);
//////////////////////////////////////////////////////


        $fdata = array(
            "335" => $instance[0], //تاريخ شمسي
//"328" => $instance[1], //تاريخ ميلادي
            "955" => 1,
            "337" => 200 //افزودن تردد
        );
        $myForm = new DMSNFC_form(array('fieldid' => $formID, 'docid' => $docID, 'referid' => null)); //با اين خط داده ها رو در فرم مي گذارد
        $myForm->setData($fdata);
//////////////////////////////////////////////////////////

//////////////////////////
        $request = Request::getInstance();
        $request->setParameter('referID', $referID);
        $request->setParameter('structID', $formID);
        $request->setParameter('docID', $docID);
        $request->setParameter('commandKey', '1_3');
        $request->setParameter('referNote', 'لطفاً تردد ناقص خود را اصلاح فرماييد');
        ModWorkFlowManager::workflowAction();
    }
/// /////////////////
ُ
}
////////////////////////////////////////////////////////////////////

Response::getInstance()->response = "تردد ناقص هاي اضافه شده " . $counterPartly . " \n فرم هاي ماموريت اضافه شده " . $counterNadard;

