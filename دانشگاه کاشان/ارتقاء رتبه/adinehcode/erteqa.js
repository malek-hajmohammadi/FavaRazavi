listener = function(event) {
  class mainClass {
    setTotalScore() {
      let total = 0;
      let alef = parseInt(FormView.myForm.getItemByName('Field_94').getData());
      if (isNaN(alef)) alef = 0;
      let be = parseInt(FormView.myForm.getItemByName('Field_95').getData());
      if (isNaN(be)) be = 0;
      let jim = parseInt(FormView.myForm.getItemByName('Field_96').getData());
      if (isNaN(jim)) jim = 0;
      let dal = parseInt(FormView.myForm.getItemByName('Field_97').getData());
      if (isNaN(dal)) dal = 0;

      total = alef + be + jim + dal ;
      FormView.myForm.getItemByName('Field_114').setData(total);
    }
    setReadOnly() {
      $jq('.totalScore >input').attr("readonly", "true");
      $jq('.totalScore >input').css("background", "gainsboro");
    }
    setAvg3sal() {
      let total = 0;
      let a = FormView.myForm.getItemByName('Field_12').getData();
      if (a == "") a = 0;
      let b = FormView.myForm.getItemByName('Field_14').getData();
      if (b == "") b = 0;
      let c = FormView.myForm.getItemByName('Field_16').getData();
      if (c == "") c = 0;
      total = a + b + c;
      total = total / 3;
      FormView.myForm.getItemByName('Field_100').setData(total);
    }
    setSumKhedmat() {
      /*$jq( "div[iamfowner$='0']" ).parent().css('overflow','visible');*/
      let count = FormView.myForm.getItemByName('Field_21').list.subListView.data.length;
      let total = 0;
      for (let i = 0; i < count; i++) {
        let value = null;
        try {
          /* let value = FormView.myForm.getItemByName('Field_21').list.subListView.data[i].DMSFields[0].value;*/
          value = $jq("div[iamfowner$='0']")[i].tagthis.getData();
        } catch (e) {}
        if (value == null) value = 0;
        value = parseInt(value);
        if (isNaN(value)) value = 0;
        total += value;
      }
      FormView.myForm.getItemByName('Field_23').setData(total); /* let total=0;             let columns=$jq( "div[iamfowner$='0'] input" );             for(let i=0;i<columns.length;i++){                 let temp=parseInt(columns[i].value);                 total+=temp;             }              if(total>10){                 Utils.showModalMessage('امتيازات اكتسابي از سقف مجاز بيشتر است');                 return;             }             FormView.myForm.getItemByName('Field_23').setData(total);*/
    }
    setSumMadrak() {
      let count = FormView.myForm.getItemByName('Field_35').list.subListView.data.length;
      let total = 0;
      for (let i = 0; i < count; i++) {
        let value = null;
        try {
          value = $jq(".tdMadrak div[iamfowner$='3']")[i].tagthis.getData();
        } catch (e) {}
        if (value == null) value = 0;
        value = parseInt(value);
        if (isNaN(value)) value = 0;
        total += value;
      }

    }
    setSumEjraee() {
      let count = FormView.myForm.getItemByName('Field_36').list.subListView.data.length;
      let total = 0;
      for (let i = 0; i < count; i++) {
        let value = null;
        try {
          value = $jq(".tdEjraee div[iamfowner$='3']")[i].tagthis.getData();
        } catch (e) {}
        if (value == null) value = 0;
        value = parseInt(value);
        if (isNaN(value)) value = 0;
        total += value;
      }

    }
    setSumKargorooh() {
      let count = FormView.myForm.getItemByName('Field_37').list.subListView.data.length;
      let total = 0;
      for (let i = 0; i < count; i++) {
        let value = null;
        try {
          value = $jq(".tdKargorooh div[iamfowner$='3']")[i].tagthis.getData();
        } catch (e) {}
        if (value == null) value = 0;
        value = parseInt(value);
        if (isNaN(value)) value = 0;
        total += value;
      }
      FormView.myForm.getItemByName('Field_102').setData(total);
    }
    setSumA() {
      let total = 0;
      let a = FormView.myForm.getItemByName('Field_118').getData();
      if (a == "") a = 0;
      let b = FormView.myForm.getItemByName('Field_119').getData();
      if (b == "") b = 0;
      let c = FormView.myForm.getItemByName('Field_120').getData();
      if (c == "") c = 0;
      let d = FormView.myForm.getItemByName('Field_121').getData();
      if (d == "") d = 0;
      total = a + b + c + d;
      FormView.myForm.getItemByName('Field_34').setData(total);
    }
    setSumB(){
      let total = 0;
      let a = FormView.myForm.getItemByName('Field_102').getData();
      if (a == "") a = 0;
      let b = FormView.myForm.getItemByName('Field_102').getData();
      if (b == "") b = 0;
      let c = FormView.myForm.getItemByName('Field_102').getData();
      if (c == "") c = 0;
      total = a + b + c ;
      FormView.myForm.getItemByName('Field_103').setData(total);

    }


    setSumLearn() {
      let total = 0;

      let a = FormView.myForm.getItemByName('Field_41').getData();
      if (a == "") a = 0;
      a = (a * 45) / 100;
      FormView.myForm.getItemByName('Field_104').setData(a);

      let b = FormView.myForm.getItemByName('Field_43').getData();
      if (b == "") b = 0;
      b = (b * 15) / 100;
      FormView.myForm.getItemByName('Field_105').setData(b);

      total = a + b;
      FormView.myForm.getItemByName('Field_106').setData(total);
    }
    setSumSoft() {
      let total = 0;
      let a = FormView.myForm.getItemByName('Field_47').getData();
      if (a == "") a = 0;
      let b = FormView.myForm.getItemByName('Field_48').getData();
      if (b == "") b = 0;
      let c = FormView.myForm.getItemByName('Field_49').getData();
      if (c == "") c = 0;
      let d = FormView.myForm.getItemByName('Field_50').getData();
      if (d == "") d = 0;
      let e = FormView.myForm.getItemByName('Field_51').getData();
      if (e == "") e = 0;
      let f = FormView.myForm.getItemByName('Field_52').getData();
      if (f == "") f = 0;
      let g = FormView.myForm.getItemByName('Field_53').getData();
      if (g == "") g = 0;
      total = a + b + c + d + e + f + g;
      if (document.getElementById("chk").checked == true) total = 70;
      FormView.myForm.getItemByName('Field_117').setData(total);
    }
    setSumLang() {
      let total = 0;
      let a = FormView.myForm.getItemByName('Field_122').getData();
      if (a == "") a = 0;
      let b = FormView.myForm.getItemByName('Field_56').getData();
      if (b == "") b = 0;
      total = a + b ;
      FormView.myForm.getItemByName('Field_107').setData(total);
    }
    setSumFardi() {
      let count = FormView.myForm.getItemByName('Field_60').list.subListView.data.length;
      let total = 0;
      for (let i = 0; i < count; i++) {
        let value = null;
        try {
          value = $jq(".tdFardi div[iamfowner$='3']")[i].tagthis.getData();
        } catch (e) {}
        if (value == null) value = 0;
        value = parseInt(value);
        if (isNaN(value)) value = 0;
        total += value;
      }
      FormView.myForm.getItemByName('Field_109').setData(total);
    }
    setSumTajrobe() {
      let count = FormView.myForm.getItemByName('Field_58').list.subListView.data.length;
      let total = 0;
      for (let i = 0; i < count; i++) {
        let value = null;
        try {
          value = $jq(".tdTajrobe div[iamfowner$='3']")[i].tagthis.getData();
        } catch (e) {}
        if (value == null) value = 0;
        value = parseInt(value);
        if (isNaN(value)) value = 0;
        total += value;
      }
      FormView.myForm.getItemByName('Field_108').setData(total);
    }
    setSumC(){
      let total = 0;
      let a = FormView.myForm.getItemByName('Field_106').getData();
      if (a == "") a = 0;
      let b = FormView.myForm.getItemByName('Field_107').getData();
      if (b == "") b = 0;
      let c = FormView.myForm.getItemByName('Field_108').getData();
      if (c == "") c = 0;
      let e = FormView.myForm.getItemByName('Field_109').getData();
      if (e == "") e = 0;
      let f = FormView.myForm.getItemByName('Field_117').getData();
      if (f == "") f = 0;
      total = a + b + c + d + e + f ;
      FormView.myForm.getItemByName('Field_110').setData(total);
    }


    setSumTashvigh() {
      /*$jq( "div[iamfowner$='0']" ).parent().css('overflow','visible');*/
      let count = FormView.myForm.getItemByName('Field_87').list.subListView.data.length;
      let total = 0;
      for (let i = 0; i < count; i++) {
        let value = null;
        try {
          /* let value = FormView.myForm.getItemByName('Field_87').list.subListView.data[i].DMSFields[0].value;*/
          value = $jq("div[iamfowner$='0']")[i].tagthis.getData();
        } catch (e) {}
        if (value == null) value = 0;
        value = parseInt(value);
        if (isNaN(value)) value = 0;
        total += value;
      }
      total = total * 0.4;
      let rotbe = FormView.myForm.getItemByName('Field_5').getData();
      switch (rotbe) {
        case "0":
          if (total > 48) total = 48;
          break;
        case "1":
          if (total > 68) total = 68;
          break;
        case "2":
          if (total > 88) total = 88;
          break;
        case "3":
          if (total > 112) total = 112;
          break;
      }
      FormView.myForm.getItemByName('Field_111').setData(total); /* let total=0;             let columns=$jq( "div[iamfowner$='0'] input" );             for(let i=0;i<columns.length;i++){                 let temp=parseInt(columns[i].value);                 total+=temp;             }              if(total>10){                 Utils.showModalMessage('امتيازات اكتسابي از سقف مجاز بيشتر است');                 return;             }             FormView.myForm.getItemByName('Field_111').setData(total);*/
    }
    setSumMaqam() {
      let count = FormView.myForm.getItemByName('Field_85').list.subListView.data.length;
      let total = 0;
      for (let i = 0; i < count; i++) {
        let value = null;
        try {
          value = $jq(".tdMaqam div[iamfowner$='3']")[i].tagthis.getData();
        } catch (e) {}
        if (value == null) value = 0;
        value = parseInt(value);
        if (isNaN(value)) value = 0;
        total += value;
      }
      FormView.myForm.getItemByName('Field_86').setData(total);
    }
    setSumReport() {
      let count = FormView.myForm.getItemByName('Field_72').list.subListView.data.length;
      let total = 0;
      for (let i = 0; i < count; i++) {
        let value = null;
        try {
          value = $jq(".tdReport div[iamfowner$='3']")[i].tagthis.getData();
        } catch (e) {}
        if (value == null) value = 0;
        value = parseInt(value);
        if (isNaN(value)) value = 0;
        total += value;
      }
      total = total * 3;
      total = count * 3;
      if (total > 20) total = 20;
      FormView.myForm.getItemByName('Field_71').setData(total);
    }
    setSumNemoone() {
      let count = FormView.myForm.getItemByName('Field_89').list.subListView.data.length;
      let total = 0;
      for (let i = 0; i < count; i++) {
        let value = null;
        try {
          value = $jq(".tdNemoone div[iamfowner$='3']")[i].tagthis.getData();
        } catch (e) {}
        if (value == null) value = 0;
        value = parseInt(value);
        if (isNaN(value)) value = 0;
        total += value;
      }
      if (total > 40) total = 40;
      FormView.myForm.getItemByName('Field_112').setData(total);
    }
    setSumD(){
      let total = 0;
      let a = FormView.myForm.getItemByName('Field_64').getData();
      if (a == "") a = 0;
      let b = FormView.myForm.getItemByName('Field_66').getData();
      if (b == "") b = 0;
      let c = FormView.myForm.getItemByName('Field_68').getData();
      if (c == "") c = 0;
      let e = FormView.myForm.getItemByName('Field_69').getData();
      if (e == "") e = 0;
      let f = FormView.myForm.getItemByName('Field_70').getData();
      if (f == "") f = 0;
      let g = FormView.myForm.getItemByName('Field_84').getData();
      if (g == "") g = 0;
      let h = FormView.myForm.getItemByName('Field_73').getData();
      if (h == "") h = 0;
      let i = FormView.myForm.getItemByName('Field_74').getData();
      if (i == "") i = 0;
      let j = FormView.myForm.getItemByName('Field_75').getData();
      if (j == "") j = 0;
      let k = FormView.myForm.getItemByName('Field_76').getData();
      if (k == "") k = 0;
      let l = FormView.myForm.getItemByName('Field_77').getData();
      if (l == "") l = 0;
      let m = FormView.myForm.getItemByName('Field_78').getData();
      if (m == "") m = 0;
      let n = FormView.myForm.getItemByName('Field_79').getData();
      if (n == "") n = 0;
      let o = FormView.myForm.getItemByName('Field_80').getData();
      if (o == "") o = 0;
      let p = FormView.myForm.getItemByName('Field_81').getData();
      if (p == "") p = 0;
      let q = FormView.myForm.getItemByName('Field_82').getData();
      if (q == "") q = 0;
      let r = FormView.myForm.getItemByName('Field_83').getData();
      if (r == "") r = 0;
      let s = FormView.myForm.getItemByName('Field_71').getData();
      if (s == "") s = 0;
      let t = FormView.myForm.getItemByName('Field_86').getData();
      if (t == "") t = 0;
      let u = FormView.myForm.getItemByName('Field_111').getData();
      if (u == "") u = 0;
      let v = FormView.myForm.getItemByName('Field_112').getData();
      if (v == "") v = 0;
      total = a + b + c + e + f + g + h + i + j + k + l + m + n + o + p + q + r + s + t + u + v;
      FormView.myForm.getItemByName('Field_113').setData(total);
    }



    setSums() {
      this.setAvg3sal();
      this.setSumKhedmat();
      this.setSumMadrak();
      this.setSumEjraee();
      this.setSumKargorooh();
      this.setSumA();
      this.setSumB();
      this.setSumLearn();
      this.setSumSoft();
      this.setSumLang();
      this.setSumFardi();
      this.setSumTajrobe();
      this.setSumC();
      this.setSumTashvigh();
      this.setSumMaqam();
      this.setSumReport();
      this.setSumNemoone();
      this.setSumD();
    }
    loadForm() {
      this.setStageAppearance();
      this.setSums();
      this.setReadOnly();
      this.setIntervalForCompletedField();
    }
    setIntervalForCompletedField() {
      let that = this;
      setInterval(function() {
        $jq("div[iamfowner$='0']").parent().css('overflow', 'visible');
        that.setSumKhedmat();
        that.setSumMadrak();
        that.setSumEjraee();
        that.setSumKargorooh();
        that.setSumLearn();
        that.setSumSoft();
        that.setSumLang();
        that.setSumFardi();
        that.setSumTajrobe();
        that.setSumTashvigh();
        that.setSumMaqam();
        that.setSumReport();
        that.setSumNemoone();
      }, 1000);
    }
    checkScoreLimitation() {
      return true;
    }
    setStageAppearance() {
      let stageNumber = FormView.myForm.getItemByName('Field_115').getData();
      this.hiddenAllFieldset();
      switch (stageNumber) {
        case "1":
          this.showFieldset(1);
          break;
        case "2":
          this.showFieldset(1);
          this.showFieldset(2);
          break;
        case "3":
          this.showFieldset(1);
          this.showFieldset(2);
          this.showFieldset(3);
          break;
        case "4":
          this.showFieldset(1);
          this.showFieldset(2);
          this.showFieldset(3);
          this.showFieldset(4);
          break;
        case "5":
          this.showFieldset(1);
          this.showFieldset(2);
          this.showFieldset(3);
          this.showFieldset(4);
          this.showFieldset(5);
          break;
        case "6":
          this.showFieldset(1);
          this.showFieldset(2);
          this.showFieldset(3);
          this.showFieldset(4);
          this.showFieldset(5);
          this.showFieldset(6);
          break;
        case "7":
          this.showFieldset(1);
          this.showFieldset(2);
          this.showFieldset(3);
          this.showFieldset(4);
          this.showFieldset(5);
          this.showFieldset(7);
          break;
        case "8":
          this.showFieldset(1);
          this.showFieldset(2);
          this.showFieldset(3);
          this.showFieldset(4);
          this.showFieldset(5);
          this.showFieldset(8);
          break;
        case "9":
          this.showFieldset(1);
          this.showFieldset(2);
          this.showFieldset(3);
          this.showFieldset(4);
          this.showFieldset(5);
          this.showFieldset(9);
          this.showFieldset(10);
          break;
        case "10":
            this.showFieldset(1);
            this.showFieldset(2);
            this.showFieldset(3);
            this.showFieldset(4);
            this.showFieldset(5);
            this.showFieldset(6);
            this.showFieldset(7);
            this.showFieldset(8);
            this.showFieldset(9);
            this.showFieldset(10);
            break;
      }
    }
    hiddenAllFieldset() {
      $jq(".fieldSet1").css("display", "none");
      $jq(".fieldSet2").css("display", "none");
      $jq(".fieldSet3").css("display", "none");
      $jq(".fieldSet4").css("display", "none");
      $jq(".fieldSet5").css("display", "none");
      $jq(".fieldSet6").css("display", "none");
      $jq(".fieldSet7").css("display", "none");
      $jq(".fieldSet8").css("display", "none");
      $jq(".fieldSet9").css("display", "none");
      $jq(".fieldSet10").css("display", "none");
    }
    showFieldset(stage) {
      $jq(".fieldSet" + stage).css("display", "");
    }
  };
  var waitInterval = setInterval(function() {
    if (FormView && FormView.myForm) {
      let instance = new mainClass();
      window.codeSet = instance;
      window.codeSet.loadForm();
    }
    clearInterval(waitInterval);
  }, 300);
};
