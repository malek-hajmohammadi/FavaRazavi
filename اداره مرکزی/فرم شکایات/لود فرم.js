listener = function (event) {
    var waitInterval = setInterval(function () {
        if (FormView && FormView.myForm) {
            var stage = FormView.myForm.getItemByName('Field_23').getData();
            if (FormView.myForm.info.settings.nodeName && FormView.myForm.info.settings.nodeName.length > 0) {
                var nodeName = FormView.myForm.info.settings.nodeName;
                var nodeName2 = nodeName;
                while (nodeName2 && nodeName2.indexOf(String.fromCharCode(1705)) >= 0) nodeName2 = nodeName2.replace(String.fromCharCode(1705), String.fromCharCode(1603));
                while (nodeName2 && nodeName2.indexOf(String.fromCharCode(1740)) >= 0) nodeName2 = nodeName2.replace(String.fromCharCode(1740), String.fromCharCode(1610));
                if (nodeName == 'كارشناس' || nodeName2 == 'كارشناس') stage = '1';
                if (nodeName == 'رئيس-اداره' || nodeName2 == 'رئيس-اداره') stage = '2';
                if (nodeName == 'معاونت اقدام كننده' || nodeName2 == 'معاونت اقدام كننده') stage = '3';
                if (nodeName == 'واحد اقدام كننده' || nodeName2 == 'واحد اقدام كننده') stage = '4';
                if (nodeName == 'كارشناس مسئول پيگيري' || nodeName2 == 'كارشناس مسئول پيگيري') stage = '6';
            }
            switch (stage) {
                case '1':
                    $jq('#hideInfoCheck').hide();
                    $jq('#sectionContainer fieldset')[2].hide();
                    $jq('#sectionContainer fieldset')[3].hide();
                    $jq('#sectionContainer fieldset')[4].hide();
                    $jq('#sectionContainer fieldset')[5].hide();
                    $jq("#" + FormView.myForm.getItemByName('Field_45').CMode.id).focus(function () {
                        FormView.myForm.getItemByName('Field_45').confListener();
                    });
                    break;
                case '3':
                    $jq('#sectionContainer fieldset')[2].hide();
                    $jq('#sectionContainer fieldset')[5].hide();
                    $jq('#formLables').hide();
                    if (FormView.myForm.getItemByName('Field_37').getData().length == 0) $jq('#otherOrgs').hide();
                    break;
                case '4':
                case '5':
                    $jq('#actorTitle').html('اقدام كننده بعدي:');
                    if (FormView.myForm.getItemByName('Field_25').getData()) {
                        $jq('#sectionContainer fieldset')[0].hide();
                    }
                    $jq('#sectionContainer fieldset')[2].hide();
                    $jq('#sectionContainer fieldset')[4].hide();
                    $jq('#sectionContainer fieldset')[5].hide();
                    $jq('#formTypeSubjectDiv').hide();
                    $jq('#formLables').hide();
                    $jq('.actorReport').css('height', '100px');
                    break;
            }
            if (FormView.myForm.getItemByName('Field_8').mode == 'edit') {
                try {
                    if ($jq('.requstString')[0].value.length > 0) var text = FormView.myForm.getItemByName('Field_8').getData();
                    while (text.indexOf('/n') >= 0) text = text.replace('/n', '\n');
                    FormView.myForm.getItemByName('Field_8').setData(text);
                } catch (e) {
                }
            }
            clearInterval(waitInterval);
        }
    }, 50);
}