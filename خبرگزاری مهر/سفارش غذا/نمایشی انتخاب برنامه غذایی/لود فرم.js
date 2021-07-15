listener = function (event) {

    class mainClass {

        outputArray=[];

        rowCounter=0;
        isSaved=true;

        dataArray=[];



        loadForm() {

            this.loadCurrentDate();




        }

        loadCurrentDate() {
            var today = Main["FirstPageParameters"]["datetime"]["todayDate"];
            var todayArray=today.split('/');
            var month=+todayArray[1];

            let year=$jq("select[name=selectedMonth").val(month);


        }


        showClick(){
            $jq('#listContainer').html();
            this.rowCounter=0;

            this.loadTableFront();
           this.loadTableData();
        }
        loadTableFront(){

            var html = Utils.fastAjax('WorkFlowAjaxFunc', 'loadFoodSelectFront');
            $jq('#listContainer').html(html);

        }
        loadTableData(){
            let year=$jq("select[name=selectedYear").val();
            let month=$jq("select[name=selectedMonth").val();
            this.dataArray = Utils.fastAjax('WorkFlowAjaxFunc', 'loadFoodSelectData',{year:year,month:month});
            for(let i=0;i<this.dataArray.length;i++){

                this.addRow();
                this.setTableData(i);

            }



        };
        setTableData(index){
            let row=this.dataArray[index];
            let checked=row[5];
            if (checked=="") checked="0";
            switch (checked) {
                case "0":
                    $jq("#none_"+index).prop('checked',true);
                    break;
                case "1":
                    $jq("#type1_"+index).prop('checked',true);
                    break;
                case "2":
                    $jq("#type2_"+index).prop('checked',true);
                    break;
            }

        };


        addRow(){

            /* var lengthTable = $jq('#listContainer >.detailedTable tbody > tr').length;*/
            var lengthTable = $jq('#listContainer >.detailedTable tbody > tr[class^=\'tab\']').length;

            let dataRow=this.dataArray[this.rowCounter];

            var readOnlyClass="";
            var inputSyle="";
            var inputReadOnly="";
            if(dataRow[6]=="r"){
                readOnlyClass=" trReadonly";
                inputSyle=" style=' background-color: gainsboro;'";
                inputReadOnly=" readOnly";
            }
            if(dataRow[5]=="0"){
                inputSyle=" style=' background-color: gainsboro;'";
                inputReadOnly=" readOnly";
            }




            var newTr = '<tr class="tableRow_' + this.rowCounter  +readOnlyClass+ '">' + '<td style="padding: 2px;border: 1px solid #ccc;">' + (lengthTable+1 ) + '</td>' +
                '<td id="day_' + this.rowCounter + '" style="padding: 2px;border: 1px solid #ccc;">'+dataRow[0]+' '+dataRow[1]+'</td>' +
                '<td  style="padding: 2px;border: 1px solid #ccc;">'+this.buildRadioGroup(dataRow)+'</td>' +
                '<td  style="padding: 2px;border: 1px solid #ccc;"><input '+inputReadOnly+inputSyle+' size="3" dir="ltr" type="number" min="0" name="foodCount" value="'+dataRow[7]+'"></td>' +

              /*  '<td style="padding: 2px;border: 1px solid #ccc;"><div id="date_' + this.rowCounter + '"></div></td>' +
                '<td id="type1_' + this.rowCounter + '" style="padding: 2px;border: 1px solid #ccc;"><input  onInput="window.codeSet.unSaved()" type="text" name="day" width="30px" value=""></td>' +*/


                '</tr>';
            $jq('.detailedTable > tbody > tr').eq(lengthTable).after(newTr);

          /*  this.setDateObject(this.rowCounter);*/
            this.rowCounter++;

        }
        buildRadioGroup(dataRow){
            let index=this.rowCounter;
            var disable="";
            if (dataRow[6]=="r")
                disable="disabled";
            let html='<div class="container">\n' +
                '    <form class="form cf" style="margin: 0px">\n' +
                '        <section class="plan cf">\n' +
                '\n' +
                '            <input '+disable+' type="radio" onchange="window.codeSet.radioOnChange(this,'+index+')" name="radio_'+index+'" id="none_'+index+'" value="0"><label class="free-label four col" for="none_'+index+'">هیچکدام</label>\n' +
                '            <input '+disable+' type="radio" onchange="window.codeSet.radioOnChange(this,'+index+')" name="radio_'+index+'" id="type2_'+index+'" value="2" checked><label class="basic-label four col" for="type2_'+index+'">'+dataRow[3]+'</label>\n' +
                '            <input '+disable+' type="radio" onchange="window.codeSet.radioOnChange(this,'+index+')" name="radio_'+index+'" id="type1_'+index+'" value="1"><label class="premium-label four col" for="type1_'+index+'">'+dataRow[2]+'</label>\n' +
                '        </section>\n' +
                '\n' +
                '    </form>\n' +
                '</div>';
            return html;

        }

        radioOnChange(src,index) {
            if(src.value=="1" || src.value=="2"){
                $jq("tr.tableRow_"+index+" input[name='foodCount']").attr("readOnly",false);
                $jq("tr.tableRow_"+index+" input[name='foodCount']").css("background-color","white");
                $jq("tr.tableRow_"+index+" input[name='foodCount']").val("1");

            }
            else {
                $jq("tr.tableRow_"+index+" input[name='foodCount']").attr("readOnly",true);
                $jq("tr.tableRow_"+index+" input[name='foodCount']").css("background-color","gainsboro");
                $jq("tr.tableRow_"+index+" input[name='foodCount']").val("0");
            }

        }


      /*  updateFrontAfterRemove(index) {
            let a=1;
            $jq('.detailedTable>tbody>tr[class^=\'tab\']').each(function() {
                $jq(this).find("td:first").html(a);
                a++;
            });
        }
        unSaved(){
            this.isSaved=false;
        };*/


        fillTableArray(){
            for(let i=0;i<this.dataArray.length;i++){
                let dataObject={
                    rowId:"",
                    selected:"",
                    count:""
                };
                dataObject.selected=$jq("input:radio[name ='radio_"+i+"']:checked").val();
                dataObject.rowId=this.dataArray[i][4];
                dataObject.count=$jq("tr.tableRow_"+i+" input[name='foodCount']").val();
                if(dataObject.count=="")
                    dataObject.count="0";
                this.outputArray[i]=dataObject;

            }
        }
        saveList(){
            this.fillTableArray();
            this.callAjaxToSave();
            Utils.showModalMessage('منو غذا شما با موفقیت ذخیره شد');

        }
        callAjaxToSave(){

            if(this.dataArray.length>0 && this.dataArray[0].length>0)
              var res = Utils.fastAjax('WorkFlowAjaxFunc', 'saveFoodSelect', {outputArray:this.outputArray});
            else{
                Utils.showModalMessage('لطفا ابتدا یک برنامه غذایی را انتخاب کنید.');
            }

        }


        /*fillTableArray(){
            let counter=0;
            let that=this;
            $jq('.detailedTable>tbody>tr[class^=\'tab\']').each(function() {
                let dateObject={
                    date:"",
                    weekDay:"",
                    type1:"",
                    type2:""
                };
                let className=$jq(this).attr('class');
                var index = className.substring(className.indexOf("_")+1);
                dateObject.date=that.dateObjects[index].getDate();
                dateObject.weekDay=$jq(this).find("td[id^='day']>select").val();
                dateObject.type1=$jq(this).find("td[id^='type1']>input").val();
                dateObject.type2=$jq(this).find("td[id^='type2']>input").val();
                that.tableArray[counter]=dateObject;
                counter++;

            });

        }*/

    };


    var waitInterval = setInterval(function () {
        if (FormOnly) {

            let instance = new mainClass();
            window.codeSet = instance;
            window.codeSet.loadForm();
        }

        clearInterval(waitInterval);
    }, 300);

};

