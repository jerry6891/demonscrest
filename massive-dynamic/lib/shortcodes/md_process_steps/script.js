function pixflow_processSteps() {
    'use strict';
    var processSteps = $('.process-steps');

    if (!processSteps.length) return;

    if ($(window).width() > 768) {
        setTimeout(function () { //to calculate it's width in tab shortcodes correctly
            processSteps.each(function () {
                var $this = $(this),
                    containerWidth = $this.width(),
                    steps = $this.find('.step'),
                    circle = steps.find('.circle'),
                    separator = circle.find('.separator'),
                    description = $(this).find('.description'),
                    title = $(this).find('.title'),
                    colPercentage = 0,
                    smallColWidth,
                    lastStepsSize = 0;

                //Getting steps width percentage
                steps.each(function () {
                    if ($(this).hasClass('small')) {
                        colPercentage += 1;
                    } else if ($(this).hasClass('medium')) {
                        colPercentage += 1.32;
                    } else if ($(this).hasClass('large')) {
                        colPercentage += 1.64;
                    }
                });
                smallColWidth = Math.floor(containerWidth / colPercentage);

                //Setting size for each item
                steps.each(function () {
                    var step = $(this),
                        circle = step.find('.circle'),
                        separator = circle.find('.separator'),
                        description = step.find('.description'),
                        title = steps.find('.title'),
                        rightPadding;

                    if (step.hasClass('small')) { //Small Size

                        rightPadding = Math.round(smallColWidth * .36);
                        step.css({
                            'width': smallColWidth + 'px',
                            'padding-right': rightPadding + 'px'
                        });
                        circle.css({
                            'height': smallColWidth - rightPadding + 'px'
                        });
                        separator.css({
                            'width': rightPadding - 30 + 'px'
                        })

                    } else if (step.hasClass('medium')) { //Medium Size

                        rightPadding = Math.round(smallColWidth * .35);
                        step.css({
                            'width': Math.round(smallColWidth * 1.2) + 'px',
                            'padding-right': rightPadding + 'px'
                        });
                        circle.css({
                            'height': Math.round(smallColWidth * 1.2) - rightPadding + 'px'
                        });
                        separator.css({
                            'width': rightPadding - 30 + 'px'
                        })

                    } else if (step.hasClass('large')) { //Large Size

                        rightPadding = Math.round(smallColWidth * .27);
                        step.css({
                            'width': Math.round(smallColWidth * 1.34) + 'px',
                            'padding-right': rightPadding + 'px'
                        });
                        circle.css({
                            'height': Math.round(smallColWidth * 1.34) - rightPadding + 'px'
                        });
                        separator.css({
                            'width': rightPadding - 30 + 'px'
                        })
                    }

                    step.hover(function () {
                        TweenMax.to(circle, 0.4, {'scale': '0.95'});
                        TweenMax.to(description, 0.4, {'padding-top': '0', 'margin-bottom': '30px', 'opacity': '1'});
                    }, function () {
                        TweenMax.to(circle, 0.4, {'scale': '1'});
                        TweenMax.to(description, 0.4, {'padding-top': '30px', 'margin-bottom': '0', 'opacity': '0'});
                    });
                });

                //Circles vertical align and animation starting point
                var largeCircleHeight = $this.find('.step.large .circle').height();
                steps.each(function () {
                    var circle = $(this).find('.circle');

                    if ($(this).is('.small, .medium') && largeCircleHeight != null) {
                        circle.css({
                            'margin-top': (largeCircleHeight - circle.height()) / 2 + 'px',
                            'margin-bottom': (largeCircleHeight - circle.height()) / 2 + 30 + 'px'
                        })
                    }
                    //Setting center points for animation
                    lastStepsSize += $(this).prev().outerWidth();
                    circle.attr('data-animate-start', ((containerWidth - $(this).outerWidth() ) / 2) + ($(this).css('padding-right').replace(/[^-\d\.]/g, '') / 2) - lastStepsSize);

                    circle.css({'left': circle.attr('data-animate-start') + 'px'});

                });

                //Center align process steps
                lastStepsSize += $this.find('.step:last-child').outerWidth();
                var lastCirclePadding = $this.find('.step:last-child').css('padding-right').replace(/[^-\d\.]/g, '') / 2;
                $this.css({'padding-left': lastCirclePadding + (($this.width() - lastStepsSize) / 2)});
                $this.find('.step:last-child').css({
                    'width': $this.find('.step:last-child .circle').css('height'),
                    'padding-right': 0
                });

                $this.addClass('animating');
                TweenMax.staggerTo(circle, 0.8, {scale: '1', opacity: '1'}, 0.2);
                TweenMax.staggerTo(circle, 0.8, {left: '0', delay: 0.3}, 0.1);
                TweenMax.staggerTo(separator, 0.6, {'scaleX': '1', delay: 0.6}, 0.2);
                TweenMax.staggerTo(title, 0.6, {
                    'padding-top': '0',
                    'margin-bottom': '30px',
                    opacity: '1',
                    delay: 0.4
                }, 0.1);
                TweenMax.staggerTo(description, 0.6, {visibility: 'visible', delay: 0.8}, 0.1);
            })
        }, 150);
    }
}
window_resize_functions.pixflow_processSteps = [];
responsive_functions.pixflow_processSteps = [];