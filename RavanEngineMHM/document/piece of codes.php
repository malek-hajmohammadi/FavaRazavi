<?php
// متغیر گردش کار
$actors = $execution->getVariable('actors');
$execution->setVariable('actors',8);//همینطوری مقدار گذاشتم


//خالی آیجکس//
class MainAjax
{

    public function main()
    {
        $output = "";
        $this->a();
        return $output;

    }

    private function a()
    {
        $a = "malek";
    }


}

$mainAjax = new MainAjax();
Response::getInstance()->response = $mainAjax->main();
return $mainAjax;

/////////

//آیجکس با گرفتن ورودی و چک کردن آن//
class MainAjax
{

    public function main()
    {
        $this->getInputArguments();

    }

    private function getInputArguments()
    {
        if (Request::getInstance()->varCleanFromInput('searchFields')) {
            $this->searchFields = Request::getInstance()->varCleanFromInput('searchFields');
            $this->searchFields = json_decode($this->searchFields,false);
        }
        else {

            Response::getInstance()->response = "There is no specefic input";
            return;
        }
    }


}

$mainAjax = new MainAjax();
Response::getInstance()->response = $mainAjax->main();
return $mainAjax;


//در در ایونت workflow برای اجرا قبل از هرچیز //
class calssName
{
    public function __construct()
    {
    }

    public function execute($wfid = "")
    {
        $db = MySQLAdapter::getInstance();
        $acm = AccessControlManager::getInstance();
        $uid = $acm->getUserID();
        $sql = "SELECT dm.DocID   FROM dm_datastoretable_964 dm inner join oa_document on (dm.docid=oa_document.rowid) inner join vi_form_userrole userrole ON(dm.DocID = userrole.docID AND userrole.FieldName = 'Field_24')  WHERE userrole.uid = $uid AND dm.Field_25 > 3 and oa_document.isenable=1 ";
        $formID = $db->executeScalar($sql);
        if (intval($formID) <= 0) return "همکار ابتدا می بایست نسبت به تکمیل فرم اطلاعات هویتی اقدام فرمایید";
        return true;
    }
}
