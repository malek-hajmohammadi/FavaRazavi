listener = function (event) {

    class mainClass {
        tableArray = [[]];
        isSaved = false;
        pNameArray = [];

        showTable() {
            var html = Utils.fastAjax('WorkFlowAjaxFunc', 'loadCreditTable');
            return html;
        };

        loadForm() {
            let html = this.showTable();
            $jq('#listContainer').html(html);
            this.setDataObjectAll();

        };


        removeRow(index) {
            $jq('.tableRow_' + index).remove();
            this.updateFrontAfterRemove();


        };

        updateFrontAfterRemove() {
            var lengthTable = $jq('.detailedTable>tbody>tr[class^=\'tab\']').length;
            for (var i = 1; i <= lengthTable; i++) {
                $jq('.detailedTable >tbody > tr').eq(i).attr('class', 'tableRow_' + i);
                $jq('.detailedTable>tbody>tr.tableRow_' + i + '>td:first ').html(i);
                $jq('.detailedTable>tbody>tr.tableRow_' + i + '>td#tdDeleteImg>img').attr('onclick', 'FormOnly.codeSet.DetailedTable.removeRow(' + i + ')');
            }

        };

        addRow() {
            var lengthTable = $jq('.detailedTable > tbody > tr').length;
            var newTr = '<tr class="tableRow_' + (lengthTable) + '">' + '<td style="padding: 2px;border: 1px solid #ccc;">' + (lengthTable) + '</td>' +
                '<td id="pName_' + lengthTable + '" style="padding: 2px;border: 1px solid #ccc;"><input id="inputPName_' + lengthTable + '"   type="text" name="presentationName" width="30px" value=""></td>' +
                '<td style="padding: 2px;border: 1px solid #ccc;"><input onkeyup="window.codeSet.separateNum(this.value,this)" type="text" name="presentationMax" value=""></td>' +

                '<td id="tdDeleteImg" style="padding: 2px;background-color: #c5e1a5; border: 1px solid #ccc;">' + '<img style="cursor: pointer" onclick="window.codeSet.removeRow(' + (lengthTable) + ')"' + 'src="gfx/toolbar/cross.png" />' + '</td>' +
                '</tr>';
            $jq('.detailedTable > tbody > tr').eq(lengthTable - 1).after(newTr);
            this.setDateobjectOneForAdd(lengthTable);
        };

        setDateobjectOneForAdd(index) {
            /*this.pNameArray[index] = new Per_Role('window.codeSet.pNameArray[' + index + ']', 'pName_' + index, Main.getActiveCurrentSectriateUser());*/

            var tempArr = Utils.fastAjax('Chart', 'getRolesByGroop', {groupID: 16});
            this.pNameArray[index]= new PRCopyElement('window.codeSet.pNameArray[' + index + ']', 'pName_' + index,'pName_' + index, tempArr);


            $jq("td#pName_"+index+" #inputPName_"+index).css("display","none");

            let value=$jq("td#pName_"+index+" #inputPName_"+index).val();
            value = value.split(",");
            this.pNameArray[index].setData([{uid: value[0],rid:value[1]}]);

            $jq("td#pName_"+index+" img").css("display","none");

        };

        setDataObjectAll() {

            var lengthTable = $jq('.detailedTable > tbody > tr').length;
           /* lengthTable=lengthTable-1; */

            for (var i = 1; i < lengthTable; i++) {
                this.setDateobjectOneForAdd(i);

            }
        }


        separateNum(value, input) {
            /* seprate number input 3 number */
            var nStr = value + '';
            nStr = nStr.replace(/\,/g, "");
            let x = nStr.split('.');
            let x1 = x[0];
            let x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }
            if (input !== undefined) {

                input.value = x1 + x2;
            } else {
                return x1 + x2;
            }
        };

        saveList() {
            this.fillDetailedTableArray();
            if (this.considerSafety())
                this.saveToDatabase();
        };

        fillDetailedTableArray() {
            this.tableArray = [[]];
            var length = $jq('.detailedTable>tbody>tr[class^=\'tab\']').length;

            for (var count = 1; count <= length; count++) {
                this.tableArray[count] = [];


                this.tableArray[count][0] =this.pNameArray[count].getData();

                this.tableArray[count][1] = $jq('.detailedTable>tbody>tr.tableRow_' + count + ' input[name=\'presentationMax\'] ').val();
            }
        };

        considerSafety() {
            return true;
        };

        saveToDatabase() {
            var result = Utils.fastAjax('WorkFlowAjaxFunc', 'saveCreditTable', {tableArray: this.tableArray});
            Utils.showModalMessage(result);
        };


    };

    var waitInterval = setInterval(function () {
        if (FormOnly) {

            let instance = new mainClass();
            window.codeSet = instance;
            window.codeSet.loadForm();
        }

        clearInterval(waitInterval);
    }, 300);

};
