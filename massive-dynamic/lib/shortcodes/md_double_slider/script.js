function pixflow_doubleSlider(id,bg,fg,autoplay,duration){
    "use strict";
    function rgba(rgb){
        if(rgb.indexOf('rgba')!= -1) return rgb;
        if(rgb.indexOf('#')!=-1){
            var h = rgb.replace('#', '');
            h =  h.match(new RegExp('(.{'+h.length/3+'})', 'g'));

            for(var i=0; i<h.length; i++)
                h[i] = parseInt(h[i].length==1? h[i]+h[i]:h[i], 16);

            h.push('.8')
            return 'rgba('+h.join(',')+')';

        }
        rgb = rgb.replace(')',',.8)');
        rgb = rgb.replace('rgb','rgba');
        return rgb;
    }
    if( $(window).width() <= 800  ){
        bg[0] = rgba(bg[0]);
    }
    $('#'+ id +' .double-slider-text-container').css('background-color',bg[0]);
    $('#'+ id +' .double-slider-nav a').css('color',fg[0]);
    $('#'+ id +' .double-slider-image-container').flexslider({
        animation: "fade",
        slideshow: autoplay,
        slideshowSpeed: duration,
        animationSpeed: 600,
        touch: false
    })
    $('#'+ id +' .double-slider-text-container').flexslider({
        animation: "slide",
        slideshow: autoplay,
        slideshowSpeed: duration,
        animationSpeed: 600,
        useCSS:false,
        touch: false,
        before: function(slider){
            if( $(window).width() <= 800  ){
                bg[slider.animatingTo] = rgba(bg[slider.animatingTo]);
            }
            $('#'+ id +' .double-slider-text-container').css('background-color',bg[slider.animatingTo]);
            $('#'+ id +' .double-slider-nav a').css('color',fg[slider.animatingTo]);
            if(slider.hasClass('double-slider-text-container')){
                var to = (slider.direction == 'next' && slider.animatingTo == 0) ? slider.count : slider.animatingTo;
                if(slider.currentSlide < to && slider.direction == 'next'){
                    slider.slides.eq(slider.currentSlide).find('.double-slider-sub-title,.double-slider-title,.double-slider-description')
                        .css('transform','translateX(-200px)');
                }else {
                    slider.slides.eq(slider.currentSlide).find('.double-slider-sub-title,.double-slider-title,.double-slider-description')
                        .css('transform','translateX(200px)');
                }
                slider.find('.slides').delay(200);
            }
        },
        after: function(slider){
            setTimeout(function(){
                slider.slides.find('.double-slider-sub-title,.double-slider-title,.double-slider-description')
                    .css('transform','');
            },100)
        }
    })
    $('#'+ id +' .double-slider-prev').click(function(e){
        e.preventDefault();
        $(this).closest('.double-slider').find('.flex-direction-nav .flex-prev').click();
        return false;
    })
    $('#'+ id +' .double-slider-next').click(function(e){
        e.preventDefault();
        $(this).closest('.double-slider').find('.flex-direction-nav .flex-next').click();
        return false;
    })
}