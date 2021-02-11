listener = function (event) {
    var url = "";
    var aid = null;
    var referID = null;
    var aid = self.getItemByName('Field_7').getData();
    var referID = self.getItemByName('Field_6').getData();
    var top = self.getItemByName('Field_14').getData();
    var left = self.getItemByName('Field_15').getData();
    console.log('top:' + top + 'left:' + left + 'aid:' + aid + 'referid:' + referID);
    var str0 = "ht" + "tp:/" + "/ravan.aqrazavi" + ".org/RAVAN/Runtime/process.php?module=DocAttachs&action=open&aid=" + aid + "&referID=" + referID + "&indexAttach=0&" + Main.getCSRFToken();
    url = "url(" + str + ")";
    console.log(url);
    var str = (window.location.origin) + "/RAVAN/Runtime/process.php?module=DocAttachs&action=open&aid=" + aid + "&referID=" + referID + "&indexAttach=0&" + Main.getCSRFToken();
    url = "url(" + str + ")";
    console.log(url);
    if (window.document.getElementById('FORMVIEW_PRINTPREVIEW') == null) {
        $$('.groundpeyvast').first().style.backgroundImage = url;
        $$('.groundpeyvast').first().style.backgroundSize = '100%';
        $$('.positionemza div').first().style.zIndex = '1';
        $$('.positionemza div').first().style.top = (parseInt(top) - 20) + 'px';
        $$('.positionemza div').first().style.left = left + 'px';
        $$('.aztaraf').first().style.top = (parseInt(top) + 40) + 'px';
        $$('.aztaraf').first().style.left = (parseInt(left) + 140) + 'px';
        $$('.aztaraf').first().style.position = 'absolute';
    }
    console.log('enddddddddd');
    window.document.getElementById('FORMVIEW_PRINTPREVIEW').getElementsByClassName('groundpeyvast')[0].style.backgroundImage = url;
    window.document.getElementById('FORMVIEW_PRINTPREVIEW').getElementsByClassName('groundpeyvast')[0].style.backgroundSize = '100% 100%';
    window.document.getElementById('FORMVIEW_PRINTPREVIEW').getElementsByClassName('positionemza')[0].style.zIndex = '1';
    window.document.getElementById('FORMVIEW_PRINTPREVIEW').getElementsByClassName('positionemza')[0].style.top = top + 'px';
    window.document.getElementById('FORMVIEW_PRINTPREVIEW').getElementsByClassName('positionemza')[0].style.left = (parseInt(left) + 150) + 'px';
    window.document.getElementById('FORMVIEW_PRINTPREVIEW').getElementsByClassName('positionemza')[0].style.top = (parseInt(top) - 90) + 'px';
    window.document.getElementById('FORMVIEW_PRINTPREVIEW').getElementsByClassName('positionemza')[0].style.position = 'absolute';
    window.document.getElementById('FORMVIEW_PRINTPREVIEW').getElementsByClassName('fview')[0].style.left = 70 + 'px';
    window.document.getElementById('FORMVIEW_PRINTPREVIEW').getElementsByClassName('row')[0].style.visibility = 'hidden';
    window.document.getElementById('FORMVIEW_PRINTPREVIEW').getElementsByClassName('aztaraf')[0].style.zIndex = '1';
    window.document.getElementById('FORMVIEW_PRINTPREVIEW').getElementsByClassName('aztaraf')[0].style.top = top + 'px';
    window.document.getElementById('FORMVIEW_PRINTPREVIEW').getElementsByClassName('aztaraf')[0].style.left = (parseInt(left) + 140) + 'px';
    window.document.getElementById('FORMVIEW_PRINTPREVIEW').getElementsByClassName('aztaraf')[0].style.top = (parseInt(top) + 48 - 90) + 'px';
    window.document.getElementById('FORMVIEW_PRINTPREVIEW').getElementsByClassName('aztaraf')[0].style.position = 'absolute';
}