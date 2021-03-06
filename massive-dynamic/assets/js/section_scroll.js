// Detect Brightness of row
function pixflow_getImageBrightness(imageSrc, callback) {
    var img = document.createElement("img");
    var brightness = 0;
    img.src = imageSrc;
    img.classList.add('pixflow-bright-img');
    img.style.display = "none";
    document.body.appendChild(img);

    var colorSum = 0;

    img.onload = function () {
        // create canvas
        var canvas = document.createElement("canvas");
        canvas.width = this.width;
        canvas.height = this.height;

        var ctx = canvas.getContext("2d");
        ctx.drawImage(this, 0, 0);

        var imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        var data = imageData.data;
        var r, g, b, avg;

        for (var x = 0, len = data.length; x < len; x += 4) {
            r = data[x];
            g = data[x + 1];
            b = data[x + 2];

            avg = Math.floor((r + g + b) / 3);
            colorSum += avg;
        }

        brightness = Math.floor(colorSum / (this.width * this.height));
        callback(brightness);
        $('.pixflow-bright-img').remove();
    }
}

var Brightness;
function pixflow_changecolor(brightness) {
    if(brightness > 160){
        $('#pix-nav').removeClass('background--dark').addClass('background--light');
        if($('header').hasClass('top-gather')){
            $('.logo').find('img').attr('src' , $('.logo-img').attr('data-dark-url'));
            $('.gather-menu-icon').css('color' , 'black');
            if($('.navigation-button').length){
                $('.navigation-button').find('span').css('color' , 'black');
            }
            if($('.shopcart-item').length){
                $('.shopcart-item').find('.icon').css('color' , 'black');
            }
            if($('.notification-item').length){
                $('.notification-item').find('.icon').css('color' , 'black');
            }
            if($('.search-item').length){
                $('.search-item').find('.icon').css('color' , 'black');
            }
            if($('.mobile-shopcart').length){
                $('.mobile-shopcart').find('span').css('color' , 'black');
            }
        }
    }else{
        $('#pix-nav').addClass('background--dark').removeClass('background--light');
        if($('header').hasClass('top-gather')){
            $('.logo-img').attr('src' , $('.logo-img').attr('data-light-url'));
            $('.gather-menu-icon').css('color' , '#ffffff');
            if($('.shopcart-item').length){
                $('.shopcart-item').find('.icon').css('color' , '#ffffff');
            }
            if($('.navigation-button').length){
                $('.navigation-button').find('span').css('color' , '#ffffff');
            }
            if($('.notification-item').length){
                $('.notification-item').find('.icon').css('color' , '#ffffff');
            }
            if($('.search-item').length){
                $('.search-item').find('.icon').css('color' , '#ffffff');
            }
            if($('.mobile-shopcart').length){
                $('.mobile-shopcart').find('span').css('color' , '#ffffff');
            }
        }
    }
}

// Detect All row of color
function pixflow_detect_color(index){
    var canvas = document.createElement('canvas') ,
        canvasObj = canvas.getContext('2d') ,
        result ;
    if( $('.vc_row:not(.vc_inner)').eq(index).find('> .row-image').last().length){
        var bgUrl = $('.vc_row:not(.vc_inner)').eq(index).find('> .row-image').last().css('backgroundImage').replace(/.*\s?url\([\'\"]?/, '').replace(/[\'\"]?\).*/, '') ;
        if( bgUrl != "none"){
            var img = new Image();
            img.onload = function() {
                canvasObj.drawImage(img, 0, 0);
                pixflow_getImageBrightness(canvas.toDataURL() , function(brightness){
                    $('.vc_row:not(.vc_inner)').eq(index).attr('data-detect-color' , brightness);
                    return ;
                });
            };
            img.src = bgUrl;
        }
    }else{
        canvas.setAttribute('width' , '20px');
        canvas.setAttribute('height' , '20px');
        canvasObj.beginPath();
        canvasObj.rect(0, 0 , 20 , 20);
        canvasObj.fillStyle = $('.vc_row:not(.vc_inner)').eq(index).attr('data-bgcolor');
        canvasObj.fill();
        pixflow_getImageBrightness(canvas.toDataURL() , function(brightness){
            $('.vc_row:not(.vc_inner)').eq(index).attr('data-detect-color' , brightness);
            return ;
        });
    }
}

function pixflow_setColor(row) {
    pixflow_changecolor( $('.vc_row:not(.vc_inner)').eq(row).attr('data-detect-color'));
}
var windowHeight;
var doScroll = true ;
var footerShow = false ;
function pixflow_set_style_for_onepage(){
    $('body').css({overflow : 'hidden' , height: '100%'});
    var style = document.createElement('style');
    style.type = 'text/css';
    if (style.styleSheet) {
        style.styleSheet.cssText = '::-webkit-scrollbar {display: none;} html { overflow: -moz-scrollbars-none; } body{overflow: hidden;} .gather-overlay{position:fixed !important}';
    } else {
        style.appendChild(document.createTextNode('::-webkit-scrollbar {display: none;} html { overflow: -moz-scrollbars-none; } .gather-overlay{position:fixed !important}'));
    }
    document.getElementsByTagName('head')[0].appendChild(style);
    var header_top =parseInt($('header:not(.header-clone)').css('top'));
    if ($('body').hasClass('admin-bar')){
        header_top+=32;
    }
    $('body.one_page_scroll:not(.compose-mode) header:not(.header-clone)').css({top:header_top});
    return ;
}

// Set the height of the screen for row
$row_element = $('.vc_row:not(.vc_inner)') ,
    $rowNum =  $row_element.length ;
function pixflow_setHight() {
    pixflow_set_style_for_onepage();
    $screenHeight = windowHeight = $(window).height();
    $vcRow = $('.vc_row:not(.vc_inner)') ;
    var count =0 ;
    $('.layout').css('min-height',$screenHeight);
    $vcRow.each(function(){
        pixflow_detect_color(count);
        $(this).attr('data-index' , count);

        $(this).addClass('full-page').css({
            'height' : $screenHeight + 'px' ,
            'top' : $screenHeight + 'px' ,
            zIndex: 8
        });
        count++;
    });
    $vcRow.first().addClass('row-active').css({
        'top': '0px' ,
        zIndex: 10
    });
    return true;
}

// create bullet for onepage
var $index = $lastIndex = 0 ;
function pixflow_add_event_for_bullet($rowNum){
    $('body').on('click', '#pix-nav span', function () {
        var currentIndex = parseInt( $('.bullet-active').find('span').attr('data-index') );
        var moveTo = parseInt( $(this).attr('data-index') );
        if (moveTo > currentIndex) {
            $lastIndex = $index;
            if($index == moveTo) {
                return ;
            }
            $index = moveTo ;
            pixflow_scrollPage('down' , $rowNum  , type = 'click');
        }else{
            if(footerShow == true){
                pixflow_footer_scroll_up_visible();
                footerShow = false ;
            }
            $lastIndex = $index;
            if($index == moveTo) {
                return ;
            }
            $index = moveTo ;
            pixflow_scrollPage('up' , $rowNum  , type = 'click');
        }
    });
}

function  pixFlow_createBullet($rowNum , where){
    var bSource = '<div id="pix-nav" class="background--light"><ul>' ;
    bSource += '<li class="bullet-active" ><span data-index="0"></span></li>';
    for(var count =1; count < $rowNum ; count++){
        bSource += '<li><span data-index="' + count  + '"></span></li>';
    }
    bSource += "</ul></div>";
    $('body').append(bSource);
    $('#pix-nav').css('top', ( $(window).height() / 2 ) + 'px');
    pixflow_add_event_for_bullet($rowNum);
}

//check thw height of row if nicescroll is needed
function pixflow_check_for_nicescoll(index){
    var next = $('.vc_row:not(.vc_inner)').eq(index);
    if(next.hasClass('row-over-height'))
        return ;
    if(next.find('>.wrap').height() > $(window).height()){
        next.addClass('row-over-height').find('>.wrap').niceScroll({
            horizrailenabled: false,
            cursorcolor: "rgba(204, 204, 204, 0.2)",
            cursorborder: "1px solid rgba(204, 204, 204, 0.2)",
            cursorwidth: "2px",
            touchbehavior: true,
            preventmultitouchscrolling: false,
            enablescrollonselection: false
        });

    }
}

function pixflow_footer_scroll_up_visible(){
    if($('footer').attr('data-footer-status') == 'off'){
        doScroll = true ;
        return ;
    }
    var Footerheight = $('footer').height();
    thisRow = $('.vc_row:not(.vc_inner)').last();
    TweenMax.to(thisRow , 1 , {top:"0px", repeatDelay:0.5, ease:Power4.easeOut});
    TweenMax.to( $('footer') , 1 , {bottom:"-" + Footerheight +"px" , opacity: 0 , repeatDelay:0.5, ease:Power4.easeOut, onComplete:function(){
        doScroll = true ;
        footerShow = false;
        pixflow_set_event_on_window($rowNum);
    }});
    return ;
}

function pixflow_back_shortcode_to_position(){
    $('.row-active').find('.has-animation').each(function(){
        var $this = $(this),
            animation_speed = Number($this.attr('data-animation-speed')) * 0.001,
            animation_delay = Number($this.attr('data-animation-delay')),
            animation_position = $this.attr('data-animation-position'),
            animation_easing = $this.attr('data-animation-easing'),
            move = 50;
        $(this).removeClass('show-animation');
        var shortcode_animation_list = [ animation_position , $this , animation_speed , animation_delay , animation_easing , move ] ;
        pixflow_get_shortcode_back_to_position(shortcode_animation_list);
    });
}

function pixflow_scroll_up_animate(type){
    var this_row , prev_row ;
    if (type != 'click') {
        this_row = $('.vc_row:not(.vc_inner)').eq($index);
        prev_row = this_row.prev();
    } else {
        this_row =  $('.vc_row:not(.vc_inner)').eq($lastIndex);
        prev_row =  $('.vc_row:not(.vc_inner)').eq($index);
    }
    this_row.css({zIndex:9});
    TweenMax.to(this_row , 1 , { scale: .75 , opacity:0 , ease:Power4.easeOut , onComplete:function(){
        if(type != 'click'){
            TweenMax.to(this_row, 0, {top: windowHeight  , scale: 1 , opacity:1 , zIndex: 8 , ease: Power4.easeOut});
        }else{
            var count =0;
            $('.vc_row:not(.vc_inner)').each(function(){
                if(count > parseInt(prev_row.attr('data-index')) ){
                    TweenMax.to($(this), 0, {top: windowHeight  , scale: 1 , opacity:1 , zIndex: 8 , ease: Power4.easeOut});
                }
                count++;
            });
        }
    }});
    setTimeout(function(){
        pixflow_shortcodeAnimationScroll();
        pixflow_onepage_scroll_svg_animate();
    } , 800 );
    prev_row.css({ zIndex : '10'});
    TweenMax.to(prev_row , 1 , {top:"0px" , ease:Power4.easeOut, delay:.1 ,onComplete:function(){
        doScroll = true ;
        pixflow_set_event_on_window($rowNum);
    }});
    if(type != 'click'){
        $index--;
    }
    pixflow_back_shortcode_to_position();
    $('.row-active').removeClass('row-active');
    $('.vc_row:not(.vc_inner)').eq($index).addClass('row-active');
    return ;
}

// Doing scroll Up for one page demo
function pixflow_scroll_up($rowNum , type){
    doScroll = false ;
    if(footerShow == true){
        pixflow_footer_scroll_up_visible();
        return ;
    }
    if($index <= 0 && type != 'click'){
        $index = 0;
        doScroll = true ;
        setTimeout(function(){
            pixflow_set_event_on_window($rowNum);
        } , 1);
        return ;
    }
    $('.bullet-active').removeClass('bullet-active');
    if( type != 'click'){
        $('#pix-nav').find('li').eq($index - 1).addClass('bullet-active');
    }else{
        $('#pix-nav').find('li').eq($index).addClass('bullet-active');
    }
    if(type != 'click'){
        pixflow_setColor($index - 1);
        pixflow_check_for_nicescoll($index - 1);
    }else{
        pixflow_setColor($index);
        pixflow_check_for_nicescoll($index);
    }
    pixflow_scroll_up_animate(type);
}

//ready for scroll footer
function pixflow_footer_visibilty($rowNum){
    if($('footer').attr('data-footer-status') == 'off'){
        doScroll = true ;
        return ;
    }
    footerShow = true ;
    $index = $rowNum - 1;
    var Footerheight = $('footer').height();
    thisRow = $('.vc_row:not(.vc_inner)').last();
    TweenMax.to(thisRow , 1 , {top:"-" + Footerheight +"px", ease:Power4.easeOut});
    TweenMax.to( $('footer') , 1 , {bottom:"0px" , opacity: 1 , ease:Power4.easeOut, onComplete:function(){
        doScroll = true ;
        pixflow_set_event_on_window($rowNum);
    }});
    return ;
}

//anmiate the rows on scrolling down
function pixflow_scroll_down_animate(type) {
    setTimeout(function () {
        pixflow_setColor($index);
    }, 500);
    if (type == 'click') {
        if ($lastIndex == $index)
            $lastIndex = $index - 1;
        this_row = $('.vc_row:not(.vc_inner)').eq($lastIndex);
        next_row = $('.vc_row:not(.vc_inner)').eq($index);

    } else {
        this_row = $('.vc_row:not(.vc_inner)').eq($index);
        next_row = this_row.next();
    }
    this_row.css({zIndex: 9});
    setTimeout(function(){
        pixflow_shortcodeAnimationScroll();
        pixflow_onepage_scroll_svg_animate();
    } ,800);
    TweenMax.to(this_row, 1 , {
        scale: .75, opacity: 0, ease: Power4.easeOut, onComplete: function () {
            if (type != 'click') {
                TweenMax.to(this_row, 0, {top: '-' + windowHeight  , scale: 1 , opacity:1 , zIndex: 8 , ease: Power4.easeOut});
            } else {
                var count = 0;
                $('.vc_row:not(.vc_inner)').each(function () {
                    if (count == parseInt(next_row.attr('data-index'))) {
                        return;
                    }
                    TweenMax.to($(this), 0, {top: '-' + windowHeight  , scale: 1 , opacity:1 , zIndex: 8 , ease: Power4.easeOut});
                    count++;
                });
            }
            doScroll = true;
        }
    });

    next_row.css({zIndex: 10});
    TweenMax.to(next_row, 1 , {top: "0px", ease: Power4.easeOut , delay:.1, onComplete: function () {
        pixflow_set_event_on_window($rowNum);
    }
    });
    if (type != 'click') {
        $index++;
    }
    pixflow_back_shortcode_to_position();
    $('.row-active').removeClass('row-active');
    $('.vc_row:not(.vc_inner)').eq($index).addClass('row-active');
}

// Doing scroll down for one page demo
function pixflow_scroll_down($rowNum , type){
    doScroll = false ;
    if($index >= $rowNum - 1 && type != 'click'){
        pixflow_footer_visibilty($rowNum);
        setTimeout(function(){
            pixflow_set_event_on_window($rowNum);
        } , 10 );
        return ;
    }
    pixflow_check_for_nicescoll();
    $('.bullet-active').removeClass('bullet-active');
    if(type == 'click'){
        $('#pix-nav').find('li').eq($index).addClass('bullet-active');
        pixflow_check_for_nicescoll($index);
    }else{
        $('#pix-nav').find('li').eq($index + 1).addClass('bullet-active');
        pixflow_check_for_nicescoll($index + 1);
    }
    pixflow_scroll_down_animate(type);
}
// detect direction of scroll
var first_time = true ;
function pixflow_scrollPage(direction , $rowNum , type){
    if( doScroll == false)
        return ;
    switch (direction){
        case'up':
            pixflow_scroll_up($rowNum , type);
            break;
        case 'down':

            pixflow_scroll_down($rowNum , type);
            break;
        default:
            return false ;
            break;
    }
}

function pixflow_first_time_one_page($rowNum) {
    pixflow_setHight();
    pixFlow_createBullet($rowNum);
    if (first_time == true) {
        pixflow_setColor(0);
        first_time = false;
    }
}

function pixflow_set_height_in_customizer($row_obj){
    var window_screen = parseInt($(window).height()) ;
    $row_obj.each(function(){
        $(this).css({
            minHeight : window_screen + 'px'
        });
    });
}

function pixflow_one_page_for_customizer(){
    $row_obj = $('.vc_row:not(.vc_inner)') ;
    pixflow_set_height_in_customizer($row_obj);
}

function pixflow_gather_fullpage_header(){
    if($('header').hasClass('top-gather')){
        $('.menu-item a').click(function(){
            var row_src , row_index ;
            setTimeout(function(){
                row_src = window.location.hash ;
                row_src = row_src.replace('#' , '');
                row_index = parseInt($('#' + row_src).closest('.vc_row:not(.vc-inner)').attr('data-index')) ;
                if($(window).width() <= 1280 && pixflow_isTouchDevice())
                    $('.navigation-button').click();
                setTimeout(function(){
                    $('#pix-nav').find('li').eq(row_index).find('span').click();
                } , 700);
            } , 10 );
        });
    }
}

function pixflow_row_scroll($rowNum , direction){
    var $nice_scroll = $('.row-active').find('.nicescroll-cursors').last();
    if( (($nice_scroll.offset().top + $nice_scroll.height()) + 4 ) >= $nice_scroll.parent().height()  && direction == 'down'){
        pixflow_scrollPage('down' , $rowNum );
        return true ;
    }
    if( parseInt( $nice_scroll.css('top') ) == 0 && direction == 'up'){
        pixflow_scrollPage('up' , $rowNum );
        return true ;
    }

}

function pixflow_set_event_on_window($rowNum){
    $(window).on('wheel', function(event) {
        if (event.originalEvent.deltaY > 0) {
            pixflow_scrollPage('down' , $rowNum );
            $(window).off();
        } else {
            pixflow_scrollPage('up' , $rowNum );
            $(window).off();
        }
    });
}
// @TODO : Refactor
function pixflow_fitRowToHeightOnePageScroll() {
    "use strict";
    if ($('body').hasClass('compose-mode') && $("body").hasClass('one_page_scroll') ) {
        pixflow_one_page_for_customizer();
        return;
    }
    if ($("body").hasClass('one_page_scroll')) {
        var footerHeight = $('footer').height()  ;
        $('footer').css('bottom' , '-' + parseInt(footerHeight) + 'px');
        pixflow_first_time_one_page($rowNum);
        var last_touch_pos = 0 ;
        $(document).bind('touchstart', function(e) {
            last_touch_pos = e.originalEvent.touches[0].clientY;
        });
        $(document).bind('touchmove', function(e) {
            var current_touch_pos = e.originalEvent.changedTouches[0].clientY;
            if (last_touch_pos > current_touch_pos + 5 ) {
                $(document).scroll();
                if(doScroll == false)
                    return true ;
                if( $('.row-active').hasClass('row-over-height')){
                    pixflow_row_scroll($rowNum , 'down');
                }else{
                    pixflow_scrollPage('down' , $rowNum );
                }
                return true;
            } else {
                if(doScroll == false)
                    return true;
                if( $('.row-active').hasClass('row-over-height') ){
                    pixflow_row_scroll($rowNum , 'up');
                }else{
                    console.log('s');
                    pixflow_scrollPage('up' , $rowNum );
                }
                return true;
            }
            return true ;

        });
        pixflow_set_event_on_window($rowNum);
        pixflow_gather_fullpage_header();
    }
}