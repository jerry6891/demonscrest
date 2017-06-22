function pixflow_mobileSliderShortcode($row) {
    "use strict";

    if ($row == undefined) {
        $row = $('body');
    }

    if ($row.find('.mobile-slider').length < 1)
        return;
    else {

        try {

            $row.find('.mobile-slider').each(function () {

                var shortcodeWidth = $(this).find('.flexslider').width(),
                    slideHeight = shortcodeWidth * (0.75),
                    $mobileFrame = $(this).find('.mobile-frame'),
                    $mobileFrameWidth = ($mobileFrame.width() > $(window).width()) ? $(window).width() * .85 : $mobileFrame.width();

                $(this).find('.slide-image').css({width: $mobileFrameWidth - 15, height: $mobileFrameWidth * (1.8)});

                $(this).find('.flexslider ul.slides li').each(function () {
                    $(this).css({'height': $mobileFrameWidth * (1.8)});
                });

            });
        } catch (e) {
        }

    }
}

var $body = $('body');
window_load_functions.pixflow_mobileSliderShortcode = [$body];
window_resize_functions.pixflow_mobileSliderShortcode = [$body];