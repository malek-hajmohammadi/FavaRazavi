
listener = function(event){

        if (FormView && FormView.myForm) {

            window.codeSet.setPointerTashvigh(event);
            window.codeSet.setSumTashvigh();

            console.log('into change field',this);
        }
}


/*
*
*
* */


listener = function(event){
    var waitInterval = setInterval(function () {
        if (FormView && FormView.myForm) {

            window.codeSet.setTotalScore();
            console.log('into change field');
        }

        clearInterval(waitInterval);
    }, 800);
}



listener = function (event) {
    let semat = FormView.myForm.getItemByName('Field_6').getData();
    SetData(semat, 'fieldxxx');
}