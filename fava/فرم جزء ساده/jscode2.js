this.jcode = function (self) {
    try {
        self.LoadJS = function (fw) {

            var res = Utils.fastAjax('WorkFlowAjaxFunc', 'TESTHAJ',{
                    a: 5,b:3
            });
            alert(res);
            //FormView.myForm.getItemByName('Field_24').setData(res);

        };
    } catch (e) {
        console.log(e);
    }
}