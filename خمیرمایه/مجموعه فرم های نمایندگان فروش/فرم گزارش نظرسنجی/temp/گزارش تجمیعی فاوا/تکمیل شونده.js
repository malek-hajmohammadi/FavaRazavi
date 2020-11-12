this.jcode = function (self) {
    self.getReport = function () {
        var year = $jq('#reportYear').val();
        res = Utils.fastAjax('WorkFlowAjaxFunc', 'getFinancialReport', {year: year});
        $jq('#listContainer').html(res);
        self.createChart();
        self.createChart2();
    };
    self.myPie = null;
    self.myPie2 = null;
    self.createChart = function () {
        if ($jq('#chartData_Hazineh').length == 0 || $jq('#chartData_Hazineh').html().length == 0) {
            return;
        }
        var values_Hazineh = JSON.parse($jq('#chartData_Hazineh').html());
        if (!values_Hazineh || !Array.isArray(values_Hazineh)) {
            return;
        }
        var data_Hazineh = [];
        var lables = [];
        values_Hazineh.each(function (value) {
            lables[lables.length] = value.name;
            data_Hazineh[data_Hazineh.length] = parseInt(value.Foroosh);
        });
        var values_Foroosh = JSON.parse($jq('#chartData_Foroosh').html());
        var data_Foroosh = [];
        values_Foroosh.each(function (value) {
            data_Foroosh[data_Foroosh.length] = parseInt(value.Foroosh);
        });
        var config = {
            type: 'horizontalBar',
            data: {
                datasets: [{
                    data: data_Hazineh,
                    backgroundColor: 'rgb(255,0,0)',
                    label: 'هزينه'
                }, {data: data_Foroosh, backgroundColor: 'rgb(0,255,0)', label: 'فروش'}], labels: lables
            },
            options: {
                responsive: true,
                legend: {display: true, position: 'top'},
                scales: {
                    xAxes: [{
                        ticks: {
                            beginAtZero: true, userCallback: function (label, index) {
                                if (Math.floor(label) === label) {
                                    return label.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                }
                            }
                        }
                    }], yAxes: [{ticks: {fontSize: 16, fontFamily: 'B Nazanin'}}]
                }
            }
        };
        var ctx = document.getElementById('chart-area').getContext('2d');
        if (self.myPie) self.myPie.destroy();
        self.myPie = new Chart(ctx, config);
        $jq('#canvas-holder').css('margin', '0 auto');
    };
    self.createChart2 = function () {
        if ($jq('#chartData_SoodZiyan').length == 0 || $jq('#chartData_SoodZiyan').html().length == 0) {
            return;
        }
        var values = JSON.parse($jq('#chartData_SoodZiyan').html());
        if (!values || !Array.isArray(values)) {
            return;
        }
        var data = [];
        var data2 = [];
        var lables = [];
        values.each(function (value) {
            lables[lables.length] = value.name;
            var value = parseInt(value.SoodZiyan);
            if (value >= 0) {
                data[data.length] = value;
                data2[data2.length] = 0;
            } else {
                data[data.length] = 0;
                data2[data2.length] = value;
            }
        });
        var config = {
            type: 'horizontalBar',
            data: {
                datasets: [{data: data, backgroundColor: 'rgb(0,255,0)', label: 'سود'}, {
                    data: data2,
                    backgroundColor: 'rgb(255,0,0)',
                    label: 'زيان'
                }], labels: lables
            },
            options: {
                responsive: true,
                legend: {display: true, position: 'top'},
                scales: {
                    xAxes: [{
                        ticks: {
                            beginAtZero: true, userCallback: function (label, index) {
                                if (Math.floor(label) === label) {
                                    return label.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                }
                            }
                        }
                    }], yAxes: [{ticks: {fontSize: 16, fontFamily: 'B Nazanin'}}]
                }
            }
        };
        var ctx = document.getElementById('chart-area2').getContext('2d');
        if (self.myPie2) self.myPie2.destroy();
        self.myPie2 = new Chart(ctx, config);
        $jq('#canvas-holder2').css('margin', '0 auto');
    };
}

































if (config == 'listType') {
    switch (this.conf['listType']) {
        case '0' :
            this.tempArr = Main.getAddressBook();
            break;
        case '1':
            this.tempArr = Main.getAllAddressBookWithOwn();
            break;
        case '2':
            if (this.conf['listTypeUnit'] && parseInt(this.conf['listTypeUnit']) > 0) {
                var tempArr = Utils.fastAjax('Chart', 'getRolesByDept', {deptID: this.conf['listTypeUnit']});
                this.CMode.innerHTML = '';

                //sh_$$RAVAN876_
                if (this.beforeMode && this.beforeMode == "search")
                    this.senderPR = new developedAC(this.name + '.senderPR', this.CMode.id, this.CMode.id, tempArr, this.CMode.id + '-ROW', true, true, 'perrole', OrgDefine.getAllUsers(), OrgDefine.getAllRoles());
                else//end sh_$$RAVAN876_
                    this.senderPR = new PRCopyElement(this.name + '.senderPR', this.CMode.id, this.CMode.id, tempArr);
            }
            break;
        case '3':
            if (this.conf['listTypeGroup'] && parseInt(this.conf['listTypeGroup']) > 0) {
                var tempArr = Utils.fastAjax('Chart', 'getRolesByGroop', {groupID: this.conf['listTypeGroup']});
                this.CMode.innerHTML = '';

                //sh_$$RAVAN876_
                if (this.beforeMode && this.beforeMode == "search")
                    this.senderPR = new developedAC(this.name + '.senderPR', this.CMode.id, this.CMode.id, tempArr, this.CMode.id + '-ROW', true, true, 'perrole', OrgDefine.getAllUsers(), OrgDefine.getAllRoles());
                else//end sh_$$RAVAN876_
                    this.senderPR = new PRCopyElement(this.name + '.senderPR', this.CMode.id, this.CMode.id, tempArr);
            }
            break;
        case '4':
            if (this.conf['listTypePosition'] && parseInt(this.conf['listTypePosition']) > 0) {
                var tempArr = Utils.fastAjax('Chart', 'getRolesByPosition', {positionID: this.conf['listTypePosition']});
                this.CMode.innerHTML = '';

                //sh_$$RAVAN876_
                if (this.beforeMode && this.beforeMode == "search")
                    this.senderPR = new developedAC(this.name + '.senderPR', this.CMode.id, this.CMode.id, tempArr, this.CMode.id + '-ROW', true, true, 'perrole', OrgDefine.getAllUsers(), OrgDefine.getAllRoles());
                else//end sh_$$RAVAN876_
                    this.senderPR = new PRCopyElement(this.name + '.senderPR', this.CMode.id, this.CMode.id, tempArr);
            }
            break;
    }
}
