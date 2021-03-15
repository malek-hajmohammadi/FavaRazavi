listener = function(event){
    $jq('.famousRole').attr('list','lstFamousRole');
    $jq('.famousRole').after( '<datalist id="lstFamousRole"> <option value="تولیت محترم آستان قدس رضوی "><option value=" قائم مقام محترم آستان قدس رضوی"><option value="مدیر عالی محترم حرم مطهر رضوی"><option value="معاون محترم اماکن متبرکه حرم مطهر رضوی"></datalist>' );

}