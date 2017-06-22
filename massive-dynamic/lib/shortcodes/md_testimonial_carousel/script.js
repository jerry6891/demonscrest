function pixflow_testimonialCarousel() {
    "use strict";

    if (!$('.testimonial-carousel').length) return;

    var owl = $(".testimonial-carousel#owl-demo");
    owl.closest('.vc_row:not(.vc_inner)').addClass('hide-overflow');
    owl.owlCarousel({
        navigation: true,
        dotsSpeed: 800,
        dragEndSpeed: 800,
        navSpeed: 800,
        fluidSpeed: 800,
        center: true,
        singleItem: true,
        items: 1,
        itemsDesktop: true,
        itemsDesktopSmall: true,
        itemsTablet: true,
        itemsMobile: true
    });
    setTimeout(function () {
        owl.data('owlCarousel').e._onResize();
    }, 1000);
}
window_load_functions.pixflow_testimonialCarousel = [];