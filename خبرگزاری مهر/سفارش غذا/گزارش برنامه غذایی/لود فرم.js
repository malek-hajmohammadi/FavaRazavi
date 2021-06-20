listener = function (event) {

    class mainClass {
        /*
        * Malek Hajmohammadi
        * 1400/03/26
        * */

        searchFields = {};
        dataTable;
        total;
        pageNumber=1;
        pageSize=10;


        loadForm() {
            this.createUsersField();
            this.createFoodList();



        }

        /*ساخت مولفه های جستجو در یک آبجکت که بعد بصورت کلی به آیجکس پاس بدیم*/
        getSearchFields() {
            this.searchFields.userId = this.getUserId();

            this.searchFields.firstDate = this.getFristDate();

            this.searchFields.endDate = this.getSecondDate();

            this.searchFields.foodType = this.getFoodTypes();

            this.searchFields.foodName = this.getFoodName();

            this.searchFields.pageNumber=this.pageNumber;

            this.searchFields.pageSize=this.pageSize;


        }

        getUserId() {
            let user = FormOnly.userList.getData();
            if (user == "")
                return -1;/*به این معنی که کاربر خاصی انتخاب نشده است*/

            var userId=user.split(',')[0];
            var checkBox = document.getElementById("allUsers");
            if (checkBox.checked == true) {
                return -1;
            }
            return userId;
        }

        getFristDate() {
            let fDate = FormOnly.allFieldsContianer[0].getData();
            if (fDate.length < 8)
                return -1;

            var checkBox = document.getElementById("allDates");
            if (checkBox.checked == true)
                return -1;
            return fDate;
        }

        getSecondDate() {
            let sDate = FormOnly.allFieldsContianer[1].getData();

            if (sDate.length < 8)
                return -1;

            var checkBox = document.getElementById("allDates");
            if (checkBox.checked == true)
                return -1;
            return sDate;

        }

        getFoodTypes() {

            var foodType = FormOnly.allFieldsContianer[2].getData();
            var checkBox = document.getElementById("allFoodTypes");
            if (checkBox.checked == true || foodType == "")
                return -1;
            return foodType;
        }

        getFoodName() {

            let foodName = $jq(".foodList select").val();

            var checkBox = document.getElementById("allFoods");
            if (checkBox.checked == true || foodName == "selected")
                return -1;
            return foodName;

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
            this.createSearchGrid();
        }

        runAjaxSearch() {
            var answers = Utils.fastAjax('WorkFlowAjaxFunc', 'reportFoodData',{searchFields:this.searchFields});
            this.dataTable=answers['dataTable'];
            this.total=answers['total'];


        }

        prevPage(){
            var pageNumber = parseInt($jq('#pageNumber').val());
            if (pageNumber > 1){
                pageNumber --;
                $jq('#pageNumber').val(pageNumber);
                this.pageNumber = pageNumber;
                this.getReport();
            }

        }
        nextPage(){

            var pageNumber = parseInt($jq('#pageNumber').val());
            var maxPage = parseInt($jq('#maxPage').val());
            if (pageNumber < maxPage) {
                pageNumber ++;
                $jq('#pageNumber').val(pageNumber);
                this.pageNumber = pageNumber;
                this.getReport();
            }
        }
        showSpecificPage(){
            var page=parseInt($jq('#pageNumber').val());
            var maxPage = parseInt($jq('#maxPage').val());
            if(page>0 && page<=maxPage){
                this.pageNumber=page;
                this.getReport();
            }


        }


        /*کلیک رو چک باکس تمام کاربران*/
        clickOnAllUsersCheckbox() {
            var checkBox = document.getElementById("allUsers");
            if (checkBox.checked == true) {

                $jq("#userList input").prop('disabled', true);
                $jq("#userList input").css("background-color", "#e0e0e0");

            } else {

                $jq("#userList input").prop('disabled', false);
                $jq("#userList input").css("background-color", "#fff");
            }
        }

        /*کلیک روی چک باکس از ابتدا تاکنون*/
        clickOnAllDates() {
            var checkBox = document.getElementById("allDates");
            if (checkBox.checked == true) {

                $jq(".firstDate input").prop('disabled', true);
                $jq(".firstDate input").css("background-color", "#e0e0e0");

                $jq(".secondDate input").prop('disabled', true);
                $jq(".secondDate input").css("background-color", "#e0e0e0");

            } else {

                $jq(".firstDate input").prop('disabled', false);
                $jq(".firstDate input").css("background-color", "#fff");

                $jq(".secondDate input").prop('disabled', false);
                $jq(".secondDate input").css("background-color", "#fff");


            }


        }

        clickOnAllFoodTypes() {
            var checkBox = document.getElementById("allFoodTypes");
            if (checkBox.checked == true) {

                $jq(".foodTypeList input").prop('disabled', true);
                $jq(".foodTypeList input").css("background-color", "#e0e0e0");

            } else {

                $jq(".foodTypeList input").prop('disabled', false);
                $jq(".foodTypeList input").css("background-color", "#fff");
            }
        }

        clickOnAllFoods() {
            var checkBox = document.getElementById("allFoods");
            if (checkBox.checked == true) {

                $jq(".foodList select").prop('disabled', true);
                $jq(".foodList select").css("background-color", "#e0e0e0");

            } else {

                $jq(".foodList select").prop('disabled', false);
                $jq(".foodList select").css("background-color", "#fff");
            }
        }


        /*ساخت فیلد انتخابی از تمام کاربران*/
        createUsersField() {
            var tempArr = Utils.fastAjax('Chart', 'getAllUsers', {justEnable: "1"});
            FormOnly.userList = new PRCopyElement("FormOnly.userList", "userList", "userList", Main.getAddressBook());
            $jq("td#userList img").css("display", "none");

        }

        /*ساخت select box از تمام غذاها*/
        createFoodList() {
            var definedFoods = Utils.fastAjax('WorkFlowAjaxFunc', 'getFoods');
            let tag;
            tag = '<select  name="FoodName" style="width: 100%;" >';
            tag += '<option value="selected" >انتخاب غذا ...</option>';
            for (let i = 0; i < definedFoods.length; i++) {
                tag += `<option value=\"${definedFoods[i]}\">${definedFoods[i]}</option>`;
            }

            tag += '</select></td>';
            $jq(".foodList").html(tag);


        }


        /*ساخت جدول جستجو*/
        createSearchGrid(){
            var paging=this.gridPagingPart()+this.gridHeader()+this.gridBody()+this.gridFooter();
            $jq('#listContainer').html(paging);
        }

        /*قسمت صفحه بندی گرید*/
        gridPagingPart(){


            var pages=Math.floor(this.total/this.pageSize)+1;
            var html = '<div style="text-align: center;padding: 2px;">'+
                '<span style="float: right;padding: 5px;font-weight: bold;">'+this.total+' مورد يافت شد</span>'+
            '<button onclick="window.codeSet.prevPage()" id="prevPage">صفحه قبل </button>صفحه'+
            '<input id="pageNumber" class="f-input" tabindex="6001" style="width: 50px;" value="'+ this.pageNumber + '">'+'       از ' + pages +
            '<input type="hidden" value="' + pages + '" id="maxPage" >'+
            '<button onclick="window.codeSet.showSpecificPage()" id="showPage" style="background-color:#b7f7ab">نمايش صفحه وارد  شده  </button>'+
            '<button tabindex="6001" onclick="window.codeSet.nextPage()" onkeydown="return FormBuilder.designFormModal.setLFocus(event)" id="textPage">صفحه بعد </button> </td></div>';
            return html;
        }

        /*قسمت هیدر گرید*/
        gridHeader(){

            var html = '<table width="100%" class="f-table accessTable" cellpadding="0" cellspacing="1" >'+
                '<tbody>'+
                '<tr>'+
                '<th width="3%" style="padding: 2px; ">رديف</th>'+
                '<th width="4%" style="padding: 2px; ">شماره پرسنلی</th>'+
                '<th width="20%" style="padding: 2px; ">نام و نام خانوادگی</th>'+
                '<th width="15%" style="padding: 2px; ">تاریخ</th>'+
                '<th width="10%" style="padding: 2px; ">نوع غذا</th>'+
                '<th width="25%" style="padding: 2px; ">نام غذا</th>'+
                '<th width="3%" style="padding: 2px; ">تعداد</th>'+
                '</tr>';

            return html;
        }

        /*قسمت فوتر گرید*/
        gridFooter(){
            var ft=' </tbody>'+
                '</table>';
            return ft;
        }

        /*بدنه گرید*/
        gridBody(){

            var radif = 0;/*+(($this->searchFields->pageNumber-1)*self::PAGE_LENGTH);*/

            var bodyPart="";
            var table = "";

            for(i=0;i<this.dataTable.length;i++){
                var value=this.dataTable[i];
                radif++;
                bodyPart+= '<tr id="accessRow_' + (radif) + '" >'+
                '<td style="padding: 2px;border: 1px solid #ccc;" >' + radif + '</td>'+
                    '<td style="padding: 2px;border: 1px solid #ccc;" >' + value[0] + '</td>'+
                    '<td style="padding: 2px;border: 1px solid #ccc;" >' + value[1] + '</td>'+
                    '<td style="padding: 2px;border: 1px solid #ccc;direction: ltr" >' + value[2] + ' '+value[3]+ '</td>'+
                    '<td style="padding: 2px;border: 1px solid #ccc;direction: ltr" >' + value[4] + '</td>'+
                    '<td style="padding: 2px;border: 1px solid #ccc;direction: ltr" >' + value[5] + '</td>'+
                    '<td style="padding: 2px;border: 1px solid #ccc;direction: ltr" >' + value[6] + '</td>'+
                    '</tr>';
            }
            return bodyPart;

        }


    };

    var waitInterval = setInterval(function () {
        if (FormOnly) {

            let instance = new mainClass();
            window.codeSet = instance;
            window.codeSet.loadForm();
        }

        clearInterval(waitInterval);
    }, 300);

};

