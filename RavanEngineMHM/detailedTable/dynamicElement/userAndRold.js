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