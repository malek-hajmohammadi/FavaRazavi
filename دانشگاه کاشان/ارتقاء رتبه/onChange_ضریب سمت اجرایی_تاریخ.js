listener = function(event){

    if(FormView && FormView.myForm && FormView.myForm.getItemByName('Field_36').list && FormView.myForm.getItemByName('Field_36').list.myForm   ) {


        let firstDate = FormView.myForm.getItemByName('Field_36').list.myForm.getItemByName('Field_1').getData();
        let secondDate = FormView.myForm.getItemByName('Field_36').list.myForm.getItemByName('Field_2').getData();

        let dateObj = window.codeSet.divideDifferenceDate(firstDate, secondDate);

        FormView.myForm.getItemByName('Field_36').list.myForm.getItemByName('Field_4').setData(dateObj.diffDays);
        FormView.myForm.getItemByName('Field_36').list.myForm.getItemByName('Field_5').setData(dateObj.diffMonths);
        FormView.myForm.getItemByName('Field_36').list.myForm.getItemByName('Field_6').setData(dateObj.diffYears);
    }

}