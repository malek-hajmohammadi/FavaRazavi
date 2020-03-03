this.actJS = function (self) {
    var vade = FormView.myForm.getItemByName('Field_5').getData();
    if (vade == 0) {
        Utils.showModalMessage('لطفا وعده پیشنهادی خود را انتخاب کنید');
        return false;
    }

    var hoze = FormView.myForm.getItemByName('Field_0').getData();
    if (hoze == "") {
        Utils.showModalMessage('حوزه متقاضی مشخص نشده');
        return false;
    }
    var mLen = FormView.myForm.getItemByName('Field_4').list.subListView.data.length;
    if (mLen == 0) {
        Utils.showModalMessage('لیست افراد نمیتواند خالی باشد.');
        return false;
    }

    /* date diff check*/
    var d1 = Main.FirstPageParameters.datetime.todayDate;
    var d2 = FormView.myForm.getItemByName('Field_1').getData();

    d1 = d1.split('/');
    d2 = d2.split('/');

    if (parseInt(d1[0]) == parseInt(d2[0]) && parseInt(d1[1]) == parseInt(d2[1]) && parseInt(d1[2]) == parseInt(d2[2]) && false) {
        Utils.showModalMessage('امکان رزرو برای امروز وجود ندارد');
        return false;
    }

    /* convert dates to days*/
    var days1 = 0;
    var m1 = parseInt(d1[1]);
    if (m1 < 7)
        days1 = (m1 - 1) * 31 + parseInt(d1[2]);
    else
        days1 = (m1 - 1) * 30 + 6 + parseInt(d1[2]);

    var days2 = 0;
    var m2 = parseInt(d2[1]);
    if (m2 < 7)
        days2 = (m2 - 1) * 31 + parseInt(d2[2]);
    else
        days2 = (m2 - 1) * 30 + 6 + parseInt(d2[2]);
    /* end convert dates to days*/

    if (d1[0] > d2[0]) {
        Utils.showModalMessage('تاریخ وارد شده قبل از تاریخ جاری میباشد');
        return false;
    }

    if (d1[0] < d2[0]) {
        days2 += 365;
    }

    var h = parseInt($('NOW-TIME-ID').innerHTML.split(':')[0]);
    if (days1 > days2) {
        Utils.showModalMessage('تاریخ وارد شده قبل از تاریخ جاری میباشد');
        return false;
    }
    if ((days1 + 1) == days2 && h > 11 && false) {
        Utils.showModalMessage('پس از ساعت 11، امکان رزرو برای فردا وجود ندارد');
        return false;
    }

    var res = Utils.fastAjax('WorkFlowAjaxFunc', 'getFoodRequestCount', {docID: FormView.docID});
    if (res != 'true' && res != true) {
        Utils.showModalMessage(res);
        return false;
    }

    return true;
}
