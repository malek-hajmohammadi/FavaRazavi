listener = function (event) {

    class mainClass {



        loadForm() {

        }
        dayAfterToday(date){
            var dateNow=Main.FirstPageParameters.datetime.todayDate;

            var dateArray = date.split("/");
            var date={
                year:dateArray[0],
                month:dateArray[1],
                day:dateArray[2]
            };
            var dateNowArray=date.split("/");
            var today={
                year:dateNow[0],
                month:dateNow[1],
                day:dateNow[2]
            };
            if(date.year>today.year)
                return true;
            if(date.year==today.year && date.month > today.month)
                return true;
            if(date.year==today.year && date.month == today.month && date.day>today.day)
                return true;

            return false;

        }


    };

    var waitInterval = setInterval(function () {
        if (FormView && FormView.myForm) {

            let instance = new mainClass();
            window.codeSet = instance;
            window.codeSet.loadForm();
        }

        clearInterval(waitInterval);
    }, 300);

};


