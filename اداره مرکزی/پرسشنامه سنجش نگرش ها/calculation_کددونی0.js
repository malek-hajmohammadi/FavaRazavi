this.jcode = function (self) {
    self.listOfQuestions = [];
    self.tagQuestion = [];
    self.htmlToAdd = "";
    self.resultString=[] ;
    self.loadForm = function () {
        self.fillListOfQuestions();
        self.createTagQuestions();

    };
    self.fillListOfQuestions = function () {
        self.listOfQuestions.push("کار من کار معمولی، ساده و غیر جذاب است و به همین دلیل برایم خیلی جالب نیست(R).");
        self.listOfQuestions.push("کار من کاری کسل کننده و خسته کننده است(R).");
        self.listOfQuestions.push("کار من کاری مفید و سودمند برای جامعه است.");
        self.listOfQuestions.push("کار من تلاش برانگیز و خشنودکننده است.");
        self.listOfQuestions.push("هر وقت بخواهم می توانم به مافوقم رجوع کنم و او همیشه در دسترس است.");
        self.listOfQuestions.push("مافوقم در حیطه کاری به من آزادی عمل می دهد.");
        self.listOfQuestions.push("مافوقم کار خوب را تحسین و تشویق می کند.");
        self.listOfQuestions.push("مافوقم فردی صمیمی و خوش‌قلب است و آداب معاشرت را رعایت و حرمت زیردستان را نگه می دارد.");
        self.listOfQuestions.push("مافوقم در جریان مسائل و اوضاع زیرمجموعه خود قرار دارد و در کارش خبره، ماهر و باهوش است.");
        self.listOfQuestions.push("مافوقم فردی کم‌حوصله، یک‌دنده، لجباز، عصبانی و تندمزاج است(R).");
        self.listOfQuestions.push("مافوقم وقتی کاری را به من می سپارد آن را به‌روشنی شرح می دهد.");
        self.listOfQuestions.push("حقوق ماهیانه ام کمتر از استحقاق من بوده و غیرمنصفانه است(R).");
        self.listOfQuestions.push("حقوق ماهیانه ام متناسب با تلاش و زحمت من نیست(R).");
        self.listOfQuestions.push("حقوق دریافتی ام برای پرداخت مخارج عادی زندگی‌ام کافی است.");
        self.listOfQuestions.push("حقوق ماهیانه ام با توجه به بودجه و امکانات آستان قدس رضوی، عادلانه  است.");
        self.listOfQuestions.push("حقوق و مزایای دریافتی من در آستان قدس رضوی به‌خوبی سازمان‌های مشابه می باشد.");
        self.listOfQuestions.push("آستان قدس رضوی دارای سیستم پرداخت خوبی برای کارکنان است و حقوق و مزایای کافی در اختیار کارکنان قرار می‌دهد ");
        self.listOfQuestions.push("همکارانم احساس مسئولیت دارند و فعال و سخت کوش هستند.");
        self.listOfQuestions.push("همکارانم افرادی پرحرف، مزاحم،  ناسازگار، ستیزه جو، کج‌خلق و تنگ‌نظرند(R).");
        self.listOfQuestions.push("همکارانم انسان های بلندنظر، باگذشت، امین، رازنگهدار، وفادار و مورد اعتمادند.");
        self.listOfQuestions.push("همکارانم افرادی تنبل و کم‌کار، کسل کننده و غیر صمیمی هستند(R).");
        self.listOfQuestions.push("همکارانم مشوق من هستند و در من انگیزه کار ایجاد می کنند.");
        self.listOfQuestions.push("شغلم موجب افزایش دانش و تخصص‌ من می‌شود.");
        self.listOfQuestions.push("در شغل من ترفیعات و ارتقاء برحسب قابلیت و توانایی کارکنان به آنان  ارائه می‌شود");
        self.listOfQuestions.push("خط‌مشی و سیاست‌های ارتقا و انتصاب در آستان قدس رضوی عادلانه و منصفانه است.");
        self.listOfQuestions.push("جایگاه شغلی ام متناسب با شایستگی ها و قابلیت هایم نیست(R).");
        self.listOfQuestions.push("در آستان قدس رضوی اگر کارکنان به‌خوبی به وظایفشان عمل کنند  ترفیعات و امتیازات متناسب را دریافت می‌کنند");
        self.listOfQuestions.push("در شغل من امکان پیشرفت، رشد و ترفیع  به‌طور مطلوبی وجود دارد");
        self.listOfQuestions.push("فضای کار و اتاق کاری من مناسب است.");
        self.listOfQuestions.push("محیط کاری من، ازنظر سروصدا مناسب است.");
        self.listOfQuestions.push("تجهیزات اداری (میز، صندلی و لوازم و ...) در محیط کار من در حد مناسبی است.");
        self.listOfQuestions.push("محیط کاری من، از نور و تهویه مناسبی برخوردار است.");
        self.listOfQuestions.push("ساختمانی که در آن کار می‌کنم ازنظر تجهیزات (آسانسور، راهروها، فضای رستوران و ...) از کیفیت مناسبی برخوردار است.");
        self.listOfQuestions.push("احساس وابستگی عاطفی نسبت به آستان قدس رضوی، دارم.");
        self.listOfQuestions.push("بسیار مشتاقم تا مابقی عمر شغلی خودم را در آستان قدس رضوی بگذرانم.");
        self.listOfQuestions.push("در حال حاضر، قصد ترک آستان قدس را ندارم، چراکه نسبت به افراد درون آن احساس تعلق دارم.");
        self.listOfQuestions.push("در حال حاضر ترک این سازمان، مشکلات خانوادگی زیادی برایم  ایجاد می نماید.");
        self.listOfQuestions.push("ترک این سازمان در شرایط فعلی ازلحاظ اقتصادی برایم بسیار پرهزینه است ");
        self.listOfQuestions.push("اگر آستان قدس را ترک کنم، شغل دیگری پیدا نمی کنم و منافع و مزایای زیادی را از دست می دهم.");
        self.listOfQuestions.push("احساس تکلیف اخلاقی می کنم که در آستان قدس رضوی بمانم.");
        self.listOfQuestions.push("اگر یک شغل بهتر در سازمانی دیگر به من پیشنهاد شود، احساس می کنم درست نیست که آن را بپذیرم.");
        self.listOfQuestions.push("به نظر من وفاداری به آستان قدس رضوی، یک ارزش است و رفتن از این سازمان به سازمانی دیگر کار درستی نیست.");
        self.listOfQuestions.push("بعضی وقت‌ها به فکر کار کردن در سازمان‌ها/ شرکت‌های دیگر می‌افتم(R).");
        self.listOfQuestions.push("در 12 ماه گذشته به صفحات فرصت‌های شغلی روزنامه‌ها و سایت‌ها نگاه کرده‌ام(R).");
        self.listOfQuestions.push("قصد دارم مدت کوتاهی در آستان قدس کارکنم و بعد اینجا را ترک می‌کنم(R).");
        self.listOfQuestions.push("یقین دارم که سازمان محل خدمتم را هیچ‌وقت ترک نخواهم کرد.");
        self.listOfQuestions.push("در شش ماه گذشته سرحال بوده و کاملاً احساس بهبودی و تندرستی می‌کنم.");
        self.listOfQuestions.push("در شش ماه گذشته احساس ضعف، بی‌حالی ، بی‌رمقی و مریض‌حالی نداشته ام.");
        self.listOfQuestions.push("در ماه های اخیر براثر نگرانی و دل‌شوره مشکل پرخوابی یا مشکل به خواب رفتن نداشته‌ام.");
        self.listOfQuestions.push("در ماه های اخیر احساس نکرده ام که تحت استرس و فشار قرار دارم.");
        self.listOfQuestions.push("در شش ماه گذشته کارهایم را به‌خوبی انجام داده  و از کیفیت و نحوه انجام کارهایم خشنود بوده ام.");
        self.listOfQuestions.push("تصمیم هایی که اخیراً گرفته ام به‌گونه‌ای بوده است که احساس مفید بودن، شایستگی و لیاقت می‌کنم");
        self.listOfQuestions.push("در این اواخر از فعالیت های روزانه‌ام لذت می برم و افکاری حاکی از بی ارزش بودن را تجربه نکرده ام.");
        self.listOfQuestions.push("در این اواخر احساس کرده ام که حوصله، شوق و علاقه لازم جهت انجام کارهایم را ندارم(R).");
        self.listOfQuestions.push("در ماه های اخیر احساس می کنم افکار منفی و نومیدکننده به ذهن من خطور می کند(R).");
        self.listOfQuestions.push("وقتی‌که کار زيادي وجود دارد، من تا دیروقت کار می‌کنم(R).");
        self.listOfQuestions.push("مافوق‌ها و همکارانم مرا به‌عنوان کسي که خیلی سخت کار می‌کند، توصيف می‌کنند(R).");
        self.listOfQuestions.push("ازلحاظ زماني، بسيار شتاب‌زده عمل می‌کنم و وقتي منتظر کسي می‌شوم، صبر و تحمل ندارم(R). ");
        self.listOfQuestions.push("وقتي کارها آن‌طور که من می‌خواهم پيش نمی‌رود، خونسرد هستم.");
        self.listOfQuestions.push("وقتی‌که افراد كار را کامل و بی‌نقص انجام نمی‌دهند، عصباني نمی‌شوم.");
        self.listOfQuestions.push("من زمان و انرژي بيشتري را در کارم نسبت به معاشرت با دوستان و خانواده‌ام صرف می‌کنم(R).");
        self.listOfQuestions.push("من ترجيح می‌دهم که خودم کارهايم را انجام دهم به‌جای اينکه درخواست کمک نمايم(R).");
        self.listOfQuestions.push("بعد از کار به‌اندازه‌ای خسته می‌شوم که نمی‌توانم کاري براي خانواده‌ام انجام دهم(R).");
        self.listOfQuestions.push("ساعات کار من به‌گونه‌ای است که نمی‌توانم وقت کافي براي زندگی صرف کنم(R).");
        self.listOfQuestions.push("وقتي از سرکار به خانه‌بر می‌گردم، انرژي زیادی برای انجام کار در خانه‌دارم.");
        self.listOfQuestions.push("وظایفی که در خانه به عهده دارم به نحوی است که تداخلی با کارم در سازمان نداشته و می‌توانم کارم را به نحو احسن انجام دهم.");
        self.listOfQuestions.push("ساعات کاري من مناسب است و زمان مفیدی براي اعضای خانواده‌ام صرف می‌کنم.");
        self.listOfQuestions.push("وقتي از سرکار برمی‌گردم به دليل خستگي، نقش‌های زندگی  را خوب ايفا نمی‌کنم(R).");
        self.listOfQuestions.push("استرس در کار و مشکلات کاري، تمرکز مرا در زندگی به هم نمی‌زند.");
        self.listOfQuestions.push("قبلاً خيلي به کارم اهميت مي‏ دادم ولي در حال حاضر چيزهاي ديگري براي من مهم شده-اند(R).");
        self.listOfQuestions.push("من تا زماني در سرکار مي‏ مانم که آن را تمام کنم، حتي اگر در آن ساعت پولي به من پرداخت نشود.");
        self.listOfQuestions.push("هنگامی‌که، مشغول به کار می‌شوم، گذشت زمان را حس نمی‌کنم و آنچه در پیرامونم رخ می‌دهد را فراموش می‌کنم.");
        self.listOfQuestions.push("شغلی که به آن مشغولم یکی از بهترین و موثرترین موارد رضایت من از زندگی است");
        self.listOfQuestions.push("شروع کار در صبح زود بهترين حالت براي من است و دوست دارم سریع‌تر در محل کار حاضر شوم.");
        self.listOfQuestions.push("معمولاً کمي زود به سرکار مي‏ روم تا همه‌چیز را آماده کنم.");
        self.listOfQuestions.push("من با شغلم زندگي مي‏ کنم و هنگامی‌که به‌سختی کار می‌کنم، خوشحالم.");
        self.listOfQuestions.push("انتظاراتی که آستان قدس رضوی از من دارد را به‌خوبی می‌دانم.");
        self.listOfQuestions.push("همه منابع و تجهیزات لازم برای انجام  درست‌کارها را در اختیاردارم.");
        self.listOfQuestions.push("هرروز در  محیط کاری فرصت این رادارم تا کارهایم را به بهترین نحو ممکن انجام دهم.");
        self.listOfQuestions.push("در هفته گذشته برای انجام درست و به‌موقع کارهایم از من قدردانی شده است.");
        self.listOfQuestions.push("مافوق مستقیم و برخی از همکارانم به من اهمیت می‌دهند.");
        self.listOfQuestions.push(" در آستان قدس رضوی افرادی هستند که مرا برای توسعه خودم تشویق می‌کنند.");
        self.listOfQuestions.push("به نظر می‌آید نظرات من برای آستان قدس رضوی مهم است.");
        self.listOfQuestions.push("هدف آستان قدس این است که احساس کنم شغل مهمی دارم.");
        self.listOfQuestions.push("همکاران و زیردستانم به انجام  کار باکیفیت متعهد هستند.");
        self.listOfQuestions.push("در محیط کارم یک دوست صمیمی دارم.");
        self.listOfQuestions.push("در شش ماه گذشته به‌منظور پیشرفتم در محیط کار، با من صحبت‌هایی شده است.");
        self.listOfQuestions.push("در سال گذشته فرصت‌های رشد و یادگیری خوبی در کارم داشته‌ام ");
        self.listOfQuestions.push("آستان قدس رضوی واقعاً مراقب سلامتی و آرامش من هست.");
        self.listOfQuestions.push("اهداف و ارزش‌های من برای آستان قدس اهمیت دارد.");
        self.listOfQuestions.push("اگر نیاز به کمک خاصی داشته باشم، روی آستان قدس رضوی حساب می‌کنم.");
        self.listOfQuestions.push("آستان قدس در حین بروز مشکلات شخصی، مرا یاری می‌کند.");
        self.listOfQuestions.push("آستان قدس رضوی اشتباهات سهوی مرا می‌بخشد.");
        self.listOfQuestions.push("سازمانم از من فقط به‌عنوان ابزار استفاده می‌کند(R).");
        self.listOfQuestions.push("من موفقیت‌های آستان قدس را موفقیت خودم می‌دانم.");
        self.listOfQuestions.push("ارزش‌های من و ارزش‌های آستان قدس رضوی، بسیار مشابه هستند.");
        self.listOfQuestions.push("هنگامی‌که کسی  یا رسانه‌ای  از آستان قدس تعریف می‌کند، مانند این است که مرا موردتمجید قرار داده است.");
        self.listOfQuestions.push("عضو آستان قدس رضوی بودن، مايه مباهات و افتخار من بوده و به‌خوبی بیانگر پایگاه اجتماعی من است.");
        self.listOfQuestions.push("من نسبت به سرنوشت آستان قدس رضوی حساسم و به همین دلیل برداشت سایرین از این سازمان برایم مهم است");
        self.listOfQuestions.push("من آستان قدس را در قالب خانواده بزرگي که به آن احساس تعلق دارم توصيف می‌کنم.");
        self.listOfQuestions.push("کارکنان وقتي با فردی خصومت پیدا می‌کنند، شايعات دروغ در مورد او ترويج مي‌دهند(R).");
        self.listOfQuestions.push("وقتي همکارانم عصباني هستند با مراجعين بدخلقی مي‌كنند(R).");
        self.listOfQuestions.push("كاركنان براي ضربه زدن به آستان قدس، عمداً وظايف خود را به تأخیر مي‌اندازند(R).");
        self.listOfQuestions.push("افراد براي تخريب سازمان، منابع آن را بيهوده مصرف نمي‌كنند و عمداً دست به تخريب اموال این سازمان مي‌زنند(R).");
        self.listOfQuestions.push("کاركنان، سرپرستان مستقيم خود را دور مي‌زنند و اطلاعات نادرست به مقامات مافوق مي‌دهند(R).");
        self.listOfQuestions.push("هیچ‌وقت افراد مقررات آستان قدس رضوی را زير پا نمي‌گذارند و مدارك سازمان را به بيرون انتقال نمي‌دهند.");
        self.listOfQuestions.push("بيشتر به جنبه‌هاي مثبت شغلم توجه مي‌كنم تا جنبه‌هاي منفي آن.");
        self.listOfQuestions.push("فعاليت‌هايي خارج از حیطه وظايفم انجام مي‌دهم كه به بهبود تصوير بيروني آستان قدس رضوی كمك مي‌كند. ");
        self.listOfQuestions.push("كاركنان سعي مي‌کنند تا از بروز مشکلات در رابطه با همكاران جلوگيري كنند.");
        self.listOfQuestions.push("به درخواست‌هاي ارباب‌رجوع، سريع پاسخ مي‌دهم.");
        self.listOfQuestions.push("مشتاقانه تمايل دارم تا وقتم را در اختيار همکارانم قرار دهم و به همكارانم كمك ‌كنم. ");
        self.listOfQuestions.push("همكارانم به درخواست براي ارائه اطلاعات و گزارش‌ها، سریعاً پاسخ مي‌دهند.");
        self.listOfQuestions.push("جذب و به‌کارگیری افراد در آستان قدس رضوی بر اساس شایستگی‌های تخصصی و اخلاقی اتفاق می‌افتد. ");
        self.listOfQuestions.push("تقسیم‌کارها و وظایف در محل کار من، تا حد زیادی عادلانه است. ");
        self.listOfQuestions.push("آستان قدس رضوی از منظر سلامت اداری در جایگاه خوبی قرار دارد.");
        self.listOfQuestions.push("من با اهداف و مأموریت‌های آستان قدس رضوی آشنایی دارم.");
        self.listOfQuestions.push("وقتی برای بهبود روش‌های معمول انجام کار تلاش می‌کنم، مورد تحسین و تقدیر واقع می‌شوم");
        self.listOfQuestions.push("این فرصت رادارم تا ایده‌های شغلی خودم را قبل از ارائه به مسئول بالاتر مورد آزمون قرار دهم");
        self.listOfQuestions.push("نظر و پیشنهاد من در مورد نحوه انجام کارهایی که بر عهده دارم بسیار مؤثر است");
        self.listOfQuestions.push("آستان قدس رضوی از نوآوری در انجام کار و ایده‌های خلاقانه حمایت می‌کند.");
        self.listOfQuestions.push("به نظر من در رده‌های مختلف سازمان، بین مدیران ارشد اصلاً هماهنگی وجود ندارد و هرکسی ساز خود را می‌زند.");


    };
    self.createTagQuestions = function () {
        var i;
        for (i = 1; i <= self.listOfQuestions.length; i++) {

            let row = " <div class=\"divTableRow\">\n" +
                "                    <div class=\"divTableCell\">\n" +
                "                        <div class=\"radif\" style=\"float: right\">" + i + "</div>\n" +
                "\n" +
                "                    </div>\n" +
                "                    <div class=\"divTableCell removeBorderLeft\">\n" +
                "                        <div class=\"titleQuestion\" style=\"float: right\">" + self.listOfQuestions[i - 1] + "\n" +
                "                        </div>\n" +
                "\n" +
                "\n" +
                "                    </div>\n" +
                "                    <div class=\"divTableCell removeBorderRight\">\n" +
                "                        <div class=\"containerRadio\">\n" +
                "                            <div class=\"cntr\">\n";
            let j;
            let radios = "";
            for (j = 0; j <= 5; j++) {
                let radio = "                                <label class=\"btn-radio\" for=\"S2R" + i + "O" + j + "\">\n" +
                    "\n" +
                    "\n" +
                    "                                    <input id=\"S2R" + i + "O" + j + "\" name=\"rGroupS2R" + i + "\" tabindex=\"6001\" value=\"" + j + "\" type=\"radio\">\n" +
                    "                                    <svg height=\"20px\" viewBox=\"0 0 20 20\" width=\"20px\">\n" +
                    "                                        <circle cx=\"10\" cy=\"10\" r=\"9\"></circle>\n" +
                    "                                        <path class=\"inner\"\n" +
                    "                                              d=\"M10,7 C8.34314575,7 7,8.34314575 7,10 C7,11.6568542 8.34314575,13 10,13 C11.6568542,13 13,11.6568542 13,10 C13,8.34314575 11.6568542,7 10,7 Z\"></path>\n" +
                    "                                        <path class=\"outer\"\n" +
                    "                                              d=\"M10,1 L10,1 L10,1 C14.9705627,1 19,5.02943725 19,10 L19,10 L19,10 C19,14.9705627 14.9705627,19 10,19 L10,19 L10,19 C5.02943725,19 1,14.9705627 1,10 L1,10 L1,10 C1,5.02943725 5.02943725,1 10,1 L10,1 Z\"></path>\n" +
                    "                                    </svg>\n" +
                    "                                    <span>" + j + "</span>\n" +
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

    };

    self.saveQuestioner = function () {
        let resCheck = self.fillAnswers();
        if (resCheck == 0) {

            var userId=Main["FirstPageParameters"]["userInfo"]['id'];
            var dep=FormOnly.allFieldsContianer[1].getData();
            Utils.fastAjax('WorkFlowAjaxFunc', 'saveQuestionerNegaresh',{userId:userId,questionerList:JSON.stringify(self.resultString),dep:dep});
            Utils.showModalMessage('با تشکر، پاسخنامه شما با موفقیت ثبت شد');
        } else {
            Utils.showModalMessage('لطفا به آیتم ردیف' + resCheck + 'پاسخ دهید');
        }
    };


    self.fillAnswers= function () {

        for (i = 1; i <= 5; i++) {
            let item="rGroupS1R"+i;
            let value = $jq("input[name='"+item+"']:checked").val();
            if (!value) value = 9;

            let obj=new Object();
            obj.question=item;
            obj.answer=value;
            self.resultString.push(obj);

        }
        for (i = 1; i <= self.listOfQuestions.length; i++) {
            let item="rGroupS2R"+i;
            let value = $jq("input[name='"+item+"']:checked").val();
            if (!value) value = 9;

            let obj=new Object();
            obj.question=item;
            obj.answer=value;
            self.resultString.push(obj);
        }
        return 0;
    };

};
