


$(document).on('click', 'a', function (e) {

    // if (this.hasChildNodes('href') != '#') {
    //     e.preventDefault();

    //     window.history.pushState({}, '', this.href);

    //     $.get(this.href, function (reponse) {
    //         $('body').html(reponse);
    //         chosenSelectInit()
    //     })

    // }
    // if (this.hasChildNodes('render-view')) {


    //     if (this.hasChildNodes('href')) {

    //         window.history.pushState({}, '', this.href);

    //         $.get(this.href, function (reponse) {
    //             $('body').html(reponse);
    //             chosenSelectInit()
    //         })
    //     }
    // }

})





$('.render-view').on('click', function (e) {
    e.preventDefault();
    if (this.hasAttribute('href')) {

        // window.history.pushState({}, '', this.url);


        $.get(this.href, function (reponse) {
            $('.render-class').html(reponse);
            chosenSelectInit()
        })
    }

})


function render(url) {

    $.get(url, function (reponse) {
        $('.render-class').html(reponse);
        chosenSelectInit()
    })

}



$('.render-currency-view').on('click', function (e) {
    e.preventDefault();
    if (this.hasAttribute('href')) {

        $.get(this.href, function (reponse) {
            $('.render-currency-class').html(reponse);

            InitDatePicker();

            chosenSelectInit()
        })
    }

})
