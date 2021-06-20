listener = function (event) {

    var me = FormView.myForm.getItemByName('Field_4');
    var sdate = FormView.myForm.getItemByName('Field_2').getData();
    var edate = FormView.myForm.getItemByName('Field_3').getData();
    res = Utils.fastAjax('NForms', 'dateDiffHolidayCheck', {sdate: sdate, edate: edate}, true);
    if ((parseInt(res) + 1) <= 0) {
        me.setData('');
        self.showMSG('تاريخ پايان را اشتباه وارد نموده ايد');
    } else {
        me.setData(parseInt(res) + 1);
    }
}