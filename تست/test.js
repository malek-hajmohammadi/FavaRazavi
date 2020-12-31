listener = function (event) {
    class mainClass {
        answers = [];
        listOfQuestions = [];
        myPie = [];
        userId = -1;
        temp = [];

        createUsersField() {
            var tempArr = Utils.fastAjax('Chart', 'getRolesByGroop', {groupID: 5});
            FormOnly.repList = new PRCopyElement("FormOnly.repList", "representationList", "representationList", tempArr);
            $jq("td#representationList img").css("display", "none");
        }

        clickOnAllRepresentationsCheckbox() {
            var checkBox = document.getElementById("allRepresentations");
            if (checkBox.checked == true) {
                $jq("#representationList input").prop('disabled', true);
                $jq("#representationList input").css("background-color", "#e0e0e0");
            } else {
                $jq("#representationList input").prop('disabled', false);
                $jq("#representationList input").css("background-color", "#fff");
            }
        }

        runAjaxSearch() {
            this.answers = Utils.fastAjax('WorkFlowAjaxFunc', 'getAnswerOfSurvey');             /* this.answers[0]=[5000,250,1200,5500,4560,1285,3450];              this.answers[1]=[8000,6545,1254,6545,4521,1354,9854];              this.answers[2]=[1200,5468,6548,8795,4567,4005,5000];*/              /* console.log("answers:",this.answers);*/
            console.log("answers:", this.answers);             /* $jq('#listContainer').html(res);*/
        }

        fillListOfQuestions() {             /*رضايت شغلي*/             /*0-19*/
            this.listOfQuestions.push("نظر شما درباره امنيت شغلي تان(ثبات آينده كاري و نداشتن نگراني در مورد از دست دادن شغل تان)؟");
            this.listOfQuestions.push("نظر شما درباره فرصت خدمت به ديگران در شغل تان؟");
            this.listOfQuestions.push("نظر شما درباره نحوه تقسيم كار بين همكاران بخش/ واحد؟");
            this.listOfQuestions.push("نظر شما درباره ميزان ساعات كار؟");
            this.listOfQuestions.push("نظر شما درباره نحوه چينش شيفت هاي كاريتان ؟");
            this.listOfQuestions.push("نظر شما درباره متناسب بودن شرح وظيفه با نوع كاري كه به شما محول شده است؟");
            this.listOfQuestions.push("نظر شما درباره نحوه تحسين و تشويق در مورد كار خوبي كه انجام داده ايد؟");
            this.listOfQuestions.push("نظر شما درباره نحوه برخورد و مشاركت همكارانتان با شما در انجام كارها؟");
            this.listOfQuestions.push("نظر شما درباره رعايت اصول ايمني در محيط كار شما؟");
            this.listOfQuestions.push("نظر شما درباره عادلانه بودن پرداخت مزايا؟");
            this.listOfQuestions.push("نظر شما درباره صميميت و ارتباط دوستانه و توأم با حمايت در محيط كاري؟");
            this.listOfQuestions.push("نظر شما درباره نحوه تعامل همكاران؟");
            this.listOfQuestions.push("نظر شما درباره امكان كاربرد خلاقيت در انجام وظايف محوله؟");
            this.listOfQuestions.push("نظر شما درباره امكان پيشرفت و ترقي در شغل؟");
            this.listOfQuestions.push("نظر شما درباره تناسب بين ميزان درآمد و مقدار كار؟");
            this.listOfQuestions.push("نظر شما درباره نحوه ارزشيابي افراد در سازمان بر اساس عملكرد و شايستگي افراد؟");
            this.listOfQuestions.push("نظر شما درباره صلاحيت و كفايت مسئول مدير مافوق در تصميم گيري؟");
            this.listOfQuestions.push("نظر شما درباره سرمايش و گرمايش محيطي كه در آن كار مي كنيد؟");
            this.listOfQuestions.push("نظر شما درباره دكوراسيون و تجهيزات اداري محيطي كه در آن كار مي كنيد؟");
            this.listOfQuestions.push("نظر شما درباره فضاي فيزيكي و ميزان نور محيطي كه در آن كار مي كنيد؟");              /*عملكرد مديران*/
            this.listOfQuestions.push("نظر شما درباره سهولت ملاقات حضوري با مدير ارشد شركت؟");
            this.listOfQuestions.push("نظر شما درباره نحوه برخورد مدير ارشد شركت؟");
            this.listOfQuestions.push("نظر شما درباره نحوه پيگيري و رسيدگي به مشكل شما توسط مدير ارشد شركت؟");
            this.listOfQuestions.push("نظر شما درباره سهولت ملاقات حضوري مديريت واحد؟");
            this.listOfQuestions.push("نظر شما درباره نحوه برخورد مديريت واحد؟");
            this.listOfQuestions.push("نظر شما درباره نحوه پيگيري و رسيدگي به مشكل شما توسط مديريت واحد؟");
            this.listOfQuestions.push("نظر شما درباره نحوه برخورد مسئولين واحد اداري؟");
            this.listOfQuestions.push("نظر شما درباره نحوه پيگيري و رسيدگي به مشكل شما توسط مسئولين واحد اداري؟");
            this.listOfQuestions.push("نظر شما درباره نحوه ارتباط و پاسخگويي مدير واحد؟");
            this.listOfQuestions.push("نظر شما درباره نحوه پيگيري و رسيدگي به مشكلات شيفت توسط مسئول مربوطه؟");
            this.listOfQuestions.push("نظر شما درباره برخوردار بودن از آزادي كافي براي بيان نظرات يا ابراز خطاهايتان؟");
            this.listOfQuestions.push("نظر شما درباره تغيير و تحولات ايجاد شده در اثر تغيير مديريت مجموعه؟");              /*تغذيه*/
            this.listOfQuestions.push("نظر شما درباره دماي مناسب غذا( از نظر گرم و سرد بودن)؟");
            this.listOfQuestions.push("نظر شما درباره پخت كامل غذا؟");
            this.listOfQuestions.push("نظر شما درباره مناسب بودن حجم غذا؟");
            this.listOfQuestions.push("نظر شما درباره مناسب بودن ظاهر غذا؟");
            this.listOfQuestions.push("نظر شما درباره نحوه برخورد مسئول توزيع غذا؟");              /*رفاهي*/
            this.listOfQuestions.push("نظر شما درباره سرويس اياب و ذهاب شركت؟");
            this.listOfQuestions.push("نظر شما درباره فعاليت ها و مسابقات فرهنگي در مناسبت هاي مختلف؟");
            this.listOfQuestions.push("ميزان اطلاع شما از فعاليت هاي رفاهي شركت؟");
            this.listOfQuestions.push("ميزان استفاده شما از امكانات رفاهي شركت؟");
            this.listOfQuestions.push("نظر شما درباره نحوه معرفي كاركنان جهت استفاده از استخر و ساير امكانات ورزشي؟");              /*آموزش*/
            this.listOfQuestions.push("نظر شما درباره انطباق برنامه هاي آموزشي شركت با نيازهاي شغلي؟");
            this.listOfQuestions.push("نظر شما درباره عدم تداخل شيفت كاري با كلاس هاي آموزشي؟");
            this.listOfQuestions.push("نظر شما درباره اطلاع رساني و آموزش مسئول مستقيم در خصوص روش هاي انجام كار؟");              /*پرسش*/
            this.listOfQuestions.push("نظر شما درباره اثربخش بودن كلاس هاي آموزشي درون بخشي؟");
            this.listOfQuestions.push("نظر شما درباره نحوه آموزش در بدو استخدام\n" + "(برگزاري آزمون، مصاحبه، آزمايشات بدو استخدام، ، بهداشت محيط، كارگزيني)\n؟");              /*اطلاع رساني*/
            this.listOfQuestions.push("نظر شما درباره نحوه اطلاع رساني در خصوص مقررات اداري و بخشنامه ها؟");
            this.listOfQuestions.push("نظر شما درباره نحوه اطلاع  رساني درباره اخبار شركت\n" + "(پايگاه اينترنتي ، پيام رسان روابط عمومي ،...)\n؟");
            this.listOfQuestions.push("نظر شما درباره اطلاع رساني شرح وظايف كاركنان؟");
            this.listOfQuestions.push("نظر شما درباره اطلاع رساني در خصوص اهداف و رسالت شركت؟");
        }

        getReport() {
            this.userId = this.getUserId();
            this.runAjaxSearch();
            this.cleanCharts();
            this.createCharts();
        }

        cleanCharts() {
            for (let i = 0; i < this.myPie.length; i++) {
                if (this.myPie[i]) this.myPie[i].destroy();
            }
            this.myPie = [];
        }

        createCharts() {
            let labels = this.listOfQuestions.slice(0, 20);
            console.log('labels', labels);
            let datasets = this.getChartDataset("rezayat");
            console.log('datasets', datasets);
            let data = {datasets: datasets, labels: labels};
            console.log('data', data);
            this.drawCharts("rezayat", data);
            labels = this.listOfQuestions.slice(20, 32);
            datasets = this.getChartDataset("amalkardModir");
            data = {datasets: datasets, labels: labels};
            this.drawCharts("amalkardModir", data);
            labels = this.listOfQuestions.slice(32, 37);
            datasets = this.getChartDataset("taghzieh");
            data = {datasets: datasets, labels: labels};
            this.drawCharts("taghzieh", data);
            labels = this.listOfQuestions.slice(37, 45);
            datasets = this.getChartDataset("refahi");
            data = {datasets: datasets, labels: labels};
            this.drawCharts("refahi", data);
            labels = this.listOfQuestions.slice(45, 47);
            datasets = this.getChartDataset("amoozesh");
            data = {datasets: datasets, labels: labels};
            this.drawCharts("amoozesh", data);
            labels = this.listOfQuestions.slice(47, 50);
            datasets = this.getChartDataset("etelaResani");
            data = {datasets: datasets, labels: labels};
            this.drawCharts("etelaResani", data);
        }

        getChartDataset(partName) {
            let datasets = [];
            let data_aali = this.getDataInEachOption(partName, "aali");             /*console.log('data_aali',data_aali);*/
            let data_khoob = this.getDataInEachOption(partName, "khoob");
            let data_ghabelGhabool = this.getDataInEachOption(partName, "ghabelGhabool");
            let data_motevaset = this.getDataInEachOption(partName, "motevaset");
            let data_zaief = this.getDataInEachOption(partName, "zaeif");
            datasets.push({data: data_aali, backgroundColor: '#1976D2', label: 'عالي'});
            datasets.push({data: data_khoob, backgroundColor: '#689F38', label: 'خوب'});
            datasets.push({data: data_ghabelGhabool, backgroundColor: '#B2FF59', label: 'قابل قبول'});
            datasets.push({data: data_motevaset, backgroundColor: '#FFA000', label: 'متوسط'});
            datasets.push({data: data_zaief, backgroundColor: '#C62828', label: 'ضعيف'});
            return datasets;
        }

        getDataInEachOption(partName, option) {
            let startQuestion = 0;
            let endQuestion = 0;             /*console.log('into getDataInEachOption');*/
            let optionValue = 0; /*مقدار متناظر با گزينه*/
            let data = [];
            switch (partName) {
                case "rezayat": {
                    startQuestion = 1;
                    endQuestion = 20;
                    break;
                }
                case "amalkardModir": {
                    startQuestion = 21;
                    endQuestion = 32;
                    break;
                }
                case "taghzieh": {
                    startQuestion = 33;
                    endQuestion = 37;
                    break;
                }
                case "refahi": {
                    startQuestion = 38;
                    endQuestion = 45;
                    break;
                }
                case "amoozesh": {
                    startQuestion = 46;
                    endQuestion = 47;
                    break;
                }
                case "etelaResani": {
                    startQuestion = 48;
                    endQuestion = 51;
                    break;
                }
            }
            switch (option) {
                case "aali":
                    optionValue = 5;
                    break;
                case "khoob":
                    optionValue = 4;
                    break;
                case "ghabelGhabool":
                    optionValue = 3;
                    break;
                case "motevaset":
                    optionValue = 2;
                    break;
                case "zaeif":
                    optionValue = 1;
                    break;
            }             /* console.log('optionValue:' + optionValue + '  startQuestion:' + startQuestion + '  endQuestion:' + endQuestion);*/
            for (let i = startQuestion, counter = 0; i <= endQuestion; i++, counter++) {
                let question = "q" + i;
                data[counter] = 0;               /*   console.log(this.userId);*/
                let sum = 0;
                for (let j = 0; j < this.answers.length; j++) {
                    let item = this.answers[j]; /*                    console.log(item.question == question && item.answer == optionValue &&(this.userId==-1 || item.userId==this.userId));*/
                    if (item.question == question && item.answer == optionValue && (this.userId == -1 || item.userId == this.userId)) {                        /* console.log('item.question:' + item.question + '  item.answer:' + item.answer);*/                         /*console.log('into if');*/
                        sum++;
                    }
                }
                data[counter] = sum;
            }
            return data;
        }

        getUserId() {
            let user = FormOnly.repList.getData();
            if (user == "") return -1;/*به اين معني كه نماينده خاصي انتخاب نشده است*/
            var checkBox = document.getElementById("allRepresentations");
            if (checkBox.checked == true) {
                return -1;
            }
            user = user.split(',');
            return user[0];
        }

        drawCharts(selector, chartData) {
            var config = {
                type: 'horizontalBar',
                data: chartData,
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
            var ctx = document.getElementById('chart-area-' + selector).getContext('2d');
            this.myPie.push(new Chart(ctx, config));
            selector = '#canvas-holder-' + selector;             /*console.log('-------------');             console.log('selector:'+selector);             console.log('chartData:',chartData);*/
            $jq(selector).css('margin', '0 auto');
        }

        loadForm() {
            this.createUsersField();
            this.fillListOfQuestions();
            this.getReport();
        }
    };var waitInterval = setInterval(function () {
        if (FormOnly && FormOnly.allFieldsContianer) {
            let instance = new mainClass();
            window.codeSet = instance;             /*window.codeSet=instance;*/
            window.codeSet.loadForm();
        }
        clearInterval(waitInterval);
    }, 300);
};