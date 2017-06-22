<?php
/**
 * Testimonial Carousel Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_testimonial_carousel', 'pixflow_get_style_script'); // pixflow_sc_testimonial_carousel

function pixflow_sc_testimonial_carousel ($atts, $content = null) {

    extract(shortcode_atts(array(
        'testimonial_carousel_text_color' => '#000',
        'testimonial_carousel_num'        => '3',
        'testimonial_carousel_text_size'  => 'h6',
    ), $atts));

    $html = "";
    $id = pixflow_sc_id('testimonial_carousel');
    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_testimonial_carousel',$atts);

    wp_enqueue_style('carousel_css',pixflow_path_combine(PIXFLOW_THEME_CSS_URI,'owl.carousel.min.css'),array(),PIXFLOW_THEME_VERSION);
    wp_enqueue_script('carousel_js',pixflow_path_combine(PIXFLOW_THEME_JS_URI,'owl.carousel.min.js'),array(),PIXFLOW_THEME_VERSION,true);


    for( $i=1; $i<=$testimonial_carousel_num; $i++ ){
        $slieds[$i] = shortcode_atts( array(
            'testimonial_carousel_img_'.$i      => PIXFLOW_THEME_IMAGES_URI."/place-holder.jpg",
            'testimonial_carousel_desc_'.$i     => 'orem ipsum dolor sit amet, nec in adipiscing purus luctus, urna pellentesque fringilla vel, non sed arcu integevestibulum in lorem nec',
            'testimonial_carousel_name_'.$i => 'Mari Javani',
            'testimonial_carousel_job_name_'.$i => 'Graphic Designer, Stupids Magazine',
        ), $atts );
    };
    ob_start();
    ?>
    <style scoped>
        .testimonial-carousel .clipPath {
            -webkit-mask-image: url(<?php echo PIXFLOW_THEME_URI;  ?>/assets/img/testimonial.png);
            -o-mask-image: url(<?php echo PIXFLOW_THEME_URI;  ?>./assets/img/testimonial.png);
            -moz-mask-image: url(<?php echo PIXFLOW_THEME_URI;  ?>/assets/img/testimonial.png);
        }
        .<?php echo esc_attr($id); ?>.testimonial-carousel .testimonial-carousel-name{
            color:<?php echo esc_attr(pixflow_colorConvertor($testimonial_carousel_text_color,'rgba',1)); ?>
        }
        .<?php echo esc_attr($id); ?>.testimonial-carousel .testimonial-carousel-job-name{
            color:<?php echo esc_attr(pixflow_colorConvertor($testimonial_carousel_text_color,'rgba',0.5)); ?>
        }
        .<?php echo esc_attr($id); ?>.testimonial-carousel .testimonial-carousel-job-text{
            color:<?php echo esc_attr(pixflow_colorConvertor($testimonial_carousel_text_color,'rgb')); ?>
        }
        .<?php echo esc_attr($id); ?>.testimonial-carousel .owl-dots .owl-dot span,
        .<?php echo esc_attr($id); ?>.testimonial-carousel .owl-dots .owl-dot.active span,
        .<?php echo esc_attr($id); ?>.testimonial-carousel .owl-dots .owl-dot:hover span{
            background:<?php echo esc_attr(pixflow_colorConvertor($testimonial_carousel_text_color,'rgba',0.3)); ?>
        }
    </style>

    <div id="owl-demo" class="owl-carousel owl-theme testimonial-carousel <?php echo esc_attr($id.' '.$animation['has-animation']); ?>" <?php echo esc_attr($animation['animation-attrs']); ?>>

        <?php

        foreach( $slieds as $key=>$slide ){

        $authorimg = $slide['testimonial_carousel_img_' . $key];
        $text = $slide['testimonial_carousel_desc_' . $key];
        $author = $slide['testimonial_carousel_name_' . $key];
        $job = $slide['testimonial_carousel_job_name_' . $key];

        if ($authorimg != '' && is_numeric($authorimg)) {
            $authorimg = wp_get_attachment_url($authorimg);
            $authorimg = (false == $authorimg)?PIXFLOW_PLACEHOLDER_BLANK:$authorimg;
        }
        ?>

        <div class="item">
            <div class="clipPath" style="background-image:url(<?php echo esc_attr($authorimg); ?>)"></div>
            <p class="testimonial-carousel-name"><?php echo esc_attr($author); ?></p>
            <p class="testimonial-carousel-job-name"><?php echo esc_attr($job); ?></p>
            <<?php echo esc_attr($testimonial_carousel_text_size); ?> class="testimonial-carousel-job-text"><?php echo preg_replace("/&lt;(.*?)&gt;/i",'',esc_attr($text)); ?></<?php echo esc_attr($testimonial_carousel_text_size); ?>>
    </div>

    <?php
}
    ?>
    </div>

    <script type="text/javascript">
        var $ = jQuery;
        $(document).ready(function(){
            if(typeof pixflow_testimonialCarousel == 'function'){
                pixflow_testimonialCarousel();
            }
            <?php pixflow_callAnimation(); ?>
        });
    </script>
    <?php
    return ob_get_clean();
}
