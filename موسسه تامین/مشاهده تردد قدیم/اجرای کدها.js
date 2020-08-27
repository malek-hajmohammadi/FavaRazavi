this.jcode = function (self) {
    self.eslah = function (wfid, date) {
        Utils.showProgress(true);
        var codem = FormOnly.allFieldsContianer[9].getData();
        var EslahNum = $jq('#f-EslahNum').text();
        var Name = $jq('#f-Name').text();
        var CodeTural = $jq('#f-CodeTural').text();
        var FLocation = $jq('#f-Location').text();
        var res = WFInfo.startWorkflow(wfid);
        Utils.fastAjax('NForms', 'setData', {
            data: '{"11082":"' + date + '","11086":"' + date + '","11143":"0","11173":"' + codem + '","11408":"' + Name + '","11409":"' + CodeTural + '","11410":"' + FLocation + '","11174":"' + EslahNum + '"}',
            docid: res.docID,
            fieldid: res.formID,
            referid: res.referID,
            ttype: 'form'
        });
        Utils.showProgress(false);
        Viewer.init(null, res.referID, true, false, null, true, '../Runtime/process.php?module=Inbox&action=inboxData&draft=1', false, false, -1, null, null, null, '');
    }, self.MorkhasiR = function (wfid, date) {
        Utils.showProgress(true);
        var codem = FormOnly.allFieldsContianer[9].getData();
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
    }, self.MorkhasiS = function (wfid, date) {
        Utils.showProgress(true);
        var codem = FormOnly.allFieldsContianer[9].getData();
        var Name = $jq('#f-Name').text();
        var CodeTural = $jq('#f-CodeTural').text();
        var FLocation = $jq('#f-Location').text();
        var MandeMorkhasi = $jq('#f-MandeMorkhasi').text();
        var MandeMorkhasiMerg = $jq('#f-MandeMorkhasiMerg').text();
        var res = WFInfo.startWorkflow(wfid);
        Utils.fastAjax('NForms', 'setData', {
            data: '{"11957":"' + MandeMorkhasiMerg + '","11955":"' + MandeMorkhasi + '","11946":"' + date + '","11954":"0","11949":"' + codem + '","11945":"' + Name + '","11950":"' + CodeTural + '","11952":"' + FLocation + '"}',
            docid: res.docID,
            fieldid: res.formID,
            referid: res.referID,
            ttype: 'form'
        });
        Utils.showProgress(false);
        Viewer.init(null, res.referID, true, false, null, true, '../Runtime/process.php?module=Inbox&action=inboxData&draft=1', false, false, -1, null, null, null, '');
    }, self.MamoriatR = function (wfid, date) {
        Utils.showProgress(true);
        var codem = FormOnly.allFieldsContianer[9].getData();
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
    }, self.MamoriatS = function (wfid, date) {
        Utils.showProgress(true);
        var codem = FormOnly.allFieldsContianer[9].getData();
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