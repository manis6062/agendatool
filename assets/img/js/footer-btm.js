$(document).ready(function() {

       if($(window).width() < 768){

    //fixing footer at the bottom at any hieght
    var footerHeight = $(".footer").innerHeight();
    $("body").css(
            "padding-bottom", footerHeight + 24 + 'px'
        )
        //fixing footer at the bottom at any hieght on browser resize
    $(window).resize(function() {
        footerHeight = $(".footer").innerHeight();
        $("body").css(
            "padding-bottom", footerHeight + 24 + 'px'
        )
    });

    }

  });