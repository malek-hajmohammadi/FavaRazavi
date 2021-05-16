listener = function (event) {

    class mainClass {



        loadForm() {

        }

        setAvg3sal() {
            let total = 0;
            let a = FormView.myForm.getItemByName('Field_12').getData();
            if (a == "") a = 0;
            a=parseInt(a);
            let b = FormView.myForm.getItemByName('Field_14').getData();
            if (b == "") b = 0;
            b=parseInt(b);
            let c = FormView.myForm.getItemByName('Field_16').getData();
            if (c == "") c = 0;
            c=parseInt(c);
            total = a + b + c;
            total = total / 3;
            FormView.myForm.getItemByName('Field_100').setData(total);
        };


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
