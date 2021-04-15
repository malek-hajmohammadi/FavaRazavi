<?php




class calssName
{
    private $employeeID;

    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {
        $this->getRepresentationInfoAndSet($execution);
        $this->getMaxCredit($execution);
        $this->getRemainCredit($execution);
        $this->setStage($execution);

    }

    protected function getMaxCredit($execution){
        $result="0";

        $acm = AccessControlManager::getInstance();
        $uid = $acm->getUserID();
        $db = PDOAdapter::getInstance();
       // $sql = "SELECT Field_1 FROM dm_datastoretable_40 WHERE Field_1 LIKE '$uid,%'";
        $sql = "SELECT Field_1 FROM dm_datastoretable_40 WHERE Field_0 like '$uid,%'";
        $db->executeSelect($sql);

        if($row = $db->fetchAssoc())
            $result=$row;
        else
            $result=0;

        $execution->workflow->myForm->setFieldValueByName('Field_17', $result);
    }


    protected function getRemainCredit($execution){
        $result="0";

        try {

            $client = new SoapClient("http://192.168.0.121/WSStaffInOut/StaffInOut.asmx?wsdl");
            $client->soap_defencoding = 'UTF-8';
            $params = array("uname" => 'bf6db9a036816352f2a5128417ad3154', "pass" => 'dbe99b52bf0008708dc38373490d1526',
                            "Xcode" => $this->employeeID);

            $result = $client->GetCustomer($params);

            $result=$result->GetCustomerResult->data->Customer->GBes;

            $result=intval($result);



            // $res = get_object_vars($res->Xname)["StaffOutIn"];


        } catch (SoapFaultException $e) {
            $result=$e;
        } catch (Exception $e) {
            $result= $e;
        }

        $execution->workflow->myForm->setFieldValueByName('Field_18', $result);

    }

    protected function getRepresentationInfoAndSet($execution)
    {

        $acm = AccessControlManager::getInstance();
        $uid = $acm->getUserID();
        $db = PDOAdapter::getInstance();
        $sql = "SELECT * FROM oa_users WHERE UserID = ".$uid;
        $db->executeSelect($sql);
        $person = $db->fetchAssoc();

        $sex="";
        if($person['sex'] == '1')
            $sex = 'آقاي';
        else $sex = 'خانم';

        $name=$sex.' '.$person['fname'].' '.$person['lname'];
        $naturalId = $person['NationalCode'] != NULL ? $person['NationalCode'] : '-';
        $phone = $person['mobile'] != NULL ? $person['mobile'] : '';
        $address = $person['address'] != NULL ? $person['address'] : '';

        $this->employeeID=$person['employeeID'] != NULL ? $person['employeeID'] : '0';

        $execution->workflow->myForm->setFieldValueByName('Field_1', $name);
        $execution->workflow->myForm->setFieldValueByName('Field_2', $naturalId);
        $execution->workflow->myForm->setFieldValueByName('Field_3', $phone);
        $execution->workflow->myForm->setFieldValueByName('Field_4', $address);

        /*برای استفاده در sms*/
        $execution->setVariable('repMobile', $phone);

        /*فلگی که  بعدا چک می کنم که ایا سفارش تایید شده هنوز یا نه*/
        $execution->workflow->myForm->setFieldValueByName('Field_16', 0);


    }

    protected function setStage($execution){
        $execution->workflow->myForm->setFieldValueByName('Field_5', "namayandeh");

    }

}

