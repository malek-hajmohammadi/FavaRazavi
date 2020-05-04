<?php

class calssName // do not change this line
{
    //protected $variable = null; define vars sample


    public function __construct()
    {
        // must be empty
    }

    private function nationalCodeCheck($nationalCode, $birthDate)
    {
        $user = "mohammadzadeh";
        $pass = "722yap788zkh";

        $client = curl_init('https://sabt-api.aqr.ir/api/national-code-verify/');

        curl_setopt($client, CURLOPT_POST, 1);
        curl_setopt($client, CURLOPT_HEADER, true);
        curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($client, CURLOPT_VERBOSE, true);

        $params = array(
            "apiUser" => "$user",
            "apiKey" => "$pass",
            "nationalCode" => "$nationalCode",
            "birthDate" => "$birthDate"
        );
        $params = json_encode($params);

        curl_setopt($client, CURLOPT_POSTFIELDS, $params);

        $res = curl_exec($client);

        $header_size = curl_getinfo($client, CURLINFO_HEADER_SIZE);
        $httpcode = curl_getinfo($client, CURLINFO_HTTP_CODE);
        curl_close($client);
        $res = substr($res, $header_size);

        if(intval($httpcode == 200))
            return true;
        else {
            switch ($httpcode){
                case 403:
                    return 'كابر يا كلمه عبور نامعتبر';
                    break;
                case 412:
                    return 'خطاي اعتبار سنجي';
                    break;
                case 404:
                    return 'فردي با چنين مشخصات وجود ندارد';
                    break;
                case 409:
                    return 'اطلاعات شخص معتبر نيست به اداره ثبت احوال مراجعه نماييد';
                    break;
                case 410:
                    return 'فرد فوت كرده است';
                    break;
                case 403:
                    return 'عدم امكان برفراري ارتباط';
                    break;
                Default:
                    return $httpcode;
            }

        }

    }

    public function execute(ezcWorkflowExecution $execution) // $execution refer to active workflow that call this class
    {

        $docID = $execution->workflow->myForm->instanceID;

        $sql = "SELECT 
            oa_content.IsScan,
            oa_content.RealFileName, 
            oa_content.RowID, 
            oa_content.MimeType, 
            Date(oa_content.CreateDate) AS CD, 
            oa_content.EncryptedHeader,
            oa_content.ContentType, 
            oa_content.PhysicalFileName, 
            oa_content.CurrentAddress,
            oa_content.DocReferID, 
            oa_content.SecID 
        FROM oa_content
        INNER JOIN dm_structure_field on(dm_structure_field.StructID = 873 and oa_content.PersistFileName = dm_structure_field.RowID)
        WHERE 
            oa_content.DocReferID = $docID 
            AND oa_content.contentState = 1
            and dm_structure_field.FieldName = 'field_6' 
            and dm_structure_field.IsEnable = 1";
        $db = MySQLAdapter::getInstance();
        $db->executeSelect($sql);
        if (!$row = $db->fetchAssoc()) {
            $res = '<span style="color:red">فايل  پيوست يافت نشد</span>';
            $execution->workflow->myForm->setFieldValueByName('Field_11', $res);
            return false;
        }
        $file_name = $row['RealFileName'];
        $cid = $row['RowID'];
        $type = $row['MimeType'];
        $isScan = $row['IsScan'];
        $referDocId = $row['DocReferID'];

        Response::getInstance()->doGenerate = false;

        $date = explode('-', $row['CD']);
        $now = Date::gregorian_to_jalali($date[0], $date[1], $date[2]);


        $path = SecUtils::GetStoragePath($row['SecID'], $now[0], $now[1]);

        $cont = $row['EncryptedHeader'];
        $cont = SecureContent::Decode($cont);
        $detailID = 875;

        if (file_exists($path . $cid)) {

            $content = $cont . file_get_contents($path . $cid);
            $content = base64_encode($content);

            $client = new SoapClient('http://10.10.10.113/WSExcelTools/default.asmx?wsdl');
            $html = '';
            $param = array('strBase64FileData' => $content, 'extension' => 'xlsx');
            $resp1 = $client->ExcelToJson($param);
            $lines = $resp1->ExcelToJsonResult;
            $lines = json_decode($lines, true);

            $repeat = array();
            $fails = array();

            if(!$lines || count($lines) == 0) {
                $res = '<span style="color:red">ليست وارد شده خالي ميباشد<br>('.var_export($resp1->ExcelToJsonResult, true).')</span>';
                $execution->workflow->myForm->setFieldValueByName('Field_11', $res);
                return;
            }
            foreach ($lines as $line) {
                // Skip the empty line
                if (empty($line)) continue;
                $nationalCode = $line['field_0'];
                $sql = "select count(dm.RowID) from dm_datastoretable_$detailID dm
                inner join oa_document on(oa_document.RowID = dm.DocID and oa_document.IsEnable = 1)
                inner join dm_datastoretable_873 pdm on(pdm.DocID = dm.MasterID)
                inner join oa_document parentDocument on(parentDocument.RowID = pdm.DocID and parentDocument.IsEnable = 1)
                inner join wf_execution on(wf_execution.execution_doc_id = pdm.DocID AND wf_execution.is_enable = 1)
                where dm.Field_0 like '$nationalCode' and pdm.Field_12 > 2";
                $check = $db->executeScalar($sql);

                $birthDate = Date::JalaliToGreg($line['field_1']);
                $validCheck = $this->nationalCodeCheck($nationalCode, $birthDate);
                if (intval($check) > 0) {
                    $repeat[] = $nationalCode;
                }
                elseif ($validCheck !== true){
                    $fails[] = $nationalCode.'('.$validCheck.') ';
                }
            }
            if(count($repeat) || count($fails)){
                if(count($repeat)) {
                    $res = '<span style="color:red">اين شماره ملي ها قبلا در سيستم ثبت گرديده: <br>' . implode(',', $repeat).'</span>';
                }
                if(count($fails)) {
                    $res .= '<br><span style="color:red">اين شماره ملي ها معتبر نميباشند، شماره ملي و تاريخ تولد هريك را بررسي نماييد: <br>' . implode(',', $fails).'</span>';
                }
                $execution->workflow->myForm->setFieldValueByName('Field_11', $res);
                return;
            }
            foreach ($lines as $line) {
                // Skip the empty line
                if (empty($line)) continue;

                $nationalCode = $line['field_0'];
                $birthDate = $line['field_1'];
                $name = $line['field_2'];
                $family = $line['field_3'];

                $myForm = new DMSNFC_form(array('fieldid' => $detailID, 'docid' => null, 'referid' => null, 'masterid' => $docID));
                $fdata = array("10690" => $nationalCode, "10695" => $name, "10697" => $family, "11765" => $birthDate);
                $myForm->setData($fdata);
            }

        } else {
            $res = '<span style="color:red">متاسفانه فايل پيوست يافت نشد</span>';
            $execution->workflow->myForm->setFieldValueByName('Field_11', $res);
            return;
        }


    }
}

?>