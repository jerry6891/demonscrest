function pixflow_displaySliderShortcode($row) {
    "use strict";

    if ($row == undefined) {
        $row = $('body');
    }

    if ($row.find('.device-slider').length < 1)
        return;
    else {
        try {

            $row.find('.device-slider').each(function () {

                var $macFrame = $(this).find('.mac-frame'),
                    $macFrameWidth = ($macFrame.width() > $(window).width()) ? $(window).width() * .85 : $macFrame.width(),
                    $macFrameHeight = $macFrameWidth * (1.1);

                $(this).find('.slide-image').css({width: $macFrameWidth * (0.95), height: $macFrameHeight * (0.53)});

                $(this).find('.flexslider ul.slides li').each(function () {
                    $(this).css({'height': $macFrameHeight});
                });

            })

        } catch (e) {
        }
    }
}

var $body = $('body');
window_load_functions.pixflow_displaySliderShortcode = [$body];
window_resize_functions.pixflow_displaySliderShortcode = [$body];