listener = function (event) {

    class mainClass {

        tableArray = [[]];
        definedProducts = [[]];/*لیست تعریف شده در فرم تعریف محصولات*/
        isSaved = false;
        showMode = "read";
        showModeForQuestions="";
        listOfQuestions = [];
        answers=[];
        /*نمایش لیست سفارشات نماینده*/
        showTable() {
            var html = Utils.fastAjax('WorkFlowAjaxFunc', 'loadListOrderRepresentation', {
                docId: FormView.docID, mode: this.showMode
            });
            return html;
        }
        /*گرفتن پاسخ ها از دیتابیس*/
        getAnswersFromDb(){
            this.answers= Utils.fastAjax('WorkFlowAjaxFunc', 'getAnswersOfRepresentation', {
                docId: FormView.docID
            });

        }

        loadForm() {

            this.changeInputAttributeOfRepresentation();
            this.getDefinedProducts();
            this.getShowMode();
            let html = this.showTable();
            $jq('.detailedTableSpan').html(html);

            if(this.showModeForQuestions=="edit")
                this.makeQuestioner();
            else if(this.showModeForQuestions=="readOnly"){
                this.getAnswersFromDb();
                this.makeQuestioner();
            }
            else
                $jq(".fieldSet6").css("display","none");

        }
        changeInputAttributeOfRepresentation() {
            $jq('.repInfo >input').attr("readonly", "true");
            $jq('.repInfo >input').css("background", "gainsboro");
        }
        /*
        * متدی که در زمان تایید نماینده در نود اول فراخوانی می شود
        * */
        confirmInNamayandehSaveOrders() {
            if (this.checkValidationOrders()) {
                return this.saveList();
            } else
                return false;
        }

        /*متدی که در زمان تایید نماینده نهایی وجود دارد*/
        confirmInNamayandehSaveAnswers(){
            if (this.checkValidationAnswers()) {
                return this.saveAnswers();
            } else
                return false;

        }
        /*دکمه تایید در کارتابل انباردار برای چک کردن مشخصات حمل بار*/
        confirmAnbardarCheckFields(){

            if(FormView.myForm.getItemByName('Field_14').getData().length<3){
                Utils.showModalMessage("لطفا شماره بارنامه را به درستی وارد کنید.");
                return false;
            }
            if(FormView.myForm.getItemByName('Field_13').getData().length<3){
                Utils.showModalMessage("لطفا نام راننده را به درستی وارد کنید.");
                return false;
            }
            if(FormView.myForm.getItemByName('Field_15').getData().length<3){
                Utils.showModalMessage("لطفا شماره همراه راننده را به درستی وارد کنید.");
                return false;
            }

            return true;
        }
        saveOrdersToDatabase() {

            let result = Utils.fastAjax('WorkFlowAjaxFunc', 'saveRepresentationOrder', {
                tableArray: this.tableArray,
                docId: FormView.did,

            });
            Utils.showModalMessage(result);
        }
        saveAnswersToDatabase() {

            let result = Utils.fastAjax('WorkFlowAjaxFunc', 'saveRepresentationAnswers', {

                docId: FormView.did,
                answers: this.answers
            });
            Utils.showModalMessage(result);
        }

        unSaved() {
            this.isSaved = false;
        };

        getShowMode() {
            let currentCable;/*کارتابلی که الان نامه رو باز کرده*/
            let activeCable;/*آخرین کارتابلی در گردش به اونجا رسید*/

            if (FormView) {
                var nodeName = FormView.myForm.info.settings.nodeName;
                var nodeName2 = nodeName;
                while (nodeName2 && nodeName2.indexOf(String.fromCharCode(1705)) >= 0) nodeName2 = nodeName2.replace(String.fromCharCode(1705), String.fromCharCode(1603));
                while (nodeName2 && nodeName2.indexOf(String.fromCharCode(1740)) >= 0) nodeName2 = nodeName2.replace(String.fromCharCode(1740), String.fromCharCode(1610));
                if (nodeName == 'نماینده' || nodeName2 == 'نماینده') currentCable = "namayandeh";
                if (nodeName == 'رئیس-فروش' || nodeName2 == 'رئیس-فروش') currentCable = "raiesFroosh";
                if (nodeName == 'مالی' || nodeName2 == 'مالی') currentCable = "mali";
                if (nodeName == 'مدیرمالی' || nodeName2 == 'مدیرمالی') currentCable = "modirMali";
                if (nodeName == 'مدیربازرگانی' || nodeName2 == 'مدیربازرگانی') currentCable = "modirBazargani";
                if (nodeName == 'مدیرعامل' || nodeName2 == 'مدیرعامل') currentCable = "modirAmel";
                if (nodeName == 'انباردار' || nodeName2 == 'انباردار') currentCable = "anbardar";
                if (nodeName == 'پخش' || nodeName2 == 'پخش') currentCable = "pakhsh";
                if (nodeName == 'انتظامات' || nodeName2 == 'انتظامات') currentCable = "entezamat";
                if (nodeName == 'پخش-بعدازنماینده-نهایی' || nodeName2 == 'پخش-بعدازنماینده-نهایی') currentCable = "pakhshAfterNamayandehNahayee";
                if (nodeName == 'پخش-در-عدم-دریافت' || nodeName2 == 'پخش-در-عدم-دریافت') currentCable = "pakhshDarAdamDaryaft";
                if (nodeName == 'نماینده-نهایی' || nodeName2 == 'نماینده-نهایی') currentCable = "namayandehNahayee";


            }


            activeCable = FormView.myForm.getItemByName('Field_5').getData();

            this.setShowModeForQuestion(activeCable,currentCable);


            if (activeCable != currentCable)/*طرف در نامه های ارسالی داره فرم رو باز می کنه*/{
                this.showMode = "readOnly";
                return;
            }


            if (activeCable == "namayandeh")
                this.showMode = "edit";
            else
                this.showMode = "readOnly";

        }

        setShowModeForQuestion(activeCable,currentCable){
            let hiddenArray=["namayandeh","raiesFroosh","mali","modirMali","anbardar","pakhsh",
                "entezamat","modirAmel","modirBazargani","raiesFrooshAdamTayeedBazargani",
                "namayandehAdamTayeedBazargani"];
            let readOnlyArray=["pakhshAfterNamayandehNahayee","pakhshDarAdamDaryaft","baygani"];
            let editArray=["namayandehNahayee"];

            var fruits = ["Banana", "Orange", "Apple", "Mango"];
            var a = fruits.indexOf("Apple");
            if (hiddenArray.indexOf(currentCable)>=0){
                this.showModeForQuestions="hidden";
                return;
            }

            if (activeCable != currentCable)/*طرف در نامه های ارسالی داره فرم رو باز می کنه*/{
                this.showModeForQuestions="readOnly";
                return;
            }

            if(readOnlyArray.indexOf(currentCable)>=0){
                this.showModeForQuestions="readOnly";
                return;
            }

            if(editArray.indexOf(currentCable)>=0){
                this.showModeForQuestions="edit";
                return;
            }

            this.showModeForQuestions="hidden";
            return ;


        }

        removeRow(index) {
            $jq('.tableRow_' + index).remove();
            this.updateFrontAfterRemove();

        }

        updateFrontAfterRemove() {
            var lengthTable = $jq('.detailedTable>tbody>tr[class^=\'tab\']').length;
            for (var i = 1; i <= lengthTable; i++) {
                $jq('.detailedTable >tbody > tr').eq(i).attr('class', 'tableRow_' + i);
                $jq('.detailedTable>tbody>tr.tableRow_' + i + '>td:first ').html(i);
                $jq('.detailedTable>tbody>tr.tableRow_' + i + '>td#tdDeleteImg>img').attr('onclick', 'window.codeSet.removeRow(' + i + ')');
            }

        }

        addRow() {
            var lengthTable = $jq('.detailedTable > tbody > tr').length;
            var newTr = '<tr class="tableRow_' + (lengthTable - 1) + '">' + '<td style="padding: 2px;border: 1px solid #ccc;">' + (lengthTable - 1) + '</td>' +
                this.getSelectedTagForDefinedProducts() +
                '<td style="padding: 2px;border: 1px solid #ccc;"><input readonly style="background: gainsboro" onInput="window.codeSet.unSaved()" type="text" name="productType" value=""></td>' +
                '<td style="padding: 2px;border: 1px solid #ccc;"><input readonly style="background: gainsboro" onInput="window.codeSet.unSaved()"   dir="ltr" type="text" name="productPrice" min="0" value=""></td>' +
                '<td style="padding: 2px;border: 1px solid #ccc;"><input onInput="window.codeSet.onChangedNumberOfProductInput(this)" onkeyup=""  dir="ltr" type="number" name="productNum" min="0" value=""></td>' +
                '<td style="padding: 2px;border: 1px solid #ccc;"><input readonly style="background: gainsboro" onInput=""   dir="ltr" type="text" name="productTotalRow" min="0" value=""></td>' +
                '<td id="tdDeleteImg" style="padding: 2px;background-color: #c5e1a5; border: 1px solid #ccc;">' + '<img style="cursor: pointer" onclick="window.codeSet.removeRow(' + (lengthTable - 1) + ')"' + ' src="gfx/toolbar/cross.png" />' + '</td>' +
                '</tr>';
            $jq('.detailedTable > tbody > tr').eq(lengthTable - 2).after(newTr);
        }

        /*window.codeSet.DetailedTable.separateNum(this.value,this)*/

        separateNum(value, input) {
            /* seprate number input 3 number */
            var nStr = value + '';
            nStr = nStr.replace(/\,/g, "");
            let x = nStr.split('.');
            let x1 = x[0];
            let x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }
            if (input !== undefined) {

                input.value = x1 + x2;
            } else {
                return x1 + x2;
            }
        }

        getDefinedProducts() {
            this.definedProducts = Utils.fastAjax('WorkFlowAjaxFunc', 'getDefinedProducts');
        }

        getSelectedTagForDefinedProducts() {

            let tag;
            tag = '<td style="padding: 2px;border: 1px solid #ccc;width: 100%"><select onchange="window.codeSet.onChangeSelectTag(this)" name="productName" style="width: 100%;" >';
            tag += '<option value="selected" >انتخاب محصول ...</option>';
            for (let i = 0; i < this.definedProducts.length; i++) {
                tag += `<option value=\"${this.definedProducts[i]['productName']}\">${this.definedProducts[i]['productName']}</option>`;
            }

            tag += '</select></td>';
            return tag;

        }

        onChangeSelectTag(selectObject) {
            var productName = selectObject.value;
            let rowClassName = $jq(selectObject).closest('tr').attr('class');
            var rowId = rowClassName.substr(rowClassName.length - 1); /*شماره جدول رو بر می گردونه*/
            this.changeTheValueOfCellsAfterChangingProductName(productName, rowId);

            this.onChangedNumberOfProductInput(selectObject);

        }

        changeTheValueOfCellsAfterChangingProductName(productName, rowId) {
            let productType = this.getProductType(productName);
            let productPrice = this.getProductPrice(productName);
            $jq('.detailedTable>tbody>tr.tableRow_' + rowId + " input[name='productType']").val(productType);
            $jq('.detailedTable>tbody>tr.tableRow_' + rowId + " input[name='productPrice']").val(productPrice);
        }

        getProductType(productName) {
            for (let i = 0; i < this.definedProducts.length; i++) {

                if (this.definedProducts[i]['productName'] == productName)
                    return this.definedProducts[i]['productType']
            }
            return "---";
        }

        getProductPrice(productName) {
            for (let i = 0; i < this.definedProducts.length; i++) {

                if (this.definedProducts[i]['productName'] == productName) {

                    let price = this.definedProducts[i]['productPrice'];
                    return this.numberWithCommas(price);
                }
            }
            return "---";

        }

        numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        onChangedNumberOfProductInput(selectObject) {
            this.unSaved();
            this.changeRowPrice(selectObject);
            this.changeTotalPrice();

        }

        changeRowPrice(selectObject) {
            /*let productNumber = selectObject.value;*/
            let rowClassName = $jq(selectObject).closest('tr').attr('class');
            let rowId = rowClassName.substr(rowClassName.length - 1); /*شماره جدول رو بر می گردونه*/

            let productNumber = $jq('.detailedTable>tbody>tr.tableRow_' + rowId + " input[name='productNum']").val();

            let priceWithComma = $jq('.detailedTable>tbody>tr.tableRow_' + rowId + " input[name='productPrice']").val();

            let productPrice = this.numberWithoutComma(priceWithComma);


            let totalPrice = productNumber * productPrice;
            totalPrice = this.numberWithCommas(totalPrice);
            $jq('.detailedTable>tbody>tr.tableRow_' + rowId + " input[name='productTotalRow']").val(totalPrice);
        }

        changeTotalPrice() {
            var length = $jq('.detailedTable>tbody>tr[class^=\'tab\']').length;
            let sum = 0;

            for (var count = 1; count <= length; count++) {
                let value = $jq('.detailedTable>tbody>tr.tableRow_' + count + ' input[name=\'productTotalRow\'] ').val();
                if (value == "")
                    value = 0;
                else {
                    value = this.numberWithoutComma(value);
                    value = parseInt(value);
                }


                sum += value;
            }

            sum = this.numberWithCommas(sum);
            $jq('input[name^=totalPrice]').val(sum);

        }

        numberWithoutComma(x) {
            return x.replace(/,/g, '');
        }

        saveList() {
            this.fillDetailedTableArray();
            /*
            * دریافت لیست پاسخ نامه و چک کردن آن
            * که خالی نباشد
            * */

            if (!this.considerSafety()) {

                return false;
            }

            this.answers=this.getAnswers();
            if(!this.checkAnswers())
                return false;

            this.saveOrdersToDatabase();
            return true;

        }
        saveAnswers(){
            this.answers=this.getAnswers();
            if(!this.checkAnswers())
                return false;

            this.saveAnswersToDatabase();
            return true;

        }


        /*
        * چک کردن پاسخ ها که خالی نباشد
        * */
        checkAnswers(){

            for (let i = 0; i < this.listOfQuestions.length; i++) {
                let value=this.answers[i].answer;
                if (!value){
                    Utils.showModalMessage("سوال "+(i+1)+" پاسخ داده نشده است.");
                    return false;
                }

            }
            return true;
        }
        checkValidationOrders() {

            let totalPrice=$jq("input[name='totalPrice']").val();
            totalPrice=this.numberWithoutComma(totalPrice);

            let remainedCredit=FormView.myForm.getItemByName('Field_18').getData();
            if(remainedCredit-totalPrice<0){
                Utils.showModalMessage("سقف اعتبار کافی نمی باشد!");
                return false;
            }


            return true;
        }
        checkValidationAnswers() {

            return true;
        }

        /*
        * پرکردن جدول محصولات
        * براساس داده های دریافتی
        * */
        fillDetailedTableArray() {
            this.tableArray = [[]];
            var length = $jq('.detailedTable>tbody>tr[class^=\'tab\']').length;

            for (var count = 1; count <= length; count++) {
                this.tableArray[count] = [];
                this.tableArray[count][0] = $jq('.detailedTable>tbody>tr.tableRow_' + count + ' select[name=\'productName\'] ').val();

                this.tableArray[count][1] = $jq('.detailedTable>tbody>tr.tableRow_' + count + ' input[name=\'productType\'] ').val();
                this.tableArray[count][2] = $jq('.detailedTable>tbody>tr.tableRow_' + count + ' input[name=\'productPrice\'] ').val();
                this.tableArray[count][2] = this.numberWithoutComma(this.tableArray[count][2]);

                this.tableArray[count][3] = $jq('.detailedTable>tbody>tr.tableRow_' + count + ' input[name=\'productNum\'] ').val();

                this.tableArray[count][4] = $jq('.detailedTable>tbody>tr.tableRow_' + count + ' input[name=\'productTotalRow\'] ').val();
                this.tableArray[count][4] = this.numberWithoutComma(this.tableArray[count][4]);
            }
        }

        /*
       * چک کردن جدول سفارش قبل از ذخیره سازی
       * */
        considerSafety() {
            let length = $jq('.detailedTable>tbody>tr[class^=\'tab\']').length;

            if(length==0){
                Utils.showModalMessage("کالایی سفارش داده نشده است!");
                return false;
            }

            for (let count = 1; count <= length; count++) {

                let price=this.tableArray[count][2];
                if (isNaN(price)||price=="" ){
                    Utils.showModalMessage("کالای انتخابی در سطر "+count+" معتبر نمی باشد!");
                    return false;
                }

                let numberOfOrder=this.tableArray[count][3];
                if (isNaN(numberOfOrder)||numberOfOrder=="" || numberOfOrder<1 || numberOfOrder>1000000){
                    Utils.showModalMessage("تعداد سفارش در سطر "+count+" معتبر نمی باشد!");
                    return false;
                }

            }

            let repeated=0;
            for (let count = 1; count+1 <= length; count++) {

                let productName=this.tableArray[count][0];
                for (let innerCount = count+1; innerCount <= length; innerCount++) {
                    let secondProduct=this.tableArray[innerCount][0];
                    if(productName==secondProduct) {
                        repeated = 1;
                        break;
                    }

                }

                if(repeated==1){
                    Utils.showModalMessage("یک محصول در بیش از یک سطر آورده شده است!");
                    return false;
                }

            }





            return true;
        }

        /*
        * تعریف سوالات پاسخنامه
        * */
        fillListOfQuestions() {
            this.listOfQuestions.push("کیفیت خمیرمایه و عملکرد آن را در ورآمدن خمیر چگونه ارزیابی می نمائید؟");
            this.listOfQuestions.push("کیفیت خمیرمایه از لحاظ رنگ و عطر را چگونه ارزیابی می نمائید؟");
            this.listOfQuestions.push("زمان تحویل خمیرمایه سفارش داده شد را چگونه ارزیابی می نمائید؟");
            this.listOfQuestions.push("کیفیت بسته بندی خمیرمایه را چگونه ارزیابی می نمائید؟");
            this.listOfQuestions.push("میزان رضایت شما از نحوه ی سفارش گزاری چگونه می باشد؟");
            this.listOfQuestions.push("برخورد راننده و کادر پخش را چگونه ارزیابی می نمائید؟");
            this.listOfQuestions.push("آیا برای مصرف خمیرمایه نیاز به آموزش دارید؟");
        }

        /*
        * ساخت گزینه ها
        * اگر نظرسنجی حالت فقط خواندنی دارد ، ان رو هم اعمال می کند
        * */
        createTagQuestions() {


            let editable="";
            let cursorState="";

            if(this.showModeForQuestions=="readOnly") {
                editable = "disabled='disabled'";
                cursorState="style='cursor:auto'";
            }

            for (let i = 1; i <= this.listOfQuestions.length; i++) {

                let row = " <div class=\"divTableRow\">\n" +
                    "                    <div class=\"divTableCell\">\n" +
                    "                        <div class=\"radif\" style=\"float: right\">" + i + "</div>\n" +
                    "\n" +
                    "                    </div>\n" +
                    "                    <div class=\"divTableCell removeBorderLeft\">\n" +
                    "                        <div class=\"titleQuestion\" style=\"float: right\">" + this.listOfQuestions[i - 1] + "\n" +
                    "                        </div>\n" +
                    "\n" +
                    "\n" +
                    "                    </div>\n" +
                    "                    <div class=\"divTableCell removeBorderRight\">\n" +
                    "                        <div class=\"containerRadio\">\n" +
                    "                            <div class=\"cntr\">\n";

                let radios = "";
                for (let j = 0; j <= 2; j++) {

                    /*
                    * چک کردن اینکه این خونه پاسخ داده شده یا نه
                    * و براساس آن صفت های این خونه رو عوض کنه
                    * */
                    let tickStateInner = "";
                    let tickStateOuter="";
                    let answer=parseInt(this.answers[i-1]);

                    if (answer == j + 1)
                    {

                        tickStateInner="style='stroke-dashoffset: 38'";
                        tickStateOuter="style='stroke-dashoffset: 0'";
                    }
                    let option = "";
                    switch (j) {
                        case 0:
                            option = "ضعیف";
                            break;
                        case 1:
                            option = "متوسط";
                            break;
                        case 2:
                            option = "خوب";

                    }
                    let radio = "                                <label "+cursorState+" class=\"btn-radio\" for=\"S2R" + i + "O" + j + "\">\n" +
                        "\n" +
                        "\n" +
                        "                                    <input "+editable+"  id=\"S2R" + i + "O" + j + "\" name=\"rGroupS2R" + i + "\" tabindex=\"6001\" value=\"" + j + "\" type=\"radio\">\n" +
                        "                                    <svg height=\"20px\" viewBox=\"0 0 20 20\" width=\"20px\">\n" +
                        "                                        <circle cx=\"10\" cy=\"10\" r=\"9\"></circle>\n" +
                        "                                        <path "+tickStateInner+" class=\"inner\"\n" +
                        "                                              d=\"M10,7 C8.34314575,7 7,8.34314575 7,10 C7,11.6568542 8.34314575,13 10,13 C11.6568542,13 13,11.6568542 13,10 C13,8.34314575 11.6568542,7 10,7 Z\"></path>\n" +
                        "                                        <path "+tickStateOuter+" class=\"outer\"\n" +
                        "                                              d=\"M10,1 L10,1 L10,1 C14.9705627,1 19,5.02943725 19,10 L19,10 L19,10 C19,14.9705627 14.9705627,19 10,19 L10,19 L10,19 C5.02943725,19 1,14.9705627 1,10 L1,10 L1,10 C1,5.02943725 5.02943725,1 10,1 L10,1 Z\"></path>\n" +
                        "                                    </svg>\n" +
                        "                                    <span>" + option + "</span>\n" +
                        "\n" +
                        "\n" +
                        "                                </label>\n";
                    radios += radio;
                }
                row += radios;

                row += "\n" +
                    "\n" +
                    "                            </div>\n" +
                    "                        </div>\n" +
                    "\n" +
                    "\n" +
                    "                    </div>\n" +
                    "                </div>";

                $jq("#divTable").append(row);

            }

        }

        /*
        * ساخت قسمت نظرسنجی
        * */
        makeQuestioner() {
            this.fillListOfQuestions();
            this.createTagQuestions();
        }

        /*

        * * گرفتن پاسخ از فرم
        که بصورت یک ارایه از آبجکت های که question و answer دارند درست می کند
        * */
        getAnswers() {
            let resultString = [];
            for (let i = 1; i <= this.listOfQuestions.length; i++) {
                let item = "rGroupS2R" + i;
                let value = $jq("input[name='" + item + "']:checked").val();
                if (!value) value = 0;
                else
                    value = parseInt(value) + 1;

                let obj = new Object();
                obj.question = item;
                obj.answer = value;
                resultString.push(obj);
            }
            return resultString;
        }

    };

    var waitInterval = setInterval(function () {
        if (FormView && FormView.myForm) {

            let instance = new mainClass();
            window.codeSet = instance;
            /*window.codeSet=instance;*/
            window.codeSet.loadForm();
        }

        clearInterval(waitInterval);
    }, 300);

};

