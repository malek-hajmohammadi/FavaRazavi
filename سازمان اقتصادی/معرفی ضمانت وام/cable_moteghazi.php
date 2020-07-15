<?php


class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {

        //گذاشتن کاربر شروع کننده در متغییرهای گردش کار به جهت استفاده در رونوشت آخر کار//
        $acm = AccessControlManager::getInstance();
        $uid = $acm->getUserID();
        $rid = $acm->getRoleID();

        $execution->setVariable('uId', $uid);
        $execution->setVariable('rId', $rid);
        //ساخت اسم متقاضی و گذاشتن در متغییر مربوطه//
        $name = MySQLAdapter::getInstance()->executeScalar("SELECT CONCAT(fname, ' ', lname) FROM oa_users WHERE UserID=$uid LIMIT 1");
        $gen = intval(MySQLAdapter::getInstance()->executeScalar("SELECT sex FROM oa_users WHERE UserID=$uid LIMIT 1"));

        $subject = 'درخواست معرفی نامه ';
        $fullName='';
        if($gen == 1){
            $fullName = $fullName . ' آقای ';
        }else{
            $fullName = $fullName . ' خانم ';
        }
        $fullName.=$name;
        $subject = $subject . $fullName;
        MySQLAdapter::getInstance()->execute("UPDATE oa_document SET Subject = '$subject' WHERE RowID = " . $execution->workflow->myForm->instanceID);

        $execution->workflow->myForm->setFieldValueByName( 'Field_1',$fullName);

        ///////////////////////////////////////








        $execution->workflow->myForm->setFieldValueByName( 'Field_8', "moteghazi");


    }

}

