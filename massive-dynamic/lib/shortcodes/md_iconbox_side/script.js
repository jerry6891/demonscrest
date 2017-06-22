function pixflow_iconBox() {
    'use strict';

    if (!$('.iconbox-side .icon-background').length)
        return;

    $('.iconbox-side').each(function () {

        if ($(this).find('.icon-background').length) {

            var containerWidth = $(this).find('.iconbox-side-container').outerWidth(true),
                iconWidth = $(this).find('.icon').width(),
                colWidth = $(this).parents('div[class *= "vc_col"]').width(),
                iconboxWidth = containerWidth + iconWidth + 20; // 81 is icon width , 20 padding left and right

            setTimeout(function () {
                if (iconboxWidth > colWidth) {
                    $(this).addClass('responsive');
                } else {
                    $(this).removeClass('responsive');
                }
            }, 200);
        }
    });

}

document_ready_functions.pixflow_iconBox = [];
window_resize_functions.pixflow_iconBox = [];