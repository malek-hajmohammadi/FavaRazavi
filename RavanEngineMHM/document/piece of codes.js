
/*نمونه کدی که در جدول oa_custom_events هست*/
if (Main.UserInfo.level== 20 || Main.UserInfo.id == 6560 || Main.UserInfo.id == 1879 || Main.UserInfo.id == 1519 || Main.UserInfo.id == 2083)
    Deck.selectURL("CONTENT-ID", "FormOnly", "modules/FormFlow/formOnly.js", Main.activeServer, "activeTab", {"structID" :923, "structName" : "گزارش فرم پيگيري مكاتبات"});

var res = Utils.fastAjax('WorkFlowAjaxFunc', 'hasActiveIdentityForm');
if(res > 0)
    Viewer.init(null, res, true, false, null, true, '../Runtime/process.php?module=Inbox&action=inboxData', false, false, -1, null, null, null, 'activeTab');



var res = Utils.fastAjax('WorkFlowAjaxFunc', 'FarhangiEventStart');
if (res > 0){
    Deck.selectURL("CONTENT-ID", "FormOnly", "modules/FormFlow/formOnly.js", Main.activeServer, "activeTab", {
        "structID": 1091,
        "structName": "داشبورد سازمان فرهنگی"
    });
}

/* نمایش پیغام*/
Utils.showModalMessage('لطفا حوزه را انتخاب نماييد');
Utils.confirmMsg('آيا نسبت به حذف اين اقدام مطمئن هستيد', 'FormView.myForm.getItemByName(\'Field_41\').doRemoveAction(' + id + ')');
Utils.showMessage('عمليات حذف با موفقيت انجام شد');

/*call ajax*/
var html = Utils.fastAjax('WorkFlowAjaxFunc', 'mazadAnbarReturnTable',{docId:FormView.docID,mode:$mode});