this.jcode = function (self) {

        self.LoadJS = function (time, id) {

            /*var PID = Main['UserInfo']['employeeID'];*/
            let PID="101";

            FormOnly.allFieldsContianer[6].setData(PID);
            $jq('.BazarPID').text(PID);
            var today = Main["FirstPageParameters"]["datetime"]["todayDate"];
            aa = today.split('/');
            yy = aa[0];
            mm = aa[1];
            dd = aa[2];
            azt = yy + '/01/01';
            tat = yy + '/' + mm + '/' + dd;
            FormOnly.allFieldsContianer[0].setData(azt);
            FormOnly.allFieldsContianer[1].setData(tat);
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
                default:
                    yy = 0;
            }
            mm--;
            FormOnly.allFieldsContianer[2].setData(parseInt(mm));
            FormOnly.allFieldsContianer[3].setData(yy);
        };
        self.DoreZamaniJS = function (fw) {
            Utils.showProgress(true);
            var PID = '';
            var mm = '';
            var yy = '';
            PID = FormOnly.allFieldsContianer[6].getData();
            mm = FormOnly.allFieldsContianer[2].getData();
            mm++;
            if (mm.toString().length == 1) mm = '0' + parseInt(mm);
            yy = FormOnly.allFieldsContianer[3].getData();
            yy = parseInt(yy) + 1398;
            let parameters={
                mm: mm,
                yy: yy,
                PID: PID,
                status: 'DoreZamani'
            };
            console.log(parameters);

            var res = Utils.fastAjax('WorkFlowAjaxFunc', 'KasboKarTimexList', parameters);
            $jq('#FORMONLY-FIELDS-CONTAINER').animate({scrollTop: 440}, 'slow');
            document.getElementById('dvQuery').innerHTML = res;
            Utils.showProgress(false);
        };
        self.BazeZamaniJS = function (fw) {
            Utils.showProgress(true);
            var PID = '';
            var azt = '';
            var tat = '';
            PID = FormOnly.allFieldsContianer[6].getData();
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
                    let parameters={
                        aztd: aztd,
                        aztm: aztm,
                        azty: azty,
                        tatd: tatd,
                        tatm: tatm,
                        taty: taty,
                        PID: PID,
                        status: 'BazeZamani'
                    };

                    console.log(parameters);

                    var res = Utils.fastAjax('WorkFlowAjaxFunc', 'KasboKarTimexList', parameters);
                    $jq('#FORMONLY-FIELDS-CONTAINER').animate({scrollTop: 360}, 'slow');
                    document.getElementById('dvQuery').innerHTML = res;
                    Utils.showProgress(false);
                }
            }
        };

        self.MorkhasiS = function (wfid, date) {
        Utils.showProgress(true);

        var res = WFInfo.startWorkflow(wfid);
        Utils.fastAjax('NForms', 'setData', {
            data: '{"519":"' + date + '"}',
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

            var res = WFInfo.startWorkflow(wfid);
            Utils.fastAjax('NForms', 'setData', {
                data: '{"507":"' + date + '"}',
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

            var res = WFInfo.startWorkflow(wfid);
            Utils.fastAjax('NForms', 'setData', {
                data: '{"547":"' + date + '"}',
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

            var res = WFInfo.startWorkflow(wfid);
            Utils.fastAjax('NForms', 'setData', {
                data: '{"524":"' + date + '"}',
                docid: res.docID,
                fieldid: res.formID,
                referid: res.referID,
                ttype: 'form'
            });
            Utils.showProgress(false);
            Viewer.init(null, res.referID, true, false, null, true, '../Runtime/process.php?module=Inbox&action=inboxData&draft=1', false, false, -1, null, null, null, '');
        }


};

