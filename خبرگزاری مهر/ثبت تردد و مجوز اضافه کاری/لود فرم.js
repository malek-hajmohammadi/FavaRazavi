listener = function (event) {
    $jq('#FORMBUILDER-FORM-MAINDIV *').removeAttr('title');
    FormView.myForm.getItemByName('Field_12').RunJS();
}