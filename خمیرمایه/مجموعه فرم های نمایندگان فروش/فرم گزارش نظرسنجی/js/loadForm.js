listener = function (event) {

    class mainClass {

        searchFields={};
        definedProducts=[[]];
        answers=[[]];
        myPie=null;

        createChart(){

            /*دیتا یک آرایه هست که سایز آن به اندازه label هست */
            var config = {
                type: 'horizontalBar',
                data: {
                    datasets: [{ data: this.answers[2],backgroundColor: '#4CAF50', label: 'خوب'}, {data: this.answers[1], backgroundColor: '#607D8B', label: 'متوسط'}, {data: this.answers[0], backgroundColor: '#FF5722', label: 'ضعیف'}],
                    labels: ["كيفيت خميرمايه و عملكرد آن را در ورآمدن خمير چگونه ارزيابي مي نمائيد؟","كيفيت خميرمايه از لحاظ رنگ و عطر را چگونه ارزيابي مي نمائيد؟","زمان تحويل خميرمايه سفارش داده شد را چگونه ارزيابي مي نمائيد؟","كيفيت بسته بندي خميرمايه را چگونه ارزيابي مي نمائيد؟","ميزان رضايت شما از نحوه ي سفارش گزاري چگونه مي باشد؟","برخورد راننده و كادر پخش را چگونه ارزيابي مي نمائيد؟","آيا براي مصرف خميرمايه نياز به آموزش داريد؟"]
                },
                options: {
                    responsive: true,
                    legend: {display: true, position: 'top'},
                    scales: {
                        xAxes: [{
                            ticks: {
                                beginAtZero: true, userCallback: function (label, index) {
                                    if (Math.floor(label) === label) {
                                        return label.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                    }
                                }
                            }
                        }], yAxes: [{ticks: {fontSize: 16, fontFamily: 'B Nazanin'}}]
                    }
                }
            };
            var ctx = document.getElementById('chart-area').getContext('2d');

            if (this.myPie)
                this.myPie.destroy();

            this.myPie = new Chart(ctx, config);
            $jq('#canvas-holder').css('margin', '0 auto');

        }
        createUsersField(){
            var tempArr = Utils.fastAjax('Chart', 'getRolesByGroop', {groupID: 16});
            FormOnly.repList= new PRCopyElement("FormOnly.repList", "representationList","representationList", tempArr);
            $jq("td#representationList img").css("display","none");

        }

        getReport(){

            this.getSearchFields();
            console.log("searchFields:",this.searchFields);
            this.runAjaxSearch();
            this.createChart();

        }

        runAjaxSearch(){
            this.answers = Utils.fastAjax('WorkFlowAjaxFunc', 'getAnswersForReport',{searchFields:this.searchFields});
           /* this.answers[0]=[5000,250,1200,5500,4560,1285,3450];
            this.answers[1]=[8000,6545,1254,6545,4521,1354,9854];
            this.answers[2]=[1200,5468,6548,8795,4567,4005,5000];*/

           /* console.log("answers:",this.answers);*/
            console.log("answers:",this.answers);
           /* $jq('#listContainer').html(res);*/
        }


        /*ساخت مولفه های جستجو در یک آبجکت که بعد بصورت کلی به آیجکس پاس بدیم*/
        getSearchFields(){







            this.searchFields.firstDate=this.getFristDate();

            this.searchFields.endDate=this.getSecondDate();

            this.searchFields.productName=this.getProductName();

            this.searchFields.userId=this.getUserId();



            /*گرفتن نماینده*/
        }

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



        changeFront(){

            $jq(".productUnit input").prop('disabled', true);
            $jq(".productUnit input").css("background-color","#e0e0e0");
        }

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
            this.getReport();

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

