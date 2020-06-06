this.jcode = function(self){

    /*برای اضافه کردن در کد دونی*/
    self.DetailedTable = {
        tableArray:[[]],
        showMode: "edit",
        isSaved:false,
        showTable: function (stage) {
            this.showMode=stage;
            var html = Utils.fastAjax('WorkFlowAjaxFunc', 'showTable_eslaheEzafekar', {
                docId: FormView.docID, mode: "edit"
            });
            return html;
        },
        addRow:function(){
            var lengthTable = $jq('.detailedTable > tbody > tr').length;
            var newTr = '<tr class="tableRow_' + (lengthTable - 1) + '">' + '<td style="padding: 2px;border: 1px solid #ccc;">' + (lengthTable - 1) + '</td>' +
                '<td style="padding: 2px;border: 1px solid #ccc;">' + ' <input onInput="FormView.myForm.getItemByName(\'Field_0\').DetailedTable.unSaved()" type="text" name="firstName" width="30px" value=""></td>' +
                '<td style="padding: 2px;border: 1px solid #ccc;"><input onInput="FormView.myForm.getItemByName(\'Field_0\').DetailedTable.unSaved()" type="text" name="lastName" value=""></td>' +
                '<td style="padding: 2px;border: 1px solid #ccc;"><input onInput="FormView.myForm.getItemByName(\'Field_0\').DetailedTable.unSaved()" class="RavanMask" data-inputmask-regex="([0-9]){4}" dir="ltr" type="text" name="cardNumber" value=""></td>' +
                '<td style="padding: 2px;border: 1px solid #ccc;"><input onInput="FormView.myForm.getItemByName(\'Field_0\').DetailedTable.unSaved()" class="RavanMask" data-inputmask-regex="([0-9]){3}" dir="ltr" type="text" name="overworkDone" value=""></td>' +
                '<td style="padding: 2px;border: 1px solid #ccc;"><input onInput="FormView.myForm.getItemByName(\'Field_0\').DetailedTable.unSaved()" class="RavanMask" data-inputmask-regex="([0-9]){3}" dir="ltr" type="text" name="overworkConfirm" value=""></td>' +

                '<td id="tdDeleteImg" style="padding: 2px;background-color: #c5e1a5;border: 1px solid #ccc;">' + '<img onclick="FormView.myForm.getItemByName(\'Field_0\').DetailedTable.removeRow(' + (lengthTable - 1) + ')"' + 'src="gfx/toolbar/cross.png" style="cursor: pointer;"/>' + '</td>' +
                '</tr>';
            $jq('.detailedTable > tbody > tr').eq(lengthTable - 2).after(newTr);

        },
        unSaved:function(){
            this.isSaved=false;
        },
        removeRow:function(index){
            $jq('.tableRow_' + index).remove();
            this.updateFrontAfterRemove();
        },
        updateFrontAfterRemove:function(){
            var lengthTable =  $jq('.detailedTable>tbody>tr[class^=\'tab\']').length;
            for (var i = 1; i <= lengthTable ; i++) {
                $jq('.detailedTable >tbody > tr').eq(i).attr('class', 'tableRow_' + i);
                $jq('.detailedTable>tbody>tr.tableRow_' + i + '>td:first ').html(i);
                $jq('.detailedTable>tbody>tr.tableRow_' + i + '>td#tdDeleteImg>img').attr('onclick', 'FormView.myForm.getItemByName(\'Field_21\').removeRow(' + i + ')');
            }


        },
        save:function(){
            this.fillDetailedTableArray();
            if(this.checkRightList())
                this.saveToDb();

        },
        checkRightList:function(){
            return true;
        },
        saveToDb:function(){

        },
        fillDetailedTableArray:function(){
            this.tableArray=[[]];
            var length =  $jq('.detailedTable>tbody>tr[class^=\'tab\']').length;

            for (var count = 1; count <= length; count++) {
                self.tableArray[count] = [];
                self.tableArray[count][0] = $jq('.detailedTable>tbody>tr.tableRow_' + count + ' input[name=\'firstName\'] ').val();
                self.tableArray[count][1] = $jq('.detailedTable>tbody>tr.tableRow_' + count + ' input[name=\'lastName\'] ').val();
                self.tableArray[count][2] = $jq('.detailedTable>tbody>tr.tableRow_' + count + ' input[name=\'nationalCode\'] ').val();
                self.tableArray[count][3] = FormView.myForm.getItemByName('Field_21').birthdayDates[count].getDate();
                var temp;
                temp = $jq('.detailedTable>tbody>tr.tableRow_' + count + '>td.sabeghe>span ').html();
                self.tableArray[count][4] = self.alter(temp);
                temp = $jq('.detailedTable>tbody>tr.tableRow_' + count + '>td.rezvan>span ').html();
                self.tableArray[count][5] = self.alter(temp);
                temp = $jq('.detailedTable>tbody>tr.tableRow_' + count + '>td.ahval>span ').html();
                self.tableArray[count][6] = self.alter(temp);
            } /*          t = [             ["ali", "alavi", "0939845654", "1398/11/11", "مجاز", "نامشخص", "نا معتبر", 1],             ["reza", "alavi", "0939845654", "1398/11/11", "مجاز", "مجاز", "مجاز", 1],             ["Jafar", "alavi", "0939845654", "1398/11/11", "مجاز", "مجاز", "مجاز", 1],             ["Taghi", "alavi", "0939845654", "1398/11/11", "مجاز", "مجاز", "مجاز", 1],             ["Mina", "alavi", "0939845654", "1398/11/11", "مجاز", "مجاز", "مجاز", 1]];           */
            


        },

    };
    self.loadForm=function(){
        let html =self.DetailedTable.showTable();
        $jq('.detailedTableSpan').html(html);


    };

}