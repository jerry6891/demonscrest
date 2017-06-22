/* Shortcode Button Slide */

function pixflow_btnSlide(btnId) {
    "use strict";

    // Calculate button width, use for animation effect
    if (btnId == "staticValue") {
        var $shortcodeBtn = $('.shortcode-btn'),
            $btnSlide = $shortcodeBtn.find('.slide'),
            $btnSlideStandard = $shortcodeBtn.find('.slide.button-standard'),
            $btnSlideSmall = $shortcodeBtn.find('.slide.button-small'),
            $btnSlideTxt = $shortcodeBtn.find('.slide span'),
            $btnSlideStandardWidth, $btnSlideSmallWidth, btnIdTemp, $btnAttr;

        $btnSlide.each(function () {

            btnIdTemp = $(this),
                $btnSlideTxt = btnIdTemp.find('span');

            $btnSlideTxt.css({position: 'relative', display: 'table'});
            btnIdTemp.css({'width': 'auto'})

            if (btnIdTemp.hasClass("button-standard")) {
                $btnSlideStandardWidth = btnIdTemp.outerWidth();
                $btnSlideStandard.css({'width': '52px'});
            }
            else if (btnIdTemp.hasClass("button-small")) {
                $btnSlideSmallWidth = btnIdTemp.outerWidth();
                $btnSlideSmall.css('width', '40px');
            }

            $btnSlideTxt.css({position: 'absolute', display: 'table'});
            btnIdTemp.addClass('slide-transition');

            $btnSlideStandard.hover(function () {
                $(this).css({width: $btnSlideStandardWidth});
                $(this).find('span').css({opacity: 1, left: '52px'});
            }, function () {
                $(this).css('width', '52px');
                $(this).find('span').css({opacity: 0, left: '25px'});
            });

            $btnSlideSmall.hover(function () {
                $(this).css('width', $btnSlideSmallWidth);
                $(this).find('span').css({opacity: 1, left: '40px'});
            }, function () {
                $(this).css('width', '40px');
                $(this).find('span').css({opacity: 0, left: '17px'});
            });

        });
    } else {

        var $shortcodeBtn = $('#' + btnId),
            $btnSlide = $shortcodeBtn.find('.slide'),
            $btnSlideStandard = $shortcodeBtn.find('.slide.button-standard'),
            $btnSlideSmall = $shortcodeBtn.find('.slide.button-small'),
            $btnSlideTxt = $shortcodeBtn.find('.slide span'),
            $btnSlideStandardWidth, $btnSlideSmallWidth, btnIdTemp, $btnAttr;

        $btnSlideTxt.css({position: 'relative', display: 'inline-block'});
        $btnSlide.css('width', 'inherit');

        btnIdTemp = $('.' + btnId);

        if (btnIdTemp.hasClass("button-standard")) {
            $btnSlideStandardWidth = btnIdTemp.outerWidth(true);
            $btnSlideStandard.css('width', '52px');
        }
        else if (btnIdTemp.hasClass("button-small")) {
            $btnSlideSmallWidth = btnIdTemp.outerWidth(true);
            $btnSlideSmall.css('width', '40px');
        }

        $btnSlideTxt.css({position: 'absolute', display: 'table'});
        btnIdTemp.addClass('slide-transition');

        $btnSlideStandard.hover(function () {
            $(btnIdTemp).css({width: $btnSlideStandardWidth});
            $(btnIdTemp).find('span').css({opacity: 1, left: '52px'});
        }, function () {
            $(btnIdTemp).css('width', '52px');
            $(btnIdTemp).find('span').css({opacity: 0, left: '25px'});
        });

        $btnSlideSmall.hover(function () {
            $(this).css('width', $btnSlideSmallWidth);
            $(this).find('span').css({opacity: 1, left: '40px'});
        }, function () {
            $(this).css('width', '40px');
            $(this).find('span').css({opacity: 0, left: '17px'});
        });

    }

    var width = $btnSlide.css('width'),
        $btnAnimation = $shortcodeBtn.find('.animation'),
        $btnText = $btnAnimation.find('span'),
        $btnIcon = $btnAnimation.find('.button-icon');


    if ($(window).width() < 1025) {
        $btnSlide.click(function () {
            if ($(this).css('width') == width) {
                $(this).trigger('mouseenter');
                return false;
            }
        })
    }

}

function pixflow_load_btn_slide(){
    if( $('.shortcode-btn .slide').length )
        pixflow_btnSlide("staticValue");
}

window_load_functions.pixflow_load_btn_slide = [];