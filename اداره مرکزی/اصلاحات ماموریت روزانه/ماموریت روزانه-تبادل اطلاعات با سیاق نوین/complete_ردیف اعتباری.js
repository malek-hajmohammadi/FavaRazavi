Class.create({
    load: function (self) {
        try {
            /*
                                var arrValue = FormView.myForm.getItemByName('Field_49').fillCompleteFieldCredits();
                                return arrValue;

             */
            self.creditsData = [[]]; /*آرایه از داده هایی که آیجکس می گیرم که شامل شماره ردیف و مانده اعتبار و سقف اعتبار است*/
            self.creditsCompletedField = [[]];  /*آرایه ای که در فیلد تکمیل شوند استفاده می شود*/

            var res = Utils.fastAjax('WorkFlowAjaxFunc', 'mission_getCredits');
            res = JSON.parse(res);

            var i = 0;
            var item = "";
            for (item in res) {

                self.creditsCompletedField[i] = [];
                self.creditsCompletedField[i][0] = i;
                self.creditsCompletedField[i][1] = res[item][0];

                self.creditsData[i] = [];
                self.creditsData[i][0] = res[item][0];
                self.creditsData[i][1] = res[item][1];
                self.creditsData[i][2] = res[item][2];


                console.log(res[item][0]);
                i++;

            }


            var waitInterval = setInterval(function () {
                if (FormView && FormView.myForm) {

                    FormView.myForm.getItemByName('Field_49').setCreditsData(self.creditsData);
                    clearInterval(waitInterval);

                }

            }, 20);

            return self.creditsCompletedField;


        } catch (e) {
            console.log(e);
        }
        return [[0, 'دوره اي نيست']];
    }
});

