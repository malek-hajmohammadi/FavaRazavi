listener = function (event) {

    class mainClass {


        answers=[];


        setTotalScore(){
            console.log(this);
            let total=window.codeSet.getTotalScoreFromList();
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
