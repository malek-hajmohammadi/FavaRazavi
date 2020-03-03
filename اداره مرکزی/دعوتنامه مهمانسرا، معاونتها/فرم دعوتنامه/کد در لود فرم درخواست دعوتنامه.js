listener = function (event) {
    var state = 0;
    var waitInterval = setInterval(function () {
        if (FormView && FormView.myForm) {
           var stage=FormView.myForm.getItemByName('Field_16').getData();
            html = FormView.myForm.getItemByName('Field_21').showTable(stage);
            $jq('.tableGuest').html(html);
            FormView.myForm.getItemByName('Field_21').setDateObjectAll();


            /*برای نمایش مانده سهمیه*/
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

            /*اتمام نمایش مانده از سهمیه*/


        }
        clearInterval(waitInterval);
    }, 300);
};