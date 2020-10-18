listener = function (event) {
    var formload = setInterval(function () {
        $jq('.f-box *').removeAttr('title');
        $jq('.dateE input').prop('disabled', true);
        $jq('.dateE img').remove();
        formload2();
    }, 500);
    var formload2 = function () {
        clearInterval(formload);
    };
}