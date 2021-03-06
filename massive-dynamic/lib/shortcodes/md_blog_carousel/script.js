function pixflow_fixflickityheight(resized, element) {
    "use strict";
    var timeout = resized ? 100 : 3000;
    window.setTimeout(function () {

        if ($(element).legnth <= 0) {
            return;
        }

        var widthdif = ( parseInt($(window).width()) - parseInt($("main #content").width())  );
        var extraheight = (widthdif > 200) ? 320 : 200;
        if (parseInt($(window).width()) < 1281) {
            extraheight += 100;
        }
        var maxh = 0;
        $(element).each(function () {

            var postcontenth = parseInt($(this).find(".post-content-container").height());
            var postdateh = parseInt($(this).find(".post-date ").height());
            if (maxh < postcontenth + postdateh) {
                maxh = postcontenth + postdateh;
            }
            $(this).height(maxh + extraheight);
        });

    }, timeout);

}

function pixflow_blog_carousel_responsive(){
    $(".post-carousel-container").each(function(){
        pixflow_fixflickityheight(true,"#"+$(this).attr("id"));
    });
}

responsive_functions.pixflow_blog_carousel_responsive = [];