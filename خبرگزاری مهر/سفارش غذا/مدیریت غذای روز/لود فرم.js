listener = function (event) {
    class mainClass{

        /*
        * Malek Hajmohammadi
        * 1400/04/02
        * */

        /*مولفه های جستجو*/
        searchFields={};

        pageNumber=1;
        pageSize=10;

        personRoleArray=[];

       dataTableSave=[];

       /*چون اطلاعات مربوط به یک تاریخ هست ، غذا هم یکی هست و بهتر است
       * برای استفاده در رکوردهای جدید اونها رو ذخیره کنیم*/
       firstFood;
       secondFood;
       
        loadForm(){

        }

        /*ساخت مولفه های جستجو در یک آبجکت که بعد بصورت کلی به آیجکس پاس بدیم*/
        getSearchFields() {

            this.searchFields.date = this.getDate();

            this.searchFields.pageNumber=this.pageNumber;

            this.searchFields.pageSize=this.pageSize;

        }

        getDate() {
            let fDate = FormOnly.allFieldsContianer[0].getData();
            if (fDate.length < 8)
                return -1;

            return fDate;
        }

        /*
      * متد اجرایی با کلیک روی دکمه سرچ
      * */
        searchBtnClick() {
            this.getReport();

        }

        getReport() {
            console.log("getReport()");
            this.getSearchFields();
            console.log("searchFields");
            console.log("searchFields", this.searchFields);
            this.runAjaxSearch();
            /*گرفتن اسم غذاها*/
            this.getFoodNames();
            this.createSearchGrid();
        }

        runAjaxSearch() {
            var answers = Utils.fastAjax('WorkFlowAjaxFunc', 'getManageFoodData',{searchFields:this.searchFields});
            this.dataTable=answers['dataTable'];

        }

        getFoodNames(){
            if(this.dataTable[0] && this.dataTable[0][4]){
                this.firstFood=this.dataTable[0][4];
                this.secondFood=this.dataTable[0][5];
            }
            else{
                this.firstFood="نا مشخص";
                this.secondFood="نا مشخص";
            }
        }

        createSearchGrid(){
            var paging=this.gridHeader()+this.gridBody()+this.gridFooter();
            $jq('#listContainer').html(paging);

            /*ساخت عناصر قابل ویرایش*/
            this.createEditableElements();
        }



        /*قسمت هیدر گرید*/
        gridHeader(){

            var html = '<div class="outDataTable"><table style="direction:rtl" width="100%" class="f-table accessTable" cellpadding="0" cellspacing="1" >'+
                '<tbody>'+
                '<tr>'+
                '<th width="3%" style="padding: 2px; ">رديف</th>'+
                '<th width="40%" style="padding: 2px; ">کارمند/مهمان</th>'+
                '<th width="10%" style="padding: 2px; ">نوع غذا</th>'+
                '<th width="25%" style="padding: 2px; ">نام غذا</th>'+
                '<th width="3%" style="padding: 2px; ">تعداد</th>'+
                '<th width="3%" style="padding: 2px; "></th>'+

                '</tr>';

            return html;
        }



        /*قسمت فوتر گرید*/
        gridFooter(){
            var ft=' </tbody>'+
                '</table></div>';
            return ft;
        }

        /*بدنه گرید*/

        gridBody(){

            var radif = 0;/*+(($this->searchFields->pageNumber-1)*self::PAGE_LENGTH);*/

            var bodyPart="";
            var table = "";

            for(i=0; i<this.dataTable.length; i++){
                var value=this.dataTable[i];
                radif=i+1;
                bodyPart+= '<tr id="accessRow_' + i + '" >'+

                    '<td style="padding: 2px;border: 1px solid #ccc;" >' + radif + '</td>'+
                    '<td id="userTd_'+i+'" style="padding: 2px;border: 1px solid #ccc;" >'+value[1]+'</td>'+
                    '<td style="padding: 2px;border: 1px solid #ccc;direction: ltr" >' +
                        '<select onchange="window.codeSet.changeFoodName(this)" id="foodTypeSelect_'+i+'" >'+
                            '<option value="1" >نوع اول</option>'+
                            '<option value="2" >نوع دوم</option>'+
                        '</select>'+
                   '</td>'+
                    '<td id="foodName_'+i+'" style="padding: 2px;border: 1px solid #ccc;direction: ltr" >' + value[5] + '</td>'+
                    '<td id="count_'+i+'" style="padding: 2px;border: 1px solid #ccc;direction: ltr" >' + value[6] + '</td>'+
                    '<td id="tdDeleteImg" style="padding: 2px;background-color: #c5e1a5; border: 1px solid #ccc;">' +
                      '<img style="cursor: pointer" onclick="window.codeSet.removeRow(' +i + ')"' + 'src="gfx/toolbar/cross.png" />' +
                    '</td>' +


                    '</tr>';


            }

            return bodyPart;

        }

        removeRow(index) {
            $jq('#accessRow_' + index).remove();
            this.updateFrontAfterRemove(index);

        }

        updateFrontAfterRemove(index) {
            let a=1;
            $jq('.accessTable>tbody>tr[id^=\'accessRow\']').each(function() {
                $jq(this).find("td:first").html(a);
                a++;
            });
        }
        save(){
            this.collectInfoForSaving();
            var saveItems={};
            saveItems.date=this.searchFields.date;
            saveItems.dataTable=this.dataTableSave;


            Utils.fastAjax('WorkFlowAjaxFunc', 'manageFoodSave',{saveItems:saveItems});

        }
        collectInfoForSaving(){

            var counter=0;
            var that=this;
            this.dataTableSave=[];
            $jq('.accessTable>tbody>tr[id^=\'accessRow\']').each(function() {

                var id=this.id.split("_");
                id=id[1];

                var row={};
                console.log("id in each:", id);

                /*بررسی اینکه آیا کاربر هست یا مهمان*/
                var tdUser=$jq("#userTd_"+id+"> b").html();
                if(tdUser=="مهمان"){
                    row.userId=-1;
                    row.roleId=0;
                }
                else{
                    var userRole=that.personRoleArray[id].getData();
                    var userRoleArray=userRole.split(",");
                    row.userId=userRoleArray[0];
                    row.roleId=userRoleArray[1];
                }

                row.foodType=$jq("select#foodTypeSelect_" + id).val();
                row.count= $jq("#count_" + id+">input").val();

                /*$jq(this).find("td:first").html(a);*/
                that.dataTableSave[counter]=row;
                counter++;
            });

        }



        /*ساخت عناصر قابل ویرایش جدول*/
        createEditableElements(){
            for(i=0;i<this.dataTable.length;i++) {
                var value = this.dataTable[i];
                this.personRoleOrGuest(i);
                this.selectBoxTypeFood(i);
                this.setFoodName(i);
                this.setCount(i);
            }
        }

        /*ساخت فیلد کاربر سمت یا نوشتن مهمان*/
        personRoleOrGuest(index){

            /*userTd_i آی یک عنصر هست که این کاربر سمت رو در آن بگذارد*/

            if(this.dataTable[index]) {

                /*عدد -1 رو برای کاربر مهمان قرار می دیم*/
                if (this.dataTable[index][1] == -1) {
                    $jq("#userTd_" + index).html("<b>مهمان</b>")
                } else {
                    this.personRoleArray[index] = new Per_Role('window.codeSet.personRoleArray[' + index + ']', 'userTd_' + index, Main.getActiveCurrentSectriateUser());
                    this.personRoleArray[index].setData(this.dataTable[index][1], this.dataTable[index][2]);

                }
            }
            /*زمانیکه سطر جدید ایجاد می کنیم که در dataTable مقداری نداریم*/
            else{

                this.personRoleArray[index] = new Per_Role('window.codeSet.personRoleArray[' + index + ']', 'userTd_' + index, Main.getActiveCurrentSectriateUser());

            }


        }

        /*ساخت selectBox برای نوع غذا*/
        selectBoxTypeFood(index) {

            $jq("select#foodTypeSelect_" + index).val(this.dataTable[index][3]);
        }

        /*نمایش نام غذا بر اساس نوع غذای انتخابی*/
        changeFoodName(selectObject){
            var foodType=selectObject.value;
            var id=selectObject.id;
            console.log("id=",id);
            id=id.split("_");
            var index=id[1];
            if(foodType==1)
                $jq("#foodName_" + index).html(this.firstFood);
            else
                $jq("#foodName_" + index).html(this.secondFood);

        }

        /*نمایش نام غذا بر اساس نوع غذای انتخابی*/
        setFoodName(index){

            var foodType=$jq("select#foodTypeSelect_" + index).val();
            if(foodType==1)
                $jq("#foodName_" + index).html(this.firstFood);
            else
                $jq("#foodName_" + index).html(this.secondFood);

        }

        /*اضافه کردن input برای تعداد*/
        setCount(index){
         var tag='<input onInput="window.codeSet.onChangeCount(this)" style="width: 50px !important;" onkeyup=""  dir="ltr" type="number" name="productNum" min="0" value="'+this.dataTable[index][6]+'">';
         $jq("#count_" + index).html(tag);
        }
        onChangeCount(){

        }

        /*اضافه کردن مهمان*/
        addGuest(){
           var index=this.getIndex();

            var newTr= '<tr id="accessRow_' + index.index + '" >'+

                '<td style="padding: 2px;border: 1px solid #ccc;" >' + index.radit + '</td>'+
                '<td id="userTd_'+index.index+'" style="padding: 2px;border: 1px solid #ccc;" ><b>مهمان</b></td>'+
                '<td style="padding: 2px;border: 1px solid #ccc;direction: ltr" >' +
                '<select onchange="window.codeSet.changeFoodName(this)" id="foodTypeSelect_'+index.index+'" >'+
                '<option value="1" >نوع اول</option>'+
                '<option value="2" >نوع دوم</option>'+
                '</select>'+
                '</td>'+
                '<td id="foodName_'+index.index+'" style="padding: 2px;border: 1px solid #ccc;direction: ltr" >' + this.firstFood + '</td>'+
                '<td id="count_'+index.index+'" style="padding: 2px;border: 1px solid #ccc;direction: ltr" >' +
                    '<input onInput="window.codeSet.onChangeCount(this)" style="width: 50px !important;" onkeyup=""  dir="ltr" type="number" name="productNum" min="0" value="1">'+
                '</td>'+
                '<td id="tdDeleteImg" style="padding: 2px;background-color: #c5e1a5; border: 1px solid #ccc;">' +
                '<img style="cursor: pointer" onclick="window.codeSet.removeRow(' +index.radit + ')"' + 'src="gfx/toolbar/cross.png" />' +
                '</td>' +


                '</tr>';

            $jq('.accessTable>tbody').append(newTr);


        }

        /*اضافه کردن سفارش جدید*/
        addOrder(){
            var index=this.getIndex();

            var newTr= '<tr id="accessRow_' + index.index + '" >'+

                '<td style="padding: 2px;border: 1px solid #ccc;" >' + index.radit + '</td>'+
                '<td id="userTd_'+index.index+'" style="padding: 2px;border: 1px solid #ccc;" ></td>'+
                '<td style="padding: 2px;border: 1px solid #ccc;direction: ltr" >' +
                '<select onchange="window.codeSet.changeFoodName(this)" id="foodTypeSelect_'+index.index+'" >'+
                '<option value="1" >نوع اول</option>'+
                '<option value="2" >نوع دوم</option>'+
                '</select>'+
                '</td>'+
                '<td id="foodName_'+index.index+'" style="padding: 2px;border: 1px solid #ccc;direction: ltr" >' + this.firstFood + '</td>'+
                '<td id="count_'+index.index+'" style="padding: 2px;border: 1px solid #ccc;direction: ltr" >' +
                '<input onInput="window.codeSet.onChangeCount(this)" style="width: 50px !important;" onkeyup=""  dir="ltr" type="number" name="productNum" min="0" value="1">'+
                '</td>'+
                '<td id="tdDeleteImg" style="padding: 2px;background-color: #c5e1a5; border: 1px solid #ccc;">' +
                '<img style="cursor: pointer" onclick="window.codeSet.removeRow(' +index.radit + ')"' + 'src="gfx/toolbar/cross.png" />' +
                '</td>' +


                '</tr>';

            $jq('.accessTable>tbody').append(newTr);

            /*اضافه کردن فیلد کاربر سمت*/
            this.personRoleOrGuest(index.index);

        }

        /*گرفتن ردیف و ایندکس برای سطر جدید*/
        getIndex(){
            var index={};
            index.radit= $jq('.accessTable>tbody>tr[id^=\'accessRow\']:last').find("td:first").html();
            index.radit=parseInt(index.radit)+1;
            var idName=$jq('.accessTable>tbody>tr[id^=\'accessRow\']:last').attr("id");
            var idNameArray=idName.split("_");
            index.index=parseInt(idNameArray[1])+1;
            return index;

        }




    }
var waitInterval = setInterval(function () {
    if (FormOnly) {

        let instance = new mainClass();
        window.codeSet = instance;
        window.codeSet.loadForm();
    }

    clearInterval(waitInterval);
}, 300);

};