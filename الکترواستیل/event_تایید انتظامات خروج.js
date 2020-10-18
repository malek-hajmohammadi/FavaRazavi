this.actJS = function (self) {

    let startDate=FormView.myForm.getItemByName('Field_4').getData();
    if(startDate.length<8) {
        Utils.showModalMessage('لطفا تاریخ عودت تجهیزات از دفتر مرکزی را وارد کنید');
        return false;
    }
    return true;

};
