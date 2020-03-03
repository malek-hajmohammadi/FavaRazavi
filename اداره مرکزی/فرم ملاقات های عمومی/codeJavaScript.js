/*--کد برای چک کردن کارشناس اقدام کنند خالی نباشه ( در کارتابل معاونت--*/

this.actJS = function (self) {

    if (FormView.myForm.getItemByName('Field_16').data == "") {
        Utils.showModalMessage('فیلد کارشناس اقدام کننده خالی است !');
        return false;
    }
    return true;

};
/*--کد برای چک کردن کارشناس اقدام کننده بعدی در کارتابل کارشناس--*/
this.actJS = function (self) {

    if (FormView.myForm.getItemByName('Field_17').data == "") {
        Utils.showModalMessage('فیلد کارشناس اقدام کننده  بعدی خالی است !');
        return false;
    }
    return true;

};

/*--کد در لود فرم جهت چک و تغییر رنگ بخش فعال--*/


/*----------*/



listener = function (event) {
    var formload = setInterval(function () {

        /* --- */
        $jq('.c0').css("background-color", "#e0e0e0");
        $jq('.c1').css("background-color", "#e0e0e0");
        $jq('.c2').css("background-color", "#e0e0e0");
        $jq('.c3').css("background-color", "#e0e0e0");

        let stage=0;
        if (FormView.myForm.info.settings.nodeName) {
            var nodeName = FormView.myForm.info.settings.nodeName;
            var nodeName2 = nodeName;
            while (nodeName2 && nodeName2.indexOf(String.fromCharCode(1705)) >= 0) nodeName2 = nodeName2.replace(String.fromCharCode(1705), String.fromCharCode(1603));
            while (nodeName2 && nodeName2.indexOf(String.fromCharCode(1740)) >= 0) nodeName2 = nodeName2.replace(String.fromCharCode(1740), String.fromCharCode(1610));
            if (nodeName == 'دفتر-معاونت' || nodeName2 == 'دفتر-معاونت') stage = 1;
            if (nodeName == 'معاونت' || nodeName2 == 'معاونت') stage = 2;
            if (nodeName == 'کارشناس-اقدام کننده' || nodeName2 == 'کارشناس-اقدام کننده') stage = 3;
            if (nodeName == 'مسئول-دفتر' || nodeName2 == 'مسئول-دفتر') stage = 4;
            if (nodeName == 'بایگان' || nodeName2 == 'بایگان') stage = 5;

            switch (stage) {
                case  1 :
                    $jq('.c0').css("background-color", "#c5e1a5");
                    break;
                case 2:
                    $jq('.c1').css("background-color", "#c5e1a5");
                    break;
                case 3:
                    $jq('.c2').css("background-color", "#c5e1a5");
                    break;
                case 4:
                    $jq('.c3').css("background-color", "#c5e1a5");
                    break;
                case 5:
                    break;
            }



        }

        FormView.myForm.getItemByName('Field_17').setData([]);
        $jq('.stateTr').hide();
        /*---*/

        loadClear();
    }, 500);
    var loadClear = function () {
        clearInterval(formload);
    };
};





