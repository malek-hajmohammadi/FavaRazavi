<?php


//-----------------------parameters----------------------//
$reqType = 0; //0:usual 1:Tashrifat
$roleId = 9; //سمت//
$reqDate='1398/11/08';
$deptId=0;
//نوع سهمیه انتخابی//
if (Request::getInstance()->varCleanFromInput('reqType'))
    $reqType = Request::getInstance()->varCleanFromInput('reqType');
//حوزه ای که این سهمیه رو انتخاب کرده//
if (Request::getInstance()->varCleanFromInput('roleId'))
    $roleId = Request::getInstance()->varCleanFromInput('roleId');

if (Request::getInstance()->varCleanFromInput('reqDate'))
    $reqDate = Request::getInstance()->varCleanFromInput('reqDate');

if (Request::getInstance()->varCleanFromInput('deptId'))
    $deptId = Request::getInstance()->varCleanFromInput('deptId');


//------------------end  parameters-------------------------//
$db = MySQLAdapter::getInstance();

$sql = "select DATEDIFF(Field_2,Field_1) * Field_3 as sumUsual,
               DATEDIFF(Field_2,Field_1) * Field_4 as sumTashrifat 
        from dm_datastoretable_1104
        where Field_0='2216' AND Field_1 <= '2020-02-25' AND Field_2 >= '2020-02-25'";
$db->executeSelect($sql);

$quota=0;

if($info = $db->fetchAssoc()){


    if($reqType==0)//usual
        $quota=$info['sumUsual'];
    else
        $quota=$info['sumTashrifat'];
}
Response::getInstance()->response=$quota;


return;



//
//        $countUse = $db->executeScalar($sql);



//چک کردن در فرم سهمیه که آیا طرف سهمیه دارد یا نه//


/**
 * می خواهیم تعداد نامه هایی که این سمت در تاریخ سهمیه اش زده البته تعداد
 * لیست مهمانهاش در هر نامه البته در فرم قدیم که محمدزاده نوشته
 * رو بگیریم
 */
//---------------------begin------------------------------//
//me///////////////////

//اولین جوین برای اینکه چک کنیم که نامه حذف نشده باشد//
//دومین جوین برای اینکه چک کنیم گردش کار این فرم حذف نشده باشد//
//875: فرم جزء لیست مهمانان است//
//سومین جوین جدول اسامی مهمانان رو به نامه جوین می کند//
//آخرین جوین برای این که مهمانان حذف شده نباشند//
//شرط کوئری رو برای یک آی دی سمت فیلتر می کند//
//شرط دوم برای نامه هایی که در بازه تاریخ سهمیه می خواهند دعوتنامه بگیرند یا گرفتند//
//شرط سوم میگه یا نامه هایی که از مرحله دوم گذشته یا نامه جاری باشد//
//هر فیلد آماری در کوئری باید یک گروه بندی داشته باشد//

//------چیزی که الان نوشتم چه کار می کنه---//
//1051: دسترسی دعوتنامه مهمانسرا//
//Field_0:کدسمت//
//Field_1:ابتدای بازه//
//Field_2:انتهای بازه//
//چک می شود که آیا تاریخ پیشنهادی در بین بازه های ابتدا و انتهای سهمیه هست یا نه//

        //حالا اگر هست می خواهیم تعدادفرمهایی که زده رو بشماریم//
        //اولین جوین برای اینکه چک کنیم که نامه حذف نشده باشد//
        //دومین جوین برای اینکه چک کنیم گردش کار این فرم حذف نشده باشد//
        //875: فرم جزء لیست مهمانان است//
        //سومین جوین جدول اسامی مهمانان رو به نامه جوین می کند//
        //آخرین جوین برای این که مهمانان حذف شده نباشند//
        //شرط کوئری رو برای یک آی دی سمت فیلتر می کند//
        //شرط دوم برای نامه هایی که در بازه تاریخ سهمیه می خواهند دعوتنامه بگیرند یا گرفتند//
        //شرط سوم میگه یا نامه هایی که از مرحله دوم گذشته یا نامه جاری باشد//
        //هر فیلد آماری در کوئری باید یک گروه بندی داشته باشد//
// فرم درخواست دعوتنامه مهمانسرا 873//
//$db = MySQLAdapter::getInstance(); // برای اجرای کوئری ها//
//$sql = "SELECT sum(row_count) from (
//                      SELECT count(child.DocID) as row_count
//                      FROM `dm_datastoretable_873` dm
//                          INNER JOIN oa_document parentDocument on(parentDocument.RowID = dm.DocID and parentDocument.IsEnable = 1)
//
//                          INNER JOIN wf_execution on(wf_execution.execution_doc_id = dm.DocID AND wf_execution.is_enable = 1)
//
//                          INNER JOIN `dm_datastoretable_875` child on(child.MasterID = dm.DocID)
//
//                          INNER JOIN oa_document childDocument on(childDocument.RowID = child.DocID and childDocument.IsEnable = 1)
//
//                      where parentDocument.CreatorRoleID = $roleID
//
//                      and (dm.Field_12 > 2 )
//                      group by dm.DocID) as forms";
//
//
//
//        $countUse = $db->executeScalar($sql);
//---------------------------------------------------//







//---------------------end------------------------------//
//Response::getInstance()->response = $kindOfQuota . $department;
//Response::getInstance()->response = $countUse;
$resut=0;
if ($reqType==0)//سهمیه عادی//
    $resut=50;
else if($reqType==1)//سهمیه تشریفات//
    $resut=70;
Response::getInstance()->response=$resut;

