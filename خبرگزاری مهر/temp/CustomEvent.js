

if (Main.UserInfo.level == 20 || Main.UserInfo.id == 6560 || Main.UserInfo.id == 1879 || Main.UserInfo.id == 1519 || Main.UserInfo.id == 2083)
Deck.selectURL("CONTENT-ID", "FormOnly", "modules/FormFlow/formOnly.js", Main.activeServer, "activeTab", {
    "structID": 923,
    "structName": "گزارش فرم پيگيري مكاتبات"
});


var res = Utils.fastAjax('WorkFlowAjaxFunc', 'hasActiveIdentityForm');
if (res > 0)
Viewer.init(null, res, true, false, null, true, '../Runtime/process.php?module=Inbox&action=inboxData', false, false, -1, null, null, null, 'activeTab');

var res = Utils.fastAjax('WorkFlowAjaxFunc', 'FarhangiEventStart');
if (res > 0){
    Deck.selectURL("CONTENT-ID", "FormOnly", "modules/FormFlow/formOnly.js", Main.activeServer, "activeTab", {
        "structID": 1091,
        "structName": "داشبورد سازمان فرهنگی"
    });
}
