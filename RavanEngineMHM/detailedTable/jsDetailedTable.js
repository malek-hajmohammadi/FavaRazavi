this.jcode = function (self) {
    /*برای اضافه کردن در کد دونی*/
    self.DetailedTable = {
        showMode: "edit",
        showTable: function (stage) {

            switch (stage) {
                case "1":
                    self.showMode = "edit";
                    break;
                case "2":
                    self.showMode = "readOnly";
                    break;
                case "4":
                    self.showMode = "justDelete";
                    break;

            }
            var html = Utils.fastAjax('WorkFlowAjaxFunc', 'showTable_eslaheEzafekar', {
                docId: FormView.docID, mode: this.showMode
            });
            return html;
        }


    }
};

