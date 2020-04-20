listener = function (event) {
    var state = 0;
    var waitInterval = setInterval(function () {
        if (FormView && FormView.myForm) {
            FormView.myForm.getItemByName('Field_31').changeBackgroundFieldSet();
            FormView.myForm.getItemByName('Field_31').addChangeEvent();
        }
        clearInterval(waitInterval);
    }, 300);
};