listener = function (event) {
    var waitInterval = setInterval(function () {
        if (FormOnly && FormOnly.allFieldsContianer[0]) {
            var html = FormOnly.allFieldsContianer[0].showTable();
            $jq('#listContainer').html(html);
            FormOnly.allFieldsContianer[0].setDateObjectAll();
            FormOnly.allFieldsContianer[0].initializeSum();
        }
        clearInterval(waitInterval);
    }, 30);
};