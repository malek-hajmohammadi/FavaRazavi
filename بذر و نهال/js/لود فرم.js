listener = function (event) {
    setTimeout(function () {
        $jq('.f-box *').removeAttr('title');
        FormOnly.allFieldsContianer[9].LoadJS();
    }, 500);
}