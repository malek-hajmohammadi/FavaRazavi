listener = function (event) {
    class mainClass {
        setTotalScore() {
            let total = 0;
            let khodArzyabi = parseInt(FormView.myForm.getItemByName('Field_49').getData());
            if (isNaN(khodArzyabi)) khodArzyabi = 0;
            let tashvigh = parseInt(FormView.myForm.getItemByName('Field_24').getData());
            if (isNaN(tashvigh)) tashvigh = 0;
            let vazaef = parseInt(FormView.myForm.getItemByName('Field_51').getData());
            if (isNaN(vazaef)) vazaef = 0;
            let takrim = parseInt(FormView.myForm.getItemByName('Field_52').getData());
            if (isNaN(takrim)) takrim = 0;
            let tasalot = parseInt(FormView.myForm.getItemByName('Field_53').getData());
            if (isNaN(tasalot)) tasalot = 0;
            let hozor = parseInt(FormView.myForm.getItemByName('Field_54').getData());
            if (isNaN(hozor)) hozor = 0;
            let paybandi = parseInt(FormView.myForm.getItemByName('Field_16').getData());
            if (isNaN(paybandi)) paybandi = 0;
            let eraehTarh = parseInt(FormView.myForm.getItemByName('Field_56').getData());
            if (isNaN(eraehTarh)) eraehTarh = 0;
            let amoozesh = parseInt(FormView.myForm.getItemByName('Field_57').getData());
            if (isNaN(amoozesh)) amoozesh = 0;
            let onvanFaliat = parseInt(FormView.myForm.getItemByName('Field_58').getData());
            if (isNaN(onvanFaliat)) onvanFaliat = 0;
            let entezamatKhabgah = parseInt(FormView.myForm.getItemByName('Field_61').getData());
            if (isNaN(entezamatKhabgah)) entezamatKhabgah = 0;
            total = khodArzyabi + tashvigh + vazaef + takrim + tasalot + hozor + paybandi + eraehTarh + amoozesh + onvanFaliat + entezamatKhabgah;
            FormView.myForm.getItemByName('Field_50').setData(total);
        }

        setReadOnly() {
            $jq('.tashvigh >input').attr("readonly", "true");
            $jq('.tashvigh >input').css("background", "gainsboro");
            $jq('.vazaef >input').attr("readonly", "true");
            $jq('.vazaef >input').css("background", "gainsboro");
            $jq('.takrim >input').attr("readonly", "true");
            $jq('.takrim >input').css("background", "gainsboro");
            $jq('.tasalot >input').attr("readonly", "true");
            $jq('.tasalot >input').css("background", "gainsboro");
            $jq('.hozor >input').attr("readonly", "true");
            $jq('.hozor >input').css("background", "gainsboro");
            $jq('.eraehTarh >input').attr("readonly", "true");
            $jq('.eraehTarh >input').css("background", "gainsboro");
            $jq('.onvanFaliat >input').attr("readonly", "true");
            $jq('.onvanFaliat >input').css("background", "gainsboro");
            $jq('.totalScore >input').attr("readonly", "true");
            $jq('.totalScore >input').css("background", "gainsboro");
        }

        setSumTashvigh() {       /*$jq( "div[iamfowner$='0']" ).parent().css('overflow','visible');*/
            let count = FormView.myForm.getItemByName('Field_9').list.subListView.data.length;
            let total = 0;
            for (let i = 0; i < count; i++) {
                let value = null;
                try {           /* let value = FormView.myForm.getItemByName('Field_8').list.subListView.data[i].DMSFields[0].value;*/
                    value = $jq("div[iamfowner$='0']")[i].tagthis.getData();
                } catch (e) {
                }
                if (value == null) value = 0;
                value = parseInt(value);
                if (isNaN(value)) value = 0;
                total += value;

            }
            if (total > 10) total = 10;
            FormView.myForm.getItemByName('Field_24').setData(total); /* let total=0;             let columns=$jq( "div[iamfowner$='0'] input" );             for(let i=0;i<columns.length;i++){                 let temp=parseInt(columns[i].value);                 total+=temp;             }              if(total>10){                 Utils.showModalMessage('امتيازات اكتسابي از سقف مجاز بيشتر است');                 return;             }             FormView.myForm.getItemByName('Field_24').setData(total);*/
        }

        setSumEraehTarh() {
            let count = FormView.myForm.getItemByName('Field_17').list.subListView.data.length;
            let total = 0;
            for (let i = 0; i < count; i++) {
                let value = null;
                try {
                    value = $jq(".tdEraehTarh div[iamfowner$='3']")[i].tagthis.getData();
                } catch (e) {
                }
                if (value == null) value = 0;
                value = parseInt(value);
                if (isNaN(value)) value = 0;
                total += value;


            }
            FormView.myForm.getItemByName('Field_56').setData(total);
        }

        setSumOnvanFaliat() {
            let count = FormView.myForm.getItemByName('Field_20').list.subListView.data.length;
            let total = 0;
            for (let i = 0; i < count; i++) {
                let value = null;
                try {
                    value = $jq(".tdOnvanFaliat div[iamfowner$='3']")[i].tagthis.getData();
                } catch (e) {
                }
                if (value == null) value = 0;
                value = parseInt(value);
                if (isNaN(value)) value = 0;
                total += value;
            }
            FormView.myForm.getItemByName('Field_58').setData(total);
        }

        setSumVazaef() {
            let total = 0;
            let a = FormView.myForm.getItemByName('Field_25').getData();
            if (a == "") a = 0;
            let b = FormView.myForm.getItemByName('Field_26').getData();
            if (b == "") b = 0;
            let d = FormView.myForm.getItemByName('Field_28').getData();
            if (d == "") d = 0;
            let e = FormView.myForm.getItemByName('Field_29').getData();
            if (e == "") e = 0;
            let f = FormView.myForm.getItemByName('Field_30').getData();
            if (f == "") f = 0;
            let g = FormView.myForm.getItemByName('Field_31').getData();
            if (g == "") g = 0;
            let h = FormView.myForm.getItemByName('Field_32').getData();
            if (h == "") h = 0;
            let i = FormView.myForm.getItemByName('Field_33').getData();
            if (i == "") i = 0;
            let j = FormView.myForm.getItemByName('Field_34').getData();
            if (j == "") j = 0;
            total = a + b + d + e + f + g + h + i + j;
            total = total / 10;
            FormView.myForm.getItemByName('Field_51').setData(total);
        }

        setSumTakrim() {
            let total = 0;
            let a = FormView.myForm.getItemByName('Field_36').getData();
            if (a == "") a = 0;
            let b = FormView.myForm.getItemByName('Field_37').getData();
            if (b == "") b = 0;
            let c = FormView.myForm.getItemByName('Field_38').getData();
            if (c == "") c = 0;
            let d = FormView.myForm.getItemByName('Field_39').getData();
            if (d == "") d = 0;
            let e = FormView.myForm.getItemByName('Field_40').getData();
            if (e == "") e = 0;
            let f = FormView.myForm.getItemByName('Field_41').getData();
            if (f == "") f = 0;
            let g = FormView.myForm.getItemByName('Field_42').getData();
            if (g == "") g = 0;
            total = a + b + c + d + e + f + g;
            total = total / 100;
            FormView.myForm.getItemByName('Field_52').setData(total);
        }

        setSumTasalot() {
            let total = 0;
            let a = FormView.myForm.getItemByName('Field_43').getData();
            if (a == "") a = 0;
            let b = FormView.myForm.getItemByName('Field_44').getData();
            if (b == "") b = 0;
            total = a + b;
            total = total / 10;
            FormView.myForm.getItemByName('Field_53').setData(total);
        }

        setSumEntezamat() {
            let total = 0;
            let a = FormView.myForm.getItemByName('Field_59').getData();
            if (a == "") a = 0;
            let b = FormView.myForm.getItemByName('Field_60').getData();
            if (b == "") b = 0;
            total = a + b;
            total = total / 10;
            FormView.myForm.getItemByName('Field_61').setData(total);
        }

        setSumHozor() {
            let total = 0;
            let a = FormView.myForm.getItemByName('Field_45').getData();
            if (a == "") a = 0;
            let b = FormView.myForm.getItemByName('Field_46').getData();
            if (b == "") b = 0;
            let c = FormView.myForm.getItemByName('Field_47').getData();
            if (c == "") c = 0;
            total = a + b + c;
            total = total / 10;
            FormView.myForm.getItemByName('Field_54').setData(total);
        }

        setSums() {
            try {
                this.setSumTashvigh();
                this.setSumEraehTarh();
                this.setSumOnvanFaliat();
                this.setSumVazaef();
                this.setSumTakrim();
                this.setSumTasalot();
                this.setSumEntezamat();
                this.setSumHozor();
            }catch (e) {
                
            }
        }

        loadForm() {
            this.setStageAppearance();
            this.setSums();
            this.setReadOnly();
            this.setIntervalForCompletedField();
        }

        setIntervalForCompletedField() {
            let that = this;
            setInterval(function () {
                $jq("div[iamfowner$='0']").parent().css('overflow', 'visible');
                try {
                    that.setSumTashvigh();
                    that.setSumEraehTarh();
                    that.setSumOnvanFaliat();
                }catch (e) {

                }
            }, 1000);
        }

        checkScoreLimitation() {
            /*
            let sumTashvigh = FormView.myForm.getItemByName('Field_24').getData();
            if (sumTashvigh > 10) {
                Utils.showModalMessage('امتيازات تقدير و تشكر از سقف مجاز بيشتر است');
                return false;
            }
            let sumEraehTarh = FormView.myForm.getItemByName('Field_56').getData();
            if (sumEraehTarh > 5) {
                Utils.showModalMessage('امتيازات ارائه طرح هاي اجرايي و پيشنهادها از سقف مجاز بيشتر است');
                return false;
            }
            let sumOnvanFaliat = FormView.myForm.getItemByName('Field_58').getData();
            if (sumOnvanFaliat > 5) {
                Utils.showModalMessage('امتيازات فعاليتها و كسب موفقيتها از سقف مجاز بيشتر است');
                return false;
            }
            let sumVazaef = FormView.myForm.getItemByName('Field_51').getData();
            if (sumVazaef > 35) {
                Utils.showModalMessage('امتيازات وظايف و مسئوليتها از سقف مجاز بيشتر است');
                return false;
            }
            let sumTakrim = FormView.myForm.getItemByName('Field_52').getData();
            if (sumTakrim > 8) {
                Utils.showModalMessage('امتيازات تكريم ارباب رجوع از سقف مجاز بيشتر است');
                return false;
            }
            let sumTasalot = FormView.myForm.getItemByName('Field_53').getData();
            if (sumTasalot > 6) {
                Utils.showModalMessage('امتيازات ميزان تسلط در استفاده از نرم افزارهاي اداري از سقف مجاز بيشتر است');
                return false;
            }
            let sumHozor = FormView.myForm.getItemByName('Field_54').getData();
            if (sumHozor > 10) {
                Utils.showModalMessage('امتيازات حضور و غياب اداري از سقف مجاز بيشتر است');
                return false;
            }

             */
            return true;
        }

        setStageAppearance() {
            let stageNumber = FormView.myForm.getItemByName('Field_23').getData();
            this.hiddenAllFieldset();
            switch (stageNumber) {
                case "1":
                    this.showFieldset(0);
                    this.showFieldset(3);
                    this.showFieldset(4);
                    this.showFieldset(5);
                    this.showFieldset(9);
                    break;
                case "2":
                    this.showFieldset(3);
                    this.showFieldset(4);
                    this.showFieldset(5);
                    this.showFieldset(7);
                    this.showFieldset(9);
                    break;
                case "3":
                    this.showFieldset(3);
                    this.showFieldset(9);
                    break;
                case "4":
                    this.showFieldset(3);
                    this.showFieldset(8);
                    break;
                case "5":
                    this.showFieldset(3);
                    this.showFieldset(4);
                    this.showFieldset(6);
                    break;
                case "6":
                    this.showFieldset(3);
                    this.showFieldset(4);
                    this.showFieldset(5);
                    this.showFieldset(6);
                    break;
                case "7":
                case "8":
                case "9":
                    this.showFieldset(2);
                    this.showFieldset(3);
                    this.showFieldset(4);
                    this.showFieldset(5);
                    this.showFieldset(6);
                    this.showFieldset(7);
                    this.showFieldset(8);
                    this.showFieldset(9);
                    this.showFieldset(10);
                    break;
            }
        }

        hiddenAllFieldset() {
            $jq(".fieldSet0").css("display", "none");
            $jq(".fieldSet2").css("display", "none");
            $jq(".fieldSet3").css("display", "none");
            $jq(".fieldSet4").css("display", "none");
            $jq(".fieldSet5").css("display", "none");
            $jq(".fieldSet6").css("display", "none");
            $jq(".fieldSet7").css("display", "none");
            $jq(".fieldSet8").css("display", "none");
            $jq(".fieldSet9").css("display", "none");
            $jq(".fieldSet10").css("display", "none");
        }

        showFieldset(stage) {
            $jq(".fieldSet" + stage).css("display", "");
        }
    };
    var waitInterval = setInterval(function () {
        if (FormView && FormView.myForm) {
            let instance = new mainClass();
            window.codeSet = instance;
            window.codeSet.loadForm();
        }
        clearInterval(waitInterval);
    }, 300);
};