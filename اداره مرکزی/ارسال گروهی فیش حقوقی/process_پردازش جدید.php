<?php



class calssName
{
    public function __construct()
    {
    }

    public function cURL($cookies = TRUE, $cookie = 'cookies.txt', $compression = 'gzip', $proxy = '')
    {
        $this->headers[] = 'Accept: image/gif, image/x-bitmap, image/jpeg, image/pjpeg';
        $this->headers[] = 'Connection: Keep-Alive';
        $this->headers[] = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8';
        $this->user_agent = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.0.3705; .NET CLR 1.1.4322; Media Center PC 4.0)';
        $this->compression = $compression;
        $this->proxy = $proxy;
        $this->cookies = $cookies;
        if ($this->cookies == TRUE) $this->cookie($cookie);
    }//end func

    public function cookie($cookie_file)
    {
        if (file_exists($cookie_file)) {
            $this->cookie_file = $cookie_file;
        } else {
            fopen($cookie_file, 'w') or $this->error('The cookie file could not be opened. Make sure this directory has the correct permissions');
            $this->cookie_file = $cookie_file;
            fclose($this->cookie_file);
        }
    }//end func

    public function get($url)
    {
        $process = curl_init($url);
        curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($process, CURLOPT_HEADER, 0);
        curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent);
        if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEFILE, $this->cookie_file);
        if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEJAR, $this->cookie_file);
        curl_setopt($process, CURLOPT_ENCODING, $this->compression);
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        if ($this->proxy) curl_setopt($process, CURLOPT_PROXY, $this->proxy);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
        $return = curl_exec($process);
        echo 'curl error:' . curl_error($process);
        curl_close($process);
        return $return;
    }//end func

    public function post($url, $data)
    {
        $process = curl_init($url);
        curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($process, CURLOPT_HEADER, 1);
        curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent);
        if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEFILE, $this->cookie_file);
        if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEJAR, $this->cookie_file);
        curl_setopt($process, CURLOPT_ENCODING, $this->compression);
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        if ($this->proxy) curl_setopt($process, CURLOPT_PROXY, $this->proxy);
        curl_setopt($process, CURLOPT_POSTFIELDS, $data);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($process, CURLOPT_POST, 1);
        $return = curl_exec($process);
        curl_close($process);
        return $return;
    }//end func

    public function execute(ezcWorkflowExecution $execution)
    {

        //---------------------------------------
        $message = 'نتيجه اجراي عمليات براي كاربران';
        $strHTML1 = '<h5>' . $message . '</h5><table dir="rtl" style="" cellpadding="1" cellspacing="0" border="1" >';
        $strHTML1 .= '<tr><th>شماره پرسنلي</th><th width="120"> نام و نام خانوادگي</th><th>شماره فرم</th><th>نتيجه عمليات</th></tr>';
        //---------------------------------------


        $pathst = "/opt/storage/Fish/ReceivedFiles/";
        $pathstdes = "/opt/storage/Fish/SendedFiles/";

        if(!is_writable($pathstdes)){
            die("is not writable!");
        }


        $server = "http://10.10.10.6/RAVAN/Runtime/process.php";
        $server = "https://ravan.aqr.ir/RAVAN/Runtime/process.php";
        $server = "https://$_SERVER[HTTP_HOST]/RAVAN/Runtime/process.php";

        if (is_dir($pathst)) {
            if ($handle = opendir($pathst)) {
                filerecorder::recorder("گردشكار فيش حقوق");


                while (false !== ($file = readdir($handle))) {
                    # Make sure we don't push parental directories or dotfiles (unix) into the arrays
                    if ($file != "." && $file != ".." && $file != "") {

                        $real_name = $file;
                        $userFamily = '';
                        $newTitle = "فيش حقوقي ";
                        filerecorder::recorder("real_name : " . var_export($real_name, true));
                        $file_arr = explode(".", $file);
                        $fileARR = explode('_', trim($file_arr[0]));
                        //شماره پرسنلي
                        $personID = $fileARR[0];
                        $personID = intval($personID);
                        $year = $fileARR[1];
                        $mah = $fileARR[2];
                        filerecorder::recorder("personID : " . var_export($personID, true));
                        filerecorder::recorder("year : " . var_export($year, true));
                        filerecorder::recorder("mah : " . var_export($mah, true));
                        //uid $ rid
                        $db = WFPDOAdapter::getInstance();
                        $sql2 = 'select oa_users.UserID as UID,oa_depts_roles.RowID as RID,oa_users.fname,oa_users.lname,oa_depts_roles.Name,oa_users.topPersonalID from oa_users left Outer join oa_depts_roles ON (oa_users.UserID = oa_depts_roles.UserID ) where oa_users.employeeID="' . $personID . '" limit 0,1';

                        $db->executeSelect($sql2);
                        $person = $db->fetchAssoc();
                        if ($person) {

                            $UID = $person['UID'];

                            $RID = $person['RID'];

                            $fname = $person['fname'];
                            $lname = $person['lname'];
                            $formID = 298;
                            //بدست اوردن شماره كردشكار جاري
                            $db = WFPDOAdapter::getInstance();
                            $wfid = $db->executeScalar("SELECT `workflow_id` FROM `wf_workflow` WHERE `workflow_enable` = 1 AND `workflow_formtypeid` =  $formID");
                            echo 'wfid' . $wfid;
                            $workFlowID = $wfid;
                            $db = WFPDOAdapter::getInstance();
                            $acm = AccessControlManager::getInstance();
                            $uid = $acm->getUserID();
                            $sql2 = "select pass,OldUserID FROM  oa_users where UserID=$uid";

                            $db->executeSelect($sql2);
                            $person = $db->fetchAssoc();

                            if ($person) {
                                $pass = $person['pass'];

                                $user = $person['OldUserID'];


                            }

                            $user = urlencode($user);
                            //ايجاد فرم
                            $urlReferID = $server . "?module=WorkFlowManager&action=stratNewWorkFlow&WID=" . $workFlowID . "&user=" . $user . "&pass=" . $pass;

                            echo 'urlReferID' . $urlReferID;
                            $output = $this->get($urlReferID);
                            echo 'output' . $output;
                            $resp = explode(',', $output);


                            //referID
                            $str = explode(':', $resp[3]);

                            $referID = $str[1];

                            //did
                            $strdocID = explode(':', $resp[2]);

                            $docID = $strdocID[1];


                            //ايجاد پيوست براي فرم ايجاد شده
                            $did = $docID;
                            $myFile = $pathst . $real_name;

                            if(!is_readable($myFile)){
                                die("myFile is not readable");
                            }


                            $url = "module=DocAttachs&action=addAttachWS";
                            $url .= "&user=" . $user;
                            $url .= "&pass=" . $pass;
                            $url .= "&did=" . $did;
                            $url .= "&aws_fname=" . $real_name;
                            $url .= "&aws_ftype=image/jpeg";

                            $url .= "&aws_fcontent=" . urlencode(base64_encode(file_get_contents($myFile)));








                            $ch = curl_init($server);
                            $params = $url;

                            die("param=$params");
                            curl_setopt($ch, CURLOPT_POST, true);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
                            curl_setopt($ch, CURLOPT_TIMEOUT, 1);
                            curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);


                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);



                            $output = curl_exec($ch);

                            $code=curl_getinfo($ch, CURLINFO_HTTP_CODE) ;


                            $resp = explode('(', $output);
                            $resp = explode(')', $resp[1]);
                            $aid = $resp[0];

                            curl_close($ch);


                            //مقداردهي به فيلدهاي فرم
                            //set data in form
                            /*$user=array('uid'=>$UID ,'rid'=>$RID );

                            $myForm = new DMSNFC_form(array('fieldid' => $formID, 'docid' => $docID, 'referid' => $referid));
                            $fdata = array("5110" =>$referID, "5111" => $aid,"5112" => $user);

                            $result2=$myForm->setData($fdata);*/

                            $yeartmp = intval($year) - 93;
                            $mahtmp = intval($mah) - 1;

                            $formData = 'data={"5113":"' . $yeartmp . '","5114":"' . $mahtmp . '","5110":"' . $referID . '","5111":"' . $aid . '","5112":[{"uid":"' . $UID . '","rid":"' . $RID . '"}]}';

                            $urlSave = $server . '?module=NForms&action=setData&ttype=form&fieldid=' . $formID . '&docid=' . $docID . '&' . $formData . '&referid=' . $referID . '&pass=' . $pass . '&user=' . $user;


                            $result2 = $this->get($urlSave);


                            if ($result2 == '(true)') {

                                $err = 'فرم با موفقيت ايجاد شد';
                                $urlSendOk = $server . '?module=WorkFlowManager&action=workflowAction&referID=' . $referID . '&structID=298&docID=' . $docID . '&pass=' . $pass . '&user=' . $user . '&commandKey=10_0&cc=&referNote=' . urlencode('جهت استحضار');

                                $resultSendOk = $this->get($urlSendOk);

                                $userFamily = ' - ' . $lname . ' ' . $fname;

                                $strHTML1 .= '<tr><td>' . $personID . '</td><td>' . $fname . '  ' . $lname . '</td><td>' . $docID . '</td><td>' . $err . '</td></tr>';

                                $newTitle = $newTitle . $userFamily . ' مورخ ' . $year . '/' . $mah;

                                $sql_title = "update oa_document set Subject='" . $newTitle . "' where RowID=" . $did . "  limit 1";
                                $db->execute($sql_title);

                                if (is_dir($pathstdes)) {
                                    $pfile = $pathst . $real_name;
                                    $desfile = $pathstdes . $real_name;
                                    //filerecorder::recorder("pfile:".var_export($pfile,true) ,"mm" );
                                    copy($pfile, $desfile);
                                    unlink($pfile);
                                } else {
                                    $pfile = $pathst . $real_name;
                                    unlink($pfile);
                                }


                            } else
                                $err = 'خطا در ايجاد فرم.';


                        } else {
                            $err = 'خطا در ايجاد فرم.';
                            $strHTML1 .= '<tr><td>' . $real_name . '</td><td></td><td></td><td>' . $err . '</td></tr>';
                        }
                    }//end if
                    else {
                        $err = 'خطا در ايجاد فرم.';
                        $strHTML1 .= '<tr><td>' . $real_name . '</td><td></td><td></td><td>' . $err . '</td></tr>';
                    }

                }//end while
                $strHTML1 .= '</table>';

                $execution->workflow->myForm->setFieldValueByName('Field_1', $strHTML1);

            }//end if
        }//endif

    }
}


