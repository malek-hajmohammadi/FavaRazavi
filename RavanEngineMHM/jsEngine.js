
/*کد خالی برای فیلد تکمیل شونده*/
Class.create({
    load: function (self) {
        return [[0, "no data"]];
    }
});
/*نمونه فیلد تکمیل شوند که بهش ajax می دیم*/
Class.create({
    load: function (self) {
        return Utils.fastAjax('WorkFlowAjaxFunc', 'getDept');

    }
});

/*کد خالی برای فیلد محاسباتی*/
this.jcode = function(self){

}

/*کد خالی در لود فرم*/
listener = function (event) {
    var waitInterval = setInterval(function () {
        if (FormView && FormView.myForm) {

            FormView.myForm.getItemByName('Field_0').loadForm();
        }

        clearInterval(waitInterval);
    }, 300);
}

/*کد در رویداد در نود گردشکار*/
this.actJS = function (self) {
    return FormView.myForm.getItemByName('Field_21').btnConfirm();

};
/*فراخوانی آیجکس با callback*/
function template_ajaxWithCallback() {

    Utils.showProgress(true);
    /*o خروجی هست که میشه ازش استفاده کرد*/
    var gotResponse = function (o) {

        console.log("in gotResponse:---------- ");
        /*کدی که می خواهیم بعد از پاسخ اجرا بشه*/
        /*اگر بخواهیم خروجی بگیریم ازش بصورت زیر هست*/

        let listExcel=JSON.parse(o.responseText);
        self.output=listExcel;
        for(let i=0;i<listExcel.length;i++) {
            item = listExcel[i];
            let firstName = item['name'];
        }

        Utils.showProgress(false);
    };
    var callback = {
        success: gotResponse
    };
    var url = "../Runtime/process.php";
    var param = 'module=WorkFlowAjaxFunc&action=saveDetailedTable_eslahEzafekar&docId=' + FormView.docID + '&detailedTable=' + tem;
    SAMA.util.Connect.asyncRequest('POST', url, callback, param);
}