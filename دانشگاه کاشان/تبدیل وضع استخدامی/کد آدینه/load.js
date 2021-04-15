listener = function (event) {
    class mainClass {
        setTotalScore() {
            let total = 0;
            let mafoq = parseInt(FormView.myForm.getItemByName('Field_29').getData());
            if (isNaN(mafoq)) mafoq = 0;
            let ejraee = parseInt(FormView.myForm.getItemByName('Field_34').getData());
            if (isNaN(ejraee)) ejraee = 0;
            let nemoone = parseInt(FormView.myForm.getItemByName('Field_36').getData());
            if (isNaN(nemoone)) nemoone = 0;
            let sesal = parseInt(FormView.myForm.getItemByName('Field_44').getData());
            if (isNaN(sesal)) sesal = 0;
            total = mafoq + ejraee + nemoone + sesal;
            FormView.myForm.getItemByName('Field_45').setData(total);
        }

        setReadOnly() {
            $jq('.mafoq >input').attr("readonly", "true");
            $jq('.mafoq >input').css("background", "gainsboro");
            $jq('.ejraee >input').attr("readonly", "true");
            $jq('.ejraee >input').css("background", "gainsboro");
            $jq('.nemoone >input').attr("readonly", "true");
            $jq('.nemoone >input').css("background", "gainsboro");
            $jq('.sesal >input').attr("readonly", "true");
            $jq('.sesal >input').css("background", "gainsboro");
            $jq('.totalScore >input').attr("readonly", "true");
            $jq('.totalScore >input').css("background", "gainsboro");
        }

        setSumMafoq() {

                let total = 0;
                let a = FormView.myForm.getItemByName('Field_10').getData();
                if (a == "") a = 0;
                let b = FormView.myForm.getItemByName('Field_11').getData();
                if (b == "") b = 0;
                let c = FormView.myForm.getItemByName('Field_12').getData();
                if (c == "") c = 0;
                let d = FormView.myForm.getItemByName('Field_13').getData();
                if (d == "") d = 0;
                let e = FormView.myForm.getItemByName('Field_14').getData();
                if (e == "") e = 0;
                total = a + b + c + d + e;
                FormView.myForm.getItemByName('Field_15').setData(total);

        }

        setSumBelafasl() {
            let total = 0;
            let a = FormView.myForm.getItemByName('Field_16').getData();
            if (a == "") a = 0;
            let b = FormView.myForm.getItemByName('Field_17').getData();
            if (b == "") b = 0;
            let c = FormView.myForm.getItemByName('Field_18').getData();
            if (c == "") c = 0;
            let d = FormView.myForm.getItemByName('Field_19').getData();
            if (d == "") d = 0;
            let e = FormView.myForm.getItemByName('Field_20').getData();
            if (e == "") e = 0;
            total = a + b + c + d + e;
            FormView.myForm.getItemByName('Field_21').setData(total);
        }

        setSumMostaqeem() {
            let total = 0;
            let a = FormView.myForm.getItemByName('Field_22').getData();
            if (a == "") a = 0;
            let b = FormView.myForm.getItemByName('Field_23').getData();
            if (b == "") b = 0;
            let c = FormView.myForm.getItemByName('Field_24').getData();
            if (c == "") c = 0;
            let d = FormView.myForm.getItemByName('Field_25').getData();
            if (d == "") d = 0;
            let e = FormView.myForm.getItemByName('Field_26').getData();
            if (e == "") e = 0;
            total = a + b + c + d + e;
            FormView.myForm.getItemByName('Field_27').setData(total);
        }

        setAvgMafoq() {
            let total = 0;
            let i = 3;
            let a = FormView.myForm.getItemByName('Field_15').getData();
            if (a == "") a = 0;
            let b = FormView.myForm.getItemByName('Field_21').getData();
            if (b == "") b = 0;
            let c = FormView.myForm.getItemByName('Field_27').getData();
            if (c == "") c = 0;
            total = a + b + c;
            let topchrt = FormView.myForm.getItemByName('Field_7').getData();
            if (typeof topchrt === "undefined") i = 2;
            total = total / i;
            FormView.myForm.getItemByName('Field_28').setData(total);
        }

        setAvgSesal() {
            let total = 0;
            let a = FormView.myForm.getItemByName('Field_38').getData();
            if (a == "") a = 0;
            let b = FormView.myForm.getItemByName('Field_40').getData();
            if (b == "") b = 0;
            let c = FormView.myForm.getItemByName('Field_42').getData();
            if (c == "") c = 0;
            total = a + b + c;
            total = total / 6;
            FormView.myForm.getItemByName('Field_43').setData(total);
        }

        setSums() {
           try {
               this.setSumMafoq();
               this.setSumBelafasl();
               this.setSumMostaqeem();
               this.setAvgMafoq();
               this.setAvgSesal();
           }
           catch (e) {

           }
        }

        loadForm() {
            this.setStageAppearance();
            this.setSums();
            this.setReadOnly();
        }

        checkScoreLimitation() {
            let sumTopMafoq = FormView.myForm.getItemByName('Field_29').getData();
            if (sumTopMafoq > 35) {
                Utils.showModalMessage('امتياز از سقف مجاز بيشتر است');
                return false;
            }
            let sumTopEjraee = FormView.myForm.getItemByName('Field_34').getData();
            if (sumTopEjraee > 9) {
                Utils.showModalMessage('امتياز از سقف مجاز بيشتر است');
                return false;
            }
            let sumTopNemoone = FormView.myForm.getItemByName('Field_36').getData();
            if (sumTopNemoone > 6) {
                Utils.showModalMessage('امتياز از سقف مجاز بيشتر است');
                return false;
            }
            let sumTopSesal = FormView.myForm.getItemByName('Field_44').getData();
            if (sumTopSesal > 50) {
                Utils.showModalMessage('امتياز از سقف مجاز بيشتر است');
                return false;
            }
            return true;
        }

        setStageAppearance() {
            let stageNumber = FormView.myForm.getItemByName('Field_47').getData();
            this.hiddenAllFieldset();
            switch (stageNumber) {
                case "0":
                    this.showFieldset(0);
                    break;
                case "1":
                    this.showFieldset(1);
                    break;
                case "2":
                    this.showFieldset(2);
                    break;
                case "3":
                    this.showFieldset(3);
                    break;
                case "4":
                    this.showFieldset(1);
                    this.showFieldset(2);
                    this.showFieldset(3);
                    this.showFieldset(4);
                    break;
            }
        }

        hiddenAllFieldset() {
            $jq(".fieldSet0").css("display", "none");
            $jq(".fieldSet1").css("display", "none");
            $jq(".fieldSet2").css("display", "none");
            $jq(".fieldSet3").css("display", "none");
            $jq(".fieldSet4").css("display", "none");
        }

        showFieldset(stage) {
            $jq(".fieldSet" + stage).css("display", "");
        }
    };var waitInterval = setInterval(function () {
        if (FormView && FormView.myForm) {
            let instance = new mainClass();
            window.codeSet = instance;
            window.codeSet.loadForm();
        }
        clearInterval(waitInterval);
    }, 300);
};