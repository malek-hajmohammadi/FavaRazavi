listener = function (event) {

    var waitInterval = setInterval(function () {
        if (FormView && FormView.myForm) {

            FormView.myForm.getItemByName('Field_52').loadForm();
            clearInterval(waitInterval);

        }

    }, 20);
};