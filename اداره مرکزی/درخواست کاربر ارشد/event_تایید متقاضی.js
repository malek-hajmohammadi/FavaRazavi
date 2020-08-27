
this.actJS = function (self) {
    var requestText =  FormView.myForm.getItemByName('Field_3').getData();
    if (requestText.length < 3) {
        Utils.showModalMessage('لطفا شرح درخواست را ذکر کنید');
        return false;
    }



    var selected= FormView.myForm.getItemByName('Field_2').getData();

    if (selected.includes("23") || selected.includes("24")) {
        var docId=FormView.docID;
        var countAttachFiles=Utils.fastAjax('WorkFlowAjaxFunc', 'countAttachFiles',{docId:docId});
        if(countAttachFiles<1){
            Utils.showModalMessage('لطفا فایل های مورد نیاز را پیوست کنید');
            return false;
        }
    }

    if (selected.includes("4") || selected.includes("41")|| selected.includes("42")
        || selected.includes("43")|| selected.includes("44")|| selected.includes("45")) {

        Utils.showMessage('فقط خصوص اصلاحات نگارشي نامه و قبل از مشاهده توسط گيرندگان نامه صورت مي پذيرد.');
    }



    return true;

};

