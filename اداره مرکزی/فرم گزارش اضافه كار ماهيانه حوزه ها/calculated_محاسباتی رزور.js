this.jcode = function (self) {
    self.search = function (fw) {
        if (FormView.myForm != undefined && FormView.myForm.getItemByName('Field_0') != undefined && FormView.myForm.getItemByName('Field_2') != undefined && FormView.myForm.getItemByName('Field_3') != undefined && FormView.myForm.getItemByName('Field_4') != undefined) {
            var p = 0;
            var res1 = FormView.myForm.getItemByName('Field_0').getData();
            var y = FormView.myForm.getItemByName('Field_3').getData();
            var m = FormView.myForm.getItemByName('Field_4').getData();
            if (y == 0) y = 1399; else y = parseInt(y) + 1399;
            m = parseInt(m) + 1;
            var res2 = Utils.fastAjax('WorkFlowAjaxFunc', 'monthlyovertime', {
                pid: res1,
                mid: FormView.docID,
                m: m,
                y: y,
                t: 'del'
            });
            FormView.myForm.getItemByName('Field_2').list.subListView.listview.refresh();
            var stTypeObj = fw.myForm.getItemByName('Field_0');
            var pos = stTypeObj.ac.actb_ids.indexOf(stTypeObj.getData());
            if (pos >= 0) {
                var txt = stTypeObj.ac.actb_keywords[pos];
                fw.myForm.getItemByName('Field_20').setData(txt);
            }
        }
    };
    self.add = function (fw) {
        if (FormView.myForm != undefined && FormView.myForm.getItemByName('Field_2') != undefined && FormView.myForm.getItemByName('Field_19') != undefined && FormView.myForm.getItemByName('Field_3') != undefined && FormView.myForm.getItemByName('Field_4') != undefined) {
            var p = 0;
            var res1 = FormView.myForm.getItemByName('Field_19').getData();
            while (res1.length < 8) {
                res1 = '0' + res1;
            }
            var y = FormView.myForm.getItemByName('Field_3').getData();
            var m = FormView.myForm.getItemByName('Field_4').getData();
            if (y == 0) y = 1399; else y = parseInt(y) + 1399;
            m = parseInt(m) + 1;
            var res2 = Utils.fastAjax('WorkFlowAjaxFunc', 'monthlyovertime', {
                pid: res1,
                mid: FormView.docID,
                m: m,
                y: y
            });
            FormView.myForm.getItemByName('Field_2').list.subListView.listview.refresh();
            FormView.myForm.getItemByName('Field_19').setData('');
        }
    };
    self.checkSaghf = function (fw) {
        window.tempFormSubListDataArray = new Array();
        window.tempFormSubListDataArraycnt = 0;
        window.tempFormSubListDataArraycntMsgs = '';
        $(FormView.myForm.getItemByName('Field_2').list.subListView.gridInfo.owner.id).select('div[iamfowner]').each(function (item) {
            var dataArray = window.tempFormSubListDataArray;
            dataArray[dataArray.length] = item.tagthis.getData();
            var info = item.getAttribute('iamfowner').split(',');
            var cnt = window.tempFormSubListDataArraycnt;
            if (info[1] == 12) {
                var mojavez = dataArray[11];
                var saghf = dataArray[10];
                var Tatil = dataArray[8];
                var gheyrTatil = dataArray[7];
                if (mojavez.length > 0) {
                    var newValue = '' + (parseInt('0' + gheyrTatil) + parseInt('0' + Tatil));
                    item.tagthis.setData(newValue);
                    if (parseInt('0' + saghf) > 0) {
                        if (parseInt('0' + gheyrTatil) + parseInt('0' + Tatil) > parseInt('0' + saghf)) {
                            window.tempFormSubListDataArraycntMsgs += 'رديف ' + (cnt + 1) + ' سقف اضافه كار رعايت نشده است.' + '<br>';
                        }
                    }
                } else {
                    if (gheyrTatil != "" || Tatil != "") {
                        window.tempFormSubListDataArraycntMsgs += 'رديف ' + (cnt + 1) + ' مجوز ندارد و نبايد اضافه كار اين رديف ويرايش شود.' + '<br>';
                    }
                }
                window.tempFormSubListDataArray = new Array();
                window.tempFormSubListDataArraycnt++;
            }
        });
        if (window.tempFormSubListDataArraycntMsgs == '') {
            return true;
        } else {
            Utils.showModalMessage(window.tempFormSubListDataArraycntMsgs);
            return false;
        }
    };
    self.checkMojavez = function (fw) {
        try {
            var cnt = 0;
            var fid = fw.myForm.getItemByName('Field_2').db.fieldid;
            fw.myForm.owner.select('td[rowid="' + fid + '"] tr.b-inbox-row').each(function (obj) {
                cnt++;
                var mojavez = obj.select('input[type="text"]')[11].value;
                if (mojavez.length > 0) {
                    var inp = obj.select('input[type="text"]')[8];
                    inp.disabled = false;
                    obj.select('input[type="text"]')[7].disabled = false;
                }
            });
        } catch (ee) {
            console.log('catch on checkMojavez');
        }
    };
}