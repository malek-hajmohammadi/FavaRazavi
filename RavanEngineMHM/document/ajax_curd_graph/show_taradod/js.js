//اجرای تردد//
//برداشت از سامان بازار

var res = Utils.fastAjax('WorkFlowAjaxFunc', 'BazarTimexList', {
    mm: mm,
    yy: yy,
    PID: PID,
    status: 'DoreZamani'
});
$jq('#FORMONLY-FIELDS-CONTAINER').animate({scrollTop: 440}, 'slow');
document.getElementById('dvQuery').innerHTML = res;

//اجرای تردد//
//برداشت از سامان بازار
var res = Utils.fastAjax('WorkFlowAjaxFunc', 'BazrTimexList', {
    aztd: aztd,
    aztm: aztm,
    azty: azty,
    tatd: tatd,
    tatm: tatm,
    taty: taty,
    PID: PID,
    status: 'BazeZamani'
});
$jq('#FORMONLY-FIELDS-CONTAINER').animate({scrollTop: 360}, 'slow');
document.getElementById('dvQuery').innerHTML = res;
