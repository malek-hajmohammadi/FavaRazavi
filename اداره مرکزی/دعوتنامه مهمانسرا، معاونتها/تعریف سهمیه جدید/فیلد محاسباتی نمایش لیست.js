this.jcode = function (self) {
    self.listItems = [[]];

    /*
       چون در رويدادهاي آبجكتهاي اتوماسيون يك نام مي گذارد ، اين نام بايد
       يونيك باشد بنابراين براي هركدام يك آرايه تعريف مي كنيم
    */
    self.PerRoles = [];
    self.StartDates = [];
    self.EndDates = [];

    self.showTable = function () {
        var html = Utils.fastAjax('WorkFlowAjaxFunc', 'guesthouseAccessListNew');
        return html;
    };
    self.setDateObjectAll = function () {
        var lengthTable = $jq('.itemsTable > tbody > tr[id^=dataTr]').length;
        for (var i = 0; i < lengthTable; i++) {
            self.setDateobjectOne(i);
        }
    };

    self.setDateobjectOne = function (index) {


        var user = $jq('#userTD_' + index).attr('data-id').split(',');

        FormOnly.allFieldsContianer[0].PerRoles[index] = new Per_Role('FormOnly.allFieldsContianer[0].PerRoles[' + index + ']', 'userTD_' + index, Main.getActiveCurrentSectriateUser());
        FormOnly.allFieldsContianer[0].PerRoles[index].setData(user[0], user[1]);


        var valueOfStartDate= $jq('#startDate_' + 0 ).html();
        FormOnly.allFieldsContianer[0].StartDates[index] = new EditCalendar('FormOnly.allFieldsContianer[0].StartDates[' + index + ']', 'startDate_' + index);
        if (valueOfStartDate.length > 7) {
            FormOnly.allFieldsContianer[0].StartDates[index].setDate(valueOfStartDate);
        }


        var valueOfEndDate= $jq('#endDate_' + 0 ).html();
        FormOnly.allFieldsContianer[0].EndDates[index] = new EditCalendar('FormOnly.allFieldsContianer[0].EndDates[' + index + ']', 'endDate_' + index);
        if (valueOfEndDate.length > 7) {
            FormOnly.allFieldsContianer[0].EndDates[index].setDate(valueOfEndDate);
        }

    };

    self.setDateobjectOneForAdd = function (index) {


        FormOnly.allFieldsContianer[0].PerRoles[index] = new Per_Role('FormOnly.allFieldsContianer[0].PerRoles[' + index + ']', 'userTD_' + index, Main.getActiveCurrentSectriateUser());

        FormOnly.allFieldsContianer[0].StartDates[index] = new EditCalendar('FormOnly.allFieldsContianer[0].StartDates[' + index + ']', 'startDate_' + index);

        FormOnly.allFieldsContianer[0].EndDates[index] = new EditCalendar('FormOnly.allFieldsContianer[0].EndDates[' + index + ']', 'endDate_' + index);


    };

    self.addRow = function () {
        var itemIndex = $jq('.itemsTable > tbody > tr[id^=dataTr]').length;
        htmlTr = '<tr  id="dataTr_' + itemIndex + '">\n' +
            '        <td style="padding: 2px;border: 1px solid #ccc;" >' + '</td>\n' +
            '        <td style="padding: 2px;border: 1px solid #ccc;" id="userTD_' + itemIndex + '" data-id=""></td>\n' +
            '        <td style="padding: 2px;border: 1px solid #ccc;"><div id="startDate_' + itemIndex + '"></div></td>\n' +
            '        <td style="padding: 2px;border: 1px solid #ccc;"><div id="endDate_' + itemIndex + '"></div></td>\n' + ' ' +
            '        <td style="padding: 2px;border: 1px solid #ccc;" >\n' + ' <input onInput="FormOnly.allFieldsContianer[0].changeSum(' + itemIndex + ')" class="f-input" type="text" id="shareCount_' + itemIndex + '" value="" style="width: 40px;font-size: 16px;" />\n' + ' </td>\n' +
            '        <td style="padding: 2px;border: 1px solid #ccc;" >\n' + ' <input onInput="FormOnly.allFieldsContianer[0].changeSumTashrif('+ itemIndex + ')" class="f-input" type="text" id="shareCountTashrif_' + itemIndex + '" value="" style="width: 40px;font-size: 16px;" />\n' + ' </td>\n' +
            '        <td  style="padding: 2px;border: 1px solid #ccc;" >\n' + ' <input readonly class="f-input" type="text" id="shareCountSum_' + itemIndex + '" value="" style="width: 40px;font-size: 16px;background-color: gainsboro;" />\n' + ' </td>\n' +
            '        <td  style="padding: 2px;border: 1px solid #ccc;" >\n' + ' <input  readonly class="f-input" type="text" id="shareCountTashrifSum_' + itemIndex + '" value="" style="width: 40px;font-size: 16px;background-color: gainsboro;" />\n' + ' </td>\n' +
            '        <td class="remove" style="padding: 2px;border: 1px solid #ccc;"><img onclick="FormOnly.allFieldsContianer[0].removeRow(' + itemIndex + ')" src="gfx/toolbar/cross.png" style="cursor: pointer;" /></td>\n' +
            '    </tr>';
        $jq('.itemsTable > tbody').append(htmlTr);
        $jq('.itemsTable > tbody > tr[id=dataTr_' + itemIndex + '] > td:first-child').html(itemIndex + 1);          /*initialize*/
        $jq('#shareCount_' + itemIndex).val(0);
        $jq('#shareCountTashrif_' + itemIndex).val(0);
        $jq('#shareCountSum_' + itemIndex).val(0);
        $jq('#shareCountTashrifSum_' + itemIndex).val(0);

        self.setDateobjectOneForAdd(itemIndex);



    };
    self.removeRow = function (index) {

        $jq('#dataTr_' + index).remove();
        self.updateFrontAfterRemove();
        self.updateObjectsAfterRemove(index);
    };
    self.updateFrontAfterRemove=function(){
        var lengthTable = $jq('.itemsTable > tbody >tr[id^=dataTr_] ').length;
        for (var i = 0; i < lengthTable; i++) {

            $jq('.itemsTable>tbody>tr[id^=dataTr_]').eq(i).attr('id', 'dataTr_' + i); /*تصحیح آی دی trها*/

            $jq('.itemsTable>tbody>tr#dataTr_' + i + '>td:first ').html(i + 1); /*تصحیح شماره ردیف ها*/

            $jq('.itemsTable>tbody>tr#dataTr_' + i + '>td.remove>img').attr('onclick', 'FormOnly.allFieldsContianer[0].removeRow(' + i + ')'); /*تصحیح آی دی برای حذف*/

            $jq('.itemsTable>tbody>tr#dataTr_'+i+' input[id^=shareCount_]').attr('id','shareCount_'+i);/*تصحیح آی دی رویداد برای تغییر changeSum*/
            $jq('.itemsTable>tbody>tr#dataTr_'+i+' input[id^=shareCountTashrif_]').attr('id','shareCountTashrif_'+i);/*تصحیح آی دی رویداد برای تغییر changeSum*/
            $jq('.itemsTable>tbody>tr#dataTr_'+i+' input[id^=shareCount_]').attr('onInput','FormOnly.allFieldsContianer[0].changeSum('+i+')');/*تصحیح آی دی رویداد برای تغییر changeSum*/
            $jq('.itemsTable>tbody>tr#dataTr_'+i+' input[id^=shareCountTashrif_]').attr('onInput','FormOnly.allFieldsContianer[0].changeSumTashrif('+i+')');/*تصحیح آی دی رویداد برای تغییر changeSumTashrif*/
            $jq('.itemsTable>tbody>tr#dataTr_'+i+' input[id^=shareCountSum_]').attr('id','shareCountSum_'+i);/*تصحیح آی دی رویداد برای تغییر changeSum*/
            $jq('.itemsTable>tbody>tr#dataTr_'+i+' input[id^=shareCountTashrifSum_]').attr('id','shareCountTashrifSum_'+i);/*تصحیح آی دی رویداد برای تغییر changeSum*/

            $jq('.itemsTable>tbody>tr#dataTr_'+i+' td[id^=\'userTD_\']').attr('id','userTD_'+i);
            $jq('.itemsTable>tbody>tr#dataTr_' + i + '>td>div[id^=\'startDate_\']').attr('id', 'startDate_' + i);
            $jq('.itemsTable>tbody>tr#dataTr_' + i + '>td>div[id^=\'endDate_\']').attr('id', 'endDate_' + i);


        }
    };
    self.updateObjectsAfterRemove=function(index) {
        var lengthTable = $jq('.itemsTable > tbody >tr[id^=dataTr_] ').length;
        /*
        اگر خط آخر باشه لازم نیست کاری بکنیم
         */
        if (index==lengthTable)
            return;
        /*
        در غیر این صورت همه رو یکی شیفت بده بالا
         */
        for(var i=index;i<lengthTable;i++)
        {
            FormOnly.allFieldsContianer[0].PerRoles[i] = new Per_Role('FormOnly.allFieldsContianer[0].PerRoles[' + i + ']', 'userTD_' +i, Main.getActiveCurrentSectriateUser());;
            var user=FormOnly.allFieldsContianer[0].PerRoles[i+1].getData().split(',');
            FormOnly.allFieldsContianer[0].PerRoles[i].setData(user[0],user[1]);

            var value =  FormOnly.allFieldsContianer[0].StartDates[i+1].getDate();
            FormOnly.allFieldsContianer[0].StartDates[i] = new EditCalendar('FormOnly.allFieldsContianer[0].StartDates[' + i + ']', 'startDate_' + i);
            FormOnly.allFieldsContianer[0].StartDates[i].setDate(value);

            value =  FormOnly.allFieldsContianer[0].EndDates[i+1].getDate();
            FormOnly.allFieldsContianer[0].EndDates[i] = new EditCalendar('FormOnly.allFieldsContianer[0].EndDates[' + i + ']', 'endDate_' + i);
            FormOnly.allFieldsContianer[0].EndDates[i].setDate(value);

            /*
            if (value.length > 7)
                   FormOnly.allFieldsContianer[0].StartDates[index].setDate(value);
             */





        }


        /*
        باید ببینم کدوم خونه حذف شده
        اگر آخری باشه لازم نیست کاری انجام بشه
         */
       /*
        self.setDateobjectOne(i,'e');

        */



        /*  $jq('.itemsTable>tbody>tr#dataTr_'+i+' input[id*=PER]').attr('id','userTD_'+i+'_PER_INPUT');*/
        /*تصحیح آی دی رویداد برای تغییر changeSumTashrif*/
        /*   $jq('.itemsTable>tbody>tr#dataTr_'+i+' input[id*=ROLE]').attr('id','userTD_'+i+'_ROLE_INPUT');*/
        /*تصحیح آی دی رویداد برای تغییر changeSumTashrif*/




        /* ست کردن آرایه ها*/

        /*self.fillListItems(); *//* براي اينكه خونه حذف شده رو بياد از آرايه بردارد*/
    };

    self.saveList = function () {
        self.fillListItems();
        console.log(self.listItems);

        if (self.checkListItems()) {
           alert("it's ok!");
           console.log('in save-----------');
           console.log(self.listItems);
            /*
            self.inProcess();
            */
            self.saveToDb();


        }


        
    };
    self.fillListItems = function () {
        var length = $jq('.itemsTable > tbody > tr[id^=dataTr_').length;
        self.listItems = [[]];
        for (var count = 0; count < length ; count++) {
            self.listItems[count] = [];
            self.listItems[count][0] = FormOnly.allFieldsContianer[0].PerRoles[count].getData();

            self.listItems[count][1] = FormOnly.allFieldsContianer[0].StartDates[count].getDate();
            self.listItems[count][2] = FormOnly.allFieldsContianer[0].EndDates[count].getDate();


            self.listItems[count][3] = $jq('.itemsTable>tbody>tr#dataTr_' + count + ' input[id=\'shareCount_' + count + '\'] ').val();
            self.listItems[count][4] = $jq('.itemsTable>tbody>tr#dataTr_' + count + ' input[id=\'shareCountTashrif_' + count + '\'] ').val();


        }

    };
    self.checkListItems = function () {
        var length = $jq('.itemsTable > tbody > tr[id^=dataTr_').length;
        for (var i = 0; i < length ; i++) {

            var user=self.listItems[i][0];
            if (user=="0,-1"){
                Utils.showModalMessage('لطفا فيلد کاربر در رديف ' + (i+1) + ' تصحيح كنيد');
                return false;

            }

            if (self.listItems[i][1].length < 8) {
                Utils.showModalMessage('لطفا فيلد تاريخ در رديف ' + (i+1) + ' تصحيح كنيد');
                return false;
            }
            if (self.listItems[i][2].length < 8) {
                Utils.showModalMessage('لطفا فيلد تاريخ در رديف ' + (i+1) + ' تصحيح كنيد');
                return false;
            }

            var diffDate = self.checkDates(self.listItems[i][1], self.listItems[i][2]);
            diffDate = diffDate + 1;
            if (diffDate <= 0) {
                Utils.showModalMessage('در انتخاب تاريخ شروع و پايان سطر ' + (i+1) + ' دقت كنيد!');
                return false;
            }

            /* چک کاربر تکراری*/
            for(var j=i+1;j<length;j++){
                if(user==self.listItems[j][0]){
                    Utils.showModalMessage('کاربر ردیف ('+(i+1)+') با کاربر ردیف ('+(j+1)+') هم نام هستند');
                    return false;

                }
            }

        }
        return true;

    };
    self.saveToDb = function () {
        if (self.listItems.length > 0) {
            var res = Utils.fastAjax('WorkFlowAjaxFunc', 'guesthouseAccessSaveNew', {listItems: self.listItems});

                Utils.showModalMessage(res.msg);


        }

    };
    self.inProcess = function () {
    };

    /***********extra functions******************/
    self.changeSum = function ( id) {
        var startDate = FormOnly.allFieldsContianer[0].StartDates[id].getDate();
        var finishDate = FormOnly.allFieldsContianer[0].EndDates[id].getDate();
        if (startDate.length < 8 || finishDate.length < 8) {
            Utils.showModalMessage('بازه هاي تاريخي سطر ' + (id) + ' ناقص ميباشد..');
            return;
        }
        var amountUsual = parseInt($jq('#shareCount_' + id).val());
        var diffDate = self.checkDates(startDate, finishDate);
        diffDate = diffDate + 1;
        if (diffDate <= 0) {
            Utils.showModalMessage('در انتخاب تاريخ شروع و پايان سطر ' + (id) + ' دقت كنيد!');
            return;
        }
        var valSum = diffDate * amountUsual;
        $jq('#shareCountSum_' + id).val(valSum);
    };     /* OK */
    self.changeSumTashrif = function ( id) {
        var startDate = FormOnly.allFieldsContianer[0].StartDates[id].getDate();
        var finishDate = FormOnly.allFieldsContianer[0].EndDates[id].getDate();
        if (startDate.length < 8 || finishDate.length < 8) {
            Utils.showModalMessage('بازه هاي تاريخي سطر ' + (id) + ' ناقص ميباشد.');
            return;
        }
        var amountUsual = parseInt($jq('#shareCountTashrif_' + id).val());
        var diffDate = self.checkDates(startDate, finishDate);
        diffDate = diffDate + 1;
        if (diffDate <= 0) {
            Utils.showModalMessage('در انتخاب تاريخ شروع و پايان سطر ' + (id) + ' دقت كنيد!');
            return;
        }
        var valSum = diffDate * amountUsual;
        $jq('#shareCountTashrifSum_' + id).val(valSum);
    };     /* not OK */
    self.checkDates = function (stJalaliFirst, stJalaliSecond) {
        var Diff = Utils.fastAjax('NForms', 'dataDiff', {sdate: stJalaliFirst, edate: stJalaliSecond}, true);
        return Diff;
    };
    self.initializeSum=function(){

        var lengthTable = $jq('.itemsTable > tbody > tr[id^=dataTr]').length;
        for (var i = 0; i < lengthTable; i++) {
            self.changeSum(i);
            self.changeSumTashrif(i);
        }


    };

};

/**********************/
/**********************/
this.jcode = function (self) {     /*OK*/
    self.PerRoles = [];
    self.StartDates = [];
    self.EndDates = [];
    self.maxRowID = 0;


    self.getAccessList = function (data) {
        var res = '';
        if (data && typeof data == 'string') res = data; else res = Utils.fastAjax('WorkFlowAjaxFunc', 'guesthouseAccessListNew');
        $jq('#listContainer').html(res);
        var accessCount = $jq('.accessTable > tbody > tr.data_tr').length;
        for (var i = 1; i <= accessCount; i++) {
            FormOnly.allFieldsContianer[0].PerRoles[i] = new Per_Role('FormOnly.allFieldsContianer[0].PerRoles[' + i + ']', 'userTD_' + i, Main.getActiveCurrentSectriateUser());
            var user = $jq('#userTD_' + i).attr('data-id').split(',');
            FormOnly.allFieldsContianer[0].PerRoles[i].setData(user[0], user[1]);
            var dateValue = $jq('#startDate_' + i).html();
            FormOnly.allFieldsContianer[0].StartDates[i] = new EditCalendar('FormOnly.allFieldsContianer[0].StartDates[' + i + ']', 'startDate_' + i);
            if (dateValue.length > 7) {
                FormOnly.allFieldsContianer[0].StartDates[i].setDate(dateValue);
            }
            var dateValue = $jq('#endDate_' + i).html();
            FormOnly.allFieldsContianer[0].EndDates[i] = new EditCalendar('FormOnly.allFieldsContianer[0].EndDates[' + i + ']', 'endDate_' + i);
            if (dateValue.length > 7) {
                FormOnly.allFieldsContianer[0].EndDates[i].setDate(dateValue);
            }
        }
        self.maxRowID = accessCount - 1;
    };
    self.saveAccesses = function (action) {
        var accessRows = $jq('.accessTable > tbody > tr.data_tr');
        var userList = [];
        for (var i = 0; i < accessRows.length; i++) {
            var index = accessRows[i].id.split('_')[1];
            var userItem = {};
            var user = FormOnly.allFieldsContianer[0].PerRoles[index].getData();
            var shareCount = $jq('#shareCount_' + index).val();
            var shareCountTashrif = $jq('#shareCountTashrif_' + index).val();
            if (user == "" || user == "0,-1" || parseInt(shareCount) == 0) {
                Utils.showModalMessage('اطلاعات سطر ' + (parseInt(index) + 1) + ' ناقص ميباشد.');
                return;
            }
            userItem['user'] = user;
            userItem['shareCount'] = shareCount;
            userItem['shareCountTashrif'] = shareCountTashrif;
            userItem['startDate'] = FormOnly.allFieldsContianer[0].StartDates[index].getDate();
            userItem['endDate'] = FormOnly.allFieldsContianer[0].EndDates[index].getDate();             /*             if (userItem['startDate'].length < 8 || userItem['endDate'].length < 8) {                 Utils.showModalMessage('بازه هاي تاريخي سطر ' + (index) + ' ناقص ميباشد.');                 return;             }             if (self.checkDates(userItem['startDate'], userItem['endDate']) < 0) {                 Utils.showModalMessage('در انتخاب تاريخ شروع و پايان سطر ' + (index) + ' دقت كنيد!');                 return;             }                           */
            userList[index] = userItem;
        }
        console.log("userListLength" + userItem.length);
        if (userList.length > 0) {
            var res = Utils.fastAjax('WorkFlowAjaxFunc', 'guesthouseAccessSaveNew', {userList: userList});
            if (res.status = 'ERROR') Utils.showModalMessage(res.msg); else if (res.status = 'SUCCESS') {
                self.getAccessList(res.data);
                Utils.showModalMessage(res.msg);
            }
        }

    };

    self.removeRow = function (id) {
        $jq('#accessRow_' + id).remove();

        var lengthTable = $jq('.accessTable > tbody > tr.data_tr').length;
        for (var i = 0; i < lengthTable; i++) {
            $jq('.accessTable >tbody > tr.data_tr').eq(i).attr('id', 'accessRow_' + i); /*تصحیح آی دی trها*/
            $jq('.accessTable>tbody>tr#accessRow_' + i + '>td:first ').html(i + 1); /*تصحیح شماره ردیف ها*/
            $jq('.accessTable>tbody>tr#accessRow_' + i + '>td.remove>img').attr('onclick', 'FormOnly.allFieldsContianer[0].removeRow(' + i + ')'); /*تصحیح آی دی برای حذف*/
            $jq('.accessTable>tbody>tr#accessRow_'+i+' input[id^=shareCount_]').attr('id','shareCount_'+i);/*تصحیح آی دی رویداد برای تغییر changeSum*/
            $jq('.accessTable>tbody>tr#accessRow_'+i+' input[id^=shareCountTashrif_]').attr('id','shareCountTashrif_'+i);/*تصحیح آی دی رویداد برای تغییر changeSum*/
            $jq('.accessTable>tbody>tr#accessRow_'+i+' input[id^=shareCount_]').attr('onInput','FormOnly.allFieldsContianer[0].changeSum(this.value,'+i+')');/*تصحیح آی دی رویداد برای تغییر changeSum*/
            $jq('.accessTable>tbody>tr#accessRow_'+i+' input[id^=shareCountTashrif_]').attr('onInput','FormOnly.allFieldsContianer[0].changeSumTashrif(this.value,'+i+')');/*تصحیح آی دی رویداد برای تغییر changeSumTashrif*/
            /* ست کردن آرایه ها*/

        }


        FormOnly.allFieldsContianer[0].PerRoles[self.maxRowID] = null;
        FormOnly.allFieldsContianer[0].StartDates[self.maxRowID] = null;
        FormOnly.allFieldsContianer[0].EndDates[self.maxRowID] = null;

        self.maxRowID--;
    };
    self.addAccessRow = function () {
        var newRowID = self.maxRowID + 1;
        html = '<tr class="data_tr" id="accessRow_' + newRowID + '">\n' +
            '        <td style="padding: 2px;border: 1px solid #ccc;" >' + '</td>\n' +
            '        <td style="padding: 2px;border: 1px solid #ccc;" id="userTD_' + newRowID + '" data-id="\'.$row[\'UserID\'].\',\'.$row[\'RowID\'].\'"></td>\n' +
            '        <td style="padding: 2px;border: 1px solid #ccc;"><div id="startDate_' + newRowID + '"></div></td>\n' +
            '        <td style="padding: 2px;border: 1px solid #ccc;"><div id="endDate_' + newRowID + '"></div></td>\n' + ' ' +
            '        <td style="padding: 2px;border: 1px solid #ccc;" >\n' + ' <input onInput="FormOnly.allFieldsContianer[0].changeSum(this.value,' + newRowID + ')" class="f-input" type="text" id="shareCount_' + newRowID + '" value="" style="width: 50px;font-size: 16px;" />\n' + ' </td>\n' +
            '        <td style="padding: 2px;border: 1px solid #ccc;" >\n' + ' <input onInput="FormOnly.allFieldsContianer[0].changeSumTashrif(this.value,' + newRowID + ')" class="f-input" type="text" id="shareCountTashrif_' + newRowID + '" value="" style="width: 50px;font-size: 16px;" />\n' + ' </td>\n' +

            '        <td style="padding: 2px;border: 1px solid #ccc;" >\n' + ' <input class="f-input" type="text" id="shareCountSum_' + newRowID + '" value="" style="width: 50px;font-size: 16px;" />\n' + ' </td>\n' +
            '        <td style="padding: 2px;border: 1px solid #ccc;" >\n' + ' <input class="f-input" type="text" id="shareCountTashrifSum_' + newRowID + '" value="" style="width: 50px;font-size: 16px;" />\n' + ' </td>\n' +

            '        <td class="remove" style="padding: 2px;border: 1px solid #ccc;"><img onclick="FormOnly.allFieldsContianer[0].removeRow(' + newRowID + ')" src="gfx/toolbar/cross.png" style="cursor: pointer;" /></td>\n' + '    </tr>';

        $jq('.accessTable > tbody').append(html);
        $jq('.accessTable > tbody > tr[id=accessRow_' + newRowID + '] > td:first-child').html(newRowID + 1);
        $jq('#shareCount_' + newRowID).val(0);
        $jq('#shareCountTashrif_' + newRowID).val(0);
        $jq('#shareCountSum_' + newRowID).val(0);
        $jq('#shareCountTashrifSum_' + newRowID).val(0);

        FormOnly.allFieldsContianer[0].PerRoles[newRowID] = new Per_Role('FormOnly.allFieldsContianer[0].PerRoles[' + newRowID + ']', 'userTD_' + newRowID, Main.getActiveCurrentSectriateUser());
        FormOnly.allFieldsContianer[0].StartDates[newRowID] = new EditCalendar('FormOnly.allFieldsContianer[0].StartDates[' + newRowID + ']', 'startDate_' + newRowID);
        FormOnly.allFieldsContianer[0].EndDates[newRowID] = new EditCalendar('FormOnly.allFieldsContianer[0].EndDates[' + newRowID + ']', 'endDate_' + newRowID);
        self.maxRowID = newRowID;
    };

};