<?php



$docID = Request::getInstance()->varCleanFromInput('docID');

if(!$docID || intval($docID) == 0){
    Response::getInstance()->response= 'شماره فرم نامعتبر ميباشد';
    return false;
}

$acm = AccessControlManager::getInstance();

$db = MySQLAdapter::getInstance();
$sql = "SELECT dm.*,oa_document.CreatorUserID,oa_document.CreatorRoleID 
        FROM `dm_datastoretable_873` dm
        INNER JOIN oa_document on(oa_document.RowID = dm.DocID)
        where DocID = $docID";
$db->executeSelect($sql);

$res = 'شماره فرم نامعتبر ميباشد';
if($formInfo = $db->fetchAssoc()){
    $userID = $formInfo['CreatorUserID'];
    $roleID = $formInfo['CreatorRoleID'];

    $currentFormDate = $formInfo['Field_1']; //تاریخ پیشنهادی در فرم درخواست دعوتنامه مهمانسرا 873//
    $res = 'true';

    if($userID == 1662)
        $res = 'true';
    //1051: دسترسی دعوتنامه مهمانسرا//
    //Field_0:کدسمت//
    //Field_1:ابتدای بازه//
    //Field_2:انتهای بازه//
    //چک می شود که آیا تاریخ پیشنهادی در بین بازه های ابتدا و انتهای سهمیه هست یا نه//
    else{
        $sql = "SELECT * from  dm_datastoretable_1051 where Field_0 = $roleID and '$currentFormDate' >= Field_1 and '$currentFormDate' <= Field_2";
        $db->executeSelect($sql);
        if($periodInfo = $db->fetchAssoc()){
            $periodStartDate = $periodInfo['Field_1'];
            $periodEndDate = $periodInfo['Field_2'];
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
            $sql = "SELECT sum(row_count) from (
                      SELECT count(child.DocID) as row_count
                      FROM `dm_datastoretable_873` dm
                          INNER JOIN oa_document parentDocument on(parentDocument.RowID = dm.DocID and parentDocument.IsEnable = 1)
                         
                          INNER JOIN wf_execution on(wf_execution.execution_doc_id = dm.DocID AND wf_execution.is_enable = 1)
                          
                          INNER JOIN `dm_datastoretable_875` child on(child.MasterID = dm.DocID)
                          
                          INNER JOIN oa_document childDocument on(childDocument.RowID = child.DocID and childDocument.IsEnable = 1)
                      
                      where parentDocument.CreatorRoleID = $roleID 
                      
                      and dm.Field_1 >= '$periodStartDate' and dm.Field_1 <= '$periodEndDate'
                      
                      and (dm.Field_12 > 2 OR dm.DocID = $docID)
                      group by dm.DocID) as forms";

            $periodOrdersCount = $db->executeScalar($sql);

            $date1 = new DateTime($periodStartDate);
            $date2 = new DateTime($periodEndDate);
            $interval = $date1->diff($date2);
            $daysCount = intval($interval->format('%R%a'));
            $maxOrderCount = ($daysCount+1) * $periodInfo['Field_3'];

            if($periodOrdersCount > $maxOrderCount)
                $res = 'تعداد درخواست بيشتر از باقي مانده سهميه شما ميباشد(كل سهميه شما '.$maxOrderCount.' نفر)'.'(تعداد استفاده شده به همراه فرم فعلي: '.$periodOrdersCount.' نفر)';
            else
                $res = 'true';
        }
        else {
            $sql = "SELECT * 
                    from  dm_datastoretable_1051 
                    where Field_0 = $roleID 
                    and (Field_1 = '0000-00-00' 
                        or Field_2 = '0000-00-00' 
                        or Field_1 is null 
                        or Field_2 is null 
                        or Field_1 = '' 
                        or Field_2 = '')
                    ";
            $db->executeSelect($sql);
            if ($accessInfo = $db->fetchAssoc()) {


                $maxOrderCount = intval($accessInfo['Field_3']) * 30;
                if(intval($nowDate[1]) < 7)
                    $maxOrderCount = intval($accessInfo['Field_3']) * 31;

                $nowDate = date('Y-m-d');
                $nowDate = Date::GregToJalali($nowDate);
                $nowDate = explode('/', $nowDate);
                $nowDate[2] = '01';
                $periodStartDate = implode('/', $nowDate);
                $periodStartDate = Date::JalaliToGreg($periodStartDate);

                if(intval($nowDate[1]) == 12){
                    $nowDate[1] = '01';
                    $nowDate[0] = intval($nowDate[0])+1;
                }
                else{
                    $nowDate[1] = intval($nowDate[1])+1;
                }
                $periodEndDate = implode('/', $nowDate);
                $periodEndDate = Date::JalaliToGreg($periodEndDate);

                $sql = "SELECT sum(row_count) from (
                          SELECT count(child.DocID) as row_count
                          FROM `dm_datastoretable_873` dm
                              INNER JOIN oa_document parentDocument on(parentDocument.RowID = dm.DocID and parentDocument.IsEnable = 1)
                              INNER JOIN wf_execution on(wf_execution.execution_doc_id = dm.DocID AND wf_execution.is_enable = 1)
                              INNER JOIN `dm_datastoretable_875` child on(child.MasterID = dm.DocID)
                              INNER JOIN oa_document childDocument on(childDocument.RowID = child.DocID and childDocument.IsEnable = 1)
                          where parentDocument.CreatorRoleID = $roleID 
                          and dm.Field_1 >= '$periodStartDate' and dm.Field_1 < '$periodEndDate'
                          and (dm.Field_12 > 2 OR dm.DocID = $docID)
                          group by dm.DocID) as forms";
                $count = $db->executeScalar($sql);
                if ($count > $maxOrderCount)
                    $res = 'تعداد درخواست بيشتر از حد مجاز براي اين تاريخ ميباشد(تعداد مجاز در اين ماه ' . $maxOrderCount . ' نفر)';
                else
                    $res = 'true';
            }
            else
                $res = 'شما مجاز به ارسال اين فرم نميباشيد';
        }
    }

}
else{
    Response::getInstance()->response= 'شماره فرم نامعتبر ميباشد';
    return false;
}


Response::getInstance()->response= $res;
