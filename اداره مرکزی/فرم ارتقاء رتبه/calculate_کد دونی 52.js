this.jcode = function(self){

    self.checkNeededFieldsMoteghazi=function(){
        
        if (FormView.myForm.getItemByName('Field_31').getData().length<3) {
            Utils.showModalMessage('لطفا آیتم توضیح کلی در خصوص ماموریت حوزه مربوطه را پر کنید');
            return false;
        }
        if (FormView.myForm.getItemByName('Field_32').getData().length<3) {
            Utils.showModalMessage('لطفا آیتم نقاط قوت انجام وظایف را پر کنید');
            return false;
        }
        if (FormView.myForm.getItemByName('Field_33').getData().length<3) {
            Utils.showModalMessage('لطفا آیتم نقاط ضعف انجام وظایف را پر کنید');
            return false;
        }
        if (FormView.myForm.getItemByName('Field_34').getData().length<3) {
            Utils.showModalMessage('لطفا آیتم اقدامات خاص که در طول خدمت صورت گرفته را پر کنید');
            return false;
        }
        if (FormView.myForm.getItemByName('Field_35').getData().length<3) {
            Utils.showModalMessage('لطفا آیتم نکات ویژه و کلیدی حاصل از تجربه را پر کنید');
            return false;
        }
        if (FormView.myForm.getItemByName('Field_36').getData().length<3) {
            Utils.showModalMessage('لطفا آیتم ارائه برنامه یا پیشنهاد اصلاحی را پر کنید');
            return false;
        }
        
        return true;

    };

    self.updateMobileNumber=function(userId){

        let mobile = Utils.fastAjax('WorkFlowAjaxFunc', 'f268getMobile',{userId:userId});
        FormView.myForm.getItemByName('Field_53').setData(mobile);

    };

    self.loadForm=function(){
        $jq('.userField input').focusout( function (e) {

            let f=FormView.myForm.getItemByName('Field_24').getData();
            let userId=f[0].uid;
            FormView.myForm.getItemByName('Field_52').updateMobileNumber(userId)

        });

    };

};