this.jcode = function(self){
    self.setMatnMorefiNameh=function(){
       let bankName=FormView.myForm.getItemByName('Field_2').getData();
       let shobeh=FormView.myForm.getItemByName('Field_3').getData();
       let moteghazi=FormView.myForm.getItemByName('Field_1').getData();
       let price=FormView.myForm.getItemByName('Field_4').getData();
       let priceWithCOmma= price = Number(price)
           .toFixed(0)
           .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
       let header1="<span style=\"line-height: 40px;font-weight: bold !important; font-family: &quot;B Titr&quot; !important;\">" +
           "ریاست محترم "+bankName+" "+"شعبه "+shobeh+
           "</span>";
       let header2="<span style='font-size: 14pt !important;'> موضوع : معرفی جهت دریافت وام</span>";
       let salam="<span style='font-size: 14pt !important;'>سلام علیکم</span>";

       let body="<span style='text-align: justify !important;font-size: 14pt !important;'>" +"احتراما بدینوسیله ؛ "+moteghazi+" "+
           "از پرسنل این سازمان جهت استفاده از تسهیلات به مبلغ "+priceWithCOmma+" ریال "+
           "معرفی میگردد. "+
           "این سازمان تعهدی در خصوص تضمین تسهیلات اعطایی نخواهد داشت، اما چنانچه نامبرده اقساط وام دریافتی را به آن بانک پرداخت ننماید بمحض وصول اخطار کتبی از طرف آن بانک، اقساط معوق وام را طبق مقررات از حقوق و مزایای وام گیرنده کسر و پرداخت می نماید، مشروط بر اینکه در زمان اعلام کتبی آن بانک، نامبرده جزو پرسنل این سازمان بوده و مانده حقوق و مزایای ایشان پاسخگوی مطالبات درخواستی باشد."+
           "<br/>"+
           "شایسته است دستور فرمائید همکاری لازم با نامبرده صورت پذیرد."+
           "<br/><br/><br/>"+
           "</span>";
        let allText=header1+"<br/>"+header2+"<br/>"+salam+"<br/>"+body;
        FormView.myForm.getItemByName('Field_6').setData(allText);

        /*رونوشت ها*/
        let ronevesht="رونوشت:"+"<br/>"+
            "- جناب آقای رضائی جهت اطلاع و اقدام لازم."+"<br/>"+
            "- جناب آقای برادران رحیمی سرپرست محترم واحد طرح و برنامه و منابع انسانی جهت اطلاع و اقدام لازم."+"<br/>"+
            "-  "+moteghazi+" عطف به درخواست جهت اطلاع.";
        FormView.myForm.getItemByName('Field_8').setData(ronevesht);


    };
    self.showOrNotShow=function(){
        userState = FormView.myForm.getItemByName('Field_7').getData();

        if(userState=="moteghazi"){
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
    self.loadForm=function(){
        self.showOrNotShow();
        self.setMatnMorefiNameh();

    };

};
