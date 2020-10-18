this.jcode = function (self) {
    self.eslah = function (r, dty, dtm, dtd) {
        var pidv = document.getElementById('dvPID').innerHTML;
        var datev = dty + '/' + dtm + '/' + dtd;
        var res = Utils.fastAjax('WorkFlowAjaxFunc', 'numChange', {dt: datev, pd: pidv});
        var arr = res.split(":");
        var specificUsers = [1908, 5237]; /* commented for coronavirus */ /*if (specificUsers.indexOf(Main.UserInfo.id) >= 0) {             if (parseInt(arr[0]) > 15) {                 Utils.showMessage('شما به دليل استفاده بيش از 15 فرم در ماه مجاز به اين عمليات نمي باشيد');                 return;             }         } else if (parseInt(arr[0]) > 8) {             Utils.showMessage('شما به دليل استفاده بيش از 8 فرم در ماه مجاز به اين عمليات نمي باشيد');             return;         }*/
        var nowDate = Main.FirstPageParameters['datetime'].todayDate.split('/');
        var dateDiff = (parseInt(nowDate[1]) - parseInt(dtm));
        if (parseInt(nowDate[1]) < parseInt(dtm)) dateDiff = ((parseInt(nowDate[1]) + 12) - parseInt(dtm));                           /*if (parseInt(dtm) == 1 || parseInt(dtm) == 2 || parseInt(dtm) == 3 || (parseInt(dtm) == 12 && parseInt(dtd) >= 10))*/
        if ((dateDiff <= 1 || dateDiff >= 0)) {
            dt = dty + '/' + dtm + '/' + dtd;
            res = WFInfo.startWorkflow(r);
            Utils.fastAjax('NForms', 'setData', {
                data: '{"7380":"' + dt + '","7370":"' + dt + '","7371":"","7372":"0","7373":""}',
                docid: res.docID,
                fieldid: res.formID,
                referid: res.referID,
                ttype: 'form'
            });
            Viewer.init(null, res.referID, true, false, null, true, '../Runtime/process.php?module=Inbox&action=inboxData&draft=1', false, false, -1, null, null, null, 'newTab');
        } else {
            Utils.showMessage('<font color="red">شما مجاز به اصلاح ترددهاي اين تاريخ نمي‌باشيد</font>');
            return;
        }
    }
}