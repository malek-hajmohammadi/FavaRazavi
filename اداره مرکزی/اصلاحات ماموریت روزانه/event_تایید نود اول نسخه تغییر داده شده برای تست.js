this.actJS = function(self){

    var uid=Main["FirstPageParameters"]["userInfo"]['id'];
    var emp=Utils.fastAjax('WorkFlowAjaxFunc', 'chkEMPMorakhasi',{uid:uid});

    var sdate = null;
    var edate = null;
    try {
        sdate = FormView.myForm.getItemByName('Field_3').getData();
        edate = FormView.myForm.getItemByName('Field_4').getData();
    } catch (e) {
    }
    if (!sdate || !edate) {
        return;
    }
    var res = Utils.fastAjax('NForms', 'dataDiff', {sdate: sdate, edate: edate}, true);
    if ((parseInt(res) + 1) <= 0) {
        FormView.myForm.getItemByName('Field_5').setData("");
        Utils.showModalMessage('تاریخ های وارد شده صحیح نمیباشد');
    } else {
        FormView.myForm.getItemByName('Field_5').setData(parseInt(res) + 1);
    }

    return true;}