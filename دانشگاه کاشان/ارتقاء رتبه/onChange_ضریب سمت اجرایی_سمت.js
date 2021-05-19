listener = function(event){

    if(FormView && FormView.myForm && FormView.myForm.getItemByName('Field_36').list && FormView.myForm.getItemByName('Field_36').list.myForm   ) {


        let semat = FormView.myForm.getItemByName('Field_36').list.myForm.getItemByName('Field_8').getData();

        let sanavatKhedmatBaMadrakEhtesabi = FormView.myForm.getItemByName('Field_36').list.myForm.getItemByName('Field_3').getData();

        if(sanavatKhedmatBaMadrakEhtesabi=="")
            sanavatKhedmatBaMadrakEhtesabi=0;
        sanavatKhedmatBaMadrakEhtesabi=parseInt(sanavatKhedmatBaMadrakEhtesabi);

        let emtiaz=sanavatKhedmatBaMadrakEhtesabi*semat;

        FormView.myForm.getItemByName('Field_36').list.myForm.getItemByName('Field_4').setData(emtiaz);

    }

}