this.jcode = function (self) {
    try {
        self.LoadJS = function (fw) {
            var userName=FormView.myForm.getItemByName('Field_1').getData();
            var res = Utils.fastAjax('WorkFlowAjaxFunc', 'TESTHAJ',{masterId:FormView.did});

            FormView.myForm.getItemByName('Field_2').list.subListView.listview.refresh();
        };
    } catch (e) {
        console.log(e);
    }
}