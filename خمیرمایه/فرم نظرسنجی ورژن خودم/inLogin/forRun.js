let isNeededToShowSurveyForm=Utils.fastAjax('WorkFlowAjaxFunc', 'checkUserDoneSurvey');
if(isNeededToShowSurveyForm==1){

    Utils.showModalMessage("ضمن تشکر، لطفا ابتدا نظرسنجی را تکمیل نمایید و سپس به اتوماسیون وارد شوید");

    MainHeader.paneNavigator();
    $jq('#MAINHEADER-PANE-IMG, #FORMVIEW-PAGER, #MAINHEADER-ID, #FORMONLY-TOOLBAR-ID').hide();
    $jq("td[onclick='eval(FormView.perArchive(undefined) )']").hide();

    Deck.selectURL("CONTENT-ID", "FormOnly", "modules/FormFlow/formOnly.js", Main.activeServer, "activeTab", {
        "structID": 32,
        "structName": "نظرسنجی"
    });
}
