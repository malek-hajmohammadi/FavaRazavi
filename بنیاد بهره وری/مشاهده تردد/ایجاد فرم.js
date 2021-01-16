this.jcode = function (self) {
    self.CreateFormMorkhasiR = function (wfid, TDateY, TDateM, TDateD, PID, GID) {
        var TDate = TDateY + '/' + TDateM + '/' + TDateD;
        res = WFInfo.startWorkflow(wfid);
        Utils.fastAjax('NForms', 'setData', {
            data: '{"17342":"' + TDate + '", "17363":"' + GID + '", "17338":"' + PID + '"}',
            docid: res.docID,
            fieldid: res.formID,
            referid: res.referID,
            ttype: 'form'
        });
        Viewer.init(null, res.referID, true, false, null, true, '../Runtime/process.php?module=Inbox&action=inboxData&draft=1', false, false, -1, null, null, null, 'newTab');
    };
    self.CreateFormMorkhasiS = function (wfid, TDateY, TDateM, TDateD, PID, GID) {
        var TDate = TDateY + '/' + TDateM + '/' + TDateD;
        res = WFInfo.startWorkflow(wfid);
        Utils.fastAjax('NForms', 'setData', {
            data: '{"17327":"' + TDate + '", "17364":"' + GID + '", "17332":"' + PID + '"}',
            docid: res.docID,
            fieldid: res.formID,
            referid: res.referID,
            ttype: 'form'
        });
        Viewer.init(null, res.referID, true, false, null, true, '../Runtime/process.php?module=Inbox&action=inboxData&draft=1', false, false, -1, null, null, null, 'newTab');
    };
    self.CreateFormMamoriatS = function (wfid, TDateY, TDateM, TDateD, PID, GID) {
        var TDate = TDateY + '/' + TDateM + '/' + TDateD;
        res = WFInfo.startWorkflow(wfid);
        Utils.fastAjax('NForms', 'setData', {
            data: '{"17302":"' + TDate + '", "17352":"' + GID + '", "17298":"' + PID + '"}',
            docid: res.docID,
            fieldid: res.formID,
            referid: res.referID,
            ttype: 'form'
        });
        Viewer.init(null, res.referID, true, false, null, true, '../Runtime/process.php?module=Inbox&action=inboxData&draft=1', false, false, -1, null, null, null, 'newTab');
    };
    self.CreateFormMamoriatR = function (wfid, TDateY, TDateM, TDateD, PID, GID) {
        var TDate = TDateY + '/' + TDateM + '/' + TDateD;
        res = WFInfo.startWorkflow(wfid);
        Utils.fastAjax('NForms', 'setData', {
            data: '{"17285":"' + TDate + '", "17296":"' + GID + '", "17291":"' + PID + '"}',
            docid: res.docID,
            fieldid: res.formID,
            referid: res.referID,
            ttype: 'form'
        });
        Viewer.init(null, res.referID, true, false, null, true, '../Runtime/process.php?module=Inbox&action=inboxData&draft=1', false, false, -1, null, null, null, 'newTab');
    };
}