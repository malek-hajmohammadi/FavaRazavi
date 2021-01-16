this.jcode = function (self) {
    try {
        var pid = Main['UserInfo']['employeeID'];
        FormOnly.allFieldsContianer[5].setData(pid);
        var level = Main['UserInfo']['level'];
        if (level >= 80 && level != 99) {
            $jq('.DivSelectPerson').show();
            $jq('.DivSelectPerson-Per').hide();
        } else if (level == 99) {
            $jq('.DivSelectPerson').show();
            $jq('.DivSelectPerson-Per').show();
        } else {
            $jq('.DivSelectPerson').hide();
        }
        var today = Main["FirstPageParameters"]["datetime"]["todayDate"];
        aa = today.split('/');
        yy = aa[0];
        mm = aa[1];
        dd = aa[2];
        azt = yy + '/01/01';
        tat = yy + '/' + mm + '/' + dd;
        FormOnly.allFieldsContianer[0].setData(azt);
        FormOnly.allFieldsContianer[1].setData(tat);
        switch (yy) {
            case '1398':
                yy = 0;
                break;
            case '1399':
                yy = 1;
                break;
            case '1400':
                yy = 2;
                break;
            default:
                yy = 0;
        }
        mm = mm.replace(/^0+/, '');
        if (dd > 10) mm++;
        if (mm == 1 && dd > 10) {
            mm = 1;
            yy++;
        }
        FormOnly.allFieldsContianer[2].setData(mm);
        FormOnly.allFieldsContianer[3].setData(yy);
    } catch (err) {
    }
}