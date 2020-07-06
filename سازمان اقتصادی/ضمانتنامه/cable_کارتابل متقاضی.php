<?php
class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {


        $rid = AccessControlManager::getInstance()->getRoleID();
        $db = MySQLAdapter::getInstance();
        $docID = $execution->workflow->myForm->instanceID;

        /*

        2966: شرکت صنایع پیشرفته رضوی
        شرکت کسب و کار رضوی:2532
        شرکت مدیریت زنجیره تامین رضوی:2481
        شرکت معادن خاور: 2965
        موسسه تامین و پشتیبانی رضوی : 2809
        موسسه کیفیت رضوی:2486
        هتل قصرالضیافه:2721
        شرکت سامان بازار رضوی :2528
        شرکت تعاونی مسکن سازمان اقتصادی رضوی:2274
        موسسه منطقه ویژه اقتصادی سرخس:1537
        شرکت فن آوری اطلاعات و ارتباطات رضوی:1520
        کمباین سازی ایران:1462
        شرکت تهیه و تولید فرش آستان قدس رضوی:1390
        شرکت نخریسی و نساجی خسروی خراسان : 1407
        شرکت شهاب خودرو :1458
        شهاب یار:1475
        شرکت معادن قدس رضوی:1443

        داروسازی ثامن:1405
        کشاورزی رضوی: 2845
        سامان دارو هشتم:2253
        صنایع غذایی رضوی:1380
        دامپروری صنعنتی قدس:1404
        یاراطب ثامن:2251
        مسکن و عمران قدس رضوی:1476
        موسسه عمران و توسعه رضوی: 2537
        خمیرمایه رضوی:1406
        داروسازی شیراز سرم:2557
        آب و خاک قدس رضوی:1411
        کارگزاری رضوی:1538
        قند تربت حیدریه:1453
        قند چناران:1454
        فرآورده های لبنی رضوی:1409
        مهندسان مشاور:1994
        بتن و ماشین قدس :1410
        شرکت فن آوری و نوآوری:2707
        بذر و نهال رضوی:2701
        صنایع خلاق رضوی:2651
        آرایه های معماری رضوی:1389
        دارو پژوه سامان:3100
        شرکت نفت و گاز رضوی:1644
        شرکت بنای سبک قدس:1384
        شرکت احداث و نگهداری آزادراه:2348
        سیمان سنگان:2124



         */

        $depts = array(1848,2966,2528,2532,2481,2965,2809,2486,2721,2274,1537,1520,1462,1390,1407,1458,1475,1443,
            1405,2845,2253,1380,1404,2251,1476,2537,1406,2557,1411,1538,1453,1454,1409,1994,1410,2707,2701,2651,1389,3100,1644,1384,2348,2124);
        $selectedDept = 0;
        $sql = "select path 
                        from oa_depts_roles
                        WHERE RowID = $rid
                    ";
        $path = $db->executeScalar($sql);
        foreach ($depts as $deptID) {

            if (strpos($path, "/$deptID/") > 0) {

                $selectedDept = $deptID;
                break;
            }
        }




        //گذاشتن نام شرکت در موضوع فرم//
        if ($selectedDept) {
            $sql = "select Name 
                        from oa_depts_roles
                        WHERE RowID = $selectedDept";
            $name = $db->executeScalar($sql);
            $dept = ' ' . $name;
            $execution->workflow->myForm->setFieldValueByName('Field_0', $name);
            $execution->workflow->myForm->setFieldValueByName('Field_33', $selectedDept);

            $sql = "update oa_document set Subject = concat(Subject,'$dept') where RowID = $docID";
            $db->execute($sql);
        }



        $execution->setVariable('companyName', $dept);

        $execution->workflow->myForm->setFieldValueByName( 'Field_32', 0);

    }
}


?>


