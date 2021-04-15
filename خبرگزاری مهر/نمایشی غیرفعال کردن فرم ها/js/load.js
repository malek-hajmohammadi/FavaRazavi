listener = function (event) {

    class mainClass {


        firstSutation=0;



        loadForm() {
            this.getSituationFlag();

        }
        getSituationFlag(){
            var res = Utils.fastAjax('WorkFlowAjaxFunc', 'loadStopFlag');
            this.firstSutation=res;
            FormOnly.allFieldsContianer[0].setData(res);
        }

        saveFlag(){
            let selectedSituation=FormOnly.allFieldsContianer[0].getData();
            if(selectedSituation=="0"){
                Utils.showModalMessage('لطفا وضعیت فرمها را انتخاب کنید');
                return;
            }

            if(this.firstSutation==selectedSituation){
                Utils.showModalMessage('تغییری در وضعیت فرم ها ایجاد نشد');
                return;
            }


            if(selectedSituation=="1")/*active*/
            {
                selectedSituation=1;

            }
            if(selectedSituation=="2")/*inactive*/
            {
                selectedSituation=2;

            }
            this.saveSituationInTable(selectedSituation);

        }
        saveSituationInTable(situation){
            var html = Utils.fastAjax('WorkFlowAjaxFunc', 'saveStopFlag',{situationFlag:situation});
            Utils.showModalMessage('تغییر وضعیت ایجاد فرم های مشخص شده ، با موفقیت انجام شد');

        }


    };

    var waitInterval = setInterval(function () {
        if (FormOnly) {

            let instance = new mainClass();
            window.codeSet = instance;
            window.codeSet.loadForm();
        }

        clearInterval(waitInterval);
    }, 300);

};

