/*--در فیلد محاسباتی--*/
this.jcode = function (self) {

    self.changeSum=function(value,id){

        var startDate=FormOnly.allFieldsContianer[0].StartDates[id].getDate();
        var finishDate=FormOnly.allFieldsContianer[0].EndDates[id].getDate();

        if(startDate.length<8 || finishDate.length<8)
        {
            Utils.showModalMessage('بازه هاي تاريخي سطر ' + (id) + ' ناقص ميباشد.');
            return;
        }
        var amountUsual=parseInt($jq('#shareCount_'+id).val());
        var diffDate=self.checkDates(startDate,finishDate);
        diffDate=diffDate+1;
        if(diffDate<=0){
            Utils.showModalMessage('در انتخاب تاریخ شروع و پایان سطر ' + (id) + ' دقت کنید!');
            return;
        }
        var valSum=diffDate*amountUsual;
        $jq('#shareCountSum_'+id).val(valSum);

    };


    self.changeSumTashrif=function(value,id){

        var startDate=FormOnly.allFieldsContianer[0].StartDates[id].getDate();
        var finishDate=FormOnly.allFieldsContianer[0].EndDates[id].getDate();

        if(startDate.length<8 || finishDate.length<8)
        {
            Utils.showModalMessage('بازه هاي تاريخي سطر ' + (id) + ' ناقص ميباشد.');
            return;
        }
        var amountUsual=parseInt($jq('#shareCountTashrif_'+id).val());
        var diffDate=self.checkDates(startDate,finishDate);
        diffDate=diffDate+1;
        if(diffDate<=0){
            Utils.showModalMessage('در انتخاب تاریخ شروع و پایان سطر ' + (id) + ' دقت کنید!');
            return;
        }
        var valSum=diffDate*amountUsual;
        $jq('#shareCountTashrifSum_'+id).val(valSum);
    };

    self.getAccessList = function (data) {
        var res = '';
        if (data && typeof data == 'string') res = data; else res = Utils.fastAjax('WorkFlowAjaxFunc', 'guesthouseAccessList');
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
        var userList = new Array();
        for (var i = 0; i < accessRows.length; i++) {
            var index = accessRows[i].id.split('_')[1];
            var userItem = new Object();
            var user = FormOnly.allFieldsContianer[0].PerRoles[index].getData();
            var shareCount = $jq('#shareCount_' + index).val();
            var shareCountTashrif = $jq('#shareCountTashrif_' + index).val();

            if (user == "" || user == "0,-1" || parseInt(shareCount) == 0) {
                Utils.showModalMessage('اطلاعات سطر ' + (index) + ' ناقص ميباشد.');
                return;
            }
            userItem['user'] = user;
            userItem['shareCount'] = shareCount;
            userItem['shareCountTashrif'] = shareCountTashrif;

            userItem['startDate'] = FormOnly.allFieldsContianer[0].StartDates[index].getDate();
            userItem['endDate'] = FormOnly.allFieldsContianer[0].EndDates[index].getDate();

            if(userItem['startDate'].length<8 || userItem['endDate'].length<8)
            {
                Utils.showModalMessage('بازه هاي تاريخي سطر ' + (index) + ' ناقص ميباشد.');
                return;
            }
            if (self.checkDates(userItem['startDate'],userItem['endDate'])<0) {
                Utils.showModalMessage('در انتخاب تاریخ شروع و پایان سطر ' + (index) + ' دقت کنید!');
                return;
            }

            userList[index - 1] = userItem;
        }
        if (userList.length > 0) {
            var res = Utils.fastAjax('WorkFlowAjaxFunc', 'guesthouseAccessSave', {userList: userList});
            if (res.status = 'ERROR') Utils.showModalMessage(res.msg); else if (res.status = 'SUCCESS') {
                self.getAccessList(res.data);
                Utils.showModalMessage(res.msg);
            }
        }
    };
    self.checkDates=function(stJalaliFirst, stJalaliSecond){

        var Diff = Utils.fastAjax('NForms', 'dataDiff', {
            sdate: stJalaliFirst,
            edate: stJalaliSecond
        }, true);
        return Diff;

    };
    self.removeRow = function (id) {
        $jq('#accessRow_' + id).remove();
    };
    self.addAccessRow = function () {
        var newRowID = self.maxRowID + 1;
        var lastRow = $jq('.accessTable > tbody > tr:last-child');
        var lastRowID = lastRow.attr('id').split('_')[1];
        var html = document.getElementById(lastRow.attr('id')).outerHTML;
        while (html.indexOf('_' + lastRowID + '"') > 0) {
            html = html.replace('_' + lastRowID + '"', '_' + newRowID + '"');
        }
        html = html.replace('removeRow(' + lastRowID + ')', 'removeRow(' + newRowID + ')');
        $jq('.accessTable > tbody').append(html);
        $jq('.accessTable > tbody > tr[id=accessRow_' + newRowID + '] > td:first-child').html(newRowID);
        $jq('#shareCount_' + newRowID).val(5);
        FormOnly.allFieldsContianer[0].PerRoles[newRowID] = new Per_Role('FormOnly.allFieldsContianer[0].PerRoles[' + newRowID + ']', 'userTD_' + newRowID, Main.getActiveCurrentSectriateUser());
        FormOnly.allFieldsContianer[0].StartDates[newRowID] = new EditCalendar('FormOnly.allFieldsContianer[0].StartDates[' + newRowID + ']', 'startDate_' + newRowID);
        FormOnly.allFieldsContianer[0].EndDates[newRowID] = new EditCalendar('FormOnly.allFieldsContianer[0].EndDates[' + newRowID + ']', 'endDate_' + newRowID);
        self.maxRowID = newRowID;
    };
    self.PerRoles = new Array();
    self.StartDates = new Array();
    self.EndDates = new Array();
    self.maxRowID = 0;


};

/*--لود فرم---*/
listener = function (event) {
    FormOnly.allFieldsContianer[0].getAccessList()
}