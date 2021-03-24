listener = function (event) {

    let mainFormClass = {
        showTable: function () {
            var html = Utils.fastAjax('WorkFlowAjaxFunc', 'loadCreditTable');
            return html;
        },
        loadForm: function () {
            let html = this.showTable();
            $jq('#listContainer').html(html);
        },
        DetailedTable:{
           tableArray:[[]],
            isSaved:false,
            unSaved:function(){
               this.isSaved=false;
            },
            removeRow:function(index){
                $jq('.tableRow_' + index).remove();
                this.updateFrontAfterRemove();

            },
            updateFrontAfterRemove:function(){
                var lengthTable =  $jq('.detailedTable>tbody>tr[class^=\'tab\']').length;
                for (var i = 1; i <= lengthTable ; i++) {
                    $jq('.detailedTable >tbody > tr').eq(i).attr('class', 'tableRow_' + i);
                    $jq('.detailedTable>tbody>tr.tableRow_' + i + '>td:first ').html(i);
                    $jq('.detailedTable>tbody>tr.tableRow_' + i + '>td#tdDeleteImg>img').attr('onclick', 'FormOnly.codeSet.DetailedTable.removeRow(' + i + ')');
                }

            },
            addRow:function(){
                var lengthTable = $jq('.detailedTable > tbody > tr').length;
                var newTr = '<tr class="tableRow_' + (lengthTable ) + '">' + '<td style="padding: 2px;border: 1px solid #ccc;">' + (lengthTable ) + '</td>' +
                    '<td style="padding: 2px;border: 1px solid #ccc;"><input  onInput="FormOnly.codeSet.DetailedTable.unSaved()" type="text" name="productName" width="30px" value=""></td>' +
                    '<td style="padding: 2px;border: 1px solid #ccc;"><input onInput="FormOnly.codeSet.DetailedTable.unSaved()" type="text" name="productType" value=""></td>' +
                    '<td style="padding: 2px;border: 1px solid #ccc;"><input onInput="FormOnly.codeSet.DetailedTable.unSaved()" onkeyup="FormOnly.codeSet.DetailedTable.separateNum(this.value,this)"  dir="ltr" type="text" name="productPrice" min="0" value=""></td>' +

                    '<td id="tdDeleteImg" style="padding: 2px;background-color: #c5e1a5; border: 1px solid #ccc;">' + '<img style="cursor: pointer" onclick="FormOnly.codeSet.DetailedTable.removeRow(' + (lengthTable) + ')"' + 'src="gfx/toolbar/cross.png" />' + '</td>' +
                    '</tr>';
                $jq('.detailedTable > tbody > tr').eq(lengthTable - 1).after(newTr);
            },
            separateNum:function(value, input) {
                /* seprate number input 3 number */
                var nStr = value + '';
                nStr = nStr.replace(/\,/g, "");
                x = nStr.split('.');
                x1 = x[0];
                x2 = x.length > 1 ? '.' + x[1] : '';
                var rgx = /(\d+)(\d{3})/;
                while (rgx.test(x1)) {
                    x1 = x1.replace(rgx, '$1' + ',' + '$2');
                }
                if (input !== undefined) {

                    input.value = x1 + x2;
                } else {
                    return x1 + x2;
                }
            },
            saveList:function(){
                this.fillDetailedTableArray();
                if(this.considerSafety())
                    this.saveToDatabase();
            },
            fillDetailedTableArray:function(){
                this.tableArray=[[]];
                var length =  $jq('.detailedTable>tbody>tr[class^=\'tab\']').length;

                for (var count = 1; count <= length; count++) {
                    this.tableArray[count] = [];
                    this.tableArray[count][0] = $jq('.detailedTable>tbody>tr.tableRow_' + count + ' input[name=\'productName\'] ').val();
                    this.tableArray[count][1] = $jq('.detailedTable>tbody>tr.tableRow_' + count + ' input[name=\'productType\'] ').val();
                    this.tableArray[count][2] = $jq('.detailedTable>tbody>tr.tableRow_' + count + ' input[name=\'productPrice\'] ').val();
                }
            },
            considerSafety:function(){
               return true;
            },
            saveToDatabase:function(){
                var result = Utils.fastAjax('WorkFlowAjaxFunc', 'saveCreditList',{tableArray:this.tableArray});
                Utils.showModalMessage(result);
            }

        }
    };
    let instance=Object.create(mainFormClass);
    FormOnly.codeSet=instance;
    instance.loadForm();

};