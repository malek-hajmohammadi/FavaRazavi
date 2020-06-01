Class.create({
    load: function (self) {
        return Utils.fastAjax('WorkFlowAjaxFunc', 'getDept');
    }
});
