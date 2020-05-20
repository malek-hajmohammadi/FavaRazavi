this.jcode = function (self) {
    let template;
    let company;
    let bank;
    let shobeh;
    self.btnConfirmMoteghazi = function () {

        var companyName = FormView.myForm.getItemByName('Field_0').getData().length;
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

        var bank =  FormView.myForm.getItemByName('Field_40').getData();
        if (bank.length < 1) {
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
        if (parseInt(mandehTashilat) < 0) {
            Utils.showModalMessage('لطفا مانده تسهیلات را وارد کنید');
            return false;
        }

        var mablaghZemanatnameh = FormView.myForm.getItemByName('Field_15').getData();
        if (mablaghZemanatnameh < 3) {
            Utils.showModalMessage('لطفا مبلغ ضمانتنامه را وارد کنید');
            return false;
        }

        var darsadSaham = FormView.myForm.getItemByName('Field_25').getData();
        if (darsadSaham > 100 || darsadSaham < 1) {
            Utils.showModalMessage('درصد سهام آستان قدس باید عددی بین 1 تا 100 باشد');
            return false;
        }

/*
        var saghfZemanatnameh = FormView.myForm.getItemByName('Field_12').getData();
        if (parseInt(mablaghZemanatnameh) > parseInt(saghfZemanatnameh)) {
            Utils.showModalMessage('مبلغ ضمانتنامه از سقف مبلغ ضمانتنامه بیشتر است');
            return false;
        }
*/
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
        let defaultColor = "#ffffff";
        let activeColor = "#c8e6c9";
        let activeColorTitle = "#98e6c9";

        $jq('.fieldSet1').css("background-color", defaultColor);
        $jq('.fieldSet2').css("background-color", defaultColor);
        $jq('.fieldSet3').css("background-color", defaultColor);
        $jq('.fieldSet4').css("background-color", defaultColor);
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
            if (nodeName == 'دبیرخانه' || nodeName2 == 'دبیرخانه') stage =7;


/*
            if(stage==7)
                $jq('.btnTd').css("display","");
            else
                $jq('.btnTd').css("display","none");
*/


            switch (stage) {
                case  1 :
                    $jq('.fieldSet1').css("background-color", activeColor);
                    $jq('.fieldSet1 >legend').css("background-color", activeColorTitle);

                    break;
                case 2:
                    $jq('.fieldSet2').css("background-color", activeColor);
                    $jq('.fieldSet2 >legend').css("background-color", activeColorTitle);

                    $jq('.fieldSet6').css("background-color", activeColor);
                    $jq('.fieldSet6 >legend').css("background-color", activeColorTitle);

                    break;
                case 3:
                    $jq('.fieldSet3').css("background-color", activeColor);
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
    self.printPievastha=function(){
        let did=FormView.did;
        Utils.windowOpen('../Runtime/process.php?module=DocAttachs&action=createImageAtachesPDF&refer=' + did);

    };
    self.btnConfirmHesabdariFirst = function () {
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

        var tikTaraznameh = FormView.myForm.getItemByName('Field_39').getData();
        if (tikTaraznameh == false) {
            Utils.showModalMessage('لطفا رعايت سقف ضمانتنامه را تایید کنید');
            return false;
        }


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
    self.btnConfirmEntezami = function () {

       
        return true;


    };
    self.btnConfirmSandogh = function () {

        return true;


    };
    self.btnConfirmHesabdariFinal = function () {


      

        return true;


    };

    self.btnConfirmMoavenat = function () {

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
    self.changeSaghf = function () {
        let saghfZemanatnameh = (70 / 10000 * FormView.myForm.getItemByName('Field_10').getData() * FormView.myForm.getItemByName('Field_25').getData()) - FormView.myForm.getItemByName('Field_11').getData();

        /*
                let st=saghfZemanatnameh.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
               FormView.myForm.getItemByName('Field_12').setData(st);
        */
        FormView.myForm.getItemByName('Field_12').setData(saghfZemanatnameh);

    };
    self.setGhalebMatn = function () {
        var SelectGhalebMatn = FormView.myForm.getItemByName('Field_34').getData();
        let template1 = "template one";
        let template2 = "template two";


        let tashilatForm = FormView.myForm.getItemByName('Field_2').getData();
       
        tashilat = Number(tashilatForm)
            .toFixed(0)
            .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
        let tashilatMilyard = Number(tashilatForm / 1000000000)
            .toFixed(3)
            .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");


        self.company = FormView.myForm.getItemByName('Field_0').getData();

        self.bank=FormView.myForm.getItemByName('Field_40').currentData[1];
        self.shobeh=FormView.myForm.getItemByName('Field_26').getData();

        let shomarehSabt=FormView.myForm.getItemByName('Field_41').getData();
        let shenaseMeli=FormView.myForm.getItemByName('Field_43').getData();
       
        

        template1 = "نظر به اینکه  " + self.company;
        template1 += " متعلق به آستان قدس رضوی تقاضای اخذ تسهیلات به مبلغ " + tashilat;
        template1 += " ریال را از آن بانک دارد، لذا بدینوسیله اعلام میدارد در صورت عدم پرداخت تعهدات توسط شرکت مذکور، این سازان بازپرداخت بدهی فوق الذکر (اصل ، سود و وجه التزام) را در موعد مقرر تعهد و تضمین می نماید. لازم به ذکر است که این ضمانتنامه بابت یک نوبت می باشد.";

        template2 = "در راستاي اجراي تفاهم نامه شماره 863762/98 مورخ 13/08/98 في مابين بانك صادرات ايران و سازمان اقتصادي رضوي و با عنايت به درخواست تسهيلات  " + self.company;
        template2 += " به شماره ثبت "+shomarehSabt+" و شناسه ملي "+shenaseMeli+" به مبلغ " + tashilat;

        template2 += " بدينوسيله اين سازمان با نمايندگي مديرعامل، امضاداران مجاز سازمان، قبول و تضمين نمود كه متضامناً با آن شركت متعهد به پرداخت مطالبات بانك خواهد بود لذا در صورت عدم پرداخت هر قسط از اقساط معوقه از طرف شركت " + self.company;
        template2 += "به محض دريافت اولين تقاضاي كتبي از سوي بانك، بدون احتياج به صدور اظهار نامه ، مبلغ مورد درخواست را در وجه بانك بپردازد . در غير اين صورت بانك به طور غير قابل برگشت و در هر زمان ولو كرارا و راسا حق و اختيار دارد نسبت به برداشت مبلغ هر يك از اقساط ( اعم از اصل و سود و جرائم تاخير تا روز تسويه ) از محل كليه حسابهاي اين سازمان نزد خود برداشت و مراتب را به سازمان منعكس نمايد بنابراین در صورت عدم كفايت موجودي حسابها ، بانك حق و اختيار دارد به منظور وصول مطالبات خود نسبت به انجام پيگيري هاي قضائي اقدام نمايد . مدت اعتبار اين تضمين نامه از تاريخ صدور تا زمان تسويه كامل بدهي معتبر خواهد بود .";


        if (SelectGhalebMatn == "0") {
            FormView.myForm.getItemByName('Field_30').setData("");
            self.template="";
            return;
        }
        if (SelectGhalebMatn == "1") {
            FormView.myForm.getItemByName('Field_30').setData(template1);
            self.template=template1;
            return;
        }
        if (SelectGhalebMatn == "2") {
            FormView.myForm.getItemByName('Field_30').setData(template2);
            self.template=template2;
            return;
        }
        


    };
    self.addChangeEvent = function () {
        $jq('.saham >input').keyup(function () {
            FormView.myForm.getItemByName('Field_31').changeSaghf()
        });
        $jq('.taraznameh >input').keyup(function () {
            FormView.myForm.getItemByName('Field_31').changeSaghf()
        });
        $jq('.tashilat >input').keyup(function () {
            FormView.myForm.getItemByName('Field_31').changeSaghf()
        });
       /* FormView.myForm.getItemByName('Field_31').changeSaghf();*/

        $jq('.template >select').change(function () {
            FormView.myForm.getItemByName('Field_31').setGhalebMatn()
        });
        $jq('.saghf >input').attr("readonly","true");
        $jq('.saghf >input').css("background","gainsboro");
        $jq('.company >input').attr("readonly","true");
        $jq('.company >input').css("background","gainsboro");
        $jq('.companyT ').css("display","none");


        /*برای اینکه دکمه ها فقط در مرحله دبیرخانه دیده بشوند*/
        if(self.shobeh=FormView.myForm.getItemByName('Field_32').getData()<5)
           $jq('.btntd ').css("display","none");






    };
    self.printZemanatnamehWithoutRonevesht=function(){
        self.company = FormView.myForm.getItemByName('Field_0').getData();
        self.bank=FormView.myForm.getItemByName('Field_40').currentData[1];
        self.shobeh=FormView.myForm.getItemByName('Field_26').getData();
        self.template=FormView.myForm.getItemByName('Field_30').getData();

        /* self.setGhalebMatn();*/
        let htmlZemanatnameh="<!DOCTYPE html>\n" +
            "<html lang=\"fa\">\n" +
            "<head>\n" +
            "    <meta charset=\"UTF-8\">\n" +
            "    <title>ضمانتنامه ارائه به بانک</title>\n" +
            "    <style>\n" +
            "        .print-body{\n" +
            "            margin: 0;\n" +
            "            padding: 0;\n" +
            "        }\n" +
            "        .print-div-1{\n" +
            "            margin: 0 auto;\n" +
            "            position: relative;\n" +
            "            width: 210mm;\n" +
            "            height: 297mm;\n" +
            "            padding: 20mm 20mm 0 0;\n" +
            "            direction: rtl;\n" +
            "            background-image: url(\"../../../formimages/BG-A5.jpg\");\n" +
            "            background-position: left top;\n" +
            "            background-repeat: no-repeat;\n" +
            "            background-size: cover;\n" +
            "        }\n" +
            "        .print-div-2{\n" +
            "            padding: 0;\n" +
            "            width: 175mm;\n" +
            "            height: 100%;\n" +
            "        }\n" +
            "        .print-god{\n" +
            "            font-family: \"B Nazanin\";\n" +
            "            font-size: 11pt;\n" +
            "            margin-bottom: 20px;\n" +
            "        }\n" +
            "        .print-title{\n" +
            "            font-family: \"B Titr\";\n" +
            "            font-size: 16pt;\n" +
            "            margin-bottom: 20px;\n" +
            "        }\n" +
            "        .print-text{\n" +
            "            font-family: \"B Nazanin\";\n" +
            "            font-size: 16pt;\n" +
            "            text-align: justify;\n" +
            "            direction: rtl;\n" +
            "        }\n" +
            "        .print-text-salavat{\n" +
            "            font-family: \"B Nazanin\";\n" +
            "            font-size: 14pt;\n" +
            "\n" +
            "            direction: rtl;\n" +
            "        }\n" +
            "        .print-sign-img{\n" +
            "            width: 150px;\n" +
            "            height: 150px;\n" +
            "            text-align: center;\n" +
            "            font-family: \"B Nazanin\";\n" +
            "            font-size: 10pt;\n" +
            "            /*padding: 0 !important;*/\n" +
            "            position: relative;\n" +
            "            margin-left: 0;\n" +
            "            margin-right: auto;\n" +
            "        }\n" +
            "        .print-sign-img div{\n" +
            "            z-index: 10 !important;\n" +
            "            /*margin-right: 20px;*/\n" +
            "        }\n" +
            "        .print-sign-text{\n" +
            "            width: 250px;\n" +
            "            height: 50px;\n" +
            "            text-align: center;\n" +
            "            font-family: \"B Titr\";\n" +
            "            font-size: 16pt;\n" +
            "            margin-top: -90px;\n" +
            "            margin-right: auto;\n" +
            "            margin-left: 0;\n" +
            "            z-index: 20 !important;\n" +
            "        }\n" +
            "        .print-dn{\n" +
            "            font-family: \"B Nazanin\";\n" +
            "            font-size: 11pt;\n" +
            "            text-align: right;\n" +
            "            position: absolute;\n" +
            "            top: 46mm;\n" +
            "            left: 8mm;\n" +
            "            direction: rtl;\n" +
            "        }\n" +
            "    </style>\n" +
            "</head>\n" +
            "<body class=\"print-body\">\n" +
            "    <div class=\"print-div-1\">\n" +
            "        <div align=\"center\" class=\"print-dn\"> <span id=\"NNumber\"></span> <br> <span id=\"NDate\"></span> </div>\n" +
            "        <div class=\"print-div-2\">\n" +
            "            <div align=\"center\" class=\"print-god\">بسمه تعالی</div>\n" +
            "            <div align=\"right\" class=\"print-title\">"+"ریاست محترم "+self.bank+" شعبه "+self.shobeh+
            "<br/>"+
            "            <br/><div align=\"center\" class=\"print-text-salavat\"> با صلوات بر محمد و آل محمد(ص)</div>\n" +
            "            <div align=\"right\" class=\"print-text\">سلام علیکم</div>\n" +
            "            <div align=\"right\" class=\"print-text\">";
         htmlZemanatnameh+=self.template;
        htmlZemanatnameh+="</div>\n" +
            "            <div align=\"left\" class=\"print-sign-img\" id=\"LetterSign2\"></div>\n" +
            "            <div align=\"left\" class=\"print-sign-text\">\n" +
            "                سید رضا فاطمی امین <br/>\n" +
            "                مدیرعامل\n" +
            "            </div>\n" +
            "        </div>\n" +
            "    </div>\n" +
            "</body>\n" +
            "</html>";

        var printWin = window.open('','A','left=0,top=0,height='+screen.height+',width='+screen.width+',resizable=0,toolbar=0,scrollbars=0,status=0,fullscreen=1');
        printWin.document.write(htmlZemanatnameh);
        printWin.document.close();
        printWin.focus();
        printWin.print();
        printWin.close();

    };

    self.printZemanatnamehWithRonevesht=function(){
        self.company = FormView.myForm.getItemByName('Field_0').getData();
        self.bank=FormView.myForm.getItemByName('Field_40').currentData[1];
        self.shobeh=FormView.myForm.getItemByName('Field_26').getData();
        self.template=FormView.myForm.getItemByName('Field_30').getData();

        let htmlZemanatnameh="<!DOCTYPE html>\n" +
            "<html lang=\"fa\">\n" +
            "<head>\n" +
            "    <meta charset=\"UTF-8\">\n" +
            "    <title>نامه ارائه به بانک</title>\n" +
            "    <style>\n" +
            "        .print-body{\n" +
            "            margin: 0;\n" +
            "            padding: 0;\n" +
            "        }\n" +
            "        .print-div-1{\n" +
            "            margin: 0 auto;\n" +
            "            position: relative;\n" +
            "            width: 210mm;\n" +
            "            height: 297mm;\n" +
            "            padding: 20mm 20mm 0 0;\n" +
            "            direction: rtl;\n" +
            "            background-image: url(\"../../../formimages/BG-A5.jpg\");\n" +
            "            background-position: left top;\n" +
            "            background-repeat: no-repeat;\n" +
            "            background-size: cover;\n" +
            "        }\n" +
            "        .print-div-2{\n" +
            "            padding: 0;\n" +
            "            width: 175mm;\n" +
            "            height: 100%;\n" +
            "        }\n" +
            "        .print-god{\n" +
            "            font-family: \"B Nazanin\";\n" +
            "            font-size: 11pt;\n" +
            "            margin-bottom: 20px;\n" +
            "        }\n" +
            "        .print-title{\n" +
            "            font-family: \"B Titr\";\n" +
            "            font-size: 16pt;\n" +
            "            margin-bottom: 20px;\n" +
            "        }\n" +
            "        .print-ronevesht{\n" +
            "            font-family: \"B Nazanin\";\n" +
            " font-weight: bold;\n"+
            " margin-top:100px;\n"+
            "            font-size: 12pt;\n" +
            "            bottom: 150px;\n" +
            "        }\n" +
            "        .print-ronevesht-details{\n" +
            "            font-family: \"B Nazanin\";\n" +
            "            margin-right: 30px;\n" +
            "            font-size: 14pt;\n" +
            "        }\n" +
            "\n" +
            "        .print-text{\n" +
            "            font-family: \"B Nazanin\";\n" +
            "            font-size: 16pt;\n" +
            "            text-align: justify;\n" +
            "            direction: rtl;\n" +
            "        }\n" +
            "        .print-text-salavat{\n" +
            "            font-family: \"B Nazanin\";\n" +
            "            font-size: 14pt;\n" +
            "\n" +
            "            direction: rtl;\n" +
            "        }\n" +
            "        .print-sign-img{\n" +
            "            width: 150px;\n" +
            "            height: 150px;\n" +
            "            text-align: center;\n" +
            "            font-family: \"B Nazanin\";\n" +
            "            font-size: 10pt;\n" +
            "            /*padding: 0 !important;*/\n" +
            "            position: relative;\n" +
            "            margin-left: 0;\n" +
            "            margin-right: auto;\n" +
            "        }\n" +
            "        .print-sign-img div{\n" +
            "            z-index: 10 !important;\n" +
            "            /*margin-right: 20px;*/\n" +
            "        }\n" +
            "        .print-sign-text{\n" +
            "            width: 250px;\n" +
            "            height: 50px;\n" +
            "            text-align: center;\n" +
            "            font-family: \"B Titr\";\n" +
            "            font-size: 16pt;\n" +
            "            margin-top: -90px;\n" +
            "            margin-right: auto;\n" +
            "            margin-left: 0;\n" +
            "            z-index: 20 !important;\n" +
            "        }\n" +
            "        .print-dn{\n" +
            "            font-family: \"B Nazanin\";\n" +
            "            font-size: 11pt;\n" +
            "            text-align: right;\n" +
            "            position: absolute;\n" +
            "            top: 46mm;\n" +
            "            left: 8mm;\n" +
            "            direction: rtl;\n" +
            "        }\n" +
            "    </style>\n" +
            "</head>\n" +
            "<body class=\"print-body\">\n" +
            "    <div class=\"print-div-1\">\n" +
            "        <div align=\"center\" class=\"print-dn\"> <span id=\"NNumber\"></span> <br> <span id=\"NDate\"></span> </div>\n" +
            "        <div class=\"print-div-2\">\n" +
            "            <div align=\"center\" class=\"print-god\">بسمه تعالی</div>\n" +
            "            <div align=\"right\" class=\"print-title\">"+"ریاست محترم "+self.bank+" شعبه "+self.shobeh+
            "<br/>"+
            "            <br/><div align=\"center\" class=\"print-text-salavat\"> با صلوات بر محمد و آل محمد(ص)</div>\n" +
            "            <div align=\"right\" class=\"print-text\">سلام علیکم</div>\n" +
            "            <div align=\"right\" class=\"print-text\">";
        htmlZemanatnameh+=self.template;
        htmlZemanatnameh+="</div>\n" +
            "            <div align=\"left\" class=\"print-sign-img\" id=\"LetterSign2\"></div>\n" +
            "            <div align=\"left\" class=\"print-sign-text\">\n" +
            "                سید رضا فاطمی امین <br/>\n" +
            "                مدیرعامل\n" +
            "            </div>\n" +

            "        <div align=\"right\" class=\"print-ronevesht\">رونوشت:</div>\n" +
            "        <div aligh=\"right\" class=\"print-ronevesht-details\">\n" +
            "            معاونت محترم امور شرکتها و مجامع جهت اطلاع<br/>\n" +
            "            مدیرعامل محترم شرکت " ;
            htmlZemanatnameh+=self.company;
            htmlZemanatnameh+="<br/>\n";
            htmlZemanatnameh+="            امور مالی جهت اطلاع و ثبت در دفاتر<br/>\n" +
            "        </div>\n" +
                "        </div>\n" +
            "    </div>\n" +
            "</body>\n" +
            "</html>";

        var printWin = window.open('','A','left=0,top=0,height='+screen.height+',width='+screen.width+',resizable=0,toolbar=0,scrollbars=0,status=0,fullscreen=1');
        printWin.document.write(htmlZemanatnameh);
        printWin.document.close();
        printWin.focus();
        printWin.print();
        printWin.close();

    };
    self.downloadAtachment=function(){
        let docId=FormView.did;
        let url="https://ravan.reo.ir/RAVAN/Runtime/process.php?module=WorkFlowAjaxFunc&action=getAttachments&docID="+docId;
        window.open(url);

    };

};