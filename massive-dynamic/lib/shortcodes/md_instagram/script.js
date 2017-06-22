function pixflow_instagramShortcode() {
    'use strict';
    var $instagram = $('.instagram');

    if (!$instagram.length) return;

    $instagram.each(function () {
        var $this = $(this),
            $items = $this.find('.photo-list .item'),
            itemsMargin = 33,
            col;

        function insta_itemSize() {
            'use strict';

            var instagramWidth = Math.floor($this.width() - 1);
            if (instagramWidth > $(window).width()) {
                instagramWidth = $(window).width() - 10;
            }
            col = 0;

            if (instagramWidth > 1200) {
                col = 4;
            }
            else if (instagramWidth <= 1200 && instagramWidth > 768) {
                col = 3;
            }
            else if (instagramWidth <= 768 && instagramWidth > 480) {
                col = 2;
            }
            else {
                col = 1;
                itemsMargin = 0;
            }

            if ($(window).width() < 1440 && $(window).width() > 1024) {
                itemsMargin = 15;
            }
            else if ($(window).width() <= 1024 && $(window).width() > 767) {
                itemsMargin = 10;
            }
            instagramWidth = instagramWidth - (col * (itemsMargin * 2));
            col = Math.floor(instagramWidth / col);

            $items.each(function () {

                var $item = $(this),
                    $itemImage = $item.find('.item-image');

                $item.css({
                    'width': col + 'px',
                    'margin-left': itemsMargin + 'px',
                    'margin-right': itemsMargin + 'px'
                });

                $itemImage.css({
                    'width': col + 'px',
                    'height': col + 'px'
                });

                if ($item.find('.video_instagram').length) {
                    var temp = $item.find('.video_instagram');
                    pixflow_videoShortcode(temp.attr('data-id'), '<source src="' + temp.attr('data-source') + '" type="video/mp4">', '_self', '');
                }
            });
        }

        insta_itemSize();


    })
}

responsive_functions.pixflow_instagramShortcode = [];