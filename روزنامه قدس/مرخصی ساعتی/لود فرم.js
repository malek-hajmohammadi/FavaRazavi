listener = function (event) {

    class mainClass {



        loadForm() {
           /* FormView.myForm.getItemByName('Field_5').setData(Main.UserInfo.employeeID);*/

        }


    };

    var waitInterval = setInterval(function () {
        if (FormView && FormView.myForm) {

            let instance = new mainClass();
            window.codeSet = instance;
            window.codeSet.loadForm();
        }

        clearInterval(waitInterval);
    }, 300);

};

