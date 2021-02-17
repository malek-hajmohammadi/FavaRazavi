listener = function (event) {
    class mainClass {
        chartArray = [];

        loadForm() {
            window.roleArray = [];
            FormOnly.imgArray = [];
            this.defineTable();
            this.chartMaker(1);
        }

        defineTable() {
            let tagTable = '<table style="border: 1px solid #ccc;"><tbody class="tBody">' + '<tr id="trSec_1">' + '<td id="tdSec_1"><input id="inSec_1" type="text"></td>' + '<td id="tdChart_1"></td>' + '<td>' + '<img gettaborder="1" id="IMG-PLUS-SC" src="gfx/plus.png" style="cursor:pointer" onclick="window.codeSet.addRow(this)" tabindex="6001" >' + '</td>' + '</tr>' + '</tbody></table>';
            let td = $jq('td.tableContainer');
            td.append(tagTable);
        };

        chartMaker(rowNumber) {
            try {
                throw new Error()
            } catch (e) {
            }              /*ساخت فرم تكميلي شونده واحدها*/
            window.roleArray[rowNumber] = new actb('inSec_' + rowNumber, Main.getAccessInfo()['depts'], true, false);
            var name = 'FIELDPREVIEW_' + rowNumber;
            var nameDiv = name + '_div';
            var nameImg = 'FormOnly.imgArray[' + rowNumber + ']';
            var img = new Element('img', {
                src: 'gfx/chart.png',
                style: "cursor:pointer",
                onclick: "this.showChart(this)"
            });            /*eval('var '+ nameImg+' = img;');*/             /*let FIELDPREVIEW_1_img=img;*/
            let td = $jq('.tBody> tr#trSec_' + rowNumber + '>td[id^=tdChart]');
            td.append('<div style="display:none" id="' + nameDiv + '"><div id="' + nameDiv + '-ROW"></div></div>');              /*img.rPR = new developedAC(nameImg + '.rPR', nameDiv, nameDiv, Main.getCurrentSectriateUser(), nameDiv + '-ROW', true, true, 'perrole', OrgDefine.getAllUsers(), OrgDefine.getAllRoles());*/
            img.rPR = new developedAC(nameImg + '.rPR', nameDiv, nameDiv, Main.getAccessInfo()['depts'], nameDiv + '-ROW', true, true, 'role', OrgDefine.getAllUsers(), OrgDefine.getAllRoles());




            img.chartModalOk = function () {
                try {
                    throw new Error()
                } catch (e) {
                }
                var selectedNode = img.rPR.getSelectedChartTreeNodeField('RowType');
                console.log('rowId=', selectedNode.RowID);
                window.roleArray[rowNumber].setData(selectedNode.RowID, "inSec_" + rowNumber);
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
            FormOnly.imgArray[rowNumber] = img;
            td.append(FormOnly.imgArray[rowNumber]);               /*             window[nameImg]=img;              td.append(img);              /!*ساخت فيلد كاربر سمتي *!/             this.chartArray[rowNumber]= new developedAC(nameImg + '.rPR', nameDiv, nameDiv, Main.getCurrentSectriateUser(), nameDiv + '-ROW', true, true, 'perrole', OrgDefine.getAllUsers(), OrgDefine.getAllRoles());*/
        };

        addRow(selectedImg) {
            let itemIndex = $jq('.tBody> tr[id^=trSec]').length;
            let row = '<tr id="trSec_' + (itemIndex+1) + '">\n' +
                '<td id="tdSec_' + (itemIndex+1) + '"><input id="inSec_' + (itemIndex+1) + '" type="text"></td>' +
                '<td id="tdChart_' + (itemIndex+1) + '"></td>' +
                '<td>' +
                '<img gettaborder="1" id="IMG-PLUS-SC" src="gfx/plus.png" style="cursor:pointer" onclick="window.codeSet.addRow(this)" tabindex="6001" >' +
                '</td>' +
                '</tr>';
            let table = $jq('td.tableContainer>table>tbody');
            table.append(row);

            $jq(selectedImg).attr('src','gfx/minus.png');
            $jq(selectedImg).attr('onclick','window.codeSet.removeRow(this)');
            this.orderRows();
            this.chartMaker(itemIndex+1);

        };

        removeRow(object){

            $(object).closest('tr').remove();
            this.orderRows();

        }
        orderRows(){
            let length=$jq('.tBody> tr[id^=trSec]').length;

            for (var i = 1; i <= length ; i++) {
                $jq('.tBody>tr').eq(i-1).attr('id', 'trSec_' + i);

                $jq('.tBody>tr>td[id^=tdSec]').eq(i-1).attr('id', 'tdSec_' + i);
                $jq('.tBody>tr>td[id^=tdSec]>div').eq(i-1).attr('id', 'divSec_' + i);
                $jq('.tBody>tr>td[id^=tdSec]>div>input').eq(i-1).attr('id', 'inSec_' + i);
                $jq('.tBody>tr>td[id^=tdChart]').eq(i-1).attr('id', 'tdChart_' + i);

                $jq('.tBody>tr>td[id^=tdChart]>div').eq(i-1).attr('id', 'FIELDPREVIEW_'+i+'_div');
                $jq('.tBody>tr>td[id^=tdChart]>div>div').eq(i-1).attr('id', 'FIELDPREVIEW_'+i+'_div-ROW');


            }


        }


    }

    var waitInterval = setInterval(function () {
        if (FormOnly) {
            let instance = new mainClass();
            window.codeSet = instance;             /*window.codeSet=instance;*/
            window.codeSet.loadForm();
        }
        clearInterval(waitInterval);
    }, 300);
}




listener = function (event) {
    var waitInterval = setInterval(function () {
        if (FormView && FormView.myForm) {

            FormView.myForm.getItemByName('Field_0').loadForm();
        }

        clearInterval(waitInterval);
    }, 300);
}





listener =function(event){
    class mainClass {



        chartArray = [];


        loadForm() {

          window.roleArray=[];
          FormOnly.imgArray=[];
          this.defineTable();
          this.chartMaker(1);




        }

        defineTable() {
            let tagTable = '<table style="border: 1px solid #ccc;"><tbody class="tBody">' +
                '<tr id="trSec_1">' +
                '<td id="tdSec_1"><input id="inSec_1" type="text"></td>' +
                '<td id="tdChart_1"></td>' +
                '<td>' +
                '<img gettaborder="1" id="IMG-PLUS-SC" src="gfx/plus.png" style="cursor:pointer" onclick="window.codeSet.addRow(this)" tabindex="6001" >' +
                '</td>' +
                '</tr>' +
                '</tbody></table>';
            let td = $jq('td.tableContainer');
            td.append(tagTable);
        };

        chartMaker(rowNumber){

            try {
                throw new Error()
            } catch (e) {
            }

            /*ساخت فرم تکمیلی شونده واحدها*/
            window.roleArray[rowNumber]=new actb('inSec_'+rowNumber, OrgDefine.getAllRoles(), true, false);

            var name = 'FIELDPREVIEW_' + rowNumber;
            var nameDiv = name + '_div';
            var nameImg = name + '_img';

            var img = new Element('img', {
                src: 'gfx/chart.png',
                style: "cursor:pointer",
                onclick: "this.showChart(this)"
            });
           /*eval('var '+ nameImg+' = img;');*/
            /*let FIELDPREVIEW_1_img=img;*/
            FormOnly.imgArray[rowNumber]=img;





            let td = $jq('.tBody> tr#trSec_'+rowNumber+'>td[id^=tdChart]');
            td.append('<div style="display:none" id="' + nameDiv + '"><div id="' + nameDiv + '-ROW"></div></div>');

            /*img.rPR = new developedAC(nameImg + '.rPR', nameDiv, nameDiv, Main.getCurrentSectriateUser(), nameDiv + '-ROW', true, true, 'perrole', OrgDefine.getAllUsers(), OrgDefine.getAllRoles());*/
            FormOnly.imgArray[rowNumber].rPR = new developedAC('FormOnly.imgArray['+rowNumber+']' + '.rPR', nameDiv, nameDiv, Main.getCurrentSectriateUser(), nameDiv + '-ROW', true, true, 'perrole', OrgDefine.getAllUsers(), OrgDefine.getAllRoles());


            img.chartModalOk = function () {
                try {
                    throw new Error()
                } catch (e) {
                }
                var selectedNode = img.rPR.getSelectedChartTreeNodeField('RowType');

                console.log('rowId=',selectedNode.RowID);

                window.roleArray[rowNumber].setData(selectedNode.RowID,"inSec_"+rowNumber);

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
            td.append(img);


            /*
            window[nameImg]=img;

            td.append(img);

            /!*ساخت فیلد کاربر سمتی *!/
            this.chartArray[rowNumber]= new developedAC(nameImg + '.rPR', nameDiv, nameDiv, Main.getCurrentSectriateUser(), nameDiv + '-ROW', true, true, 'perrole', OrgDefine.getAllUsers(), OrgDefine.getAllRoles());*/

        };

    }

    var waitInterval = setInterval(function () {
        if (FormOnly) {

            let instance = new mainClass();
            window.codeSet = instance;
            /*window.codeSet=instance;*/
            window.codeSet.loadForm();
        }

        clearInterval(waitInterval);
    }, 300);

}








listener =function(event){
    class mainClass {

        roleArray = [];
        chartArray = [];


        loadForm() {

           /* this.defineTable();*/
           /* this.chartMakerV2(1);*/
        }



        chartMakerV2(rowNumber){

            /*ساخت فرم تکمیلی شونده واحدها*/
            this.roleArray[rowNumber]=new actb('inSec_'+rowNumber, OrgDefine.getAllRoles(), true, false);

            this.createChartImage(rowNumber);
            /* this.chartModalOk(rowNumber);*/


        }

        createChartImage(rowNumber){
            let td = $jq('.tBody> tr#trSec_'+rowNumber+'>td[id^=tdChart]');

            /*let self=this;*/


            var name = 'FIELDPREVIEW_' + rowNumber;
            var nameDiv = name + '_div';
            var nameImg = name + '_img';

            var img = new Element('img', {
                src: 'gfx/chart.png',
                style: "cursor:pointer",
                onclick: "this.showChart(this)"
            });
            eval(nameImg + ' = img;');

            td.append('<div style="display:none" id="' + nameDiv + '"><div id="' + nameDiv + '-ROW"></div></div>');

            img.rPR = new developedAC(nameImg + '.rPR', nameDiv, nameDiv, Main.getCurrentSectriateUser(), nameDiv + '-ROW', true, true, 'perrole', OrgDefine.getAllUsers(), OrgDefine.getAllRoles());

            img.chartModalOk = function () {
                try {
                    throw new Error()
                } catch (e) {
                }
                var selectedNode = img.rPR.getSelectedChartTreeNodeField('RowType');

                let inputId="inSec_"+self.rowNumber;
                console.log('rowId=',selectedNode.RowID,'inputId=',)



              /* self.roleArray[rowNumber].setData(selectedNode.RowID,"inSec_"+rowNumber);*/

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
            td.append(img);


            /*
            window[nameImg]=img;

            td.append(img);

            /!*ساخت فیلد کاربر سمتی *!/
            this.chartArray[rowNumber]= new developedAC(nameImg + '.rPR', nameDiv, nameDiv, Main.getCurrentSectriateUser(), nameDiv + '-ROW', true, true, 'perrole', OrgDefine.getAllUsers(), OrgDefine.getAllRoles());*/

        };

    }

    var waitInterval = setInterval(function () {
        if (FormOnly) {

            let instance = new mainClass();
            window.codeSet = instance;
            /*window.codeSet=instance;*/
            window.codeSet.loadForm();
        }

        clearInterval(waitInterval);
    }, 300);

}






















listener = function (event) {

    class mainClass {

        roleArray = [];
        chartArray=[];

        chartModalOk (rowNumber) {


            /*console.log('img',img);
            console.log('img',img.rPR);
            console.log('img',img.rPR.getSelectedChartTreeNodeField('RowType'));
            console.log('img',img.rPR.getSelectedChartTreeNodeField('RowType').RowID);*/





            var selectedNode = this.chartArray[rowNumber].getSelectedChartTreeNodeField('RowType'.RowID);


            console.log('selectdNode',selectedNode);

            this.roleArray[rowNumber].setData(selectedNode.RowID);

            this.unitArray;
            img.chartModal.close();
        };

        chartReset(rowNumber) {

            var name = 'FIELDPREVIEW_' + rowNumber;
            var nameImg = name + '_img';


            this.chartArray[rowNumber].chartModalOkBtn = new Button(nameImg + '.chartModalOkBtn', 'اضافه', nameImg + '.chartModalOk');
            var body = '<div style="border-width: 1px 1px 1px 1px; border-style: solid;';
            body += 'border-color: black; padding: 5px; background-color: #FFFFE2;';
            body += 'position: absolute; top: 30; left: 3.5%; width: 90%;';
            body += 'height: 68.5%; -moz-box-sizing: padding-box;overflow:auto" id="developedAC-CHART-MODAL-ID">';
            body += '</div>';
            body += '<div style="position: absolute; top: 80%; left: 2.2%; width: 92.1%;">';
            body += '<table width="100%"><tr><td align="center">' + this.chartArray[rowNumber].chartModalOkBtn.body + '</td></tr></table></div>';
            this.chartArray[rowNumber].chartModal.setBody(body);
            this.chartArray[rowNumber].rPR.createTree('showInModal');
        };
        chartCreate(rowNumber) {

            var name = 'FIELDPREVIEW_' + rowNumber;
            var nameImg = name + '_img';

            this.chartArray[rowNumber].chartModalOkBtn = new Button(nameImg + '.chartModalOkBtn', 'اضافه', nameImg + '.chartModalOk');
            this.chartReset(rowNumber);
        };
        chartShow(rowNumber) {
            var name = 'FIELDPREVIEW_' + rowNumber;
            var nameImg = name + '_img';

            this.chartArray[rowNumber].chartModal = new Modal(nameImg + '.chartModal', 'FIELDPREVIEW-CAHRT-MODAL', 50, 200, 400, 500, 'چارت سازماني', {
                create: this.chartCreate(rowNumber),
                reset: this.chartReset(rowNumber)
            });
            this.chartArray[rowNumber].chartModal.open();
        };
        createChartImage(rowNumber){
            let td = $jq('.tBody> tr#trSec_'+rowNumber+'>td[id^=tdChart]');

            var name = 'FIELDPREVIEW_' + rowNumber;
            var nameDiv = name + '_div';
            var nameImg = name + '_img';

            var img = new Element('img', {
                src: 'gfx/chart.png',
                style: "cursor:pointer",
                onclick: "window.codeSet.chartShow("+rowNumber+")"
            });

            td.append('<div style="display:none" id="' + nameDiv + '"><div id="' + nameDiv + '-ROW"></div></div>');
             window[nameImg]=img;

            td.append(img);

            /*ساخت فیلد کاربر سمتی */
            this.chartArray[rowNumber]= new developedAC(nameImg + '.rPR', nameDiv, nameDiv, Main.getCurrentSectriateUser(), nameDiv + '-ROW', true, true, 'perrole', OrgDefine.getAllUsers(), OrgDefine.getAllRoles());

        };







        chartMaker(rowNumber) {

            try {
                throw new Error()
            } catch (e) {
            }


            var field = FormOnly.allFieldsContianer[1];

            var name = 'FIELDPREVIEW_' + field.db.fieldid;
            var nameDiv = name + '_div';
            var nameImg = name + '_img';

            var td = $$('#FORMONLY-DOCTYPE-FIELDS-' + field.db.fieldid)[0];
            var td = $$('.chartClass')[0];

            var img = new Element('img', {
                src: 'gfx/chart.png',
                style: "cursor:pointer",
                onclick: "this.showChart(this)"
            });

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
        chartMakerV2(rowNumber){



            /*ساخت فرم تکمیلی شونده واحدها*/
            this.roleArray[rowNumber]=new actb('inSec_'+rowNumber, OrgDefine.getAllRoles(), true, false);

            this.createChartImage(rowNumber);



           /* this.chartModalOk(rowNumber);*/



        }

        defineTable() {
            let tagTable = '<table style="border: 1px solid #ccc;"><tbody class="tBody">' +
                '<tr id="trSec_1">' +
                '<td id="tdSec_1"><input id="inSec_1" type="text"></td>' +
                '<td id="tdChart_1"></td>' +
                '<td>' +
                '<img gettaborder="1" id="IMG-PLUS-SC" src="gfx/plus.png" style="cursor:pointer" onclick="window.codeSet.addRow(this)" tabindex="6001" >' +
                '</td>' +
                '</tr>' +
                '</tbody></table>';
            let td = $jq('td.tableContainer');
            td.append(tagTable);
            this.chartMakerV2(1);
        };

        addRow(selectedImg) {
            let itemIndex = $jq('.tBody> tr[id^=trSec]').length;
            let row = '<tr id="trSec_' + (itemIndex+1) + '">\n' +
                '<td id="tdSec_' + (itemIndex+1) + '"><input id="inSec_' + (itemIndex+1) + '" type="text"></td>' +
                '<td id="tdChart_' + (itemIndex+1) + '"></td>' +
                '<td>' +
                '<img gettaborder="1" id="IMG-PLUS-SC" src="gfx/plus.png" style="cursor:pointer" onclick="window.codeSet.addRow(this)" tabindex="6001" >' +
                '</td>' +
                '</tr>';
            let table = $jq('td.tableContainer>table>tbody');
            table.append(row);

           $jq(selectedImg).attr('src','gfx/minus.png');
           $jq(selectedImg).attr('onclick','window.codeSet.removeRow(this)');
           this.orderRows();
            this.chartMakerV2(itemIndex+1);

        };

        removeRow(object){

            $(object).closest('tr').remove();
            this.orderRows();

        }
        orderRows(){
            let length=$jq('.tBody> tr[id^=trSec]').length;

            for (var i = 1; i <= length ; i++) {
                $jq('.tBody>tr').eq(i-1).attr('id', 'trSec_' + i);

                $jq('.tBody>tr>td[id^=tdSec]').eq(i-1).attr('id', 'tdSec_' + i);
                $jq('.tBody>tr>td[id^=tdSec]>div').eq(i-1).attr('id', 'divSec_' + i);
                $jq('.tBody>tr>td[id^=tdSec]>div>input').eq(i-1).attr('id', 'inSec_' + i);
                $jq('.tBody>tr>td[id^=tdChart]').eq(i-1).attr('id', 'tdChart_' + i);

                $jq('.tBody>tr>td[id^=tdChart]>div').eq(i-1).attr('id', 'FIELDPREVIEW_'+i+'_div');
                $jq('.tBody>tr>td[id^=tdChart]>div>div').eq(i-1).attr('id', 'FIELDPREVIEW_'+i+'_div-ROW');


            }


        }


        loadForm() {

           this.defineTable();
        }


    };

    var waitInterval = setInterval(function () {
        if (FormOnly) {

            let instance = new mainClass();
            window.codeSet = instance;
            /*window.codeSet=instance;*/
            window.codeSet.loadForm();
        }

        clearInterval(waitInterval);
    }, 300);


}












   /* self.addRowTest = function () {
        var itemIndex = $jq('.itemsTable > tbody > tr[id^=dataTr]').length;
        htmlTr = '<tr  id="dataTr_' + itemIndex + '">\n' +
            '        <td style="padding: 2px;border: 1px solid #ccc;" >' + '</td>\n' +
            '        <td style="padding: 2px;border: 1px solid #ccc;" id="userTD_' + itemIndex + '" data-id=""></td>\n' +
            '        <td style="padding: 2px;border: 1px solid #ccc;"><div id="startDate_' + itemIndex + '"></div></td>\n' +
            '        <td style="padding: 2px;border: 1px solid #ccc;"><div id="endDate_' + itemIndex + '"></div></td>\n' + ' ' +
            '        <td style="padding: 2px;border: 1px solid #ccc;" >\n' + ' <input onInput="FormOnly.allFieldsContianer[0].changeSum(' + itemIndex + ')" class="f-input" type="text" id="shareCount_' + itemIndex + '" value="" style="width: 40px;font-size: 16px;" />\n' + ' </td>\n' +
            '        <td style="padding: 2px;border: 1px solid #ccc;" >\n' + ' <input onInput="FormOnly.allFieldsContianer[0].changeSumTashrif('+ itemIndex + ')" class="f-input" type="text" id="shareCountTashrif_' + itemIndex + '" value="" style="width: 40px;font-size: 16px;" />\n' + ' </td>\n' +
            '        <td  style="padding: 2px;border: 1px solid #ccc;" >\n' + ' <input readonly class="f-input" type="text" id="shareCountSum_' + itemIndex + '" value="" style="width: 40px;font-size: 16px;background-color: gainsboro;" />\n' + ' </td>\n' +
            '        <td  style="padding: 2px;border: 1px solid #ccc;" >\n' + ' <input  readonly class="f-input" type="text" id="shareCountTashrifSum_' + itemIndex + '" value="" style="width: 40px;font-size: 16px;background-color: gainsboro;" />\n' + ' </td>\n' +
            '        <td class="remove" style="padding: 2px;border: 1px solid #ccc;"><img onclick="FormOnly.allFieldsContianer[0].removeRow(' + itemIndex + ')" src="gfx/toolbar/cross.png" style="cursor: pointer;" /></td>\n' +
            '    </tr>';
        $jq('.itemsTable > tbody').append(htmlTr);
        $jq('.itemsTable > tbody > tr[id=dataTr_' + itemIndex + '] > td:first-child').html(itemIndex + 1);          /!*initialize*!/
        $jq('#shareCount_' + itemIndex).val(0);
        $jq('#shareCountTashrif_' + itemIndex).val(0);
        $jq('#shareCountSum_' + itemIndex).val(0);
        $jq('#shareCountTashrifSum_' + itemIndex).val(0);

        self.setDateobjectOneForAdd(itemIndex);



    };
    self.removeRowTest = function (index) {

        $jq('#dataTr_' + index).remove();
        self.updateFrontAfterRemove();
        self.updateObjectsAfterRemove(index);
    };*/

