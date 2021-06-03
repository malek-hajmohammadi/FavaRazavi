<?php

class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution, ezcWorkflowNodeAction $caller = null)
    {
        $execution->workflow->myForm->setFieldValueByName('Field_15', '1');
        $user = $execution->workflow->myForm->getFieldValueByName('Field_0');

        $execution->workflow->myForm->setFieldValueByName('Field_11', $user);
        $execution->setVariable('haram', 0);

        $execution->setVariable('bazrasi', 0);

        $rid = $user[0]['rid'];
        $path = WFPDOAdapter::getInstance()->executeScalar("SELECT path FROM oa_depts_roles WHERE RowID = $rid");
        if (strpos($path, '/3464/') !== false) {
            $execution->setVariable('bazrasi', '1');
        } else {
            $execution->setVariable('bazrasi', '0');
        }
        $uid = $user[0]['uid'];
        $employeeID = WFPDOAdapter::getInstance()->executeScalar("SELECT employeeID FROM oa_users WHERE UserID=$uid LIMIT 1");
        if (strlen($employeeID) > 0) {
            $execution->workflow->myForm->setFieldValueByName('Field_6', $employeeID);
        }
        $vtype = $execution->workflow->myForm->getFieldValueByName('Field_5');
        $name = WFPDOAdapter::getInstance()->executeScalar("SELECT CONCAT(fname, ' ', lname) FROM oa_users WHERE UserID=$uid LIMIT 1");
        $gen = intval(WFPDOAdapter::getInstance()->executeScalar("SELECT sex FROM oa_users WHERE UserID=$uid LIMIT 1"));

        //changed by abdollahi 941220
        //$vtypeconf = array( '10' =>'استراحت هفتگي كاركنان شيفتي','1' => 'استحقاقي', '2' => 'استعلاجي','3' => 'بدون حقوق','4' => 'اضطراري','5' => 'تحصيلي','6' => 'ورزشي','7' => 'خارج از كشور');
        //$vtypeconf[20]='استعلاجي بدون حقوق';

        $moavenat = Chart::getTopRoleByLevel($user[0]['rid'], 20);
        if ($moavenat && isset($moavenat['RID'])) {
            $moavenat = array(array('uid' => $moavenat['UID'], 'rid' => $moavenat['RID']));
            $execution->workflow->myForm->setFieldValueByName('Field_28', $moavenat);
        } else {
            for ($index = 29; $index > 15; $index--) {
                $moavenat = Chart::getTopRoleByLevel($user[0]['rid'], $index);
                if ($moavenat && isset($moavenat['RID'])) {
                    $moavenat = array(array('uid' => $moavenat['UID'], 'rid' => $moavenat['RID']));
                    $execution->workflow->myForm->setFieldValueByName('Field_28', $moavenat);
                    break;
                }
            }
        }

        $vtypeconf = array(
            '1'  => 'استحقاقي',
            '2'  => 'استعلاجي',
            '3'  => 'بدون حقوق',
            '4'  => 'اضطراري',
            '5'  => 'تحصيلي',
            '6'  => 'ورزشي',
            '7'  => 'خارج از كشور',
            '9'  => 'چكاپ',
            '10' => 'استراحت هفتگي كاركنان شيفتي',
            '20' => 'استعلاجي بدون حقوق',
            '21' => 'استعلاجي کرونا',
            '31' => 'تولد فرزند');
        $info = 'درخواست مرخصی ' . $vtypeconf[$vtype];
        if ($gen == 1) {
            $info = $info . ' آقای ';
        } else {
            $info = $info . ' خانم ';
        }
        $info = $info . $name;
        WFPDOAdapter::getInstance()->execute("UPDATE oa_document SET Subject = '$info' WHERE RowID = " . $execution->workflow->myForm->instanceID);

        $this->checkCoronaForHaram($execution,$caller);
    }
    //Hajmohammadi 1400/03/05//
    private function checkCoronaForHaram(ezcWorkflowExecution $execution,ezcWorkflowNodeAction $caller){
        $result=0;


        $user = $execution->workflow->myForm->getFieldValueByName('Field_0');
        $rid = $user[0]['rid'];
        $path = WFPDOAdapter::getInstance()->executeScalar("SELECT path FROM oa_depts_roles WHERE RowID = $rid");
        if (strpos($path, '/9817/')){
            $vtype = $execution->workflow->myForm->getFieldValueByName('Field_5');
            if(intval($vtype) == 50) {
                $result=1;
            }
        }

        if($result==1){
            $execution->setVariable('coronaForHarmFlag', 1);
        }else{
            $execution->setVariable('coronaForHarmFlag', 0);
        }


    }
}

