listener = function (event) {
    var state = 0;
    var waitInterval = setInterval(function () {
        if (FormView && FormView.myForm) {
            html = FormView.myForm.getItemByName('Field_1').showTable();
            $jq('.tableSpan').html(html);
            
        }
        clearInterval(waitInterval);
    }, 300);
};