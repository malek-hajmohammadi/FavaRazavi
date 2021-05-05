<?php

class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution, ezcWorkflowNodeAction $caller = null)
    {

        /* Status
        0 خطادر اتصال به تردد
        1 عدم خطا در اتصال
        2 ساعت ها درست بود درج نشود
        3 برسد به دست متقاضی که دستی درج کند
        */

        $docID = $execution->workflow->myForm->instanceID;
        $emp = $execution->workflow->myForm->getFieldValueByName('Field_0'); //متقاضي
        $uid = $emp[0]['uid'];
        $sql1 = 'SELECT employeeID from oa_users where UserID=' . $uid;
        $db = WFPDOAdapter::getInstance();
        $emp = $db->executeScalar($sql1);
        $emp = trim($emp);
        $emp = ltrim($emp, '0');

        $date = $execution->workflow->myForm->getFieldValueByName('Field_9');
        $date1 = explode('/', $date);
        $rooz = $date1[0] . $date1[1] . $date1[2];
        $wn = date('w', strtotime(Date::JalaliToGreg($date)));

        $takmiletaradod = $execution->workflow->myForm->getFieldValueByName('Field_18');
        $StartTimeKara = $execution->workflow->myForm->getFieldValueByName('Field_4');
        $EndTimeKara = $execution->workflow->myForm->getFieldValueByName('Field_5');

        $StartTimeKara = date('H:i', strtotime($StartTimeKara));
        $EndTimeKara = date('H:i', strtotime($EndTimeKara));
        $StartTimeKaraNum = intval(str_replace(":", "", $StartTimeKara));
        $EndTimeKaraNum = intval(str_replace(":", "", $EndTimeKara));

        $FormType = $execution->workflow->myForm->getFieldValueByName('Field_11'); //نوع مرخصي
        $FormType = trim($FormType, ',');

        $TaradodCount = 0;

        //---
        try {
            $client = new SoapClient('http://172.16.61.25:8088/Timex.asmx?wsdl');
            $s1 = "EXEC [adon].[TimeSheetView] " . $emp . ",'" . $date . "','" . $date . "'";
            $s2 = "SELECT count(*) FROM [adon].[TblIOData] WHERE [CardNo]=" . $emp . " AND [IODate]='" . $date . "' AND iotime>0";
            $s3 = "EXEC [adon].[IOData_sel] " . $emp . ",'" . $date . "','" . $date . "'";

            $param1 = array(
                'username' => '3ef1b48067e4f2ac9913141d77e847dd',
                'password' => '9a3f5b14f1737c15e86680d9cd40b840',
                'objStr'   => $s1
            );
            $param2 = array(
                'username' => '3ef1b48067e4f2ac9913141d77e847dd',
                'password' => '9a3f5b14f1737c15e86680d9cd40b840',
                'objStr'   => $s2
            );
            $param3 = array(
                'username' => '3ef1b48067e4f2ac9913141d77e847dd',
                'password' => '9a3f5b14f1737c15e86680d9cd40b840',
                'objStr'   => $s3
            );

            $resp1 = $client->RunSelectQuery($param1);
            $resp2 = $client->RunSelectQuery($param2);
            $resp3 = $client->RunSelectQuery($param3);

            $recs1 = $resp1->RunSelectQueryResult->cols;
            $recs2 = $resp2->RunSelectQueryResult->cols;
            $recs3 = $resp3->RunSelectQueryResult->cols;

            $recs1 = json_decode(json_encode($recs1), true);
            $recs2 = json_decode(json_encode($recs2), true);
            $recs3 = json_decode(json_encode($recs3), true);

            $execution->setVariable('status', 1);
            $execution->workflow->myForm->setFieldValueByName('Field_13', '5');
        } catch (SoapFaultException $e) {
            $execution->setVariable('status', 0);
        } catch (Exception $e) {
            $execution->setVariable('status', 0);
        }
        //---

        $TimexCount = $recs2['recs']['string'];
        $KaraArray = $recs3['recs']['string'];
        $ww = $recs1['recs']['string'][1];

        if ($wn == 5) //جمعه
        {
            $startS = 0;
            $startE = 0;
            $endS = 2359;
            $endE = 2359;
        } /*
        else if((int)$rooz >= 13970419 && (int)$rooz <= 13970515)
        {
            $startS=630;$startE=645;
            $endS=2359;$endE=2359;

            if($wn!=4){$endS=1315;$endE=1330;}
            else{$endS=1215;$endE=1230;}
        }
        */
        else {
            if ($ww == 2) {
                $startS = 700;
                $startE = 715;
            } //شیفت اداری
            elseif ($ww == 4) {
                $startS = 700;
                $startE = 715;
            } //شیفت مرکز
            elseif ($ww == 15) {
                $startS = 700;
                $startE = 715;
            } //شیفت ثابت
            elseif ($ww == 12) {
                $startS = 700;
                $startE = 715;
            } //شیفت مرکز2
            elseif ($ww == 16) {
                $startS = 700;
                $startE = 715;
            } //شیفت ثابت2
            elseif ($ww == 11) {
                $startS = 800;
                $startE = 815;
            } //شیفت ۸ تا ۱۷
            elseif ($ww == 17) {
                $startS = 745;
                $startE = 800;
            } //شیفت نعیم رضوان
            else {
                $startS = 700;
                $startE = 715;
            }
            $endS = 2359;
            $endE = 2359;

            if ($wn != 4) //شنبه تا چهارشنبه
            {
                if ($ww == 2) {
                    $endS = 1415;
                    $endE = 1430;
                } //شیفت اداری
                elseif ($ww == 4) {
                    $endS = 1345;
                    $endE = 1400;
                } //شیفت مرکز
                elseif ($ww == 15) {
                    $endS = 1645;
                    $endE = 1700;
                } //شیفت ثابت
                elseif ($ww == 12) {
                    $endS = 1415;
                    $endE = 1430;
                } //شیفت مرکز2
                elseif ($ww == 16) {
                    $endS = 1645;
                    $endE = 1700;
                } //شیفت ثابت2
                elseif ($ww == 11) {
                    $endS = 1645;
                    $endE = 1700;
                } //شیفت ۸ تا ۱۷
                elseif ($ww == 17) {
                    $endS = 1450;
                    $endE = 1505;
                } //شیفت نعیم رضوان
                else {
                    $endS = 2359;
                    $endE = 2359;
                }
            } else //پنجشنبه
            {
                if ($ww == 2) {
                    $endS = 1315;
                    $endE = 1330;
                } //شیفت اداری
                elseif ($ww == 4) {
                    $endS = 1245;
                    $endE = 1200;
                } //شیفت مرکز
                elseif ($ww == 15) {
                    $endS = 1545;
                    $endE = 1600;
                } //شیفت ثابت
                elseif ($ww == 12) {
                    $endS = 1315;
                    $endE = 1330;
                } //شیفت مرکز2
                elseif ($ww == 16) {
                    $endS = 1545;
                    $endE = 1600;
                } //شیفت ثابت2
                elseif ($ww == 11) {
                    $endS = 1545;
                    $endE = 1600;
                } //شیفت ۸ تا ۱۷
                elseif ($ww == 17) {
                    $endS = 1450;
                    $endE = 1505;
                } //شیفت نعیم رضوان
                else {
                    $endS = 2359;
                    $endE = 2359;
                }
            }
        }
        //[17, 'مرخصي ساعتي']
        //[9, 'ماموريت ساعتي']
        //[200, 'اشكالات كارتزني']
        //[300, 'حذف تردد']
        //[15, 'اضافه كاري'] حذف----
        //[20, 'پاس شير'] حذف----

        //0 عادی
        //1 مرخصی
        //2 ماموریت

        if ($FormType == 300 || $FormType == 200 || $FormType == 15) //حذف - اشکالات - اضافه
        {
            if ($FormType == 200 || $FormType == 15) $FormType = 0;
            else $FormType = 300;

            if ($execution->workflow->myForm->getFieldValueByName('Field_5') == "" || $execution->workflow->myForm->getFieldValueByName('Field_5') == "00:00:00")
                $Times = array(array($StartTimeKara, $FormType));
            elseif ($execution->workflow->myForm->getFieldValueByName('Field_4') == "" || $execution->workflow->myForm->getFieldValueByName('Field_4') == "00:00:00")
                $Times = array(array($EndTimeKara, $FormType));
            else
                $Times = array(array($StartTimeKara, $FormType), array($EndTimeKara, $FormType));
            $sql = self::TimexDBAction($docID, $Times, $TimexCount, $KaraArray, $date, $emp);
            $level = '1';
        } else //مرخصی ساعتی - ماموریت ساعتی
        {
            if ($FormType == 17) $FormType = 1; //مرخصی
            else $FormType = 2; //ماموریت

            if ($StartTimeKaraNum > $startE && $EndTimeKaraNum < $endS) //ساعات مياني روز كاري  $endS=1415 or 1315
            {
                $Times = array(array($StartTimeKara, $FormType), array($EndTimeKara, 0));
                $sql = self::TimexDBAction($docID, $Times, $TimexCount, $KaraArray, $date, $emp);
                $level = '2';
            } else {
                if ($StartTimeKaraNum >= $startS && $StartTimeKaraNum <= $startE && $EndTimeKaraNum >= $endS) //كل روز ماموريت دارد
                {
                    $d = date_create($EndTimeKara);
                    date_add($d, date_interval_create_from_date_string("-1 minutes"));
                    $EndTimeKaraTmp = date_format($d, "H:i"); // يك دقيقه قبل از پايان

                    $d = date_create($StartTimeKara);
                    date_add($d, date_interval_create_from_date_string("1 minutes"));
                    $StartTimeKaraTmp = date_format($d, "H:i");  // يك دقيقه بعد از شروع

                    $Times = array(array($StartTimeKara, 0), array($StartTimeKaraTmp, $FormType), array($EndTimeKaraTmp, 0), array($EndTimeKara, 0));
                    $sql = self::TimexDBAction($docID, $Times, $TimexCount, $KaraArray, $date, $emp);
                    $level = '3';
                } else {
                    if ($StartTimeKaraNum >= $startS && $StartTimeKaraNum <= $startE) //اول صبح باشد
                    {
                        $Times = array(array($EndTimeKara, $FormType));
                        $sql = self::TimexDBAction($docID, $Times, $TimexCount, $KaraArray, $date, $emp);
                        $level = '4';
                    } else {
                        if ($EndTimeKaraNum >= $endS) //آخر روز باشد
                        {
                            if ($StartTimeKaraNum >= $endS)  // اگر شروع بعد از ساعت اداري باشد
                            {
                                if ($FormType == 2) //ماموریت
                                {
                                    $d = date_create($EndTimeKara);
                                    date_add($d, date_interval_create_from_date_string("-1 minutes"));
                                    $EndTimeKaraTmp = date_format($d, "H:i");
                                    $EndTimeIsLast = 1;
                                    for ($i = 2; $i < $TimexCount; $i = $i + 6) {
                                        if ($EndTimeKaraNum < (int)$KaraArray[$i]) {
                                            $EndTimeIsLast = 0;
                                            break;
                                        }
                                    }
                                    if ($EndTimeIsLast == 1) //اگر بعد از اين كارت نداشت
                                        $Times = array(array($StartTimeKara, $FormType), array($EndTimeKaraTmp, 0), array($EndTimeKara, 0));
                                    else // اگر بعد از اين كارت داشت
                                        $Times = array(array($StartTimeKara, $FormType), array($EndTimeKara, 0));
                                    $sql = self::TimexDBAction($docID, $Times, $TimexCount, $KaraArray, $date, $emp);
                                    $level = '5';
                                }
                                if ($FormType == 1) //مرخصی
                                {
                                    $Times = array(array($StartTimeKara, $FormType));
                                    $sql = self::TimexDBAction($docID, $Times, $TimexCount, $KaraArray, $date, $emp);
                                    $level = '6';
                                }
                            } else //مرخصي و ماموريت آخر ساعت
                            {
                                if ($FormType == 1) //مرخصی
                                {
                                    $Times = array(array($StartTimeKara, $FormType));
                                    $sql = self::TimexDBAction($docID, $Times, $TimexCount, $KaraArray, $date, $emp);
                                    $level = '7';
                                }
                                if ($FormType == 2) //ماموریت
                                {
                                    $d = date_create($EndTimeKara);
                                    date_add($d, date_interval_create_from_date_string("-1 minutes"));
                                    $EndTimeKaraTmp = date_format($d, "H:i");
                                    $EndTimeIsLast = 1;
                                    for ($i = 2; $i < $TimexCount; $i = $i + 6) {
                                        if ($EndTimeKaraNum < (int)$KaraArray[$i]) {
                                            $EndTimeIsLast = 0;
                                            break;
                                        }
                                    }
                                    //اگر بعد از اين كارت نداشت و تعداد كارت زوج بود بايد انتها را ببندد
                                    if ($EndTimeIsLast == 1 || $TimexCount % 2 == 0) {
                                        $Times = array(array($StartTimeKara, $FormType), array($EndTimeKaraTmp, 0), array($EndTimeKara, 0));
                                        $level = '8';
                                    } // اگر بعد از اين كارت داشت و يا كارت ها فرد بود نبايد انتها را ببندد
                                    else {
                                        $Times = array(array($StartTimeKara, $FormType), array($EndTimeKara, 0));
                                        $level = '9';
                                    }
                                    $sql = self::TimexDBAction($docID, $Times, $TimexCount, $KaraArray, $date, $emp);
                                }
                            }
                        }
                        /*
                        else //استثنا
                        {
                            $Times = array(array($EndTimeKara,$FormType));
                            $sql = self::TimexDBAction($docID, $Times, $TimexCount, $KaraArray, $date, $emp);
                            $level = '9';
                        }
                        */
                    }
                }
            }
        }

        $execution->workflow->myForm->setFieldValueByName('Field_24', 'startS-EndS:' . $startS . '-' . $endS . 'startE-endE:' . $startE . '-' . $endE . ' Level:' . $level . ' Query:' . $sql);
        $param1 = array(
            'username' => '3ef1b48067e4f2ac9913141d77e847dd',
            'password' => '9a3f5b14f1737c15e86680d9cd40b840',
            'objStr'   => $sql
        );
        $resp1 = $client->RunQuery($param1);

        return true;
    }


    protected function TimexDBAction($docID, $Times, $TimexCount, $KaraArray, $date, $emp)
    {
        $TimesCount = count($Times);

        $sql = "";
        for ($ti = 0; $ti < $TimesCount; $ti++) {
            $ki = 2;
            $j = 0;

            //1
            if ($TimexCount > 1) {
                $KaraArrayTmp = urldecode($KaraArray[$ki]);

                while ($j < $TimexCount && urldecode($KaraArray[$ki]) != $Times[$ti][0]) {
                    $ki = $ki + 6;
                    $j = $j + 1;
                    $KaraArrayTmp = urldecode($KaraArray[$ki]);
                }
            } else {
                $KaraArrayTmp = urldecode($KaraArray[$ki]);
            }

            //2
            if ($Times[$ti][1] == 300) {
                $tt = explode(':', $Times[$ti][0]);
                $tt = ($tt[0] * 60) + $tt[1];
                $sql .= "EXEC [adon].[IOData_del] " . $emp . ", '" . $date . "', " . $tt . ", '000000T000000U0';";
            } else {
                //3
                if ($KaraArrayTmp == $Times[$ti][0]) {
                    $tt = explode(':', $Times[$ti][0]);
                    $tt = ($tt[0] * 60) + $tt[1];
                    $sql .= "EXEC [adon].[IOData_up] " . $emp . ", '" . $date . "', " . $tt . ", " . $tt . ", " . $Times[$ti][1] . ",'WB$docID';";
                } else {
                    $tt = explode(':', $Times[$ti][0]);
                    $tt = ($tt[0] * 60) + $tt[1];
                    $sql .= "EXEC [adon].[IOData_ins] " . $emp . ", '" . $date . "', " . $tt . ", " . $Times[$ti][1] . ",'WB$docID';";
                }
            }

        }
        return $sql;
    }
}

