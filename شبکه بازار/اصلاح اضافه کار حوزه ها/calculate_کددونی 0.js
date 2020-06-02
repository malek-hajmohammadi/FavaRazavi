this.jcode = function(self){

    /*برای اضافه کردن در کد دونی*/
    self.DetailedTable = {
        showMode: "edit",
        showTable: function (stage) {
            this.showMode=stage;
            var html = Utils.fastAjax('WorkFlowAjaxFunc', 'showTable_eslaheEzafekar', {
                docId: FormView.docID, mode: "edit"
            });
            return html;
        }
    };
    self.loadForm=function(){
        let html =self.DetailedTable.showTable();
        $jq('.detailedTableSpan').html(html);


    };

}