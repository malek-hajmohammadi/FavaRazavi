this.jcode = function (self) {
    self.add = function (fw) {
        if (FormView.myForm != undefined && FormView.myForm.getItemByName('Field_2') != undefined && FormView.myForm.getItemByName('Field_19') != undefined && FormView.myForm.getItemByName('Field_3') != undefined && FormView.myForm.getItemByName('Field_4') != undefined) {
            var p = 0;
            var res1 = FormView.myForm.getItemByName('Field_19').getData();
            var y = FormView.myForm.getItemByName('Field_3').getData();
            var m = FormView.myForm.getItemByName('Field_4').getData();
            y = parseInt(y) + 1395;
            m = parseInt(m) + 1;
            var res2 = Utils.fastAjax('WorkFlowAjaxFunc', 'monthlyovertime', {
                pid: res1,
                mid: FormView.docID,
                m: m,
                y: y
            });
            FormView.myForm.getItemByName('Field_2').list.subListView.listview.refresh();
            FormView.myForm.getItemByName('Field_19').setData('');
        }
    }
}