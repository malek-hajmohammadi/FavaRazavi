this.actJS = function (self) {

    let startDate=FormView.myForm.getItemByName('Field_5').getData();
    if(startDate.length<8) {
        Utils.showModalMessage('لطفا تاریخ دریافت کالا در مبدا را وارد کنید');
        return false;
    }
    return true;

};
