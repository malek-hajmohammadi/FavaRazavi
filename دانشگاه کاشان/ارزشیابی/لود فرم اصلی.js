listener = function (event) {

    class mainClass {

        pointerTashvigh=0;
        pointerEraehTarh=0;
        pointerOnvanFaliat=0;

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

        setSumEraehTarh(){
            let count=FormView.myForm.getItemByName('Field_38').list.subListView.data.length;
            let total=0;
            for(let i=0;i<count;i++){
                let value=null;
                try {
                    value=$jq( ".tdEraehTarh div[iamfowner$='3']" )[i].tagthis.getData();
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
            FormView.myForm.getItemByName('Field_82').setData(total);

        }

        setSumOnvanFaliat(){
            let count=FormView.myForm.getItemByName('Field_42').list.subListView.data.length;
            let total=0;
            for(let i=0;i<count;i++){
                let value=null;
                try {
                    value=$jq( ".tdOnvanFaliat div[iamfowner$='3']" )[i].tagthis.getData();
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
            FormView.myForm.getItemByName('Field_84').setData(total);
        }

        setSumVazaef(){
            let total=0;

            let a=FormView.myForm.getItemByName('Field_51').getData();
            if(a=="") a=0;

            let b=FormView.myForm.getItemByName('Field_52').getData();
            if(b=="") b=0;

            let c=FormView.myForm.getItemByName('Field_53').getData();
            if(c=="") c=0;

            let d=FormView.myForm.getItemByName('Field_54').getData();
            if(d=="") d=0;

            let e=FormView.myForm.getItemByName('Field_55').getData();
            if(e=="") e=0;

            let f=FormView.myForm.getItemByName('Field_56').getData();
            if(f=="") f=0;

            let g=FormView.myForm.getItemByName('Field_57').getData();
            if(g=="") g=0;

            let h=FormView.myForm.getItemByName('Field_58').getData();
            if(h=="") h=0;

            let i=FormView.myForm.getItemByName('Field_59').getData();
            if(i=="") i=0;

            let j=FormView.myForm.getItemByName('Field_60').getData();
            if(j=="") j=0;

            let l=FormView.myForm.getItemByName('Field_61').getData();
            if(l=="") l=0;

            total=a+b+c+d+e+f+g+h+i+j+h+i+j+l;
            FormView.myForm.getItemByName('Field_77').setData(total);
        }

        setSumTakrim(){
            let total=0;

            let a=FormView.myForm.getItemByName('Field_62').getData();
            if(a=="") a=0;

            let b=FormView.myForm.getItemByName('Field_63').getData();
            if(b=="") b=0;

            let c=FormView.myForm.getItemByName('Field_64').getData();
            if(c=="") c=0;

            let d=FormView.myForm.getItemByName('Field_65').getData();
            if(d=="") d=0;

            let e=FormView.myForm.getItemByName('Field_66').getData();
            if(e=="") e=0;

            let f=FormView.myForm.getItemByName('Field_67').getData();
            if(f=="") f=0;

            let g=FormView.myForm.getItemByName('Field_68').getData();
            if(g=="") g=0;



            total=a+b+c+d+e+f+g;
            FormView.myForm.getItemByName('Field_78').setData(total);
        }

        setSumTasalot(){
            let total=0;

            let a=FormView.myForm.getItemByName('Field_69').getData();
            if(a=="") a=0;

            let b=FormView.myForm.getItemByName('Field_70').getData();
            if(b=="") b=0;


            total=a+b;
            FormView.myForm.getItemByName('Field_79').setData(total);
        }

        setSumHozor(){
            let total=0;

            let a=FormView.myForm.getItemByName('Field_71').getData();
            if(a=="") a=0;

            let b=FormView.myForm.getItemByName('Field_72').getData();
            if(b=="") b=0;

            let c=FormView.myForm.getItemByName('Field_73').getData();
            if(c=="") c=0;

            total=a+b+c;
            FormView.myForm.getItemByName('Field_80').setData(total);
        }

        setSums(){
            this.setSumTashvigh();
            this.setSumEraehTarh();
            this.setSumOnvanFaliat();
            this.setSumVazaef();
            this.setSumTakrim();
            this.setSumTasalot();
            this.setSumHozor();
        }



        loadForm() {
            this.setSums();
            this.setReadOnly();
            this.setIntervalForCompletedField();

        }

        setPointerTashvigh(event){
            console.log('setPointerTashvigh');
            console.log(event);

        }
        setPointerEraehTarh(){
            console.log('setPointerEraehTarh');

        }
        setPointerOnvanFaliat(){
            console.log('setPointerOnvanFaliat');

        }


        checkAndCorrectTashvigh(pointerTashvigh){
            console.log('checkAndCorrectTshvigh');


        }
        checkAndCorrectEraehTarh(pointerEraehTarh){
            console.log('checkAndCorrecEraehTarh');

        }

        checkAndCorrectOnvanFaliat(pointerOnvanFaliat){
            console.log('pointerOvan');

        }


        setIntervalForCompletedField(){

            let that=this;
            setInterval(function () {
                $jq( "div[iamfowner$='0']" ).parent().css('overflow','visible');
                that.setSumTashvigh();
                /*that.checkAndCorrectTashvigh(that.pointerTashvigh);*/

                that.setSumEraehTarh();
               /* that.checkAndCorrectEraehTarh(that.pointerEraehTarh);*/

                that.setSumOnvanFaliat();
               /* that.checkAndCorrectOnvanFaliat(that.pointerOnvanFaliat);*/
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
