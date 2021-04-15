Class.create({
    load: function (self) {

        res = Utils.fastAjax('WorkFlowAjaxFunc', 'getDepts');
        return res;
    }
})
