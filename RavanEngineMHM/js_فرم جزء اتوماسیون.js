/*به روز کردن فرم جزء*/
FormView.myForm.getItemByName('Field_2').list.subListView.listview.refresh();
/*
* کی نیاز هست که فرم جزء رو رفرش کنیم ؟
*
*
* */

/*دسترسی به المان های زیر فرم*/
/*این دوتا چه فرقی با هم دارند؟*/


FormView.myForm.getItemByName('Field_12').list.subListView.listview.data;

FormView.myForm.getItemByName('Field_15').list.myForm.getItemByName('Field_12');


/*مخفی سازی سطر کامل زیر فرم پس از لود فرم جزء، علت گذاشتن تایمر این است که در ابتدا فرم جزء لود نشده و باید در دوره های زمانی منظم لود شدنش چک شود*/

int1=setInterval(function(){
    if(FormView.myForm.getItemByName('Field_11').list.myForm){
        for (var j=0;j<hidden_rows.length;j++){
            id=FormView.myForm.getItemByName('Field_11').list.myForm.getItemByName('Field_0').idCount;
            form=FormView.myForm.getItemByName('Field_11').list.myForm;
            form.CMode.select('[mosmenu="CMenu-'+id+'"]')[0].setStyle("display:none");
        }
        clearInterval(int1);
    }
},50);

/*مخفی کردن یک ستون از لیست زیرفرم*/

var subfid = FormView.myForm.getItemByName('Field_11').list.targetID;
var hd = document.getElementById(subfid+'-TEMP_BODY');
var hd2 = document.getElementById(subfid+'-TEMP_HEAD');
hd2.childNodes[0].childNodes[4].setStyle("display:none!important");
for (var i = 0; i < hd.childNodes.length; i++) {
    hd.childNodes[i].childNodes[4].setStyle("display:none!important");
}

/*مخفی کردن سطر از لیست فرم جزء*/
var subfid=FormView.myForm.getItemByName('Field_8').list.targetID;
document.getElementById(subfid+'-TEMP_BODY').childNodes[0].setStyle('display:none !important');

/*درسترسی به مقادیر لیست زیرفرم*/
FormView.myForm.getItemByName('Field_11').list.subListView.data[0].DMSFields[0].value
/*
مقدار فیلد اول از سطر اول*/

/*تغییر مقادیر فیلد ها در لیست فرم جزء*/
$(FormView.myForm.getItemByName('Field_2').list.subListView.gridInfo.owner.id).select('div[iamfowner]').each(function(item){

    /* get cell row and column index in array, first value is row index and second value is column index */
    var info = item.getAttribute('iamfowner').split(',');
    if(info[0] == 5 && info[1] == 12)
        item.tagthis.setData(newValue);

});

/*رسیدن فیلدهای فرم جز در حالت بسته*/
FormView.myForm.getItemByName('Field_11').list.subListView.data[0].DMSFields[0].value;

/*رسیدن فیلدهای فرم جز در حالت باز*/
FormView.myForm.getItemByName('Field_50').list.myForm.getItemByName('Field_2').getData();

/*
نمونه کد پیاده سازی شده برای فرم جز (اعمال در فیلد محاسباتی فرم اصلی)*/


this.jcode = function (self) {
    try {
        self.Joz = function () {
            var JozID = FormView.myForm.getItemByName('Field_21').list.targetID;
            var b = 'none'; /* Joz Baste */
            if ($(JozID + '-ADD-MODAL') != null) b = $(JozID + '-ADD-MODAL').style.display; /* Joz Baz */
            if (b == 'none') {
                window.tedad = 0;
                window.vahed = 0;
                window.mablagh = 0;
                window.mablaghKol = 0;
                $(FormView.myForm.getItemByName('Field_21').list.subListView.gridInfo.owner.id).select('div[iamfowner]').each(function (item) {
                    if (item.tagthis.conf.lable == 'تعداد') window.tedad = item.tagthis.getData();
                    if (item.tagthis.conf.lable == 'اعتبار واحد') window.vahed = item.tagthis.getData();
                    window.mablagh = window.tedad * window.vahed;
                    if (item.tagthis.conf.lable == 'مبلغ كل') {
                        item.tagthis.setData(window.mablagh);
                        window.mablaghKol = window.mablaghKol + window.mablagh;
                    }
                });
                FormView.myForm.getItemByName('Field_42').setData(window.mablaghKol);
            } else {
                var tedad = FormView.myForm.getItemByName('Field_21').list.myForm.getItemByName('Field_2').getData();
                var vahed = FormView.myForm.getItemByName('Field_21').list.myForm.getItemByName('Field_3').getData();
                var mablagh = tedad * vahed;
                FormView.myForm.getItemByName('Field_21').list.myForm.getItemByName('Field_6').setData(mablagh);
            }
        };
    } catch (cc) {
        console.log(cc);
    }
}


/*تعداد ردیف های فرم جز*/
FormView.myForm.getItemByName('Field_9').list.subListView.listview.dataCount.count;

/*مقداردهی به فیلدهای داخل لیست فرم جزء*/
$jq('#inputID').closest('div[iamfowner]')[0].tagthis.setData(value);

