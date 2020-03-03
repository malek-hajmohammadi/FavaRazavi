/*لود فرم اصلی */
listener = function (event) {
    clearInterval(waitInterval);
    var waitInterval = setInterval(function () {
        if (FormView && FormView.myForm) {
            $jq('td > div[fname^="MosFormFields_"] > div[id$="_PR-ROW"]').parent('div').css('overflow', 'visible');
            var stage = FormView.myForm.getItemByName('Field_8').getData();
            if (FormView.myForm.info.settings.nodeName) {
                var nodeName = FormView.myForm.info.settings.nodeName;
                var nodeName2 = nodeName;
                while (nodeName2 && nodeName2.indexOf(String.fromCharCode(1705)) >= 0) nodeName2 = nodeName2.replace(String.fromCharCode(1705), String.fromCharCode(1603));
                while (nodeName2 && nodeName2.indexOf(String.fromCharCode(1740)) >= 0) nodeName2 = nodeName2.replace(String.fromCharCode(1740), String.fromCharCode(1610));
                if (nodeName == 'كارمند كارگزيني' || nodeName2 == 'كارمند كارگزيني') stage = '1';
                if (nodeName == 'مدير اداري' || nodeName2 == 'مدير اداري') stage = '2';
                if (nodeName == 'مافوق حوزه' || nodeName2 == 'مافوق حوزه') stage = '3';
                if (nodeName == 'مدير حوزه مديرعامل' || nodeName2 == 'مدير حوزه مديرعامل') stage = '4';
                if (nodeName == 'مدير عامل' || nodeName2 == 'مدير عامل') stage = '5';
                if (nodeName == 'مدير اداري2' || nodeName2 == 'مدير اداري2') stage = '6';
                if (nodeName == 'كارمند كارگزيني2' || nodeName2 == 'كارمند كارگزيني2') stage = '7';
                if (nodeName == 'مديرمالي' || nodeName2 == 'مديرمالي') stage = '8';
                if (nodeName == 'بايگان' || nodeName2 == 'بايگان') stage = '9';
            }
            if (stage != '1') $jq('.createListButton').remove();
            if (stage == '2') stage = '1';
            $jq("input[class^='accessStep_']:not(.accessStep_" + stage + "), span[class^='accessStep_']:not(.accessStep_" + stage + ") input,.departmentSum").prop('disabled', true);
            $jq('.Deviation').css('direction', 'ltr');
            if (stage == '1' || stage == '3' || stage == '5') {
                $jq('.PhysicalHourses').off('keyup keypress blur change').on('keyup keypress blur change', function () {
                    var thisValue = $jq(this).val();
                    if (thisValue.indexOf(':') > 0) {
                        var tempArray = thisValue.split(':');
                        if (tempArray.length > 1 && tempArray[1].length > 0) {
                            thisValue = parseInt(tempArray[0]) + ((Math.round(parseInt(tempArray[1]) / 60 * 100)) / 100);
                        } else thisValue = tempArray[0];
                    } else thisValue = parseInt(thisValue);
                    thisValue = (isNaN(thisValue)) ? 0 : thisValue;
                    $jq(this).closest('tr').find('.PhysicalNumber').closest('div[iamfowner]')[0].tagthis.setData(thisValue);
                    $jq(this).closest('tr').find('.managerValue').change();
                });
                $jq('.managerValue').off('keyup keypress blur change').on('keyup keypress blur change', function () {
                    var thisValue = parseFloat($jq(this).val());
                    thisValue = (isNaN(thisValue)) ? 0 : thisValue;
                    var physicalValue = parseFloat($jq(this).closest('tr').find('.PhysicalNumber')[0].value);
                    physicalValue = (isNaN(physicalValue)) ? 0 : physicalValue;
                    var sum = thisValue + physicalValue;
                    $jq(this).closest('tr').find('.sumPAM').closest('div[iamfowner]')[0].tagthis.setData(sum);
                    $jq(this).closest('tr').find('.mainManagerValue').change();
                });
                $jq('.mainManagerValue').off('keyup keypress blur change').on('keyup keypress blur change', function () {
                    var thisValue = parseFloat($jq(this).val());
                    thisValue = (isNaN(thisValue)) ? 0 : thisValue;
                    var sum = parseFloat($jq(this).closest('tr').find('.sumPAM')[0].value);
                    sum = (isNaN(sum)) ? 0 : sum;
                    var lastSum = sum + thisValue;
                    $jq(this).closest('tr').find('.lastSum').closest('div[iamfowner]')[0].tagthis.setData(lastSum);
                    var perCapita = parseFloat($jq(this).closest('tr').find('.perCapita')[0].value);
                    perCapita = (isNaN(perCapita)) ? 0 : perCapita;
                    var deviation = perCapita - lastSum;
                    deviation = ((Math.round(deviation * 100)) / 100);
                    $jq(this).closest('tr').find('.Deviation').closest('div[iamfowner]')[0].tagthis.setData(deviation);
                    var listID = FormView.myForm.getItemByName('Field_3').list.targetID;
                    for (var i = 10; i <= 20; i++) {
                        var sum = 0;
                        $jq('#' + listID + " div[iamfowner$='," + (i - 6) + "'] input").each(function () {
                            var itemValue = parseFloat(this.value);
                            sum += (isNaN(itemValue) ? 0 : itemValue);
                        });
                        FormView.myForm.getItemByName('Field_' + i).setData(sum);
                    }
                });
            }             /*clearInterval(waitInterval);*/
        }
    }, 300);
}
/* انتهای لود فرم اضافه کار*/
/* فیلد محاسباتی  عملیات*/

this.jcode = function (self) {
    self.getList = function () {
        var deptID = FormView.myForm.getItemByName('Field_6').getData();
        if (!deptID || deptID == '') {
            Utils.showModalMessage('لطفا حوزه را انتخاب نماييد');
            return false;
        }
        var res = Utils.fastAjax('WorkFlowAjaxFunc', 'createUsersList', {
            deptID: deptID,
            masterID: FormView.docID
        });
        if (res && res.indexOf('success' >= 0)) {
            FormView.myForm.getItemByName('Field_3').list.subListView.listview.refresh();
        } else {
            Utils.showModalMessage('عمليات با خطا مواجه شد(' + res + ')');
        }
    };
}
/*انتهای فیلد محاسباتی عملیات*/
/* فرم جزء*/
