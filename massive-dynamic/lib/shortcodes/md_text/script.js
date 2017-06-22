function pixflow_title_slider() {
    'use strict';

    if (!$('.title-slider').length) {
        return;
    }

    $('.md-text-title').each(function (index, value) {
        if ($(this).find('.texts li').length <= 1) {
            $(this).removeData();
            return true;
        }
        $(this).textillate({
            in: {
                effect: 'fadeIn',
                delayScale: 1.5,
                delay: 65
            },
            out: {
                effect: 'fadeOut',
                delayScale: 1.5,
                delay: 65
            },
            loop: true,
            type: 'char',
            minDisplayTime: 0,
            initialDelay: 100
        });

        $(this).find('ul').css('display', 'block');
        var liHeight = 0,
            tempVal = 0;
        $(this).find('ul li').each(function () {
            liHeight = $(this).height();
            if (tempVal < liHeight) {
                tempVal = liHeight;
            }
        });
        $(this).find('ul').css('display', 'none');
        $(this).css('height', tempVal + 'px');

    });

}
document_ready_functions.pixflow_title_slider = [];