this.jcode = function (self) {
    self.getReport = function () {
        var searchFields = self.getSearchFields();
        res = Utils.fastAjax('WorkFlowAjaxFunc', 'warrantiesReport', searchFields);
        $jq('#listContainer').html(res);
        self.createChart();
    };
    self.pageNum = 1;
    self.myPie = null;
    self.createChart = function () {
        var randomScalingFactor = function () {
            return Math.round(Math.random() * 100);
        };
        var values = JSON.parse($jq('#chartData').html());
        var data = [0, 0, 0, 0, 0, 0];
        values.each(function (value) {
            var index = parseInt(value.Field_32);
            data[index] = parseInt(value.offerCount);
        });
        console.log(data);
        var config = {
            type: 'pie',
            data: {
                datasets: [{
                    data: data,
                    backgroundColor: [window.chartColors.blue, window.chartColors.purple, window.chartColors.yellow, window.chartColors.red, window.chartColors.green, window.chartColors.orange],
                    label: 'گزارش فرم هاي ضمانت نامه'
                }],
                labels: ['پيشنويس', 'تاييد رئيس حسابداري', 'تاييد مدير مالي', 'تاييد مدير حسابرسي و مجامع', 'تاييد معاون شركت ها', 'درحال صدور ضمانتنامه']
            },
            options: {responsive: true, legend: {display: true, position: 'right'}}
        };
        var ctx = document.getElementById('chart-area').getContext('2d');
        if (self.myPie) self.myPie.destroy();
        self.myPie = new Chart(ctx, config);
        $jq('#canvas-holder').css('margin', '0 auto');
    };
    self.getExcel = function () {
        var searchFields = self.getSearchFields();
        var param = "module=WorkFlowAjaxFunc&action=warrantiesReport&CSV=1";
        for (var key in searchFields) {
            param += '&' + key + '=' + encodeURI(searchFields[key]);
        }
        while (param.indexOf('+') >= 0) param = param.replace('+', '%2B');
        var url = "../Runtime/process.php?" + param;
        Utils.windowOpen(url);
    };
    self.getSearchFields = function () {
        var searchFields = {};
        if ($jq('#companyName').val().trim().length > 0) searchFields.companyName = $jq('#companyName').val().trim();
        if ($jq('#priceFrom').val().trim().length > 0) searchFields.priceFrom = $jq('#priceFrom').val().trim();
        if ($jq('#priceTo').val().trim().length > 0) searchFields.priceTo = $jq('#priceTo').val().trim();
        if ($jq('#checkNum1').val().trim().length > 0) searchFields.checkNum1 = $jq('#checkNum1').val().trim();
        if ($jq('#checkNum2').val().trim().length > 0) searchFields.checkNum2 = $jq('#checkNum2').val().trim();
        if ($jq('#checkDoc1').val().trim().length > 0) searchFields.checkDoc1 = $jq('#checkDoc1').val().trim();
        if ($jq('#checkDoc2').val().trim().length > 0) searchFields.checkDoc2 = $jq('#checkDoc2').val().trim();
        if ($jq('#loanType').val() != 0) searchFields.loanType = $jq('#loanType').val();
        if ($jq('#bankID').val() != 0) searchFields.bankID = $jq('#bankID').val();
        if ($jq('#acceptOne').val() != 0) searchFields.acceptOne = $jq('#acceptOne').val();
        if ($jq('#acceptTwo').val() != 0) searchFields.acceptTwo = $jq('#acceptTwo').val();
        if ($jq('#requestStatus').val() > 0) searchFields.requestStatus = $jq('#requestStatus').val();
        if ($jq('#requestDesc').val().trim().length > 0) searchFields.requestDesc = $jq('#requestDesc').val().trim();
        if ($jq('#descOne').val().trim().length > 0) searchFields.descOne = $jq('#descOne').val().trim();
        if ($jq('#descTwo').val().trim().length > 0) searchFields.descTwo = $jq('#descTwo').val().trim();
        var createDateFrom = FormOnly.allFieldsContianer[1].getData();
        if (createDateFrom.length > 0) searchFields.createDateFrom = createDateFrom;
        var createDateTo = FormOnly.allFieldsContianer[2].getData();
        if (createDateFrom.length > 0) searchFields.createDateTo = createDateTo;
        var pageNumber = parseInt($jq('#pageNumber').val());
        if (isNaN(pageNumber) || pageNumber < 1) pageNumber = 1;
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
};