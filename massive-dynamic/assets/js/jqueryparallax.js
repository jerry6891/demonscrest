
/*
 Plugin: jQuery ParallaxjQuery Parallax
 Version 1.1.3
 Author: Ian Lunn
 Twitter: @IanLunn
 Author URL: http://www.ianlunn.co.uk/
 Plugin URL: http://www.ianlunn.co.uk/plugins/jquery-parallax/

 Dual licensed under the MIT and GPL licenses:
 http://www.opensource.org/licenses/mit-license.php
 http://www.gnu.org/licenses/gpl.html
 */

!function(n){var t=n(window),e=t.height();t.resize(function(){e=t.height()}),n.fn.parallax=function(o,i,u){function r(){var u=t.scrollTop();s.each(function(){setTimeout(function(){s.each(function(){f=s.offset().top})},500);var t=n(this),r=t.offset().top,h=c(t);u>r+h||r>u+e||s.css("backgroundPosition",o+" "+Math.round((f-u)*i)+"px")})}var c,f,s=n(this);setTimeout(function(){s.each(function(){f=s.offset().top})},500),c=u?function(n){return n.outerHeight(!0)}:function(n){return n.height()},(arguments.length<1||null===o)&&(o="50%"),(arguments.length<2||null===i)&&(i=.1),(arguments.length<3||null===u)&&(u=!0),t.bind("scroll",r).resize(r),setTimeout(function(){r()},1e3)}}(jQuery);