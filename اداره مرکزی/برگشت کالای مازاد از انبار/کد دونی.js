this.jcode = function (self) {
   
    self.myList = [[]];

    self.showTable = function () {
        $mode=FormView.myForm.getItemByName('Field_3').data;
        var html = Utils.fastAjax('WorkFlowAjaxFunc', 'mazadAnbarReturnTable',{docId:FormView.docID,mode:$mode});
        return html;
    };

   
    
    self.addRow = function () {
        var lengthTable = $jq('#FORMVIEW-FORM-MAINDIV .goodsTable > tbody>tr ').length;
        var newTr = '<tr class="tableRow_' + (lengthTable - 1) + '">' +
            '<td  style="padding: 2px;border: 1px solid #ccc;">' + (lengthTable - 1) + '</td>' +
            '<td style="padding: 2px;border: 1px solid #ccc;">' +
            ' <input type="text" name="goods" value=""></td>' +
            '<td style="padding: 2px;border: 1px solid #ccc;"><input type="text" dir="ltr" name="goodsNum" value=""></td>' +
            '<td style="padding: 2px;border: 1px solid #ccc;"><input class="RavanMask" data-inputmask-regex="([0-9]){0,5}" dir="ltr" type="text" name="amount" value=""></td>' +
            
            '<td class="sit" style="padding: 2px;border: 1px solid #ccc;">' +
            '<select ">'+
              '<option selected="selected" value="1" >نو</option>'+
              '<option value="2" >مستعمل</option>'+
              '<option value="3" >اسقاط</option>'+
            '</select></td>' +
            
            '<td style="padding: 2px;border: 1px solid #ccc;">' +
            ' <input type="text" name="value"'+" onkeyup=\"FormView.myForm.getItemByName('Field_1').separateNum(this.value,this);\""+' dir="ltr" value=""></td>' + 

            '<td style="padding: 2px;border: 1px solid #ccc;">' +
            ' <input type="text" name="comment" value=""></td>' +
           
           
           
            '<td class="tdDeleteImg" style="padding: 2px;background-color: #c5e1a5;border: 1px solid #ccc;">' +
            '<img onclick="FormView.myForm.getItemByName(\'Field_1\').removeRow(' + (lengthTable - 1) + ')"' +
            'src="gfx/toolbar/cross.png" style="cursor: pointer;"/>' +
            '</td>' +
            '</tr>';
        $jq('#FORMVIEW-FORM-MAINDIV .goodsTable > tbody > tr').eq(lengthTable - 2).after(newTr);
        

    };

    self.removeRow = function (index) {
        $jq('.tableRow_' + index).remove();
        var lengthTable = $jq('#FORMVIEW-FORM-MAINDIV .goodsTable > tbody > tr').length;
        for (var i = 1; i <= lengthTable - 2; i++) {

            $jq('#FORMVIEW-FORM-MAINDIV .goodsTable >tbody > tr').eq(i).attr('class', 'tableRow_' + i);
           
            $jq('#FORMVIEW-FORM-MAINDIV .goodsTable>tbody>tr.tableRow_' + i + '>td:first ').html(i);
            $jq('#FORMVIEW-FORM-MAINDIV .goodsTable>tbody>tr.tableRow_' + i + '>td.tdDeleteImg>img').attr('onclick', 'FormView.myForm.getItemByName(\'Field_1\').removeRow(' + i + ')');

        }
        self.fillMyList(); /* برای اینکه خونه حذف شده رو بیاد از آرایه بردارد*/

    };

    self.saveMyList = function () {
        self.fillMyList();
        console.log(self.myList);
        if (self.checkRightList()) {
            self.saveToDb();
            return true;
        }
        else 
          return false;

        /*  console.log(self.myList);*/
    };

    self.fillMyList = function () {
        /*  1:barrasi 2:mojaz 3:na motaber*/
        var length = $jq('#FORMVIEW-FORM-MAINDIV .goodsTable > tbody > tr').length;
        self.myList = [[]];

        for (var count = 1; count <= length - 2; count++) {
            self.myList[count] = [];
            self.myList[count][0] = $jq('#FORMVIEW-FORM-MAINDIV .goodsTable>tbody>tr.tableRow_' + count + ' input[name=\'goods\'] ').val();
            self.myList[count][1] = $jq('#FORMVIEW-FORM-MAINDIV .goodsTable>tbody>tr.tableRow_' + count + ' input[name=\'goodsNum\'] ').val();
            self.myList[count][2] = $jq('#FORMVIEW-FORM-MAINDIV .goodsTable>tbody>tr.tableRow_' + count + ' input[name=\'amount\'] ').val();

            self.myList[count][3] = $jq('#FORMVIEW-FORM-MAINDIV .goodsTable>tbody>tr.tableRow_' + count + '>td.sit> select ').val();
            self.myList[count][4] = $jq('#FORMVIEW-FORM-MAINDIV .goodsTable>tbody>tr.tableRow_' + count + ' input[name=\'value\'] ').val();
            self.myList[count][5] = $jq('#FORMVIEW-FORM-MAINDIV .goodsTable>tbody>tr.tableRow_' + count + ' input[name=\'comment\'] ').val();


        }
        /*
        $dataInTable = array(
    array("تلویزیون", "1212312", 6, "1398/11/11", 1, "120000", "نیاز به تعمیر دارد"),
    array("تلویزیون", "1212312", 6, "1398/11/11", 1, "120000", "نیاز به تعمیر دارد"),
    array("تلویزیون", "1212312", 6, "1398/11/11", 1, "120000", "نیاز به تعمیر دارد"),
    array("تلویزیون", "1212312", 6, "1398/11/11", 1, "120000", "نیاز به تعمیر دارد"),
    array("تلویزیون", "1212312", 6, "1398/11/11", 1, "120000", "نیاز به تعمیر دارد"),
    */




    };

    self.checkRightList = function () {

        for (var count = 1; count <= self.myList.length - 1; count++) {

            if (self.myList[count][4].length < 1) {
                Utils.showModalMessage('لطفا ارزش کارشناسی در ردیف ' + count + ' تصحیح کنید');
                return false;
            }
            if (self.myList[count][0].length < 3) {
                Utils.showModalMessage('لطفا فیلد شرح کالا در ردیف ' + count + ' تصحیح کنید');
                return false;
            }
            if (self.myList[count][1].length < 3) {
                Utils.showModalMessage('لطفا شماره اموال در ردیف ' + count + ' تصحیح کنید');
                return false;
            }
            if (self.myList[count][2].length < 1) {
                Utils.showModalMessage('لطفا فیلد تعداد در ردیف ' + count + ' تصحیح کنید');
                return false;
            }



        }
        return true;

    };
    self.saveToDb = function () {

        console.log(self.myList);
        console.log("in save:---------- ");
        var tem = JSON.stringify(self.myList);
        console.log(tem);
        Utils.showProgress(true);
        var res = Utils.fastAjax('WorkFlowAjaxFunc', 'saveDetailedTable_mazadAnbar', {
            docId: FormView.docID,
            myList: tem
        });
        
         console.log(res);
        /*self.myList=listComeFromAjax;*/
        /*self.fillMyListAfterAjax();*/
        Utils.showProgress(false);
      /*  Utils.showModalMessage(res);*/
       /* Utils.showMessage(res);*/

        var ravanResult = new RavanResult();
        ravanResult.assign(res);
                  
        Utils.showModalMessage(ravanResult.msg);
                   

       


        /*
        
            if (res.status = 'ERROR')
            Utils.showModalMessage(res.msg);
        else if (res.status = 'SUCCESS') {

            Utils.showModalMessage(res.msg);
        }
        */
       

    };

    /*1:barrasi 2:mojaz 3:na motaber*/
    self.alter = function (temp) {
        if (temp == 'مجاز')
            return 2;
        if (temp == 'بررسی')
            return 1;
        if (temp == 'نا‌معتبر')
            return 3;
    };
    self.fillMyListAfterAjax=function(){

        html = self.showTable();
        $jq('#FORMVIEW-FORM-MAINDIV.tableGoods').html(html);

       /* self.setDateObjectAll();*/

/*

        var countTr = $jq('#FORMVIEW-FORM-MAINDIV .goodsTable > tbody > tr').length;
        for (var count = 1; count <= countTr - 2; count++) {

        $jq('.tableRow_' + count).remove();

        }
        
        for (var count = 2; count <= self.myList.length ; count++) {
           
            var newTr = '<tr class="tableRow_' + (count - 1) + '">' +
            '<td style="padding: 2px;border: 1px solid #ccc;">' + (count - 1) + '</td>' +
            '<td style="padding: 2px;border: 1px solid #ccc;">' +
            ' <input type="text" name="firstName" value=""></td>' +
            '<td style="padding: 2px;border: 1px solid #ccc;"><input type="text" name="lastName" value=""></td>' +
            '<td style="padding: 2px;border: 1px solid #ccc;"><input class="RavanMask" data-inputmask-regex="([0-9]){10}" dir="ltr" type="text" name="nationalCode" value=""></td>' +
            '<td style="padding: 2px;border: 1px solid #ccc;">' +
            '<div id="birthdayDate_' + (count - 1) + '"><input type="text" name="birthDay" value=""></div>' +
            '</td>' +
            '<td class="sabeghe"  style="padding: 2px;border: 1px solid #ccc;"><span style=\"color:#004ba0;font-weight: bold;\">بررسی</span></td>' +
            '<td class="rezvan" style="padding: 2px;border: 1px solid #ccc;"><span style=\"color:#004ba0;font-weight: bold;\">بررسی</span></td>' +
            '<td class="ahval" style="padding: 2px;border: 1px solid #ccc;"><span style=\"color:#004ba0;font-weight: bold;\">بررسی</span></td>' +
            '<td id="tdDeleteImg" style="padding: 2px;background-color: #c5e1a5;border: 1px solid #ccc;">' +
            '<img onclick="FormView.myForm.getItemByName(\'Field_1\').removeRow(' + (count - 1) + ')"' +
            'src="gfx/toolbar/cross.png" style="cursor: pointer;"/>' +
            '</td>' +
            '</tr>';
        $jq('#FORMVIEW-FORM-MAINDIV .goodsTable > tbody > tr').eq(count - 2).after(newTr);
        self.setDateobjectOne(count - 1);




        }
        
*/

    };

    self.separateNum=function(value, input) {
        /* seprate number input 3 number */
        var nStr = value + '';
        nStr = nStr.replace(/\,/g, "");
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        if (input !== undefined) {

            input.value = x1 + x2;
        } else {
            return x1 + x2;
        }
    }



};