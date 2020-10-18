/*نمایش پارامترهای شیفت در ماه مورد نظر*/
DECLARE @ShiftId INT = 0, --شماره شیفت
    @ShiftDate VARCHAR(10)= ''		--تاریخ ماه مورد نظر مثلا 1398/06/31

SELECT  adon.TblShiftPrm.ShiftID ,
        adon.TblShift.ShiftName ,
        adon.TblShiftPrm.PrmID ,
        adon.TblPrm.PrmName ,
        adon.IntToTime(PrmVal) AS PrmTimeVal 
FROM    adon.TblShiftPrm
        INNER JOIN adon.TblShift ON adon.TblShiftPrm.ShiftID = adon.TblShift.ShiftID
        INNER JOIN adon.TblPrm ON adon.TblShiftPrm.PrmID = adon.TblPrm.PrmID
WHERE   adon.TblShiftPrm.ShiftID = @ShiftId
        AND ShiftYear = SUBSTRING(@ShiftDate, 1, 4)
        AND ShiftMonth = SUBSTRING(@ShiftDate, 6, 2)

---------------------------------------------------------------------------------------------

/*نمایش تایم شیت پرسنل*/
EXECUTE adon.TimeSheetView @EmpID = 0, -- int
    @FDate = '', -- varchar(10)
    @TDate = '' -- varchar(10)

---------------------------------------------------------------------------------------------

/*ثبت تردد*/
EXECUTE adon.IOData_ins @CardNo = 0, -- int
    @IODate = '', -- varchar(10)
    @IOTime = 0, -- int
    @IOTypeID = 0, -- smallint
    @DeviceID = '' -- varchar(20)

---------------------------------------------------------------------------------------------

/*مشاهده ترددها*/
EXECUTE adon.IOData_sel @CardNo = 0, -- int
    @Date1 = '', -- varchar(10)
    @Date2 = '' -- varchar(10)

---------------------------------------------------------------------------------------------

/*ویرایش تردد*/
EXECUTE adon.IOData_up @CardNo = 0, -- int
    @IODate = '', -- varchar(10)
    @IOTime = 0, -- int
    @NewIOTime = 0, -- int
    @NewIOTypeID = 0, -- smallint
    @NewDeviceID = '' -- varchar(20)

---------------------------------------------------------------------------------------------

/*ثبت درخواست مرخصی و ماموریت*/
DECLARE @ReqID VARCHAR(12);
EXECUTE adon.FlowDocs_Fill @ReqID = @ReqID OUTPUT, -- varchar(12)	شماره ثبت
    @DocTyp = 0, -- tinyint				نوع سند 10 مرخصی روزانه -- 20 ماموریت روزانه -- 15 مرخصی ساعتی -- 25 ماموریت ساعتی
    @asker = 0, -- int					شماره پرسنلی درخواست کنندهvvv
    @AskDate = '', -- varchar(10)		تاریخ درخواست  1396/10/25
    @ReqCode = 0, -- int			    کد نوع تردد  104 مرخصی استحقاقی   150 ماموریت روزانه   1 مرخصی ساعتی   2 ماموریت ساعتی 
    @ReqVal = '', -- varchar(10)		البته بعدا در سیستم بصورت دقیق محاسبه می گردد
    @SDate = '', -- varchar(10)			تاریخ شروع
    @EDate = '', -- varchar(10)			تاریخ پایان
    @STime = 0, -- int					ساعت شروع
    @ETime = 0, -- int					ساعت پایان
    @city = 0, -- int					شهر	
    @DocStt = 0, -- tinyint				1 تایید شده 
    @SttDsc = '', -- varchar(100)		توضیحات سیستمی
    @ReqDsc = '', -- varchar(100)		توضیحات کاربر
    @UserLog = '', -- varchar(50)		930208T105449UW    شامل تاریخ،ساعت و اختصار کاربر
    @Flag = NULL -- bit					 مقدار یک به معنی صدور سند در صورت تداخل

---------------------------------------------------------------------------------------------

/*نمایش اطلاعات تردد*/
SELECT * FROM adon.IOList --			ویوی نمایش اطلاعات تردد (شامل کد کارت، تاریخ تردد، تعداد تردد در روز، ساعت و نوع و دستگاه ترددهای 1 تا 10). برای محدود کردن خروجی میتوان ستون ها را فیلتر کرد

---------------------------------------------------------------------------------------------

/*نمایش خلاصه وضعیت پرسنل*/
EXECUTE adon.kholaseList @EmpID = 0, -- int
    @FDate = '', -- varchar(10)
    @TDate = '' -- varchar(10)
