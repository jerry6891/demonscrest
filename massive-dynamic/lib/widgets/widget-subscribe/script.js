function pixflow_subscribeWidget() {
    "use strict";
    var $subscribes = $('.widget-subscribe');
    if (!$subscribes.length) {
        return
    }
    $subscribes.each(function () {
        var $this = $(this);
        $this.find('form.send').submit(function (e) {
            e.preventDefault();


            var $thisForm = $(this),
                $form = $this.find('.mc4wp-form'),
                $textbox = $form.find('[type=email]'),
                index = $('.widget-subscribe').index($this),
                interval = null;
            $textbox.val($thisForm.find('.widget-subscribe-textbox').val());
            if (!$form.length) {
                $this.find('.subscribe-err').css('color', 'rgba(255,0,0,.7)').html(themeOptionValues.mailchimpNotInstalled);
                return false;
            }
            $.ajax({
                method: "POST",
                url: window.location.href.split('?')[0],
                data: $form.serialize(),
                beforeSend: function () {
                    $this.find('.widget-subscribe-button').addClass('subscribe-button-animation');

                    $this.find('.subscribe-textbox').css({border: ''});
                    $this.find('.subscribe-err').html('');
                }
            }).done(function (msg) {
                var $id = $(msg).find('.widget-subscribe:eq(' + index + ')');
                $this.find('.widget-subscribe-button').removeClass('subscribe-button-animation');

                $this.find('.subscribe-err').html($id.find('.mc4wp-response').text());
            });

            return false;
        });
    })

}

document_ready_functions.pixflow_subscribeWidget = [] ;