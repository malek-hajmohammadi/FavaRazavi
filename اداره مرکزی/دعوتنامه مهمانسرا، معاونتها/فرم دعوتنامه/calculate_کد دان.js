this.jcode = function (self) {
    self.birthdayDates = new Array();
    self.output;
    self.fileName;


    /*هر دو پس از ذخیره سازی جدول true می شوند*/
    self.isSaved=false;
    self.isValid=false;

    self.showMode="unknown";
    self.listGuest = [[] ];
    self.unSaved=function(){
        self.isSaved=false;
    };
    self.showTable = function (stage) {

        switch(stage){
            case "1":
                self.showMode="edit";
                break;
            case "2":
                self.showMode="readOnly";
                break;
            case "4":
                self.showMode="justDelete";
                break;

        }
        var html = Utils.fastAjax('WorkFlowAjaxFunc', 'showGuestList_mehmansara', {
            docId: FormView.docID,mode:self.showMode
        });
        return html;
    };

    self.setDateObjectAll = function () {
        var lengthTable =  $jq('.guestTable>tbody>tr[class^=\'tab\']').length;

        for (var i = 1; i <= lengthTable ; i++) {
            self.setDateobjectOne(i);
        }
    };
    self.setDateobjectOne = function (index) {
        var dateValue = $jq('#birthdayDate_' + index + ' input').val(); /*FormView.allFieldsContianer[0].birthdayDates[i] = new EditCalendar('FormOnly.allFieldsContianer[0].birthdayDates[' + i + ']', 'startDate_' + i);*/
        FormView.myForm.getItemByName('Field_21').birthdayDates[index] = new EditCalendar('FormView.myForm.getItemByName(\'Field_21\').birthdayDates[' + index + ']', 'birthdayDate_' + index);
        if (dateValue.length > 7) {
            FormView.myForm.getItemByName('Field_21').birthdayDates[index].setDate(dateValue);
            if(self.showMode=="readOnly")
                self.setReadOnly(index);
        }
    };
    self.setReadOnly=function(index){
        $jq('.guestTable>tbody>tr.tableRow_' +index + '>td>div[id^=\'birthday\']>span>input').attr('readonly',true);
        $jq('.guestTable>tbody>tr.tableRow_' + index + '>td>div[id^=\'birthday\']>span>img').attr('onClick',"");


    };
    self.setDateobjectOneForAdd=function(index){
        FormView.myForm.getItemByName('Field_21').birthdayDates[index] = new EditCalendar('FormView.myForm.getItemByName(\'Field_21\').birthdayDates[' + index + ']', 'birthdayDate_' + index);

    };
    self.addRow = function () {
        var lengthTable = $jq('.guestTable > tbody > tr').length;
        var newTr = '<tr class="tableRow_' + (lengthTable - 1) + '">' + '<td style="padding: 2px;border: 1px solid #ccc;">' + (lengthTable - 1) + '</td>' +
            '<td style="padding: 2px;border: 1px solid #ccc;">' + ' <input onInput="FormView.myForm.getItemByName(\'Field_21\').unSaved()" type="text" name="firstName" value=""></td>' +
            '<td style="padding: 2px;border: 1px solid #ccc;"><input onInput="FormView.myForm.getItemByName(\'Field_21\').unSaved()" type="text" name="lastName" value=""></td>' +
            '<td style="padding: 2px;border: 1px solid #ccc;"><input onInput="FormView.myForm.getItemByName(\'Field_21\').unSaved()" class="RavanMask" data-inputmask-regex="([0-9]){10}" dir="ltr" type="text" name="nationalCode" value=""></td>' +
            '<td style="padding: 2px;border: 1px solid #ccc;">' + '<div id="birthdayDate_' + (lengthTable - 1) + '"><input type="text" name="birthDay" value=""></div>' + '</td>' +
            '<td class="sabeghe"  style="padding: 2px;border: 1px solid #ccc;"><span style=\"color:#004ba0;font-weight: bold;\">نامشخص</span></td>' +
            '<td class="rezvan" style="padding: 2px;border: 1px solid #ccc;"><span style=\"color:#004ba0;font-weight: bold;\">نامشخص</span></td>' +
            '<td class="ahval" style="padding: 2px;border: 1px solid #ccc;"><span style=\"color:#004ba0;font-weight: bold;\">نامشخص</span></td>' +
            '<td id="tdDeleteImg" style="padding: 2px;background-color: #c5e1a5;border: 1px solid #ccc;">' + '<img onclick="FormView.myForm.getItemByName(\'Field_21\').removeRow(' + (lengthTable - 1) + ')"' + 'src="gfx/toolbar/cross.png" style="cursor: pointer;"/>' + '</td>' +
            '</tr>';
        $jq('.guestTable > tbody > tr').eq(lengthTable - 2).after(newTr);
        self.setDateobjectOneForAdd(lengthTable - 1);
    };
    self.removeRow = function (index) {
        $jq('.tableRow_' + index).remove();
        self.updateFrontAfterRemove();
        self.updateObjectsAfterRemove(index);


    };
    self.updateFrontAfterRemove=function(){

        var lengthTable =  $jq('.guestTable>tbody>tr[class^=\'tab\']').length;
        for (var i = 1; i <= lengthTable ; i++) {
            $jq('.guestTable >tbody > tr').eq(i).attr('class', 'tableRow_' + i);
            $jq('.guestTable>tbody>tr.tableRow_' + i + '>td>div[id^=\'birthday\']').attr('id', 'birthdayDate_' + i);
            $jq('.guestTable>tbody>tr.tableRow_' + i + '>td:first ').html(i);
            $jq('.guestTable>tbody>tr.tableRow_' + i + '>td#tdDeleteImg>img').attr('onclick', 'FormView.myForm.getItemByName(\'Field_21\').removeRow(' + i + ')');
        }

    };
    self.updateObjectsAfterRemove=function(index){
        var lengthTable =  $jq('.guestTable>tbody>tr[class^=\'tab\']').length;
        /*

        اگر خط آخر باشه لازم نیست کاری بکنیم
        */
        if ((index+1)==lengthTable)
            return;
        /*
        در غیر این صورت همه رو یکی شیفت بده بالا
         */
        for (var i = index+1; i <= lengthTable; i++) {
            FormView.myForm.getItemByName('Field_21').birthdayDates[i] = new EditCalendar('FormView.myForm.getItemByName(\'Field_21\').birthdayDates[' + i + ']', 'birthdayDate_' + i);
            var dateValue = FormView.myForm.getItemByName('Field_21').birthdayDates[i+1].getDate();
            FormView.myForm.getItemByName('Field_21').birthdayDates[i].setDate(dateValue);
        }

    };



    self.saveGustList = function () {
        self.fillGuestList();
        var reqType=FormView.myForm.getItemByName('Field_5').getData();
        if ((reqType=="1")||(self.checkRightList()))
        {
            self.inProcess();
            self.saveToDb();

        }

    }; /*  console.log(self.listGuest);*/
    self.saveGustListLevel4=function(){
        self.fillGuestList();
        var tem = JSON.stringify(self.listGuest);
        var result = Utils.fastAjax('WorkFlowAjaxFunc', 'saveLevel4_mehmansara', {docId:FormView.docID,listGuest:tem});
    };

    self.fillGuestList = function () {
        /*  1:barrasi 2:mojaz 3:na motaber*/

        var length =  $jq('.guestTable>tbody>tr[class^=\'tab\']').length;
        self.listGuest = [
            []
        ];
        for (var count = 1; count <= length; count++) {
            self.listGuest[count] = [];
            self.listGuest[count][0] = $jq('.guestTable>tbody>tr.tableRow_' + count + ' input[name=\'firstName\'] ').val();
            self.listGuest[count][1] = $jq('.guestTable>tbody>tr.tableRow_' + count + ' input[name=\'lastName\'] ').val();
            self.listGuest[count][2] = $jq('.guestTable>tbody>tr.tableRow_' + count + ' input[name=\'nationalCode\'] ').val();
            self.listGuest[count][3] = FormView.myForm.getItemByName('Field_21').birthdayDates[count].getDate();
            var temp;
            temp = $jq('.guestTable>tbody>tr.tableRow_' + count + '>td.sabeghe>span ').html();
            self.listGuest[count][4] = self.alter(temp);
            temp = $jq('.guestTable>tbody>tr.tableRow_' + count + '>td.rezvan>span ').html();
            self.listGuest[count][5] = self.alter(temp);
            temp = $jq('.guestTable>tbody>tr.tableRow_' + count + '>td.ahval>span ').html();
            self.listGuest[count][6] = self.alter(temp);
        } /*          t = [             ["ali", "alavi", "0939845654", "1398/11/11", "مجاز", "نامشخص", "نا معتبر", 1],             ["reza", "alavi", "0939845654", "1398/11/11", "مجاز", "مجاز", "مجاز", 1],             ["Jafar", "alavi", "0939845654", "1398/11/11", "مجاز", "مجاز", "مجاز", 1],             ["Taghi", "alavi", "0939845654", "1398/11/11", "مجاز", "مجاز", "مجاز", 1],             ["Mina", "alavi", "0939845654", "1398/11/11", "مجاز", "مجاز", "مجاز", 1]];           */
    };
    self.checkRightList = function () {
        for (var count = 1; count <= self.listGuest.length - 1; count++) {
            if (self.listGuest[count][3].length < 8) {
                Utils.showModalMessage('لطفا فيلد تاريخ در رديف ' + count + ' تصحيح كنيد');
                return false;
            }
            if (self.listGuest[count][0].length < 3) {
                Utils.showModalMessage('لطفا فيلد نام در رديف ' + count + ' تصحيح كنيد');
                return false;
            }
            if (self.listGuest[count][1].length < 3) {
                Utils.showModalMessage('لطفا فيلد نام خانوادگي در رديف ' + count + ' تصحيح كنيد');
                return false;
            }
            if (self.listGuest[count][2].length < 10) {
                Utils.showModalMessage('لطفا فيلد كد ملي در رديف ' + count + ' تصحيح كنيد');
                return false;
            }
        }
        return true;
    };
    
    self.saveToDb = function () {
        console.log(self.listGuest);
        console.log("in save:---------- ");
        var tem = JSON.stringify(self.listGuest);
        console.log(tem);
        Utils.showProgress(true);


        var gotResponse = function (o) {
            var Hadith = eval(o.responseText);
    
            Utils.showProgress(false);
            html = self.showTable();
            $jq('.tableGuest').html(html);
            self.setDateObjectAll();
            self.isSaved=true;
            self.fillGuestList();
            self.isValid=self.checkValidGuest();
    
    
        };

        var callback = {
            success: gotResponse
        };
        var url = "../Runtime/process.php";
        var param = 'module=WorkFlowAjaxFunc&action=saveAndConsiderGuest_mehmansara&docId=' + FormView.docID +'&listGuest='+tem;
        SAMA.util.Connect.asyncRequest('POST', url, callback, param);
        

    }; /*1:barrasi 2:mojaz 3:na motaber*/

    self.alter = function (temp) {
        if (temp === 'مجاز') return 2;
        if (temp === 'نامشخص') return 1;
        if (temp === 'نامعتبر') return 3;
    };
    self.inProcess = function () {

        var length =  $jq('.guestTable>tbody>tr[class^=\'tab\']').length;
        for (var count = 1; count <= length; count++) {
            var thinking="<img  src=\"gfx/toolbar/sort.png\" />";
            $jq('.guestTable>tbody>tr.tableRow_' + count + '>td.sabeghe>span ').html(thinking);
            $jq('.guestTable>tbody>tr.tableRow_' + count + '>td.rezvan>span ').html(thinking);
            $jq('.guestTable>tbody>tr.tableRow_' + count + '>td.ahval>span ').html(thinking);
        }
    };

    /*دکمه تایید در نود اول*/
    self.btnConfirm=function(){

        var vade = FormView.myForm.getItemByName('Field_3').getData();
        if (vade == 0) {
            Utils.showModalMessage('لطفا وعده پیشنهادی خود را انتخاب کنید');
            return false;
        }
        var rabetName=FormView.myForm.getItemByName('Field_1').getData().length;
        if (rabetName < 3) {
            Utils.showModalMessage('لطفا نام رابط را مشخص کنید');
            return false;
        }

        /*توضیحات برای نوع سهمیه تشریفات، اجباری است*/
        var typeSahmie=FormView.myForm.getItemByName('Field_5').getData();
        if (typeSahmie==1){
            var comment=FormView.myForm.getItemByName('Field_6').getData();
            if(comment.length<5){
                Utils.showModalMessage(' توضیحات برای نوع سهمیه تشریفات، اجباری است');
                return false;

            }
        }

        /*checking date*/
        var d1 = Main.FirstPageParameters.datetime.todayDate;
        var d2 = FormView.myForm.getItemByName('Field_4').getData();

        d1 = d1.split('/');
        d2 = d2.split('/');

        if (parseInt(d1[0]) == parseInt(d2[0]) && parseInt(d1[1]) == parseInt(d2[1]) && parseInt(d1[2]) == parseInt(d2[2]) && false) {
            Utils.showModalMessage('امکان رزرو برای امروز وجود ندارد');
            return false;
        }

        /* convert dates to days*/
        var days1 = 0;
        var m1 = parseInt(d1[1]);
        if (m1 < 7)
            days1 = (m1 - 1) * 31 + parseInt(d1[2]);
        else
            days1 = (m1 - 1) * 30 + 6 + parseInt(d1[2]);

        var days2 = 0;
        var m2 = parseInt(d2[1]);
        if (m2 < 7)
            days2 = (m2 - 1) * 31 + parseInt(d2[2]);
        else
            days2 = (m2 - 1) * 30 + 6 + parseInt(d2[2]);
        /* end convert dates to days*/

        if (d1[0] > d2[0]) {
            Utils.showModalMessage('تاریخ وارد شده قبل از تاریخ جاری میباشد');
            return false;
        }

        if (d1[0] < d2[0]) {
            days2 += 365;
        }

        var h = parseInt($('NOW-TIME-ID').innerHTML.split(':')[0]);
        if (days1 > days2) {
            Utils.showModalMessage('تاریخ وارد شده قبل از تاریخ جاری میباشد');
            return false;
        }
        if ((days1 + 1) == days2 && h > 11 && false) {
            Utils.showModalMessage('پس از ساعت 11، امکان رزرو برای فردا وجود ندارد');
            return false;
        }

        /*end checking date */
        var countGuest= $jq('.guestTable > tbody > tr').length;
        if (countGuest == 2) {
            Utils.showModalMessage('لیست افراد نمیتواند خالی باشد.');
            return false;
        }

        if(self.isSaved==false){
            Utils.showModalMessage('لطفا قبل از تایید، دکمه ذخیره و بررسی را کلیک کنید.');
            return false;
        }
        else{
            var typeSahmie=FormView.myForm.getItemByName('Field_5').getData();
            if(typeSahmie==0 && self.isValid==false){
                Utils.showModalMessage('لطفا قبل از تایید، مدعوین نامعتبر را حذف کنید.');
                return false;

            }


        }





        return true;

    };
    self.checkValidGuest=function(){
        console.log("in checkValidGuest function------------");


           for (var count = 1; count <= self.listGuest.length - 1; count++) {
               if (self.listGuest[count][4] == 3)
                   return false;
               if (self.listGuest[count][5] == 3)
                   return false;
               if (self.listGuest[count][6] == 3)
                   return false;
           }
           return true;


    };

   /*زمانیکه دکمه استخراج از فایل اکسل را زد تابع زیر فراخوانی می شود*/
    self.exportExcelToTable=function(){


        let fileName= FormView.myForm.getItemByName('Field_9').getData();
        if(fileName==""){
            Utils.showModalMessage('فایلی پیوست نشده است');
            return;
        }
        if(fileName==self.fileName){
            Utils.showModalMessage('فایل پیوست یکبار به لیست اضافه شده است');
            return;
        }
        self.fileName=fileName;


        var gotResponse = function (o) {


            Utils.showProgress(false);
            let listExcel=JSON.parse(o.responseText);


/* به جای json.parse از eval استفاده کنیم که اگر مشکلی در جیسون بود این تابع رفعش می کنه*/

            var i=0;
            var item="";

            var lengthTable = $jq('.guestTable > tbody > tr').length;
            let newTrId=lengthTable-1;




            self.output=listExcel;
            for(let i=0;i<listExcel.length;i++){
                item=listExcel[i];




                let firstName=item['name'];
                let lastName=item['family'];
                let birthday=item['birthDate'];
                let nationalCode=item['nationalCode'];
                self.addRow(birthday);
                $jq('.guestTable>tbody>tr.tableRow_' + newTrId + ' input[name=\'firstName\'] ').val(firstName);
                $jq('.guestTable>tbody>tr.tableRow_' + newTrId + ' input[name=\'lastName\'] ').val(lastName);
                $jq('.guestTable>tbody>tr.tableRow_' + newTrId + ' input[name=\'nationalCode\'] ').val(nationalCode);

                var lengthTable = $jq('.guestTable > tbody > tr').length;
                self.birthdayDates[lengthTable-2].setDate(birthday);


                newTrId++;

            }
            self.fillGuestList();


        };


        var callback = {
            success: gotResponse
        };
        var url = "../Runtime/process.php";
        var param = 'module=WorkFlowAjaxFunc&action=extractExcel&docId=' + FormView.docID;
        SAMA.util.Connect.asyncRequest('POST', url, callback, param);
    };

};