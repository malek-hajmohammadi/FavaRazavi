this.jcode = function (self) {
    try {
        self.LoadJS = function () {
            var Creator = FormView.myForm.getItemByName('Field_0').getData();
            var UID = Creator[0]["uid"];
            var RID = Creator[0]["rid"];
            var res = Utils.fastAjax('WorkFlowAjaxFunc', 'KA_CreatorInfo', {UID: parseInt(UID), RID: parseInt(RID),});
            res = JSON.parse(res);
            $jq('#KA-Name').text(res.Name);

            $jq('#KA-Emp').text(res.Emp);
            $jq('#KA-Username').text(res.Username);
            $jq('#KA-Tel').text(res.Tel);
            $jq('#KA-InTel').text(res.InTel);
            $jq('#KA-Mobile').text(res.Mobile);
            $jq('#KA-CodeM').text(res.CodeM);
            $jq('#KA-Unit').text(res.Unit);

           let role;
            if(res.representation=="")/*We don't have assistance in this situation*/
                role=res.Role;
            else
                role=res.Role +"(از طرف)";


            $jq('#KA-Role').html(role);


        };
        self.RunJS = function (fw) {
            var msg = '';
            var Tedad = FormView.myForm.getItemByName('Field_2').getData().length;
            var Shahrh = FormView.myForm.getItemByName('Field_3').getData();
            if (Tedad > 1) msg = 'مجاز هستيد تنها يك درخواست را انتخاب كنيد';
            if (Shahrh == '') msg = 'شرح درخواست را بنويسيد';
            if (msg == '') {
                return true;
            } else {
                Utils.showMessage(msg);
                return false;
            }
        };
    } catch (cc) {
        console.log(cc);
    }
};