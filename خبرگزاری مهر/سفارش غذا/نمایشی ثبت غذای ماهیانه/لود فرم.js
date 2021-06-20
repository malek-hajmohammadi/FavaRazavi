listener = function (event) {

    class mainClass {

        tableArray=[];
        dateObjects=[];
        rowCounter=0;
        isSaved=true;

        dataArray=[[]];



        loadForm() {


        }
        showClick(){
            $jq('#listContainer').html();
            this.rowCounter=0;
            this.dateObjects=[];
            this.loadTableFront();
            this.loadTableData();
        }
        loadTableFront(){

            var html = Utils.fastAjax('WorkFlowAjaxFunc', 'loadFoodPlanFront');
            $jq('#listContainer').html(html);

        }
        loadTableData(){
            let year=$jq("select[name=selectedYear").val();
            let month=$jq("select[name=selectedMonth").val();
            this.dataArray = Utils.fastAjax('WorkFlowAjaxFunc', 'loadFoodPlanData',{year:year,month:month});
            for(let i=0;i<this.dataArray.length;i++){

                this.addRow();
                this.addDataRow()

            }


        }
        addDataRow(){
            let index=this.rowCounter-1;
            let dataRow=this.dataArray[index];/*because at the end of addRow() we hava an increment*/
            console.log("dataRow",dataRow);
          $jq(".tableRow_"+index+">td[id^='type1']>input").val(dataRow[4]);

            $jq(".tableRow_"+index+">td[id^='type2']>input").val(dataRow[5]);
            this.dateObjects[index].setDate(dataRow[3]);
            $jq(".tableRow_"+index+">td[id^='day']>select").val(dataRow[2]);


        }

        getSelectedTagForDays() {

            let tag;
            tag = '<select onchange="window.codeSet.unSaved()" name="days" style="width: 100%;" >' +
            '<option value="selected" >روز هفته...</option>'+

            '<option value="شنبه">شنبه</option>' +
            '<option value="یکشنبه">یکشنبه</option>' +
            '<option value="دوشنبه">دوشنبه</option>' +
            '<option value="سه شنبه">سه شنبه</option>' +
            '<option value="چهارشنبه">چهارشنبه</option>' +
            '<option value="پنجشنبه">پنجشنبه</option>' +


            '</select>';
            return tag;

        }

        addRow(){

           /* var lengthTable = $jq('#listContainer >.detailedTable tbody > tr').length;*/
            var lengthTable = $jq('#listContainer >.detailedTable tbody > tr[class^=\'tab\']').length;


            var newTr = '<tr class="tableRow_' + this.rowCounter  + '">' + '<td style="padding: 2px;border: 1px solid #ccc;">' + (lengthTable+1 ) + '</td>' +
                '<td id="day_' + this.rowCounter + '" style="padding: 2px;border: 1px solid #ccc;">'+this.getSelectedTagForDays()+'</td>' +
                '<td style="padding: 2px;border: 1px solid #ccc;"><div id="date_' + this.rowCounter + '"></div></td>' +
                '<td id="type1_' + this.rowCounter + '" style="padding: 2px;border: 1px solid #ccc;"><input  onInput="window.codeSet.unSaved()" type="text" name="day" width="30px" value=""></td>' +
                '<td id="type2_' + this.rowCounter + '" style="padding: 2px;border: 1px solid #ccc;"><input  onInput="window.codeSet.unSaved()" type="text" name="day" width="30px" value=""></td>' +


                '<td id="tdDeleteImg" style="padding: 2px;background-color: #c5e1a5; border: 1px solid #ccc;">' + '<img style="cursor: pointer" onclick="window.codeSet.removeRow(' + this.rowCounter + ')"' + 'src="gfx/toolbar/cross.png" />' + '</td>' +
                '</tr>';
            $jq('.detailedTable > tbody > tr').eq(lengthTable).after(newTr);
           /* this.setDataObjectOneForAdd(index);*/
            this.setDateObject(this.rowCounter);
            this.rowCounter++;

        }
        setDateObject(index){
            this.dateObjects[index] = new EditCalendar('window.codeSet.dateObjects[' + index + ']', 'date_' + index);

        };

        removeRow(index) {
            $jq('.tableRow_' + index).remove();
            this.updateFrontAfterRemove(index);

        }

        updateFrontAfterRemove(index) {
            let a=1;
            $jq('.detailedTable>tbody>tr[class^=\'tab\']').each(function() {
                $jq(this).find("td:first").html(a);
                a++;
            });
        }
        unSaved(){
            this.isSaved=false;
        };

        fillTableArray(){
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

        }
        saveList(){
            this.fillTableArray();
            this.callAjaxToSave();
            Utils.showModalMessage('منو غذا با موفقیت ذخیره شد');

        };
        callAjaxToSave(){


               let year=$jq("select[name=selectedYear").val();
               let month=$jq("select[name=selectedMonth").val();


               let res = Utils.fastAjax('WorkFlowAjaxFunc', 'saveFoodPlan', {tableArray:this.tableArray,
                year:year,month:month});






        };

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

