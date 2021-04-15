<?php

class calssName
{
    public function __construct()
    {
    }

    public function execute($wfid = "")
    {
        $db = MySQLAdapter::getInstance();
        $sql = "SELECT Field_2  FROM dm_datastoretable_26 where RowID = 1 ";
        $flagID = $db->executeScalar($sql);
        if (intval($flagID) == 2)
            return "در حال حاضر امکان ایجاد کردن فرم وجود ندارد";
        return true;
    }
}

?>


<?php class calssName
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
} ?>

