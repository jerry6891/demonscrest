<?php
/**
 * Client Carousel Shortcode
 *
 * @author Pixflow
 */

/*Clinet Carousel*/
add_shortcode('md_client_carousel', 'pixflow_get_style_script'); // pixflow_sc_client_carousel

/*-----------------------------------------------------------------------------------*/
/*  Client Carousel
/*-----------------------------------------------------------------------------------*/
function pixflow_sc_client_carousel($atts, $content = null)
{
    $output = $client_carousel_num = $client_play_mode = $client_carousel_number = '';
    extract( shortcode_atts( array(
        'client_carousel_num' => '8',
        'client_carousel_number' => '5',
        'client_play_mode'    => 'no'
    ), $atts ) );
    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_client_carousel',$atts);

    for($i=1; $i <= $client_carousel_num ; $i++){
        $slides[$i] = shortcode_atts( array(
            'client_carousel_logo_'.$i => PIXFLOW_THEME_IMAGES_URI."/place-holder.jpg",
        ), $atts );
    }

    $id = pixflow_sc_id('client-carousel');
    $func_id = uniqid();

    $output .= '<div id="'.$id.'" data-autoplay="'. $client_play_mode .'" data-slide-item="'.$client_carousel_number.'" class="wrap-client-carousel clearfix '.esc_attr($animation["has-animation"]).'" '.esc_attr($animation["animation-attrs"]).'>';

    $output .= '<ul class="slides">';

    foreach( $slides as $key=>$slide ){

        $image = $slide['client_carousel_logo_'.$key];

        if ($image != '' && is_numeric($image)) {
            $image = wp_get_attachment_image_src($image) ;
            $image = (false == $image)?PIXFLOW_PLACEHOLDER_BLANK:$image[0];
        }

        $output .= ' <li> ';

        $output .= ' <div class="wrap"> <div class="client-logo"><img src="'. esc_attr($image) .'" /></div> ';

        $output .= ' </li> '; // end wrap

    }

    $output .= ' </ul> ';

    $output .= ' </div> ';

    ob_start();
    ?>

    <script type="text/javascript">

        var $ = jQuery,
            slickTtrackWidth, CTO;

        $('document').ready(function() {

            if (typeof pixflow_teammemberCarousel == 'function') {
                pixflow_teammemberCarousel("<?php echo esc_attr($id) ?>");
            }

        });
        <?php pixflow_callAnimation(); ?>
    </script>

    <?php
    $output .= ob_get_clean();
    return $output;

}