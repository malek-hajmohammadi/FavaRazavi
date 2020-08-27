this.jcode = function (self) {
    self.search = function (csv) {
        Utils.showProgress(true);
        document.getElementById('dvQuery').innerHTML = '';
        var res = Main.FirstPageParameters.datetime.todayDate.split('/');
        var yynow = res[0];
        var mmnow = res[1];
        var ddnow = res[2];
        var mm = FormOnly.allFieldsContianer[1].getData();
        if (mm.length == '1') mm = "0" + mm;
        var yy = FormOnly.allFieldsContianer[0].getData();
        var yy = parseInt(yy) + 1395;
        var codem = FormOnly.allFieldsContianer[9].getData();
        if (csv == 1) {
            document.getElementById('dvQuery').innerHTML = '<div id="dvQueryTrue">خروجي اكسل گرفته شد<div>';
            Utils.showProgress(false);
            window.open("../Runtime/process.php?module=WorkFlowAjaxFunc&action=TaminTaradod&yy=" + yy + "&mm=" + mm + "&codem=" + codem + "&mmnow=" + mmnow + "&ddnow=" + ddnow + "&yynow=" + yynow + "&csv=1&" + Main.getCSRFToken());
            return;
        } else {
            if (mm == 0) {
                $jq('#FORMONLY-FIELDS-CONTAINER').animate({scrollTop: 0}, 'slow');
                document.getElementById('dvQuery').innerHTML = '<div id="dvQueryFalse">ماه را انتخاب كنيد</div>';
                Utils.showProgress(false);
                return;
            }
            if (yy == 0) {
                $jq('#FORMONLY-FIELDS-CONTAINER').animate({scrollTop: 0}, 'slow');
                document.getElementById('dvQuery').innerHTML = '<div id="dvQueryFalse">سال را انتخاب كنيد</div>';
                Utils.showProgress(false);
                return;
            }
            if (codem == '') {
                $jq('#FORMONLY-FIELDS-CONTAINER').animate({scrollTop: 0}, 'slow');
                document.getElementById('dvQuery').innerHTML = '<div id="dvQueryFalse">كد ملي را وارد كنيد</div>';
                Utils.showProgress(false);
                return;
            } else if (codem.length == 10) {
                if (codem == '1111111111' || codem == '0000000000' || codem == '2222222222' || codem == '3333333333' || codem == '4444444444' || codem == '5555555555' || codem == '6666666666' || codem == '7777777777' || codem == '8888888888' || codem == '9999999999') {
                    document.getElementById('dvQuery').innerHTML = '<div id="dvQueryFalse">كد ملي را بصورت صحيح وارد كنيد</div>';
                    Utils.showProgress(false);
                    return;
                }
                c = parseInt(codem.charAt(9));
                n = parseInt(codem.charAt(0)) * 10 + parseInt(codem.charAt(1)) * 9 + parseInt(codem.charAt(2)) * 8 + parseInt(codem.charAt(3)) * 7 + parseInt(codem.charAt(4)) * 6 + parseInt(codem.charAt(5)) * 5 + parseInt(codem.charAt(6)) * 4 + parseInt(codem.charAt(7)) * 3 + parseInt(codem.charAt(8)) * 2;
                r = n - parseInt(n / 11) * 11;
                if ((r == 0 && r == c) || (r == 1 && c == 1) || (r > 1 && c == 11 - r)) {
                } else {
                    document.getElementById('dvQuery').innerHTML = '<div id="dvQueryFalse">كد ملي را بصورت صحيح وارد كنيد</div>';
                    Utils.showProgress(false);
                    return;
                }
            } else {
                document.getElementById('dvQuery').innerHTML = '<div id="dvQueryFalse">كد ملي را بصورت صحيح وارد كنيد</div>';
                Utils.showProgress(false);
                return;
            }
            var res = Utils.fastAjax('WorkFlowAjaxFunc', 'TaminTaradod', {
                yy: yy,
                mm: mm,
                codem: codem,
                mmnow: mmnow,
                ddnow: ddnow,
                yynow: yynow,
                csv: csv
            });
            document.getElementById('dvQuery').innerHTML = res;
            Utils.showProgress(false);
        }
    }
}