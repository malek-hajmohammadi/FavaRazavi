listener = function (event) {

    var reqType=0; /*نوع سهمیه*/
    var roleId=0; /* کد سمت*/
    var reqDate=0; /*تاریخ درخواست*/
    var reptId=0; /* کد حوزه*/

    reqType= FormView.myForm.getItemByName('Field_5').getData();



    var res = Utils.fastAjax('WorkFlowAjaxFunc', 'countQuota', {
        'reqType': reqType,
        'roleId': roleId,
        'reqDate': reqDate,
        'reptId': reptId
    });



    FormView.myForm.getItemByName('Field_7').setData(res);
};

