listener = function (event) {
    class mainClass {

        listOfQuestions = [];
        resultString = [];

        makeQuestioner() {
            this.fillListOfQuestions();
            this.createTagQuestions();
        }

        isNewUserInSurvey(){
            let result=Utils.fastAjax('WorkFlowAjaxFunc', 'isNewUserInSurvey');
            return result;
        }


        loadForm() {
            if(this.isNewUserInSurvey()) {
                this.makeQuestioner();
            }
            else{
                Utils.showModalMessage('شما قبلا فرم نظرسنجی را تکمیل نموده اید');
            }

        }
        fillListOfQuestions() {
            /*رضایت شغلی*/
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
            this.listOfQuestions.push("نظر شما درباره فضاي فيزيكي و ميزان نور محيطي كه در آن كار مي كنيد؟");

            /*عملکرد مدیران*/
            this.listOfQuestions.push("نظر شما درباره سهولت ملاقات حضوري با مدير ارشد شركت؟");
            this.listOfQuestions.push("نظر شما درباره نحوه برخورد مدير ارشد شركت؟");
            this.listOfQuestions.push("نظر شما درباره نحوه پيگيري و رسيدگي به مشكل شما توسط مدير ارشد شركت؟");
            this.listOfQuestions.push("نظر شما درباره سهولت ملاقات حضوري مديريت واحد؟");
            this.listOfQuestions.push("نظر شما درباره نحوه برخورد مديريت واحد؟");
            this.listOfQuestions.push("نظر شما درباره نحوه پيگيري و رسيدگي به مشكل شما توسط مديريت واحد؟");
            this.listOfQuestions.push("نظر شما درباره نحوه برخورد مسئولین واحد اداری؟");
            this.listOfQuestions.push("نظر شما درباره نحوه پيگيري و رسيدگي به مشكل شما توسط مسئولین واحد اداری؟");
            this.listOfQuestions.push("نظر شما درباره نحوه ارتباط و پاسخگويي مدير واحد؟");
            this.listOfQuestions.push("نظر شما درباره نحوه پيگيري و رسيدگي به مشكلات شيفت توسط مسئول مربوطه؟");
            this.listOfQuestions.push("نظر شما درباره برخوردار بودن از آزادي كافي براي بيان نظرات يا ابراز خطاهايتان؟");
            this.listOfQuestions.push("نظر شما درباره تغيير و تحولات ايجاد شده در اثر تغيير مديريت مجموعه؟");

            /*تغذیه*/
            this.listOfQuestions.push("نظر شما درباره دماي مناسب غذا( از نظر گرم و سرد بودن)؟");
            this.listOfQuestions.push("نظر شما درباره پخت كامل غذا؟");
            this.listOfQuestions.push("نظر شما درباره مناسب بودن حجم غذا؟");
            this.listOfQuestions.push("نظر شما درباره مناسب بودن ظاهر غذا؟");
            this.listOfQuestions.push("نظر شما درباره نحوه برخورد مسئول توزيع غذا؟");

            /*رفاهی*/
            this.listOfQuestions.push("نظر شما درباره سرويس اياب و ذهاب شركت؟");
            this.listOfQuestions.push("نظر شما درباره فعاليت ها و مسابقات فرهنگي در مناسبت هاي مختلف؟");
            this.listOfQuestions.push("ميزان اطلاع شما از فعاليت هاي رفاهي شركت؟");
            this.listOfQuestions.push("ميزان استفاده شما از امكانات رفاهي شركت؟");
            this.listOfQuestions.push("نظر شما درباره نحوه معرفي كاركنان جهت استفاده از استخر و ساير امكانات ورزشي؟");

            /*آموزش*/
            this.listOfQuestions.push("نظر شما درباره انطباق برنامه هاي آموزشي شركت با نيازهاي شغلي؟");
            this.listOfQuestions.push("نظر شما درباره عدم تداخل شيفت كاري با كلاس هاي آموزشي؟");
            this.listOfQuestions.push("نظر شما درباره اطلاع رساني و آموزش مسئول مستقيم در خصوص روش هاي انجام كار؟");

            /*پرسش*/
            this.listOfQuestions.push("نظر شما درباره اثربخش بودن كلاس هاي آموزشي درون بخشي؟");
            this.listOfQuestions.push("نظر شما درباره نحوه آموزش در بدو استخدام\n" + "(برگزاري آزمون، مصاحبه، آزمايشات بدو استخدام، ، بهداشت محيط، كارگزيني)\n؟");

            /*اطلاع رسانی*/
            this.listOfQuestions.push("نظر شما درباره نحوه اطلاع رساني در خصوص مقررات اداري و بخشنامه ها؟");
            this.listOfQuestions.push("نظر شما درباره نحوه اطلاع  رساني درباره اخبار شركت\n" + "(پايگاه اينترنتي ، پيام رسان روابط عمومي ،...)\n؟");
            this.listOfQuestions.push("نظر شما درباره اطلاع رساني شرح وظايف كاركنان؟");
            this.listOfQuestions.push("نظر شما درباره اطلاع رساني در خصوص اهداف و رسالت شركت؟");
        }
        createTagQuestions() {



            for (let i = 0; i < this.listOfQuestions.length; i++) {
                let row = " <div class=\"divTableRow\">\n" +
                    "                    <div class=\"divTableCell\">\n" +
                    "                        <div class=\"radif\" style=\"float: right\">" + (i+1) + "</div>\n" +
                    "\n" + "                    </div>\n" +
                    "                    <div class=\"divTableCell removeBorderLeft tdtitleQuestion\">\n" +
                    "                        <div class=\"titleQuestion\" style=\"float: right\">" + this.listOfQuestions[i] +
                    "\n" + "                        </div>\n" + "\n" + "\n" + "                    </div>\n" +
                    "                    <div class=\"divTableCell removeBorderRight\">\n" +
                    "                        <div class=\"containerRadio\">\n" +
                    "                            <div class=\"cntr\">\n";
                let radios = "";
                for (let j = 0; j <= 4; j++) {                     /*                     * چك كردن اينكه اين خونه پاسخ داده شده يا نه                     * و براساس آن صفت هاي اين خونه رو عوض كنه                     * */
                    let tickStateInner = "";
                    let tickStateOuter = "";
                    let option = "";
                    switch (j) {
                        case 0:
                            option = "ضعيف";
                            break;
                        case 1:
                            option = "متوسط";
                            break;
                        case 2:
                            option = "قابل قبول";
                            break;
                        case 3:
                            option="خوب";
                            break;
                        case 4:
                            option="عالی";
                            break;
                    }
                    let radio = "                                <label  class=\"btn-radio\" for=\"S2R" + i + "O" + j + "\">\n" + "\n" + "\n" + "                                    <input  id=\"S2R" + i + "O" + j + "\" name=\"q" + (i+1) + "\" tabindex=\"6001\" value=\"" + j + "\" type=\"radio\">\n" + "                                    <svg height=\"20px\" viewBox=\"0 0 20 20\" width=\"20px\">\n" + "                                        <circle cx=\"10\" cy=\"10\" r=\"9\"></circle>\n" + "                                        <path " + tickStateInner + " class=\"inner\"\n" + "                                              d=\"M10,7 C8.34314575,7 7,8.34314575 7,10 C7,11.6568542 8.34314575,13 10,13 C11.6568542,13 13,11.6568542 13,10 C13,8.34314575 11.6568542,7 10,7 Z\"></path>\n" + "                                        <path " + tickStateOuter + " class=\"outer\"\n" + "                                              d=\"M10,1 L10,1 L10,1 C14.9705627,1 19,5.02943725 19,10 L19,10 L19,10 C19,14.9705627 14.9705627,19 10,19 L10,19 L10,19 C5.02943725,19 1,14.9705627 1,10 L1,10 L1,10 C1,5.02943725 5.02943725,1 10,1 L10,1 Z\"></path>\n" + "                                    </svg>\n" + "                                    <span>" + option + "</span>\n" + "\n" + "\n" + "                                </label>\n";
                    radios += radio;
                }
                row += radios;
                row += "\n" + "\n" + "                            </div>\n" + "                        </div>\n" + "\n" + "\n" + "                    </div>\n" + "                </div>";

                this.appendRow(i,row)

            }
        }


        appendRow(index,row){

            let suffix="";
            if(index>=0 && index<=19)
                suffix="1";
            else if(index>=20 && index<=31)
                suffix="2";
            else if(index>=32 && index<=36)
                suffix="3";
            else if(index>=37 && index<=44)
                suffix="4";
            else if(index>=45 && index<=46)
                suffix="5";
            else if(index>=47 && index<=50)
                suffix="6";

            $jq(".fieldSet"+suffix+" #divTable").append(row);

        }

        saveAnswersToDatabase() {
            let result = Utils.fastAjax('WorkFlowAjaxFunc', 'saveSurveyAnswers', {
                answers: this.resultString
            });
            Utils.showModalMessage(result);
        }

        unSaved() {
            this.isSaved = false;
        };

        getAnswers() {
            this.resultString=[];
            for (let i = 0; i < this.listOfQuestions.length; i++) {
                let item = "q" + (i+1);
                let value = $jq("input[name='" + item + "']:checked").val();
                if (!value) value = 0; else value = parseInt(value) + 1;
                let obj = new Object();
                obj.question = item;
                obj.answer = value;
                this.resultString.push(obj);
            }
            /*console.log("result:", this.resultString); */                /*return resultString;*/
        };
        checkAnswers(){

            for (let i = 0; i < this.listOfQuestions.length; i++) {
                let value=this.resultString[i].answer;
                if (!value){
                    Utils.showModalMessage("سوال "+(i+1)+" پاسخ داده نشده است.");
                    return false;
                }

            }
            return true;
        }
        saveAnswers(){
            this.answers=this.getAnswers();
            if(!this.checkAnswers())
                return false;

            this.saveAnswersToDatabase();

            MainHeader.paneNavigator();
            $jq('#MAINHEADER-PANE-IMG, #FORMVIEW-PAGER, #MAINHEADER-ID, #FORMONLY-TOOLBAR-ID').show();
            $jq("td[onclick='eval(FormView.perArchive(undefined) )']").show();

            return true;

        }


    };
    var waitInterval = setInterval(function () {
        if (FormOnly && FormOnly.allFieldsContianer) {
            let instance = new mainClass();
            window.codeSet = instance;
            window.codeSet.loadForm();
        }
        clearInterval(waitInterval);
    }, 300);
};