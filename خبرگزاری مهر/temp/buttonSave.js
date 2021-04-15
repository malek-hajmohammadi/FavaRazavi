this.jcode = function(self) {
    try {

        self.ButtonSave = function(fw) {
            Utils.showProgress(true);

            var FildeVazeiyat = '';

            FildeVazeiyat = FormView.myForm.getItemByName('Field_2').getData();
            var res = Utils.fastAjax('WorkFlowAjaxFunc', 'FormConfig', {
                flag: FildeVazeiyat
            });
            Utils.showProgress(false);

        }


        self.RunJS = function(fw) {};
    } catch (cc) { console.log(cc); }
}