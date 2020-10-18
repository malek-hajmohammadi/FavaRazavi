listener = function (event) {
    let mainFormClass = {
        showTable: function () {
            var html = Utils.fastAjax('WorkFlowAjaxFunc', 'loadListOrderRepresentation');
            return html;
        }, loadForm: function () {
            this.changeInputAttributeOfRepresentation();
            this.DetailedTable.getDefinedProducts();
            let html = this.showTable();
            $jq('.detailedTableSpan').html(html);
            this.Questioner.makeQuestioner();
        }, changeInputAttributeOfRepresentation: function () {
            $jq('.repInfo >input').attr("readonly", "true");
            $jq('.repInfo >input').css("background", "gainsboro");
        }, DetailedTable: {
            tableArray: [[]],
            definedProducts: [[]],/*ليست تعريف شده در فرم تعريف محصولات*/
            isSaved: false,
            unSaved: function () {
                this.isSaved = false;
            },
            removeRow: function (index) {
                $jq('.tableRow_' + index).remove();
                this.updateFrontAfterRemove();
            },
            updateFrontAfterRemove: function () {
                var lengthTable = $jq('.detailedTable>tbody>tr[class^=\'tab\']').length;
                for (var i = 1; i <= lengthTable; i++) {
                    $jq('.detailedTable >tbody > tr').eq(i).attr('class', 'tableRow_' + i);
                    $jq('.detailedTable>tbody>tr.tableRow_' + i + '>td:first ').html(i);
                    $jq('.detailedTable>tbody>tr.tableRow_' + i + '>td#tdDeleteImg>img').attr('onclick', 'FormView.codeSet.DetailedTable.removeRow(' + i + ')');
                }
            },
            addRow: function () {
                var lengthTable = $jq('.detailedTable > tbody > tr').length;
                var newTr = '<tr class="tableRow_' + (lengthTable - 1) + '">' + '<td style="padding: 2px;border: 1px solid #ccc;">' + (lengthTable - 1) + '</td>' + this.getSelectedTagForDefinedProducts() + '<td style="padding: 2px;border: 1px solid #ccc;"><input readonly style="background: gainsboro" onInput="FormView.codeSet.DetailedTable.unSaved()" type="text" name="productType" value=""></td>' + '<td style="padding: 2px;border: 1px solid #ccc;"><input readonly style="background: gainsboro" onInput="FormView.codeSet.DetailedTable.unSaved()" onkeyup="FormView.codeSet.DetailedTable.separateNum(this.value,this)"  dir="ltr" type="text" name="productPrice" min="0" value=""></td>' + '<td style="padding: 2px;border: 1px solid #ccc;"><input onInput="FormView.codeSet.DetailedTable.onChangedNumberOfProductInput(this)" onkeyup="FormView.codeSet.DetailedTable.separateNum(this.value,this)"  dir="ltr" type="number" name="productNum" min="0" value=""></td>' + '<td style="padding: 2px;border: 1px solid #ccc;"><input readonly style="background: gainsboro" onInput="" onkeyup="FormView.codeSet.DetailedTable.separateNum(this.value,this)"  dir="ltr" type="text" name="productTotalRow" min="0" value=""></td>' + '<td id="tdDeleteImg" style="padding: 2px;background-color: #c5e1a5; border: 1px solid #ccc;">' + '<img style="cursor: pointer" onclick="FormView.codeSet.DetailedTable.removeRow(' + (lengthTable - 1) + ')"' + ' src="gfx/toolbar/cross.png" />' + '</td>' + '</tr>';
                $jq('.detailedTable > tbody > tr').eq(lengthTable - 2).after(newTr);
            },
            separateNum: function (value, input) {                 /* seprate number input 3 number */
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
            getDefinedProducts: function () {
                this.definedProducts = Utils.fastAjax('WorkFlowAjaxFunc', 'getDefinedProducts');
            },
            getSelectedTagForDefinedProducts: function () {
                let tag;
                tag = '<td style="padding: 2px;border: 1px solid #ccc;width: 100%"><select onchange="FormView.codeSet.DetailedTable.onChangeSelectTag(this)" name="productName" style="width: 100%;" >';
                tag += '<option value="selected" >انتخاب محصول ...</option>';
                for (i = 0; i < this.definedProducts.length; i++) {
                    tag += `<option value=\"${this.definedProducts[i]['productName']}\">${this.definedProducts[i]['productName']}</option>`;
                }
                tag += '</select></td>';
                return tag;
            },
            onChangeSelectTag: function (selectObject) {
                var productName = selectObject.value;
                let rowClassName = $jq(selectObject).closest('tr').attr('class');
                var rowId = rowClassName.substr(rowClassName.length - 1); /*شماره جدول رو بر مي گردونه*/
                this.changeTheValueOfCellsAfterChangingProductName(productName, rowId);
            },
            changeTheValueOfCellsAfterChangingProductName: function (productName, rowId) {
                let productType = this.getProductType(productName);
                let productPrice = this.getProductPrice(productName);
                $jq('.detailedTable>tbody>tr.tableRow_' + rowId + " input[name='productType']").val(productType);
                $jq('.detailedTable>tbody>tr.tableRow_' + rowId + " input[name='productPrice']").val(productPrice);
            },
            getProductType: function (productName) {
                for (i = 0; i < this.definedProducts.length; i++) {
                    if (this.definedProducts[i]['productName'] == productName) return this.definedProducts[i]['productType']
                }
                return "---";
            },
            getProductPrice: function (productName) {
                for (i = 0; i < this.definedProducts.length; i++) {
                    if (this.definedProducts[i]['productName'] == productName) {
                        let price = this.definedProducts[i]['productPrice'];
                        return this.numberWithCommas(price);
                    }
                }
                return "---";
            },
            numberWithCommas: function (x) {
                return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            },
            onChangedNumberOfProductInput: function (selectObject) {
                this.unSaved();
                this.changeRowPrice(selectObject);
                this.changeTotalPrice();
            },
            changeRowPrice: function (selectObject) {
                let productNumber = selectObject.value;
                let rowClassName = $jq(selectObject).closest('tr').attr('class');
                let rowId = rowClassName.substr(rowClassName.length - 1); /*شماره جدول رو بر مي گردونه*/
                let priceWithComma = $jq('.detailedTable>tbody>tr.tableRow_' + 2 + " input[name='productPrice']").val();
                let productPrice = this.numberWithoutComma(priceWithComma);
                let totalPrice = productNumber * productPrice;
                totalPrice = this.numberWithCommas(totalPrice);
                $jq('.detailedTable>tbody>tr.tableRow_' + rowId + " input[name='productTotalRow']").val(totalPrice);
            },
            changeTotalPrice: function () {
                var length = $jq('.detailedTable>tbody>tr[class^=\'tab\']').length;
                let sum = 0;
                for (var count = 1; count <= length; count++) {
                    let value = $jq('.detailedTable>tbody>tr.tableRow_' + count + ' input[name=\'productTotalRow\'] ').val();
                    if (value == "") value = 0; else {
                        value = this.numberWithoutComma(value);
                        value = parseInt(value);
                    }
                    sum += value;
                }
                $jq('input[name^=totalPrice]').val(sum)
            },
            numberWithoutComma: function (x) {
                return x.replace(/,/g, '');
            }
        }, Questioner: {
            listOfQuestions: [], fillListOfQuestions: function () {
                this.listOfQuestions.push("كيفيت خميرمايه و عملكرد آن را در ورآمدن خمير چگونه ارزيابي مي نمائيد؟");
                this.listOfQuestions.push("كيفيت خميرمايه از لحاظ رنگ و عطر را چگونه ارزيابي مي نمائيد؟");
                this.listOfQuestions.push("زمان تحويل خميرمايه سفارش داده شد را چگونه ارزيابي مي نمائيد؟");
                this.listOfQuestions.push("كيفيت بسته بندي خميرمايه را چگونه ارزيابي مي نمائيد؟");
                this.listOfQuestions.push("ميزان رضايت شما از نحوه ي سفارش گزاري چگونه مي باشد؟");
                this.listOfQuestions.push("برخورد راننده و كادر پخش را چگونه ارزيابي مي نمائيد؟");
                this.listOfQuestions.push("آيا براي مصرف خميرمايه نياز به آموزش داريد؟");
            }, createTagQuestions: function () {
                var i;
                for (i = 1; i <= this.listOfQuestions.length; i++) {
                    let row = " <div class=\"divTableRow\">\n" + "                    <div class=\"divTableCell\">\n" + "                        <div class=\"radif\" style=\"float: right\">" + i + "</div>\n" + "\n" + "                    </div>\n" + "                    <div class=\"divTableCell removeBorderLeft\">\n" + "                        <div class=\"titleQuestion\" style=\"float: right\">" + this.listOfQuestions[i - 1] + "\n" + "                        </div>\n" + "\n" + "\n" + "                    </div>\n" + "                    <div class=\"divTableCell removeBorderRight\">\n" + "                        <div class=\"containerRadio\">\n" + "                            <div class=\"cntr\">\n";
                    let j;
                    let radios = "";
                    for (j = 0; j <= 2; j++) {
                        let option = "";
                        switch (j) {
                            case 0:
                                option = "ضعيف";
                                break;
                            case 1:
                                option = "متوسط";
                                break;
                            case 2:
                                option = "خوب";
                        }
                        let radio = "                                <label class=\"btn-radio\" for=\"S2R" + i + "O" + j + "\">\n" + "\n" + "\n" + "                                    <input id=\"S2R" + i + "O" + j + "\" name=\"rGroupS2R" + i + "\" tabindex=\"6001\" value=\"" + j + "\" type=\"radio\">\n" + "                                    <svg height=\"20px\" viewBox=\"0 0 20 20\" width=\"20px\">\n" + "                                        <circle cx=\"10\" cy=\"10\" r=\"9\"></circle>\n" + "                                        <path class=\"inner\"\n" + "                                              d=\"M10,7 C8.34314575,7 7,8.34314575 7,10 C7,11.6568542 8.34314575,13 10,13 C11.6568542,13 13,11.6568542 13,10 C13,8.34314575 11.6568542,7 10,7 Z\"></path>\n" + "                                        <path class=\"outer\"\n" + "                                              d=\"M10,1 L10,1 L10,1 C14.9705627,1 19,5.02943725 19,10 L19,10 L19,10 C19,14.9705627 14.9705627,19 10,19 L10,19 L10,19 C5.02943725,19 1,14.9705627 1,10 L1,10 L1,10 C1,5.02943725 5.02943725,1 10,1 L10,1 Z\"></path>\n" + "                                    </svg>\n" + "                                    <span>" + option + "</span>\n" + "\n" + "\n" + "                                </label>\n";
                        radios += radio;
                    }
                    row += radios;
                    row += "\n" + "\n" + "                            </div>\n" + "                        </div>\n" + "\n" + "\n" + "                    </div>\n" + "                </div>";
                    $jq("#divTable").append(row);
                }
            }, makeQuestioner: function () {
                this.fillListOfQuestions();
                this.createTagQuestions();
            }
        }
    };
    let instance = Object.create(mainFormClass);
    FormView.codeSet = instance;
    instance.loadForm();
};