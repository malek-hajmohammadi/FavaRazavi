listener = function (event) {
    if (FormView.myForm.getItemByName('Field_28').getData() == true)
        $jq('.chekviwe10').css('display', '');
    else
        $jq('.chekviwe10').css('display', 'none');
};