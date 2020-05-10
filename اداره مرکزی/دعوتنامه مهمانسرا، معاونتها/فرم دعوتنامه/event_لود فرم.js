listener = function (event) {
    var state = 0;
    var waitInterval = setInterval(function () {
        if (FormView && FormView.myForm) {
           var stage=FormView.myForm.getItemByName('Field_16').getData();

           if(stage!="1")/*edit mode*/
           {
               $jq(".excelDiv").css("display","none")
           }


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
            if(stage=="4"){
                var lengthTable =  $jq('.guestTable>tbody>tr[class^=\'tab\']').length;
                FormView.myForm.getItemByName('Field_11').setData(lengthTable); /*تعداد وعده تایید شده ، مقدار پیش فرض*/

                var requiredDate=FormView.myForm.getItemByName('Field_4').getData();
                FormView.myForm.getItemByName('Field_10').setData(requiredDate); /*فیلد تاریخ تایید شده بصورت پیش فرض*/

                var vadeh=FormView.myForm.getItemByName('Field_3').getData();
                FormView.myForm.getItemByName('Field_12').setData(vadeh);

            }


        }
        clearInterval(waitInterval);
    }, 300);
};