self.saveToDb = function () {
    console.log(self.listGuest);
    console.log("in save:---------- ");
    var tem = JSON.stringify(self.listGuest);
    console.log(tem);
    Utils.showProgress(true);


    var gotResponse = function (o) {
        var Hadith = eval(o.responseText);

        Utils.showProgress(false);
        html = self.showTable();
        $jq('.tableGuest').html(html);
        self.setDateObjectAll();
        self.isSaved = true;
        self.fillGuestList();
        self.isValid = self.checkValidGuest();


    };

    var callback = {
        success: gotResponse
    };
    var url = "../Runtime/process.php";
    var param = 'module=WorkFlowAjaxFunc&action=saveAndConsiderGuest_mehmansara&docId=' + FormView.docID + '&listGuest=' + tem;
    SAMA.util.Connect.asyncRequest('POST', url, callback, param);
}