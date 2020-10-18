this.jcode = function (self) {
    try {
        self.RunJS = function (fw) {
            var msg = '';
            var dateD = FormView.myForm.getItemByName('Field_1').getData();
            var dateDSplit = dateD.split('/');
            var dateNow = Main["FirstPageParameters"]["datetime"]["todayDate"];
            var dateNowSplit = dateNow.split('/');
            var timeR = FormView.myForm.getItemByName('Field_2').getData();
            var timeB = FormView.myForm.getItemByName('Field_14').getData();
            var type = FormView.myForm.getItemByName('Field_3').getData();
            var MandeMorkhasiMerg = FormView.myForm.getItemByName('Field_12').getData();
            var MandeMorkhasiStatus = '';
            if (MandeMorkhasiMerg != 'خطا') {
                MandeMorkhasiStatus = MandeMorkhasiMerg[4];
                MandeMorkhasiMerg = MandeMorkhasiMerg.split('-');
                MandeMorkhasiMerg = parseInt(MandeMorkhasiMerg[3]);
            }
            $jq('.dateD input').prop('disabled', true);
            $jq('.dateD img').remove();
            $jq('.RefreshMandeMorkhasi').css('visibility', 'hidden');
            var modat = '0:0';
            var ModatMerg = '0';
            strArray1 = timeR.split(":");
            var ah = parseInt(strArray1[0]);
            var am = parseInt(strArray1[1]);
            var az = (ah * 60) + am;
            var strArray2 = timeB.split(":");
            var th = parseInt(strArray2[0]);
            var tm = parseInt(strArray2[1]);
            var ta = (th * 60) + tm;
            if (az <= ta) {
                var final = ta - az;
                if (final >= 60) {
                    fh = Math.floor(final / 60, 0);
                    fm = Math.round(final % 60, 0);
                } else {
                    fh = 0;
                    fm = final;
                }
                modat = fh + ":" + fm;
                ModatMerg = (parseInt(fh) * 60) + parseInt(fm);
            } else if (az > ta && timeB != "") modat = '0:0'; else modat = '0:0';
            FormView.myForm.getItemByName('Field_15').setData(modat);
            var dateDiffM = parseInt(dateNowSplit[1]) - parseInt(dateDSplit[1]);              /*             if((dateDiffM > 1 || dateDiffM < 0) || dateDSplit[1] == 12)             */
            if (dateDiffM > 1 || dateDiffM < 0) {
                msg = "بدليل اختلاف با ماه جاري امكان تاييد نيست";
                FormView.myForm.getItemByName('Field_1').showMSG(msg);
            }
            if (dateDiffM == 1 && parseInt(dateNowSplit[2]) > 17) {
                msg = "پس از روز هفدهم ماه جاري امكان تاييد فرم ماه قبل نيست";
                FormView.myForm.getItemByName('Field_1').showMSG(msg);
            }             /*             if(MandeMorkhasiMerg == 'خطا')             {                 msg = "مانده مرخصي خطا دارد!";                 FormView.myForm.getItemByName('Field_10').showMSG(msg);                 $jq('.RefreshMandeMorkhasi').css('visibility','visible');             }             */
            if (MandeMorkhasiStatus == 'manfi') {
                msg = "مانده مرخصي منفي است";
                FormView.myForm.getItemByName('Field_10').showMSG(msg);
            }
            if (type == '' || timeR == '' || timeB == '') {
                msg = "ساعت رفت، ساعت برگشت و نوع مرخصي اجباري است";
                FormView.myForm.getItemByName('Field_3').showMSG(msg);
            }
            if (timeR == '0:0' || (timeR != '' && modat == '0:0')) {
                msg = "ساعت رفت صحيح نيست";
                FormView.myForm.getItemByName('Field_2').showMSG(msg);
            }
            if (timeB == '0:0' || (timeB != '' && modat == '0:0')) {
                msg = "ساعت برگشت صحيح نيست";
                FormView.myForm.getItemByName('Field_14').showMSG(msg);
            }
            if (ModatMerg > 180) {
                msg = "براي مرخصي بيش از ۳ ساعت از مرخصي روزانه استفاده كنيد";
                FormView.myForm.getItemByName('Field_3').showMSG(msg);
            }             /*             if(ModatMerg > parseInt(MandeMorkhasiMerg))             {                 msg = "مدت مرخصي بيشتر از مانده مرخصي است";                 FormView.myForm.getItemByName('Field_15').showMSG(msg);             }             */
            var RID = Main.RoleId;             /*RID = 8651 Delbari*/
            if (msg == '' || RID == 8651 || RID == 1508) {
                return true;
            } else {                 /*Utils.showMessage(msg);*/
                return false;
            }
        };
    } catch (cc) {
        console.log(cc);
    }
}