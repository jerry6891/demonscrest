function pixflow_iconShortcode(id) {
    'use strict';
    var $ = (jQuery);
    /* Replace all SVG images with inline SVG */
    $('.' + id + ' img.svg').each(function () {
        var $img = $(this);
        var imgID = $img.attr('id');
        var imgClass = $img.attr('class');
        var imgURL = $img.attr('src');

        $.ajax({
            url: imgURL,
            processData: false,
            dataType: "html"
        }).done(function (data) {
            // Get the SVG tag, ignore the rest
            var $svg = $(data).find('svg');
            if (!$svg.length) {
                $svg = $(data);
            }
            // Add replaced image's ID to the new SVG
            if (typeof imgID !== 'undefined') {
                $svg = $svg.attr('id', imgID);
            }
            // Add replaced image's classes to the new SVG
            if (typeof imgClass !== 'undefined') {
                if ($svg.attr('class') != undefined)
                    var classes = $svg.attr('class');
                else {
                    for (var i = 0; i < $svg.length; i++) {
                        if ($svg.get(i).getAttribute && $svg.get(i).getAttribute('class') != undefined) {
                            var classes = $svg.get(i).getAttribute('class');
                            break;
                        }
                    }
                }
                $svg = $svg.attr('class', classes + " " + imgClass + ' replaced-svg');
            }

            // Remove any invalid XML tags as per http://validator.w3.org
            $svg = $svg.removeAttr('xmlns:a');

            // Replace image with new SVG
            $img.replaceWith($svg);
            if (typeof pixflow_animateSvgInitiate == 'function') {
                pixflow_animateSvgInitiate();
            }
            if (typeof pixflow_animateSvgExecute == 'function') {
                pixflow_animateSvgExecute();
            }
        });

    });
}