
-- Table structure for table `dm_field_type` = ليست نوع فيلدهاي قايل استقاده در فرم ها
--

CREATE TABLE IF NOT EXISTS `dm_field_type` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT ,
  `Name` varchar(200) COLLATE utf8_persian_ci NOT NULL COMMENT 'عنوان نوع فيلد',
  `JSControlName` varchar(100) COLLATE utf8_persian_ci NOT NULL COMMENT 'نام شي جاوا اسكريپت',
  `DBFieldType` varchar(50) COLLATE utf8_persian_ci NOT NULL COMMENT 'نوع فيلد بانك اطلاعاتي',
  `MakeList` int(1) NOT NULL DEFAULT '0' COMMENT 'جهت بررسی',
  `Mode` int(1) NOT NULL DEFAULT '0' COMMENT 'جهت بررسی',
  PRIMARY KEY (`RowID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci COMMENT='ليست نوع فيلدهاي قايل استقاده در فرم ها' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dm_permission` = دسترسی های اعمال شده بر روی فرمها ، فیلدهای فرمها و گزارشات

CREATE TABLE IF NOT EXISTS `dm_permission` (
  `RowID` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'primary key',
  `StructID` int(10) unsigned DEFAULT NULL COMMENT 'کد مشخصه فرم => dm_structure.RowID',
  `FieldID` int(10) unsigned DEFAULT NULL COMMENT 'کد مشخصه فيلد => dm_structure_field.RowID',
  `RepID` int(11) DEFAULT NULL COMMENT 'کد مشخصه گزارش => rg_repproperties.RowID',
  `AccessType` int(1) unsigned DEFAULT NULL COMMENT '
  0 = سمت 
  1 = گروه کاربری 
  2 = گردش کار
  3 = واحد سازمانی
  ',
  `AccessID` int(10) unsigned DEFAULT NULL COMMENT '
  IF AccessType=0 THEN AccessID=>oa_depts_roles.RowID
  IF AccessType=1 THEN AccessID=>oa_user_group.RowID
  IF AccessType=2 THEN AccessID=>wf_workflow.RowID
  IF AccessType=3 THEN AccessID=>oa_depts_roles.DeptID 
  ',
  `ActionType` int(1) unsigned DEFAULT NULL COMMENT '
  نوع دسترسي 
  0 view, 1 insert, 2 edit, 3 delete, 4 confirm, 5 archive, 10 form manager, 11 addrow for master-detail, 12 editRow for master-detail, 13 deleteRow for masterdetail, 14 search and result search, 15 show link in menu',
  `ActionID` tinyint(1) DEFAULT NULL COMMENT '0 none, 1 have, 2 not have',
  `Conditions` text COLLATE utf8_persian_ci COMMENT 'شرطي كه در صورت برقرار بودن، اين مجوز به صاحب دسترسی داده ميشود',
  `step` int(4) DEFAULT NULL COMMENT '
  if AccessType=2 then step is step of workfow else step is ueseless',
  PRIMARY KEY (`RowID`),
  KEY `StructID` (`StructID`),
  KEY `FieldID` (`FieldID`),
  KEY `AccessType` (`AccessType`),
  KEY `AccessID` (`AccessID`),
  KEY `ActionType` (`ActionType`),
  KEY `ActionID` (`ActionID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'دسترسی های اعمال شده بر روی فرمها ، فیلدهای فرمها و گزارشات' ;

-- --------------------------------------------------------

--
-- Table structure for table `dm_search_history` = سوابق جستجوي آرشيو
--

CREATE TABLE IF NOT EXISTS `dm_search_history` (
  `SearchText` varchar(256) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL COMMENT 'مطلب مورد جستجو',
  `CountOfRepeat` int(11) NOT NULL COMMENT 'تعداد دفعات جستجو',
  PRIMARY KEY (`SearchText`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT 'سوابق جستجوي آرشيو';

-- --------------------------------------------------------

--
-- Table structure for table `dm_shared_folders` = در قسمت بايگاني دبيرخانه و آرشيو در زمان به امانت دادن، ركورد متناظر در  اين جدول درج ميشود.
--

CREATE TABLE IF NOT EXISTS `dm_shared_folders` (
  `RowID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `FolderID` int(11) NOT NULL COMMENT 'کد پوشه بایگانی => oa_sec_archive_folder.RowID',
  `OwnerUserID` int(11) NOT NULL COMMENT 'کد کاربری امانت دهنده => oa_users.RowID',
  `OwnerRoleID` int(11) NOT NULL COMMENT 'کد سمت امانت دهنده => oa_depts_roles.RowID',
  `ReceiverUserID` int(11) NOT NULL COMMENT 'کد کاربر امانت گیرنده => oa_users.RowID',
  `ReceiverRoleID` int(11) NOT NULL COMMENT 'کد سمت امانت گیرنده => oa_depts_roles.RowID',
  `SetDate` date NOT NULL COMMENT 'تاریخ شروع امانت',
  `ExpDate` date NOT NULL COMMENT 'تاریخ اتمام امانت',
  `type` int(11) NOT NULL COMMENT 'اگه يك بود از طريق دبيرخانه امانت  داده شده در غير اين صورت از طريق اسناد امانت داده شده
  حذف شده',
  `Note` varchar(250) COLLATE utf8_persian_ci NOT NULL COMMENT 'شرح',
  PRIMARY KEY (`RowID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'در قسمت بايگاني دبيرخانه و آرشيو در زمان به امانت دادن، ركورد متناظر در  اين جدول درج ميشود.';

-- --------------------------------------------------------

--
-- Table structure for table `dm_structure` = جدول نگهداري ساختار فرم ها، گردش کارها و اسناد آرشیو 
--

CREATE TABLE IF NOT EXISTS `dm_structure` (
  `RowID` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'کد مشخصه',
  `Title` varchar(256) COLLATE utf8_persian_ci NOT NULL COMMENT 'عنوان فرم',
  `Note` varchar(256) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'توضيحات فرم',
  `IsEnable` int(1) NOT NULL COMMENT 'صفر يعني غير فعال و يك يعني فعال',
  `CreatorRole` int(11) NOT NULL COMMENT 'کد سمت ايجاد كننده  => oa_depts_roles.RowID',
  `CreatorUser` int(11) NOT NULL COMMENT 'كد كاربر ايجاد كننده => oa_users.RowID',
  `CreateDate` datetime NOT NULL COMMENT 'تاريخ ايجاد',
  `ConfirmType` int(1) NOT NULL DEFAULT '1' COMMENT '
  قابل استفاده در تعريف بايگاني در قسمت اسناد
  0 = ارشيو بعد از تاييد اوليه سند
  1 = آرشيو بعد از هربار تاييد سند
  2 = ارشيو سند يه ترتيب
  ',
  `ConfirmCount` int(3) DEFAULT NULL COMMENT '
  قابل استفاده در بایگانی اسناد در قسمت اسناد
  if ConfirmType = 1 then ConfirmCount = تعداد تاييدهاي لازم براي بايگاني شدن
  ',
  `SubjectField` varchar(50) COLLATE utf8_persian_ci DEFAULT NULL COMMENT '
  نام یکی از فیلدها که تایین شده به عنوان موضوع سند قرار گیرد - قسمت درج خودکار موضوع در نوار ابزار قسمت تعریف یایگانی ها => dm_structure_field.FieldName',
  `NoteField` varchar(50) COLLATE utf8_persian_ci DEFAULT NULL COMMENT '
  نام یکی از فیلدها که تایین شده به عنوان توضیحات سند قرار گیرد => dm_structure_field.FieldName',
  `TreeInfo` text COLLATE utf8_persian_ci COMMENT '
  ايجاد درخت براي بايگاني شامل يك ساختار جيسون كه حاوي نام درخت ها و فيلدهاي آن ميباشد
  ',
  `TypeOfDoc` int(1) NOT NULL COMMENT '
  نوع سند
  0 = آرشيو
  1 = فرم
  2 = گردش كار
  3 = فرم جزء',
  `Properties` text COLLATE utf8_persian_ci COMMENT 'رشته اي جيسون كه شامل مشخصه هاي فرم ميباشد',
  `NForm` int(1) NOT NULL DEFAULT '0' COMMENT '
  0 = از كلاس هاي قديمي در سورس استفاده ميكند
  1 = از كلاس هاي جديد در سورس استفاده ميكند
  ',
  `icon` text COLLATE utf8_persian_ci COMMENT 'حذف شده',
  `OrderFields` varchar(256) COLLATE utf8_persian_ci DEFAULT NULL COMMENT '
  خروجي كاربر براساس فيدهاي مشخص شده در اين فيلد مرتب ميشود.اين فيلد فقط در قسمت اسناد استفاده ميشود
  این فیلد در اتوماسیون قابل تغییر نمیباشد',
  `CategoryID` int(11) NOT NULL COMMENT 'كد مربوط به پوشه بندي فرم ها - جهت بررسی',
  `urlInfo` TEXT NOT NULL COMMENT 'لینک ذخیره شده برای ساختار و تنظیمات کادر نمایش نتیجه که در قسمت جستجو بر اساس بایگانی در قسمت اسناد استفاده میشود'و
  `orderFieldShow` int(1) NOT NULL COMMENT 'حذف شده',
  PRIMARY KEY (`RowID`),
  KEY `Title` (`Title`(255)),
  KEY `IsEnable` (`IsEnable`),
  KEY `ConfirmType` (`ConfirmType`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'جدول نگهداري ساختار فرم ها، گردش کارها و اسناد آرشیو' ;

-- --------------------------------------------------------

--
-- Table structure for table `dm_structure_field` = فيلد هاي تعريف شده در فرمها و بايگاني ها
--

CREATE TABLE IF NOT EXISTS `dm_structure_field` (
  `RowID` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'کد مشخصه',
  `FieldTitle` varchar(256) COLLATE utf8_persian_ci NOT NULL COMMENT 'عنوان فارسي فيلد',
  `FieldName` varchar(50) COLLATE utf8_persian_ci NOT NULL COMMENT 'نام انگليسي فيلد در جدول معادل',
  `FieldType` int(10) unsigned NOT NULL COMMENT 'نوع فيلد =>dm_field_type.RowID ',
  `StructID` int(10) unsigned NOT NULL COMMENT 'کد شناسايي فرم => dm_structure.RowID',
  `IsEnable` int(1) NOT NULL DEFAULT '1' COMMENT 'فعال یا غیر فعال',
  `FieldsOrder` tinyint(4) NOT NULL COMMENT 'چيدمان فيلد در باقي فيلد ها',
  `properties` text COLLATE utf8_persian_ci COMMENT 'مشخصات فیلد که یک ساختار جیسون است و حاوی مشخصات  شی نمایش داده شده در فرم میباشد',
  `NForm` int(1) NOT NULL DEFAULT '0' COMMENT '
  0= از كلاس هاي قديمي در سورس استفاده ميكند
  1= از كلاس هاي جديد در سورس استفاده ميكند
  ',
  `FieldMode` varchar(50) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'u=عادي,k=كليد,c=ثابت,l=نامه
زماني كه نامه ازسيستم مكاتبات به سيستم ارشيو منتقل ميشود
 در صورت تايين فيلد كليد فيلدهای ثابت از ورود اطلاعات قبلي كاربر اورده ميشوند
 و فيلد نامه مطابق تعريف از فيلد متناظرش براي ان نامه اورده ميشود و فيلد هاي عادی توسط كاربر كامل ميشود'
  `isDate` tinyint(4) DEFAULT NULL COMMENT ' نوع تاريخ است ياخير',
  PRIMARY KEY (`RowID`),
  KEY `FieldTitle` (`FieldTitle`(255)),
  KEY `FieldName` (`FieldName`),
  KEY `StructID` (`StructID`),
  KEY `IsEnable` (`IsEnable`),
  KEY `FieldsOrder` (`FieldsOrder`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci COMMENT 'فيلد هاي تعريف شده در فرمها و بايگاني ها' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dm_templates` = قالب طراحی فرم ها که در قسمت گردش کارها - فرم های ایجاد شده برای هر فرم قابل تغییر است
--

CREATE TABLE IF NOT EXISTS `dm_templates` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) COLLATE utf8_persian_ci NOT NULL COMMENT 'نام قالب - همه مقادیر خالی است - جهت بررسی',
  `StructID` int(11) NOT NULL COMMENT 'شماره فرم => dm_structure.RowID',
  `Html` longtext COLLATE utf8_persian_ci NOT NULL COMMENT 'کد html  قالب',
  `Labels` text COLLATE utf8_persian_ci NOT NULL COMMENT 'برچسب عنوان فیلدهایی مانند امضاء',
  `WFID` int(11) DEFAULT NULL COMMENT 'شماره شناسایی گردش کار - همه مقادیر خالی است - جهت بررسی',
  `WFNodeID` int(11) DEFAULT NULL COMMENT 'شماره نود گردشکار - همه مقادیر خالی است - جهت بررسی',
  `Settings` varchar(500) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'حذف شده',
  `templateType` TINYINT( 1 ) NOT NULL COMMENT '0 : view, 1 : print',
  PRIMARY KEY (`RowID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'قالب طراحی فرم ها که در قسمت گردش کارها - فرم های ایجاد شده برای هر فرم قابل تغییر است' ;

-- --------------------------------------------------------

--
-- Table structure for table `fc_flowchart` = مشخصات گردش کارهای ایجاد شده در قسمت تعریف گردش کار
--

CREATE TABLE IF NOT EXISTS `fc_flowchart` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'کد مشخصه',
  `Name` varchar(256) COLLATE utf8_persian_ci NOT NULL COMMENT 'name of chart' COMMENT 'نام فارسی گردشکار',
  `UserID` int(11) NOT NULL COMMENT 'کد مشخصه کاربر ایجاد کننده => oa_users.RowID',
  `RoleID` int(11) NOT NULL COMMENT 'کد مشخصه سمت ایجاد کننده => oa_depts_roles.RowID',
  `CreateDate` datetime NOT NULL COMMENT 'تاریخ ایجاد',
  `IsEnable` int(1) NOT NULL COMMENT 'حذف شده یانه',
  `FlowType` int(4) NOT NULL COMMENT 'همه مقادیر یک است - جهت بررسی',
  `Properties` longtext COLLATE utf8_persian_ci NOT NULL COMMENT 'مشخصات گردش کار بصورت جیسون',
  `LoadCount` int(11) NOT NULL COMMENT 'تعداد دفعاتی که گردش کار در محیط طراحی گردش کار باز میشود',
  `structID` int(10) DEFAULT NULL COMMENT 'کد شناسایی فرم تایین شده => dm_structure.RowID',
  PRIMARY KEY (`RowID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=198 COMMENT 'مشخصات گردش کارهای ایجاد شده در قسمت تعریف گردش کار' ;
-- --------------------------------------------------------

--
-- Table structure for table `oa_access_point` = ليست امكانات سيستم جهت تعريف دسترسي
--

CREATE TABLE IF NOT EXISTS `oa_access_point` (
  `RowID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `AccessPoint` varchar(50) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'نام لاتین دسترسی',
  `Comments` varchar(50) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'نام فارسی دسترسی',
  `CategoryID` int(2) DEFAULT NULL COMMENT '
  مشخص کننده طبقه بندی دسترسی
  0 = کارتابل
  1 = دبیرخانه
  2 = مدیریت
  3 = گزارشات
  4 = آرشیو
  5 = متفرقه
  ',
  PRIMARY KEY (`RowID`),
  KEY `AccessPoint` (`AccessPoint`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci COMMENT 'ليست امكانات سيستم جهت تعريف دسترسي - مقادیر این جدول بصورت درستی در بانک وارد میشود' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `oa_attach_template_archive` = الگوهای پیوست در قسمت مدیریت کارتابل
--

CREATE TABLE IF NOT EXISTS `oa_attach_template_archive` (
  `RowID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `FolderID` int(10) unsigned NOT NULL COMMENT 'شماره شناسایی پوشه => oa_attach_template_archive_folder.RowID',
  `Title` varchar(50) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'عنوان',
  `Description` varchar(100) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'توضیحات',
  `Content` longblob NOT NULL COMMENT 'محتویات که بصورت فایل قابل دانلود یا آپلود میباشد',
  `Date` datetime NOT NULL COMMENT 'تاریخ ایجاد',
  `RealFileName` varchar(100) COLLATE utf8_persian_ci NOT NULL COMMENT 'نام اصلی  فایل آپلود شده',
  `MimeType` varchar(80) COLLATE utf8_persian_ci NOT NULL COMMENT 'نوع فایل',
  `SecID` int(11) NOT NULL COMMENT 'شماره دبیرخانه مربوط به پوشه => oa_secretariat.RowID',
  PRIMARY KEY (`RowID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'الگوهای پیوست در قسمت مدیریت کارتابل';

-- --------------------------------------------------------

--
-- Table structure for table `oa_attach_template_archive_folder` = پوشه های  الگوهای پیوست
--

CREATE TABLE IF NOT EXISTS `oa_attach_template_archive_folder` (
  `RowID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `SecID` int(11) NOT NULL COMMENT 'شماره دبیرخانه => oa_secretariat.RowID',
  `Title` varchar(70) COLLATE utf8_persian_ci NOT NULL COMMENT 'عنوان',
  `Comments` varchar(100) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'توضیحات',
  `ClassCode` varchar(50) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'شماره کلاسه',
  `OrderID` int(11) NOT NULL COMMENT 'شماره ترتیب',
  `ParentID` int(11) NOT NULL COMMENT 'شماره شناسایی پوشه پدر => oa_attach_template_archive_folder.RowID',
  PRIMARY KEY (`RowID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'پوشه های  الگوهای پیوست';

-- --------------------------------------------------------

--
-- Table structure for table `oa_attach_template_dept` = واحد های مجاز به دسترسی پوشه های  الگوهای پیوست
--

CREATE TABLE IF NOT EXISTS `oa_attach_template_dept` (
  `AttachTemplateFolderID` int(11) NOT NULL DEFAULT '-1' COMMENT 'شماره پوشه پیوست => oa_attach_template_archive_folder.RowID',
  `DeptID` int(11) NOT NULL DEFAULT '-1' COMMENT 'شماره واحد سازمانی => oa_depths_roles.RowID',
  `HasSubDept` int(10) unsigned DEFAULT '0'  'شامل زیر مجموعه های واحد سازمانی هست یا خیر',
  PRIMARY KEY (`AttachTemplateFolderID`,`DeptID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT 'واحد های مجاز به دسترسی پوشه های  الگوهای پیوست';

-- --------------------------------------------------------

--
-- Table structure for table `oa_attach_template_perms` = گروه های مجاز به دسترسی پوشه های  الگوهای پیوست
--

CREATE TABLE IF NOT EXISTS `oa_attach_template_perms` (
  `AttachTemplateFolderID` int(11) NOT NULL DEFAULT '-1' COMMENT 'شماره شناسایی پوشه => oa_attach_template_archive_folder.RowID',
  `AccessGroupID` int(11) NOT NULL DEFAULT '-1' COMMENT 'شماره گروه دسترسی => oa_user_group.RowID',
  PRIMARY KEY (`AttachTemplateFolderID`,`AccessGroupID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci COMMENT 'گروه های مجاز به دسترسی پوشه های  الگوهای پیوست';

-- --------------------------------------------------------

--
-- Table structure for table `oa_attach_template_sec` = دبیرخانه های مجاز به دسترسی پوشه های  الگوهای پیوست
--

CREATE TABLE IF NOT EXISTS `oa_attach_template_sec` (
  `AttachTemplateFolderID` int(11) NOT NULL DEFAULT '-1' COMMENT 'شماره شناسایی پوشه => oa_attach_template_archive_folder.RowID',
  `SecID` int(11) NOT NULL DEFAULT '-1' COMMENT 'شماره دبیرخانه => oa_secretariat.RowID',
  PRIMARY KEY (`AttachTemplateFolderID`,`SecID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci COMMENT 'دبیرخانه های مجاز به دسترسی پوشه های  الگوهای پیوست';

-- --------------------------------------------------------

--
-- Table structure for table `oa_betweenserver_letter` = جدول نامه های بین سروری
--

CREATE TABLE IF NOT EXISTS `oa_betweenserver_letter` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `referID` int(11) NOT NULL COMMENT 'if type=0 then referID => oa_doc_refer.RowID else referID => oa_document.RowID - جهت بررسی',
  `secOutID` int(11) NOT NULL COMMENT 'شماره شناسایی طرف مکاتبات => oa_sec_outpersons.RoeID',
  `secID` int(11) NOT NULL COMMENT 'شماره دبیرخانه => oa_secretariat.RowID',
  `RegUserID` int(11) NOT NULL COMMENT 'شماره کاربری ثبات نامه => oa_users.RowID',
  `RegRoleID` int(11) NOT NULL COMMENT 'شماره سمت کاربر ثبات => oa_depts_roles.RowID',
  `IsSend` tinyint(1) NOT NULL COMMENT 'ارسال شده یا خیر',
  `SendDate` date NOT NULL COMMENT 'تاریخ ارسال',
  `IsSendAttach` int(1) DEFAULT NULL COMMENT 'اگر صفر باشد دارای پیوست است و اگر یک باشد پیوست ندارد',
  `didOtherServer` int(11) NOT NULL COMMENT 'شماره نامه نامه ارسالی در سرور مقصد',
  `settings` text NOT NULL COMMENT 'تنظیمات و مشخصات',
  `wsType` int(1) NOT NULL COMMENT '
  0 = نامه ارسالی است
  1 = نامه دریافتی است
  ',
  `GUIDOtherServer` varchar(50) NOT NULL COMMENT 'کد انحصاری سرور  طرف مکاتبه => oa_secretariat.GUID',
  `WSReferType` int(2) NOT NULL COMMENT '
  0 = ارجاع نشده 
  1 = ارجاع شده
  2 = برگشت داده شده از کارتابل دبیرخانه',
  `mainReferID` int(11) NOT NULL COMMENT 'شماره ارجاعی که بعد از ارجاع نامه ایجاد میگردد',
  `ReferNoteDesc` varchar(200) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL COMMENT 'توضیحات',
  `IsSendHistoryAttach` int(1) NOT NULL COMMENT 'ارسال سابقه گردش نامه یا خیر
  0 = سابقه گردش نامه همراه نامه ضمیمه نشده است
  1 = سابقه گردش نامه همراه نامه ضمیمه شده است
  ',
  `HistoryFile` blob NOT NULL COMMENT 'فایل سابقه گردش نامه',
  `MainLetterCodeForEce` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_persian_ci NULL COMMENT 'این فیلد مین لتر کد را برای نامه های ای سی ای ارسالی استفاده می شود.',
  PRIMARY KEY (`RowID`),
  KEY `referID` (`referID`),
  KEY `secOutID` (`secOutID`),
  KEY `didOtherServer` (`didOtherServer`),
  KEY `GUIDOtherServer` (`GUIDOtherServer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT 'جدول نامه های بین سروری';

-- --------------------------------------------------------

--
-- Table structure for table `oa_city` = لیست شهرا همراه با مختصات جغرافیایی
--

CREATE TABLE IF NOT EXISTS `oa_city` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(256) COLLATE utf8_persian_ci NOT NULL COMMENT 'نام شهر',
  `Longitude` float NOT NULL COMMENT 'طول جغرافیایی',
  `Latitude` float NOT NULL COMMENT 'عرض جغرافیایی',
  PRIMARY KEY (`RowID`),
  KEY `Name` (`Name`,`Longitude`,`Latitude`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci COMMENT 'لیست شهرا همراه با مختصات جغرافیایی' AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `oa_content` = مشخصات کلیه فایل های ذخیره شده در اتوماسیون شامل تصاویر پیوست ها و متن نامه ها
--

CREATE TABLE IF NOT EXISTS `oa_content` (
  `RowID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `UserID` int(11) unsigned NOT NULL COMMENT 'شماره کاربر',
  `RoleID` int(11) unsigned NOT NULL COMMENT 'شماره سمت ایجاد کننده',
  `DocReferID` int(11) DEFAULT NULL COMMENT '
  if ContentType = 4,5 then DocReferID => oa_refers.RowID
  if ContentType = 1,2,3,101,6 then DocReferID => oa_document.RowID
  if ContentType = 7 , fax
  if ContentType = 10 then DocReferID is oa_board.RowID, 
  ',
  `Desc` varchar(100) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'توضیحات فایل',
  `CreateDate` datetime DEFAULT NULL COMMENT 'تاریخ ایجاد',
  `ModifiedDate` datetime DEFAULT NULL COMMENT 'تاریخ بروز رسانی',
  `PersistFileName` varchar(100) COLLATE utf8_persian_ci DEFAULT NULL COMMENT '
  if ContentType = 6 then PersistFileName => dm_structure_field.RowID
  ',
  `RealFileName` varchar(50) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'نام اصلی فایل',
  `MimeType` char(80) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'نوع فایل',
  `FileSize` int(11) unsigned DEFAULT NULL COMMENT 'حجم فایل',
  `IsScan` tinyint(1) unsigned DEFAULT '0' COMMENT 'اسکن است یا خیر ',
  `CompactContent` text COLLATE utf8_persian_ci COMMENT '
  if ContentType = 2 then CompactContent = متن نامه
  ',
  `EncryptedHeader` blob COMMENT 'بعد از ذخیره شدن فایل در سیستم قسمتی از هدر فایل بصورت رندم جدا شده و به صورت کد شده در این فیلد قرار میگیرد',
  `EncryptedHeaderLength` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'طول هدر کد شده فایل',
  `ScanOrder` float NOT NULL DEFAULT '-1' COMMENT 'شماره ترتیب تصویر و یا پیوست',
  `ContentType` int(3) DEFAULT NULL COMMENT '1 scan, 2 text, 3 attach, 4 refer, 5 paraoh, 6 form field, 7 deleted fax, 8 attach for letter job, 9 OCR, 10 boardAttach, 101 DMS',
  `contentState` int(1) NOT NULL DEFAULT '1' COMMENT '
  1 = فعال
  0 = غیر فعال
  ',
  `SecID` int(11) DEFAULT NULL COMMENT 'شماره دبیرخانه => oa_secretariat.RowID',
  `PhysicalFileName` int(11) DEFAULT NULL COMMENT 'جهت بررسی',
  `CurrentAddress` int(4) DEFAULT NULL COMMENT 'شماره پوشه ذخیره کننده در آرشیو - همه مقادیر خالی است - جهت بررسی',
  PRIMARY KEY (`RowID`),
  KEY `UserID` (`UserID`),
  KEY `RoleID` (`RoleID`),
  KEY `ScanOrder` (`ScanOrder`),
  KEY `SecID` (`SecID`),
  KEY `ContentType` (`ContentType`),
  KEY `contentState` (`contentState`),
  KEY `DocReferID` (`DocReferID`),
  KEY `PersistFileName` (`PersistFileName`),
  KEY `MimeType` (`MimeType`),
  KEY `IsScan` (`IsScan`),
  KEY `PhysicalFileName` (`PhysicalFileName`,`CurrentAddress`),
  KEY `CurrentAddress` (`CurrentAddress`),
  KEY `MimeType_2` (`MimeType`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'مشخصات کلیه فایل های ذخیره شده در اتوماسیون شامل تصاویر پیوست ها و متن نامه ها' ;

-- --------------------------------------------------------

--
-- Table structure for table `oa_dberror` = خطاهای بانک اطلاعاتی ثبت شده
--

CREATE TABLE IF NOT EXISTS `oa_dberror` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `ErrDateTime` datetime NOT NULL COMMENT 'تاریخ خطا',
  `SQL` text COLLATE utf8_persian_ci NOT NULL COMMENT 'متن پرس و جوی دارای خطا',
  `UserID` int(11) DEFAULT NULL COMMENT 'کاربر اجرا کننده پرس و جو ',
  PRIMARY KEY (`RowID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'خطاهای بانک اطلاعاتی ثبت شده ';

-- --------------------------------------------------------

--
-- Table structure for table `oa_depts_roles` = سمت ها و واحدهای سازمانی
--

CREATE TABLE IF NOT EXISTS `oa_depts_roles` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'کد مشخصه',
  `Name` varchar(100) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'نام سمت',
  `DeptID` int(11) DEFAULT NULL COMMENT 'شماره واحد سازمانی => oa_depts_roles.RowID',
  `CanSign` int(1) DEFAULT NULL COMMENT 'حق امضاء دارد یانه',
  `UserID` int(11) DEFAULT NULL COMMENT 'شماره شناسایی کاربر => oa_users.RowID',
  `IsDefault` int(1) DEFAULT NULL COMMENT 'سمت پیش فرض است یانه',
  `ParentID` int(11) NOT NULL DEFAULT '-1' COMMENT 'شماره واحد سازمانی پدر => oa_depts_roles.RowID',
  `IsEnable` int(1) DEFAULT '1' COMMENT 'فعال یا غیر فعال',
  `HasDefaultSign` tinyint(4) DEFAULT NULL COMMENT 'دارای امضای پیش فرض است یانه',
  `CustomSign` varchar(700) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'متن پیشفرض که درصورت مقدار داشتن کنار امضا قرار میگیرد',
  `Prefix` varchar(45) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'پیش وند واحد سازمانی',
  `Postfix` varchar(45) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'پسوند واحد سازمانی',
  `SecID` int(11) unsigned DEFAULT NULL COMMENT 'شماره دبیرخانه واحد => oa_secretariat.RowID',
  `RowType` int(1) NOT NULL DEFAULT '1' COMMENT '
  1 = سمت
  2 = واحد
  ',
  `path` varchar(256) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'مسیر سازمانی تشکیل شده از کد سمت های موجود در همین جدول با توجه به چارت سازمانی',
  `temprowid` int(11) DEFAULT NULL COMMENT 'حذف شده',
  `LetterHeader` tinytext COLLATE utf8_persian_ci COMMENT 'سربرگ سفارشی برای درج در نامه ها',
  `level` int(5) DEFAULT NULL COMMENT 'سطح سازمانی',
  `accessReferFromAll` tinyint(4) NOT NULL DEFAULT '0' COMMENT '
  دریافت ارجاع از همه
  1 = دریافت کند
  0 = دریافت نکند
  ',
  `groupID` varchar(256) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'جهت بررسی',
  PRIMARY KEY (`RowID`),
  KEY `DeptRoleID` (`DeptID`),
  KEY `UserID` (`UserID`),
  KEY `ParentID` (`ParentID`),
  KEY `level` (`level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'سمت ها و واحدهای سازمانی';

-- --------------------------------------------------------

--
-- Table structure for table `oa_depts_roles_log` = سوابق تغییرات سمت
--

CREATE TABLE IF NOT EXISTS `oa_depts_roles_log` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL COMMENT 'شماره شناسایی کاربر',
  `RoleID` int(11) NOT NULL COMMENT 'شماره سمت',
  `RoleName` varchar(100) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL COMMENT 'نام قبلی سمت',
  `RegDate` datetime NOT NULL COMMENT 'تاریخ تغییر',
  PRIMARY KEY (`RowID`),
  KEY `UserID` (`UserID`),
  KEY `RoleID` (`RoleID`),
  KEY `RoleName` (`RoleName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT 'سوابق تغییرات سمت' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `oa_document` = به ازای هر نامه، پیام و سند ایجاد شده یک رکورد در این جدول درج میشود که حاوی مشخصات کلی سند میباشد
--

CREATE TABLE IF NOT EXISTS `oa_document` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `Subject` varchar(100) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'موضوع نامه',
  `ContentID` int(11) DEFAULT NULL COMMENT 'کد مشخصه محتوای نامه => oa_content.RowID',
  `CategoryID` int(11) NOT NULL COMMENT 'نوع محرمانگی => oa_secret_level.RowID',
  `CreateDate` date DEFAULT NULL COMMENT 'تاریخ ایجاد',
  `CreatorRoleID` int(11) DEFAULT NULL COMMENT 'کد مشخصه سمت ایجاد کننده نامه => oa_depts_roles.RowID',
  `CreatorUserID` int(11) DEFAULT NULL COMMENT 'کد مشخصه کاربری ایجاد کننده نامه => oa_users.RowID',
  `DocType` int(11) DEFAULT NULL COMMENT '
  1 = نامه,
  2 = پیام,
  10 = فرم یا سند ارشیو,
  99 = ای سی ای های تکراری',
  `DocDesc` varchar(200) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'شرح',
  `DocStatus` tinyint(4) DEFAULT NULL COMMENT '
  0 = معمولی,
  1 = پیش نویس,
  2 = نامه در دبیرخانه است,
  3 = reserve,
  101 = پیش نویس یا قابل ویرایش,
  102 = اماده تایید,
  103 = تایید شده و اماده ارشیو,
  104 = ارشیو شده',
  `HasAttach` int(11) NOT NULL COMMENT 'دارای پیوست یا خیر',
  `MainAttachNum` int(11) NOT NULL DEFAULT '0' COMMENT 'تعداد پیوست',
  `AttDesc` varchar(140) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'شرح پیوست',
  `IsEnable` int(1) DEFAULT '1' COMMENT 'فعال یا غیر فعال',
  `Browser` varchar(20) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'نوع مرورگر',
  `Depts` varchar(200) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'شماره واحدهایی که به نامه دسترسی دارند',
  `HasMultiSigners` int(1) NOT NULL DEFAULT '0' COMMENT '
  0 = تک امضا 
  1 = چند امضایی ترتیبی 
  3 =  چند امضایی همزمان
  4 = tartibi   ',
  PRIMARY KEY (`RowID`),
  KEY `ContentID` (`ContentID`),
  KEY `CreatorRoleID` (`CreatorRoleID`),
  KEY `CreatorUserID` (`CreatorUserID`),
  KEY `Depts` (`Depts`(150)),
  KEY `Subject` (`Subject`),
  KEY `DocDesc` (`DocDesc`),
  KEY `IsEnable` (`IsEnable`),
  KEY `DocType` (`DocType`),
  KEY `DocStatus` (`DocStatus`),
  KEY `CategoryID` (`CategoryID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'به ازای هر نامه، پیام و سند ایجاد شده یک رکورد در این جدول درج میشود که حاوی مشخصات کلی سند میباشد' ;

-- --------------------------------------------------------

--
-- Table structure for table `oa_doc_attach` = این جدول بدون استفاده میباشد - جهت بررسی
--

CREATE TABLE IF NOT EXISTS `oa_doc_attach` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `DocID` int(11) DEFAULT NULL COMMENT 'شماره سند  => oa_document.RowID',
  `ContentID` int(11) DEFAULT NULL COMMENT '  شماره فایل از جدول => oa_content.RowID',
  PRIMARY KEY (`RowID`),
  KEY `DocID` (`DocID`),
  KEY `ContentID` (`ContentID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'این جدول بدون استفاده میباشد - جهت بررسی' ;

-- --------------------------------------------------------

--
-- Table structure for table `oa_doc_link` = تعریف رابطه عطف و پیرو بین نامه ها
--

CREATE TABLE IF NOT EXISTS `oa_doc_link` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `DocID` int(11) DEFAULT NULL COMMENT ' شماره سند  یک => oa_document.RowID',
  `DocID2` int(11) DEFAULT NULL COMMENT ' شماره سند دو  => oa_document.RowID',
  `LinkType` tinyint(4) DEFAULT NULL COMMENT '
  توع اتصال
  1 = عطف
  2 = پیرو
  3 = عطف ثبت نشده
  4 = پیرو ثبت نشده
  ',
  `LinkDate` date DEFAULT NULL COMMENT 'تاریخ ایجاد',
  `CustomRegCode` varchar(50) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'جهت بررسی',
  `CustomSubject` varchar(100) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'عنوان لینک',
  `CustomSender` varchar(100) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'جهت بررسی',
  PRIMARY KEY (`RowID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'تعریف رابطه عطف و پیرو بین نامه ها' ;

-- --------------------------------------------------------

--
-- Table structure for table `oa_doc_log` = سابقه اعمال انجام شده بر روی سند 
--

CREATE TABLE IF NOT EXISTS `oa_doc_log` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'کد مشخصه',
  `Category` tinyint(4) DEFAULT NULL COMMENT 'همه مقادیر خالی است - جهت بررسی',
  `DocID` int(11) DEFAULT NULL COMMENT 'شماره سند => oa_secretariat.RowID',
  `ReferID` int(11) DEFAULT NULL COMMENT 'شماره ارجاع => oa_doc_refer.RowID - همه مقادیر خالی است - جهت بررسی',
  `LogDesc` varchar(100) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'شرح لاگ',
  `LogDateTime` datetime DEFAULT NULL COMMENT 'تاریخ ایجاد',
  `UserID` int(11) DEFAULT NULL COMMENT 'شماره کاربر => oa_users.RowID',
  `RoleID` int(11) DEFAULT NULL COMMENT 'شماره سمت => oa_depts_roles.RowID',
  `UserName` varchar(50) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'همه مقادیر خالی است - جهت بررسی',
  `LogID` int(11) NOT NULL COMMENT 'شماره شناسایی لاگ => oa_log.RowID',
  PRIMARY KEY (`RowID`),
  KEY `DocID` (`DocID`),
  KEY `ReferID` (`ReferID`),
  KEY `UserID` (`UserID`),
  KEY `LogID` (`LogID`),
  KEY `RoleID` (`RoleID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1  COMMENT 'سابقه اعمال انجام شده بر روی سند ';

-- --------------------------------------------------------

--
-- Table structure for table `oa_doc_refer` = لیست ارجاعات بین نامه ها و اسناد
--

CREATE TABLE IF NOT EXISTS `oa_doc_refer` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `DocID` int(11) DEFAULT NULL COMMENT 'کد مشخصه سند => oa_document=>RowID',
  `FromRoleID` int(11) DEFAULT NULL COMMENT 'کد مشخصه سمت ارسال کننده => oa_depts_roles.RowID',
  `FromUserID` int(11) DEFAULT NULL COMMENT 'کد مشخصه کاربر ارسال کننده => oa_users.RowID',
  `IsAssist` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'ارسال از نیابت یا خیر',
  `ToRoleID` int(11) DEFAULT NULL COMMENT 'کد مشخصه سمت دریافت کننده => oa_depts_roles.RowID',
  `ToUserID` int(11) DEFAULT NULL COMMENT 'کد مشخصه کاربر دریافت کننده => oa_users.RowID',
  `ReferDate` datetime DEFAULT NULL COMMENT 'تاریخ ارجاع',
  `TimeoutDate` datetime DEFAULT NULL COMMENT 'تاریخ مهلت',
  `HasTrack` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'دارای پیگیری یا خیر',
  `NoteDesc` varchar(1000) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'شرح ارجاع',
  `UrgentID` int(11) DEFAULT NULL COMMENT '
  فوریت
  1 = عادی
  2 = فوری
  3 = آنی
  ',
  `SecID` int(11) DEFAULT NULL COMMENT 'شماره دبیرخانه => oa_secretariat.RowID',
  `IsOpened` int(1) DEFAULT '0' COMMENT 'باز شده یا خیر',
  `FirstOpenDate` datetime DEFAULT NULL COMMENT 'تاریخ اولین باز شدن نامه',
  `OpenDuration` int(11) DEFAULT NULL COMMENT 'تعداد روزهای طی شده بین این ارجاع و ارجاع بعدی',
  `ParentID` int(11) DEFAULT NULL COMMENT 'شماره ارجاع پدر - شماره ارجاع قبلی => oa_doc_refer.RowID',
  `FromOtherOutName` varchar(100) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'نام واحد سازمانی فرستنده نامه مشخص شده توسط فیلد FromOutID',
  `ToOtherOutName` varchar(100) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'نام واحد سازمانی گیرنده نامه مشخص شده توسط فیلد ToOutID',
  `PerformDate` datetime DEFAULT NULL COMMENT 'تاریخ اخرین اقدام روی نامه',
  `SenderCheckPerform` tinyint(1) DEFAULT '0' COMMENT 'جهت بررسی',
  `RefStatus` tinyint(4) DEFAULT NULL COMMENT '
  وضعیت ارجاع
  0 معمولی, 
  1 آرشیو, 
  2 حذف شده, 
  3 ارجاع خودکار, 
  4 دبیرخانه, 
  6 در دست اقدام, 
  7 برگشت بین دبیرخانه ای, 
  8 برگشت به امضا کننده, 
  101 ایجاد سند در بایگانی, 
  102 تایید سند در بایگانی, 
  103 ذخیره سند در بایگانی',
  `SecureBackRefer` tinyint(1) NOT NULL DEFAULT '0' COMMENT '
  1 = سابقه مخفی شود
  0 = سابقه نمایش داده شود
  ',
  `SecureFrontRefer` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'ارجاع محرمانه',
  `IsRefered` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'ارجاع شده یا خیر. اگر این مقدار صفر باشد یعنی این ارجاع آخرین ارجاع نامه مربوطه است',
  `Hidden` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'مخفی',
  `FromOutID` int(11) DEFAULT NULL COMMENT 'شماره شناسایی واحد سازمانی فرستنده',
  `ToOutID` int(11) DEFAULT NULL COMMENT 'شماره شناسایی واحد سازمانی گیرنده',
  `IsSigned` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'امضا شده',
  `path` varchar(200) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'حذف شود',
  `del` int(11) NOT NULL DEFAULT '0' COMMENT 'حذف شود',
  `FaxID` bigint(20) DEFAULT NULL COMMENT 'جهت بررسی',
  `SendType` int(11) NOT NULL COMMENT 'حذف شده',
  PRIMARY KEY (`RowID`),
  KEY `DocID` (`DocID`),
  KEY `FromRoleID` (`FromRoleID`),
  KEY `FromUserID` (`FromUserID`),
  KEY `ToUserID` (`ToUserID`),
  KEY `SecID` (`SecID`),
  KEY `ParentID` (`ParentID`),
  KEY `IsRefered` (`IsRefered`),
  KEY `ReferDate` (`ReferDate`),
  KEY `Hidden` (`Hidden`),
  KEY `RefStatus` (`RefStatus`),
  KEY `ToUserId & ToRoleId` (`ToUserID`,`ToRoleID`),
  KEY `IsOpened` (`IsOpened`),
  KEY `FromUserID&FromRoleID` (`FromUserID`,`FromRoleID`),
  KEY `PerformDate` (`PerformDate`),
  KEY `TimeoutDate` (`TimeoutDate`),
  KEY `ToRoleID&RefStatus` (`ToRoleID`,`RefStatus`),
  KEY `FromRoleID&RefStatus` (`FromRoleID`,`RefStatus`),
  KEY `UrgentID` (`UrgentID`),
  KEY `FromOutID` (`FromOutID`),
  KEY `FromRoleID_2` (`ToRoleID`,`ReferDate`),
  KEY `IsSigned` (`IsSigned`,`FaxID`),
  KEY `ToRoleID` (`ToRoleID`,`FromRoleID`,`RefStatus`,`ReferDate`),
  KEY `DocID_2` (`ToRoleID`,`DocID`,`ReferDate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'لیست ارجاعات بین نامه ها و اسناد';

-- --------------------------------------------------------

--
-- Table structure for table `oa_doc_titles` = عناوین و رونوشت های نامه که هنگام ارسال نامه در تب عناوین اضافه میشوند
--

CREATE TABLE IF NOT EXISTS `oa_doc_titles` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `SecID` int(11) NOT NULL COMMENT 'شماره دبیرخانه => oa_secretariat.RowID',
  `DocID` int(11) NOT NULL COMMENT 'شماره سند => oa_document.RowID',
  `ReceiverType` tinyint(4) NOT NULL COMMENT '
  0 = خارج سازمان
  1 = داخلی
  2 = بین دبیرخانه ای
  3 = خارج سازمان به غیر از بدون سرویس - ارسال مجدد
  ',
  `ReceiverSecID` int(11) DEFAULT NULL COMMENT 'if ReceiverType = 2 then ReceiverSecID => oa_secretariat.RowID else ReceiverSecID = null',
  `SecOutPersonID` int(11) DEFAULT NULL COMMENT 'if ReceiverType = 0 then SecOutPersonID => oa_sec_outpersons.RowID else SecOutPersonID = null',
  `UserID` int(11) DEFAULT NULL COMMENT 'if ReceiverType = 1 then UserID => oa_depts_roles.RowID else UserID = null',
  `RoleID` int(11) DEFAULT NULL COMMENT 'if ReceiverType = 1 then RoleID => oa_users.RowID else RoleID = null',
  `Title` varchar(300) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'عنوان',
  `IsCC` tinyint(4) NOT NULL COMMENT '
  رونوشت
  0 = رکورد جزو عناوین میباشد
  1 = رکورد جزو رونوشت ها میباشد
  ',
  `ServerOutID` int(11) NOT NULL DEFAULT '-1' COMMENT 'حذف شود',
  PRIMARY KEY (`RowID`),
  KEY `DocID` (`DocID`),
  KEY `UserID` (`UserID`),
  KEY `RoleID` (`RoleID`),
  KEY `SecID` (`SecID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'عناوین و رونوشت های نامه که هنگام ارسال نامه در تب عناوین اضافه میشوند' ;

-- --------------------------------------------------------

--
-- Table structure for table `oa_sec_doc_title_group` similar to `oa_per_doc_title_group`
--

CREATE TABLE IF NOT EXISTS `oa_sec_doc_title_group` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `GroupTitle` varchar(70) COLLATE utf8_persian_ci DEFAULT NULL,
  `SecID` int(11) DEFAULT NULL,
  PRIMARY KEY (`RowID`),
  KEY `SecID` (`SecID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `oa_sec_doc_title_group_detail` similar to `oa_per_doc_title_group_detail`
--

CREATE TABLE IF NOT EXISTS `oa_sec_doc_title_group_detail` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(100) COLLATE utf8_persian_ci DEFAULT NULL,
  `IsCC` tinyint(1) DEFAULT NULL,
  `DocTitleGroupID` int(11) DEFAULT NULL,
  `UserID` int(11) NOT NULL DEFAULT '-1',
  `RoleID` int(11) NOT NULL DEFAULT '-1',
  `ReceiverType` tinyint(4) NOT NULL DEFAULT '-1',
  `ReceiverSecID` int(11) NOT NULL DEFAULT '-1',
  `SecOutPersonID` int(11) NOT NULL DEFAULT '-1',
  `ServerOutID` int(11) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`RowID`),
  UNIQUE KEY `RowID` (`RowID`),
  KEY `UserID` (`Title`),
  KEY `RoleID` (`IsCC`),
  KEY `ReferGroupID` (`DocTitleGroupID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `oa_emerg_level` = فوریت های نامه که برای هر نامه در تب مشخصات قابل تغییر میباشد
--

CREATE TABLE IF NOT EXISTS `oa_emerg_level` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `SecID` int(11) NOT NULL COMMENT 'شماره دبیرخانه => oa_secretariat.RowID',
  `Title` varchar(256) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL COMMENT 'عنوان فوریت که در تب مشخصات نامه نمایش داده میشود',
  `Desc` text COMMENT 'شرح',
  `During` time NOT NULL DEFAULT '00:00:00' COMMENT 'the during of level ;0 for normal',
  `orderList` tinyint(4) NOT NULL COMMENT 'شماره ترتیب فوریت هنگام نمایش',
  `Color` varchar(256) CHARACTER SET utf8 COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'مشخص میکند نامه دارای این فوریت در کارتابل گیرنده به چه رنگی نمایش داده شود',
  PRIMARY KEY (`RowID`),
  KEY `SecID` (`SecID`),
  KEY `SecID_2` (`SecID`),
  KEY `Title` (`Title`),
  KEY `During` (`During`),
  KEY `orderList` (`orderList`),
  KEY `Color` (`Color`),
  KEY `Color_2` (`Color`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=33 COMMENT 'فوریت های نامه که برای هر نامه در تب مشخصات قابل تغییر میباشد';

-- --------------------------------------------------------

--
-- Table structure for table `oa_file_types` = انواع فایل تعریف شده در سیستم
--

CREATE TABLE IF NOT EXISTS `oa_file_types` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `Mime` varchar(250) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'mime type of file',
  `Postfix` varchar(10) COLLATE utf8_persian_ci NOT NULL COMMENT 'پسوند نوع',
  `FileType` varchar(50) COLLATE utf8_persian_ci NOT NULL COMMENT 'نوع فایل',
  PRIMARY KEY (`RowID`),
  UNIQUE KEY `Mime` (`Mime`,`Postfix`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci COMMENT 'انواع فایل تعریف شده در سیستم' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `oa_group_members` = لیست سمت های عضو در گروه های درسترسی
--

CREATE TABLE IF NOT EXISTS `oa_group_members` (
  `RowID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `RoleID` int(11) unsigned DEFAULT NULL COMMENT 'شماره سمت => oa_depts_roles.RowID',
  `UserGroupID` int(11) unsigned DEFAULT NULL COMMENT 'شماره گروه دسترسی => oa_user_group.RowID',
  PRIMARY KEY (`RowID`),
  KEY `RoleID` (`RoleID`),
  KEY `UserGroupID` (`UserGroupID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1  COMMENT 'لیست سمت های عضو در گروه های درسترسی';

-- --------------------------------------------------------

--
-- Table structure for table `oa_hadith` = حدیث های تعریف شده در سیستم
--

CREATE TABLE IF NOT EXISTS `oa_hadith` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `Owner` varchar(40) COLLATE utf8_persian_ci NOT NULL COMMENT 'گوینده',
  `Content` varchar(400) COLLATE utf8_persian_ci NOT NULL COMMENT 'متن حدیث',
  `fontSize` tinyint(4) DEFAULT NULL COMMENT 'اندازه فونت حدیث',
  PRIMARY KEY (`RowID`),
  KEY `fontSize` (`fontSize`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'حدیث های تعریف شده در سیستم';

-- --------------------------------------------------------

--
-- Table structure for table `oa_letter` = نامه های موجود در سیستم
--

CREATE TABLE IF NOT EXISTS `oa_letter` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `DocID` int(11) DEFAULT NULL COMMENT 'شماره سند => oa_document.RowID',
  `MainLetterCode` varchar(20) COLLATE utf8_persian_ci DEFAULT NULL COMMENT '
  if LetterType = 1 then MainLetterCode = null
  if LetterType = 2 then MainLetterCode = RegCode
  if LetterType = 3 then MainLetterCode = شماره اصلی نامه که از طرف فرستنده تایین شده
  ',
  `MainLetterDate` date DEFAULT NULL COMMENT '
  if LetterType = 1 then MainLetterDate = null
  if LetterType = 2 then MainLetterDate = RegDate
  if LetterType = 3 then MainLetterDate = تاریخ اصلی نامه که از طرف فرستنده تایین شده
  ',
  `SecID` int(11) DEFAULT NULL COMMENT 'شماره دبیرخانه => oa_secretariat.RowID',
  `LetterType` int(11) DEFAULT NULL COMMENT '
  نوع نامه
  1 = داخلی 
  2 = صادره 
  3 = وارده',
  `SendRecID` int(11) DEFAULT NULL COMMENT '
  نحوه دریافت نامه
  1 = پست
  2 = پیک
  3 = دورنگار
  4 = ECE
  5 = بین سروری
  6 = بین دبیرخانه ای
  7 = دولت
  ',
  `OrigType` tinyint(4) DEFAULT '0' COMMENT '
  نوع نامه در تب مشخصات نامه
  1 = اصل
  2 = رونوشت
  ',
  `TemplateID` int(11) DEFAULT NULL COMMENT '
  نوع نامه در تب مشخصات نامه
  1 = متنی
  2 = اسکنی
  ',
  `IsSigned` int(1) DEFAULT NULL COMMENT 'امضا شده یا نه',
  `WhoPrintUserID` int(11) NOT NULL COMMENT 'کد کاربری تهیه کننده نسخه چاپی => oa_users.RowID',
  `WhoPrintRoleID` int(11) DEFAULT NULL COMMENT 'کد مشخصه سمت تهیه کننده نسخه چاپی => oa_depts_roles.RowID',
  `PrintTemplateID` int(11) NOT NULL COMMENT 'کد مشخصه الگوی چاپ => oa_print_template.RowID',
  `RegUserID` int(11) DEFAULT NULL COMMENT 'کد مشخصه کاربری ثبات نامه => oa_users.RowID',
  `RegRoleID` int(11) DEFAULT NULL COMMENT 'کد مشخصه سمت ثبات => oa_depts_roles.RowID',
  `RegDate` date DEFAULT NULL COMMENT 'تاریخ ثبت',
  `RegCode` int(11) DEFAULT NULL COMMENT 'شماره ثبتی',
  `Prefix` varchar(7) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'پیشوند',
  `Postfix` varchar(7) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'پسوند',
  `InSecInbox` tinyint(1) NOT NULL DEFAULT '0' COMMENT '
  وضعیت در دبیرخانه
  1 = در کارتابل دبیرخانه است و هنوز ثبت نشده است
  0 = از کارتابل دبیرخانه خارج شده و ثبت شده است
  ',
  `LetterStatus` tinyint(4) NOT NULL DEFAULT '0' COMMENT '
  وضعیت نامه
  0 = معمولی
  1 = نامه حدفاصل پس از امضا تا قبل از ارسال(نامه ممکن است در کارتابل دبیرخانه باشد و هنوز شماره نگرفته و یا شماره گرفته اما ارسال نشده)
  2 = پیشنویس بین دبیرخانه ای)(نامه صادره سمت فرستنده)
  ',
  `FlowType` tinyint(4) DEFAULT '0' COMMENT 'دارای گردش کاغذی یا خیر',
  `IsAssist` tinyint(1) NOT NULL DEFAULT '0' COMMENT '
  نیابت
  0 = ایجاد کننده خود شخص است
  1 = ایجاد کننده نایب شخص است
  ',
  `regYear` int(4) DEFAULT NULL COMMENT 'حذف شود',
  `Year` int(4) DEFAULT NULL COMMENT 'سال ثبت',
  PRIMARY KEY (`RowID`),
  KEY `DocID` (`DocID`),
  KEY `WhoPrintUserID` (`WhoPrintUserID`),
  KEY `WhoPrintRoleID` (`WhoPrintRoleID`),
  KEY `SecID` (`SecID`),
  KEY `InSecInbox` (`InSecInbox`),
  KEY `LetterType` (`LetterType`),
  KEY `RegDate` (`RegDate`),
  KEY `MainLetterCode` (`MainLetterCode`),
  KEY `MainLetterDate` (`MainLetterDate`),
  KEY `Year` (`Year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'نامه ها';

-- --------------------------------------------------------

--
-- Table structure for table `oa_letter_jobs` = اقدامات نامه
--

CREATE TABLE IF NOT EXISTS `oa_letter_jobs` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `RoleID` int(11) DEFAULT '-1' COMMENT 'کد مشخصه سمت ایجاد کننده اقدام => oa_depts_roles.RowID',
  `UserID` int(11) DEFAULT '-1' COMMENT 'کد مشخصه کاربری ایجاد کننده اقدام => oa_users.RowID',
  `PostDate` date DEFAULT NULL COMMENT 'تاریخ ایجاد',
  `Desc` varchar(250) COLLATE utf8_persian_ci DEFAULT 'بدون شرح' COMMENT 'شرح',
  `LetterID` int(11) DEFAULT '-1' COMMENT 'کد مشخصه نامه => oa_letter.RowID',
  `ProjID` int(11) DEFAULT NULL COMMENT 'کد مشخصه پروژه => oa_project.RowID',
  `During` time DEFAULT NULL COMMENT 'مدت زمان صرف شده برای اقدام',
  `JobDate` date DEFAULT NULL COMMENT 'تاریخ اقدام',
  `IsConfirm` int(11) NOT NULL DEFAULT '0' COMMENT '
  وضعیت تایید اقدام
  0 = تایید نشده 
  1 = تایید شده
  ',
  `IsDelete` int(11) NOT NULL DEFAULT '0' COMMENT '
  وضعیت حذف
  0 = حذف نشده
  1 = حذف شده
  ',
  `customerID` int(11) NOT NULL COMMENT 'کد مشخصه مشتری => oa_project_coding.RowID',
  `serviceID` int(11) NOT NULL COMMENT 'کد مشخصه سرویس oa_project_coding.RowID',
  `conventionID` tinyint(4) DEFAULT NULL COMMENT 'حذف شود',
  PRIMARY KEY (`RowID`),
  KEY `RoleID` (`RoleID`),
  KEY `UserID` (`UserID`),
  KEY `LetterID` (`LetterID`),
  KEY `IsConfirm` (`IsConfirm`),
  KEY `IsDelete` (`IsDelete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'اقدامات نامه' ;

-- --------------------------------------------------------

--
-- Table structure for table `oa_letter_signature` = هنگام استفاده از قفل سخت افزاری برای امضای نامه یک رکورد به این جدول اضافه میشود
--

CREATE TABLE IF NOT EXISTS `oa_letter_signature` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL COMMENT 'کد مشخصه کاربر => oa_users.RowID',
  `PublicKey` longtext COLLATE utf8_persian_ci NOT NULL COMMENT 'کلید عمومی که براساس توکن سخت افزاری مشخص میشود',
  `DocID` int(11) NOT NULL COMMENT 'کد مشخصه سند => oa_document.RowID',
  `LetterID` int(11) NOT NULL COMMENT 'کد مشخصه نامه => oa_letter.RowID',
  `Signature` longtext CHARACTER SET utf8 NOT NULL COMMENT 'اطلاعات نامه که با توکن فرد کد شده است - جهت بررسی',
  `SignDate` datetime NOT NULL COMMENT 'تاریخ امضا',
  `Content` longtext COLLATE utf8_persian_ci COMMENT 'متن نامه',
  `Md5Content` varchar(256) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'md5 متن نامه',
  PRIMARY KEY (`RowID`),
  UNIQUE KEY `DocID` (`DocID`,`LetterID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=141 COMMENT 'هنگام استفاده از قفل سخت افزاری برای امضای نامه یک رکورد به این جدول اضافه میشود';

-- --------------------------------------------------------

--
-- Table structure for table `oa_letter_signers` = پس از تایین امضا برای نامه رکورد مربوطه در این جدول درج میشود که حاوی مشخصات امضا میباشد
--

CREATE TABLE IF NOT EXISTS `oa_letter_signers` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `LetterID` int(11) NOT NULL COMMENT 'شماره نامه => oa_letter.RowID',
  `RoleID` int(11) DEFAULT NULL COMMENT 'شماره سمت => oa_depts_roles.RowID',
  `UserID` int(11) DEFAULT NULL COMMENT 'شماره کاربری => oa_users.RowID',
  `IsSigned` int(1) DEFAULT '0' COMMENT 'امضا شده یانه',
  `IsAssist` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'از طرف نایب امضا شده یانه',
  `AssistUserID` int(11) NOT NULL DEFAULT '0' COMMENT 'در صورتی که از طرف نایب امضا شده باشد کد کاربری نایب => oa_users.RowID',
  `SignDate` date DEFAULT NULL COMMENT 'تاریخ امضا',
  `CustomSign` varchar(700) COLLATE utf8_persian_ci NOT NULL COMMENT '
  امضای پیشفرض که در قسمت تعریف سمت تب امضا برای هر سمت قابل تعریف است
  اگر امضای پیشفرض تایین نشود از نام سمت سازمانی استفاده میشود  
  ',
  `SignType` int(11) NOT NULL DEFAULT '0' COMMENT 'این فیلد مشخص میکند در فیلد قبلی امضای پیشفرض تایین شده یانه',
  `Order` int(11) NOT NULL COMMENT 'ترتيب امضا',
  PRIMARY KEY (`RowID`),
  KEY `LetterID` (`LetterID`),
  KEY `RoleID` (`RoleID`),
  KEY `UserID` (`UserID`),
  KEY `AssistUserID` (`AssistUserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'پس از تایین امضا برای نامه رکورد مربوطه در این جدول درج میشود که حاوی مشخصات امضا میباشد' ;

-- --------------------------------------------------------

--
-- Table structure for table `oa_letter_structure` = به ازای هربار انتقال یک مکاتبه به سیستم ارشیو یک رکورد در این جدول درج میکردد که نشان دهنده بایگانی مقصد است
--

CREATE TABLE IF NOT EXISTS `oa_letter_structure` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `DocID` int(11) NOT NULL COMMENT 'شماره سند => oa_document.RowID',
  `StructID` int(11) NOT NULL COMMENT 'شماره بایگانی که در بخش اسناد تعریف شده => dm_sructure.RowID',
  PRIMARY KEY (`RowID`),
  UNIQUE KEY `DocID` (`DocID`,`StructID`),
  KEY `DocID_2` (`DocID`,`StructID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=76 COMMENT 'به ازای هربار انتقال یک مکاتبه به سیستم ارشیو یک رکورد در این جدول درج میکردد که نشان دهنده بایگانی مقصد است';

-- --------------------------------------------------------

--
-- Table structure for table `oa_letter_tele_message` = جدول رابطه نامه و پیام های صوتی نامه
--

CREATE TABLE IF NOT EXISTS `oa_letter_tele_message` (
`RowID` int(11) NOT NULL COMMENT 'primary key',
  `LetterID` int(11) DEFAULT NULL COMMENT 'foreigner key for oa_letter table',
  `MessageID` int(11) DEFAULT NULL COMMENT 'foreigner key for oa_tele_message table',
  `InsertDate` date NOT NULL COMMENT 'date of insertion'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci COMMENT 'جدول رابطه نامه و پیام های صوتی نامه';

-- --------------------------------------------------------

--
-- Table structure for table `oa_letter_template` = الگوهای نامه که در قسمت مدیریت تعریف میشوند
--

CREATE TABLE IF NOT EXISTS `oa_letter_template` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(100) COLLATE utf8_persian_ci NOT NULL COMMENT 'عنوان',
  `Comments` varchar(255) COLLATE utf8_persian_ci NOT NULL COMMENT 'شرح',
  `Content` longtext COLLATE utf8_persian_ci NOT NULL COMMENT 'متن الگو',
  `SecID` int(11) NOT NULL COMMENT 'شماره دبیرخانه => oa_secretariat.RowID',
  `PrintTemplateID` int(11) NOT NULL COMMENT 'شماره الگوی چاپ => oa_print_template.RowID',
  `Subject` varchar(100) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'موضوع',
  `Note` varchar(200) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'چکیده',
  `titles` varchar(256) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'عناوین الگو',
  `copies` varchar(256) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'رونوشت های الگو',
  `userRole` text COLLATE utf8_persian_ci COMMENT '',
  `CategoryID` INT( 10 ) NOT NULL COMMENT 'شماره گروه الگو => oa_letter_template_category.RowID',
  `order` int(11) NOT NULL COMMENT 'شماره ترتیب',
  PRIMARY KEY (`RowID`),
  KEY `titles` (`titles`(255),`copies`(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'الگوهای نامه که در قسمت مدیریت تعریف میشوند' ;

-- --------------------------------------------------------

--
-- Table structure for table `oa_letter_template_dept` = واحدهای مجاز به دسترسی الگوها
--

CREATE TABLE IF NOT EXISTS `oa_letter_template_dept` (
  `LetterTemplateID` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'شماره شناسایی الگوی نامه => oa_letter_template.RowID',
  `DeptID` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'شماره واحد => oa_depts_roles.RowID',
  `HasSubDept` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'شامل زیر واحد است یا خیر',
  PRIMARY KEY (`LetterTemplateID`,`DeptID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT 'واحدهای مجاز به دسترسی الگوها';

-- --------------------------------------------------------

--
-- Table structure for table `oa_letter_template_perms` = گروه های مجاز به دسترسی الگوها
--

CREATE TABLE IF NOT EXISTS `oa_letter_template_perms` (
  `LetterTemplateID` int(11) NOT NULL DEFAULT '-1' COMMENT 'شماره شناسایی الگوی نامه => oa_letter_template.RowID',
  `AccessGroupID` int(11) NOT NULL DEFAULT '-1' COMMENT 'شماره گروه دسترسی => oa_user_group.RowID',
  PRIMARY KEY (`LetterTemplateID`,`AccessGroupID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci COMMENT 'گروه های مجاز به دسترسی الگوها';

-- --------------------------------------------------------

--
-- Table structure for table `oa_letter_template_sec` = دبیرخانه های مجاز به دسترسی الگوها
--

CREATE TABLE IF NOT EXISTS `oa_letter_template_sec` (
  `LetterTemplateID` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'شماره شناسایی الگوی نامه => oa_letter_template.RowID',
  `SecID` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'شماره دبیرخانه  => oa_secretariat.RowID',
  PRIMARY KEY (`LetterTemplateID`,`SecID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT ' دبیرخانه های مجاز به دسترسی الگوها';

-- --------------------------------------------------------

--
-- Table structure for table `oa_letter_template_settings`
--

CREATE TABLE IF NOT EXISTS `oa_letter_template_settings` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `TemplateID` int(11) DEFAULT NULL COMMENT 'شماره الگوی نامه => oa_letter_template.RowID',
  `RoleID` int(11) DEFAULT NULL COMMENT 'شماره سمت => oa_depts_roles.RowID',
  `UserID` int(11) DEFAULT NULL COMMENT 'شماره کاربری => oa_users.RowID',
  `view` int(11) DEFAULT NULL COMMENT 'mmeaqr',
  `order` int(11) DEFAULT NULL COMMENT 'ترتیب',
  `type` int(1) DEFAULT NULL COMMENT '0=letter- 1=perletter',
  PRIMARY KEY (`RowID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'جهت بررسی' ;

-- --------------------------------------------------------

--
-- Table structure for table `oa_letter_template_category`
--

CREATE TABLE `oa_letter_template_category` (
  `RowID` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'کد مشخصه گروه' ,
  `CategoryName` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'نام گروه',
  `SecID` int(11) NOT NULL COMMENT 'شماره دبیرخانه => oa_secretariat.RowID',
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'گروه های الگوه های نامه' ;

-- --------------------------------------------------------

--
-- Table structure for table `oa_log` = سوابق تمام اعمال انجام شده در سیستم
--

CREATE TABLE IF NOT EXISTS `oa_log` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'کد مشخصه',
  `Category` int(11) NOT NULL COMMENT 'همه مقادیر صفر است - جهت بررسی',
  `Msg` varchar(110) COLLATE utf8_persian_ci NOT NULL COMMENT 'متن لاگ',
  `LogDate` datetime NOT NULL COMMENT 'تاریخ لاگ',
  `IP` varchar(21) COLLATE utf8_persian_ci NOT NULL COMMENT 'ای پی',
  `UserID` int(11) NOT NULL COMMENT 'شماره کاربری => oa_users.RowID',
  `MainIP` varchar(20) COLLATE utf8_persian_ci NOT NULL DEFAULT '0.0.0.0' COMMENT 'ای پی سیستمی',
  `msgid` int(11) NOT NULL DEFAULT '-1' COMMENT 'کد عملیات که به یک آرایه ذخیره شده در فایل اشاره میکند',
  `RoleID` tinyint(4) DEFAULT NULL COMMENT 'حذف شده',
  PRIMARY KEY (`RowID`),
  KEY `UserID` (`UserID`),
  KEY `msgid` (`msgid`),
  KEY `LogDate` (`LogDate`),
  KEY `RoleID` (`RoleID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'سوابق تمام اعمال انجام شده در سیستم' ;

-- --------------------------------------------------------

--
-- Table structure for table `oa_logtables` = اسامی جداول مربوط به ثبت وقایع سال های گذشته
--

CREATE TABLE IF NOT EXISTS `oa_logtables` (
  `Year` int(4) NOT NULL AUTO_INCREMENT,
  `logTableName` varchar(100) NOT NULL COMMENT 'نام جدول oa_log برای ان سال خاص',
  `doclogTableName` varchar(100) NOT NULL COMMENT 'نام جدول oa_doc_log برای ان سال خاص',
  `StartDocID` int(13) NOT NULL COMMENT 'شماره ای پرونده ای که جدول با ان اغاز می شود',
  PRIMARY KEY (`Year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT '
 به دلیل بالا رفتن حجم جداول
 oa_log_url  و oa_log 
 با استفاده از این جدول  میتوانیم به ازای هر سال
جداول جداگانه ای برای این منظور داشته باشیم';

-- --------------------------------------------------------

--
-- Table structure for table `oa_log_messages`
--

CREATE TABLE IF NOT EXISTS `oa_log_messages` (
  `RowID` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Log identity',
  `msg` varchar(100) COLLATE utf8_persian_ci NOT NULL COMMENT 'Log message',
  PRIMARY KEY (`RowID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'خالی است - جهت بررسی';

-- --------------------------------------------------------

--
-- Table structure for table `oa_log_url` = آدرس اجرا شده به ازای هر لاگ
--

CREATE TABLE IF NOT EXISTS `oa_log_url` (
  `RowID` int(11) NOT NULL,
  `URL` text COLLATE utf8_persian_ci COMMENT 'متن آدرس و پارامتر های ارسال شده از طرف سرویس گیرنده',
  PRIMARY KEY (`RowID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci COMMENT 'آدرس اجرا شده به ازای هر لاگ';

-- --------------------------------------------------------

--
-- Table structure for table `oa_maildoc`
--

CREATE TABLE IF NOT EXISTS `oa_maildoc` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `DocID` int(11) DEFAULT NULL,
  `MainLetterCode` varchar(20) COLLATE utf8_persian_ci DEFAULT NULL,
  `MainLetterDate` date DEFAULT NULL,
  `MainLetterTime` time NOT NULL,
  `SecID` int(11) DEFAULT NULL,
  `LetterType` int(11) DEFAULT NULL,
  `SendRecID` int(11) DEFAULT NULL,
  `OrigType` tinyint(4) DEFAULT '0',
  `TemplateID` int(11) DEFAULT NULL,
  `IsSigned` int(1) DEFAULT NULL,
  `WhoPrintUserID` int(11) NOT NULL,
  `WhoPrintRoleID` int(11) DEFAULT NULL,
  `PrintTemplateID` int(11) NOT NULL,
  `RegUserID` int(11) DEFAULT NULL,
  `RegRoleID` int(11) DEFAULT NULL,
  `RegDate` date DEFAULT NULL,
  `RegCode` int(11) DEFAULT NULL,
  `Prefix` varchar(7) COLLATE utf8_persian_ci DEFAULT NULL,
  `Postfix` varchar(7) COLLATE utf8_persian_ci DEFAULT NULL,
  `InSecInbox` tinyint(1) NOT NULL DEFAULT '0',
  `LetterStatus` tinyint(4) NOT NULL DEFAULT '0',
  `FlowType` tinyint(4) DEFAULT '0',
  `MailID` int(11) NOT NULL,
  `MailToken` varchar(256) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'email Token ',
  `IsDeleted` int(1) NOT NULL DEFAULT '0' COMMENT 'deleted(1) or notDeleted(0)',
  `InCartable` int(1) NOT NULL DEFAULT '0' COMMENT 'if moved to carteble (1) or notMoved (0)',
  `MailOrder` tinyint(4) DEFAULT NULL,
  `fromMail` varchar(50) COLLATE utf8_persian_ci DEFAULT NULL,
  `recNameinSender` varchar(100) COLLATE utf8_persian_ci NOT NULL 'نام تعریف شده برای گیرنده سمت فرستنده',
  `recCodeinSender` varchar(100) COLLATE utf8_persian_ci NOT NULL 'کد تعریف شده برای گیرنده سمت فرستنده که در رسید ای سی ای استفاده می شود',
  `GUIDSender` varchar(150) COLLATE utf8_persian_ci NOT NULL,
  `IsSendReceipt` tinyint(1) NOT NULL,
  `MailType` int(1) NOT NULL COMMENT ' 1=ece - 2=goverment - 3=general - 4=email - 5=EFAX - 6=FAX',
  `faxType` TINYINT NOT NULL COMMENT 'if mailtype=6 then 0=secFax, 1=personalFax else this field is worthless',
  PRIMARY KEY (`RowID`),
  UNIQUE KEY `RowID` (`RowID`),
  KEY `DocID` (`DocID`),
  KEY `WhoPrintUserID` (`WhoPrintUserID`),
  KEY `WhoPrintRoleID` (`WhoPrintRoleID`),
  KEY `SecID` (`SecID`),
  KEY `RegCodeIndex` (`RegCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 'جهت بررسی';


-- --------------------------------------------------------

--
-- Table structure for table `oa_mainmenu_category` = ایتم های منوی اصلی اتوماسیون
--

CREATE TABLE IF NOT EXISTS `oa_mainmenu_category` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `EnTitle` varchar(20) COLLATE utf8_persian_ci NOT NULL COMMENT 'نام لاتین',
  `Title` varchar(20) COLLATE utf8_persian_ci NOT NULL COMMENT 'عنوان فارسی',
  `Image` varchar(40) COLLATE utf8_persian_ci NOT NULL COMMENT 'تصویر',
  `Order` int(11) NOT NULL COMMENT 'ترتیب',
  PRIMARY KEY (`RowID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'ایتم های منوی اصلی اتوماسیون' ;

-- --------------------------------------------------------

--
-- Table structure for table `oa_mainmenu_items` = آیتم های موجود در هر بخش منوی اصلی اتوماسیون
--

CREATE TABLE IF NOT EXISTS `oa_mainmenu_items` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `EnTitle` varchar(30) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'نام کلاسی که باید اجرا شود => oa_access_point.AccessPoint',
  `Title` varchar(30) COLLATE utf8_persian_ci NOT NULL COMMENT 'نام فارسی منو',
  `Image` varchar(50) COLLATE utf8_persian_ci NOT NULL COMMENT 'عکس',
  `URL` varchar(100) COLLATE utf8_persian_ci NOT NULL COMMENT 'آدرس داخلی منو در اتوماسیون',
  `ItemID` varchar(30) COLLATE utf8_persian_ci NOT NULL COMMENT 'نام ماژولی که هنگام فراخوانی گت استفاده میشود',
  `MainMenuCatID` int(11) NOT NULL COMMENT 'کد شخصه منوی پدر => oa_mainmenu_category.RowID',
  `Order` int(11) DEFAULT NULL COMMENT 'ترتیب',
  `ServerAddress` varchar(256) COLLATE utf8_persian_ci NOT NULL DEFAULT 'current' COMMENT 'location of responder module',
  PRIMARY KEY (`RowID`),
  KEY `MainMenuCatID` (`MainMenuCatID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'آیتم های موجود در هر بخش منوی اصلی اتوماسیون';

-- --------------------------------------------------------

--
-- Table structure for table `oa_meeting`
--

CREATE TABLE IF NOT EXISTS `oa_meeting` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) DEFAULT NULL COMMENT 'شماره کاربر => oa_users.RowID',
  `DocID` int(11) DEFAULT NULL COMMENT 'شماره پرونده ',
  `ReferID` int(11) NOT NULL DEFAULT '-1' COMMENT 'شماره ارجاع',
  `SetDate` date DEFAULT NULL COMMENT 'تاریخ شروع',
  `TargetDate` datetime DEFAULT NULL COMMENT 'تاریخ پایان',
  `Notes` varchar(100) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'شرح',
  `TargetUserID` int(11) DEFAULT NULL COMMENT 'کاربرمقصد',
  PRIMARY KEY (`RowID`),
  UNIQUE KEY `userdoc` (`UserID`,`DocID`,`ReferID`),
  KEY `DocID` (`DocID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'در بانک 161 وجود ندارد - جهت بررسی' ;

-- --------------------------------------------------------

--
-- Table structure for table `oa_option_filter` = هنگام تعریف نیابت در قسمت مدیریت و یا تعریف عملیات خودکار در قسمت تنظیمات یک رکورد در این جدول درج میشود
--

CREATE TABLE IF NOT EXISTS `oa_option_filter` (
  `RowID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `SavedSelectID` int(10) unsigned NOT NULL COMMENT 'کد مشخصه فیلتر انتخاب شده => oa_per_search_rep.RowID',
  `Fdate` datetime DEFAULT NULL COMMENT 'تاریخ شروع نیابت',
  `Tdate` datetime DEFAULT NULL COMMENT 'تاریخ پایان نیابت',
  `ActionType` int(11) DEFAULT NULL COMMENT '
  نوع عملیات که هنگام تعریف عملیات خودکار مشخص میشود و هنگام تفریف نیابت این فیلد 4 میشود و قابل تغییر نمیباشد
  0 = حذف
  1 = ارجاع خودکار
  2 = در دست اقدام
  3 = بایگانی
  4 = نیابت
  5 = امضاء خودکار
  ',
  `Description` varchar(400) DEFAULT NULL COMMENT 'توضیحات',
  `AutoReferUid` int(10) unsigned DEFAULT '0' COMMENT 'if ActionType = 1 then شماره کاربری کاربر ارجاع گیرنده خودکار => oa_depts_roles.RowID else 0',
  `AutoReferRid` int(10) unsigned DEFAULT '0' COMMENT 'if ActionType = 1 then شماره سمت کاربر ارجاع گیرنده خودکار => oa_users.RowID else 0',
  `AssistUid` int(10) unsigned DEFAULT '0' COMMENT 'if ActionType = 4 then شماره کاربری نایب => oa_users.RowID else 0',
  `FolderID` int(11) DEFAULT '0' COMMENT 'if ActionType = 3 then شماره پوشه مشخص شده برای بایگانی خودکار => oa_per_archive_folder.RowID else 0',
  `UserID` int(10) unsigned DEFAULT NULL COMMENT 'شماره کاربر نیابت دهنده یا ایجاد کننده عملیات خودکار => oa_users.RowID',
  `RoleID` int(10) unsigned DEFAULT NULL COMMENT 'شماره سمت نیابت دهنده یا ایجاد کننده عملیات خودکار => oa_depts_roles.RowID',
  `GetAllAssist` int(10) unsigned DEFAULT '1' COMMENT 'جهت بررسی',
  `updateAssist` tinyint(4) DEFAULT '0' COMMENT 'جهت بررسی',
  `deleteAfterAutoRefer` tinyint(4) DEFAULT '1' COMMENT 'نامه پس از ارجاع خودکار حذف شود یاخیر ',
  `AutoReferDesc` text CHARACTER SET utf8 COLLATE utf8_persian_ci COMMENT 'توضیحات مربوط به ارجاع خودکار',
  `IsEnable` tinyint(2) NOT NULL DEFAULT '1' COMMENT 'فعال - جهت بررسی',
  `IsActive` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=active 0=noactive - جهت بررسی',
  `assistPerms` varchar(500) DEFAULT NULL COMMENT 'شناسه دسترسی هایی که نایب از نیابت دهنده ندارد',
  `customSign` tinyint(2) NOT NULL DEFAULT '1' COMMENT 'در صورتی که نوع عملیات امضای خودکار است این فیلد مشخص میکند که از امضای پیشفرض استفاده شود یانه ',
  PRIMARY KEY (`RowID`),
  KEY `AssistUid` (`AssistUid`),
  KEY `UserID` (`UserID`),
  KEY `IsEnable` (`IsEnable`),
  KEY `IsActive` (`IsActive`),
  KEY `customSign` (`customSign`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT '
هنگام تعریف نیابت در قسمت مدیریت و یا تعریف عملیات خودکار در قسمت تنظیمات یک رکورد در این جدول درج میشود';

-- --------------------------------------------------------

--
-- Table structure for table `oa_per_access` = طرف های مکاتبات خاص تعریف شده برای هر سمت در چارت سازمانی
--

CREATE TABLE IF NOT EXISTS `oa_per_access` (
  `RowID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `RoleID` int(11) NOT NULL COMMENT 'شماره سمت صاحب دسترسی => oa_depts_roles.RowID',
  `SourceID` int(11) NOT NULL COMMENT 'شماره سمت یا واحد طرف مکاتبات خاص => oa_depts_roles.RowID',
  `SourceType` int(11) NOT NULL COMMENT '
  نوع طرف مکاتبات
  1 = سمت
  2 = واحد
  ',
  `AccessType` tinyint(4) NOT NULL COMMENT '
  نوع دسترسی
  ارجاع = 0
  1 = دبیرخانه
  ',
  `HasSubUnit` tinyint(4) NOT NULL COMMENT '
  مشخص میکند که زیرمجموعه های طرف مکاتبه مشخص شده هم شامل این دسترسی هستند یا نه
  ',
  `referType` tinyint(1) NOT NULL COMMENT '
  نوع دسترسی
  0 = مشخص نشده
  1 = یک طرفه
  2 = دوطرفه
  هنگامی که دسترسی دوطرفه تعریف شود طرف مکاتبات تعیین شده نیز میتواند به صاحب دسترسی ارجاع داشته باشد
  '
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT 'طرف های مکاتبات خاص تعریف شده برای هر سمت در چارت سازمانی -دسترسی ارجاعات و دسترسی مکاتبات کاربر';

-- --------------------------------------------------------

--
-- Table structure for table `oa_per_archive` = هنگام انتقال یک سند به بایگانی شخصی رکورد متناظر در این جدول درج میشود
--

CREATE TABLE IF NOT EXISTS `oa_per_archive` (
  `RowID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ReferID` int(10) unsigned NOT NULL COMMENT 'شماره ارجاع سند بایگانی شده',
  `FolderID` int(10) unsigned NOT NULL COMMENT 'شماره پوشه => oa_per_archive_folder.RowID',
  PRIMARY KEY (`RowID`),
  KEY `ReferID` (`ReferID`),
  KEY `FolderID` (`FolderID`),
  KEY `ReferID_2` (`ReferID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT 'هنگام انتقال یک سند به بایگانی شخصی رکورد متناظر در این جدول درج میشود' ;

-- --------------------------------------------------------

--
-- Table structure for table `oa_per_archive_copy`
--

CREATE TABLE IF NOT EXISTS `oa_per_archive_copy` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `FolderID` int(11) NOT NULL COMMENT 'شماره پوشه',
  `Title` varchar(50) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'عنوان',
  `Comments` varchar(100) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'شرح',
  `FromRoleID` int(11) DEFAULT NULL COMMENT 'سمت مبدا',
  `FromUserID` int(11) DEFAULT NULL COMMENT 'کاربر مبدا',
  `ParentID` int(11) DEFAULT NULL COMMENT 'پوشه پدر',
  `ToRoleID` int(11) NOT NULL COMMENT 'سمت مقصد',
  `ToUserID` int(11) NOT NULL COMMENT 'کاربر مقصد',
  `status` int(2) NOT NULL COMMENT '0=dont copy - 1=copy - 2= user cancel',
  PRIMARY KEY (`RowID`),
  KEY `RoleID` (`FromRoleID`),
  KEY `UserID` (`FromUserID`),
  KEY `ParentID` (`ParentID`),
  KEY `ToRoleID` (`ToRoleID`),
  KEY `ToUserID` (`ToUserID`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'بدون استفاده - جهت بررسی' ;

-- --------------------------------------------------------

--
-- Table structure for table `oa_per_archive_folder` = پوشه های آرشیو شخصی
--

CREATE TABLE IF NOT EXISTS `oa_per_archive_folder` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(50) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'عنوان پوشه',
  `Comments` varchar(100) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'شرح',
  `RoleID` int(11) DEFAULT NULL COMMENT 'سمت => oa_depts_roles.RowID',
  `UserID` int(11) DEFAULT NULL COMMENT 'شماره کاربر => oa_users.RowID',
  `ParentID` int(11) DEFAULT NULL COMMENT 'شماره پوشه پدر => oa_per_archive_folder.RowID',
  `tempRowID` int(11) NOT NULL COMMENT 'حذف شده',
  PRIMARY KEY (`RowID`),
  KEY `RoleID` (`RoleID`),
  KEY `UserID` (`UserID`),
  KEY `ParentID` (`ParentID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'پوشه های آرشیو شخصی' ;

-- --------------------------------------------------------

--
-- Table structure for table `oa_per_dept_participant`
--

CREATE TABLE IF NOT EXISTS `oa_per_dept_participant` (
  `FromRoleID` int(11) NOT NULL DEFAULT '0' COMMENT 'سمت مبدا',
  `DeptID` int(11) NOT NULL DEFAULT '0' COMMENT 'شماره واحد',
  `SecAccess` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'mmeaqr',
  `SuccessorAccess` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'mmeaqr',
  PRIMARY KEY (`FromRoleID`,`DeptID`,`SecAccess`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci COMMENT 'حذف شود';

-- --------------------------------------------------------

--
-- Table structure for table `oa_per_doc_note` = یادداشت های شخصی برروی یک نامه به ازای هر کاربر
--

CREATE TABLE IF NOT EXISTS `oa_per_doc_note` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL COMMENT 'شماره کاربر => oa_users.RowID',
  `DocID` int(11) NOT NULL COMMENT 'شماره پرونده => oa_document.RowID',
  `Notes` varchar(255) COLLATE utf8_persian_ci NOT NULL COMMENT 'شرح',
  PRIMARY KEY (`RowID`),
  KEY `UserID` (`UserID`),
  KEY `DocID` (`DocID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'یادداشت های شخصی برروی یک نامه به ازای هر کاربر';

-- --------------------------------------------------------

--
-- Table structure for table `oa_per_doc_title_group` = گروه های عنوان مربوط به هر کاربر که در قسمت تنظیمات قابل تعریف  هستند
--

CREATE TABLE IF NOT EXISTS `oa_per_doc_title_group` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `GroupTitle` varchar(70) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'نام گروه',
  `UserID` int(11) DEFAULT NULL COMMENT 'شماره کاربری => oa_users.RowID',
  PRIMARY KEY (`RowID`),
  UNIQUE KEY `RowID` (`RowID`),
  KEY `UserID` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'گروه های عنوان مربوط به هر کاربر که در قسمت تنظیمات قابل تعریف  هستند' ;

-- --------------------------------------------------------

--
-- Table structure for table `oa_per_doc_title_group_detail` = عناوین و رونوشت های تعریف شده در گروه های عنوان در بخش تنظیمات
--

CREATE TABLE IF NOT EXISTS `oa_per_doc_title_group_detail` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(100) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'عنوان',
  `IsCC` tinyint(1) DEFAULT NULL COMMENT '
  رونوشت است یا خیر
  0 = عنوان
  1 = رونوشت
  ',
  `DocTitleGroupID` int(11) DEFAULT NULL COMMENT 'کد مشخصه گروه عنوان => oa_per_doc_title_group.RowID',
  `UserID` int(11) NOT NULL DEFAULT '-1' COMMENT 'if RecieverType = 1 کد کاربری => oa_users.RowID.RowID',
  `RoleID` int(11) NOT NULL DEFAULT '-1' COMMENT 'if RecieverType = 1 کد مشخصه سمت => oa_depts_roles.RowID',
  `ReceiverType` tinyint(4) NOT NULL DEFAULT '-1' COMMENT '
  نوع گیرنده
  0 = خارج سازمان
  1 = دبیرخانه سازمان مرکزی آستان قدس رضوی
  2 = بین دبیرخانه ای
  ',
  `ReceiverSecID` int(11) NOT NULL DEFAULT '-1' COMMENT 'if RecieverType = 2 then کد مشخصه دبیرخانه گیرنده => oa_secretariat.RowID',
  `SecOutPersonID` int(11) NOT NULL DEFAULT '-1' COMMENT 'if RecieverType = 0 then کد مشخصه طرف مکاتبات خاص => oa_sec_outpersons.RowID',
  `ServerOutID` int(11) NOT NULL DEFAULT '-1' COMMENT 'شماره سرور مقصد - جهت بررسی',
  PRIMARY KEY (`RowID`),
  KEY `Title` (`Title`),
  KEY `IsCC` (`IsCC`),
  KEY `DocTitleGroupID` (`DocTitleGroupID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'عناوین و رونوشت های تعریف شده در گروه های عنوان در بخش تنظیمات' ;

-- --------------------------------------------------------

--
-- Table structure for table `oa_per_letter_template` = الگوهای نامه شخصی که هر کاربر در کارتبل خود تعریف میکند
--

CREATE TABLE IF NOT EXISTS `oa_per_letter_template` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(100) COLLATE utf8_persian_ci NOT NULL COMMENT 'عنوان',
  `Comments` varchar(255) COLLATE utf8_persian_ci NOT NULL COMMENT 'شرح',
  `Content` text COLLATE utf8_persian_ci NOT NULL COMMENT 'محتویات نامه',
  `UserID` int(11) NOT NULL COMMENT 'شماره کاربری => oa_users.RowID',
  `PrintTemplateID` int(10) unsigned NOT NULL COMMENT 'شماره الگوی چاپ => dm_templates.RowID',
  `Subject` varchar(100) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'موضوع',
  `Note` varchar(200) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'چکیده',
  `titles` varchar(256) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'عناوین',
  `copies` varchar(256) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'رونوشت ها',
  `order` int(11) NOT NULL COMMENT 'ترتیب',
  PRIMARY KEY (`RowID`),
  KEY `titles` (`titles`(255),`copies`(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'الگوهای نامه شخصی که هر کاربر در کارتبل خود تعریف میکند - در قسمت ایجاد نامه' ;

-- --------------------------------------------------------

--
-- Table structure for table `oa_per_refer_group` = گروه های ارجاع  هرکاربر که در قسمت تنضیمات تعریف میشوند
--

CREATE TABLE IF NOT EXISTS `oa_per_refer_group` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `GroupTitle` varchar(70) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'عنوان گروه',
  `UserID` int(11) DEFAULT NULL COMMENT 'کد مشخصه کاربری ایجاد کننده => oa_users.RowID',
  `ReferModal` tinyint(4) DEFAULT '0' COMMENT '
  نمایش در صفحه ارجاع
  0 = نماش داده نشود
  1 = بصورت آیکن گروه در بالای صفحه ارجاع نمایش داده میشود
  ',
  PRIMARY KEY (`RowID`),
  KEY `UserID` (`UserID`),
  KEY `ReferModal` (`ReferModal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'گروه های ارجاع  هرکاربر که در قسمت تنضیمات تعریف میشوند' ;

-- --------------------------------------------------------

--
-- Table structure for table `oa_per_refer_group_detail` = عناوین تعریف شده در گروه های ارجاع شخصی
--

CREATE TABLE IF NOT EXISTS `oa_per_refer_group_detail` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) DEFAULT NULL COMMENT 'شماره کاربر => oa_users.RowID',
  `RoleID` int(11) DEFAULT NULL COMMENT 'شماره سمت => oa_depts_roles.RowID',
  `ReferGroupID` int(11) DEFAULT NULL COMMENT 'شماره گروه ارجاع oa_per_refer_group.RowID',
  PRIMARY KEY (`RowID`),
  KEY `UserID` (`UserID`),
  KEY `RoleID` (`RoleID`),
  KEY `ReferGroupID` (`ReferGroupID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'عناوین تعریف شده در گروه های ارجاع شخصی' ;

-- --------------------------------------------------------

--
-- Table structure for table `oa_per_refer_note` = رده های  ارجاع تعریف شده برای هر کاربر
--

CREATE TABLE IF NOT EXISTS `oa_per_refer_note` (
  `RowID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Title` varchar(200) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'عنوان رده ارجاع',
  `UserID` int(11) NOT NULL COMMENT 'شماره کاربری => oa_users.RowID',
  PRIMARY KEY (`RowID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'رده های  ارجاع تعریف شده برای هر کاربر' ;

-- --------------------------------------------------------

--
-- Table structure for table `oa_per_reminder` یادآوری ها و ملاقات ها
--

CREATE TABLE IF NOT EXISTS `oa_per_reminder` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) DEFAULT NULL COMMENT 'کد مشخصه کاربر => oa_users.RowID',
  `DocID` int(11) DEFAULT NULL COMMENT 'کد مشخصه پرونده => oa_document.RowID',
  `ReferID` int(11) NOT NULL DEFAULT '-1' COMMENT 'کد مشخصه ارجاع => oa_doc_refer.RowID',
  `SetDate` date DEFAULT NULL COMMENT 'تاریخ ثبت',
  `TargetDate` datetime DEFAULT NULL COMMENT 'تاریخ شروع',
  `EndDate` datetime DEFAULT NULL COMMENT 'تاریخ پایان',
  `Notes` varchar(200) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'شرح',
  `TargetUserID` int(11) DEFAULT NULL COMMENT 'هنگام تعریف ملاقات ،در این فیلد کد کاربری طرف ملاقات فرار میگیرد => oa_users.RowID',
  `IsMeeting` tinyint(1) NOT NULL COMMENT '
  0 = در قسمت مشخصات نامه بصورت ملاقات تعریف شده یا در قسمت یادآوری های کارتابل تعریف شده
  1 = در قسمت تنظیم ملاقات کارتابل تعریف شده
  2 = در قسمت مدیریار تعریف شده
  ',
  `IsDelete` int(11) NOT NULL COMMENT 'حذف شده یا خیر',
  `ِDelReferID` int(11) NOT NULL COMMENT 'همه مقادیر تهی است - جهت بررسی',
  `CalUserID` int(11) NOT NULL COMMENT 'کاربر مدیر یار',
  `Location` varchar(200) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'محل برگزاری جلسه',
  `Description` varchar(255) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'شرح ملاقات',
  `IsAllDayEvent` smallint(6) NOT NULL COMMENT 'اگر این فیلد یک باشد یعنی یادآوری برای تمام روز است و ساعت خاصی ندارد',
  `Color` varchar(200) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'رنگ - جهت بررسی',
  `Done` tinyint(2) NOT NULL DEFAULT '0' COMMENT 'اقدام شده - جهت بررسی',
  PRIMARY KEY (`RowID`),
  UNIQUE KEY `userdoc` (`UserID`,`DocID`,`ReferID`,`IsMeeting`),
  KEY `DocID` (`DocID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT '
   یادآوری های تعریف شده در کارتابل ، ملاقات های تعریف شده در تب مشخصات نامه و موضوعات ایجاد شده در مدیریار';

-- --------------------------------------------------------

--
-- Table structure for table `oa_per_search_rep` = جستجوهای ذخیره شده توسط کاربران
--

CREATE TABLE IF NOT EXISTS `oa_per_search_rep` (
  `RowID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL COMMENT 'نام جستجو',
  `Param` text CHARACTER SET utf8 COMMENT 'پارامتر های جستجو مشخص شده در فرم جستجو',
  `UserID` int(10) unsigned DEFAULT NULL COMMENT 'شماره کاربری ایجاد کننده => oa_users.RowID',
  PRIMARY KEY (`RowID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 COMMENT 'جستجوهای ذخیره شده توسط کاربران';

-- --------------------------------------------------------

--
-- Table structure for table `oa_per_settings` = تنظیمات شخصی کارتابل
--

CREATE TABLE IF NOT EXISTS `oa_per_settings` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL COMMENT 'شماره کاربر => oa_users.RowID',
  `PrintUserID` int(11) NOT NULL DEFAULT '0' COMMENT 'شماره کاربر تهیه کننده پیش نویس => oa_users.RowID',
  `PrintRoleID` int(11) NOT NULL DEFAULT '0' COMMENT 'شماره سمت تهیه کننده پیش نویس => oa_depts_roles.RowID',
  `ParaphUserID` int(11) NOT NULL COMMENT 'کد مشخصه کاربر گیرنده ارجاع پس از پی نوشت',
  `ParaphRoleID` int(11) NOT NULL COMMENT 'کد مشخصه سمت گیرنده ارجاع پس از پی نوشت',
  `DelInbox` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'تنظیمات ارجاع - حذف نامه از كارتابل پس از ارسال ',
  `CloseRefWin` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'تنظیمات ارجاع - بسته شدن پنجره ارجاع پس از ارسال ',
  `MaxRecords` tinyint(4) NOT NULL DEFAULT '21' COMMENT 'تعداد نامه هاي كارتابل در هر صفحه هنگام نمایش ',
  `EndEdit` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'تنظیمات ارجاع - خاتمه ویرایش',
  `HasDefaultSign` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'حذف شده',
  `CustomSign` varchar(700) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'حذف شده',
  `AfterRefer` int(10) unsigned DEFAULT '0' COMMENT '
  عمل بعد از ارجاع
  0 = صفحه جاری
  1 = کارتابل
  2 = نامه های ارسالی
  3 = نامه بعدی
  ',
  `HideDelLetter` int(11) DEFAULT '1' COMMENT 'مخفی نمودن نامه حذف شده - جهت بررسی',
  `SortPerArchive` int(11) DEFAULT '1' COMMENT '
  نحوه مرتب شدن بایگانی شخصی
  1 = حروف الفبا
  2 = کلاسه نامه
  3 = بدون مرتب سازی
  ',
  `ReferModalType` tinyint(4) NOT NULL DEFAULT '1' COMMENT '
  وضعیت صفحه ارجاع
  1 = شرح مشترک برای همه
  2 = شرح اختصاصی برای هر نفر
  ',
  `Template` tinyint(4) NOT NULL DEFAULT '1' COMMENT '
  طرح سیستم
  1 = طرح پیشفرض
  2 = طرح فانتزی
  ',
  `ShowToolbarName` tinyint(1) NOT NULL DEFAULT '0' COMMENT '
  تنظیم نوار ابزار
  1 = نمایش نام کلیدها
  2 = عدم نمایش نام کلیدها
  ',
  `CheckReRefer` int(1) NOT NULL DEFAULT '0' COMMENT 'تنظیمات ارجاع -  نمايش هشدار ارجاع مجدد ',
  `refersListType` int(1) NOT NULL DEFAULT '0' COMMENT '0 alphabet, 1 max refer - جهت بررسی - دوفیلد با همین نام در جدول موجود است',
  `trackStatus` int(11) DEFAULT NULL COMMENT '
  وضعیت پیگیری های خودکار
  0 = نمایش
  1 = عدم نمایش
  2 = نمایش برای اشخاص تایین شده که در جدول 
	oa_per_track_roles ذخیره میشود
  ',
  `DefaultLetterType` tinyint(1) NOT NULL COMMENT '
  پیشقرض نوع نامه
  0 = صادره
  1 = غیر ثبتی
  ',
  `DMSAutoCompleteField` int(1) NOT NULL DEFAULT '0' COMMENT 'فیلدها بصورت خودکار تکمیل شود یاخیر',
  `TrackerUserID` int(11) DEFAULT NULL COMMENT 'کد کاربری پیگیری کننده => oa_users.RowID',
  `TrackerRoleID` int(11) DEFAULT NULL COMMENT 'شماره سمت پیگیری کننده => oa_depts_roles.RowID',
  `TrackerTimeOut` int(11) DEFAULT NULL COMMENT 'مشخص میکند که مهلت پیگیری چند روز پس از ارجاع میباشد',
  `MultiOption` text COLLATE utf8_persian_ci COMMENT 'UI options - جهت پیگیری',
  `DefCategory` text COLLATE utf8_persian_ci COMMENT 'json def category - منوی پیش فرض برای هر سمت به صورت جدا در جیسون ذخیره میشود',
  `ShowTracker` int(1) DEFAULT '1' COMMENT 'تنظیمات ارجاع - نمايش پيگيري كننده',
  `JustTracker` tinyint(2) NOT NULL COMMENT 'if(0) show tracking for tracker and main reciever ;if(1) show track time just for tracker - جهت پیگیری',
  `IP` varchar(256) COLLATE utf8_persian_ci NOT NULL COMMENT 'ای پی که در قسمت تغییر کلمه عبور تایین میشود که کاربر فقط با آن ای پی میتواند وارد شود',
  `CityID` int(11) DEFAULT NULL COMMENT 'موقعیت جغرافیایی - کد شهر',
  `Longitude` float DEFAULT NULL COMMENT 'موقعیت جغرافیایی - طول جغرافیایی',
  `Latitude` float DEFAULT NULL COMMENT 'موقعیت جغرافیایی - عرض جغرافیایی',
  `FaxOptions` text COLLATE utf8_persian_ci COMMENT 'the fax options for per user - جهت بررسی',
  `DelAfterPerArch` tinyint(4) NOT NULL DEFAULT 'حذف نامه پس از بایگانی شخصی',
  `userFont` text COLLATE utf8_persian_ci COMMENT 'فونت های اختصاصی کاربر',
  `roleSort` text COLLATE utf8_persian_ci COMMENT 'ترتیب سمت - جهت بررسی',
  `usessl` tinyint(2) NOT NULL DEFAULT '0' COMMENT '
  استفاده از ssl
  0 = تنها برای ورود
  1 = تمامی سیستم
  ',
  `thumbnailNum` int(11) DEFAULT NULL COMMENT 'جهت بررسی',
  `ReadySignStatus` int(1) DEFAULT NULL COMMENT '0 = show in ready sign cartable - 1= show in cartable - 2= show in cartable & ready sign cartable - جهت بررسی',
  `MainIP` varchar(100) COLLATE utf8_persian_ci NOT NULL COMMENT 'ای پی اصلی - جهت بررسی',
  `countMinAutoExit` MEDIUMINT NOT NULL DEFAULT '-1' COMMENT 'خروج خودکار از سیستم بعد از گذشت این زمان براساس دقیقه',
  `showRoleNameAfterUserFLName` TINYINT( 1 ) NOT NULL DEFAULT '0' COMMENT 'هر کاربر می تواند برای خود در تنظیمات مشخص کند که آیا می خواهد سمت کاربران کنار نام آنها در فیلد های کاربری نمایش داده شود یا نه ',
  PRIMARY KEY (`RowID`),
  KEY `UserID` (`UserID`),
  KEY `CityID` (`CityID`,`Longitude`,`Latitude`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'تنظیمات شخصی کارتابل' ;

-- --------------------------------------------------------

--
-- Table structure for table `oa_per_subjects` = موضوعات آماده تعریف شده توسط کاربران
--

CREATE TABLE IF NOT EXISTS `oa_per_subjects` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL COMMENT 'شماره کاربر',
  `Title` varchar(110) COLLATE utf8_persian_ci NOT NULL COMMENT 'عنوان',
  PRIMARY KEY (`RowID`),
  KEY `UserID` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'موضوعات آماده تعریف شده توسط کاربران';

-- --------------------------------------------------------

--
-- Table structure for table `oa_per_track_roles` = هنگامی که در قسمت تنظیمات بخش وضعیت پیگیری های خودکار گزینه عدم نمایش برای اشخاص زیر انتخاب شود
اشخاص تعیین شده در این جدول قرار میگیرند
--

CREATE TABLE IF NOT EXISTS `oa_per_track_roles` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) DEFAULT NULL COMMENT 'شماره کاربر تعیین کننده پیگیری های خودکار',
  `RoleID` int(11) DEFAULT NULL COMMENT 'شماره سمت',
  PRIMARY KEY (`RowID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT 'هنگامی که در قسمت تنظیمات بخش وضعیت پیگیری های خودکار گزینه عدم نمایش برای اشخاص زیر انتخاب شود
اشخاص تعیین شده در این جدول قرار میگیرند';

-- --------------------------------------------------------

--
-- Table structure for table `oa_php_session` = به ازای هر نشست که با ورود کاربران ایجاد میشود یک رکورد در این جدول درج میشود و با خروج کاربر و از بین رفتن نشست رکورد مربوطه حذف میشود
--

CREATE TABLE IF NOT EXISTS `oa_php_session` (
  `session_id` varchar(32) COLLATE utf8_persian_ci NOT NULL COMMENT 'شماره مشخصه نشست',
  `user_id` varchar(16) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'شماره کاربر => oa_users.RowID',
  `role_id` int(11) NOT NULL COMMENT 'شماره سمت => oa_depts_roles.RowID',
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'تاریخ ایجاد نشست',
  `last_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'تاریخ اخرین ویرایش',
  `session_data` varchar(1000) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'اطلاعات ذخیر شده نشست',
  `IP` varchar(20) COLLATE utf8_persian_ci DEFAULT '0.0.0.0' COMMENT 'ای پی که کاربر با آن وارد شده',
  `MainIP` varchar(20) COLLATE utf8_persian_ci DEFAULT '0.0.0.0' COMMENT 'ای پی واقعی کاربر در صورد استفاده از پراکسی سرور  که در',
  PRIMARY KEY (`session_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci COMMENT 'به ازای هر نشست که با ورود کاربران ایجاد میشود یک رکورد در این جدول درج میشود 
و با خروج کاربر و از بین رفتن نشست رکورد مربوطه حذف میشود';

-- --------------------------------------------------------

--
-- Table structure for table `oa_postman` = پیک های تعریف شده در قسمت تعریف دبیرخانه تب اسامی پیک ها 
--

CREATE TABLE IF NOT EXISTS `oa_postman` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) COLLATE utf8_persian_ci NOT NULL COMMENT 'نام',
  `SecID` int(11) NOT NULL COMMENT 'شماره دبیرخانه',
  PRIMARY KEY (`RowID`),
  KEY `SecID` (`SecID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'پیک های تعریف شده در قسمت تعریف دبیرخانه تب اسامی پیک ها ' ;

-- --------------------------------------------------------

--
-- Table structure for table `oa_postman_req` = درخواست های پیک
--

CREATE TABLE IF NOT EXISTS `oa_postman_req` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL COMMENT 'شماره کاربر درخواست دهنده => oa_users.RowID',
  `Date` datetime NOT NULL COMMENT 'تاریخ درخواست',
  `Notes` varchar(70) COLLATE utf8_persian_ci NOT NULL COMMENT 'شرح',
  `DocID` int(11) NOT NULL COMMENT 'شماره پرونده => oa_document.RowID - جهت بررسی - شماره نامه وارد میشود ولی شماره سند  در بانک درج میشود',
  `IsMain` tinyint(1) NOT NULL COMMENT 'اصل نامه است یانه',
  `IsAttach` tinyint(1) NOT NULL COMMENT 'پیوست است یانه',
  `PostmanID` int(11) DEFAULT NULL COMMENT 'کد مشخصه پیک => oa_postman.RowID',
  `SecID` int(11) NOT NULL COMMENT 'شماره دبیرخانه => oa_secretariat.RowID',
  `Status` tinyint(4) DEFAULT '0' COMMENT 'وضعیت در خواست که نشان میدهد توسط پیک ارسال شده یانه',
  `Year` int(11) DEFAULT NULL COMMENT 'سال ثبت نامه',
  PRIMARY KEY (`RowID`),
  KEY `UserID` (`UserID`),
  KEY `DocID` (`DocID`),
  KEY `PostmanID` (`PostmanID`),
  KEY `SecID` (`SecID`),
  KEY `Date` (`Date`),
  KEY `Year` (`Year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'درخواست های پیک';

-- --------------------------------------------------------

--
-- Table structure for table `oa_print_template` = الگوهای چاپ تعریف شده در قسمت مدیریت
--

CREATE TABLE IF NOT EXISTS `oa_print_template` (
  `RowID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `SecID` int(11) unsigned NOT NULL COMMENT 'شماره دبیرخانه => oa_secretariat.RowID',
  `Title` varchar(100) COLLATE utf8_persian_ci NOT NULL COMMENT 'عنوان الگو',
  `PaperWidth` int(11) unsigned NOT NULL COMMENT 'طول صفحه',
  `PaperHeight` int(11) unsigned NOT NULL COMMENT 'عرض صفحه',
  `HeaderTop` int(11) unsigned NOT NULL COMMENT 'فاصله از بالا هدر',
  `HeaderLeft` int(11) unsigned NOT NULL COMMENT 'فاصله از چپ هدر',
  `HeaderWidth` int(11) unsigned NOT NULL COMMENT 'طول هدر',
  `HeaderHeight` int(11) unsigned NOT NULL COMMENT 'عرض هدر',
  `BodyTop` int(11) unsigned NOT NULL COMMENT 'فاصله از بالای بدنه',
  `BodyLeft` int(11) unsigned NOT NULL COMMENT 'فاصله از چپ متن نامه',
  `BodyWidth` int(11) unsigned NOT NULL COMMENT 'طول متن نامه',
  `BodyHeight` int(11) unsigned NOT NULL COMMENT 'عرض متن نامه',
  `DateTop` int(11) unsigned NOT NULL COMMENT 'فاصله از بالا تاریخ',
  `DateLeft` int(11) unsigned NOT NULL COMMENT 'فاصله از چپ تاریخ',
  `DateFont` varchar(70) COLLATE utf8_persian_ci NOT NULL COMMENT 'فونت تاریخ',
  `CodeTop` int(11) unsigned NOT NULL COMMENT 'فاصله از بالای شماره نامه',
  `CodeLeft` int(11) unsigned NOT NULL COMMENT 'فاصله از چپ شماره نامه',
  `CodeFont` varchar(70) COLLATE utf8_persian_ci NOT NULL COMMENT 'فونت شماره نامه',
  `LogoLeft` int(11) unsigned NOT NULL COMMENT 'فاصله از بالا لوگو',
  `LogoTop` int(11) unsigned NOT NULL COMMENT 'فاصله از چپ لوگو',
  `TitleTop` int(11) unsigned NOT NULL COMMENT 'فاصله از بالا عناوین',
  `TitleFont` varchar(70) COLLATE utf8_persian_ci NOT NULL COMMENT 'فونت عناوین',
  `ContentTop` int(11) unsigned NOT NULL COMMENT 'فاصله از بالای متن نامه',
  `SignTop` int(11) unsigned NOT NULL COMMENT 'فاصله از بالای امضا',
  `SignLeft` int(11) unsigned NOT NULL COMMENT 'فاصله از چپ امضا',
  `SignFont` varchar(70) COLLATE utf8_persian_ci NOT NULL COMMENT 'فونت امضا',
  `SignPicTop` int(11) unsigned NOT NULL COMMENT 'فاصله امضا از بالا',
  `SignPicLeft` int(11) unsigned NOT NULL COMMENT 'فاصله تصویر امضا از چپ',
  `CCTop` int(11) unsigned NOT NULL COMMENT 'فاصله رونوشت ها از بالا',
  `CCLeft` int(11) unsigned NOT NULL COMMENT 'فاصله رونوشت ها از چپ',
  `CCFont` varchar(70) COLLATE utf8_persian_ci NOT NULL COMMENT 'فونت رونوشت ها',
  `Paper` longblob COMMENT 'جهت بررسی',
  `GodTop` int(10) unsigned DEFAULT '0' COMMENT 'فاصله بسمه تعالی از بالا',
  `TitleSize` int(10) unsigned NOT NULL DEFAULT '12' COMMENT 'اندازه عناوین',
  `CCSize` int(10) unsigned NOT NULL DEFAULT '12' COMMENT 'اندازه رونوشت ها',
  `SignSize` int(11) unsigned NOT NULL DEFAULT '12' COMMENT 'اندازه امضا',
  `AttachTop` int(11) unsigned NOT NULL COMMENT 'فاصله پیوست از بالا',
  `AttachLeft` int(11) unsigned NOT NULL COMMENT 'فاصله پیوست از چپ',
  `AttachFont` varchar(70) COLLATE utf8_persian_ci NOT NULL COMMENT 'فونت پیوست',
  `HeaderFile` longblob COMMENT 'فایل هدر',
  `DateSize` int(11) DEFAULT '13' COMMENT 'اندازه فونت تاریخ',
  `CodeSize` int(11) DEFAULT '13' COMMENT 'اندازه فونت شماره',
  `AttachSize` int(11) DEFAULT '13' COMMENT 'اندازه فونت پیوست',
  `bottomMargin` int(11) NOT NULL DEFAULT '50' COMMENT 'فاصله از پایین',
  `lineheight` VARCHAR( 5 ) NOT NULL COMMENT 'فاصله بین خطوط در پیش نمایش چاپ',
  PRIMARY KEY (`RowID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'الگوهای چاپ تعریف شده در قسمت مدیریت' ;

-- --------------------------------------------------------

--
-- Table structure for table `oa_print_template_perms` = مجوز های الگو های چاپ
--

CREATE TABLE IF NOT EXISTS `oa_print_template_perms` (
  `PrintTemplateID` int(10) unsigned NOT NULL DEFAULT '0',
  `AccessID` int(10) unsigned NOT NULL COMMENT 'if PermType is 1 = DeptID, if PermType is 2 = UserID',
  `DetailID` int(10) unsigned NOT NULL COMMENT 'if PermType is 1 = HasSubDept, if PermType is 2 = RoleID',
  `PermType` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 = dept,2 = user',
  PRIMARY KEY (`PrintTemplateID`,`AccessID`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT 'مجوز های الگو های چاپ';


-- --------------------------------------------------------

--
-- Table structure for table `oa_project` = پروژه های تعریف شده در قسمت مدیریت
--

CREATE TABLE IF NOT EXISTS `oa_project` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `SecID` int(11) NOT NULL COMMENT 'دبیرخانه => oa_secretariat.RowID',
  `Title` varchar(70) COLLATE utf8_persian_ci NOT NULL COMMENT 'عنوان پروژه',
  `Comments` varchar(100) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'شرح',
  `ClassCode` varchar(50) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'کد کلاسه',
  `ParentID` int(11) NOT NULL DEFAULT '-1' COMMENT 'شماره پدر oa_project.RowID',
  `OrderID` int(11) NOT NULL DEFAULT '1' COMMENT 'شماره ترتیب',
  `DeptID` int(11) NOT NULL COMMENT 'شماره واحد => oa_depts_roles.RowID',
  `Path` varchar(50) COLLATE utf8_persian_ci NOT NULL COMMENT 'مسیر طی شده از نودهای پدر به سمت این نود',
  PRIMARY KEY (`RowID`),
  KEY `SecID` (`SecID`),
  KEY `ParentID` (`ParentID`),
  KEY `Title` (`Title`(20)),
  KEY `OrderID` (`OrderID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'پروژه های تعریف شده در قسمت مدیریت';

-- --------------------------------------------------------

--
-- Table structure for table `oa_project_coding` = خدمات و مشتریان تعریف شده برای پروژه ها در قسمت مدیریت
--

CREATE TABLE IF NOT EXISTS `oa_project_coding` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `SecID` int(11) NOT NULL COMMENT 'شماره دبیرخانه',
  `customerServiceID` tinyint(2) NOT NULL COMMENT '
  نوع
  1 = مشتری
  2 = سرویس
  ',
  `customerServiceDesc` text CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL COMMENT 'توضیحات',
  `isDeleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'deleted =1 or notdeleted=0',
  PRIMARY KEY (`RowID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 COMMENT 'خدمات و مشتریان تعریف شده برای پروژه ها در قسمت مدیریت' ;

-- --------------------------------------------------------

--
-- Table structure for table `oa_reserved_codes` = شماره های ثبتی رزرو شده در دبیرخانه
--

CREATE TABLE IF NOT EXISTS `oa_reserved_codes` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `ResDate` date NOT NULL COMMENT 'تاریخ رزرو ',
  `SecID` int(11) NOT NULL COMMENT 'شماره دبیرخانه => oa_secretariat.RowID',
  `FromCode` int(11) NOT NULL COMMENT 'از شماره',
  `ToCode` int(11) NOT NULL COMMENT 'تا شماره',
  `NextCode` int(11) NOT NULL COMMENT 'شماره بعدی قابل استفاده در این بازه',
  `Notes` varchar(100) COLLATE utf8_persian_ci NOT NULL COMMENT 'شرح',
  `IsEnable` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'فعال یا غیر فعال',
  `DeptID` int(11) NOT NULL DEFAULT '-1' COMMENT 'شماره واحد سازمانی => oa_depts_roles.RowID',
  `Year` int(4) DEFAULT NULL COMMENT 'سال رزرو',
  PRIMARY KEY (`RowID`),
  KEY `SecID` (`SecID`),
  KEY `ResDate` (`ResDate`),
  KEY `FromCode` (`FromCode`),
  KEY `ToCode` (`ToCode`),
  KEY `IsEnable` (`IsEnable`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'شماره های ثبتی رزرو شده در دبیرخانه' ;

-- --------------------------------------------------------

--
-- Table structure for table `oa_role_perms` = مجوز های تایین شده برای سمت ها در چارت سازمانی
--

CREATE TABLE IF NOT EXISTS `oa_role_perms` (
  `RowID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `RoleID` int(11) unsigned DEFAULT NULL COMMENT 'شماره سمت => oa_depts_roles.RowID',
  `AccessType` char(1) COLLATE utf8_persian_ci DEFAULT NULL COMMENT '
  نوع دسترسی
  d = نامشخص
  y = دارد
  n = ندارد
  ',
  `AccessPointID` int(10) unsigned DEFAULT '0' COMMENT 'شماره منبعی که درسترسی آن به سمت داده میشود => oa_access_point.RowID',
  PRIMARY KEY (`RowID`),
  KEY `RoleID` (`RoleID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'مجوز های تایین شده برای سمت ها در چارت سازمانی' ;

-- --------------------------------------------------------

--
-- Table structure for table `oa_saint_times` = اوقات شرعی
--

CREATE TABLE IF NOT EXISTS `oa_saint_times` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `SaintDate` date NOT NULL COMMENT 'تاریخ اوقات شرعی',
  `AzanSobh` time NOT NULL COMMENT 'اذان صبح',
  `Toloo` time NOT NULL COMMENT 'طلوع خورشید',
  `AzanZohr` time NOT NULL COMMENT 'اذان ظهر',
  `Ghoroob` time NOT NULL COMMENT 'غروب خورشید',
  `AzanMaghreb` time NOT NULL COMMENT 'اذان مغرب',
  `DayWeek` varchar(255) NOT NULL COMMENT 'روز هفته',
  `CityID` int(11) NOT NULL COMMENT 'شماره شهر => oa_city.RowID',
  `Longitude` float NOT NULL COMMENT 'طول جغرافیایی',
  `Latitude` float NOT NULL COMMENT 'عرض جغرافیایی',
  PRIMARY KEY (`RowID`),
  KEY `RowID` (`RowID`,`SaintDate`,`AzanSobh`,`Toloo`,`AzanZohr`,`Ghoroob`,`AzanMaghreb`,`DayWeek`),
  KEY `CityID` (`CityID`,`Longitude`,`Latitude`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1034 COMMENT 'اوقات شرعی' ;

-- --------------------------------------------------------

--
-- Table structure for table `oa_secretariat`
--

CREATE TABLE IF NOT EXISTS `oa_secretariat` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(40) COLLATE utf8_persian_ci NOT NULL COMMENT 'نام دبیرخانه',
  `Prefix` varchar(10) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'پیشوند',
  `Postfix` varchar(10) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'پسوند',
  `NextCode` int(11) NOT NULL COMMENT 'شماره ثبتی بعدی',
  `ResetCode` int(11) DEFAULT NULL COMMENT 'شماره  ثبتی ابتدایی',
  `PrintUserID` int(11) DEFAULT NULL COMMENT 'شماره کاربری تهیه کننده نسخه چاپی => oa_users.RowID',
  `PrintRoleID` int(11) DEFAULT NULL COMMENT 'شماره سمت تهیه کننده نسخه چاپی => oa_depts_roles.RowID',
  `AttachTemplatePath` varchar(500) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'جهت بررسی',
  `ParentID` int(11) NOT NULL DEFAULT '-1' COMMENT 'دبیرخانه پدر => oa_secretariat.RowID',
  `EmailUser` text COLLATE utf8_persian_ci COMMENT 'نام کاربری ایمیل',
  `EmailPass` text COLLATE utf8_persian_ci COMMENT 'کلمه عبور ایمیل',
  `MailService` text COLLATE utf8_persian_ci COMMENT 'مشخصات پست های الکترونیک بصورت ساختار جیسون در این فیلد ذخیره میشند',
  `InPort` text COLLATE utf8_persian_ci COMMENT 'پورت ورودی ',
  `OutPort` text COLLATE utf8_persian_ci COMMENT 'پورت خروجی',
  `InServer` text CHARACTER SET utf8 COMMENT 'سرویس دهنده ورودی',
  `OutServer` text CHARACTER SET utf8 COMMENT 'سرویس دهنده خروجی',
  `LastEmailDate` text COLLATE utf8_persian_ci COMMENT 'اخرین تاریخ ایمیل',
  `OnlySelectSbj` tinyint(1) DEFAULT NULL COMMENT '
  موضوع نامه
  0 = جدید ثبت شود
  1 = انتخابی باشد
  ',
  `OnlySelectOutPer` tinyint(1) DEFAULT NULL COMMENT '
  طرف مکاتبات
  0 = جدید ثبت شود
  1 = انتخابی باشد
  ',
  `faxModemName` varchar(100) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'نام مودم فکس',
  `faxSendMode` enum('0','1') COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'نحوه ارسال فکس',
  `faxReciveMode` enum('0','1','2') COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'نحوه دریافت فکس',
  `UserCount` int(11) DEFAULT NULL COMMENT 'تعداد كاربران دبيرخانه',
  `MD5UserCount` varchar(100) CHARACTER SET utf8 COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'هش كد تعداد كاربران دبيرخانه',
  `LetterShowInDept` int(1) NOT NULL DEFAULT '0' COMMENT '0 signer, 1 creator, 2 all refer',
  `LetterHeader` tinytext COLLATE utf8_persian_ci COMMENT 'letter header for all sec letter',
  `FaxAddress` varchar(256) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'آدرس فاکس',
  `FaxUsername` varchar(256) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'نام کاربری فکس',
  `FaxPass` varchar(256) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'کلمه عبور فکس',
  `FaxTimeout` int(11) DEFAULT NULL COMMENT 'تایم اوت فکس',
  `CityID` int(11) DEFAULT NULL COMMENT 'کد شهر',
  `Longitude` float DEFAULT NULL COMMENT 'طول جغرافیایی',
  `Latitude` float DEFAULT NULL COMMENT 'عرض جغرافیایی',
  `wsFont` varchar(50) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'فونت بین سروری',
  `wsFontSize` int(11) DEFAULT NULL COMMENT 'اندازه فونت بین سروری',
  `isUseWSSettings` int(1) NOT NULL COMMENT 'جهت بررسی',
  `GUID` varchar(50) COLLATE utf8_persian_ci NOT NULL COMMENT 'کد منحصر به فرد دبیرخانه برای ارسال مکاتبه از سرور های دیگر به این دبیرخانه خاص',
  `IsEnable` int(1) NOT NULL DEFAULT '1' COMMENT 'فعال',
  `DefaultTemplateID` int(11) NOT NULL COMMENT 'الگوی چاپ پیش فرض',
  `LastUpdatePostcartables` TEXT CHARACTER SET utf8 COLLATE utf8_persian_ci NULL COMMENT 'تاریخ آخرین بروزرسانی کارتابل های ای سی ای و دولت',
  `regBetweenSecForPerson` TINYINT NOT NULL COMMENT '0:register in sec, 1:register in person Kartable جهت ارسال مستقیم بین دبیرخانه ای سمت مقصد استفاده می شود',
  `TitleTextOutLetter` TINYINT( 1 ) NULL DEFAULT '0' COMMENT '0:by user, 1:not empty' AFTER `OnlySelectOutPer` COMMENT 'تنظیم چک کردن شرح عناوین و رونوشت ها برای ارسال نامه های صادره تایپی',
  PRIMARY KEY (`RowID`),
  KEY `CityID` (`CityID`),
  KEY `Longitude` (`Longitude`,`Latitude`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'دبیرخانه ها' ;

-- --------------------------------------------------------

--
-- Table structure for table `oa_secret_level` = سطوح محرمانگی
--

CREATE TABLE IF NOT EXISTS `oa_secret_level` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `Desc` varchar(100) NOT NULL COMMENT 'نوع سطوح محرمانگي',
  `AccessPoint` varchar(50) NOT NULL COMMENT 'سطح دسترسي',
  `Note` varchar(200) NOT NULL COMMENT 'يادداشت ( شرح)',
  `Order` int(11) NOT NULL COMMENT 'اولويت',
  PRIMARY KEY (`RowID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT 'سطوح محرمانگی' ;

-- --------------------------------------------------------

--
-- Table structure for table `oa_sec_archive` = اسناد و نامه های بایگانی شده در دبیرخانه
--

CREATE TABLE IF NOT EXISTS `oa_sec_archive` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `LetterID` int(11) NOT NULL COMMENT 'شماره نامه',
  `Date` date NOT NULL COMMENT 'تاریخ',
  `JeldNo` varchar(10) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'همه مقادیر خالی است - جهت بررسی',
  `Paper` varchar(10) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'همه مقادیر خالی است - جهت بررسی',
  `FolderID` int(11) NOT NULL COMMENT 'شماره پوشه => oa_sec_archive_folder.RowID',
  `Comments` varchar(200) COLLATE utf8_persian_ci NOT NULL COMMENT 'شرح',
  `IsDMSDoc` int(1) NOT NULL DEFAULT '0' COMMENT '
  نوع بایگانی
  0 = letter
  1 = DMSDoc',
  PRIMARY KEY (`RowID`),
  UNIQUE KEY `unique_folder_letter` (`FolderID`,`LetterID`),
  KEY `LetterID` (`LetterID`),
  KEY `FolderID` (`FolderID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'اسناد و نامه های بایگانی شده در دبیرخانه' ;

-- --------------------------------------------------------

--
-- Table structure for table `oa_sec_archive_folder` = پوشه های بایگانی دبیرخانه
--

CREATE TABLE IF NOT EXISTS `oa_sec_archive_folder` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `SecID` int(11) NOT NULL COMMENT 'شماره دبیرخانه',
  `Title` varchar(70) COLLATE utf8_persian_ci NOT NULL COMMENT 'عنوان پوشه',
  `Comments` varchar(100) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'توضیحات',
  `ClassCode` varchar(50) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'کلاسه',
  `ParentID` int(11) NOT NULL DEFAULT '-1' COMMENT 'پوشه پدر => oa_sec_archive_folder.RowID',
  `OrderID` int(11) NOT NULL DEFAULT '1' COMMENT 'شماره ترتیب',
  `DeptID` int(11) NOT NULL COMMENT 'شماره واحد => oa_depts_roles.RowID',
  `Path` varchar(50) COLLATE utf8_persian_ci NOT NULL COMMENT 'مسیر طی شده از شاخه اصلی تا این پوشه',
  PRIMARY KEY (`RowID`),
  KEY `SecID` (`SecID`),
  KEY `ParentID` (`ParentID`),
  KEY `Title` (`Title`(20)),
  KEY `OrderID` (`OrderID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'پوشه های بایگانی دبیرخانه' ;

-- --------------------------------------------------------

--
-- Table structure for table `oa_sec_archive_notes`
--

CREATE TABLE IF NOT EXISTS `oa_sec_archive_notes` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `SecID` int(11) NOT NULL COMMENT 'شماره دبیرخانه',
  `Title` varchar(256) COLLATE utf8_persian_ci NOT NULL COMMENT 'عنوان',
  PRIMARY KEY (`RowID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'خالی است جهت بررسی' ;

-- --------------------------------------------------------

--
-- Table structure for table `oa_sec_incartable` = شامل اطلاعات نامه های وارده به جهت نمایش در منو های دبیرخانه میباشد این اطلاعات خلاصه شده جداول مختلفی نظیر نامه ها 
، ارجاعات ، اسناد به جهت سرعت بخشیدن به بازیابی اطلاعات به جای عمل الحاق استفاده میشود . هنگام ثبت نامه وارد یک رکورد در این جدول درج میشود
--

CREATE TABLE IF NOT EXISTS `oa_sec_incartable` (
  `RowID` int(11) NOT NULL DEFAULT '0' COMMENT 'کد مشخصه',
  `referID` int(11) NOT NULL DEFAULT '0' COMMENT 'کد مشخصه ارجاع oa_doc_refer.ROwID',
  `did` int(11) NOT NULL DEFAULT '0' COMMENT 'کد مشخصه پرونده => oa_document.RowID',
  `Subject` varchar(100) CHARACTER SET utf8 COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'موضوع',
  `IsEnable` int(1) DEFAULT '1' COMMENT 'فعال',
  `RegCode` int(11) DEFAULT NULL COMMENT 'شماره ثبتی',
  `Prefix` varchar(7) CHARACTER SET utf8 COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'پیشوند',
  `RegDate` date DEFAULT NULL COMMENT 'تاریخ ثبت',
  `Postfix` varchar(7) CHARACTER SET utf8 COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'پسوند',
  `MainLetterCode` varchar(20) CHARACTER SET utf8 COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'شماره اصلی نامه',
  `MainLetterDate` date DEFAULT NULL COMMENT 'تاریخ اصلی نامه',
  `FromOtherOutName` varchar(100) CHARACTER SET utf8 COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'نام فرستنده',
  `DocStatus` tinyint(4) DEFAULT NULL COMMENT '
  0 = معمولی,
  1 = پیش نویس,
  2 = نامه در دبیرخانه است,
  3 = reserve,
  10 = پیام سیستمی
  
  101 = پیش نویس یا قابل ویرایش,
  102 = اماده تایید,
  103 = تایید شده و اماده ارشیو,
  104 = ارشیو شده
  ',
  `LetterStatus` tinyint(4) NOT NULL DEFAULT '0' COMMENT '
  وضعیت نامه
  0 = معمولی
  1 = پیشنویس بین دبیرخانه ای
  2 = جهت بررسی
  ',
  `TemplateID` int(11) DEFAULT NULL COMMENT 'کد مشخصه قالب => oa_print_template.RowID',
  `SecID` int(11) DEFAULT NULL COMMENT 'کد مشخصه دبیرخانه => oa_secretariat.RowID',
  `LetterID` int(11) NOT NULL DEFAULT '0' COMMENT 'کد مشخصه نامه => oa_letter.RowID',
  `outID` int(11) DEFAULT '0' COMMENT 'کد مشخصه طرف مکاتبات => oa_sec_outpersons.RowID',
  `Depts` varchar(200) CHARACTER SET utf8 COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'کد مشخصه واحد های دارای دسترسی',
  `DocDesc` varchar(200) CHARACTER SET utf8 COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'شرح نامه',
  `RegUserID` int(11) DEFAULT NULL COMMENT 'کد مشخصه کاربری ثبات نامه => oa_users.RowID',
  `RegRoleID` int(11) DEFAULT NULL COMMENT 'کد مشخصه سمت ثبات نامه => oa_depts_roles.RowID',
  `CreateDate` date DEFAULT NULL COMMENT 'تاریخ ایجاد',
  `HasAttach` int(11) NOT NULL DEFAULT '0' COMMENT 'دارای پیوست',
  `ToUserID` int(11) DEFAULT NULL COMMENT 'کد مشخصه کاربر مقصد => oa_users.RowID',
  `UrgentID` int(11) DEFAULT NULL COMMENT 'کد مشخصه فوریت => oa_emerg_level.RowID - مقادیر با جدول فوریت های مطابقت ندارد - جهت بررسی',
  `CategoryID` int(11) NOT NULL DEFAULT '0' COMMENT 'نوع محرمانگی نامه => oa_secret_level.RowID',
  `NoteDesc` varchar(500) CHARACTER SET utf8 COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'شرح سند',
  `FromUserID` int(11) DEFAULT NULL COMMENT 'کد مشخصه کاربری ارسال کننده => oa_users.RowID',
  `ReferDate` datetime DEFAULT NULL COMMENT 'تاریخ ارجاع',
  `IsAssist` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'نیابت یا خیر',
  PRIMARY KEY (`referID`),
  KEY `SecID` (`SecID`),
  KEY `RegDate` (`RegDate`),
  KEY `LetterID` (`LetterID`),
  KEY `outID` (`outID`),
  KEY `did` (`did`),
  KEY `RegCode` (`RegCode`),
  KEY `Depts` (`Depts`(150)),
  KEY `DocDesc` (`DocDesc`),
  KEY `Subject` (`Subject`),
  KEY `MainLetterCode` (`MainLetterCode`),
  KEY `FromUserID` (`FromUserID`),
  KEY `ToUserID` (`ToUserID`),
  KEY `MainLetterDate` (`MainLetterDate`),
  KEY `IsEnable` (`IsEnable`),
  KEY `SecID_2` (`SecID`,`IsEnable`,`RegDate`,`CategoryID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  COMMENT 'شامل اطلاعات نامه های وارده به جهت نمایش در منو های دبیرخانه میباشد این اطلاعات خلاصه شده جداول مختلفی نظیر نامه ها 
، ارجاعات ، اسناد به جهت سرعت بخشیدن به بازیابی اطلاعات به جای عمل الحاق استفاده میشود . هنگام ثبت نامه وارد یک رکورد در این جدول درج میشود';

-- --------------------------------------------------------

--
-- Table structure for table `oa_sec_outcartable` = شامل اطلاعات نامه های صادره به جهت نمایش در منو های دبیرخانه میباشد این اطلاعات خلاصه شده جداول مختلفی نظیر نامه ها 
، ارجاعات ، اسناد به جهت سرعت بخشیدن به بازیابی اطلاعات به جای عمل الحاق استفاده میشود . هنگام نامه ثبت شده صادره یک رکورد در این جدول درج میشود
--

CREATE TABLE IF NOT EXISTS `oa_sec_outcartable` (
  `RowID` int(11) NOT NULL DEFAULT '0',
  `referID` int(11) NOT NULL DEFAULT '0' COMMENT 'کد مشخصه ارجاع oa_doc_refer.ROwID',
  `did` int(11) NOT NULL DEFAULT '0' COMMENT 'کد مشخصه پرونده => oa_document.RowID',
  `Subject` varchar(100) CHARACTER SET utf8 COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'موضوع',
  `IsEnable` int(1) DEFAULT '1' COMMENT 'فعال',
  `RegCode` int(11) DEFAULT NULL COMMENT 'شماره ثبتی',
  `Prefix` varchar(7) CHARACTER SET utf8 COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'پیش وند',
  `RegDate` date DEFAULT NULL COMMENT 'تاریخ ثبت',
  `Postfix` varchar(7) CHARACTER SET utf8 COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'پس وند',
  `MainLetterCode` varchar(20) CHARACTER SET utf8 COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'شماره اصلی نامه',
  `MainLetterDate` date DEFAULT NULL COMMENT 'تاریخ اصلی نامه',
  `LetterID` int(11) NOT NULL DEFAULT '0' COMMENT 'شماره نامه ',
  `FromOtherOutName` varchar(100) CHARACTER SET utf8 COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'فرستنده نامه',
  `DocStatus` tinyint(4) DEFAULT NULL COMMENT '
  0 = معمولی,
  1 = پیش نویس,
  2 = نامه در دبیرخانه است,
  3 = reserve,
  101 = پیش نویس یا قابل ویرایش,
  102 = اماده تایید,
  103 = تایید شده و اماده ارشیو,
  104 = ارشیو شده
  ',
  `LetterStatus` tinyint(4) NOT NULL DEFAULT '0' COMMENT '
  وضعیت نامه
  0 = معمولی
  1 = پیشنویس بین دبیرخانه ای
  2 = جهت بررسی
  ',
  `TemplateID` int(11) DEFAULT NULL COMMENT 'کد مشخصه الگوی چاپ => oa_print_template.RowID',
  `fname` varchar(50) CHARACTER SET utf8 COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'نام',
  `lname` varchar(50) CHARACTER SET utf8 COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'نام خانوادگی',
  `SignerName` varchar(50) CHARACTER SET utf8 COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'نام امضا کننده',
  `SignerFamily` varchar(50) CHARACTER SET utf8 COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'فامیل امضا کننده',
  `SecID` int(11) DEFAULT NULL COMMENT 'کد مشخصه دبیرخانه => oa_secretariat.RowID',
  `uid` varchar(20) CHARACTER SET utf8 COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'جهت بررسی',
  `uid1` varchar(20) CHARACTER SET utf8 COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'جهت بررسی',
  `UserID` int(11) unsigned DEFAULT '0' COMMENT 'کد مشخصه کاربری => oa_users.RowID',
  `Depts` varchar(200) CHARACTER SET utf8 COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'شماره واحد های دارای دسترسی',
  `DocDesc` varchar(200) CHARACTER SET utf8 COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'شرح نامه',
  `RegUserID` int(11) DEFAULT NULL COMMENT 'کد مشخصه کاربری ثبات نامه => oa_users.RowID',
  `RegRoleID` int(11) DEFAULT NULL COMMENT 'کد مشخصه سمت ثبات نامه => oa_depts_roles.RowID',
  `CreateDate` date DEFAULT NULL COMMENT 'تاریخ ایجاد',
  `HasAttach` int(11) NOT NULL DEFAULT '0' COMMENT 'دارای پیوست',
  `SignerUser` int(11) DEFAULT NULL COMMENT 'کد مشخصه کاربری امضا کننده => oa_users.RowID',
  `SignerRole` int(11) DEFAULT NULL COMMENT 'کد مشخصه سمت امضا کننده => oa_depts_roles.RowID',
  `ContentID` int(11) DEFAULT NULL COMMENT 'کد مشخصه محتویات نامه => oa_content.RowID',
  `ToUserID` int(11) DEFAULT NULL COMMENT 'کد مشخصه کاربری گیرنده => oa_users.RowID',
  `UrgentID` int(11) DEFAULT NULL COMMENT 'فوریت نامه = oa_emerg_level_RowID',
  `CategoryID` int(11) NOT NULL DEFAULT '0' COMMENT 'سطح محرمانگی => oa_secret_level.RowID',
  `NoteDesc` varchar(500) CHARACTER SET utf8 COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'شرح سند',
  `FromUserID` int(11) DEFAULT NULL COMMENT 'کد مشخصه کاربری فرستنده => oa_users.RowID',
  `ReferDate` datetime DEFAULT NULL COMMENT 'تاریخ ارجاع',
  `CreatorName` varchar(50) CHARACTER SET utf8 COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'نام ایجاد کننده',
  `CreatorFamily` varchar(50) CHARACTER SET utf8 COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'نام خانوادگی ایجاد کننده',
  `IsAssist` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'نیابت یا خیر',
  PRIMARY KEY (`referID`),
  KEY `SecID` (`SecID`),
  KEY `RegDate` (`RegDate`),
  KEY `uid` (`uid`),
  KEY `uid1` (`uid1`),
  KEY `UserID` (`UserID`),
  KEY `Depts` (`Depts`(150)),
  KEY `DocDesc` (`DocDesc`),
  KEY `RegCode` (`RegCode`),
  KEY `did` (`did`),
  KEY `Subject` (`Subject`),
  KEY `MainLetterCode` (`MainLetterCode`),
  KEY `ToUserId` (`ToUserID`),
  KEY `FromUserID` (`FromUserID`),
  KEY `MainLetterDate` (`MainLetterDate`),
  KEY `IsEnable` (`IsEnable`),
  KEY `SecID_2` (`SecID`,`IsEnable`,`RegDate`,`CategoryID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT 'شامل اطلاعات نامه های صادره به جهت نمایش در منو های دبیرخانه میباشد این اطلاعات خلاصه شده جداول مختلفی نظیر نامه ها 
، ارجاعات ، اسناد به جهت سرعت بخشیدن به بازیابی اطلاعات به جای عمل الحاق استفاده میشود . هنگام نامه ثبت شده صادره یک رکورد در این جدول درج میشود';

-- --------------------------------------------------------

--
-- Table structure for table `oa_sec_outpersons` = طرف های مکاتبات دبیرخانه که در مدیریت تعریف میشوند
--

CREATE TABLE IF NOT EXISTS `oa_sec_outpersons` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT, 
  `Name` varchar(100) COLLATE utf8_persian_ci NOT NULL COMMENT 'نام طرف مکاتبات',
  `TitleOfPrint` VARCHAR( 200 ) NOT NULL COMMENT 'عنوان طرف مکاتبات برای نمایش و چاپ',
  `SecID` int(11) NOT NULL COMMENT 'کد دبیرخانه => oa_secretariat.RowID',
  `Url` varchar(300) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'آدرس الکترونیکی',
  `UrlType` tinyint(4) NOT NULL DEFAULT '0' COMMENT '
  نوع سرویس
  0 = ECE
  1 = وب سرویس
  2 = بدون سرویس
  3 = فاکس
  4 = دولت
  5 = ایمیل
  6 = فکس ایمیلی
  ',
  `UserName` varchar(50) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'نام کاربری',
  `Password` varchar(50) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'کلمه عبور',
  `Tel` varchar(50) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'تلفن',
  `BlockState` enum('0','1') COLLATE utf8_persian_ci DEFAULT NULL COMMENT '
  وضعیت مسدود بودن
  0 = مسدود است
  1 = مسدود نیست
  ',
  `ParentID` int(11) NOT NULL DEFAULT '-1' COMMENT 'Parend identifier - همه مقادیر نامعتبر است -  جهت بررسی',
  `Path` varchar(256) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'مسیر طی شده از ریشه تا این نود',
  `mailOrder` tinyint(4) DEFAULT NULL COMMENT 'شماره ترتیب ایمیل - جهت بررسی',
  `vpnName` varchar(256) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'نام وی پی ان',
  `vpnUsername` varchar(256) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'نام کاربری وی پی ان',
  `vpnPassword` varchar(256) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'کلمه عبور وی پی ان',
  `GUID` varchar(50) COLLATE utf8_persian_ci NOT NULL COMMENT 'شماره منحصر به فرد دبیرخانه ',
  `updSettings` text COLLATE utf8_persian_ci NOT NULL COMMENT 'همه مقادیر خالی است - جهت بررسی',
  PRIMARY KEY (`RowID`),
  KEY `Name` (`Name`),
  KEY `SecID` (`SecID`),
  KEY `GUID` (`GUID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'طرف های مکاتبات دبیرخانه که در مدیریت تعریف میشوند' ;

-- --------------------------------------------------------

--
-- Table structure for table `oa_sec_outpersons_log` = وقتی دو طرف مکاتبه را باهم ادغام میکنیم یک رکورد در این جدول درج میشود
--

CREATE TABLE IF NOT EXISTS `oa_sec_outpersons_log` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `SelectedItemID` varchar(500) NOT NULL COMMENT 'شماره طرف های مکاتبات',
  `SelectedItemName` varchar(500) NOT NULL COMMENT 'نام طرف  های مکاتبات',
  `TargetItemID` int(11) NOT NULL COMMENT 'کد شناسایی خروجی حاصل از ادغام',
  `TargetItemName` varchar(100) NOT NULL COMMENT 'جهت بررسی',
  `ResultName` varchar(100) NOT NULL COMMENT 'نام نتیجه که به جای نام طرف های ادغام شده قرار گرفته',
  `SecID` int(11) NOT NULL COMMENT 'شماره دبیرخانه => oa_secretariat.RowID',
  PRIMARY KEY (`RowID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT 'وقتی دو طرف مکاتبه را باهم ادغام میکنیم یک رکورد در این جدول درج میشود';

-- --------------------------------------------------------

--
-- Table structure for table `oa_sec_refer_note_template`
--

CREATE TABLE IF NOT EXISTS `oa_sec_refer_note_template` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `SecID` int(11) NOT NULL COMMENT 'شماره دبیرخانه',
  `ReferNote` varchar(200) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'شرح ارجاع',
  PRIMARY KEY (`RowID`),
  KEY `SecID` (`SecID`,`ReferNote`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'خالی است - جهت بررسی' ;

-- --------------------------------------------------------

--
-- Table structure for table `oa_sec_storage` = مسیرهای ذخیره سازی تعریف شده هنگام تعریف دبیرخانه'
--

CREATE TABLE IF NOT EXISTS `oa_sec_storage` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `SecID` int(11) NOT NULL COMMENT 'کد مشخصه دبیرخانه اگه -1 باشد به این معنی است که مسیر برای ذخیره سازی اسناد تعریف شده',
  `Year` int(11) NOT NULL COMMENT 'سال',
  `Month` int(11) NOT NULL COMMENT 'ماه',
  `Path` varchar(255) COLLATE utf8_persian_ci NOT NULL COMMENT 'مسیر',
  PRIMARY KEY (`RowID`),
  KEY `SecID` (`SecID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'مسیرهای ذخیره سازی تعریف شده هنگام تعریف دبیرخانه';

-- --------------------------------------------------------

--
-- Table structure for table `oa_sec_subject_template` = موضوعات دبیرخانه ای تعریف شده در قسمت مدیریت
--

CREATE TABLE IF NOT EXISTS `oa_sec_subject_template` (
  `RowID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Subject` varchar(200) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'موضوع',
  `SecID` int(10) unsigned NOT NULL COMMENT 'شماره دبیرخانه => oa_secretariat.RowID',
  PRIMARY KEY (`RowID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'موضوعات دبیرخانه ای تعریف شده در قسمت مدیریت';

-- --------------------------------------------------------

--
-- Table structure for table `oa_sec_templates`
--

CREATE TABLE IF NOT EXISTS `oa_sec_templates` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'نام',
  `FolderID` int(11) NOT NULL DEFAULT '-1' COMMENT 'شماره پوشه',
  PRIMARY KEY (`RowID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'خالی است - جهت بررسی';

-- --------------------------------------------------------

--
-- Table structure for table `oa_sec_template_folder`
--

CREATE TABLE IF NOT EXISTS `oa_sec_template_folder` (
  `RowID` int(11) NOT NULL,
  `Name` varchar(100) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'نام',
  `ParentID` int(11) NOT NULL DEFAULT '-1' COMMENT 'شماره پوشه پدر',
  PRIMARY KEY (`RowID`),
  KEY `ParentID` (`ParentID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci COMMENT 'خالی است - جهت بررسی';

-- --------------------------------------------------------

--
-- Table structure for table `oa_sec_template_group_perms`
--

CREATE TABLE IF NOT EXISTS `oa_sec_template_group_perms` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `TempID` int(11) NOT NULL DEFAULT '-1' COMMENT 'شماره قالب',
  `GroupID` int(11) NOT NULL DEFAULT '-1' COMMENT 'شماره گروه',
  PRIMARY KEY (`RowID`),
  KEY `TempID` (`TempID`,`GroupID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'خالی است - جهت بررسی';

-- --------------------------------------------------------

--
-- Table structure for table `oa_sec_template_sec_perms`
--

CREATE TABLE IF NOT EXISTS `oa_sec_template_sec_perms` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `SecID` int(11) NOT NULL DEFAULT '-1' COMMENT 'شماره دبیرخانه',
  `TempID` int(11) NOT NULL DEFAULT '-1' COMMENT 'شماره قالب',
  PRIMARY KEY (`RowID`),
  KEY `SecID` (`SecID`,`TempID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'خالی است - جهت بررسی';

-- --------------------------------------------------------

--
-- Table structure for table `oa_telbooks` = دفترچه تلفن
--

CREATE TABLE IF NOT EXISTS `oa_telbooks` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL COMMENT 'شماره کاربر ایجاد کننده => oa_users.RowID',
  `fname` varchar(50) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL COMMENT 'نام',
  `lname` varchar(50) CHARACTER SET ucs2 COLLATE ucs2_persian_ci NOT NULL COMMENT 'نام خانوادگی',
  `tel1` varchar(25) CHARACTER SET utf8 COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'شماره ثابت اول',
  `tel2` varchar(25) CHARACTER SET utf8 COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'شماره ثابت دوم',
  `tel3` varchar(25) CHARACTER SET utf8 COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'شماره ثابت سوم',
  `tel4` varchar(200) CHARACTER SET utf8 COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'شماره ثابت چهارم',
  `mobile` varchar(25) CHARACTER SET utf8 COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'شماره موبایل',
  `fax` varchar(25) CHARACTER SET utf8 COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'فکس',
  `email` varchar(25) CHARACTER SET ucs2 COLLATE ucs2_persian_ci NOT NULL COMMENT 'ایمیل',
  `address` varchar(500) CHARACTER SET ucs2 COLLATE ucs2_persian_ci NOT NULL COMMENT 'آدرس',
  `IsPub` int(2) NOT NULL DEFAULT '1' COMMENT '
  نوع مشخصات
  0 = عمومی
  1 = خصوصی
  2 = عمومی محرمانه',
  `SecID` varchar(25) NOT NULL COMMENT 'شماره دبیرخانه => oa_secretariat.RowID',
  `desc` text CHARACTER SET utf8 COLLATE utf8_persian_ci COMMENT 'توضیحات',
  PRIMARY KEY (`RowID`),
  KEY `UserID` (`UserID`),
  KEY `fname` (`fname`),
  KEY `lname` (`lname`),
  KEY `tel1` (`tel1`),
  KEY `tel1_2` (`tel1`),
  KEY `tel2` (`tel2`),
  KEY `tel3` (`tel3`),
  KEY `tel4` (`tel4`),
  KEY `mobile` (`mobile`),
  KEY `mobile_2` (`mobile`),
  KEY `fax` (`fax`),
  KEY `email` (`email`),
  KEY `address` (`address`),
  KEY `IsPub` (`IsPub`),
  KEY `SecID` (`SecID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=110 COMMENT 'دفترچه تلفن' ;

-- --------------------------------------------------------

--
-- Table structure for table `oa_tele_message` = پیام های صوتی نامه
--

CREATE TABLE IF NOT EXISTS `oa_tele_message` (
`RowID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `MessageDesc` varchar(100) COLLATE utf8_persian_ci DEFAULT NULL,
  `EncryptedFile` blob
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci COMMENT 'پسام های صوتی نامه' ;

-- --------------------------------------------------------

--
-- Table structure for table `oa_users` = کاربران سیستم
--

CREATE TABLE IF NOT EXISTS `oa_users` (
  `UserID` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'شماره کاربر',
  `OldUserID` varchar(20) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'نام کاربری',
  `SecID` int(11) unsigned NOT NULL COMMENT 'دبیرخانه => oa_secretariat.RowID',
  `Comments` varchar(50) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'توضیحات',
  `LastRequestTime` datetime DEFAULT NULL COMMENT 'آخرین زمان ارسال درخواست',
  `IsEnable` int(1) unsigned DEFAULT '1' COMMENT 'فعال است یا خیر',
  `TokenSerial` varchar(15) COLLATE utf8_persian_ci NOT NULL COMMENT 'شماره سریال توکن',
  `TokenPublicKey` longtext COLLATE utf8_persian_ci NOT NULL COMMENT 'کلید عمومی قفل سخت افزاری',
  `IsOPTUser` enum('0','1') COLLATE utf8_persian_ci NOT NULL COMMENT 'کاربر دارای قفل است یاخیر',
  `signCheck` varchar(256) COLLATE utf8_persian_ci DEFAULT '' COMMENT 'یک متن حاوی رشته جیسون برای مشخط کردن سطح محرمانگی امضا',
  `pass` char(32) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'کلمه عبور',
  `fname` varchar(50) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'نام',
  `lname` varchar(50) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'نام خانوادگی',
  `sex` tinyint(4) NOT NULL COMMENT 'جنسیت',
  `tel` varchar(45) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'تلفن',
  `internalTel` varchar(10) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'شماره تلفن داخلی',
  `mobile` varchar(45) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'موبایل',
  `address` varchar(45) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'ادرس',
  `email` varchar(45) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'ایمیل',
  `MD5uid` varchar(100) COLLATE utf8_persian_ci NOT NULL COMMENT 'نم کاربری هش ده با md5',
  `MD5pass` varchar(100) COLLATE utf8_persian_ci NOT NULL COMMENT 'کلمه عبور هش شده با md5',
  `ChangePassDate` date DEFAULT NULL COMMENT 'تاریخ تغییر کلمه عبور',
  `picture` blob COMMENT 'تصویر',
  `AccessLogin` tinyint(2) NOT NULL DEFAULT '1' COMMENT 'مجوز ورود دارد  یانه',
  `employeeID` varchar(256) CHARACTER SET utf8 DEFAULT NULL COMMENT 'شماره پرسنلی',
  `unlimitedToken` tinyint(2) NOT NULL DEFAULT '0' COMMENT '
  عدم محدودیت ورود به سیستم
  0 = limited Token 
  1 = unlimitedToken',
  `CreateDate` date DEFAULT NULL COMMENT 'user create date',
  `IsFreeze` int(1) NOT NULL COMMENT 'فریز شده - جهت بررسی',
  `IsArchive` int(11) NOT NULL COMMENT 'user bayghan = 1 else 0 - جهت بررسی',
  `unActiveDate` date DEFAULT NULL COMMENT 'تاریخ آخرین غیر فعال سازی',
  `excelRowID` int(11) NOT NULL COMMENT 'جهت بررسی',
  `topPersonalID` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT 'برای ارزش یابی استفاده میشود',
  `types` varchar(100) COLLATE utf8_persian_ci NOT NULL COMMENT 'برای ارزش یابی استفاده میشود',
  `values` varchar(1000) COLLATE utf8_persian_ci NOT NULL COMMENT 'برای ارزش یابی استفاده میشود',
  PRIMARY KEY (`UserID`),
  KEY `SecID` (`SecID`),
  KEY `OldUserID` (`OldUserID`),
  KEY `employeeID` (`employeeID`(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'کاربران سیستم' ;

-- --------------------------------------------------------

--
-- Table structure for table `oa_user_forced_exit` = کاربران اخراج شده از سیستم
--

CREATE TABLE IF NOT EXISTS `oa_user_forced_exit` (
  `UserID` int(11) NOT NULL DEFAULT '-1' COMMENT 'کد کاربر',
  `IP` varchar(20) COLLATE utf8_persian_ci NOT NULL DEFAULT '127.0.0.1' COMMENT 'ای پی',
  `MainIP` varchar(20) COLLATE utf8_persian_ci NOT NULL DEFAULT '0.0.0.0' COMMENT 'ای پی',
  PRIMARY KEY (`UserID`,`IP`,`MainIP`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci COMMENT 'هنگامی که کاربری از سیستم اخراج میشود یک  رکورد در این جدول ثبت میگردد
و تا ورود مجدد کاربر به سیستم این رکورد در جدول باقی میماند';

-- --------------------------------------------------------

--
-- Table structure for table `oa_user_group` = گروه های کاربری
--

CREATE TABLE IF NOT EXISTS `oa_user_group` (
  `RowID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'عنوان',
  `Comments` varchar(100) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'شرح',
  `MaxFileSize` int(11) DEFAULT NULL COMMENT 'uploadMaxFileSize',
  `MaxMessageSize` int(11) DEFAULT NULL COMMENT 'max size of attach message',
  `Public` TINYINT NOT NULL DEFAULT '1' COMMENT 'گروههای کاربری را میتوان به صورت عمومی تعریف کرد',
  PRIMARY KEY (`RowID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'گروه های کاربری';

-- --------------------------------------------------------

--
-- Table structure for table `oa_user_group_perms` = مجوزهای دسترسی داده شده به گروه های کاربری
--

CREATE TABLE IF NOT EXISTS `oa_user_group_perms` (
  `RowID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `UserGroupID` int(11) unsigned DEFAULT NULL COMMENT 'شماره گروه کاربری',
  `AccessPointID` int(11) unsigned DEFAULT NULL COMMENT 'شماره دسترسی',
  `AccessType` char(1) COLLATE utf8_persian_ci DEFAULT NULL COMMENT '
  نوع دسترسی
  y = درسترسی دارد
  مقدار دیگری برای این فیلد در جدول درج نمیشود
  ',
  PRIMARY KEY (`RowID`),
  KEY `UserGroupID` (`UserGroupID`),
  KEY `AccessPointID` (`AccessPointID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'مجوزهای دسترسی داده شده به گروه های کاربری' ;

-- --------------------------------------------------------

--
-- Table structure for table `oa_user_io` = اطلاعات مربوط به ورود و خروج کاربران
--

CREATE TABLE IF NOT EXISTS `oa_user_io` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL COMMENT 'شماره کاربر => oa_users.RowID',
  `RoleID` int(11) NOT NULL COMMENT 'شماره سمت => oa_depts_roles.RowID',
  `TimeLeft` time NOT NULL COMMENT 'زمان حضور در سیستم',
  `LoginDate` datetime NOT NULL COMMENT 'تاریخ ورود به سیستم',
  `Type` tinyint(4) NOT NULL COMMENT '
  نوع ورود
  0 = ورود به سمت یا کارتابلی شخصی
  1 = ورود به عنوان نایب
  ',
  `SessionID` varchar(256) CHARACTER SET utf8 NOT NULL COMMENT 'کد شناسایی نشست ایجاد شده',
  PRIMARY KEY (`RowID`),
  KEY `UserID` (`UserID`,`RoleID`,`TimeLeft`,`LoginDate`,`Type`),
  KEY `SessionID` (`SessionID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12073 COMMENT 'اطلاعات مربوط به ورود و خروج کاربران' ;

-- --------------------------------------------------------


--
-- Table structure for table `rg_category_folder` = پوشه های گزارش تعریف شده در قسمت گزارشات
--

CREATE TABLE IF NOT EXISTS `rg_category_folder` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'کد شناسایی پوشه',
  `Title` varchar(150) COLLATE utf8_persian_ci NOT NULL COMMENT 'عنوان',
  `Desc` int(200) DEFAULT NULL COMMENT 'توضیحات',
  `ParentID` int(11) NOT NULL COMMENT 'کد شناسایی پوشه پدر => rg_category_folder.RowID',
  `SecID` int(11) NOT NULL COMMENT 'کد شناسایی دبیرخانه => oa_secretariat.RowID',
  `DeptID` int(11) NOT NULL COMMENT 'کد شناسایی واحد => => oa_depts_roles.RowID',
  `path` varchar(150) COLLATE utf8_persian_ci NOT NULL COMMENT 'مسیر طی شده از شاخه اصلی تا این پوشه',
  `OrderID` int(11) NOT NULL COMMENT 'شماره ترتیب',
  PRIMARY KEY (`RowID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=2 COMMENT 'پوشه های گزارش تعریف شده در قسمت گزارشات' ;

-- --------------------------------------------------------

--
-- Table structure for table `rg_databases` = پایگاه داده های تعریف شده درسیستم
--

CREATE TABLE IF NOT EXISTS `rg_databases` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'کد شناسایی',
  `Title` varchar(150) COLLATE utf8_persian_ci NOT NULL COMMENT 'عنوان پایگاه داده',
  `Desc` varchar(150) COLLATE utf8_persian_ci NOT NULL COMMENT 'توضیحات',
  `Type` int(1) NOT NULL COMMENT '
  نوع پایگاه داده
  0 = mysql
  1 = sql
  ',
  `connectionstring` varchar(500) COLLATE utf8_persian_ci NOT NULL COMMENT 'رشته اتصال به بانک',
  `IsInternalServer` int(1) NOT NULL COMMENT 'سرور داخلی است یا خیر',
  PRIMARY KEY (`RowID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=3 COMMENT 'پایگاه داده های تعریف شده درسیستم' ;

-- --------------------------------------------------------

--
-- Table structure for table `rg_fields` = لیست فیلد های جداولی که برای  ایجاد گزارش به گزارش ساز ارسال شده
--

CREATE TABLE IF NOT EXISTS `rg_fields` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'کد منحصر به فرد فیلد در این جدول',
  `Name` varchar(150) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'نام فیلد در پایگاه داده',
  `Title` varchar(150) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'عنوان فیلد',
  `Desc` varchar(200) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'توضیحات',
  `FieldType` int(11) DEFAULT NULL COMMENT 'نوع فیلد => dm_field_type.RowID',
  `TableID` int(11) DEFAULT NULL COMMENT 'کد شناسایی جدول => rg_tables.RowID',
  `IsEnable` int(1) DEFAULT NULL COMMENT 'فعال یا غیر فعال',
  `order` int(11) DEFAULT NULL COMMENT 'شماره ترتیب در جدول',
  `properties` text COLLATE utf8_persian_ci NOT NULL COMMENT 'مشخصات فیلد',
  `mainRowID` int(11) NOT NULL COMMENT '',
  PRIMARY KEY (`RowID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'لیست فیلد های جداولی که برای  ایجاد گزارش به گزارش ساز ارسال شده';

-- --------------------------------------------------------

--
-- Table structure for table `rg_functions`
--

CREATE TABLE IF NOT EXISTS `rg_functions` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `Name` varchar(50) COLLATE utf8_persian_ci NOT NULL COMMENT '',
  `Title` varchar(50) COLLATE utf8_persian_ci NOT NULL COMMENT '',
  `Desc` varchar(100) COLLATE utf8_persian_ci NOT NULL COMMENT '',
  `IsGroupby` int(1) NOT NULL COMMENT '',
  PRIMARY KEY (`RowID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=8 COMMENT 'جهت بررسی';

-- --------------------------------------------------------

--
-- Table structure for table `rg_report_condition` = شرط های تعریف شده در گزارشات
--

CREATE TABLE IF NOT EXISTS `rg_report_condition` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'کد مشخصه',
  `FieldID` int(11) NOT NULL COMMENT 'کد مشخصه فیلد => rg_fields.RowID',
  `FieldType` int(11) NOT NULL COMMENT '',
  `ConditionType` int(11) NOT NULL COMMENT '
  نوع شرط 
  0 = برابر با
  1 = بزرگتر از
  2 = کوچکتر از
  3 = بزرگتر مساوی از
  4 = کوچکتر مساوی از
  5 = مخالف
  6 = شامل
  ',
  `Operator` int(11) NOT NULL COMMENT '
  عملگر
  0 = نامشخص
  1 = and
  2 = or
  ',
  `RepID` int(11) NOT NULL COMMENT 'کد مشخصه گزارش => rg_repproperties.RowID',
  `OneValue` varchar(300) COLLATE utf8_persian_ci NOT NULL COMMENT 'مقدار اول',
  `TwoValue` varchar(300) COLLATE utf8_persian_ci NOT NULL COMMENT 'مقدار دوم - جهت بررسی',
  `OneValueType` int(11) NOT NULL COMMENT '
  نوع مقدار اول
  0 = مقدار
  1 = فیلد
  2 = پارامتر
  ',
  `TwoValueType` int(11) NOT NULL COMMENT 'نوع مقدار دوم - جهت بررسی',
  `RepTableID` int(11) NOT NULL COMMENT 'کد مشخصه جدول گزارش => rg_report_table.RowID',
  `OneValueRepTableID` int(11) NOT NULL COMMENT 'if OneValueType = 1 then کد مشخصه جدول گزارش مقدار اول => rg_report_table.RowID',
  `TwoValueRepTableID` int(11) NOT NULL COMMENT 'if TwoValueType = 1 then کد مشخصه جدول گزارش مقدار دوم => rg_report_table.RowID',
  `RepTemplateID` int(11) NOT NULL COMMENT 'کد مشخصه الگو => rg_tabletemplates.RowID',
  PRIMARY KEY (`RowID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT '
هنگام تعریف گزارش اگر شرطی برای گزارش در قسمت تعیین شرط تعریف شکنیم رکورد مربوطه در این جدول درج میشود';

-- --------------------------------------------------------

--
-- Table structure for table `rg_report_fields` = لیست فیلدهای گزارش های ایجاد شده
--

CREATE TABLE IF NOT EXISTS `rg_report_fields` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'کد شناسایی',
  `RepID` int(11) NOT NULL COMMENT 'کد شناسایی گزارش => rg_repproperties.RowID',
  `RepTableID` int(11) NOT NULL COMMENT 'کد شناسایی گزارش => rg_report_table.RowID',
  `FieldAlias` int(11) NOT NULL COMMENT 'جهت بررسی',
  `FieldID` int(11) NOT NULL COMMENT 'کد شناسایی فیلد => rg_fields.RowID',
  `RepFieldType` int(11) NOT NULL COMMENT '
  نوع فیلد در گزارش ساز
  0 = ساده 
  1 = محاسباتی
  2 = گروه بندی',
  `CalculationField` varchar(500) COLLATE utf8_persian_ci NOT NULL COMMENT 'مقادیر تایین شده در فیلد محاسباتی با اسامی لاتین',
  `MainCalculationField` varchar(500) COLLATE utf8_persian_ci NOT NULL COMMENT 'مقادیر تایین شده در فیلد محاسباتی با اسامی فارسی جهت نمایش به کاربر',
  `Position` int(1) NOT NULL COMMENT 'use in crosstab report: junction:0- row:1-column:2 - جهت بررسی',
  `FieldSumType` int(11) NOT NULL COMMENT 'page:1- Report:2-Group by:3 - جهت بررسی',
  `IsHidden` int(1) NOT NULL COMMENT 'جهت بررسی',
  `Order` int(11) NOT NULL COMMENT 'ترتیب قرار گیری فیلدها در گزارش',
  `properties` text COLLATE utf8_persian_ci NOT NULL COMMENT 'مشخصات فیلد که بصورت جیسون ذخیره میشود',
  PRIMARY KEY (`RowID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'لیست فیلدهای گزارش های ایجاد شده' ;

-- --------------------------------------------------------

--
-- Table structure for table `rg_report_relationtable` =  روابط بین جدول های گزارش
--

CREATE TABLE IF NOT EXISTS `rg_report_relationtable` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'کد شناسایی',
  `firstRepTableID` int(11) NOT NULL COMMENT 'کد شناسایی جدول طرف اول رابطه => rg_report_table.RowID',
  `firstFieldID` int(11) NOT NULL COMMENT 'کد شناسایی فیلد رابط از جدول اول => rg_fields.RowID',
  `secondRepTableID` int(11) NOT NULL COMMENT 'کد شناسایی جدول طرف دوم رابطه => rg_report_table.RowID',
  `secondFieldID` int(11) NOT NULL COMMENT 'کد شناسایی فیلد رابط از جدول دوم => rg_fields.RowID',
  `joinType` int(11) NOT NULL COMMENT '
  نوع الحاق
  0 = left join
  1 = right join
  2 = inner join
  ',
  `relationType` int(11) NOT NULL COMMENT '
  نوع رابطه - جهت بررسی
  0 = manual
  1 = system
  ',
  `RepID` int(11) NOT NULL COMMENT 'کد شناسایی گزارش => rg_repproperties.RowID',
  `RepTemplateID` int(11) NOT NULL COMMENT 'شماره شناسایی الگوی گزارش => rg_tabletemplates.RowIِ',
  PRIMARY KEY (`RowID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT ' روابط بین جدول های گزارش' ;

-- --------------------------------------------------------

--
-- Table structure for table `rg_report_sort` = لیست فیلدهایی که گزارشات بر  اساس انها مرتب میشوند که هنگام ایجاد گزارش در تب مرتب سازی قابل تایین هستند
--

CREATE TABLE IF NOT EXISTS `rg_report_sort` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'کد شناسایی',
  `FieldID` int(11) NOT NULL COMMENT 'کد شناسایی فیلد  => rg_fields.RowID',
  `RepID` int(11) NOT NULL COMMENT 'کد شناسایی گزارش => rg_repproperties.RowID',
  `RepTableID` int(11) NOT NULL COMMENT 'کد شناسایی گزارش ایجاد شده => rg_report_table.RowID',
  `order` int(11) NOT NULL COMMENT 'شماره ترتیب فیلد',
  `SortType` int(11) NOT NULL COMMENT '
  نوع مرتب سازی
  0 = asc
  1 = desc
  ',
  `FieldType` int(11) NOT NULL COMMENT 'simple=0-Calculation = 1 - جهت بررسی',
  `IsGroupby` int(1) NOT NULL COMMENT 'جهت بررسی',
  PRIMARY KEY (`RowID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'لیست فیلدهایی که گزارشات بر  اساس انها مرتب میشوند که هنگام
ایجاد گزارش در تب مرتب سازی قابل تایین هستند' ;

-- --------------------------------------------------------

--
-- Table structure for table `rg_report_table` = جداول اضافه شده به گزارشات
--

CREATE TABLE IF NOT EXISTS `rg_report_table` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'شماره شناسایی',
  `TableID` int(11) NOT NULL COMMENT 'کد شناسایی جدول => rg_tables.RowID',
  `NameAlias` varchar(500) COLLATE utf8_persian_ci NOT NULL COMMENT 'نام مستعار',
  `RepID` int(11) NOT NULL COMMENT 'شماره شناسایی گزارش => rg_repproperties.RowID',
  `Order` int(11) NOT NULL COMMENT 'شماره ترتیب',
  `TableAlias` varchar(500) COLLATE utf8_persian_ci NOT NULL COMMENT 'جهت بررسی',
  `RepTemplateID` int(11) NOT NULL COMMENT 'شماره شناسایی الگوی گزارش => rg_tabletemplates.RowID',
  PRIMARY KEY (`RowID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'جداول اضافه شده به گزارشات' ;

-- --------------------------------------------------------

--
-- Table structure for table `rg_repproperties` = اطلاعات و تنظیمات گزارشات
--

CREATE TABLE IF NOT EXISTS `rg_repproperties` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'کد گزارش',
  `Name` varchar(150) COLLATE utf8_persian_ci NOT NULL COMMENT 'نام گزارش',
  `Desc` varchar(200) COLLATE utf8_persian_ci NOT NULL COMMENT 'شرح',
  `CreateDate` datetime NOT NULL COMMENT 'تاریخ ایجاد',
  `UserIDCreator` int(11) NOT NULL COMMENT 'کد شناسایی کاربر ایجاد کننده => oa_users.RowID',
  `RoleIDCreator` int(11) NOT NULL COMMENT 'کد شناسایی سمت کاربر ایجاد کننده => oa_depts_roles.RowID',
  `CategoryID` int(11) NOT NULL COMMENT 'کد شناسایی پوشه => rg_category_folder.RowID',
  `RepType` int(11) NOT NULL COMMENT '
  نوع  خروجی گزارش
  1 = معمولی
  2 = ماتریسی
  3 = مرکب
  ',
  `IsDistinct` int(1) NOT NULL COMMENT 'این فیلد مشخص میکند که از نمایش داده های تکراری جلوگیری شود یانه ',
  `IsConfirm` int(1) NOT NULL COMMENT 'جهت بررسی',
  `dbID` int(11) NOT NULL COMMENT 'کد پایگاه داده تعریف شده => rg_database.RowID',
  `Condition` varchar(200) COLLATE utf8_persian_ci NOT NULL COMMENT 'جهت بررسی',
  `CrossTabRowsCount` int(11) NOT NULL COMMENT 'جهت بررسی',
  `CrossTabColumnCount` int(11) NOT NULL COMMENT 'جهت بررسی',
  `SizeUnit` int(11) NOT NULL COMMENT 'واحد اندازه گیری که در قسمت تنظیمات تایین میشود (px,cm)',
  `HeaderSize` int(11) NOT NULL COMMENT 'جهت بررسی',
  `FooterSize` int(11) NOT NULL COMMENT 'جهت بررسی',
  `headerProperties` text COLLATE utf8_persian_ci NOT NULL COMMENT 'جهت بررسی',
  `bodyProperties` text COLLATE utf8_persian_ci NOT NULL COMMENT 'یک ساختار جیسون شامل تنظیمات بدنه گزارش',
  `footerProperties` text COLLATE utf8_persian_ci NOT NULL COMMENT 'یک ساختار جیسون شامل تنظیمات پاورقی',
  `ApplyAccess` int(1) DEFAULT NULL COMMENT '
  درسترسی گردش مکاتبات
  0 = بدون احتساب درسترسی
  1 = با احتساب درسترسی
  ',
  `bgImge` blob NOT NULL COMMENT 'تصویر پس زمینه - جهت بررسی',
  `SQlQuery` text COLLATE utf8_persian_ci NOT NULL COMMENT 'جهت بررسی',
  PRIMARY KEY (`RowID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=10 COMMENT ' اطلاعات و تنظیمات گزارشات' ;

-- --------------------------------------------------------

--
-- Table structure for table `rg_tables` = لیست جداولی که برای  ایجاد گزارش به گزارش ساز ارسال شده
--

CREATE TABLE IF NOT EXISTS `rg_tables` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'کد مشخصه',
  `Name` varchar(150) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'نام جدول در بانک اطلاعاتی',
  `Title` varchar(150) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'عنوان جدول',
  `Note` varchar(200) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'توضیحات',
  `dbID` int(11) DEFAULT NULL COMMENT 'شماره شناسایی بانک اطلاعاتی => rg_databases.RowID',
  `IsEnable` int(1) DEFAULT NULL COMMENT 'فعال است یاخیر',
  `CreatorRole` int(11) DEFAULT NULL COMMENT 'کد شناسایی سمت ایجاد کننده => oa_users.RowID',
  `CreatorUser` int(11) DEFAULT NULL COMMENT 'کد شناسایی کاربر ایجاد کننده => oa_depts_roles.RowID',
  `CreateDate` datetime DEFAULT NULL COMMENT 'تاریخ ایجاد',
  `mainRowID` int(11) NOT NULL COMMENT 'کد مشخصه در جدول ذخیره ساختارها => dm_structure.RowID',
  PRIMARY KEY (`RowID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=2 COMMENT 'لیست جداولی که برای  ایجاد گزارش به گزارش ساز ارسال شده' ;

-- --------------------------------------------------------

--
-- Table structure for table `rg_tabletemplates` = الگوهای گزارش
--

CREATE TABLE IF NOT EXISTS `rg_tabletemplates` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'شماره شناسایی',
  `Name` varchar(200) COLLATE utf8_persian_ci NOT NULL COMMENT 'نام الگو',
  PRIMARY KEY (`RowID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 COMMENT 'الگوهای گزارش' ;

-- --------------------------------------------------------

--
-- Table structure for table `uni_holiday` = روزهای تعطیل تعریف ثبت  در سیستم
--

CREATE TABLE IF NOT EXISTS `uni_holiday` (
  `RowID` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'شماره شناسایی',
  `TargetDate` date NOT NULL COMMENT 'تاریخ',
  `Notes` `Notes` VARCHAR( 250 ) CHARACTER SET utf8 COLLATE utf8_persian_ci NULL DEFAULT NULL COMMENT 'توضیحات',
  `holidayType` INT NOT NULL COMMENT '1:shamsi, 2:miladi, 3:ghamari',
  PRIMARY KEY (`RowID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 COMMENT 'روزهای تعطیل تعریف ثبت  در سیستم' ;

-- --------------------------------------------------------

--
-- Table structure for table `uni_otp_token`
--

CREATE TABLE IF NOT EXISTS `uni_otp_token` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL,
  `OldPass` varchar(100) NOT NULL,
  `NewPass` varchar(100) NOT NULL,
  `Used` enum('0','1') NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11  COMMENT 'جهت بررسی';

-- --------------------------------------------------------

--
-- Table structure for table `uni_sign`
--

CREATE TABLE IF NOT EXISTS `uni_sign` (
  `uid` varchar(20) COLLATE utf8_persian_ci NOT NULL,
  `signature` blob,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci COMMENT 'جهت بررسی';

/*---------------------------------------------------------------------------WorkFlow Tables--------------------------------------------*/

--
-- Table structure for table `wf_execution` به ازای هر فرایندی که با کلیک بر روی نام فرایند در قسمت ایجاد نامه کلیک میشود یک رکورد در 
-- این جدول ایجاد میگردد و در طول اجرای فرایند مقادیر آن تغییر میکند

CREATE TABLE IF NOT EXISTS `wf_execution` (
  `workflow_id` int(10) unsigned NOT NULL COMMENT 'کد مشخصه گردش کار => wf_workflow.workflow_id',
  `execution_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'کد مشخصه',
  `execution_parent` int(10) unsigned DEFAULT NULL COMMENT 'اگر زیرگردش کار باشد در این فیلد شماره گردشکار پدر قرار میگیرد',
  `execution_started` int(11) NOT NULL COMMENT 'تاریخ شروع به کار',
  `execution_suspended` int(11) DEFAULT NULL COMMENT '
  تاریخ آخرین توقف
  هنگامی که نامه برای اولین بار ارسال میشود بعد از رسیدن به کارتابل بعدی تاریخ قرار گرفتن در کارتابل در این فیلد ذخیره میشود و
  تا ارسال به کارتابل بعدی همین تاریخ در فیلد میماند و با ارسال مجدد این فرایند تکرار میشود
  ',
  `execution_variables` blob COMMENT 'متغیرهای نمونه گردش کار اجراشده که بصورت ساختار جیسون ذخیره شده و حاوی نام متغیرهای سراسری تعریف شده در
  گردش کار و کدهای شناسایی ارجاعات انجام شده در طول گردش کار میباشد',
  `execution_waiting_for` blob COMMENT 'نشان میدهد که نمونه اجرایی در حال حاضر منتظر اتمام کدام ارجاع است',
  `execution_threads` blob COMMENT 'جهت بررسی',
  `execution_next_thread_id` int(10) unsigned NOT NULL COMMENT 'نشان میدهد که نمونه اجرا شده در چه مرحله ای قرار دارد',
  `execution_doc_id` int(11) NOT NULL COMMENT 'کد شناسایی سند => oa_document.RowID',
  `is_enable` int(1) DEFAULT '1' COMMENT '
  وضعیت فرایند
  1 = فرایند هنوز در جریان است
  2 = فرایند به انتها رسیده
  ',
  PRIMARY KEY (`execution_id`,`workflow_id`),
  KEY `execution_parent` (`execution_parent`),
  KEY `execution_doc_id` (`execution_doc_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10169 COMMENT 'به ازای هر فرایندی که با کلیک بر روی نام فرایند در قسمت ایجاد نامه کلیک میشود یک رکورد در 
 این جدول ایجاد میگردد و در طول اجرای فرایند مقادیر آن تغییر میکند';

-- --------------------------------------------------------

--
-- Table structure for table `wf_execution_state` = وضعیت نمونه گردشکار اجرا شده را نشان میدهد
--

CREATE TABLE IF NOT EXISTS `wf_execution_state` (
  `execution_id` int(10) unsigned NOT NULL COMMENT 'کد مشخصه نمونه گردش کار اجرا شده => wf_execution.execution_id',
  `node_id` int(10) unsigned NOT NULL COMMENT 'شماره گره فعلی',
  `node_state` blob COMMENT 'وضعیت را بصورت رشته جیسون نشان میدهد که شامل کد منحصر به فرد ارجاعی است که نمونه کار اجرا شده منتظر اتمام آن است',
  `node_activated_from` blob COMMENT 'جهت بررسی',
  `node_thread_id` int(10) unsigned NOT NULL COMMENT 'جهت بررسی',
  PRIMARY KEY (`execution_id`,`node_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT 'وضعیت نمونه گردشکار اجرا شده را نشان میدهد';

-- --------------------------------------------------------

--
-- Table structure for table `wf_node` = گره های تشکیل دهنده گردش کارها
--

CREATE TABLE IF NOT EXISTS `wf_node` (
  `workflow_id` int(10) unsigned NOT NULL COMMENT 'کد شناسایی گردش کار => wf_workflow.workflow_id',
  `node_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'کد مشخصه گره',
  `node_class` varchar(255) NOT NULL COMMENT 'کلاس گره',
  `node_configuration` text COMMENT 'پیکربندی گره که از یک رشته جیسون تشکیل شده است',
  `pic_kartable` longblob COMMENT 'همه مقادیر خالی  است - جهت بررسی',
  PRIMARY KEY (`node_id`),
  KEY `workflow_id` (`workflow_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101903 COMMENT 'گره های تشکیل دهنده گردش کارها' ;

-- --------------------------------------------------------

--
-- Table structure for table `wf_node_connection` = ارتباطات بین نودهای گردش کار
--

CREATE TABLE IF NOT EXISTS `wf_node_connection` (
  `node_connection_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'کد مشخصه ارتباط',
  `incoming_node_id` int(10) unsigned NOT NULL COMMENT 'گره شروع کننده ارتباط => wf_node.node_id',
  `outgoing_node_id` int(10) unsigned NOT NULL COMMENT 'گره دوم ارتباط => wf_node.node_id',
  PRIMARY KEY (`node_connection_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=123927 COMMENT 'ارتباطات بین نودهای گردش کار' ;

-- --------------------------------------------------------

--
-- Table structure for table `wf_refers` = ارجاعات انجام شده در طور اجرای گردش کار
--

CREATE TABLE IF NOT EXISTS `wf_refers` (
  `refer_id` int(11) NOT NULL COMMENT 'کد مشخصه ارجاع => oa_doc_refer.RowID',
  `execution_id` int(11) NOT NULL COMMENT 'کد مشخصه نمونه گردش کار => wf_execution.execution_id',
  `node_index` int(11) NOT NULL COMMENT 'شماره ترتیب اجرایی ارجاع برای هر نمونه گردشکار اجرا شده',
  `commands` text CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL COMMENT 'در این فیلد اعمالی که دارنده کارتابل میتواند انجام دهد قرار میگیرد
  که بصورت ساختار جیسون ذخیره شده و حاوی متن و نام آیکن دکمه هایی است که کاربر میتوان کلیک کند',
  `selected` varchar(255) CHARACTER SET utf8 COLLATE utf8_persian_ci DEFAULT NULL COMMENT '
  این فیلد عمل انجام شده روی نامه توسط دارنده کارتابل را نشان میدهد که بصورت یک رشته جیسون است و حاوی متن و نام تصویر دکمه زده شده توسط کاربر است',
  `uniq_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'کد شناسایی منحصر به فرد',
  `timeout_id` int(11) DEFAULT NULL COMMENT 'جهت بررسی',
  `setemerg` int(1) NOT NULL DEFAULT '0' COMMENT 'قابلیت تعیین فوریت',
  `setRemind` TINYINT NOT NULL DEFAULT '0' COMMENT 'قابلیت ایجاد یادآوری',
  `Settings` varchar(500) CHARACTER SET utf8 COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'تنظیمات - جهت بررسی',
  PRIMARY KEY (`refer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT 'ارجاعات انجام شده در طور اجرای گردش کار';

-- --------------------------------------------------------

--
-- Table structure for table `wf_scheduler`
--

CREATE TABLE IF NOT EXISTS `wf_scheduler` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'کد شناسایی زمانبند',
  `setdate` datetime NOT NULL COMMENT '',
  `timeout` int(11) NOT NULL COMMENT '',
  `class` varchar(255) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL COMMENT '',
  `method` varchar(255) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL COMMENT '',
  `params` text CHARACTER SET utf8 COLLATE utf8_persian_ci COMMENT '',
  `varinputs` text CHARACTER SET utf8 COLLATE utf8_polish_ci COMMENT '',
  `result` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1289 COMMENT 'جهت بررسی';

-- --------------------------------------------------------

--
-- Table structure for table `wf_variable_handler`
--

CREATE TABLE IF NOT EXISTS `wf_variable_handler` (
  `workflow_id` int(10) unsigned NOT NULL COMMENT 'کد شناسایی گردش کار => wf_workflow.workflow_id',
  `variable` varchar(255) NOT NULL COMMENT '',
  `class` varchar(255) NOT NULL COMMENT '',
  PRIMARY KEY (`workflow_id`,`class`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT 'خالی است - جهت بررسی';

-- --------------------------------------------------------

--
-- Table structure for table `wf_webkiosk_perm`
--

CREATE TABLE IF NOT EXISTS `wf_webkiosk_perm` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `StructID` int(11) NOT NULL COMMENT '',
  `FieldID` int(11) NOT NULL COMMENT '',
  `FieldName` varchar(256) COLLATE utf8_persian_ci NOT NULL COMMENT '',
  `Perm` tinyint(4) NOT NULL COMMENT '1=inherit-2=JustView' COMMENT '',
  PRIMARY KEY (`RowID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=4 COMMENT 'جهت بررسی';

-- --------------------------------------------------------

--
-- Table structure for table `wf_workflow`
--

CREATE TABLE IF NOT EXISTS `wf_workflow` (
  `workflow_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'کد شناسایی گردش کار',
  `workflow_name` varchar(255) COLLATE utf8_persian_ci NOT NULL COMMENT 'نام گردش کار',
  `workflow_version` int(10) unsigned NOT NULL DEFAULT '1' COMMENT 'هربار که گردش کار فعال میشود یک شماره به ورژن  آن اضافه میشود',
  `workflow_created` int(11) NOT NULL COMMENT 'تاریخ ایجاد',
  `workflow_formtypeid` int(11) DEFAULT NULL COMMENT 'کد شناسایی فرم',
  `workflow_enable` int(1) NOT NULL COMMENT '1 true, 0 false' COMMENT 'گردش کار فعال است یانه',
  `workflow_event` text COLLATE utf8_persian_ci NOT NULL COMMENT 'رویدادی که در زمان ایجاد یک نسخه از این فرایند اجرا میگردد در صورت کلیک بر روی نام فرم فرایند در ایجاد نامه این رویداد اجرا میشود',
  PRIMARY KEY (`workflow_id`),
  UNIQUE KEY `name_version` (`workflow_name`,`workflow_version`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=2574 COMMENT 'گردش کارها' ;
/*-------------------------------------------------------------------------------------------------------------------------------------------*/
--
-- Table structure for table `wf_errors` = خطاهای ایجاد شده هنگام اجرای کدهای پردازش های گردش کار
--
CREATE TABLE IF NOT EXISTS `wf_errors` (
  `RowID` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'کد مشخصه و کلید اصلی خطا',
  `ExecutionID` int(11) unsigned NOT NULL COMMENT 'کد مشخصه نمونه اجرایی گردش کار => wf_execution.execution_id',
  `DocID` int(11) unsigned NOT NULL COMMENT 'کد مشخصه سند => oa_document.RowID',
  `ReferID` int(11) unsigned NOT NULL COMMENT 'کد مشخصه ارجاع => oa_doc_refer.RowID',
  `Date` datetime NOT NULL COMMENT 'تاریخ وقوع خطا',
  `ErrorText` text COMMENT 'توضیحات خطا که یک عبارت جی سون شامل متن خطا و شماره خط وقوع خطا در کد میباشد',
  PRIMARY KEY (`RowID`),
  KEY `ExecutionID` (`ExecutionID`),
  KEY `DocID` (`DocID`),
  KEY `ReferID` (`ReferID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 COMMENT 'خطاهای ایجاد شده هنگام اجرای کدهای پردازش های گردش کار' ;

-- --------------------------------------------------------

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
