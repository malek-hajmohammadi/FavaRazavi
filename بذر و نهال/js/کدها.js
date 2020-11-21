this.jcode = function (self) {
    try {
        self.LoadJS = function (time, id) {
            var PID = Main['UserInfo']['employeeID'];
            FormOnly.allFieldsContianer[7].setData(PID);
            $jq('.BazrPID').text(PID);
            var today = Main["FirstPageParameters"]["datetime"]["todayDate"];
            aa = today.split('/');
            yy = aa[0];
            mm = aa[1];
            dd = aa[2];
            azt = yy + '/01/01';
            tat = yy + '/' + mm + '/' + dd;
            FormOnly.allFieldsContianer[0].setData(azt);
            FormOnly.allFieldsContianer[1].setData(tat);
            if (yy == '1398') yy = 1; else if (yy == '1398') yy = 2; else yy = 0;
            mm = mm.replace(/^0+/, '');
            parseInt(mm);
            FormOnly.allFieldsContianer[3].setData(mm);
            FormOnly.allFieldsContianer[4].setData(yy);
        };
        self.DoreZamaniJS = function (fw) {
            Utils.showProgress(true);
            var PID = '';
            var mm = '';
            var yy = '';
            PID = FormOnly.allFieldsContianer[7].getData();
            mm = FormOnly.allFieldsContianer[3].getData();
            if (mm.toString().length == 1) mm = '0' + parseInt(mm);
            yy = FormOnly.allFieldsContianer[4].getData();
            yy = parseInt(yy) + 1398;
            var res = Utils.fastAjax('WorkFlowAjaxFunc', 'BazrTimexList', {
                mm: mm,
                yy: yy,
                PID: PID,
                status: 'DoreZamani'
            });
            $jq('#FORMONLY-FIELDS-CONTAINER').animate({scrollTop: 440}, 'slow');
            document.getElementById('dvQuery').innerHTML = res;
            Utils.showProgress(false);
        };
        self.BazeZamaniJS = function (fw) {
            Utils.showProgress(true);
            var PID = '';
            var azt = '';
            var tat = '';
            PID = FormOnly.allFieldsContianer[5].getData();
            azt = FormOnly.allFieldsContianer[0].getData();
            tat = FormOnly.allFieldsContianer[1].getData();
            if (azt == "") {
                $jq('#FORMONLY-FIELDS-CONTAINER').animate({scrollTop: 0}, 'slow');
                document.getElementById('dvQuery').innerHTML = '<div id="dvQueryFalse">تاريخ ابتدا بايد مقدار داشته باشد</div>';
                Utils.showProgress(false);
            } else if (tat == "") {
                $jq('#FORMONLY-FIELDS-CONTAINER').animate({scrollTop: 0}, 'slow');
                document.getElementById('dvQuery').innerHTML = '<div id="dvQueryFalse">تاريخ انتها بايد مقدار داشته باشد</div>';
                Utils.showProgress(false);
            } else {
                azt = azt.split('/');
                var azty = azt[0];
                var aztm = azt[1];
                if (aztm.length == '1') aztm = "0" + aztm;
                var aztd = azt[2];
                if (aztd.length == '1') aztd = "0" + aztd;
                azt = azty + '/' + aztm + '/' + aztd;
                tat = tat.split('/');
                var taty = tat[0];
                var tatm = tat[1];
                if (tatm.length == '1') tatm = "0" + tatm;
                var tatd = tat[2];
                if (tatd.length == '1') tatd = "0" + tatd;
                tat = taty + '/' + tatm + '/' + tatd;
                if (azt > tat) {
                    $jq('#FORMONLY-FIELDS-CONTAINER').animate({scrollTop: 0}, 'slow');
                    document.getElementById('dvQuery').innerHTML = '<div id="dvQueryFalse">تاريخ انتها از تاريخ ابتدا بايد بزرگتر باشد</div>';
                    Utils.showProgress(false);
                } else {
                    var res = Utils.fastAjax('WorkFlowAjaxFunc', 'BazrTimexList', {
                        aztd: aztd,
                        aztm: aztm,
                        azty: azty,
                        tatd: tatd,
                        tatm: tatm,
                        taty: taty,
                        PID: PID,
                        status: 'BazeZamani'
                    });
                    $jq('#FORMONLY-FIELDS-CONTAINER').animate({scrollTop: 360}, 'slow');
                    document.getElementById('dvQuery').innerHTML = res;
                    Utils.showProgress(false);
                }
            }
        };
        self.CreateForm = function (FormWFID, FormType, FormDate, FormCodeM) {
            res = WFInfo.startWorkflow(FormWFID);
            var FormDate_ID = '';
            var FormCodeM_ID = '';
            if (FormType == 'Eslah') {
                FormDate_ID = 13339;
                FormCodeM_ID = 13341;
            } else if (FormType == 'MorkhasiR') {
                FormDate_ID = 13381;
                FormCodeM_ID = 13383;
            } else if (FormType == 'MorkhasiS') {
                FormDate_ID = 13399;
                FormCodeM_ID = 13398;
            } else if (FormType == 'MamoriatS') {
                FormDate_ID = 13417;
                FormCodeM_ID = 13415;
            }
            Utils.fastAjax('NForms', 'setData', {
                data: '{"' + FormDate_ID + '":"' + FormDate + '", "' + FormCodeM_ID + '":"' + FormCodeM + '"}',
                docid: res.docID,
                fieldid: res.formID,
                referid: res.referID,
                ttype: 'form'
            });
            Viewer.init(null, res.referID, true, false, null, true, '../Runtime/process.php?module=Inbox&action=inboxData&draft=1', false, false, -1, null, null, null, 'newTab');
        };
        self.RunJS = function (fw) {
        };
    } catch (cc) {
        console.log(cc);
    }
}