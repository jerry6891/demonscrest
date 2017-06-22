
function pixflow_contactForm() {
    "use strict";
    var inputheight;

    if ($('.contact-form').length < 1) {
        return;
    }
    $(".wpcf7").on('invalid.wpcf7', function (e) {
        $('span.wpcf7-not-valid-tip').each(function () {
            $(this).prev().css({'box-shadow': '0 0 2px 1px red'});
        });
    });
    $('.wpcf7-form .form-input input').click(function () {
        $(this).css({'box-shadow': 'none'});
    });

}

document_ready_functions.pixflow_contactForm = [];
window_resize_functions.pixflow_contactForm = [];

function pixflow_contactFormAnimation() {
    "use strict";
    $('.input__field--hoshi').each(function () {
        var $this = $(this);
        $this.focus(function () {
            var $elem = $(this);
            $elem.parent('.wpcf7-form-control-wrap').addClass('focus');
            $elem.parents('.input').addClass('input--filled');
        });
        $this.focusout(function () {
            var $elem = $(this);
            $elem.parent('.wpcf7-form-control-wrap').removeClass('focus');
            if ($elem.val().length < 1) {
                $elem.parents('.input').removeClass('input--filled');
            }
        });

        $this.keyup(function () {
            var $elem = $(this);
            if ($elem.is('.wpcf7-not-valid')) {
                $elem.removeClass('wpcf7-not-valid').removeAttr('style');
                $elem.next('.wpcf7-not-valid-tip').remove();
            }
        })

    })
}

document_ready_functions.pixflow_contactFormAnimation = [];
