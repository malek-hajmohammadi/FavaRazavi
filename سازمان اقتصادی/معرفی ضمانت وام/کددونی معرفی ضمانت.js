this.jcode = function(self){
    self.setMatnMorefiNameh=function(){
       let bankName=FormView.myForm.getItemByName('Field_2').getData();
       let shobeh=FormView.myForm.getItemByName('Field_3').getData();
       let moteghazi=FormView.myForm.getItemByName('Field_1').getData();
       let zamenFor=FormView.myForm.getItemByName('Field_6').getData();

       let price=FormView.myForm.getItemByName('Field_4').getData();
       let priceWithCOmma= price = Number(price)
           .toFixed(0)
           .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
       let header1="<span style=\"line-height: 40px;font-weight: bold !important; font-family: &quot;B Titr&quot; !important;\">" +
           "ریاست محترم "+bankName+" "+"شعبه "+shobeh+
           "</span>";
       let header2="<span style='font-size: 14pt !important;'> موضوع : معرفی جهت ضمانت وام</span>";
       let salam="<span style='font-size: 14pt !important;'>سلام علیکم</span>";

       let body="<span style='text-align: justify !important;font-size: 14pt !important;'>" +"احتراما؛ گواهی میشود "+moteghazi+" "+
           "در حال حاضر جزو پرسنل این سازمان میباشد. این گواهی بنا به درخواست ایشان جهت ضمانت وام "+zamenFor+" به مبلغ "+priceWithCOmma+" ریال "+
           "صادر گردیده است. "+
           "لذا این سازمان متعهد می گردد در صورت تأخیر در پرداخت اقساط وام گیرنده به محض اولین اخطار کتبی آن بانک، اقساط معوق را ( از بابت اصل و کارمزد وام ) از حقوق و مزایای ضامن وی کسر و تأدیه نماید. مشروط بر اینکه در زمان اعلام کتبی آن بانک، نامبرده جزو پرسنل این سازمان بوده و مانده حقوق ایشان پاسخگوی مطالبات درخواستی باشد."+
           "<br/>"+
           "این گواهی فاقد ارزش قانونی دیگری است."+
           "<br/><br/><br/>"+
           "</span>";
        let allText=header1+"<br/>"+header2+"<br/>"+salam+"<br/>"+body;
        FormView.myForm.getItemByName('Field_7').setData(allText);




    };
    self.showOrNotShow=function(){
        userState = FormView.myForm.getItemByName('Field_8').getData();

        if(userState=="moteghazi"){
            $jq(".mali").css("display","none");
        }
        if(userState=="manabeEnsani"){
            $jq(".mali").css("display","none");
        }

        if(userState=="mali"){
            $jq(".moteghazi").css("display","none");

        }
        if(userState=="dabirkhaneh"){
            $jq(".moteghazi").css("display","none");
            $jq(".nonePrint").css("display","none");

        }
        if(userState=="baygani"){
            $jq(".moteghazi").css("display","none");
            $jq(".nonePrint").css("display","none");

        }

        $jq('.tdMoteghazi >input').attr("readonly","true");
        $jq('.tdMoteghazi >input').css("background","gainsboro");



    };
    self.btnConfirmMoteghazi=function(){
        let bankLength=FormView.myForm.getItemByName('Field_2').getData().length;
        let shobehLength=FormView.myForm.getItemByName('Field_3').getData().length;
        let mablaghTashilatLength=FormView.myForm.getItemByName('Field_4').getData().length;
        let vamGirandehLength=FormView.myForm.getItemByName('Field_6').getData().length;

        if(bankLength<5){
            Utils.showModalMessage('نام بانک معتبر نمی باشد');
            return false;
        }
        if(shobehLength<5){
            Utils.showModalMessage('نام شعبه معتبر نمی باشد');
            return false ;
        }
        if(mablaghTashilatLength<5){
            Utils.showModalMessage('مبلغ تسهیلات معتبر نمی باشد');
            return false ;
        }
        if(mablaghTashilatLength<5){
            Utils.showModalMessage('مبلغ تسهیلات معتبر نمی باشد');
            return false ;
        }
        if(vamGirandehLength<5){
            Utils.showModalMessage('نام وام گیرنده معتبر نمی باشد');
            return false ;
        }
        return  true;

    };
    self.loadForm=function(){
        self.showOrNotShow();
        self.setMatnMorefiNameh();

    };

};
