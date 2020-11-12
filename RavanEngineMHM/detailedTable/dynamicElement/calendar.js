self.setDateobjectOne = function (index) {
    var dateValue = $jq('#birthdayDate_' + index + ' input').val(); /*FormView.allFieldsContianer[0].birthdayDates[i] = new EditCalendar('FormOnly.allFieldsContianer[0].birthdayDates[' + i + ']', 'startDate_' + i);*/
    FormView.myForm.getItemByName('Field_21').birthdayDates[index] = new EditCalendar('FormView.myForm.getItemByName(\'Field_21\').birthdayDates[' + index + ']', 'birthdayDate_' + index);
    if (dateValue.length > 7) {
        FormView.myForm.getItemByName('Field_21').birthdayDates[index].setDate(dateValue);
        if(self.showMode=="readOnly")
            self.setReadOnly(index);
    }
};

