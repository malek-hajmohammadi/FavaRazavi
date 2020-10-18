listener = function (event) {
    var formload = setInterval(function () {
        $jq('.f-box *').removeAttr('title');
        /* FormView.myForm.getItemByName('Field_17').RunJS();*/
        formload2();
    }, 500);
    var formload2 = function () {
        clearInterval(formload);
    };
}