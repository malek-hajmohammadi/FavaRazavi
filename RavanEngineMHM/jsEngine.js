
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

