this.jcode = function (self) {
    self.parentTr = null;
    self.removeAction = function (id, element) {
        self.parentTr = element;
        Utils.confirmMsg('آيا نسبت به حذف اين اقدام مطمئن هستيد', 'FormView.myForm.getItemByName(\'Field_28\').doRemoveAction(' + id + ')');
    };
    self.doRemoveAction = function (id) {
        res = Utils.fastAjax('WorkFlowAjaxFunc', 'sepasRemoveEghdam', {id: id});
        if (res == 'true') {
            Utils.showMessage('عمليات حذف با موفقيت انجام شد');
            self.parentTr.closest('tr').remove();
        } else Utils.showModalMessage('خطا در انجام عمليات(' + res + ')');
    };
};