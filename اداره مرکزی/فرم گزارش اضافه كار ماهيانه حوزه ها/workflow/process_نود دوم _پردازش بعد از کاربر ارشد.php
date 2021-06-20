<?php


class calssName // do not change this line
{
    //protected $variable = null; define vars sample

    public function __construct()
    {
        // must be empty
    }

    public function execute(ezcWorkflowExecution $execution) // $execution refer to active workflow that call this class
    {
        filerecorder::recorder("process", "homaaa123");
        $execution->workflow->myForm->setFieldValueByName('Field_19', '');
        $u = $execution->workflow->myForm->getFieldValueByName('Field_0');
        $m1 = $execution->workflow->myForm->getFieldValueByName('Field_4');
        $y = $execution->workflow->myForm->getFieldValueByName('Field_3');

        $m1 = intval($m1) + 1;
        $y = intval($y) + 1399;

        switch ($m1) {
            case "1":
                $m = "فروردین";
                break;
            case "2":
                $m = "اردیبهشت";
                break;
            case "3":
                $m = "خرداد";
                break;
            case "4":
                $m = "تیر";
                break;
            case "5":
                $m = "مرداد";
                break;
            case "6":
                $m = "شهریور";
                break;
            case "7":
                $m = "مهر";
                break;
            case "8":
                $m = "آبان";
                break;
            case "9":
                $m = "آذر";
                break;
            case "10":
                $m = "دی";
                break;
            case "11":
                $m = "بهمن";
                break;
            case "12":
                $m = "اسفند";
                break;
            default:
                $m = "فروردین";
        }
        filerecorder::recorder("m: " . $m . " y:" . $y . " u:" . $u, "homaaa123");
        $dep = WFPDOAdapter::getInstance()->executeScalar('select  `Name` from `oa_depts_roles` where `RowID`=' . $u . '  limit 0,1');
        filerecorder::recorder("dep: " . $dep, "homaaa123");

        $title = 'اضافه کار ' . $m . ' ' . $y . ' ' . $dep;
        $execution->workflow->myForm->setFieldValueByName('Field_13', $title);

        filerecorder::recorder("title: " . $title, "homaaa123");
        /*
        $q="SELECT odp1.RowID as rid,us.UserID as uid  FROM  `oa_depts_roles` odp1 inner join oa_users us on odp1.UserID=us.UserID  WHERE odp1.rowtype =1 AND odp1.userid !=0 AND odp1.userid IS NOT NULL AND odp1.path  LIKE (SELECT CONCAT( path, RowID, '/')  FROM oa_depts_roles WHERE `RowID`= ".$u")";

         filerecorder::recorder("q: ".$q,"homaaa123");
        $a[0]['uid']=378;$a[0]['rid']=1229;*/

        /*
        //روال انتخاب مافوق دستي شد 950117 abdollahi
        $q="SELECT odp1.RowID as rid,us.UserID as uid  FROM  `oa_depts_roles` odp1 inner join oa_users us on odp1.UserID=us.UserID  WHERE odp1.rowtype =1 AND odp1.userid !=0 AND odp1.userid IS NOT NULL AND odp1.path  LIKE (SELECT CONCAT( path, RowID, '/')  FROM oa_depts_roles WHERE `RowID`= ".$u.")";
        $dbb = MySQLAdapter::getInstance();
        $dbb->executeSelect($q);
        $person = $dbb->fetchAssoc();
        $execution->setVariable('boss', '0' );
        if($person)
                {		$a[0]['uid']=$person['uid'];      $a[0]['rid']=$person['rid'];
                                $execution->workflow->myForm->setFieldValueByName( 'Field_8',$a);
                                $execution->setVariable('boss', '1' );
                 }
        */
//added by abdollahi 950117
        $person = $execution->workflow->myForm->getFieldValueByName('Field_8');
        if (is_array($person) && isset($person[0]))
            $execution->setVariable('boss', '1');
        else
            $execution->setVariable('boss', '0');

//-----------تغيير نام فرم---------
        $db = WFPDOAdapter::getInstance();
        $docID = $execution->workflow->myForm->instanceID;
        $hoze = $execution->workflow->myForm->getFieldValueByName('Field_20');
        $month = $execution->workflow->myForm->getFieldValueByName('Field_4');
        $year = $execution->workflow->myForm->getFieldValueByName('Field_3');
        if ($year == 0)
            $year = 1399;
        if ($year == 1)
            $year = 1400;
        if ($year == 2)
            $year = 1401;
        if ($year == 3)
            $year = 1402;
        $monthArray = array("فروردین", "اردیبهشت", "خرداد", "تیر", "مرداد", "شهریور", "مهر", "آبان", "آذر", "دی", "بهمن", "اسفند");
        $month = $monthArray[$month];
        $db = WFPDOAdapter::getInstance();
        $newTitle = $hoze . ' ' . $month . ' ' . $year;
        $sql_title = "update oa_document set Subject= CONCAT(Subject,'" . $newTitle . "'), DocDesc='" . $newTitle . "' where RowID=" . $docID . " limit 1";
        $db->execute($sql_title);
//-----------------------------------

    }
}



