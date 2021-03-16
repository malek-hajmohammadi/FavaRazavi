listener = function (event) {

    class mainClass {

        setTotalScore(){
            let total=0;
            let khodArzyabi=parseInt(FormView.myForm.getItemByName('Field_75').getData());
            if(isNaN(khodArzyabi)) khodArzyabi=0;

            let tashvigh=parseInt(FormView.myForm.getItemByName('Field_50').getData());
            if(isNaN(tashvigh)) tashvigh=0;

            let vazaef= parseInt(FormView.myForm.getItemByName('Field_77').getData());
            if(isNaN(vazaef)) vazaef=0;

            let takrim= parseInt(FormView.myForm.getItemByName('Field_78').getData());
            if(isNaN(takrim)) takrim=0;

            let tasalot= parseInt(FormView.myForm.getItemByName('Field_79').getData());
            if(isNaN(tasalot)) tasalot=0;

            let hozor= parseInt(FormView.myForm.getItemByName('Field_80').getData());
            if(isNaN(hozor)) hozor=0;

            let paybandi=parseInt( FormView.myForm.getItemByName('Field_37').getData());
            if(isNaN(paybandi)) paybandi=0;

            let eraehTarh=parseInt( FormView.myForm.getItemByName('Field_82').getData());
            if(isNaN(eraehTarh)) eraehTarh=0;

            let amoozesh= parseInt(FormView.myForm.getItemByName('Field_83').getData());
            if(isNaN(amoozesh)) amoozesh=0;

            let onvanFaliat=parseInt( FormView.myForm.getItemByName('Field_84').getData());
            if(isNaN(onvanFaliat)) onvanFaliat=0;

            total=khodArzyabi+tashvigh+vazaef+takrim+
            tasalot+hozor+paybandi+eraehTarh+amoozesh+
            onvanFaliat;
            FormView.myForm.getItemByName('Field_76').setData(total);
        }
        setReadOnly(){

            $jq('.tashvigh >input').attr("readonly","true");
            $jq('.tashvigh >input').css("background","gainsboro");

            $jq('.vazaef >input').attr("readonly","true");
            $jq('.vazaef >input').css("background","gainsboro");

            $jq('.takrim >input').attr("readonly","true");
            $jq('.takrim >input').css("background","gainsboro");

            $jq('.tasalot >input').attr("readonly","true");
            $jq('.tasalot >input').css("background","gainsboro");

            $jq('.hozor >input').attr("readonly","true");
            $jq('.hozor >input').css("background","gainsboro");


            $jq('.eraehTarh >input').attr("readonly","true");
            $jq('.eraehTarh >input').css("background","gainsboro");



            $jq('.onvanFaliat >input').attr("readonly","true");
            $jq('.onvanFaliat >input').css("background","gainsboro");

        }

        setSumTashvigh(){

            /*$jq( "div[iamfowner$='0']" ).parent().css('overflow','visible');*/

            let count=FormView.myForm.getItemByName('Field_8').list.subListView.data.length;
            let total=0;
            for(let i=0;i<count;i++){
                let value=null;
                try {
                   /* let value = FormView.myForm.getItemByName('Field_8').list.subListView.data[i].DMSFields[0].value;*/
                    value=$jq( "div[iamfowner$='0']" )[i].tagthis.getData();
                }
                catch (e) {

                }

                if(value==null)
                    value=0;
                value=parseInt(value);
                if(isNaN(value))
                    value=0;

                total+=value;
            }
            FormView.myForm.getItemByName('Field_50').setData(total);




            console.log('into setTotalScore');

           /* let total=0;
            let columns=$jq( "div[iamfowner$='0'] input" );
            for(let i=0;i<columns.length;i++){
                let temp=parseInt(columns[i].value);
                total+=temp;
            }

            if(total>10){
                Utils.showModalMessage('امتیازات اکتسابی از سقف مجاز بیشتر است');
                return;
            }
            FormView.myForm.getItemByName('Field_50').setData(total);*/
        }



        loadForm() {
            this.setSumTashvigh();
            /*$jq( "div[iamfowner$='3'] input" ).off('keyup keypress blur change').on('keyup keypress blur change',function(){window.codeSet.setTotalScore()});*/
            $jq( "div[iamfowner$='0']" ).parent().css('overflow','visible');
            this.setIntervalForCompletedField();
            this.setReadOnly();

        }
        setIntervalForCompletedField(){
            setInterval(function () {
                $jq( "div[iamfowner$='0']" ).parent().css('overflow','visible');
                this.setSumT
                }
            , 1000);
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
