let isNeededToShowCommuteForm=Utils.fastAjax('WorkFlowAjaxFunc', 'checkCommute');
if(isNeededToShowCommuteForm==1){
    Deck.selectURL("CONTENT-ID", "FormOnly", "modules/FormFlow/formOnly.js", Main.activeServer, "activeTab", {
        "structID": 59,
        "structName": "مشاهده تردد"
    });
}
