function pixflow_portfolioWidget() {
    "use strict";
    if (!$('.widget-md-recent-portfolio').length)
        return;

    $('.widget-md-recent-portfolio').each(function () {

        var $item = $(this).find('.item');
        $item.css({height: $item.width() * .75});

    });
}
responsive_functions.pixflow_portfolioWidget = {
    params : [],
    view_size : {
        less_equal:1440,
        great:1280
    }
};
window_resize_functions.pixflow_portfolioWidget = [];
