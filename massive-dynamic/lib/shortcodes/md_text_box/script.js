function pixflow_textBox() {
    "use strict";
    $(".text-box").each(function (index, element) {

        var $this = $(this),
            iconTimeline = new TimelineLite(),
            titleTimeline = new TimelineLite(),
            masterTimeline = new TimelineLite({paused: true});

        //creating icon timeline
        iconTimeline.to($this.find('.text-box-icon-holder'), 0.7, {
            'margin-top': '-25px',
            'margin-bottom': '25px',
            ease: Expo.easeInOut
        })
            .to($this.find('.text-box-icon-holder'), 0.6, {
                'margin-top': '-20px',
                'margin-bottom': '20px',
                ease: Expo.easeOut
            });

        //Creating title timeline
        titleTimeline.to($this.find('.text-box-title'), 0.7, {
            'margin-top': '2px',
            'margin-bottom': '25px',
            ease: Expo.easeInOut
        })
            .to($this.find('.text-box-title'), 0.6, {'margin-top': '7px', 'margin-bottom': '20px', ease: Expo.easeOut});

        masterTimeline.add(iconTimeline)
            .add(titleTimeline, 0.3)
            .to($this.find('.text-box-description'), 0.4, {'opacity': '1'}, "-= 0.9");

        element.animation = masterTimeline;
    });
    $(".text-box").hover(over, out);
    function over() {
        this.animation.play()
    };
    function out() {
        this.animation.reverse()
    };
}
document_ready_functions.pixflow_textBox = [];