listener = function(event){
    var index=FormView.myForm.getItemByName('Field_46').getData();
    FormView.myForm.getItemByName('Field_49').setValuesOfSabeghEtebar(index-1);

};