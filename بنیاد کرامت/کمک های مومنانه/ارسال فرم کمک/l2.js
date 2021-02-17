listener = function (event) {
    try {
        throw new Error()
    } catch (e) {
    }

    var form = event.target.tagthis;

    form.chartMaker = function (fName, form) {
        try {
            throw new Error()
        } catch (e) {
        }
        var field = form.getItemByName(fName);

        var name = 'FIELDPREVIEW_' + field.db.fieldid;
        var nameDiv = name + '_div';
        var nameImg = name + '_img';
        var td = $$('#FIELDPREVIEW-' + field.db.fieldid)[0];
        var img = new Element('img', {src: 'gfx/chart.png', style: "cursor:pointer", onclick: "this.showChart(this)"});
        eval(nameImg + ' = img;');
        td.insert('<div style="display:none" id="' + nameDiv + '"><div id="' + nameDiv + '-ROW"></div></div>');
        img.rPR = new developedAC(nameImg + '.rPR', nameDiv, nameDiv, Main.getCurrentSectriateUser(), nameDiv + '-ROW', true, true, 'perrole', OrgDefine.getAllUsers(), OrgDefine.getAllRoles());
        img.chartModalOk = function () {
            try {
                throw new Error()
            } catch (e) {
            }
            var selectedNode = img.rPR.getSelectedChartTreeNodeField('RowType');
            field.setData(selectedNode.RowID);
            img.chartModal.close();
        };
        var reset = function () {
            try {
                throw new Error()
            } catch (e) {
            }

            img.chartModalOkBtn = new Button(nameImg + '.chartModalOkBtn', 'اضافه', nameImg + '.chartModalOk');
            var body = '<div style="border-width: 1px 1px 1px 1px; border-style: solid;';
            body += 'border-color: black; padding: 5px; background-color: #FFFFE2;';
            body += 'position: absolute; top: 30; left: 3.5%; width: 90%;';
            body += 'height: 68.5%; -moz-box-sizing: padding-box;overflow:auto" id="developedAC-CHART-MODAL-ID">';
            body += '</div>';
            body += '<div style="position: absolute; top: 80%; left: 2.2%; width: 92.1%;">';
            body += '<table width="100%"><tr><td align="center">' + img.chartModalOkBtn.body + '</td></tr></table></div>';
            img.chartModal.setBody(body);
            img.rPR.createTree('showInModal');
        };
        var create = function () {
            try {
                throw new Error()
            } catch (e) {
            }
            img.chartModalOkBtn = new Button(nameImg + '.chartModalOkBtn', 'اضافه', nameImg + '.chartModalOk');
            reset();
        };
        img.showChart = function (img) {
            img.chartModal = new Modal(nameImg + '.chartModal', 'FIELDPREVIEW-CAHRT-MODAL', 50, 200, 400, 500, 'چارت سازماني', {
                create: create,
                reset: reset
            });
            img.chartModal.open();
        };
        td.insert(img);
    };
    try {
        throw new Error()
    } catch (e) {
    }
    if (FormOnly) {
        form.chartMaker('Field_2', form);
        form.chartMaker('Field_13', form);
    }
}