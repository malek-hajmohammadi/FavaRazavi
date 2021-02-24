listener = function (event) {
    class mainClass {
        chartArray = [];
        pointer=1;

        loadForm() {
            window.roleArray = [];
            FormOnly.imgArray = [];

            this.defineTable();
            this.chartMaker(this.pointer);
            this.pointer++;
        }

        defineTable() {
            let tagTable = '<table style="border: 1px solid #ccc;"><tbody class="tBody">' + '<tr id="trSec_1">' + '<td id="tdSec_1"><input style="width: 300px;" id="inSec_1" type="text"></td>' + '<td id="tdChart_1"></td>' + '<td>' + '<img gettaborder="1" id="IMG-PLUS-SC" src="gfx/plus.png" style="cursor:pointer" onclick="window.codeSet.addRow(this)" tabindex="6001" >' + '</td>' + '</tr>' + '</tbody></table>';
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
            let row = '<tr id="trSec_' + (this.pointer) + '">\n' +
                '<td id="tdSec_' + (this.pointer) + '"><input style="width: 300px;" id="inSec_' + (this.pointer) + '" type="text"></td>' +
                '<td id="tdChart_' + (this.pointer) + '"></td>' +
                '<td>' +
                '<img gettaborder="1" id="IMG-PLUS-SC" src="gfx/plus.png" style="cursor:pointer" onclick="window.codeSet.addRow(this)" tabindex="6001" >' +
                '</td>' +
                '</tr>';
            let table = $jq('td.tableContainer>table>tbody');
            table.append(row);

            $jq(selectedImg).attr('src','gfx/minus.png');


            $jq(selectedImg).attr('onclick','window.codeSet.removeRow(this,'+(this.pointer-1)+')');
            /*this.orderRows();*/
            this.chartMaker(this.pointer);
            this.pointer++;

        };

        removeRow(object,itemIndex){

            $(object).closest('tr').remove();
            window.roleArray[itemIndex]=-1;

        };

        clickOnSendBtn(){
           if(this.checkValidationBeforeSending()){
               this.sendHelpFormsToSelectedGroups();
           }
        };
        checkValidationBeforeSending(){
            let title=FormOnly.allFieldsContianer[0].getData();

            if(title.length<5) {
                Utils.showModalMessage('لطفا عنوان طرح را وارد نماييد');
                return false;
            }

            window.arrayDept=[];

            let columnCounter=1;
            for(i=1;i<this.pointer;i++) {

                if (window.roleArray[i] != -1) {
                    let depId = window.roleArray[i].getData();

                    if (depId == 0) {
                        Utils.showModalMessage('ستون ' + columnCounter + 'خالی است ');
                        return false;
                    }
                    var index = window.arrayDept.indexOf(depId);
                    if (index == -1)
                        arrayDept.push(depId);
                    else {
                        Utils.showModalMessage('ستون ' + columnCounter + 'تکراری است ');
                        return false;
                    }
                    columnCounter++;
                }
            }

            return true;

        };

        sendHelpFormsToSelectedGroups(){
            alert("Malek");
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



