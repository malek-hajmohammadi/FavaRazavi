this.jcode = function (self) {
    self.CreateFormMorkhasiR = function (wfid, TDateY, TDateM, TDateD, PID) {
        var TDate = TDateY + '/' + TDateM + '/' + TDateD;
        res = WFInfo.startWorkflow(wfid);
        Utils.fastAjax('NForms', 'setData', {
            data: '{"896":"' + TDate + '", "907":"' + PID + '"}',
            docid: res.docID,
            fieldid: res.formID,
            referid: res.referID,
            ttype: 'form'
        });
        Viewer.init(null, res.referID, true, false, null, true, '../Runtime/process.php?module=Inbox&action=inboxData&draft=1', false, false, -1, null, null, null, 'newTab');
    };
    self.CreateFormMorkhasiS = function (wfid, TDateY, TDateM, TDateD, PID) {
        var TDate = TDateY + '/' + TDateM + '/' + TDateD;
        res = WFInfo.startWorkflow(wfid);
        Utils.fastAjax('NForms', 'setData', {
            data: '{"335":"' + TDate + '", "347":"' + PID + '", "337":17}',
            docid: res.docID,
            fieldid: res.formID,
            referid: res.referID,
            ttype: 'form'
        });
        Viewer.init(null, res.referID, true, false, null, true, '../Runtime/process.php?module=Inbox&action=inboxData&draft=1', false, false, -1, null, null, null, 'newTab');
    };
    self.CreateFormMamoriatS = function (wfid, TDateY, TDateM, TDateD, PID) {
        var TDate = TDateY + '/' + TDateM + '/' + TDateD;
        res = WFInfo.startWorkflow(wfid);
        Utils.fastAjax('NForms', 'setData', {
            data: '{"335":"' + TDate + '", "347":"' + PID + '", "337":9}',
            docid: res.docID,
            fieldid: res.formID,
            referid: res.referID,
            ttype: 'form'
        });
        Viewer.init(null, res.referID, true, false, null, true, '../Runtime/process.php?module=Inbox&action=inboxData&draft=1', false, false, -1, null, null, null, 'newTab');
    };
    self.CreateFormTaradodA = function (wfid, TDateY, TDateM, TDateD, PID) {
        var TDate = TDateY + '/' + TDateM + '/' + TDateD;
        res = WFInfo.startWorkflow(wfid);
        Utils.fastAjax('NForms', 'setData', {
            data: '{"335":"' + TDate + '", "347":"' + PID + '", "337":200}',
            docid: res.docID,
            fieldid: res.formID,
            referid: res.referID,
            ttype: 'form'
        });
        Viewer.init(null, res.referID, true, false, null, true, '../Runtime/process.php?module=Inbox&action=inboxData&draft=1', false, false, -1, null, null, null, 'newTab');
    };
    self.CreateFormTaradodH = function (wfid, TDateY, TDateM, TDateD, PID) {
        var TDate = TDateY + '/' + TDateM + '/' + TDateD;
        res = WFInfo.startWorkflow(wfid);
        Utils.fastAjax('NForms', 'setData', {
            data: '{"335":"' + TDate + '", "347":"' + PID + '", "337":300}',
            docid: res.docID,
            fieldid: res.formID,
            referid: res.referID,
            ttype: 'form'
        });
        Viewer.init(null, res.referID, true, false, null, true, '../Runtime/process.php?module=Inbox&action=inboxData&draft=1', false, false, -1, null, null, null, 'newTab');
    };
    self.CreateFormMamoriatR = function (wfid, TDateY, TDateM, TDateD, PID) {
        var TDate = TDateY + '/' + TDateM + '/' + TDateD;
        res = WFInfo.startWorkflow(wfid);
        Utils.fastAjax('NForms', 'setData', {
            data: '{"320":"' + TDate + '", "910":"' + PID + '"}',
            docid: res.docID,
            fieldid: res.formID,
            referid: res.referID,
            ttype: 'form'
        });
        Viewer.init(null, res.referID, true, false, null, true, '../Runtime/process.php?module=Inbox&action=inboxData&draft=1', false, false, -1, null, null, null, 'newTab');
    };
}