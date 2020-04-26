this.jcode = function(self) {
    self.btnConfirmMoteghazi = function () {

        var companyName = FormView.myForm.getItemByName('Field_33').getData().length;
        if (companyName < 1) {
            Utils.showModalMessage('شما قادر به  ثبت درخواست ضمانتنامه بانکی نیستید');
            return false;
        }
        var mablaghTashilat = FormView.myForm.getItemByName('Field_2').getData();
        if (mablaghTashilat < 2) {
            Utils.showModalMessage('لطفا مبلغ تسهیلات را وارد کنید');
            return false;
        }

        var shobeh = FormView.myForm.getItemByName('Field_26').getData();
        if (shobeh < 3) {
            Utils.showModalMessage('لطفا نام شعبه را وارد کنید');
            return false;
        }

        var bank = FormView.myForm.getItemByName('Field_24').getData();
        if (bank == "0") {
            Utils.showModalMessage('لطفا نام بانک یا موسسه  را انتخاب کنید');
            return false;
        }
        var noTashilat = FormView.myForm.getItemByName('Field_4').getData();
        if (noTashilat == "0") {
            Utils.showModalMessage('لطفا نوع تسهیلات را انتخاب کنید');
            return false;
        }

        var bazPardakht = FormView.myForm.getItemByName('Field_3').getData();
        if (bazPardakht < 3) {
            Utils.showModalMessage('لطفا طول مدت بازپرداخت را وارد کنید');
            return false;
        }

        var numMosavabehHeiatModireh = FormView.myForm.getItemByName('Field_27').getData().length;
        if (numMosavabehHeiatModireh < 3) {
            Utils.showModalMessage('لطفا شماره مصوبه هیئت مدیره را وارد کنید');
            return false;
        }

        var dateMosavabehHeiatModireh = FormView.myForm.getItemByName('Field_28').getData().length;
        if (dateMosavabehHeiatModireh < 8) {
            Utils.showModalMessage('لطفا تاریخ مصوبه هیئت مدیره را به درستی وارد کنید');
            return false;
        }

        var arzeshTaraznameh = FormView.myForm.getItemByName('Field_10').getData();
        if (arzeshTaraznameh < 3) {
            Utils.showModalMessage('لطفا ارزش دارایی شرکت (ترازنامه) را وارد کنید');
            return false;
        }

        var mandehTashilat = FormView.myForm.getItemByName('Field_11').getData();
        if (parseInt(mandehTashilat) <0) {
            Utils.showModalMessage('لطفا مانده تسهیلات را وارد کنید');
            return false;
        }

        var mablaghZemanatnameh = FormView.myForm.getItemByName('Field_15').getData();
        if (mablaghZemanatnameh < 3) {
            Utils.showModalMessage('لطفا مبلغ ضمانتنامه را وارد کنید');
            return false;
        }

        var darsadSaham = FormView.myForm.getItemByName('Field_25').getData();
        if (darsadSaham > 100 || darsadSaham<1 ) {
            Utils.showModalMessage('درصد سهام آستان قدس باید عددی بین 1 تا 100 باشد');
            return false;
        }


        var saghfZemanatnameh=FormView.myForm.getItemByName('Field_12').getData();
        if(parseInt(mablaghZemanatnameh)>parseInt(saghfZemanatnameh)){
            Utils.showModalMessage('مبلغ ضمانتنامه از سقف مبلغ ضمانتنامه بیشتر است');
            return false;
        }

        var attachFile = FormView.myForm.getItemByName('Field_7').getData().length;
        if (attachFile < 3) {
            Utils.showModalMessage('لطفا اسناد مصوبه هیئت مدیره را  پیوست کنید');
            return false;
        }

        var attachFile = FormView.myForm.getItemByName('Field_9').getData().length;
        if (attachFile < 3) {
            Utils.showModalMessage('لطفا جدول تسهیلات مالی را پیوست کنید');
            return false;
        }

        var attachFile = FormView.myForm.getItemByName('Field_35').getData().length;
        if (attachFile < 3) {
            Utils.showModalMessage('لطفا  تصویر آخرین ترازنامه تایید شده را پیوست کنید');
            return false;
        }

        return true;

    };
    self.changeBackgroundFieldSet = function () {
        let defaultColor="#ffffff";
        let activeColor="#c8e6c9";
        let activeColorTitle="#98e6c9";

        $jq('.fieldSet1').css("background-color", defaultColor);
        $jq('.fieldSet2').css("background-color", defaultColor);
        $jq('.fieldSet3').css("background-color", defaultColor);
        $jq('.fieldSet4').css("background-color",defaultColor);
        $jq('.fieldSet5').css("background-color", defaultColor);

        let stage = 0;
        if (FormView.myForm.info.settings.nodeName) {
            var nodeName = FormView.myForm.info.settings.nodeName;
            var nodeName2 = nodeName;
            while (nodeName2 && nodeName2.indexOf(String.fromCharCode(1705)) >= 0) nodeName2 = nodeName2.replace(String.fromCharCode(1705), String.fromCharCode(1603));
            while (nodeName2 && nodeName2.indexOf(String.fromCharCode(1740)) >= 0) nodeName2 = nodeName2.replace(String.fromCharCode(1740), String.fromCharCode(1610));
            if (nodeName == 'شرکت-متقاضی' || nodeName2 == 'شرکت-متقاضی') stage = 1;
            if (nodeName == 'رئیس-حسابداری' || nodeName2 == 'رئیس-حسابداری') stage = 2;

            if (nodeName == 'مسئول-انتظامی' || nodeName2 == 'مسئول-انتظامی') stage = 3;
            if (nodeName == 'مسئول-صندوق' || nodeName2 == 'مسئول-صندوق') stage = 4;
            if (nodeName == 'حسابداری-نهایی' || nodeName2 == 'حسابداری-نهایی') stage = 5;


            switch (stage) {
                case  1 :
                    $jq('.fieldSet1').css("background-color", activeColor);
                    $jq('.fieldSet1 >legend').css("background-color", activeColorTitle);

                    break;
                case 2:
                    $jq('.fieldSet2').css("background-color", activeColor);
                    $jq('.fieldSet2 >legend').css("background-color", activeColorTitle);


                    break;
                case 3:
                    $jq('.fieldSet3').css("background-color",activeColor);
                    $jq('.fieldSet3 >legend').css("background-color", activeColorTitle);
                    break;
                case 4:
                    $jq('.fieldSet4').css("background-color", activeColor);
                    $jq('.fieldSet4 >legend').css("background-color", activeColorTitle);
                    break;
                case 5:
                    $jq('.fieldSet5').css("background-color", activeColor);
                    $jq('.fieldSet5 >legend').css("background-color", activeColorTitle);

                    $jq('.fieldSet6').css("background-color", activeColor);
                    $jq('.fieldSet6 >legend').css("background-color", activeColorTitle);

                    break;
            }


        }

    };
    self.btnConfirmHesabdariFirst=function(){
        var tikMosavabehHeiatModireh = FormView.myForm.getItemByName('Field_6').getData();
        if (tikMosavabehHeiatModireh == false) {
            Utils.showModalMessage('لطفا مصوبه هیئت مدیره را تایید نمایید');
            return false;
        }


        var tikJadvalTashilatMali = FormView.myForm.getItemByName('Field_8').getData();
        if (tikJadvalTashilatMali == false) {
            Utils.showModalMessage('لطفا جدول تسهیلات مالی شرکت را تایید نمایید');
            return false;
        }

        var tikTaraznameh = FormView.myForm.getItemByName('Field_36').getData();
        if (tikTaraznameh == false) {
            Utils.showModalMessage('لطفا آخرین ترازنامه شرکت مربوطه را تایید کنید');
            return false;
        }

        return true;

    };
    self.btnConfirmEntezami=function(){

        var numCheckZemanat = FormView.myForm.getItemByName('Field_16').getData().length;
        if (numCheckZemanat < 3) {
            Utils.showModalMessage('لطفا شماره چک ضمانت را وارد کنید');
            return false;
        }

        var mablaghZemanat = FormView.myForm.getItemByName('Field_37').getData().length;
        if (mablaghZemanat < 3) {
            Utils.showModalMessage('لطفا مبلغ چک ضمانت را وارد کنید');
            return false;
        }
        return true;



    };
    self.btnConfirmSandogh=function(){

        var numCheckKarmozd = FormView.myForm.getItemByName('Field_18').getData().length;
        if (numCheckKarmozd < 3) {
            Utils.showModalMessage('لطفا شماره چک کارمزد را وارد کنید');
            return false;
        }


        var dateChekKarmozd = FormView.myForm.getItemByName('Field_21').getData().length;
        if (dateChekKarmozd < 8) {
            Utils.showModalMessage('لطفا تاریخ چک کارمزد را به درستی وارد کنید');
            return false;
        }

        var numSanadCheckKarmozd = FormView.myForm.getItemByName('Field_38').getData().length;
        if (numSanadCheckKarmozd < 3) {
            Utils.showModalMessage('لطفا مبلغ چک کارمزد را وارد کنید');
            return false;
        }

        return true;




    };
    self.btnConfirmHesabdariFinal = function () {


        var ezharNazarHesabdari = FormView.myForm.getItemByName('Field_13').getData();
        if (ezharNazarHesabdari == "0") {
            Utils.showModalMessage('لطفا فیلد اظهارنظر سرپرست حسابداری را انتخاب کنید');
            return false;
        } else if (ezharNazarHesabdari == "2") {
            Utils.showModalMessage('با توجه به اظهارنظر، تایید فرم و ارسال به مدیر مالی امکان پذیر نیست');
            return false;
        }

        var ghalebMatn = FormView.myForm.getItemByName('Field_34').getData();
        if (ghalebMatn == "0") {
            Utils.showModalMessage('لطفا قالب متن مناسب انتخاب کنید');
            return false;
        }
        
        return true;


    };

    self.btnConfirmMoavenat=function(){

        /*
        var ezharNazarMoavenat = FormView.myForm.getItemByName('Field_23').getData();
        if (ezharNazarMoavenat == "0") {
            Utils.showModalMessage('لطفا فیلد اظهارنظر معاونت را انتخاب کنید');
            return false;
        }
        else if (ezharNazarMoavenat == "2") {
            Utils.showModalMessage('با توجه به اظهار نظر انتخاب شده، تایید فرم و ارسال به مدیر عامل امکان پذیر نیست');
            return false;
        }


         */
        return true;



    };
    self.changeSaghf=function(){
       let saghfZemanatnameh=(70/10000*FormView.myForm.getItemByName('Field_10').getData()*FormView.myForm.getItemByName('Field_25').getData())-FormView.myForm.getItemByName('Field_11').getData();

/*
        let st=saghfZemanatnameh.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
       FormView.myForm.getItemByName('Field_12').setData(st);
*/
        FormView.myForm.getItemByName('Field_12').setData(saghfZemanatnameh);

    };
self.setGhalebMatn=function(){
    var SelectGhalebMatn = FormView.myForm.getItemByName('Field_34').getData();
   let template1="template one";
   let template2="template two";




    
   let tashilat=FormView.myForm.getItemByName('Field_2').getData();
   tashilatInt=1320000000000;
   tashilat=Number(tashilatInt)
       .toFixed(0)
       .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
   let tashilatMilyard=Number(tashilatInt/1000000000)
       .toFixed(0)
       .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
    
    
    let company=FormView.myForm.getItemByName('Field_0').getData();
    
    template1="نظر به اینکه شرکت "+ company;
    template1+=" متعلق به آستان قدس رضوی تقاضای اخذ تسهیلات به مبلغ "+tashilat;
    template1+=" ریال را از آن بانک دارد، لذا بدینوسیله اعلام میدارد در صورت عدم پرداخت تعهدات توسط شرکت مذکور، این سازان بازپرداخت بدهی فوق الذکر (اصل ، سود و وجه التزام) را در موعد مقرر تعهد و تضمین می نماید. لازم به ذکر است که این ضمانتنامه بابت یک نوبت می باشد.";
    
    template2="در راستاي اجراي تفاهم نامه شماره 863762/98 مورخ 13/08/98 في مابين بانك صادرات ايران و سازمان اقتصادي رضوي و با عنايت به درخواست تسهيلات شركت "+company;
    template2+="به شماره ثبت ................. و شناسه ملي ....................... به مبلغ "+tashilat +" ریال ("+tashilatMilyard+" میلیارد ریال )";
    
    template2+=" بدينوسيله اين سازمان با نمايندگي مديرعامل، امضاداران مجاز سازمان، قبول و تضمين نمود كه متضامناً با آن شركت متعهد به پرداخت مطالبات بانك خواهد بود لذا در صورت عدم پرداخت هر قسط از اقساط معوقه از طرف شركت "+ company;
    template2+="به محض دريافت اولين تقاضاي كتبي از سوي بانك، بدون احتياج به صدور اظهار نامه ، مبلغ مورد درخواست را در وجه بانك بپردازد . در غير اين صورت بانك به طور غير قابل برگشت و در هر زمان ولو كرارا و راسا حق و اختيار دارد نسبت به برداشت مبلغ هر يك از اقساط ( اعم از اصل و سود و جرائم تاخير تا روز تسويه ) از محل كليه حسابهاي اين سازمان نزد خود برداشت و مراتب را به سازمان منعكس نمايد بنابراین در صورت عدم كفايت موجودي حسابها ، بانك حق و اختيار دارد به منظور وصول مطالبات خود نسبت به انجام پيگيري هاي قضائي اقدام نمايد . مدت اعتبار اين تضمين نامه از تاريخ صدور تا زمان تسويه كامل بدهي معتبر خواهد بود .";

   
   
   
   
   
   if (SelectGhalebMatn=="1"){
       FormView.myForm.getItemByName('Field_30').setData(template1);
       return;
   }
    if (SelectGhalebMatn=="2"){
        FormView.myForm.getItemByName('Field_30').setData(template2);
        return;
    }



};
    self.addChangeEvent=function(){
        $jq('.saham >input').keyup(function(){  FormView.myForm.getItemByName('Field_31').changeSaghf()});
        $jq('.taraznameh >input').keyup(function(){  FormView.myForm.getItemByName('Field_31').changeSaghf()});
        $jq('.tashilat >input').keyup(function(){  FormView.myForm.getItemByName('Field_31').changeSaghf()});
        FormView.myForm.getItemByName('Field_31').changeSaghf();
        
        $jq('.template >select').change(function(){ FormView.myForm.getItemByName('Field_31').setGhalebMatn()});

    };



};