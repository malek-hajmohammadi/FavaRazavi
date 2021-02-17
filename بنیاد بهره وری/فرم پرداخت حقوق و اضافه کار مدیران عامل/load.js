listener = function (event) {

    class mainClass {

        loadForm() {

            let cableNumber=this.getCable();
            switch (cableNumber) {
                case "1":/*متقاضی نود اول*/
                    this.howToDisplayCable1();
                    break;
                case "2":/*معاون توسعه مدیریت و منابع بنیاد*/
                    this.howToDisplayCable2();
                    break;
                case "3":/*متقاضی دوم*/
                    this.howToDisplayCable3();
                    break;
                case "4":/*مدیریت امور مالی بنیاد*/
                    this.howToDisplayCable4();
                    break;
                case "5":/*بایگانی*/
                    this.howToDisplayCable5();
                    break;

            }

        }

        getCable(){
            let cableNumber=FormView.myForm.getItemByName('Field_5').getData();
            return cableNumber;
        }

        howToDisplayCable1(){
            $jq('.attach').css('display','none');
            $jq('.attachMessage').css('display','none');
        }

        howToDisplayCable2(){
            $jq('.attach').css('display','none');
            $jq('.attachMessage').css('display','none');
        }

        howToDisplayCable3(){

        }

        howToDisplayCable4(){
            $jq('.attachMessage').css('display','none');

        }

        howToDisplayCable5(){
            $jq('.attachMessage').css('display','none');

        }

        confirm_moteghazi2(){
            var attachFile = FormView.myForm.getItemByName('Field_3').getData().length;
            if (attachFile < 3) {
                Utils.showModalMessage('لطفا تصویر فیش حقوقی را پیوست کنید');
                return false;
            }

            var attachFile = FormView.myForm.getItemByName('Field_4').getData().length;
            if (attachFile < 3) {
                Utils.showModalMessage('لطفا  تصویر فیش واریزی را پیوست کنید');
                return false;
            }

            return true;

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

