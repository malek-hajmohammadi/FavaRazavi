listener = function (event) {

    class mainClass {





        setTotalScore(){

            let total=0;
            let columns=$jq( "div[iamfowner$='3'] input" );
            for(let i=0;i<columns.length;i++){
                let temp=parseInt(columns[i].value);
                total+=temp;
            }

            if(total>10){
                Utils.showModalMessage('امتیازات اکتسابی از سقف مجاز بیشتر است');
                return;
            }
            FormView.myForm.getItemByName('Field_50').setData(total);
        }
        getTotalScoreFromList(){
            let len=FormView.myForm.getItemByName('Field_8').list.subListView.data.length;
            let total=0;
            for(let i=0;i<len;i++){
                let temp=FormView.myForm.getItemByName('Field_8').list.subListView.data[i].DMSFields[3].value;
                total+=parseInt(temp);
            }
            return total;

        }
        loadForm() {
            this.setTotalScore();
            $jq( "div[iamfowner$='3'] input" ).off('keyup keypress blur change').on('keyup keypress blur change',function(){window.codeSet.setTotalScore()});

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
