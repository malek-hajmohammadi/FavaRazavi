listener = function (event) {
    if (FormOnly.allFieldsContianer && FormOnly.allFieldsContianer.length > 0) FormOnly.allFieldsContianer[0].getReport();
    $jq('#FORMONLY-FIELDS-CONTAINER').animate({scrollTop: '0px'}, 2000, function () {
        $jq('#FORMONLY-FIELDS-CONTAINER').animate({scrollTop: '200px'}, 4000);
    });
}