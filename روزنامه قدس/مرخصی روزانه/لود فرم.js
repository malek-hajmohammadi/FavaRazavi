listener = function (event) {

    class mainClass {


        answers = [];


        loadForm() {
            this.makeReadOnly();

        }

        setNumberOfDays() {
            var msg = '';
            var typeDiff = '';

            var beginDate = FormView.myForm.getItemByName('Field_4').getData();
            var endDate = FormView.myForm.getItemByName('Field_5').getData();

            var step = FormView.myForm.getItemByName('Field_6').getData();             /* اختلاف دو روز */
            var DiffWithHoliday = Utils.fastAjax('NForms', 'dataDiff', {sdate: beginDate, edate: endDate}, true);             /* احتساب جمعه و تعطيلات */
            var diffWithoutHoliday = Utils.fastAjax('NForms', 'dateDiffHolidayCheck', {
                sdate: beginDate,
                edate: endDate
            }, true);

            var ModatMorkhasi = parseInt(diffWithoutHoliday);

            if (ModatMorkhasi.length == 1)
                ModatMorkhasi = '0' + ModatMorkhasi;

            FormView.myForm.getItemByName('Field_6').setData(ModatMorkhasi);
            if (endDate == '') FormView.myForm.getItemByName('Field_6').setData('0');

        }
        makeReadOnly(){

            $jq("[rowid='17743']>input").attr('readonly',true);
            $jq("[rowid='17743']>input").css("background-color", "#e0e0e0");

            $jq("[rowid='17748']>input").attr('readonly',true);
            $jq("[rowid='17748']>input").css("background-color", "#e0e0e0");

        }

        isFilledValues(){
            let beginDate=FormView.myForm.getItemByName('Field_4').getData();
            let beginDateSize=beginDate.length;
            if(beginDateSize<8){
                Utils.showModalMessage('لطفا تاریخ شروع مرخصی را وارد نمایید');
                return false;
            }

            let endDate=FormView.myForm.getItemByName('Field_5').getData();
            let endDateSize=endDate.length;
            if(endDateSize<8){
                Utils.showModalMessage('لطفا تاریخ انتهای مرخصی را وارد نمایید');
                return false;
            }

            let personalId=FormView.myForm.getItemByName('Field_1').getData();
            if(personalId.length<1){
                Utils.showModalMessage('شماره پرسنلی وارد نشده است');
                return false;
            }

            let dateDiff=FormView.myForm.getItemByName('Field_6').getData();
            if(dateDiff<1){
                Utils.showModalMessage('تاریخ خاتمه مرخصی می بایست بیشتر از تاریخ شروع باشد');
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
