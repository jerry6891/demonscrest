<?php
/**
 * Mobile Slider Shortcode
 *
 * @author Pixflow
 */
add_shortcode('md_mobile_slider', 'pixflow_get_style_script'); // pixflow_sc_mobile_slider

function pixflow_sc_mobile_slider( $atts, $content = null ) {

    $output = $mobile_slide_num = '';

    extract( shortcode_atts( array(
        'mobile_slider_text_color' => '#000',
        'mobile_slide_num'         => '3',
        'mobile_slider_slideshow'  => 'yes',
        'align'                    => 'center'
    ), $atts ) );

    for($i=1; $i<=$mobile_slide_num; $i++){
        $slides[$i] = shortcode_atts( array(
            'mobile_slider_slide_title_'.$i => '',
            'mobile_slider_slide_image_'.$i => PIXFLOW_THEME_IMAGES_URI."/place-holder.jpg",
        ), $atts );
    }
    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_mobile_slider',$atts);
    $id = pixflow_sc_id('mobile_slider');
    $func_id = uniqid();

    if( 'yes' == $mobile_slider_slideshow ){
        $slideshow = 'true';
    } else{
        $slideshow = 'false';
    }
    $align = trim($align);
    $output .= '<div data-flex-id="'.$id.'" clone="false" class="mobile-slider md-align-'.esc_attr($align).' '.$animation['has-animation'].'" '.$animation['animation-attrs'].'>';
    $output .= '<div data-flex-id="'.$id.'" class="flexslider-controls">';
    $output .= '<ol data-flex-id="'.$id.'" class="flex-control-nav">';

    foreach($slides as $key=>$slide){
        $title  = $slide['mobile_slider_slide_title_'.$key];
        $image  = $slide['mobile_slider_slide_image_'.$key];
        if ($image != '' && is_numeric($image)){
            $image = wp_get_attachment_image_src( $image, 'pixflow_mobile-slider') ;
            $image = (false == $image)?PIXFLOW_PLACEHOLDER1:$image[0];
        }
        if('' != $title) {
            $output .= '<li>' . $title . '</li>';
        }
    }

    $output .= '</ol>' . "\n";
    $output .= '</div>';
    $output .= '<div id="'.$id.'" flex="false" class="flexslider clearfix">';
    $output .= '<ul data-flex-id="'.$id.'" class="slides clearfix">';

    foreach($slides as $key=>$slide){
        $title  = $slide['mobile_slider_slide_title_'.$key];
        $image  = $slide['mobile_slider_slide_image_'.$key];
        if ($image != '' && is_numeric($image)){
            $image = wp_get_attachment_image_src( $image, 'pixflow_mobile-slider') ;
            $image = (false == $image)?PIXFLOW_PLACEHOLDER1:$image[0];
        }
        $output .= '<li>';
        $output .= '<div class="mobile-frame"></div><div class="slide-image" style="background-image:url(\''.esc_attr($image).'\');"></div>';
        $output .= '</li>';
    }

    $output .= '</ul>';
    $output .= '</div>';
    $output .= '</div>';
    ob_start();
    ?>

    <style scoped>
        .mobile-slider .flexslider .mobile-frame {
            background: transparent url("<?php echo PIXFLOW_THEME_URI; ?>/assets/img/mobile-slider-with-shade.png") left top no-repeat;
            background-size: contain;
        }
        [data-flex-id="<?php echo esc_attr($id); ?>"] .flex-control-nav li,
        #<?php echo esc_attr($id); ?> .slide-description{
                                          color: <?php echo esc_attr($mobile_slider_text_color); ?>;
                                      }
    </style>

    <script type="text/javascript">
        var $ = jQuery;
        $(function() {
            if (typeof $.flexslider == 'function')
                $('#<?php echo esc_attr($id); ?>').flexslider({
                    animation: "fade",
                    manualControls: $('ol.flex-control-nav[data-flex-id=<?php echo esc_attr($id); ?>] li'),
                    slideshow: <?php echo esc_attr($slideshow) ?>,
                    slideshowSpeed: 3000,
                    selector: '.slides > li',
                    directionNav: false,
                });
        });

        if (typeof pixflow_mobileSliderShortcode == 'function'){
            pixflow_mobileSliderShortcode();
        }


        <?php pixflow_callAnimation(); ?>
    </script>

    <?php
    $output .= ob_get_clean();
    return $output;
}
