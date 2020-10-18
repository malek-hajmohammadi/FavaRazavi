<?php




class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {
        self::getRepresentationInfoAndSet($execution);
        self::setStage($execution);

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
        $phone = $person['tel'] != NULL ? $person['tel'] : '';
        $address = $person['address'] != NULL ? $person['address'] : '';

        $execution->workflow->myForm->setFieldValueByName('Field_1', $name);
        $execution->workflow->myForm->setFieldValueByName('Field_2', $naturalId);
        $execution->workflow->myForm->setFieldValueByName('Field_3', $phone);
        $execution->workflow->myForm->setFieldValueByName('Field_4', $address);
    }

    protected function setStage($execution){
        $execution->workflow->myForm->setFieldValueByName('Field_5', "namayandeh");

    }

}

