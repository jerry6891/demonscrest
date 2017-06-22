function pixflow_sliderCarousel($selector, autoPlay) {
    "use strict";
    setTimeout(function () {
        $selector.not('.flickity-enabled').flickity({
            contain: true,
            initialIndex: 1,
            autoPlay: autoPlay,
            prevNextButtons: false,
            percentPosition: false,
            wrapAround: true,
            pauseAutoPlayOnHover: false,
            selectedAttraction: 0.045,
            friction: 0.5
        });
    }, 100);
}