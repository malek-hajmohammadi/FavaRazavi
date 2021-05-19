listener = function (event) {

    class mainClass {



        loadForm() {
            this.setMarginForDetailedForms();
            this.setIntervalDuringShowingForm();
        }

        setAvg3sal() {
            let total = 0;
            let a = FormView.myForm.getItemByName('Field_12').getData();
            if (a == "") a = 0;
            a=parseInt(a);
            let b = FormView.myForm.getItemByName('Field_14').getData();
            if (b == "") b = 0;
            b=parseInt(b);
            let c = FormView.myForm.getItemByName('Field_16').getData();
            if (c == "") c = 0;
            c=parseInt(c);
            total = a + b + c;
            total = total / 3;
            FormView.myForm.getItemByName('Field_100').setData(total);
        };
        setSumA() {
            let total = 0;
            let a = FormView.myForm.getItemByName('Field_118').getData();
            if (a == "") a = 0;
            a=parseInt(a);

            let b = FormView.myForm.getItemByName('Field_119').getData();
            if (b == "") b = 0;
            b=parseInt(b);

            let c = FormView.myForm.getItemByName('Field_120').getData();
            if (c == "") c = 0;
            c=parseInt(c);

            let d = FormView.myForm.getItemByName('Field_121').getData();
            if (d == "") d = 0;
            d=parseInt(d);
            total = a + b + c + d;
            FormView.myForm.getItemByName('Field_34').setData(total);
        }

        setSumKargorooh() {
            let count = FormView.myForm.getItemByName('Field_37').list.subListView.data.length;
            let total = 0;
            for (let i = 0; i < count; i++) {
                let value = null;
                try {
                    value = $jq(".fieldset73 div[iamfowner$='2']")[i].tagthis.getData();
                } catch (e) {}
                if (value == null) value = 0;
                value = parseInt(value);
                if (isNaN(value)) value = 0;
                total += value;
            }
            FormView.myForm.getItemByName('Field_102').setData(total);
        }

        divideDifferenceDate(firstDate,secondDate){

            let dateObj = {
                allDays: 0,
                diffDays: 0,
                diffMonths: 0,
                diffYears: 0
            };

           if(firstDate.length>=8 && secondDate.length>=8) {

               dateObj.allDays = Utils.fastAjax('NForms', 'dataDiff', {sdate: firstDate, edate: secondDate}, true);
               /*دومی روزهای تعطیل رو حساب نمی کنه*/
               /*var diffH = Utils.fastAjax('NForms', 'dateDiffHolidayCheck', {sdate: firstDate, edate: secondDate}, true);*/

               dateObj.diffYears = dateObj.allDays / 365;
               dateObj.diffMonths = (dateObj.allDays % 365) / 30;
               dateObj.diffDays = (dateObj.allDays % 365) % 30;
           }

            return dateObj;

        }

        refreshDifferenceDate_ehtesabSanavat(){
            if(FormView && FormView.myForm && FormView.myForm.getItemByName('Field_21').list && FormView.myForm.getItemByName('Field_21').list.subListView
                &&  $jq(".fieldSet3 div[iamfowner$='4']").length>0

            ) {

                let count = FormView.myForm.getItemByName('Field_21').list.subListView.data.length;

                for (let i = 0; i < count; i++) {
                    let firstDate = $jq(".fieldSet3 div[iamfowner$='1']")[i].tagthis.getData();
                    let secondDate = $jq(".fieldSet3 div[iamfowner$='2']")[i].tagthis.getData();

                    let diffObj = this.divideDifferenceDate(firstDate, secondDate);

                    $jq(".fieldSet3 div[iamfowner$='4']")[i].tagthis.setData(diffObj.diffDays);
                    $jq(".fieldSet3 div[iamfowner$='5']")[i].tagthis.setData(diffObj.diffMonths);
                    $jq(".fieldSet3 div[iamfowner$='6']")[i].tagthis.setData(diffObj.diffYears);
                    $jq(".fieldSet3 div[iamfowner$='7']")[i].tagthis.setData(diffObj.allDays);

                }
            }

        }

        refreshZaribMadrakTahsili(){

            if(FormView && FormView.myForm && FormView.myForm.getItemByName('Field_35').list && FormView.myForm.getItemByName('Field_35').list.subListView
                &&  $jq(".fieldSet71 div[iamfowner$='4']").length>0 )
            {

                let count = FormView.myForm.getItemByName('Field_35').list.subListView.data.length;

                for (let i = 0; i < count; i++) {
                    let firstDate = $jq(".fieldSet71 div[iamfowner$='1']")[i].tagthis.getData();
                    let secondDate = $jq(".fieldSet71 div[iamfowner$='2']")[i].tagthis.getData();

                    let diffObj = this.divideDifferenceDate(firstDate, secondDate);

                    $jq(".fieldSet71 div[iamfowner$='4']")[i].tagthis.setData(diffObj.diffDays);
                    $jq(".fieldSet71 div[iamfowner$='5']")[i].tagthis.setData(diffObj.diffMonths);
                    $jq(".fieldSet71 div[iamfowner$='6']")[i].tagthis.setData(diffObj.diffYears);

                    let madrakTahsili =$jq(".fieldSet71 div[iamfowner$='0']")[i].tagthis.getData();
                    let sanavatKhedmatBaMadrakEhtesabi =$jq(".fieldSet71 div[iamfowner$='3']")[i].tagthis.getData();
                    let emtiaz=sanavatKhedmatBaMadrakEhtesabi*madrakTahsili;
                    $jq(".fieldSet71 div[iamfowner$='7']")[i].tagthis.setData(diffObj.diffYears);


                }
            }

        }

        setSumKhedmat() {

            let count = FormView.myForm.getItemByName('Field_21').list.subListView.data.length;
            let total = 0;
            for (let i = 0; i < count; i++) {
                let value = null;
                try {
                    value = $jq(".fieldSet3 div[iamfowner$='7']")[i].tagthis.getData();
                } catch (e) {}
                if (value == null) value = 0;
                value = parseInt(value);
                if (isNaN(value)) value = 0;
                total += value;
            }
            FormView.myForm.getItemByName('Field_23').setData(total);
        }

        setSumReportMoredi() {
            let count = FormView.myForm.getItemByName('Field_72').list.subListView.data.length;


            let total = count * 3;
            if (total > 20) total = 20;
            FormView.myForm.getItemByName('Field_71').setData(total);
        }

        setSumTashvigh() {
            /*$jq( "div[iamfowner$='0']" ).parent().css('overflow','visible');*/
            let count = FormView.myForm.getItemByName('Field_87').list.subListView.data.length;
            let total = 0;
            for (let i = 0; i < count; i++) {
                let value = null;
                try {
                    /* let value = FormView.myForm.getItemByName('Field_87').list.subListView.data[i].DMSFields[0].value;*/
                    value = $jq(".fieldSet_tashvigh div[iamfowner$='0']")[i].tagthis.getData();
                } catch (e) {}
                if (value == null) value = 0;
                value = parseInt(value);
                if (isNaN(value)) value = 0;
                total += value;
            }
            total = total * 0.4;
            let rotbe = FormView.myForm.getItemByName('Field_5').getData();
            switch (rotbe) {
                case "0":
                    if (total > 48) total = 48;
                    break;
                case "1":
                    if (total > 68) total = 68;
                    break;
                case "2":
                    if (total > 88) total = 88;
                    break;
                case "3":
                    if (total > 112) total = 112;
                    break;
            }
            FormView.myForm.getItemByName('Field_111').setData(total);
        }

        setMarginForDetailedForms(){
            $jq(".b-inbox-box").css("margin-top","30px");
        };

        setIntervalDuringShowingForm(){
            let that=this;
            setInterval(function(){
                /*that.refreshDifferenceDate_ehtesabSanavat();*/
               /* that.refreshZaribMadrakTahsili();*/
                that.setSumKhedmat();
                that.setSumKargorooh();
                that.setSumReportMoredi();


            }
            ,1000);
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
