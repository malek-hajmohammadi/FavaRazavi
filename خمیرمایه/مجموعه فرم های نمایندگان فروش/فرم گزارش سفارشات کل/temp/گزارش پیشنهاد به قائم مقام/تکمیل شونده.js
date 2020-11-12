this.jcode = function (self) {
    self.getReport = function () {
        var searchFields = self.getSearchFields();
        res = Utils.fastAjax('WorkFlowAjaxFunc', 'getOffersToDeputy', {searchFields:searchFields});
        $jq('#listContainer').html(res);
        if (!self.offerUser) self.offerUser = new Per_Role('FormOnly.allFieldsContianer[0].offerUser', 'offerUser', Main.getActiveCurrentSectriateUser());
        $jq('#offerUser input').addClass('f-input');
        self.createChart();
    };
    self.offerUser = null;
    self.pageNum = 1;
    self.myPie = null;

    self.getExcel = function () {
        var searchFields = self.getSearchFields();
        var param = "module=WorkFlowAjaxFunc&action=getOffersToDeputy&CSV=1";
        for (var key in searchFields) {
            param += '&' + key + '=' + encodeURI(searchFields[key]);
        }
        while (param.indexOf('+') >= 0) param = param.replace('+', '%2B');
        var url = "../Runtime/process.php?" + param;
        Utils.windowOpen(url);
    };
    self.getSearchFields = function () {
        var searchFields = new Object();
        if (FormOnly.allFieldsContianer[0].offerUser && FormOnly.allFieldsContianer[0].offerUser.getData() != "0,-1")
            searchFields.offerUser = FormOnly.allFieldsContianer[0].offerUser.getData();

        if ($jq('#offerEID').val().trim().length > 0) searchFields.offerEID = $jq('#offerEID').val().trim();

        if ($jq('#offerGender').val() != 2) searchFields.offerGender = $jq('#offerGender').val();
        if ($jq('#offerWorkType').val() != 10) searchFields.offerWorkType = $jq('#offerWorkType').val();
        var offerDept = FormOnly.allFieldsContianer[3].getData();
        if (parseInt(offerDept) > 0) searchFields.offerDept = offerDept;
        if ($jq('#offerNumber').val().trim().length > 0) searchFields.offerNumber = $jq('#offerNumber').val().trim();
        if ($jq('#offerStatus').val() > 0) searchFields.offerStatus = $jq('#offerStatus').val();
        var runDateForecast = FormOnly.allFieldsContianer[4].getData();
        if (runDateForecast.length > 0) searchFields.runDateForecast = runDateForecast;
        if ($jq('#offerTitle').val().trim().length > 0) searchFields.offerTitle = $jq('#offerTitle').val().trim();
        if ($jq('#offerDesc').val().trim().length > 0) searchFields.offerDesc = $jq('#offerDesc').val().trim();
        if ($jq('#offerMethodOfRun').val().trim().length > 0) searchFields.offerMethodOfRun = $jq('#offerMethodOfRun').val().trim();
        var createDateFrom = FormOnly.allFieldsContianer[1].getData();
        if (createDateFrom.length > 0) searchFields.createDateFrom = createDateFrom;
        var createDateTo = FormOnly.allFieldsContianer[2].getData();
        if (createDateFrom.length > 0) searchFields.createDateTo = createDateTo;
        if ($jq('#offerActionDesc').val().trim().length > 0) searchFields.offerActionDesc = $jq('#offerActionDesc').val().trim();
        if ($jq('#offerActionResults').val().trim().length > 0) searchFields.offerActionResults = $jq('#offerActionResults').val().trim();
        var pageNumber = parseInt($jq('#pageNumber').val());
        if (pageNumber < 1) pageNumber = 1;
        searchFields.pageNumber = pageNumber;
        return searchFields;
    };
    self.prevPage = function () {
        var pageNumber = parseInt($jq('#pageNumber').val());
        if (pageNumber > 1) {
            pageNumber--;
            $jq('#pageNumber').val(pageNumber);
            self.getReport();
        }
    };
    self.nextPage = function () {
        var pageNumber = parseInt($jq('#pageNumber').val());
        var maxPage = parseInt($jq('#maxPage').val());
        if (pageNumber < maxPage) {
            pageNumber++;
            $jq('#pageNumber').val(pageNumber);
            self.getReport();
        }
    }
}