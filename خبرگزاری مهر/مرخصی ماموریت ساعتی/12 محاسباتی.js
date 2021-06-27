this.jcode = function (self) {
    try {
        self.RunJS = function (fw) {
            var msg = '';
            var PID = FormView.myForm.getItemByName('Field_2').getData();
            var TimeR = FormView.myForm.getItemByName('Field_5').data;
            if (TimeR == '') FormView.myForm.getItemByName('Field_5').setData('0:0');
            var TimeB = FormView.myForm.getItemByName('Field_6').data;
            if (TimeB == '') FormView.myForm.getItemByName('Field_6').setData('0:0');
            var DateD = FormView.myForm.getItemByName('Field_4').getData();
            var Type = FormView.myForm.getItemByName('Field_21').getData();
            var modat = '0:0';
            var TimeRsplit = TimeR.split(":");
            var ah = parseInt(TimeRsplit[0]);
            var am = parseInt(TimeRsplit[1]);
            var az = (ah * 60) + am;
            var TimeBsplit = TimeB.split(":");
            var th = parseInt(TimeBsplit[0]);
            var tm = parseInt(TimeBsplit[1]);
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
            } else if (az > ta && TimeB != "") modat = '0:0'; else modat = '0:0';
            $jq('#f-Modat-MoSMaS').html('مدت ' + modat);
            if (PID == '' || PID == '0') {
                msg += 'شماره پرسنلي صحيح نيست' + '<br>';
            } else if (DateD == '') {
                msg += 'تاريخ درخواست را كامل نماييد' + '<br>';
            } else if (Type == '' || Type==0) {
                msg += 'نوع درخواست را انتخاب نماييد' + '<br>';
            } else if (TimeR == '' || TimeR == '00:00') {
                msg += 'ساعت شروع را كامل نماييد' + '<br>';
            } else if (TimeR == '' || TimeB == '00:00') {
                msg += 'ساعت پايان را كامل نماييد' + '<br>';
            }
            if (msg == '') {
                return true;
            } else {
                Utils.showMessage(msg);
                return false;
            }
        };
    } catch (e) {
        console.log(e);
    }
}