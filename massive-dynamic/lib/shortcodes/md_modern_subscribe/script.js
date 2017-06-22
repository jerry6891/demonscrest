function pixflow_modernSubscribe() {
    "use strict";
    if ($(window).width() <= 1025)
        return;


    $('.modern-subscribe').each(function () {
        var $this = $(this),
            height = $this.css('height');
        $this.find('.subscribe-image').css('height', height);
    });
}

document_ready_functions.pixflow_modernSubscribe = [];