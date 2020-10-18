this.actJS = function (self) {

    let startDate=FormView.myForm.getItemByName('Field_3').getData();
    if(startDate.length<8) {
        Utils.showModalMessage('لطفا تاریخ ورود تجهیزات به دفتر مرکزی را وارد کنید');
        return false;
    }
    return true;

};

