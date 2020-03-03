/*-----------------------کد منبع داده برای لیست تکمیل شونده (لیست دوره ها)-------------------------*/
Class.create({
    load: function (self) {
        try {
            return FormView.myForm.getItemByName('Field_4').setListForCourses();
        } catch (e) {
            console.log(e);
        }
        return [[0, 'دوره اي نيست']];
    }
})

/*-------------------------------------------------------*/

/*-------------------------- کد در فیلد محاسباتی فرم اصلی------------------------------*/
this.jcode = function (self) {
    self.listFromAjax = [];
    self.getlist = function () {
        try {
            self.listFromAjax = Utils.fastAjax('WorkFlowAjaxFunc', 'courses');
        } catch (e) {
            console.log(e);
        }
    };
    self.setListForCourses = function () {
        if (self.listFromAjax.length == 0) {
            self.getlist();
        }
        var arrayForField = [];
        for (var i = 0; i < self.listFromAjax.length; i++) {
            var text = self.listFromAjax[i]['class_name'];
            while (text && text.indexOf(String.fromCharCode(1705)) >= 0) text = text.replace(String.fromCharCode(1705), String.fromCharCode(1603));
            while (text && text.indexOf(String.fromCharCode(1740)) >= 0) text = text.replace(String.fromCharCode(1740), String.fromCharCode(1610));
            arrayForField[i] = [self.listFromAjax[i]['class_id'], text];
        }
        return arrayForField;
    };
    self.setStartDate = function (classId) {
        for (var i = 0; i < self.listFromAjax.length; i++) {
            if (self.listFromAjax[i]['class_id'] == classId) return self.listFromAjax[i]['startdate'];
        }
        return 'پيدا نشد';
    };
    self.setDuration = function (classId) {
        for (var i = 0; i < self.listFromAjax.length; i++) {
            if (self.listFromAjax[i]['class_id'] == classId) return self.listFromAjax[i]['duration'];
        }
        return 'پيدا نشد';
    };
    self.checkValidity=function(){
        let validCount=1;
        if(FormView.myForm.getItemByName('Field_11').list.subListView.data.length>=validCount)
            return true;
        else
            return false;

    }
}
/*----------------------------کد در لود فرم اصلی---------------------------*/


listener = function (event) {
    var countLoader = 0;
    var waitInterval = setInterval(function () {
        if (FormView && FormView.myForm) {
            FormView.myForm.getItemByName('Field_4').getlist();
            console.log(countLoader++);
        }
        clearInterval(waitInterval);
    }, 300);
}
/*------------------------------------------------------------------*/
/*----------------------------------رویداد تغییر مقدار در تکمیل شونده فرم جزء-------------------------------*/

listener = function (event) {
    var classId = FormView.myForm.getItemByName('Field_11').list.myForm.getItemByName('Field_0').getData();
    FormView.myForm.getItemByName('Field_11').list.myForm.getItemByName('Field_2').setData(FormView.myForm.getItemByName('Field_4').setDuration(classId));
    FormView.myForm.getItemByName('Field_11').list.myForm.getItemByName('Field_1').setData(FormView.myForm.getItemByName('Field_4').setStartDate(classId));

}

/*-------------------------کد در عملیات های کارتابل زمان تایید------------------------------*/
this.actJS = function (self) {
    let IsItOk=FormView.myForm.getItemByName('Field_4').checkValidity();
    if(IsItOk)
    return true;
    else{
        alert("حداقل یک دوره باید انتخاب شود !");
        return false;
    }
}

/*-----------------------موقتی---------------------*/
Class.create({
    load: function (self) {
        try {
            var res = Utils.fastAjax('WorkFlowAjaxFunc', 'courses');
            var counter = 0;
            var arrayForField = new Array();
            for (var i = 0; i < res.length; i++) {
                arrayForField[i] = [res[i]['class_id'], res[i]['class_name'].toString()];
            }
            return arrayForField;
        } catch (e) {
            console.log(e);
        }
        return;
    }
})
/*-------------------------------*/
Class.create({
    load: function (self) {
        self.showMSG("اطلاعات ورودی قابل قبول نیست");
        return [[0, "no data"]];
    }
})