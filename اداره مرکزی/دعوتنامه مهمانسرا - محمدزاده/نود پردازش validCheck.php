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

        if (intval($httpcode == 200))
            return true;
        else {
            switch ($httpcode){
                case 403:
                    return 'کابر یا کلمه عبور نامعتبر';
                    break;
                case 412:
                    return 'خطار اعتبار سنجی';
                    break;
                case 404:
                    return 'فردی با چنین مشخصات وجود ندارد';
                    break;
                case 409:
                    return 'اطلاعات شخص معتبر نیست به اداره ثبت احوال مراجعه نمایید';
                    break;
                case 410:
                    return 'فرد فوت کرده است';
                    break;
                case 403:
                    return 'عدم امکان برفراری ارتباط';
                    break;
                Default:
                    return $httpcode;
            }

        }

    }

    public function execute(ezcWorkflowExecution $execution) // $execution refer to active workflow that call this class
    {

        $detailID = 875;
        $docID = $execution->workflow->myForm->instanceID;
        $db = MySQLAdapter::getInstance();
        $execution->workflow->myForm->setFieldValueByName('Field_11', '');

        $sql = "select * from dm_datastoretable_$detailID dm
                inner join oa_document on(oa_document.RowID = dm.DocID and oa_document.IsEnable = 1)
                where dm.MasterID = $docID";
        $db->executeSelect($sql);

        $lines = array();
        while ($row = $db->fetchAssoc())
            $lines[] = $row;

        $repeat = array();
        $fails = array();

        $execution->workflow->myForm->setFieldValueByName('Field_11', count($lines));
        foreach ($lines as $line) {
            // Skip the empty line
            if (empty($line)) continue;
            $nationalCode = $line['Field_0'];
            $sql = "select count(dm.RowID) from dm_datastoretable_$detailID dm
                inner join oa_document on(oa_document.RowID = dm.DocID and oa_document.IsEnable = 1)
                inner join dm_datastoretable_873 pdm on(pdm.DocID = dm.MasterID)
                inner join oa_document parentDocument on(parentDocument.RowID = pdm.DocID and parentDocument.IsEnable = 1)
                inner join wf_execution on(wf_execution.execution_doc_id = pdm.DocID AND wf_execution.is_enable = 1)
                where dm.Field_0 like '$nationalCode' and pdm.Field_12 > 2";
            $check = $db->executeScalar($sql);

            $birthDate = $line['Field_3'];
            $validCheck = $this->nationalCodeCheck($nationalCode, $birthDate);
            if (intval($check) > 0) {
                $repeat[] = $nationalCode;
            } elseif ($validCheck !== true) {
                $fails[] = $nationalCode.'('.$validCheck.') ';
            }
        }
        if (count($repeat) || count($fails)) {
            if (count($repeat)) {
                $res = '<span style="color:red">این شماره ملی ها قبلا در سیستم ثبت گردیده: <br>' . implode(',', $repeat) . '</span>';
            }
            if (count($fails)) {
                $res .= '<br><span style="color:red">این شماره ملی ها معتبر نمیباشند، شماره ملی و تاریخ تولد هریک را بررسی نمایید: <br>' . implode(',', $fails) . '</span>';
            }
            $execution->workflow->myForm->setFieldValueByName('Field_11', $res);
            $execution->setVariable('validCheck', 0);
        }
        else
            $execution->setVariable('validCheck', 1);



    }
}

?>
