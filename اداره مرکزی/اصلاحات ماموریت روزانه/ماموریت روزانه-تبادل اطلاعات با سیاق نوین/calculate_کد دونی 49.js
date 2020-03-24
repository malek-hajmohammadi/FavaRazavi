
this.jcode = function(self){

    self.creditsData=[[]]; /*آرایه از داده هایی که آیجکس می گیرم که شامل شماره ردیف و مانده اعتبار و سقف اعتبار است*/
    self.creditsCompletedField=[[]];  /*آرایه ای که در فیلد تکمیل شوند استفاده می شود*/

    self.setCreditsData=function(vCreditsData){
        self.creditsData=vCreditsData;
    };
    self.getCreditsData=function(){
        /*
        چون آبجکت می شه می خواهیم آرایه اش کنیم با حلقه پایین این کار رو می کنیم
         */
        var res = Utils.fastAjax('WorkFlowAjaxFunc', 'mission_getCredits');
        res = JSON.parse(res);

       var i=0;
       var item="";
        for(item in res){

            self.creditsCompletedField[i]=[];
            self.creditsCompletedField[i][0]=i;
            self.creditsCompletedField[i][1]=res[item][0];

            self.creditsData[i]=[];
            self.creditsData[i][0]=res[item][0];
            self.creditsData[i][1]=res[item][1];
            self.creditsData[i][2]=res[item][2];


            console.log(res[item][0]);
            i++;

        }


    };
    self.fillCompleteFieldCredits=function(){

/*
        self.creditsCompletedField[0][0]=0;
        self.creditsCompletedField[0][1]="asdfsadf";


        self.creditsCompletedField[1][0]=1;
        self.creditsCompletedField[1][1]="dovminsdf";
*/

        return self.creditsCompletedField;


    };
    self.setValuesOfSabeghEtebar=function(index){
        /*
        در رویداد onChange() وقتی دریف اعتبار تغییر کرد، این تابع فراخوانی می شود
         */
        FormView.myForm.getItemByName('Field_47').setData(self.creditsData[index][1]);
        FormView.myForm.getItemByName('Field_48').setData(self.creditsData[index][2]);

    };
    self.loadForm=function(){
        $jq("tr#etebarRow input").attr('readonly',true);
        $jq("tr#etebarRow input").css("background-color", "#e0e0e0");
       /* self.getCreditsData();*/


    };

};

