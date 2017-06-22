function pixflow_showcase_moved($moveing, $carouselImages) {
    "use strict";

    var current = $moveing,
        all = $carouselImages.length;

    $carouselImages.find('.showcase-overlay-first').remove();
    $carouselImages.find('.showcase-overlay-second').remove();

    for (var i = 0; i < all; i++) {
        if (current.index() == all - 1)
            current = $carouselImages.first();
        else
            current = current.next();
        if (i == 0 || (i == 3 && all == 5) || (i == 1 && all == 3)) {
            current.append('<div class="showcase-overlay-first"></div>')
        }
        if ((i == 1 && all == 5) || (i == 2 && all == 5)) {
            current.append('<div class="showcase-overlay-second"></div>')
        }
    }
}

// @TODO : refactor
function pixflow_showcaseHover() {
    'use strict';
    var halfHeight = $(window).height() * 0.5,
        halfWidth = $(window).width() * 0.5,
        rotationLimit = 20;

    if (pixflow_isTouchDevice() && $(window).width() <= 1280)
        return;

    $('.showcase a').each(function () {
        $(this).on('mouseenter', function () {
            var $this = $(this);
            $this.addClass('smooth-rotation');
            setTimeout(function () {
                $this.removeClass('smooth-rotation')
            }, 350)
        })
        $(this).on('mousemove', function (event) {
            var rotateY = ((-event.pageX + halfWidth) / halfWidth) * rotationLimit,
                rotateX = ((event.pageY - halfHeight) / halfHeight) * rotationLimit,
                bodyScrollLeft = $('body').get(0).scrollLeft,
                offsets = $(this).get(0).getBoundingClientRect(),
                offsetX = 0.52 - (event.pageX - offsets.left - bodyScrollLeft) / $(window).width();
            rotateY = rotateY > rotationLimit ? rotationLimit : rotateY;
            rotateY = rotateY < -rotationLimit ? -rotationLimit : rotateY;
            rotateX = rotateX > rotationLimit ? rotationLimit : rotateX;
            rotateX = rotateX < -rotationLimit ? -rotationLimit : rotateX;
            $(this).css({'transform': 'perspective(2000px) rotateX(0deg) rotateY(' + rotateY + 'deg' + ') translateX(' + offsetX * -10 + 'px) translateZ(0px)'});
        })
        $(this).on('mouseleave', function () {
            var $this = $(this);
            $this.addClass('smooth-rotation');
            $this.css({'transform': 'rotateX(0deg) rotateY(0deg) translateZ(0px)'});
            setTimeout(function () {
                $this.removeClass('smooth-rotation')
            }, 350)
        })
    })
}
document_ready_functions.pixflow_showcaseHover = [];
window_resize_functions.pixflow_showcaseHover = [];