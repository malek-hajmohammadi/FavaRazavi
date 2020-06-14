this.jcode = function(self){

    /*برای اضافه کردن در کد دونی*/
    self.DetailedTable = {
        tableArray:[[]],
        showMode: "edit",
        isSaved:false,
        showTable: function () {
            var html = Utils.fastAjax('WorkFlowAjaxFunc', 'showTable_eslaheEzafekar', {
                docId: FormView.docID, mode: this.showMode
            });
            return html;
        },
        addRow:function(){
            var lengthTable = $jq('.detailedTable > tbody > tr').length;
            var newTr = '<tr class="tableRow_' + (lengthTable - 1) + '">' + '<td style="padding: 2px;border: 1px solid #ccc;">' + (lengthTable - 1) + '</td>' +
                '<td style="padding: 2px;border: 1px solid #ccc;"><input onInput="FormView.myForm.getItemByName(\'Field_0\').DetailedTable.unSaved()" type="text" name="firstName" width="30px" value=""></td>' +
                '<td style="padding: 2px;border: 1px solid #ccc;"><input onInput="FormView.myForm.getItemByName(\'Field_0\').DetailedTable.unSaved()" type="text" name="lastName" value=""></td>' +
                '<td style="padding: 2px;border: 1px solid #ccc;"><input onInput="FormView.myForm.getItemByName(\'Field_0\').DetailedTable.unSaved()"  dir="ltr" type="number" name="cardNumber" value=""></td>' +
                '<td style="padding: 2px;border: 1px solid #ccc;"><input onInput="FormView.myForm.getItemByName(\'Field_0\').DetailedTable.unSaved()"  dir="ltr" type="number" name="overworkDone" value=""></td>' +
                '<td style="padding: 2px;border: 1px solid #ccc;"><input onInput="FormView.myForm.getItemByName(\'Field_0\').DetailedTable.unSaved()"  dir="ltr" type="number" name="overworkConfirm" value=""></td>' +

                '<td id="tdDeleteImg" style="padding: 2px;background-color: #c5e1a5; border: 1px solid #ccc;">' + '<img style="cursor: pointer" onclick="FormView.myForm.getItemByName(\'Field_0\').DetailedTable.removeRow(' + (lengthTable - 1) + ')"' + 'src="gfx/toolbar/cross.png" />' + '</td>' +
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
                $jq('.detailedTable>tbody>tr.tableRow_' + i + '>td#tdDeleteImg>img').attr('onclick', 'FormView.myForm.getItemByName(\'Field_0\').DetailedTable.removeRow(' + i + ')');
            }


        },
        saveList:function(){
            this.fillDetailedTableArray();
            if(this.checkRightList())
                this.saveToDb();

        },
        checkRightList:function(){
            return true;
        },
        saveToDb:function(){
            console.log(this.tableArray);
            console.log("in save:---------- ");
            var tem = JSON.stringify(this.tableArray);
            console.log(tem);
            Utils.showProgress(true);


            var gotResponse = function (o) {
                /*var Hadith = eval(o.responseText);*/

                /*Utils.showProgress(false);*/
                /*html = self.showTable();*/
                /*$jq('.tableGuest').html(html);
                self.setDateObjectAll();*/
                self.isSaved=true;
                Utils.showProgress(false);
               /* self.fillGuestList();
                self.isValid=self.checkValidGuest();*/


            };

            var callback = {
                success: gotResponse
            };
            var url = "../Runtime/process.php";
            var param = 'module=WorkFlowAjaxFunc&action=saveDetailedTable_eslahEzafekar&docId=' + FormView.docID +'&detailedTable='+tem;
            SAMA.util.Connect.asyncRequest('POST', url, callback, param);

        },
        fillDetailedTableArray:function(){
            this.tableArray=[[]];
            var length =  $jq('.detailedTable>tbody>tr[class^=\'tab\']').length;

            for (var count = 1; count <= length; count++) {
                this.tableArray[count] = [];
                this.tableArray[count][0] = $jq('.detailedTable>tbody>tr.tableRow_' + count + ' input[name=\'firstName\'] ').val();
                this.tableArray[count][1] = $jq('.detailedTable>tbody>tr.tableRow_' + count + ' input[name=\'lastName\'] ').val();
                this.tableArray[count][2] = $jq('.detailedTable>tbody>tr.tableRow_' + count + ' input[name=\'cardNumber\'] ').val();
                this.tableArray[count][3] = $jq('.detailedTable>tbody>tr.tableRow_' + count + ' input[name=\'overworkDone\'] ').val();
                this.tableArray[count][4] = $jq('.detailedTable>tbody>tr.tableRow_' + count + ' input[name=\'overworkConfirm\'] ').val();

            } /*          t = [             ["ali", "alavi", "4532", "521","534"]          */

        },

    };
    self.loadForm=function(){
        self.DetailedTable.showMode=self.getCable();
        console.log("DetailedTable.showMode="+self.DetailedTable.showMode);
        let html =self.DetailedTable.showTable();
        $jq('.detailedTableSpan').html(html);

    };
    self.getCable = function () {
        /*1: نود اول کارشناس مسئول منابع انسانی*/
        /*2: مسئول حوزه*/
        /*در نود سوم و*/
        /*
        سه حالت داریم در این فرم حالت اول edit که همه چیز قابل ویرایش هست
          حالت دوم فقط ستون اضافه کار تایید شده قابل ویرایش باشه
          حالت سوم که read only برای نود سوم که برگشت است و همچنین زمانیکه حالت کاربر و حالت گردشکار یکی نیست یعنی زمانیکه فرم در کارتبال های ارسالی دیده می شود
         */
        let workFlowState=""; /*level1 نود اول کارشناس مئسول منابع انسانی,level2 مسئول پرسنلی حوزه,level3 نود اخری برگشت به کارشناس */
        let userState="";


        if (FormView.myForm.info.settings.nodeName) {
            var nodeName = FormView.myForm.info.settings.nodeName;
            var nodeName2 = nodeName;
            while (nodeName2 && nodeName2.indexOf(String.fromCharCode(1705)) >= 0) nodeName2 = nodeName2.replace(String.fromCharCode(1705), String.fromCharCode(1603));
            while (nodeName2 && nodeName2.indexOf(String.fromCharCode(1740)) >= 0) nodeName2 = nodeName2.replace(String.fromCharCode(1740), String.fromCharCode(1610));
            if (nodeName == 'کارشناس-مسئول-منابع-انسانی' || nodeName2 == 'کارشناس-مسئول-منابع-انسانی') workFlowState = "level1";
            if (nodeName == 'مسئول-پرسنلی-حوزه' || nodeName2 == 'مسئول-پرسنلی-حوزه') workFlowState = "level2";
            if (nodeName == 'کارشناس-مسئول-منابع-انسانی-انتهایی' || nodeName2 == 'کارشناس-مسئول-منابع-انسانی-انتهایی') workFlowState = "level3";
        }

        userState = FormView.myForm.getItemByName('Field_5').getData();

        if(userState != workFlowState)/*طرف در نامه های ارسالی داره فرم رو باز می کنه*/
            return "readOnly";
        if(userState=="level1")
            return "edit";
        if(userState=="level2")
            return "editJustConfirm";
        if(userState=="level3")
            return "readOnly";

        /*
        * edit
        * readOnly
        * editJustConfirm
        * */

    }

}