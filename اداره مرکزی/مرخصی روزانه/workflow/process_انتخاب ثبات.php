<?php


class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution, ezcWorkflowNodeAction $caller = null)
    {
        $docID = $execution->workflow->myForm->instanceID;

        //$rid =WFPDOAdapter::getInstance()->executeScalar("SELECT CreatorRoleID FROM oa_document WHERE RowID = $docID");
        //$rid = AccessControlManager::getInstance()->getRoleID();
        $person = $execution->workflow->myForm->getFieldValueByName('Field_0');
        $rid = $person[0]['rid'];
        $path = WFPDOAdapter::getInstance()->executeScalar("SELECT path FROM oa_depts_roles WHERE RowID = $rid");
        $mainSecRid = '874';
        $mainSecUid = '2152';
        $arr = array(
            //sharbatdar 990401---------------
            '/3472/'  => array('uid' => '1042', 'rid' => '1890'), //۴۶۹۲۲۰-۹۸ نذورات خاموشی نامه
            '/9533/'  => array('uid' => '3231', 'rid' => '9614'), //۴۶۹۲۲۰-۹۸معاونت ارتباطات و رسانه مجید نژادوفا
            '/10707/' => array('uid' => '640', 'rid' => '11780'), //۴۶۹۲۲۰-۹۸ یگان حفاظت رضا دلبری
            //sharbatdar 990401---------------
            //sharbatdar 990721---------------
            '/12296/' => array('uid' => '9544', 'rid' => '12339'), //۹۸۷۷۹- معاونت سرمایه انسانی سیدی 99
            // '/8845/' => array('uid' => '5087', 'rid' => '6492'), //۹۸۷۷۹-99بنیاد کرامت هاشم پناه
            '/9533/'  => array('uid' => '3569', 'rid' => '9779'), //991206 از دارابی بشود هوشمند
            //sharbatdar 990721---------------
//sharbatdar-991020
            '/8845/'  => array('uid' => '3231', 'rid' => '4897'), //بنیاد کرامت نژادوفا فرم13690294-99
//sharbatdar-991020
            '/3458/'  => array('uid' => '1881', 'rid' => '2074'), // مديريت آموزش يعقوبي
            '/3473/'  => array('uid' => '380', 'rid' => '883'), // هسته گزينش مركزي جعفري
            '/3464/'  => array('uid' => $mainSecUid, 'rid' => $mainSecRid), // حراست باقي زاده
            // '/3472/'=> array('uid' => $mainSecUid, 'rid' => $mainSecRid), // نذورات باقي زاده
            '/3478/'  => array('uid' => $mainSecUid, 'rid' => $mainSecRid), // بازرسي باقي زاده
            '/3444/'  => array('uid' => '945', 'rid' => '850'), // شوراي عالي مسلمان
            '/3445/'  => array('uid' => $mainSecUid, 'rid' => $mainSecRid), // دفتر ويژه باقي زاده
            '/3437/'  => array('uid' => $mainSecUid, 'rid' => $mainSecRid), //مشاورين باقي زاده
            '/3433/'  => array('uid' => $mainSecUid, 'rid' => $mainSecRid), // دفاتر ستادي باقي زاده
            '/3537/'  => array('uid' => '899', 'rid' => '1723'), // موقوفات احمدي مقدم
            // '/3536/' => array('uid' => '2057', 'rid' => '916'), // حقوقي یعقوبی کلاغ آباد
            '/3440/'  => array('uid' => '3048', 'rid' => '1556'), //حقوقی محمدعلی غلامی ناوخ فرم12213507-99

            //abdollahi 980819-------------------
            // حسب درخواست آقاي صباغيان در معاونت حقوقي بجاي ايشان خانم احتشام ثبات شدند
            /*'/3440/' => array('uid' => '2376', 'rid' => '4768'), //معاونت امور حقوقي وموقوفات آستان قدس رضوي فاطمه مظفريان مقدم*/
            //فرم -sharbatdar
            '/3439/'  => array('uid' => '689', 'rid' => '3047'), //املاك و اراضي باوندی
            '/3567/'  => array('uid' => '689', 'rid' => '3047'), //املاك و اراضي باوندی
//sharbatdar
            //        '/3439/' => array('uid' => '1308', 'rid' => '4820'), //املاك و اراضي باقري
            //        '/3567/' => array('uid' => '1308', 'rid' => '4820'), //املاك و اراضي باقري
            '/3442/'  => array('uid' => '1881', 'rid' => '2074'), //پشتيباني يعقوبي
            '/9816/'  => array('uid' => '4052', 'rid' => '5708'),// سازمان حرم مطهر رضوي مجيد وفائي جهان
            //'/3438/' => array('uid' => '870', 'rid' => '1690'), //اماكن حامد فارمدي Borsipour 95/12/21 نامه 459363
            //'/3438/' => array('uid' => '4052', 'rid' => '5708'), //اماكن مجيد وفائي جهان Borsipour 95/12/21 نامه 459363
            '/3443/'  => array('uid' => '3827', 'rid' => '2886'), //فني روحبخش
            //'/3441/' => array('uid' => '972', 'rid' => '1759'), //تبليغات افخمي
            //'/3441/' => array('uid' => '4052', 'rid' => '5708'), //تبليغات مجيد وفائي جهان Akhavan 97/11/11 نامه 467330
            '/3467/'  => array('uid' => $mainSecUid, 'rid' => $mainSecRid), //دبيرخامه كل باقي زاده
            '/3556/'  => array('uid' => '1238', 'rid' => '1304'), //نقليه رحماني
//حسب نامه 414953 معاونت امداد افزوده شد 951127
            '/6488/'  => array('uid' => '3557', 'rid' => '6492'), //معاونت امداد _ اسماعيل ناظري
            '/6290/'  => array('uid' => '4811', 'rid' => '6300'), //تستي تستي
            //     '/9533/' => array('uid' => '2105', 'rid' => '3038'), //معاونت ارتباطات و رسانه محمود نجفي

            //'/9725/'  => array('uid' => '3297', 'rid' => '1327'), //معاونت برنامه و بودجه مهدی ماهوان akhavan Form ICT 8731148
            '/9725/'  => array('uid' => '64', 'rid' => '1232'), //معاونت برنامه و بودجه عبدالله نظري akhavan Form ICT 8731148

            '/8660/'  => array('uid' => '1185', 'rid' => '2163'),// مركز فناوري اطلاعات و فضاي مجازي مرتضي يزدي
            //'/9856/' => array('uid' => '972', 'rid' => '1759'), //مديريت برنامه و بودجه سازمان حرم مطهّر رضوي افخمي
            //'/9847/' => array('uid' => '972', 'rid' => '1759'), //مديريت پشتيباني سازمان حرم مطهّر رضوي افخمي
            '/3629/'  => array('uid' => '5083', 'rid' => '6475'), //مديريت املاك و اراضي سرخس هادي شايق
            '/8674/'  => array('uid' => '332', 'rid' => '9848') //معاونت علمي آستان قدس رضوي جواد ديانتي نيت
        );

        if ((strpos($path, '/3438/') !== false) || (strpos($path, '/3441/') !== false) || (strpos($path, '/3472/') !== false) || (strpos($path, '/3466/') !== false)) {
            $execution->setVariable('haram', '1');
        } else {
            $execution->setVariable('haram', '0');
        }

        /* add by mohammadzadeh for fava users */
        $person = $execution->workflow->myForm->getFieldValueByName('Field_0');
        $rid = $person[0]['rid'];
        $acm = AccessControlManager::getInstance();
        $gid = $acm->getUserGroupID($rid);
        $gids = explode(',', $gid);
        $execution->setVariable('favaEmp', '0');
        if (in_array(25, $gids)) {
            $execution->workflow->myForm->setFieldValueByName('Field_11', array(array('uid' => '1185', 'rid' => '2163'))); // مرتضي يزدي
            $execution->setVariable('favaEmp', '1');
            return true;
        }

        /* انتخاب ثبات پرسنل امريه */
        if (in_array(35, $gids)) {
            $execution->workflow->myForm->setFieldValueByName('Field_11', array(array('uid' => '3165', 'rid' => '1990'))); //  مصطفی گلپور - فرم 13235850-15-05-1399
            return true;
        }
        /*     if(in_array(35, $gids)){
                 $execution->workflow->myForm->setFieldValueByName( 'Field_11', array( array('uid' => '4509', 'rid' => '1273') ) ); //  جواد فاني ديسفاني
                 return true;
             }*/

        $vtype = $execution->workflow->myForm->getFieldValueByName('Field_5');
        $vlen = $execution->workflow->myForm->getFieldValueByName('Field_4');
        /* add type 32 by mohammadzadeh */
        /* add type 4 by mohammadzadeh */
        /* add type 33 by mohammadzadeh */
        if (intval($vtype) == 20 || intval($vtype) == 31 || intval($vtype) == 5 || intval($vtype) == 32 || intval($vtype) == 4 || intval($vtype) == 33 || intval($vtype) == 50 || intval($vtype) == 21) {
            //changed by abdollahi 950530
            //          $execution->workflow->myForm->setFieldValueByName( 'Field_11', array( array('uid' => '9070', 'rid' => //'1866') ) ); // محمدعلی رضائی
            $execution->workflow->myForm->setFieldValueByName('Field_11', array(array('uid' => '1087', 'rid' => '1962'))); // محمد صفایی-فرم13522851 سال 99-change by sharbatdar
            if (intval($vtype) == 50)//990106 added by abdollahi
                $execution->workflow->myForm->setFieldValueByName('Field_11', array(array('uid' => '1024', 'rid' => '1270'))); //مسلم فدایی

            if (intval($vtype) == 21)//990929 added by akhavan
                $execution->workflow->myForm->setFieldValueByName('Field_11', array(array('uid' => '1087', 'rid' => '1962'))); //صفایی
            return true;
        }

        $execution->setVariable('specRegister', '0');
        foreach ($arr as $key => $value) {
            if (strpos($path, $key) !== false) {
                $execution->workflow->myForm->setFieldValueByName('Field_11', array($value));

                // add by mohammadzadeh for irajZad
                if ($value['rid'] == $mainSecRid)
                    $execution->setVariable('specRegister', '1');

                return true;
            }
        }
        $execution->workflow->myForm->setFieldValueByName('Field_11', array(array('uid' => $mainSecUid, 'rid' => $mainSecRid)));
        $execution->setVariable('specRegister', '1');
        return true;

    }
}



