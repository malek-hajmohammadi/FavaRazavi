listener = function (event) {

    class mainClass {


        answers=[];
        doreZamaniSearch(){

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
            var res = Utils.fastAjax('WorkFlowAjaxFunc', 'show_taradod_rooznameGhods', {
                mm: mm,
                yy: yy,
                PID: PID,
                status: 'DoreZamani'
            });
            $jq('#FORMONLY-FIELDS-CONTAINER').animate({scrollTop: 440}, 'slow');
            document.getElementById('dvQuery').innerHTML = res;
            Utils.showProgress(false);


        }
        bazeZamaniSearch(){

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
                    var res = Utils.fastAjax('WorkFlowAjaxFunc', 'show_taradod_rooznameGhods', {
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


        }

        createMamoriatRoozaneh(workflowId,formDate){
            Utils.showProgress(true);

            /*var codem = FormOnly.allFieldsContianer[0].getData();
            var Name = $jq('#f-Name').text();
            var CodeTural = $jq('#f-CodeTural').text();
            var FLocation = $jq('#f-Location').text();*/
            var res = WFInfo.startWorkflow(workflowId);

            let formDateTime=formDate+ " 00:00";
            Utils.fastAjax('NForms', 'setData', {
                data: '{"17799":"' + formDateTime + '"}',
                docid: res.docID,
                fieldid: res.formID,
                referid: res.referID,
                ttype: 'form'
            });
            Utils.showProgress(false);
            Viewer.init(null, res.referID, true, false, null, true, '../Runtime/process.php?module=Inbox&action=inboxData&draft=1', false, false, -1, null, null, null, '');

        }

        createMorkhasiRoozaneh(workflowId,formDate){

            Utils.showProgress(true);

            /*var codem = FormOnly.allFieldsContianer[0].getData();
            var Name = $jq('#f-Name').text();
            var CodeTural = $jq('#f-CodeTural').text();
            var FLocation = $jq('#f-Location').text();
            var MandeMorkhasi = $jq('#f-MandeMorkhasi').text();
            var MandeMorkhasiMerg = $jq('#f-MandeMorkhasiMerg').text();*/

            var res = WFInfo.startWorkflow(workflowId);


            Utils.fastAjax('NForms', 'setData', {
                /*data: '{"12436":"' + MandeMorkhasiMerg + '","12386":"' + MandeMorkhasi + '","11557":"' + date + '","11563":"0","11561":"' + codem + '","11556":"' + Name + '","11560":"' + CodeTural + '","11562":"' + FLocation + '"}',*/
                data: '{"17746":"' + formDate + '"}',
                docid: res.docID,
                fieldid: res.formID,
                referid: res.referID,
                ttype: 'form'
            });

            Utils.showProgress(false);
            Viewer.init(null, res.referID, true, false, null, true, '../Runtime/process.php?module=Inbox&action=inboxData&draft=1', false, false, -1, null, null, null, '');

        }

        createMamoriatSaati(workflowId,formDate){

            Utils.showProgress(true);

           /* var codem = FormOnly.allFieldsContianer[0].getData();
            var Name = $jq('#f-Name').text();
            var CodeTural = $jq('#f-CodeTural').text();
            var FLocation = $jq('#f-Location').text();*/

            var res = WFInfo.startWorkflow(workflowId);

           Utils.fastAjax('NForms', 'setData', {
                data: '{"17806":"' + formDate + '"}',
                docid: res.docID,
                fieldid: res.formID,
                referid: res.referID,
                ttype: 'form'
            });

            Utils.showProgress(false);

            Viewer.init(null, res.referID, true, false, null, true, '../Runtime/process.php?module=Inbox&action=inboxData&draft=1', false, false, -1, null, null, null, '');

        }

        createMorkhasiSaati(workflowId,formDate){

            Utils.showProgress(true);

            /*var codem = FormOnly.allFieldsContianer[0].getData();
            var Name = $jq('#f-Name').text();
            var CodeGPersonal = $jq('#f-GpersonalID').text();
            var CodeGCard = $jq('#f-CodeTural').text();
            var FLocation = $jq('#f-Location').text();
            var MandeMorkhasi = $jq('#f-MandeMorkhasi').text();
            var MandeMorkhasiMerg = $jq('#f-MandeMorkhasiMerg').text();*/

            var res = WFInfo.startWorkflow(workflowId);

            Utils.fastAjax('NForms', 'setData', {
                data: '{"17732":"' + formDate + '"}',
                docid: res.docID,
                fieldid: res.formID,
                referid: res.referID,
                ttype: 'form'
            });

            Utils.showProgress(false);

            Viewer.init(null, res.referID, true, false, null, true, '../Runtime/process.php?module=Inbox&action=inboxData&draft=1', false, false, -1, null, null, null, '');

        }




        loadForm() {

        }


    };

    var waitInterval = setInterval(function () {
        if (FormOnly) {

            let instance = new mainClass();
            window.codeSet = instance;
            window.codeSet.loadForm();
        }

        clearInterval(waitInterval);
    }, 300);

};
