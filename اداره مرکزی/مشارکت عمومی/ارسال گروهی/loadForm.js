listener = function (event) {
    var waitInterval = setInterval(function () {
        if (FormView && FormView.myForm) {
            var stage = FormView.myForm.getItemByName('Field_4').getData();
            switch (stage) {
                case '1':
                    var type = 0;
                    try {
                        type = FormView.myForm.getItemByName('Field_3').getData();
                    } catch (e) {
                    }
                    if (!type || parseInt(type) == 0) FormView.myForm.getItemByName('Field_3').setData(3);
                    break;
            }
            clearInterval(waitInterval);
        }
    }, 50);
}