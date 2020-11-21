listener = function (event) {
    var waitInterval = setInterval(function () {
        $jq('.f-box *').removeAttr('title');
        if (FormView && FormView.myForm) {
            var stage = FormView.myForm.getItemByName('Field_25').getData();             /*if (FormView.myForm.info.settings.nodeName) {                 var nodeName = FormView.myForm.info.settings.nodeName;                 var nodeName2 = nodeName;                 while (nodeName2.indexOf(String.fromCharCode(1705)) >= 0) nodeName2 = nodeName2.replace(String.fromCharCode(1705), String.fromCharCode(1603));                 while (nodeName2.indexOf(String.fromCharCode(1740)) >= 0) nodeName2 = nodeName2.replace(String.fromCharCode(1740), String.fromCharCode(1610));                 if (nodeName == 'شروع' || nodeName2 == 'شروع') stage = '1';                 if (nodeName == 'بررسي مجدد' || nodeName2 == 'متقاضي') stage = '2';                 if (nodeName == 'بعدي' || nodeName2 == 'بعدي') stage = '3';                 if (nodeName == 'متقاضي*' || nodeName2 == 'متقاضي*') stage = '4';                 if (nodeName == 'بايگان' || nodeName2 == 'بايگان') stage = '5';             }*/
            switch (stage) {
                case '2':                     /*$('networkDiv').hide();                     $jq('input.CodeM').attr({'value': '__________', 'data-mask': '__________'});                     if (FormView.myForm.getItemByName('Field_29').getData() == 0) {                         $jq('input.CodeMS').attr({'value': '__________', 'data-mask': '__________'});                     } else {                         $jq('input.CodeMS').attr({'value': '_________', 'data-mask': '_________'});                     }*/
                    $jq('input.CodeShS2').attr({'value': '__', 'data-mask': '__'});
                    $jq('input.CodeShS3').attr({'value': '______', 'data-mask': '______'});
                    window.applyDataMask = function (field) {
                        var mask = field.dataset.mask.split('');
                        self.stripMask = function (maskedData) {
                            self.isDigit = function (char) {
                                return /\d/.test(char);
                            };
                            return maskedData.split('').filter(self.isDigit);
                        };
                        self.applyMask2 = function (data, field) {
                            var mask = field.dataset.mask.split('');
                            mask.map(function (char) {
                                if (char != '_') return char;
                                if (data.length == 0) return char;
                                return data.shift();
                            }).join('');
                        };
                        self.applyMask = function (data, field) {
                            var mask = field.dataset.mask.split('');
                            var ret = mask.map(function (char) {
                                if (char != '_') return char;
                                if (data.length == 0) return char;
                                return data.shift();
                            }).join('');
                            self.applyMask2(data, field);
                            return ret;
                        };
                        self.reapplyMask = function (field) {
                            var data = field.value;
                            return self.applyMask(self.stripMask(data), field);
                        };
                        self.changed = function () {
                            var oldStart = field.selectionStart;
                            var oldEnd = field.selectionEnd;
                            field.value = self.reapplyMask(field);
                            field.selectionStart = oldStart;
                            field.selectionEnd = oldEnd;
                        };
                        field.addEventListener('click', self.changed);
                        field.addEventListener('keyup', self.changed);
                        self.changed();
                    };
                    Array.prototype.forEach.call(document.body.querySelectorAll("*[data-mask]"), window.applyDataMask);
                    FormView.myForm.getItemByName('Field_9').CMode.maxLength = 11;
                    FormView.myForm.getItemByName('Field_6').CMode.maxLength = 10;
                    FormView.myForm.getItemByName('Field_12').CMode.maxLength = 6;
                    if (FormView.myForm.getItemByName('Field_29').getData() == 0) {
                        $jq('span.form-help-pop#codeM').html("<img src='/formimages/meli-new.jpg'>");
                        FormView.myForm.getItemByName('Field_7').CMode.maxLength = 10;
                    } else if (FormView.myForm.getItemByName('Field_29').getData() == 1) {
                        $jq('span.form-help-pop#codeM').html("<img src='/formimages/meli-old.jpg'>");
                        FormView.myForm.getItemByName('Field_7').CMode.maxLength = 9;
                    } else {
                        $jq('span.form-help-pop#codeM').html("<div style='width: 200px'>لطفا كد رهگيري 10 رقمي موجود در رسيد ثبت نام را وارد نماييد</div>");
                        FormView.myForm.getItemByName('Field_7').CMode.maxLength = 10;
                    }
                    var fn = FormView.docID;
                    var codeTaid = FormView.myForm.getItemByName('Field_12').getData();
                    var mobile = FormView.myForm.getItemByName('Field_9').getData();
                    if (mobile.length == 0) FormView.myForm.getItemByName('Field_9').setData('09');                     /*if(mobile.length==0) FormView.myForm.getItemByName('Field_9').setData('09');*/
                    if (codeTaid.length != 0 && mobile.length != 0) {
                        if (!Utils.fastAjax('WorkFlowAjaxFunc', 'ftResiveSms', {
                            codeUser: codeTaid,
                            mobile: mobile,
                            fn: fn
                        })) {
                            FormView.myForm.getItemByName('Field_12').showMSG('كد تاييد براي اين شماره موبايل اعتبار ندارد');
                        } else {
                            FormView.myForm.getItemByName('Field_9').CMode.disabled = "disblabed";
                            FormView.myForm.getItemByName('Field_12').CMode.disabled = "disblabed";
                            $('mobileButton').disable();
                            $jq('#codeVerify').hide();
                            $jq('.resetTD ,#successImg').show();
                        }
                    } else {
                        if (mobile.length != 0) {
                            $jq('#codeVerify').show();
                        } else $jq('#codeVerify').hide();
                    }
                    var id = FormView.myForm.getItemByName('Field_8').CMode.id;
                    $jq('#' + id + ' input').keyup(function () {
                        var value = $jq(this).val();
                        var maxLength = $jq(this).attr('maxLength');
                        if (value.length == maxLength) {
                            var dayInput = $jq('#' + id + ' input')[0];
                            var monthInput = $jq('#' + id + ' input')[1];
                            var yearInput = $jq('#' + id + ' input')[2];
                            if ($jq(dayInput).val().length != $jq(dayInput).attr('maxLength')) $jq(dayInput).focus(); else if ($jq(monthInput).val().length != $jq(monthInput).attr('maxLength')) $jq(monthInput).focus(); else if ($jq(yearInput).val().length != $jq(yearInput).attr('maxLength')) $jq(yearInput).focus(); else FormView.myForm.getItemByName('Field_6').CMode.focus();
                        }
                    });
                    $jq('.seriChar').mouseenter(function (e) {
                        Tooltip.show(e, 'در صورتي كه سري شناسنامه شما قسمت حرف(الف ، ب ، ...) را ندارد گزينه آخر(-) را انتخاب نماييد و درصورتي كه عدد كوچكتر سري بصورت حروف ميباشد آن را بصورت عددي وارد نماييد');
                    });
                    $jq('.seriChar').mouseout(function (e) {
                        Tooltip.hide();
                    });
                    $jq('.ncSerial').focus(function (e) {
                        $jq('span.form-help-pop#codeM').css('visibility', 'visible');
                    });
                    $jq('.ncSerial').blur(function (e) {
                        $jq('span.form-help-pop#codeM').css('visibility', 'hidden');
                    });
                    $jq('#helpSection').show();
                    var uids = [6609, 2466, 5062, 5133, 1882, 3970, 5182, 3671, 3672, 3673, 3675, 3676, 3678, 3679, 3680, 3681, 3683, 3684, 3685, 3690, 3691, 3692, 3693, 3694, 3695, 4311, 4312, 4313, 4315, 4316, 4327, 4328, 4329, 4330, 4333, 4337, 4347, 4348, 4351, 4352, 4355, 4357, 4358, 4361, 4371, 4372, 4373, 4374, 4375, 4376, 4377, 4381, 4382, 4385, 4387, 4389, 4390, 4391, 4392, 4393, 4394, 4417, 4418, 4419, 4420, 4421, 4422, 4423, 4430, 4431, 4436, 4432, 4433, 4434, 4435, 4440, 4443, 4468, 4469, 4470, 4472, 4473, 4474, 4620, 4621, 4622, 4623, 4624, 4625, 4626, 4810, 5003, 5137, 5138, 5143, 6572, 6572, 3532, 7542, 9129, 6837, 9266, 9296, 9305, 9237, 9260, 9293, 6021, 9241, 9245, 9818, 9840];                     /* harim */
                    uids = uids.concat([7939, 8365, 8533, 8991, 8442, 8550, 8323, 8353, 8446, 8459, 8114, 9037]);
                    var inRegisterProcess = false;
                    if (FormView.myForm.getItemByName('Field_29').getData() == 2 && FormView.myForm.getItemByName('Field_7').getData().length == 10) {
                        inRegisterProcess = true;
                    }
                    if (uids.indexOf(Main.UserInfo.id) < 0 && !inRegisterProcess) {
                        MainHeader.paneNavigator();
                        $jq('#MAINHEADER-PANE-IMG,#FORMVIEW-PAGER,#MAINHEADER-ID').hide();
                        $jq("td[onclick='eval(FormView.perArchive(undefined) )']").hide();
                    }
                    break;
                case '4':
                    $jq('#codeVerify').hide();
                    $jq('.resetTD').hide();
                    $jq('#mobileButton').hide();
                    $('networkPass').hide();
                    break;
                case '5':
                    $jq('#codeVerify,.resetTD,#mobileButton,#helpSection').hide();
                    $jq('#networkDiv,#payamSection').show();
                    var status = FormView.myForm.getItemByName('Field_36').getData();
                    if (status == 'updated') {
                        $jq('#networkPass,.networkUser').prop("disabled", true);
                        $jq('#networkVerify').hide();
                        $jq('#networkPass').val(' ').css('color', '#f1f0f0');
                    }
                    var oldUser = FormView.myForm.getItemByName('Field_10').getData();
                    if (oldUser.length > 0 && $jq('#networkPass,.networkUser').prop('disabled')) $jq('#oldUserName').html('(كه الآن ' + oldUser + '@aqrazavi.org است)');
                    var nationalCode = FormView.myForm.getItemByName('Field_6').getData();
                    $jq('#newUserName').html('(' + nationalCode + ')');
                    setTimeout(function () {
                        $jq('#FORMVIEW-TABDIV-ID-ID-ITEM-BODY').animate({scrollTop: 650}, 2000);
                    }, 2000);
                    $jq('#networkVerify').click(function () {
                        var user = FormView.myForm.getItemByName('Field_10').getData();
                        var pass = $('networkPass').value;
                        if (user.length == 0) {
                            FormView.myForm.getItemByName('Field_10').showMSG('لطفا نام كاربري شبكه خود را وارد نماييد');
                            return false;
                        }
                        if (pass.length == 0) {
                            Utils.showModalMessage('لطفا پسورد شبكه خود را وارد نماييد');
                            return false;
                        }
                        if (!FormView.myForm.getItemByName('Field_21').getData()) {
                            FormView.myForm.getItemByName('Field_21').showMSG('لطفا در صورت پذيرش تعهد، گزينه مربوطه را فعال نماييد');
                            return false;
                        }
                        if (!FormView.myForm.getItemByName('Field_35').getData()) {
                            FormView.myForm.getItemByName('Field_35').showMSG('لطفا در صورت پذيرش تعهد، گزينه مربوطه را را فعال نماييد');
                            return false;
                        }
                        FormView.myForm.save();
                        var docID = FormView.docID;
                        var userCheck = Utils.fastAjax('WorkFlowAjaxFunc', 'ftNetwork', {
                            us: user,
                            pas: pass,
                            docID: docID
                        });
                        if (userCheck == true || userCheck == 'true') {
                            Utils.showModalMessage('نام كاربري شبكه(Domain) شما به كد ملي تغيير يافت' + '<br>' + 'با كد ملي و رمز عبور شبكه ميتوانيد در پيام رسان(payam.razavi.ir) وارد شويد.');
                            $jq('#networkPass,.networkUser').prop("disabled", true);
                            $(this).hide();
                        } else {
                            Utils.showModalMessage(userCheck);
                            return false;
                        }
                    });
                    break;
                case '6':
                    $jq('#codeVerify,.resetTD,#mobileButton,#helpSection').hide();
                    $jq('#networkDiv,#payamSection').show();
                    var status = FormView.myForm.getItemByName('Field_36').getData();
                    if (status == 'updated') {
                        $jq('#networkPass,.networkUser').prop("disabled", true);
                        $jq('#networkVerify').hide();
                        $jq('#networkPass').val(' ').css('color', '#f1f0f0');
                    }
                    var oldUser = FormView.myForm.getItemByName('Field_10').getData();
                    $jq('#oldUserName').html('(كه الآن ' + oldUser + '@aqrazavi.org است)');
                    var nationalCode = FormView.myForm.getItemByName('Field_6').getData();
                    $jq('#newUserName').html('(' + nationalCode + ')');
                    break;
            }
            clearInterval(waitInterval);
        }
    }, 500);
}