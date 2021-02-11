var tempArr = Utils.fastAjax('Chart', 'getRolesByGroop', {groupID: 5});
FormOnly.repList = new PRCopyElement("FormOnly.repList", "representationList", "representationList", tempArr);

var tempArr = Utils.fastAjax('Chart', 'getRolesByDept', {deptID: 1});


/////////////////////////////////
var optionsCorrespondent = new RavanSelectOptions();//new Select2Options();
optionsCorrespondent.width=300;

optionsCorrespondent.hasNew = (Main.currentSecInfo['OnlySelectOutPer'] != null) ? !Main.currentSecInfo['OnlySelectOutPer'] : false;
optionsCorrespondent.placeHolder = 'حوزه هدف را انتخاب کرده یا وارد نمایید.';

////////////////


var res = Main.getSecOutPersonsNew();

{id: 100042, text: "- اتاق اصناف مشهد", urlType: 2, textPrint: "رئيس محترم اتاق اصناف مشهد"}
var res=[];
    res.push({id:1,text:"علی"});
    res.push({id:2,text:"تقی"});
    res.push({id:3,text:"جواد"});
    res.push({id:4,text:"سینا"});

let tagId



correspondentSelectorArr[itemNO] = new RavanSelect(id + '___ComboCorrespondent_' + itemNO, res, optionsCorrespondent);