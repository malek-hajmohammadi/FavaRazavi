this.jcode = function (self) {
    try {

        listener = function (event) {
            setTimeout(function () {
                $jq('.f-box *').removeAttr('title');
                FormOnly.allFieldsContianer[2].LoadJS();
            }, 500);
        }


        self.LoadJS = function (time, id) {
            var codem = Main['UserInfo']['NationalCode'];
            FormOnly.allFieldsContianer[0].setData(codem);
            var today = Main["FirstPageParameters"]["datetime"]["todayDate"];
            aa = today.split('/');
            yy = aa[0];
            mm = aa[1];
            dd = aa[2];
            azt = yy + '/01/01';
            tat = yy + '/' + mm + '/' + dd;             /*FormOnly.allFieldsContianer[0].setData(azt);*/             /*FormOnly.allFieldsContianer[1].setData(tat);*/
            switch (yy) {
                case '1398':
                    yy = 0;
                    break;
                case '1399':
                    yy = 1;
                    break;
                case '1400':
                    yy = 2;
                    break;
                case '1401':
                    yy = 3;
                    break;
                default:
                    yy = 0;
            }
            mm--;
            FormOnly.allFieldsContianer[1].setData(parseInt(mm));
            FormOnly.allFieldsContianer[2].setData(yy);
        };
        self.DoreZamaniJS = function (fw) {
            Utils.showProgress(true);
            var codem = '';
            var mm = '';
            var yy = '';
            codem = FormOnly.allFieldsContianer[0].getData();
            mm = FormOnly.allFieldsContianer[1].getData();
            mm++;
            if (mm.toString().length == 1) mm = '0' + parseInt(mm);
            yy = FormOnly.allFieldsContianer[2].getData();
            yy = parseInt(yy) + 1398;
            var res = Utils.fastAjax('WorkFlowAjaxFunc', 'TaminTimexList', {
                mm: mm,
                yy: yy,
                codem: codem,
                status: 'DoreZamani'
            });
            $jq('#FORMONLY-FIELDS-CONTAINER').animate({scrollTop: 440}, 'slow');
            document.getElementById('dvQuery').innerHTML = res;
            Utils.showProgress(false);
        };
        self.BazeZamaniJS = function (fw) {
            Utils.showProgress(true);
            var codem = '';
            var azt = '';
            var tat = '';
            codem = FormOnly.allFieldsContianer[0].getData();
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

    /*ایجاد فرم ها*/

    self.MorkhasiS = function (wfid, date) {
        Utils.showProgress(true);
        var codem = FormOnly.allFieldsContianer[0].getData();
        var Name = $jq('#f-Name').text();

        var CodeGPersonal = $jq('#f-GpersonalID').text();
        var CodeGCard = $jq('#f-CodeTural').text();

        var FLocation = $jq('#f-Location').text();
        var MandeMorkhasi = $jq('#f-MandeMorkhasi').text();
        var MandeMorkhasiMerg = $jq('#f-MandeMorkhasiMerg').text();

        var res = WFInfo.startWorkflow(wfid);
        Utils.fastAjax('NForms', 'setData', {
            data: '{"11957":"' + MandeMorkhasiMerg + '","11955":"' + MandeMorkhasi + '","11946":"' + date + '","11954":"0","11949":"' + codem + '","11945":"' + Name + '","11950":"' + CodeGPersonal + '","13804":"'+CodeGCard+'","11952":"' + FLocation + '"}',
            docid: res.docID,
            fieldid: res.formID,
            referid: res.referID,
            ttype: 'form'
        });
        Utils.showProgress(false);
        Viewer.init(null, res.referID, true, false, null, true, '../Runtime/process.php?module=Inbox&action=inboxData&draft=1', false, false, -1, null, null, null, '');
    },
    self.MorkhasiR = function (wfid, date) {
            Utils.showProgress(true);
            var codem = FormOnly.allFieldsContianer[0].getData();
            var Name = $jq('#f-Name').text();
            var CodeTural = $jq('#f-CodeTural').text();
            var FLocation = $jq('#f-Location').text();
            var MandeMorkhasi = $jq('#f-MandeMorkhasi').text();
            var MandeMorkhasiMerg = $jq('#f-MandeMorkhasiMerg').text();
            var res = WFInfo.startWorkflow(wfid);
            Utils.fastAjax('NForms', 'setData', {
                data: '{"12436":"' + MandeMorkhasiMerg + '","12386":"' + MandeMorkhasi + '","11557":"' + date + '","11563":"0","11561":"' + codem + '","11556":"' + Name + '","11560":"' + CodeTural + '","11562":"' + FLocation + '"}',
                docid: res.docID,
                fieldid: res.formID,
                referid: res.referID,
                ttype: 'form'
            });
            Utils.showProgress(false);
            Viewer.init(null, res.referID, true, false, null, true, '../Runtime/process.php?module=Inbox&action=inboxData&draft=1', false, false, -1, null, null, null, '');
        },






    self.eslah = function (wfid, date) {
        Utils.showProgress(true);
        var codem = FormOnly.allFieldsContianer[0].getData();
        var EslahNum = $jq('#f-EslahNum').text();
        var Name = $jq('#f-Name').text();

        var CodeGPersonal = $jq('#f-GpersonalID').text();
        var FLocation = $jq('#f-Location').text();
        var res = WFInfo.startWorkflow(wfid);
        Utils.fastAjax('NForms', 'setData', {
            data: '{"11082":"' + date + '","11086":"' + date + '","11143":"0","11173":"' + codem + '","11408":"' + Name + '","11409":"' + CodeGPersonal + '","11410":"' + FLocation + '","11174":"' + EslahNum + '"}',
            docid: res.docID,
            fieldid: res.formID,
            referid: res.referID,
            ttype: 'form'
        });
        Utils.showProgress(false);
        Viewer.init(null, res.referID, true, false, null, true, '../Runtime/process.php?module=Inbox&action=inboxData&draft=1', false, false, -1, null, null, null, '');
    },

    self.MamoriatR = function (wfid, date) {
            Utils.showProgress(true);
            var codem = FormOnly.allFieldsContianer[0].getData();
            var Name = $jq('#f-Name').text();
            var CodeTural = $jq('#f-CodeTural').text();
            var FLocation = $jq('#f-Location').text();
            var res = WFInfo.startWorkflow(wfid);
            Utils.fastAjax('NForms', 'setData', {
                data: '{"11929":"' + date + '","11935":"0","11933":"' + codem + '","11928":"' + Name + '","11932":"' + CodeTural + '","11934":"' + FLocation + '"}',
                docid: res.docID,
                fieldid: res.formID,
                referid: res.referID,
                ttype: 'form'
            });
            Utils.showProgress(false);
            Viewer.init(null, res.referID, true, false, null, true, '../Runtime/process.php?module=Inbox&action=inboxData&draft=1', false, false, -1, null, null, null, '');
        },
    self.MamoriatS = function (wfid, date) {
            Utils.showProgress(true);
            var codem = FormOnly.allFieldsContianer[0].getData();
            var Name = $jq('#f-Name').text();
            var CodeTural = $jq('#f-CodeTural').text();
            var FLocation = $jq('#f-Location').text();
            var res = WFInfo.startWorkflow(wfid);
            Utils.fastAjax('NForms', 'setData', {
                data: '{"11962":"' + date + '","11970":"0","11965":"' + codem + '","11961":"' + Name + '","11966":"' + CodeTural + '","11968":"' + FLocation + '"}',
                docid: res.docID,
                fieldid: res.formID,
                referid: res.referID,
                ttype: 'form'
            });
            Utils.showProgress(false);
            Viewer.init(null, res.referID, true, false, null, true, '../Runtime/process.php?module=Inbox&action=inboxData&draft=1', false, false, -1, null, null, null, '');
        }

}