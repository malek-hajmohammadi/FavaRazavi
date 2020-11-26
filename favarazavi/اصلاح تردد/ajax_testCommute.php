<?php


class MainAjax
{


    const START_DAY = 21;
    private $incompleteCommuteArray=[];
    private $absenceArray=[];

    public function main()
    {
        $personalId = $this->getPersonalId();
        $lastUpdate = $this->getLastUpdate();
        $today=$this->getToday();
        $beginDate = $this->getBeginDate($today);
       /* $commutes = $this->getCommutes($beginDate, $today, $personalId);*/
       /* $incompleteCommute = $this->getIncompleteCommute($commutes);*/

      /*  $otherCommutes=$this->getCommuteFromOtherQuery($beginDate,$today,$personalId);*/
        $this->getCommuteFromOtherQuery($beginDate,$today,$personalId);
        $existCommutingFormForIncompleteCommute = $this->existCommutingFormForIncompleteCommute( $personalId);




        $existMissionForm=$this->existMissionFormForAbsence($personalId);
        $existOffForm=$this->exitDayOffFormForAbsence($personalId);






        $isNeeded = 0;
        /*if ($existCommutingFormForIncompleteCommute == 0)
            $isNeeded = 1;
        else
            $isNeeded = 0;

        return $isNeeded;*/

        /*$returnObject=new stdClass();
        $returnObject->commutes=$otherCommutes;
        $returnObject->inCommpleteCommutes=$this->incompleteCommuteArray;
        $returnObject->absence=$this->absenceArray;
        $jsonForReturn=json_encode($returnObject);
        return $returnObject;*/

        return $existCommutingFormForIncompleteCommute;

    }

    private function getPersonalId()
    {
        if (Request::getInstance()->varCleanFromInput('emp')) {
            $emp = Request::getInstance()->varCleanFromInput('emp');
        } else {
            $RID = AccessControlManager::getInstance()->getRoleID(); //آي دي سمت كاربر جاري
            $UID = AccessControlManager::getInstance()->getUserID(); //آي دي كاربر جاري
            /*if ($UID != 353)
                return;*/

            $SQL = "SELECT * FROM oa_users WHERE UserID=" . $UID;
            $db = WFPDOAdapter::getInstance();
            $db->executeSelect($SQL);
            $res = $db->fetchAssoc();
            $emp = $res['employeeID'];

        }
        return $emp;

    }

    private function getLastUpdate()
    {
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

        $updateDateEn = new DateTime($recs1[0]);
        $updateDateEn = $updateDateEn->modify('-2 day');
        $dateFA = Date::GregToJalali($updateDateEn->format('Y-m-d'));
        $updateDate = $dateFA;


        $updateDay = (int)$dateFA[8] . $dateFA[9];
        $updateMonth = (int)$dateFA[5] . $dateFA[6];
        $updateYear = (int)$dateFA[0] . $dateFA[1] . $dateFA[2] . $dateFA[3];

        /*Response::getInstance()->response ="updateDate=$updateDate,updateDay=$updateDay,updateMonth=$updateMonth
        ,updateYear=$updateYear";
        return;*/

        return $dateFA;
    }

    private function getToday(){
        $today = Date::GregToJalali((new DateTime())->format('Y-m-d'));//تاريخ امروز
        return $today;
    }

    private function getBeginDate($endDate)
    {
        /*تاریخ امروز*/

        /* $today = Date::GregToJalali((new DateTime())->format('Y-m-d'));//تاريخ امروز
         $dayToday = (int)$today[8] . $today[9];
         $monthToday = (int)$today[5] . $today[6];*/


        $dayEndDate = (int)$endDate[8] . $endDate[9];
        $monthEndDate = (int)$endDate[5] . $endDate[6];
        $yearEndDate = (int)$endDate[0] . $endDate[1] . $endDate[2] . $endDate[3];

        /*محاسبه تاریخ شروع بازه*/

        $startDay = 21;//چون هميشه از اين تاريخ براي حقوق محاسبه مي كنند
        $startMonth = 0;
        $startYear = 0;
        $shortLogArray = array();

        if ($dayEndDate >= self::START_DAY) {
            $startMonth = $monthEndDate;
            $startYear = $yearEndDate;
        } else {
            if ($monthEndDate > 1) {
                $startMonth = $monthEndDate - 1;
                $startMonth = strlen($startMonth) == 1 ? "0" . $startMonth : $startMonth;
                $startYear = $yearEndDate;
            } else {
                $startMonth = 12;
                $startYear = $yearEndDate - 1;

            }
        }

        $startDate = $startYear . '/' . $startMonth . '/' . $startDay;
//tested
//     Response::getInstance()->response ="startDate=$startDate";

        return $startDate;
    }

    private function getCommutes($beginDate, $endDate, $personalId)
    {
        $client = new SoapClient('http://172.16.61.253/Timex.asmx?wsdl');

        $sqlString = "EXEC [adon].[IOData_sel] " . $personalId . ",'" . $beginDate . "','" . $endDate . "'";
        $param = array(
            'username' => '3ef1b48067e4f2ac9913141d77e847dd',
            'password' => '9a3f5b14f1737c15e86680d9cd40b840',
            'objStr' => $sqlString
        );
        $response = $client->RunSelectQuery($param);
        $response = $response->RunSelectQueryResult->cols;
        $responseDecode = json_decode(json_encode($response), true);

        return $responseDecode;
    }

    private function getIncompleteCommute($commutes)
    {
        $oddPassingADay = [];
        $arraySize=sizeof($commutes);
        $index=1;
        foreach ($commutes as $dayTable) {
            if($index>$arraySize-1) {
                break;
                /*تا روز قبل از آخرین تردد*/
            }
            else {
                $index++;
            }
            $TimexArray = $dayTable['recs']['string'];
            $timexCounter = 2;/*چون ترددهای روز از خونه سوم آرایه شروع می شود*/
            $passCounter = 0;

            $day = urldecode($TimexArray[$timexCounter - 1]);

            while ($timexCounter < 92) //it's ok (counter)
            {

                $passStatus = urldecode($TimexArray[$timexCounter + 1]);
                if ($passStatus == "0" || $passStatus == "1" || $passStatus == "2") {
                    $passCounter++; // count the number of passing
                }
                switch ($passStatus) {

                    case "1":
                        $timeTable[] = array(urldecode($TimexArray[1]), Date::JalaliToGreg(urldecode($TimexArray[1])), urldecode($TimexArray[$timexCounter]), 17);
                        break; // مرخصي ساعتي

                    case "2":
                        $timeTable[] = array(urldecode($TimexArray[1]), Date::JalaliToGreg(urldecode($TimexArray[1])), urldecode($TimexArray[$timexCounter]), 9);
                        break; // ماموريت ساعتي
                }


                $timexCounter += 6;
            }
            if ($passCounter % 2 == 1)//يعني تردد در آن روز ناقص است
            {
                $oddPassingADay[] = array(urldecode($TimexArray[1]), Date::JalaliToGreg(urldecode($TimexArray[1])));

            }
        }
        return $oddPassingADay;

    }

    private function existCommutingFormForIncompleteCommute( $personalId)
    {

        foreach ($this->incompleteCommuteArray as $commute) {

          /*  if (date_diff(date_create($commute), date_create(Date::JalaliToGreg($day)))->format('%d') > 1) {*/
            $enDate=Date::JalaliToGreg($commute);


            $sql = "SELECT count(dm_datastoretable_34.RowID)" .
                " FROM `dm_datastoretable_34`" .
                " inner join oa_document on (oa_document.rowid = dm_datastoretable_34.docid and oa_document.isenable=1)" .
               /* "inner join wf_execution on(wf_execution.execution_doc_id = oa_document.RowID AND wf_execution.is_enable = 1)" .*/
                " WHERE" .
                " oa_document.DocStatus=0" .
                " AND dm_datastoretable_34.Field_20='$personalId'" .
                " AND field_9='$enDate' AND dm_datastoretable_34.Field_13 > 1";


            $db = WFPDOAdapter::getInstance();
            $count = $db->executeScalar($sql);

            if ($count < 1)
                return 0;
        }
        return 1;

    }

    private function getCommuteFromOtherQuery($beginDate, $endDate, $personalId){


        $myArray=[];
        $recs="";
        try {
            $client = new SoapClient('http://172.16.61.253/Timex.asmx?wsdl');
            $s1 = "EXEC [adon].[TimeSheetView] " . $personalId . ",'" . $beginDate . "','" . $endDate . "'";
            $param = array(
                'username' => '3ef1b48067e4f2ac9913141d77e847dd',
                'password' => '9a3f5b14f1737c15e86680d9cd40b840',
                'objStr' => $s1
            );
            $resp1 = $client->RunSelectQuery($param);
            $recs = $resp1->RunSelectQueryResult->cols;

            $recs = json_decode(json_encode($recs), true);
        }catch (Exception $e){
            die($e);
        }

        $count=count($recs);

        for ($i = 0; $i < $count; $i++) {
            $myArray[$i]=[];



            if ($count == 1) $row = $recs['recs']['string'];
            else $row = $recs[$i]['recs']['string'];

// تاريخ و روز
            $date = urldecode($row[2]);

            $date="13".$date;


            array_push($myArray,$date);
            $myArray[$i][0]=$date;
            $myArray[$i][1]="";




            $Check = strpos(urldecode($row[15]), 'مرخص');
            if ($Check !== false) {
                $myArray[$i][1]="مرخصی روزانه";
                           }

            $Check = strpos(urldecode($row[15]), 'استعلا');
            if ($Check !== false) {
                $myArray[$i][1]="مرخصی استعلاجی";
            }

            $Check = strpos(urldecode($row[15]), 'مامور');
            if ($Check !== false) {
                $myArray[$i][1]="ماموریت";
            }

            $Check = strpos(urldecode($row[15]), 'ناقص');
            if ($Check !== false) {
                $myArray[$i][1]="تردد ناقص";
            }

            $Check = strpos(urldecode($row[15]), 'بت');
            if ($Check !== false) {
                $myArray[$i][1]="غیبت";
            }
        }

        $indexIncomplete=0;
        $indexAbsence=0;
        foreach ($myArray as $commuteDay){
            if($commuteDay[1]=="تردد ناقص") {
                $this->incompleteCommuteArray[$indexIncomplete]=$commuteDay[0];
                $indexIncomplete++;
            }else if ($commuteDay[1]=="غیبت"){
                $this->absenceArray[$indexAbsence]=$commuteDay[0];
                $indexAbsence++;
            }
        }

        return $myArray;

    }

    private function existMissionFormForAbsence($personalId)
    {

        foreach ($this->incompleteCommuteArray as $commute) {
            $sql = "SELECT count(dm_datastoretable_31.RowID)" .
                " FROM `dm_datastoretable_31" .
                " inner join oa_document on (oa_document.rowid = dm_datastoretable_31.docid and oa_document.isenable=1)" .
               /* " inner join wf_execution on(wf_execution.execution_doc_id = oa_document.RowID AND wf_execution.is_enable = 1)" .*/
                " WHERE" .
                " oa_document.DocStatus=0" .
                " AND dm_datastoretable_31.Field_20='$personalId'" .
                " AND field_32>='$commute' AND field_33 <='$commute' dm_datastoretable_31.Field_13 > 1";

            $db = WFPDOAdapter::getInstance();
            $count = $db->executeScalar($sql);

            if ($count < 1)
                return 0;
        }
        return true;
    }

    private function exitDayOffFormForAbsence($personalId){

        return true;
    }
}

$mainAjax = new MainAjax();
Response::getInstance()->response = $mainAjax->main();
return $mainAjax;


