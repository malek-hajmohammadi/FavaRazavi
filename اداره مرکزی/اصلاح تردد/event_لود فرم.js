listener = function (event) {
    var reportInterval = setInterval(function () {
        if (FormView && FormView.myForm && FormView.myForm.getItemByName('Field_13') && FormView.myForm.getItemByName('Field_11') && FormView.myForm.getItemByName('Field_10') && FormView.myForm.getItemByName('Field_10').search) {
            FormView.myForm.getItemByName('Field_10').search(FormView);
            FormView.myForm.getItemByName('Field_11').viewMode.style.color = "#f00";
            clearInterval(reportInterval);
        }
    }, 100)
};