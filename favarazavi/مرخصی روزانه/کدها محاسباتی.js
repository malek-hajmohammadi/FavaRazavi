this.jcode = function (self) {
    try {
        self.RunJS = function (fw) {
            var msg = '';
            var typeDiff = '';
            var emp = FormView.myForm.getItemByName('Field_11').getData();
            var dateR = FormView.myForm.getItemByName('Field_2').getData();
            var dateB = FormView.myForm.getItemByName('Field_3').getData();
            var type = FormView.myForm.getItemByName('Field_7').getData();
            var janeshin = FormView.myForm.getItemByName('Field_1').getData();
            var step = FormView.myForm.getItemByName('Field_6').getData();             /* اختلاف دو روز */
            var Diff = Utils.fastAjax('NForms', 'dataDiff', {sdate: dateR, edate: dateB}, true);             /* احتساب جمعه و تعطيلات */
            var DiffH = Utils.fastAjax('NForms', 'dateDiffHolidayCheck', {sdate: dateR, edate: dateB}, true);
            typeDiff = DiffH;
            var ModatMorkhasi = parseInt(typeDiff);
            if (ModatMorkhasi.length == 1) ModatMorkhasi = '0' + ModatMorkhasi;
            FormView.myForm.getItemByName('Field_10').setData(ModatMorkhasi + ' روز');
            if (dateB == '') FormView.myForm.getItemByName('Field_10').setData('0');
            if (emp == '' || emp == '0') {
                msg = "شماره پرسنلي صحيح نيست";
            } else if (!type) {
                msg = "نوع مرخصي را انتخاب نماييد";
            } else if (dateR == '') {
                msg = "تاريخ رفت را انتخاب نماييد";
            } else if (dateB == '') {
                msg = "تاريخ برگشت را انتخاب نماييد";
            } else if (parseInt(ModatMorkhasi) <= 0) {
                msg = "مدت مرخصي نبايد صفر باشد";
            } else if (type != 11 && janeshin == '') {
                msg = "جانشين را انتخاب نماييد";
            }
            if (type == 11) {
                $jq('.janeshin').hide();
                $jq('.estelaji').show();
                if (step == 1) {
                    $jq('#f-file-name').html('مدارك مربوطه خود را در قالب يك فايل در «ارسال فايل» بارگذاري نماييد:');
                    $jq('#f-file-type').html('پسوندهاي مجاز: jpg و zip و rar');
                } else {
                    $jq('#f-file-name').html('مدارك مربوطه متقاضي را از «دريافت فايل» دانلود نماييد:');
                    $jq('#f-file-type').html('');
                }
                var madarekID = $jq('.estelaji input.madarek').attr('id');
                $jq('.estelaji label.f-file').attr('for', madarekID);
                if (FormView.myForm.getItemByName('Field_9').mode == 'edit') {
                    if (FormView.myForm.getItemByName('Field_9').getData() == '') {
                        msg = 'فايل(هاي) خود را بارگذاري نماييد';
                    }                     /*                     setInterval(function () {                         $jq('.estelaji input[type="file"]').change(function (e) {                             e.preventDefault();                             var FileName = $jq('.estelaji input[type="file"]')[0].files[0].name;                             $jq('#f-file-name').text(FileName);                         });                     }, 100);                     */
                } else {
                    $jq('.estelaji label.f-file').hide();
                }
            } else if (type == 10 || type == 15 || type == 12 || type == 13 || type == 14) {
                $jq('.janeshin').show();
                $jq('.estelaji').hide();
            } else {
                $jq('.janeshin').hide();
                $jq('.estelaji').hide();
            }
            if (msg == '') {
                $jq('.f-notification').fadeOut(500, function () {
                    $jq(this).removeClass('f-notification-false').text('');
                });
                return true;
            } else {                 /*Utils.showMessage(msg);*/
                $jq('.f-notification').fadeIn(500, function () {
                    $jq(this).addClass('f-notification-false').text(msg);
                });
                return false;
            }
        };
    } catch (cc) {
        console.log(cc);
    }
}