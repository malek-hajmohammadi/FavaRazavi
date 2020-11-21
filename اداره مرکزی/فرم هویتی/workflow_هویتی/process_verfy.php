<?php


class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution) // $execution refer to active workflow that call this class
    {

        $docID = $execution->workflow->myForm->instanceID;
        $execution->workflow->myForm->getFieldValueByName('Field_11');
        $user = "ravan-aqr";
        $pass = "587ebw667nwf989";

        $nationalCode = $execution->workflow->myForm->getFieldValueByName('Field_6');
        $birthDate = $execution->workflow->myForm->getFieldValueByName('Field_8');
        $birthDate = Date::JalaliToGreg($birthDate);
        $shenasnameNo = $execution->workflow->myForm->getFieldValueByName('Field_4');
        $nationalCardSerial = $execution->workflow->myForm->getFieldValueByName('Field_7');
        $nationalCardSerial = strtoupper($nationalCardSerial);
        $execution->workflow->myForm->setFieldValueByName('Field_7', $nationalCardSerial);
        $shenasnameSerial = $execution->workflow->myForm->getFieldValueByName('Field_23');
        $field_26 = $execution->workflow->myForm->getFieldValueByName('Field_26');
        $chars = array("الف", "ب", "پ", "ت", "ث", "ج", "چ", "ح", "خ", "د", "ذ", "ر", "ز", "ژ", "س", "ش", "ص", "ض", "ط", "ظ", "ع", "غ", "ف", "ق", "ك", "گ", "ل", "م", "ن", "و", "ه", "ي");
        $field_26 = $chars[$field_26];
        $field_22 = $execution->workflow->myForm->getFieldValueByName('Field_22');
        $shenasnameSeries = $field_26 . $field_22;
        $firstName = $execution->workflow->myForm->getFieldValueByName('Field_0');
        $lastName = $execution->workflow->myForm->getFieldValueByName('Field_1');
        $fatherName = $execution->workflow->myForm->getFieldValueByName('Field_3');
        $gender = $execution->workflow->myForm->getFieldValueByName('Field_2');
        if ($gender == 1)
            $gender = 'male';
        elseif ($gender == 2)
            $gender = 'female';

        $textfalse404 = '<div class=StatusSabtFalse>کد ملی یا تاریخ تولد معتبر نیست</div>';
        $textfalse409 = '<div class=StatusSabtFalse>اطلاعات شما در بانک اطلاعاتی نهایی نیست</div>';
        $textfalse410 = '<div class=StatusSabtFalse>فرد در قید حیات نیست</div>';
        $textfalse481 = '<div class=StatusSabtFalse>سریال کارت ملی نامعتبر است</div>';
        $textfalse503 = '<div class=StatusSabtFalse>موقتا امکان بررسی وجود ندارد</div>';
        $textfalse403 = '<div class=StatusSabtFalse>کابر یا کلمه عبور نامعتبر</div>';
        $textfalse412 = '<div class=StatusSabtFalse>خطای اعتبار سنجی</div>';
        $textfalseD = '<div class=StatusSabtFalse>خطای ناشناخته</div>';
        $texttrue = '';

        $client = curl_init('https://sabt-api.aqr.ir/api/detail-verify-multi/');

        curl_setopt($client, CURLOPT_POST, 1);
        curl_setopt($client, CURLOPT_HEADER, true);
        curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($client, CURLOPT_VERBOSE, true);

        $persons = array();
        $persons[] = array(
            "nationalCode" => $nationalCode,
            "birthDate" => $birthDate,
            "shenasnameNo" => $shenasnameNo,
            "nationalCardSerial" => $nationalCardSerial,
            "shenasnameSerial" => $shenasnameSerial,
            "shenasnameSeries" => $shenasnameSeries,
            "firstName" => $firstName,
            "lastName" => $lastName,
            "fatherName" => $fatherName,
            "gender" => $gender,
        );

        $params = array(
            "apiUser" => "$user",
            "apiKey" => "$pass",
            "persons" => $persons
        );
        $params = json_encode($params);

        curl_setopt($client, CURLOPT_POSTFIELDS, $params);

        $res = curl_exec($client);

        $header_size = curl_getinfo($client, CURLINFO_HEADER_SIZE);
        $httpcode = curl_getinfo($client, CURLINFO_HTTP_CODE);
        curl_close($client);
        $res = substr($res, $header_size);


        $execution->workflow->myForm->setFieldValueByName('Field_27', $httpcode . "($res)");

        switch ($httpcode) {
            case 200:
                $execution->setVariable('nextPath', 1);
                $execution->workflow->myForm->setFieldValueByName('Field_31', $texttrue);
                $res = json_decode($res, true);
                if (isset($res['persons']) && count($res['persons']) > 0 && isset($res['persons'][0]['firstName'])) {
                    $firstName = $res['persons'][0]['firstName'];
                    $lastName = $res['persons'][0]['lastName'];
                    $execution->workflow->myForm->setFieldValueByName('Field_0', $firstName);
                    $execution->workflow->myForm->setFieldValueByName('Field_1', $lastName);
                }
                break;
            case 404:
                $execution->setVariable('nextPath', 3);
                $newReferNote = "جهت اصلاح اطلاعات";
                $execution->workflow->myForm->setFieldValueByName('Field_31', $textfalse404);
                break;
            case 409:
                $execution->setVariable('nextPath', 3);
                $newReferNote = "جهت تلاش مجدد";
                $execution->workflow->myForm->setFieldValueByName('Field_31', $textfalse409);
                break;
            case 410:
                $execution->setVariable('nextPath', 3);
                $newReferNote = "جهت اصلاح اطلاعات";
                $execution->workflow->myForm->setFieldValueByName('Field_31', $textfalse410);
                break;
            case 481:
                $execution->setVariable('nextPath', 3);
                $newReferNote = "جهت اصلاح اطلاعات";
                $execution->workflow->myForm->setFieldValueByName('Field_31', $textfalse481);
                break;
            case 503:
                $execution->setVariable('nextPath', 2);
                $execution->workflow->myForm->setFieldValueByName('Field_31', $textfalse503);
                break;
            case 403:
                $execution->setVariable('nextPath', 2);
                $execution->workflow->myForm->setFieldValueByName('Field_31', $textfalse403);
                break;
            case 412:
                $execution->setVariable('nextPath', 2);
                $execution->workflow->myForm->setFieldValueByName('Field_31', $textfalse412);
                break;
            default:
                $execution->setVariable('nextPath', 2);
                $execution->workflow->myForm->setFieldValueByName('Field_31', $textfalseD);
                break;

        }

        if ($execution->getVariable('nextPath') == 3) {

            $referID = Request::getInstance()->varCleanFromInput("referID");
            $sql = "update oa_doc_refer set NoteDesc='" . $newReferNote . "' where oa_doc_refer.ParentID = $referID";
            $db = MySQLAdapter::getInstance();
            $db->execute($sql);

        }

    }
}


