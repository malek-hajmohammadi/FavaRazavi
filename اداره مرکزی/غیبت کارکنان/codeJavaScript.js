/*-----------------------------فیلد محاسباتی فرم ارسال گروهی غیبت کارکنان------------------------*/
this.jcode = function (self) {
    self.search = function (fw) {
        if (FormView && FormView.myForm) {
            var p = 0;
            var res1 = FormView.myForm.getItemByName('Field_0').getData();
            var y = FormView.myForm.getItemByName('Field_1').getData();
            var m = FormView.myForm.getItemByName('Field_2').getData();
            y = parseInt(y) + 1397;
            m = parseInt(m) + 1;
            var res2 = Utils.fastAjax('WorkFlowAjaxFunc', 'gheybatKarkonan', {
                pid: res1,
                mid: FormView.docID,
                m: m,
                y: y,
                t: 'del'
            });
            FormView.myForm.getItemByName('Field_5').list.subListView.listview.refresh();
            var stTypeObj = fw.myForm.getItemByName('Field_0');
            var pos = stTypeObj.ac.actb_ids.indexOf(stTypeObj.getData());
            if (pos >= 0) {
                var txt = stTypeObj.ac.actb_keywords[pos];
                fw.myForm.getItemByName('Field_6').setData(txt);
                fw.myForm.getItemByName('Field_7').setData(res2);
            }
        }
    };
}

/*-------------------------کد در عملیات های کارتابل زمان تایید------------------------------*/
this.actJS = function (self) {
    let isFilled=FormView.myForm.getItemByName('Field_10').data;
    if(isFilled==0)
        return true;
    else{
        alert("لطفا نظر خود را در مورد غیبت فرد انتخاب کنید ");
        return false;
    }
};
