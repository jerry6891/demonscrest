function pixflow_calendarBlog(obj, count) {
    'use strict';

    var $ = jQuery,
        $id = $('.' + obj),
        $blogTitle = $id.find('.blog-title'),
        $blogContainer = $id.find('.blog-container'),
        $blogOverlay = $id.find('.blog-overlay'),
        $blogDay = $id.find('.blog-day'),
        $blogMonth = $id.find('.blog-month'),
        $blogYear = $id.find('.blog-year'),
        widthBlog = 0,
        movementStrength = 15,
        height = movementStrength / $(window).height(),
        width = movementStrength / $(window).width(),
        widthContainer, wheeling;

    $blogContainer.css({'width': '20%'});

    widthBlog = $id.parent().width();
    widthContainer = widthBlog * 20 / 100;

    if (widthContainer < 300) {
        $blogDay.css('font-size', '45px');
        $blogMonth.css('font-size', '10px');
        $blogYear.css('font-size', '10px');
        $blogYear.css('font-size', '10px');
        $blogTitle.css('font-size', '15px');
    } else {
        $blogDay.css('font-size', '48px');
        $blogMonth.css('font-size', '13px');
        $blogYear.css('font-size', '13px');
        $blogTitle.css('font-size', '18px');
    }
    if (widthBlog < 1360) {
        $blogDay.css('font-size', '48px');
        $blogMonth.css('font-size', '13px');
        $blogYear.css('font-size', '13px');
        $blogTitle.css('font-size', '18px');
        if (widthBlog <= 480) {
            //Blog calendar
            $blogContainer.css('width', '100%');
        } else if (widthBlog <= 991) {
            $blogContainer.css('width', '50%');
        } else if (widthBlog <= 1199) {
            $blogContainer.css('width', '25%');
        } else {
            $blogContainer.css('width', '20%');
        }
    }
    if ($id[0] != undefined) {
        $id[0].onmousemove = function (e) {
            var pageX = e.clientX - ($(window).width() / 2);
            var pageY = e.clientY - ($(window).height() / 2);
            var newvalueX = width * pageX - 10;
            var newvalueY = height * pageY - 10;
            $id.css("background-position", newvalueX + "px     " + newvalueY + "px");
        }
    }

    $('body').on('mousewheel', function () {
        if ($id[0] != undefined) {
            $id[0].onmousemove = function (e) {
                return false;
            }
            clearTimeout(wheeling);
            wheeling = setTimeout(function () {
                $id[0].onmousemove = function (e) {
                    var pageX = e.clientX - ($(window).width() / 2);
                    var pageY = e.clientY - ($(window).height() / 2);
                    var newvalueX = width * pageX - 10;
                    var newvalueY = height * pageY - 10;
                    $id.css("background-position", newvalueX + "px     " + newvalueY + "px");
                }
            }, 100);
        }
    });
    if ($(window).width() < 1025) {
        $blogContainer.click(function () {
            if (!$(this).hasClass('hovered')) {
                $(this).addClass('hovered');
                return false;
            }
        });
        $blogContainer.blur(function () {
            $(this).removeClass('hovered');
        })
    }
}

responsive_functions.pixflow_calendarBlog = ["calendar-blog"];