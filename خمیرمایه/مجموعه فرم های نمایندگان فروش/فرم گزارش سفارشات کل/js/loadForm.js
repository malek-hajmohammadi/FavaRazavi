listener = function (event) {

    class mainClass {
        searchFields={};
        definedProducts=[[]];




        /*
        * ساخت فیلد تکمیل شونده نمایندگان (براساس گروه دسترسی)
        * */
        createUsersField(){
            var tempArr = Utils.fastAjax('Chart', 'getRolesByGroop', {groupID: 16});
            FormOnly.repList= new PRCopyElement("FormOnly.repList", "representationList","representationList", tempArr);
            $jq("td#representationList img").css("display","none");

        }

        /*
        * متد اجرایی با کلیک روی دکمه سرچ
        * */
        searchBtnClick(){
            this.getReport();
        }


        loadForm() {
            this.changeFront();
            this.createUsersField();
            this.createProductSelectedList();
            console.log("in Load Form");
            this.getReport();

        }

        getReport(){
            console.log("getReport()");
           this.getSearchFields();
            console.log("searchFields");
            console.log("searchFields"+this.searchFields);
            this.runAjaxSearch();


        }
        prevPage(){
            var pageNumber = parseInt($jq('#pageNumber').val());
            if (pageNumber > 1){
                pageNumber --;
                $jq('#pageNumber').val(pageNumber);
                this.searchFields.pageNumber = pageNumber;
                this.runAjaxSearch();
            }

        }
        nextPage(){

            var pageNumber = parseInt($jq('#pageNumber').val());
            var maxPage = parseInt($jq('#maxPage').val());
            if (pageNumber < maxPage) {
                pageNumber ++;
                $jq('#pageNumber').val(pageNumber);
                this.searchFields.pageNumber = pageNumber;
                this.runAjaxSearch();
            }
        }
        runAjaxSearch(){
            res = Utils.fastAjax('WorkFlowAjaxFunc', 'getTotalOrders',{searchFields:this.searchFields});
            console.log("res".res);
            $jq('#listContainer').html(res);
        }


        /*ساخت مولفه های جستجو در یک آبجکت که بعد بصورت کلی به آیجکس پاس بدیم*/
        getSearchFields(){



            this.searchFields.pageNumber = this.getPageNumber();

            this.searchFields.formNumber=this.getFormNumber();

            this.searchFields.firstDate=this.getFristDate();

            this.searchFields.endDate=this.getSecondDate();

            this.searchFields.productName=this.getProductName();

            this.searchFields.userId=this.getUserId();

            this.searchFields.cableState=this.getCableState();

            /*گرفتن نماینده*/
        }
        /*تغییرات در ظاهر فرم که در لود فرم اجرا می شود*/
        getUserId() {
            let user = FormOnly.repList.getData();
            if (user == "")
                return -1;/*به این معنی که نماینده خاصی انتخاب نشده است*/

            var checkBox = document.getElementById("allRepresentations");
            if (checkBox.checked == true) {
                return -1;
            }
            return user[0];
        }

        getFristDate(){
            let fDate=FormOnly.allFieldsContianer[2].getData();
            if(fDate.length<8)
                return -1;

            var checkBox = document.getElementById("allDates");
            if (checkBox.checked == true)
                return -1;
            return fDate;
        }
        getSecondDate(){
           let sDate=FormOnly.allFieldsContianer[3].getData();

            if(sDate.length<8)
                return -1;

            var checkBox = document.getElementById("allDates");
            if (checkBox.checked == true)
                return -1;
            return sDate;

        }

        getPageNumber(){
            var pageNumber = parseInt($jq('#pageNumber').val());
            if (pageNumber < 1)
                return 1;
            return pageNumber;
        }

        getFormNumber(){
            let fNumber=FormOnly.allFieldsContianer[4].getData();
            if(fNumber=="")
                return -1;
            return fNumber;
        }

        /*گرفتن نام محصول انتخابی برای ساخت آبجکت جستجو*/
        getProductName(){
            let pName=$jq(".productsList select").val();

            var checkBox = document.getElementById("allProducts");
            if (checkBox.checked == true){
                return -1;
            }

            if(pName=="selected")
                return -1;

            return pName;


        }

        getCableState(){
            let state=FormOnly.allFieldsContianer[5].getData();
            if(state=="" || state==1)
                return -1;
            return state;
        }
        changeFront(){

            $jq(".productUnit input").prop('disabled', true);
            $jq(".productUnit input").css("background-color","#e0e0e0");
        }

        /*ساخت یک select box*/
        createProductSelectedList(){
            this.definedProducts = Utils.fastAjax('WorkFlowAjaxFunc', 'getDefinedProducts');
            let tag;
            tag = '<select onchange="window.codeSet.onChangeProductSelectTag(this)" name="productName" style="width: 100%;" >';
            tag += '<option value="selected" >انتخاب محصول ...</option>';
            for (let i = 0; i < this.definedProducts.length; i++) {
                tag += `<option value=\"${this.definedProducts[i]['productName']}\">${this.definedProducts[i]['productName']}</option>`;
            }

            tag += '</select></td>';
            $jq(".productsList").html(tag);
            return tag;

        }

        onChangeProductSelectTag(selectObject){
            var productName = selectObject.value;
            var productType=this.getProductType(productName);
            $jq(".productUnit input").val(productType);

        }

        getProductType(productName) {
            for (let i = 0; i < this.definedProducts.length; i++) {

                if (this.definedProducts[i]['productName'] == productName)
                    return this.definedProducts[i]['productType']
            }
            return "---";
        }

        /*کلیک رو چک باکس تمام نمایندگان*/

        clickOnAllRepresentationsCheckbox(){
            var checkBox = document.getElementById("allRepresentations");
            if (checkBox.checked == true){

                $jq("#representationList input").prop('disabled', true);
                $jq("#representationList input").css("background-color","#e0e0e0");

            } else {

                $jq("#representationList input").prop('disabled', false);
                $jq("#representationList input").css("background-color","#fff");
            }



        }

        /*کلیک رویه چک باکس تمام محصولات*/
        clickOnAllProducts(){
            var checkBox = document.getElementById("allProducts");
            if (checkBox.checked == true){

                $jq(".productsList select").prop('disabled', true);
                $jq(".productsList select").css("background-color","#e0e0e0");

            } else {

                $jq(".productsList select").prop('disabled', false);
                $jq(".productsList select").css("background-color","#fff");
            }

        }
        clickOnAllDates(){
            var checkBox = document.getElementById("allDates");
            if (checkBox.checked == true){

                $jq(".firstDate input").prop('disabled', true);
                $jq(".firstDate input").css("background-color","#e0e0e0");

                $jq(".secondDate input").prop('disabled', true);
                $jq(".secondDate input").css("background-color","#e0e0e0");

            } else {

                $jq(".firstDate input").prop('disabled', false);
                $jq(".firstDate input").css("background-color","#fff");

                $jq(".secondDate input").prop('disabled', false);
                $jq(".secondDate input").css("background-color","#fff");


            }


        }

    };


    var waitInterval = setInterval(function () {
        if (FormOnly && FormOnly.allFieldsContianer[1]) {

            let instance = new mainClass();
            window.codeSet = instance;
            /*window.codeSet=instance;*/
            window.codeSet.loadForm();


        }
        clearInterval(waitInterval);


    }, 300);

};

/*-------------------------------------------------------------------------------------------------------*/
