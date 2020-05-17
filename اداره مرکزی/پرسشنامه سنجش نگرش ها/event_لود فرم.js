listener = function (event) {
    var state = 0;
    var waitInterval = setInterval(function () {
        if (FormOnly ) {
            FormOnly.allFieldsContianer[0].loadForm();
        }
        clearInterval(waitInterval);
    }, 300);
};
