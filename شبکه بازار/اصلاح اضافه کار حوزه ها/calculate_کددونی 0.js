this.jcode = function(self){
    self.DetailedTable = {
        tableArray:[[]],
        showMode: "edit",/** edit * readOnly*editJustConfirm*/
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
                '<td style="padding: 2px;border: 1px solid #ccc;"><input  onInput="FormView.myForm.getItemByName(\'Field_0\').DetailedTable.unSaved()" type="text" name="firstName" width="30px" value=""></td>' +
                '<td style="padding: 2px;border: 1px solid #ccc;"><input onInput="FormView.myForm.getItemByName(\'Field_0\').DetailedTable.unSaved()" type="text" name="lastName" value=""></td>' +
                '<td style="padding: 2px;border: 1px solid #ccc;"><input onInput="FormView.myForm.getItemByName(\'Field_0\').DetailedTable.unSaved()"  dir="ltr" type="number" name="cardNumber" min="0" value=""></td>' +
                '<td style="padding: 2px;border: 1px solid #ccc;"><input onInput="FormView.myForm.getItemByName(\'Field_0\').DetailedTable.onInputForOverworkDone()"  dir="ltr" type="number" min="0" name="overworkDone" value=""></td>' +
                '<td style="padding: 2px;border: 1px solid #ccc;"><input onkeydown="FormView.myForm.getItemByName(\'Field_0\').DetailedTable.checkTabOnLastCell(event)" onInput="FormView.myForm.getItemByName(\'Field_0\').DetailedTable.onInputForOverworkConfirm()"  dir="ltr" type="number" min="0" name="overworkConfirm" value=""></td>' +

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

            this.updateTotalOverworkDone();
            this.updateTotalOverworkConfirm();
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
            if(this.checkBeforeSave())
                this.saveToDb();
        },
        saveToDb:function(){
            console.log(this.tableArray);
            console.log("in save:---------- ");
            this.isSaved=true;
            Utils.showMessage('ذخیره سازی با موفقیت انجام شد');
            var tem = JSON.stringify(this.tableArray);
            console.log(tem);



            Utils.showProgress(true);

            var gotResponse = function (o) {

                console.log("in gotResponse:---------- ");
               /* this.isSaved=true;*/ /*this اینجا کار نمی کنه*/

                Utils.showProgress(false);
            };

            var callback = {
                success: gotResponse
            };
            var url = "../Runtime/process.php";
            let hozeh=FormView.myForm.getItemByName('Field_4').getData();
            var param = 'module=WorkFlowAjaxFunc&action=saveDetailedTable_eslahEzafekar&docId=' + FormView.docID +'&detailedTable='+tem+'&hozeh='+hozeh;
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
        getCable:function(){
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

        },
        checkBeforeSave:function(){

            var length = $jq('.detailedTable>tbody>tr[class^=\'tab\']').length;
            for (var count = 1; count <= length; count++) {
                if (this.tableArray[count][0].length < 3) {
                    Utils.showModalMessage('لطفا فيلد نام را در رديف ' + count + ' تصحيح كنيد');
                    return false;
                }
                if (this.tableArray[count][1].length < 3) {
                    Utils.showModalMessage('لطفا فيلد نام خانوادگی را در رديف ' + count + ' تصحيح كنيد');
                    return false;
                }
                if (this.tableArray[count][2].length < 3) {
                    Utils.showModalMessage('لطفا فيلد شماره پرسنلی را در رديف ' + count + ' تصحيح كنيد');
                    return false;
                }
                if ((this.tableArray[count][4].length < 1) && (this.showMode == "editJustConfirm")) {
                    Utils.showModalMessage('لطفا فيلد اضافه کار تایید شده را در رديف ' + count + ' تصحيح كنيد');
                    return false;
                }
            }

            let saghf=FormView.myForm.getItemByName('Field_3').getData();
            if(saghf=="")
                saghf=0;

            let totaloverworkConfirm=$jq('input[name^=totalOverworkConfirm]').val();
            if(totaloverworkConfirm=="")
                totaloverworkConfirm=0;

            if(parseInt(parseInt(saghf)-totaloverworkConfirm)<0){
                Utils.showModalMessage("مقدار اضافه کار تایید شده از سقف مجاز اضافه کار بیشتر است");
                return false;
            }



            return true;


        },
        updateTotalOverworkDone:function(){
            var length =  $jq('.detailedTable>tbody>tr[class^=\'tab\']').length;
            let sum=0;

            for (var count = 1; count <= length; count++) {
                let value=$jq('.detailedTable>tbody>tr.tableRow_' + count + ' input[name=\'overworkDone\'] ').val();
                if (value=="")
                    value=0;
                else
                    value=parseInt(value);

                sum+=value;
            }
            $jq('input[name^=totalOverworkDone]').val(sum)
        },
        updateTotalOverworkConfirm:function(){

            var length =  $jq('.detailedTable>tbody>tr[class^=\'tab\']').length;
            let sum=0;

            for (var count = 1; count <= length; count++) {
                let value=$jq('.detailedTable>tbody>tr.tableRow_' + count + ' input[name=\'overworkConfirm\'] ').val();
                if (value=="")
                    value=0;
                else
                    value=parseInt(value);

                sum+=value;
            }
            $jq('input[name^=totalOverworkConfirm]').val(sum)
        },
        onInputForOverworkDone:function(){
           this.unSaved();
           this.updateTotalOverworkDone();
        },
        onInputForOverworkConfirm:function(){
            this.unSaved();
            this.updateTotalOverworkConfirm();
            this.changeBackgroundTotalConfirm();
        },
        changeBackgroundTotalConfirm:function () {
            let saghf=FormView.myForm.getItemByName('Field_3').getData();
            if(saghf=="")
                saghf=0;

            let totaloverworkConfirm=$jq('input[name^=totalOverworkConfirm]').val();
            if(totaloverworkConfirm=="")
                totaloverworkConfirm=0;

            if(parseInt(parseInt(saghf)-totaloverworkConfirm)>=0)
                $jq('input[name^=totalOverworkConfirm]').css("background-color","#c5e1a5");/*green*/
            else
                $jq('input[name^=totalOverworkConfirm]').css("background-color","#ffab91");/*red*/


        },
        loadListFromLastList:function(arrayList){

        },
        checkTabOnLastCell:function(event){
            /*اگر روی خونه آخر تب رو زد بره یک سطر اضافه کند*/
            var key = event.keyCode;
            if(key==9)
                this.addRow();
        },

    };
    self.loadForm=function(){
        self.DetailedTable.showMode=self.DetailedTable.getCable();
        console.log("DetailedTable.showMode="+self.DetailedTable.showMode);
        self.showOrNotShowButtons();
        let html =self.DetailedTable.showTable();
        $jq('.detailedTableSpan').html(html);
        self.DetailedTable.updateTotalOverworkDone();
        self.DetailedTable.updateTotalOverworkConfirm();

    };
    self.btnConfirmLevel1=function(){
        let hozeh=FormView.myForm.getItemByName('Field_4').getData();
        if(hozeh.length==0){
            Utils.showModalMessage('حوزه انتخاب نشده است');
            return false;
        }

       /* let year=FormView.myForm.getItemByName('Field_2').getData();
        if(year=="0"){
            Utils.showModalMessage('سال انتخاب نشده است');
            return false;
        }

        let month=FormView.myForm.getItemByName('Field_1').getData();
        if(month=="0"){
            Utils.showModalMessage('ماه انتخاب نشده است');
            return false;
        }*/

        let startDate=FormView.myForm.getItemByName('Field_8').getData();
        if(startDate.length<8){
            Utils.showModalMessage('تاریخ ابتدای دوره انتخاب نشده است');
            return false ;
        }

        let endDate=FormView.myForm.getItemByName('Field_9').getData();
        if(endDate.length<8){
            Utils.showModalMessage('تاریخ انتهای دوره انتخاب نشده است');
            return false ;
        }





        let saghf=FormView.myForm.getItemByName('Field_3').getData();
        if(saghf.length=="0"){
            Utils.showModalMessage('سقف مجاز اضافه کار تعیین نشده است');
            return false;
        }


        if(self.DetailedTable.isSaved==false){
            Utils.showModalMessage('لطفا قبل از تایید، دکمه ذخیره و بررسی را کلیک کنید.');
            return false;
        }
        return true;
    };
    self.btnConfirmLevel2=function(){
        if(self.DetailedTable.isSaved==false){
            Utils.showModalMessage('لطفا قبل از تایید، دکمه ذخیره و بررسی را کلیک کنید.');
            return false;
        }
        return true;
        return true;
    };
    self.showOrNotShowButtons=function(){
        if(self.DetailedTable.showMode !="edit"){
            $jq("#btnAdd").css("display","none");
            $jq("#btnLoadDefault").css("display","none");
            $jq("#btnGetGraph").css("display","none");
            $jq("#btnAdd").css("display","none");
        }
        if(self.DetailedTable.showMode =="readOnly"){
            $jq("#btnSave").css("display","none");
            $jq("#trWillSaved").css("display","none");

        }


    };
    self.onClickBtnFetchingLastList=function(){
        let hozeh=FormView.myForm.getItemByName('Field_4').getData();
        if(hozeh.length==0){
            Utils.showModalMessage('حوزه انتخاب نشده است');
            return false;
        }

        /*گرفتن سقف اضافه کار از آیجکس*/
        var res = Utils.fastAjax('WorkFlowAjaxFunc', 'fetchSaghfHozeh',{hozeh:hozeh});
        FormView.myForm.getItemByName('Field_3').setData(res);
        /*گرفتن سقف اضافه کار از آیجکس*//*پایان*/

        /*گرفتن مسئول حوزه از آیجکس*/
        var res = Utils.fastAjax('WorkFlowAjaxFunc', 'fetchResponsibleForHozeh',{hozeh:hozeh});
        FormView.myForm.getItemByName('Field_7').setData([{uid: res['uid'],rid:res['rid']}]);
        /*گرفتن مسئول حوزه از آیجکس*//*پایان*/




        /*گرفتن لیست حوزه رکورد پیش فرض*/
        let lastList=[[]];
        /*---------------*/
        Utils.showProgress(true);

        var gotResponse = function (o) {
            console.log("in gotResponse:---------- ");
             lastList=JSON.parse(o.responseText);
             self.fillLastList(lastList);


            Utils.showProgress(false);
        };
        var callback = {
            success: gotResponse
        };
        var url = "../Runtime/process.php";
        var param = 'module=WorkFlowAjaxFunc&action=fetchLastList&hozeh='+hozeh;
        SAMA.util.Connect.asyncRequest('POST', url, callback, param);
        /*---------------*/
    };
    self.onClickBtnGetFromGragh=function(){


        let cardNumberArray=[];
        var length =  $jq('.detailedTable>tbody>tr[class^=\'tab\']').length;
        let index=0;
        let month=FormView.myForm.getItemByName('Field_1').getData();
        if(month==0){
            Utils.showModalMessage('ماه انتخاب نشده است');
            return ;
        }
        if(month.length==1)
            month="0"+month;
        let year=FormView.myForm.getItemByName('Field_2').getData();
        if(year==0){
            Utils.showModalMessage('سال انتخاب نشده است');
            return ;
        }
        if (year==1)
            year='1399';


        for (var count = 1; count <= length; count++) {
            let temp=$jq('.detailedTable>tbody>tr.tableRow_' + count + ' input[name=\'cardNumber\'] ').val();
            if ((Number(temp)>= 1)) {
                cardNumberArray[index] = temp;

                var res = Utils.fastAjax('WorkFlowAjaxFunc', 'getGraphListBazar',{mm:month,yy:year,PID:temp,status:'DoreZamani'});
                $jq('.detailedTable>tbody>tr.tableRow_' + count + ' input[name=\'overworkDone\'] ').val(res);

                index++;
            }
        }
        this.DetailedTable.updateTotalOverworkDone();
        console.log(cardNumberArray);


    },
    self.onClickBtnGetFromGraghV2=function(){


        let cardNumberArray=[];
        var length =  $jq('.detailedTable>tbody>tr[class^=\'tab\']').length;
        let index=0;
        let startDate=FormView.myForm.getItemByName('Field_8').getData();
        if(startDate.length<8){
            Utils.showModalMessage('تاریخ ابتدای دوره انتخاب نشده است');
            return ;
        }

        let endDate=FormView.myForm.getItemByName('Field_9').getData();
        if(endDate.length<8){
            Utils.showModalMessage('تاریخ انتهای دوره انتخاب نشده است');
            return ;
        }
        startDate=startDate.split('/');
        endDate=endDate.split('/');
        let aztd = startDate[2];
        if(aztd.length==1)
            aztd="0"+aztd;

        let aztm = startDate[1];
        if(aztm.length==1)
            aztm="0"+aztm;

        let azty = startDate[0];

        let tatd =endDate[2];
        if(tatd.length==1)
            tatd="0"+tatd;

        let tatm = endDate[1] ;
        if(tatm.length==1)
            tatm="0"+tatm;
        let taty = endDate[0] ;



        for (var count = 1; count <= length; count++) {
            let temp=$jq('.detailedTable>tbody>tr.tableRow_' + count + ' input[name=\'cardNumber\'] ').val();
            if ((Number(temp)>= 1)) {
                cardNumberArray[index] = temp;
                var res = Utils.fastAjax('WorkFlowAjaxFunc', 'getGraphListBazar',{aztd:aztd,aztm:aztm,azty:azty,tatd:tatd,tatm:tatm,taty:taty,PID:temp,status:'BazeZamani'});
                $jq('.detailedTable>tbody>tr.tableRow_' + count + ' input[name=\'overworkDone\'] ').val(res);

                index++;
            }
        }
        this.DetailedTable.updateTotalOverworkDone();
        console.log(cardNumberArray);


    },

    self.fillLastList=function(lastList){

        for (var count = 0; count < lastList.length; count++) {
            item=lastList[count];
            let firstName=item[0];
            let lastName=item[1];
            let cardNumber=item[2];
            let repetitive=0;

            var length =  $jq('.detailedTable>tbody>tr[class^=\'tab\']').length;

            for (var innerCount = 1; innerCount <= length; innerCount++) {
                if(cardNumber == $jq('.detailedTable>tbody>tr.tableRow_' + innerCount + ' input[name=\'cardNumber\'] ').val())
                    repetitive=1;
            }

            if(repetitive==0){
                self.DetailedTable.addRow();
                var lengthTable = $jq('.detailedTable > tbody > tr').length;
                let newTrId=lengthTable- 2;
                $jq('.detailedTable>tbody>tr.tableRow_' + newTrId + ' input[name=\'firstName\'] ').val(firstName);
                $jq('.detailedTable>tbody>tr.tableRow_' + newTrId + ' input[name=\'lastName\'] ').val(lastName);
                $jq('.detailedTable>tbody>tr.tableRow_' + newTrId + ' input[name=\'cardNumber\'] ').val(cardNumber);

            }

        }

    };

}