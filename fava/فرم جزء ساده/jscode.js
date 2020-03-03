this.jcode = function (self) {
    try {
        self.LoadJS = function (fw) {
            alert('باز کردن');
            var res = Utils.fastAjax('WorkFlowAjaxFunc', 'createUsersList',{
                    masterID: FormView.docID
            });
            

        };
    } catch (e) {
        console.log(e);
    }
}

/***
 * the method of using Ajax here is quite simple. First parameter is constant at least for me right now.
 * refresh(): update the detailed form.
 */