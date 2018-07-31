function mycarousel_initCallback(carousel)
{
    // Disable autoscrolling if the user clicks the prev or next button.
    carousel.buttonNext.bind('click', function() {
        carousel.startAuto(0);
    });

    carousel.buttonPrev.bind('click', function() {
        carousel.startAuto(0);
    });

    // Pause autoscrolling if the user moves with the cursor over the clip.
    carousel.clip.hover(function() {
        carousel.stopAuto();
    }, function() {
        carousel.startAuto();
    });
};


jQuery(document).ready(function() {



                       jQuery('#mycarousel').jcarousel({
        auto: 2,
        wrap: 'last',
        initCallback: mycarousel_initCallback
    });

                       jQuery('.main_menu>li').hover(function(){$(this).find("ul").fadeIn(500);$(this).find("ul a").css("width",$(this).find("ul").width())},function(){$(this).find("ul").css("display","none");})
                        jQuery('.catalog dd').css("height",jQuery('.catalog').height()-40)
                        jQuery('.activity dd').css("height",jQuery('.activity').height()-120)

  $(function() {
        $('.gallery a').lightBox();
    });
if ($.browser.msie && $.browser.version == 6) {
   DD_belatedPNG.fix('#footer,#out_wrapper');
}



});
