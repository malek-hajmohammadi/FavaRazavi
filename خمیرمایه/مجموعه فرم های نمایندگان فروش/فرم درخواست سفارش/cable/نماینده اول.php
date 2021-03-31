<?php




class calssName
{
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
        $sql = "SELECT Field_1 FROM dm_datastoretable_40 WHERE Field_1 like '$uid,%'";
        $db->executeSelect($sql);
        $res = $db->fetchAssoc();
        $result=$res?$res['Field_1']:"0";

        $execution->workflow->myForm->setFieldValueByName('Field_17', $result);
        $execution->workflow->myForm->setFieldValueByName('Field_4', $result);
    }

    protected function getRemainCredit($execution){
        $result="0";
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

