<?php
/**
 * Display Slider Shortcode
 *
 * @author Pixflow
 */

/*Display Slider*/
add_shortcode('md_display_slider', 'pixflow_get_style_script'); // pixflow_sc_display_slider

/*-----------------------------------------------------------------------------------*/
/*  Display Slider
/*-----------------------------------------------------------------------------------*/

function pixflow_sc_display_slider( $atts, $content = null ) {

    $output = $el_class = $slide_num = '';

    extract( shortcode_atts( array(
        'text_color' => '#000',
        'slide_num' => '3',
        'device_slider_slideshow' => 'yes',
        'align' =>'center'
    ), $atts ) );

    for($i=1; $i<=$slide_num; $i++){
        $slides[$i] = shortcode_atts( array(
            'slide_title_'.$i => '',
            'slide_description_'.$i => '',
            'slide_image_'.$i => PIXFLOW_THEME_IMAGES_URI."/place-holder.jpg",
        ), $atts );
    }
    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_display_slider',$atts);

    $id = pixflow_sc_id('display_slider');
    $func_id = uniqid();

    if( 'yes' == $device_slider_slideshow){
        $slideshow = 'true';
    } else{
        $slideshow = 'false';
    }


    $align = trim($align);

    $output .= '<div data-flex-id="'.$id.'" clone="false" class="device-slider '.esc_attr($animation['has-animation']).' md-align-'.esc_attr($align).'" '.esc_attr($animation['animation-attrs']).'>';
    $output .= '<div data-flex-id="'.$id.'" class="flexslider-controls">';
    $output .= '<ol data-flex-id="'.$id.'" class="flex-control-nav">';

    foreach($slides as $key=>$slide){
        $title  = $slide['slide_title_'.$key];
        $decription  = $slide['slide_description_'.$key];
        $image  = $slide['slide_image_'.$key];

        if ($image != '' && is_numeric($image)){
            $imageSrc = wp_get_attachment_image_src( $image, 'pixflow_display-slider') ;
            $imageSrc = (false == $imageSrc)?PIXFLOW_PLACEHOLDER1:$imageSrc[0];
            $image = $imageSrc ;
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

        $title = $slide['slide_title_'.$key];
        $decription = $slide['slide_description_'.$key];
        $image = $slide['slide_image_'.$key];

        if($image != '' && is_numeric($image)){
            $imageSrc = wp_get_attachment_image_src( $image, 'pixflow_display-slider') ;
            $imageSrc = (false == $imageSrc)?PIXFLOW_PLACEHOLDER1:$imageSrc[0];
            $image = $imageSrc ;
        }

        $output .= '<li>';
        if('' != $decription){
            $output .= '<p class="slide-description">'.preg_replace("/&lt;(.*?)&gt;/i",'',esc_attr($decription)).'</p>';
        }

        $output .= '<div class="mac-frame"></div><div class="slide-image" style="background-image:url(\''.esc_attr($image).'\');"></div>';
        $output .= '</li>';
    }
    $output .= '</ul>';
    $output .= '</div>';
    $output .= '</div>';
    ob_start();
    ?>
    <style scoped>
        .device-slider .flexslider .mac-frame {
            background: transparent url("<?php echo PIXFLOW_THEME_URI; ?>/assets/img/slider-mac-with-shade.png") left top no-repeat;
            background-size: contain;

        }
        [data-flex-id="<?php echo esc_attr($id); ?>"] .flex-control-nav li,
        #<?php echo esc_attr($id); ?> .slide-description{
                                          color: <?php echo esc_attr($text_color); ?>;
                                      }
    </style>
    <script type="text/javascript">
        var $ = jQuery;

        $(function(){
            if ( typeof $.flexslider == 'function' )
                $('#<?php echo esc_attr($id); ?>').flexslider({
                    animation: "fade",
                    manualControls: $('ol.flex-control-nav[data-flex-id=<?php echo esc_attr($id); ?>] li'),
                    slideshow: <?php echo esc_attr($slideshow) ?>,
                    slideshowSpeed: 5000,
                    selector: '.slides > li',
                    directionNav: false,
                });
            $('#<?php echo esc_attr($id); ?>').find('ol.flex-control-paging').remove();
            if(typeof pixflow_displaySliderShortcode == 'function'){
                pixflow_displaySliderShortcode($('#<?php echo esc_attr($id); ?>').closest('.vc_row'));
            }
        });
        <?php pixflow_callAnimation(); ?>
    </script>
    <?php
    $output .= ob_get_clean();
    return $output;
}