listener = function (event) {
    var price = FormView.myForm.getItemByName('Field_92').getData();
    while (price.indexOf(',') >= 0) price = price.replace(',', '');
    var len = price.length;
    var patt1 = /^\d+$/;
    reg = patt1.test(price);
    if (!reg) {
        price = price.substr(0, len - 1);
        FormView.myForm.getItemByName('Field_92').setData(price);
    }
    if (price && price.length > 3) {
        price = price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        FormView.myForm.getItemByName('Field_92').setData(price);
    }
}