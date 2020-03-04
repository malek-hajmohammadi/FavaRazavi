this.jcode = function (self) {
    self.birthdayDates = new Array();
    self.showMode="unknown";
    self.listGuest = [[] ];
    self.showTable = function (stage) {

        switch(stage){
            case "1":
                self.showMode="edit";
                break;
            case "2":
                self.showMode="readOnly";
                break;

        }
        var html = Utils.fastAjax('WorkFlowAjaxFunc', 'showGuestList_mehmansara', {
            docId: FormView.docID,mode:self.showMode
        });
        return html;
    };

    self.setDateObjectAll = function () {
        var lengthTable = $jq('.guestTable > tbody > tr').length;
        for (var i = 1; i <= lengthTable - 2; i++) {
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
            '<td style="padding: 2px;border: 1px solid #ccc;">' + ' <input type="text" name="firstName" value=""></td>' +
            '<td style="padding: 2px;border: 1px solid #ccc;"><input type="text" name="lastName" value=""></td>' +
            '<td style="padding: 2px;border: 1px solid #ccc;"><input class="RavanMask" data-inputmask-regex="([0-9]){10}" dir="ltr" type="text" name="nationalCode" value=""></td>' +
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
        var lengthTable = $jq('.guestTable > tbody > tr').length;
        for (var i = 1; i <= lengthTable - 2; i++) {
            $jq('.guestTable >tbody > tr').eq(i).attr('class', 'tableRow_' + i);
            $jq('.guestTable>tbody>tr.tableRow_' + i + '>td>div[id^=\'birthday\']').attr('id', 'birthdayDate_' + i);
            $jq('.guestTable>tbody>tr.tableRow_' + i + '>td:first ').html(i);
            $jq('.guestTable>tbody>tr.tableRow_' + i + '>td#tdDeleteImg>img').attr('onclick', 'FormView.myForm.getItemByName(\'Field_21\').removeRow(' + i + ')');
        }

    };
    self.updateObjectsAfterRemove=function(index){
        var lengthTable = $jq('.guestTable > tbody > tr').length;
        /*

        اگر خط آخر باشه لازم نیست کاری بکنیم
        */
        if ((index+1)==lengthTable)
            return;
        /*
        در غیر این صورت همه رو یکی شیفت بده بالا
         */
        for (var i = index+1; i <= lengthTable - 2; i++) {
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

    self.fillGuestList = function () {
        /*  1:barrasi 2:mojaz 3:na motaber*/
        var length = $jq('.guestTable > tbody > tr').length;
        self.listGuest = [
            []
        ];
        for (var count = 1; count <= length - 2; count++) {
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
        var length = $jq('.guestTable > tbody > tr').length;
        for (var count = 1; count <= length - 2; count++) {
            var thinking="<img  src=\"gfx/toolbar/sort.png\" />";
            $jq('.guestTable>tbody>tr.tableRow_' + count + '>td.sabeghe>span ').html(thinking);
            $jq('.guestTable>tbody>tr.tableRow_' + count + '>td.rezvan>span ').html(thinking);
            $jq('.guestTable>tbody>tr.tableRow_' + count + '>td.ahval>span ').html(thinking);
        }
    };
};