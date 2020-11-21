this.actJS = function(self){

    var PatternPersian = /[a-zA-Z0-9!@#$%^&*()_+-=\/\\|/?.><,`~'";:۰-۹]+/g;
    var PatternMobile = /[0][9][0-9]{9}$/;
    var PatternNumber = /[0-9]{10}$/;
    var PatternNumberNationalcode = /[0-9]{10}$/;
    var PatternNumberSCodeMeliJadid = /([0-9])([a-zA-Z])[0-9]{8}$/;
    var PatternNumberSCodeMelighadim = /[0-9]{9}$/;
    var PatternShSh = /[0-9]+$/;
    var PatternSSh22 = /([1-9])[0-9]{1}$/;
    var PatternSSh23 = /[0-9]{6}$/;
    /*var PatterBirth = /([12]\d{3}\/(0[1-9]|1[0-2])\/(0[1-9]|[12]\d|3[01]));/[0-9]{6}$/*/

    var s_MeliCode= FormView .myForm.getItemByName('Field_7').getData();
    var meliCartType = FormView .myForm.getItemByName('Field_29').getData();
    var codeTaid = FormView.myForm.getItemByName('Field_12').getData();
    var meli = FormView.myForm.getItemByName('Field_6').getData();
    var birth = FormView.myForm.getItemByName('Field_8').getData();
    var fn = FormView.docID;

    var checked = FormView.myForm.getItemByName('Field_16').checkMelliCode();
    var mobile = FormView.myForm.getItemByName('Field_9').getData();

    if(FormView.myForm.getItemByName('Field_0').getData().length==0){
        FormView.myForm.getItemByName('Field_0').showMSG('نام را وارد نمایید');
        return false;
    }
    if(PatternPersian.test(FormView.myForm.getItemByName('Field_0').getData())){
        FormView.myForm.getItemByName('Field_0').showMSG('نام را صحیح وارد نمایید');
        return false;
    }

    if(FormView.myForm.getItemByName('Field_1').getData().length==0){
        FormView.myForm.getItemByName('Field_1').showMSG('نام خانوادگی را وارد نمایید');
        return false;
    }
    if(PatternPersian.test(FormView.myForm.getItemByName('Field_1').getData())){
        FormView.myForm.getItemByName('Field_1').showMSG('نام خانوادگی را صحیح وارد نمایید');
        return false;
    }

    if(FormView.myForm.getItemByName('Field_3').getData().length ==0){
        FormView.myForm.getItemByName('Field_3').showMSG('نام پدر را وارد نمایید');
        return false;
    }
    if(PatternPersian.test(FormView.myForm.getItemByName('Field_3').getData())){
        FormView.myForm.getItemByName('Field_3').showMSG('نام پدر را صحیح وارد نمایید');
        return false;
    }

    if(FormView.myForm.getItemByName('Field_2').getData()==0){
        FormView.myForm.getItemByName('Field_2').showMSG('جنسیت را انتخاب کنید');
        return false;
    }

    if(FormView.myForm.getItemByName('Field_8').getData().length==0){
        FormView.myForm.getItemByName('Field_8').showMSG('تاریخ تولد را وارد نمایید');
        return false;
    }
    var birthYear = birth.split('/')[0];
    if(parseInt(birthYear) >= 1380){
        FormView.myForm.getItemByName('Field_8').showMSG('لطفا تاریخ تولد را بطور صحیح وارد نمایید');
        return false;
    }
    /*
    if(!PatterBirth.test(FormView.myForm.getItemByName('Field_8').getData())){
        FormView.myForm.getItemByName('Field_3').showMSG('تاریخ تولد را صحیح وارد نمایید');
        return false;
    }
    */

    if(meli.length==0){
        FormView.myForm.getItemByName('Field_6').showMSG('کد ملی را وارد نمایید');
        return false;
    }
    if(!PatternNumberNationalcode.test(meli)){
        FormView.myForm.getItemByName('Field_6').showMSG('کد ملی را صحیح وارد نمایید');
        return false;
    }
    if(!checked){
        FormView.myForm.getItemByName('Field_6').showMSG('کد ملی را صحیح وارد نمایید');
        return false;
    }

    if((meliCartType == 1 && s_MeliCode.length != 9) || (meliCartType == 0 && s_MeliCode.length != 10) ){
        FormView.myForm.getItemByName('Field_7').showMSG('سریال کد ملی را صحیح وارد نمایید');
        return false;
    }
    if(meliCartType == 0 && !PatternNumberSCodeMeliJadid.test(s_MeliCode)){
        FormView.myForm.getItemByName('Field_6').showMSG('سریال کد ملی را صحیح وارد نمایید');
        return false;
    }
    if(meliCartType == 1 && !PatternNumberSCodeMelighadim.test(s_MeliCode)){
        FormView.myForm.getItemByName('Field_6').showMSG('سریال کد ملی را صحیح وارد نمایید');
        return false;
    }

    if(FormView.myForm.getItemByName('Field_4').getData().length==0){
        FormView.myForm.getItemByName('Field_4').showMSG('شماره شناسنامه را وارد نمایید');
        return false;
    }
    if(!PatternShSh.test(FormView.myForm.getItemByName('Field_4').getData())){
        FormView.myForm.getItemByName('Field_4').showMSG('شماره شناسنامه را صحیح وارد نمایید');
        return false;
    }

    var f22 = FormView.myForm.getItemByName('Field_22').getData().replace(/[_]+/g, "");
    if(f22.length==0){
        FormView.myForm.getItemByName('Field_22').showMSG('سریال شناسنامه را وارد نمایید');
        return false;
    }
    if(f22.length!=2){
        FormView.myForm.getItemByName('Field_22').showMSG('سریال شناسنامه را صحیح وارد نمایید');
        return false;
    }
    if(!PatternSSh22.test(f22)){
        FormView.myForm.getItemByName('Field_22').showMSG('سریال شناسنامه را صحیح وارد نمایید');
        return false;
    }

    var f23 = FormView.myForm.getItemByName('Field_23').getData().replace(/[_]+/g, "");
    if(f23.length==0){
        FormView.myForm.getItemByName('Field_23').showMSG('سریال شناسنامه را وارد نمایید');
        return false;
    }
    if(f23.length!=6){
        FormView.myForm.getItemByName('Field_23').showMSG('سریال شناسنامه را صحیح وارد نمایید');
        return false;
    }
    if(!PatternSSh23.test(f23)){
        FormView.myForm.getItemByName('Field_23').showMSG('سریال شناسنامه را صحیح وارد نمایید');
        return false;
    }

    if(mobile.length==0){
        FormView.myForm.getItemByName('Field_9').showMSG('شماره موبایل را وارد نمایید');
        return false;
    }
    if (!PatternMobile.test(mobile)){
        FormView.myForm.getItemByName('Field_9').showMSG('شماره موبایل را صحیح وارد نمایید');
        return false;
    }

    if(codeTaid.length == 0){
        FormView.myForm.getItemByName('Field_12').showMSG('کد تایید پیامک را وارد نمایید');
        return false;
    }
    if(codeTaid.length != 6){
        FormView.myForm.getItemByName('Field_12').showMSG('کد تایید پیامک را صحیح وارد نمایید');
        return false;
    }
    if(!Utils.fastAjax('WorkFlowAjaxFunc', 'ftResiveSms', {codeUser:codeTaid,mobile:mobile,fn:fn}))
    {
        FormView.myForm.getItemByName('Field_12').showMSG('کد تایید برای این شماره موبایل اعتبار ندارد');
        return false;
    }
    /*
    else{
        FormView.myForm.getItemByName('Field_9').CMode.disabled="disblabed";
        FormView.myForm.getItemByName('Field_12').CMode.disabled="disblabed";
    }
    */

    if(!FormView.myForm.getItemByName('Field_30').getData())
    {
        FormView.myForm.getItemByName('Field_30').showMSG('پذیرش تعهد را انتخاب نمایید');
        return false;
    }


    if(meli!="" && birth!="")
    {
        var cheakSabt=Utils.fastAjax('WorkFlowAjaxFunc', 'ftNationalCode', {meli:meli,birth:birth});

        if(cheakSabt!="true" && cheakSabt!=true)
        {
            Utils.showModalMessage(cheakSabt);
            return false;
        }

        var cheakimage = Utils.fastAjax('WorkFlowAjaxFunc', 'ftsabtimage' ,{meli:meli,birth:birth,s_MeliCode:s_MeliCode,fn:fn});

        if(cheakimage != "true")
        {
            Utils.showModalMessage(cheakimage);
            return false;
        }

    }
    FormView.myForm.getItemByName('Field_9').CMode.disabled="disblabed";
    FormView.myForm.getItemByName('Field_12').CMode.disabled="disblabed";
    $('mobileButton').disable();
    return true;
}