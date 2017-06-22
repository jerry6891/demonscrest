<?php
/**
 * Tablet Slider Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_tablet_slider', 'pixflow_get_style_script'); // pixflow_sc_tablet_slider

function pixflow_sc_tablet_slider( $atts, $content = null ) {

    $output = $tablet_slide_num = '';

    extract( shortcode_atts( array(
        'tablet_slider_text_color' => '#000',
        'tablet_slide_num'         => '3',
        'tablet_slider_slideshow'  => 'yes',
        'align'                    => 'center'
    ), $atts ) );
    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_tablet_slider',$atts);

    for($i=1; $i<=$tablet_slide_num; $i++){
        $slides[$i] = shortcode_atts( array(
            'tablet_slider_slide_title_'.$i => 'Slide'.$i,
            'tablet_slider_slide_image_'.$i => PIXFLOW_THEME_IMAGES_URI."/place-holder.jpg",
        ), $atts );
    }

    $id = pixflow_sc_id('tablet_slider');
    $func_id = uniqid();

    if( 'yes' == $tablet_slider_slideshow ){
        $slideshow = 'true';
    } else{
        $slideshow = 'false';
    }
    $align = trim($align);
    ?>
    <style type="text/css">
        .tablet-slider .flexslider .flex-direction-nav .flex-prev:before{
            background-image:  url("<?php echo PIXFLOW_THEME_URI; ?>/assets/img/tablet-slider-back-button.png") ;
        }
        .tablet-slider .flexslider .flex-direction-nav .flex-next:before {
            background-image:url("<?php echo PIXFLOW_THEME_URI; ?>/assets/img/tablet-slider-next-button.png");
        }
        .tablet-slider .flexslider .tablet-frame {
            background-image: url("<?php echo PIXFLOW_THEME_URI; ?>/assets/img/tablet-slider-with-shade.png");
        }
    </style>
    <?php
    $output .= '<div data-flex-id="'.$id.'" clone="false" class="tablet-slider md-align-'.esc_attr($align).' '.esc_attr($animation['has-animation']).'" '.$animation['animation-attrs'].'>';
    $output .= '<div data-flex-id="'.$id.'" class="flexslider-controls">';
    $output .= '<ol data-flex-id="'.$id.'" class="flex-control-nav">';

    foreach($slides as $key=>$slide){
        $title  = $slide['tablet_slider_slide_title_'.$key];
        $image  = $slide['tablet_slider_slide_image_'.$key];
        if ($image != '' && is_numeric($image)){
            $image = wp_get_attachment_image_src( $image, 'pixflow_tablet-slider') ;
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
        $title  = $slide['tablet_slider_slide_title_'.$key];
        $image  = $slide['tablet_slider_slide_image_'.$key];
        if ($image != '' && is_numeric($image)){
            $image = wp_get_attachment_image_src( $image, 'pixflow_tablet-slider') ;
            $image = (false == $image)?PIXFLOW_PLACEHOLDER1:$image[0];
        }
        $output .= '<li>';
        $output .= '<div class="tablet-frame"></div><div class="slide-image" style="background-image:url(\''.esc_attr($image).'\');"></div>';
        $output .= '</li>';
    }

    $output .= '</ul>';
    $output .= '</div>';
    $output .= '</div>';
    ob_start();
    ?>

    <style scoped>

        [data-flex-id="<?php echo esc_attr($id); ?>"] .flex-control-nav li,
        #<?php echo esc_attr($id); ?> .slide-description{
                                          color: <?php echo esc_attr($tablet_slider_text_color); ?>;
                                      }
    </style>

    <script type="text/javascript">
        $(document).ready(function(){
            if (typeof pixflow_tabletSlider == 'function'){
                pixflow_tabletSlider('<?php echo esc_attr($id); ?>','<?php echo esc_attr($slideshow) ?>');
            }
        });
        <?php pixflow_callAnimation(); ?>
    </script>

    <?php
    $output .= ob_get_clean();
    return $output;
}
