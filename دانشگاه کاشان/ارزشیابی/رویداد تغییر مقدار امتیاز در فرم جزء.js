listener = function(event){
    var waitInterval = setInterval(function () {
        if (FormView && FormView.myForm) {

            window.codeSet.setTotalScore();
        }

        clearInterval(waitInterval);
    }, 300);
}

