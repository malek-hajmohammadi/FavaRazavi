listener = function (event) {
    var waitInterval = setInterval(function () {
        if (FormOnly && FormOnly.allFieldsContianer[5]) {

            FormOnly.allFieldsContianer[5].LoadJS();
        }

        clearInterval(waitInterval);
    }, 300);
}
