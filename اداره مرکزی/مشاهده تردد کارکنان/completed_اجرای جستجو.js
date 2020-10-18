this.jcode = function (self) {
    self.search2 = function (r, t) {
        if (r.length == '1') r = "0" + r;
        var idd = '0' + document.getElementById("dvPID").innerHTML;
        var tt = parseInt(t) + 1395;
        var res = Utils.fastAjax('WorkFlowAjaxFunc', 'testfavaa', {mm: r, yy: tt});
        document.getElementById("dvQuery").innerHTML = res;
    }
}