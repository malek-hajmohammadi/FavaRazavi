/*-- قسمت جاوا اسکرییپت .فیلد محاسباتی در فرم سپاس برای نمایش دادن سابقه اقدامات یا فیلد جزء مربوط به آن--*/
this.jcode = function (self) {
    self.parentTr = null;
    self.removeAction = function (id, element) {
        self.parentTr = element;
        Utils.confirmMsg('آيا نسبت به حذف اين اقدام مطمئن هستيد', 'FormView.myForm.getItemByName(\'Field_41\').doRemoveAction(' + id + ')');
    };
    self.doRemoveAction = function (id) {
        res = Utils.fastAjax('WorkFlowAjaxFunc', 'shekayatActionRemove', {id: id});
        if (res == 'true') {
            Utils.showMessage('عمليات حذف با موفقيت انجام شد');
            self.parentTr.closest('tr').remove();
        } else Utils.showModalMessage('خطا در انجام عمليات(' + res + ')');
    };
};
/*-----------------------------کد در لود فرم------------------------------*/
listener = function (event) {
    var waitInterval = setInterval(function () {
        if (FormView && FormView.myForm ) {

            $jq('.c0').css("background-color", "#e0e0e0");
            $jq('.c1').css("background-color", "#e0e0e0");
            $jq('.c2').css("background-color", "#e0e0e0");
            $jq('.c3').css("background-color", "#e0e0e0");
            $jq('.c4').css("background-color", "#e0e0e0");

            $jq('.operator >input').attr("readonly","true");

            let stage=0;
            if (FormView.myForm.info.settings.nodeName) {
                var nodeName = FormView.myForm.info.settings.nodeName;
                var nodeName2 = nodeName;
                while (nodeName2 && nodeName2.indexOf(String.fromCharCode(1705)) >= 0) nodeName2 = nodeName2.replace(String.fromCharCode(1705), String.fromCharCode(1603));
                while (nodeName2 && nodeName2.indexOf(String.fromCharCode(1740)) >= 0) nodeName2 = nodeName2.replace(String.fromCharCode(1740), String.fromCharCode(1610));
                if (nodeName == 'اپراتور-سپاس' || nodeName2 == 'اپراتور-سپاس') stage = 1;
                if (nodeName == 'مافوق-سپاس' || nodeName2 == 'مافوق-سپاس') stage = 2;
                if (nodeName == 'اقدام-کننده-اولیه' || nodeName2 == 'اقدام-کننده-اولیه') stage = 3;
                if (nodeName == 'کارشناس-اقدام-کننده' || nodeName2 == 'کارشناس-اقدام-کننده') stage = 4;
                if (nodeName == 'مافوق-سپاس-نهایی' || nodeName2 == 'مافوق-سپاس-نهایی') stage = 5;
                if (nodeName == 'پیگیری-کننده' || nodeName2 == 'پیگیری-کننده') stage = 6;


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
                        $jq('.c2').css("background-color", "#c5e1a5");
                        break;
                    case 5:
                        $jq('.c3').css("background-color", "#c5e1a5");
                        break;
                }

            }

        }
        clearInterval(waitInterval);
    }, 50);
};
/*--------------کد در دکمه جهت اقدام در کارتابل مافوق سپاس--------------*/
this.actJS = function(self){
    var user = FormView.myForm.getItemByName('Field_20').getData();
    if(user.length == 0){
        Utils.showModalMessage('لطفا کاربر اقدام کننده را مشخص فرمایید');
        return false;
    }
    var comment = FormView.myForm.getItemByName('Field_23').getData();
    if(comment.length < 20){
        Utils.showModalMessage('لطفا فیلد شرح درخواست اقدام را پر کنید (حداقل 20 کاراکتر)');
        return false;
    }
    return true;
};

/*------------کد در ارسال به شخص دیگر در کارتابل  اقدام کننده اولیه------------*/
this.actJS = function(self){
    var user = FormView.myForm.getItemByName('Field_21').getData();
    if(user.length == 0){
        Utils.showModalMessage('لطفا کاربر اقدام کننده بعدی را مشخص فرمایید');
        return false;
    }

    return true;
};

/*------------کد در اعلام نتیجه  در کارتابل  اقدام کننده اولیه------------*/
this.actJS = function(self){
    var user = FormView.myForm.getItemByName('Field_22').getData();
    if(user.length <50){
        Utils.showModalMessage('لطفا فیلد گزارش اعلام نتیجه رو پر کنید (حداقل 50 کاراکتر) ');
        return false;
    }

    return true;
};
