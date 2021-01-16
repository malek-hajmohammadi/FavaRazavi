listener = function (event) {

    class mainClass {


        answers=[];
        showMode="";
        userRoleObject=[];
        tableArray=[[]];


        loadForm() {


            this.showMode=this.getCable();


           let html=this.showTable();
            $jq('.detailedTableSpan').html(html);

            this.checkTableMode();

           this.setDataObjectAll();

           this.setTableMode();


        }

        checkTableMode(){
            if($jq('.detailedTableSpan input').is('[readonly]')){
                this.showMode="readOnly";
            }
            else{
                this.showMode="edit";
            }
        }

        setTableMode(){
            if(this.showMode=="readOnly") {
                $jq('.detailedTableSpan input').prop('disabled', true);
                $jq('.detailedTableSpan input').css("background-color", "#e0e0e0");
            }
        }




        setDataObjectAll() {

            var lengthTable = $jq('.detailedTable > tbody > tr').length;
            lengthTable=lengthTable-2; /*base 0*/

            for (var i = 0; i < lengthTable; i++) {
                this.setDataObjectOne(i);

            }
        }

        setDataObjectOne(index){
            index++; /*سطرها از یک شروع می شود*/
            var user = $jq('.tableRow_'+index+ ' input' ).attr('data-id').split(',');
            this.userRoleObject[index] = new Per_Role('window.codeSet.userRoleObject[' + index + ']', 'userTD_' + index, Main.getActiveCurrentSectriateUser());
            this.userRoleObject[index].setData(user[0], user[1]);
        }

        setDataObjectOneForAdd(index){

            this.userRoleObject[index] = new Per_Role('window.codeSet.userRoleObject[' + index + ']', 'userTD_' + index, Main.getActiveCurrentSectriateUser());

        }

        /*برای نمایش جدول*/
        showTable(){
            var html = Utils.fastAjax('WorkFlowAjaxFunc', 'loadParticipatesInEducationRequirement', {
                docId: FormView.docID, mode: this.showMode
            });
            return html;
        }


        /*برای اضافه کردن سطر جدید*/
        addRow(){
            var lengthTable = $jq('.detailedTable > tbody > tr').length;
            let index=lengthTable-2; /*base 0*/
            var newTr = '<tr class="tableRow_' + index  + '">' + '<td style="padding: 2px;border: 1px solid #ccc;">' + (index+1 ) + '</td>' +
                '<td id="userTD_' + index + '" style="padding: 2px;border: 1px solid #ccc;"><input  onInput="window.codeSet.unSaved()" type="text" name="productName" width="30px" value=""></td>' +


                '<td id="tdDeleteImg" style="padding: 2px;background-color: #c5e1a5; border: 1px solid #ccc;">' + '<img style="cursor: pointer" onclick="window.codeSet.removeRow(' + (lengthTable) + ')"' + 'src="gfx/toolbar/cross.png" />' + '</td>' +
                '</tr>';
            $jq('.detailedTable > tbody > tr').eq(index).after(newTr);
            this.setDataObjectOneForAdd(index);

        }

        /*برای حذف سط*/
       /* removeRow(){
            let c;
        }*/

       /* updateFrontAfterRemove(){
            let d;
        }*/

        saveList(){
            this.fillDetailedTableArray();
            console.log(this.tableArray);

            if(this.considerSafety()){
                this.saveToDatabase();
                return true;
            }
            else{
                return false;
            }


        }
        considerSafety(){
            var length =  $jq('.detailedTable>tbody>tr[class^=\'tab\']').length;
            if (length==0){
                Utils.showModalMessage("لیست افراد برای شرکت در دوره وارد نشده است");
                return false;
            }

            for (var count = 0; count < length; count++) {
                let userTemp = this.userRoleObject[count].getData();
                if(userTemp[0]==0){
                    Utils.showModalMessage("ردیف "+(count+1)+" فاقد اطلاعات است ");
                    return false;
                }


            }


            return true;
        }

        saveToDatabase(){
            let ajaxParam={tableArray:this.tableArray,docId:FormView.docID};
            var result = Utils.fastAjax('WorkFlowAjaxFunc', 'savePeopleCourse',ajaxParam);

            console.log("ajaxParam:",ajaxParam);
        }

        fillDetailedTableArray(){
            var length =  $jq('.detailedTable>tbody>tr[class^=\'tab\']').length;

            for (var count = 0; count < length; count++) {
                this.tableArray[count] = this.userRoleObject[count].getData();

            }


        }

        getCable(){
            /*1: نود اول مدیر واحد*/
            /*2: تضمین کیفیت*/
            /*در نود سوم و*/

            let workFlowState=""; /*level1 نود اول مدیر واحد,level2 تضمین کیفیت */
            let userState="";


            if (FormView.myForm.info.settings.nodeName) {
                var nodeName = FormView.myForm.info.settings.nodeName;
                var nodeName2 = nodeName;
                while (nodeName2 && nodeName2.indexOf(String.fromCharCode(1705)) >= 0) nodeName2 = nodeName2.replace(String.fromCharCode(1705), String.fromCharCode(1603));
                while (nodeName2 && nodeName2.indexOf(String.fromCharCode(1740)) >= 0) nodeName2 = nodeName2.replace(String.fromCharCode(1740), String.fromCharCode(1610));
                if (nodeName == 'مدیر-واحد' || nodeName2 == 'مدیر-واحد') workFlowState = "level1";
                if (nodeName == 'تضمین-کیفیت' || nodeName2 == 'تضمین-کیفیت') workFlowState = "level2";
                if (nodeName == 'مدیرعامل' || nodeName2 == 'مدیرعامل') workFlowState = "level3";
                if (nodeName == 'مدیرآموزش' || nodeName2 == 'مدیرآموزش') workFlowState = "level4";
            }

            userState = FormView.myForm.getItemByName('Field_9').getData();

            if(userState != workFlowState)/*طرف در نامه های ارسالی داره فرم رو باز می کنه*/
            {
                return "readOnly";
            }

            switch (userState) {
                case "level1":
                    return "edit";
                case "level2":
                    return "readOnly";
                case "level3":
                    return "readOnly";
                case "level4":
                    return "editAmoozesh";

            }
            return "readOnly";

        }

        confirm_1_modirVahed(){
            let flag=this.saveList();

            return flag;
        }
        confirm_2_tazminKeifiat(){
            return true;
        }
        confirm_3_modirAmel(){
            return true;
        }
        confirm_4_modirAmoozesh(){
            return true;
        }


      /*  checkBeforeSave(){
            let i;
        }*/

        /*checkTabOnLastCell(event){
            var key = event.keyCode;
            if(key==9)
                this.addRow();
        }*/

    };


    var waitInterval = setInterval(function () {
        if (FormView && FormView.myForm) {

            let instance = new mainClass();
            window.codeSet = instance;

            window.codeSet.loadForm();
        }

        clearInterval(waitInterval);
    }, 300);

};

