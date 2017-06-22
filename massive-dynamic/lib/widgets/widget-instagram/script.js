
function pixflow_instagramWidget() {
    'use strict';

    $('.widget-instagram').each(function () {
        var $this = $(this),
            firstItem = $this.find('.item:first').clone();
        $this.find('.featured-item').css({'height': ($this.find('.featured-item').width())});
        $this.find('.featured-item').append(firstItem).fadeIn('slow');

        $this.find('.item').click(function () {
            $this.find('.featured-item .item').fadeOut().remove();
            $this.find('.featured-item').append($(this).clone());
            $this.find('.featured-item .item').hide().fadeIn(800);
        });
    });
}

document_ready_functions.pixflow_instagramWidget = [];
