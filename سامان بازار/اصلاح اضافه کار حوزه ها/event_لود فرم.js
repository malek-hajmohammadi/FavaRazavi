listener = function (event) {
    var waitInterval = setInterval(function () {
        if (FormView && FormView.myForm) {

            FormView.myForm.getItemByName('Field_0').loadForm();
        }

        clearInterval(waitInterval);
    }, 300);
}
