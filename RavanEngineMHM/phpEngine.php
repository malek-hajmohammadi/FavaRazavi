<?php

/*کد خالی برای نود پردازش و همچنین کد نویسی کارتابل در گردشکار*/

class calssName
{
    public function __construct()
    {
    }

    public function execute(ezcWorkflowExecution $execution)
    {
        /*Insert Code Here*/
    }
}

/*درست کردن فیلد تکمیل شوند برای حوزه*/
function template_completion()
{
    /*اوناییکه تایپشون 1 هست حوزه هست و بقیه سمت*/
    /*باید یک سمت یا حوزه رو داشته باشیم و در زیر مجموعه آن این کوئری رو داشته باشیم*/
    /*rid*/
    /*در تابع ajax می نویسیم و در فیلد تکمیل شونده فراخوانی می کنیم*/
    /*برای داشتن دپارتمان ها RowType=2 و برای پرسنل آن 1 بگذارم*/
    /////////////////////////////////////////////////////
    $db = MySQLAdapter::getInstance();
    $parent = Request::getInstance()->varCleanFromInput('parent');
    $pathsql = ' ';
    if (!empty($parent)) {
        $pathsql = ' and path LIKE "%/' . $parent . '/%" ';
    }

    $sql = 'SELECT RowID,Name  FROM oa_depts_roles where RowType =2 and IsEnable=1';
    $sql .= $pathsql;
    $db->executeSelect($sql);
    while ($row = $db->fetchAssoc()) {
        if (!empty($row['RowID']))
            $res[] = array($row['RowID'], $row['Name']);
    }
    Response::getInstance()->response = $res;
////////////////////////////////////////////////////////

}

/*ساخت تابع آیجکس خالی*/
/*وقتی با آیجکس چیزی رو می فرستیم خودش json رو تبدیل به object می کنه*/
function template_ajax()
{

    /*--------------------------------------*/

    if (Request::getInstance()->varCleanFromInput('placeHolder'))
        $varTemp = Request::getInstance()->varCleanFromInput('placeHolder');
    else {

        Response::getInstance()->response = "There is no specefic input";
        return;
    }


    /*--------------------------------------*/

}
/*استفاده از PDO*/
function template_pdo(){
    $db = PDOAdapter::getInstance();
    $sql="SELECT * FROM `dm_datastoretable_1098` WHERE `DocID`=:docId";
    $PDOParams=array(
        array('name'=>'docId','value'=>$docId,'type'=>PDO::PARAM_STR)
    );
    $db->executeSelect($sql,$PDOParams);

    $count = 0;
    $arrayList=[];
    while ($row = $db->fetchAssoc()) {
       $arrayList[$count]= $row['Field_2'];


        $count++;
    }



}





