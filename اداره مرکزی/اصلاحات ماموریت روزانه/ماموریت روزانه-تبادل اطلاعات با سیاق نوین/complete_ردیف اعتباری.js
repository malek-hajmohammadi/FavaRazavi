Class.create({
    load: function (self) {
        try {
            var arrValue = FormView.myForm.getItemByName('Field_49').fillCompleteFieldCredits();
            return arrValue;
        }catch(e){
            console.log(e);
        }
        return [[0, 'دوره اي نيست']];
    }
});

