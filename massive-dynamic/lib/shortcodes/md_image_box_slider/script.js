function pixflow_imageBoxSlider(id, height) {
    "use strict";
    var $ = (jQuery),
        $btnIdSlide = $('.' + id),
        $imgSlider = $('#' + id);

    if ($btnIdSlide.length)
        $btnIdSlide.attr("data-width", "." + id);

    // change height
    $imgSlider.find('.slides').css({height: height + 'px'});
    $imgSlider.find('.slides').css({'max-height': $(window).height()});
    if (typeof $.flexslider == 'function') {
        $imgSlider.flexslider({
            animation: $imgSlider.attr('data-effect'),
            slideshow: true,
            animationLoop: true,
            controlNav: false,
            easing: "swing",
            smoothHeight: false,
            startAt: 0,
            slideshowSpeed: $imgSlider.attr('data-speed'),
            directionNav: false,
            touch: true,
            animationSpeed: 1200,
        });
    }
}

window_load_functions.pixflow_imageBoxSlider = [];