
/*کد خالی برای فیلد تکمیل شونده*/
Class.create({
    load: function (self) {
        return [[0, "no data"]];
    }
});
/*نمونه فیلد تکمیل شوند که بهش ajax می دیم*/
Class.create({
    load: function (self) {
        return Utils.fastAjax('WorkFlowAjaxFunc', 'getDept');

    }
});

/*کد خالی برای فیلد محاسباتی*/
this.jcode = function(self){

}

/*کد خالی در لود فرم*/
listener = function (event) {
    var waitInterval = setInterval(function () {
        if (FormView && FormView.myForm) {

            FormView.myForm.getItemByName('Field_0').loadForm();
        }

        clearInterval(waitInterval);
    }, 300);
}