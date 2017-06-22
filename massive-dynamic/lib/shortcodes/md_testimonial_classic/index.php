<?php
/**
 * Testimonial Classic Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_testimonial_classic', 'pixflow_get_style_script'); // pixflow_sc_testimonial_classic

function pixflow_sc_testimonial_classic( $atts, $content = null ) {

    $output = $testimonial_classic_num = '';

    extract( shortcode_atts( array(
        'testimonial_classic_title'  => 'TESTIMONIAL',
        'md_testimonial_solid_color' => '#000',
        'testimonial_classic_num'    => '5',
        'md_testimonial_alignment'   => 'left',
        'md_testimonial_text_size'   => 'h5',
    ), $atts ) );
    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_testimonial_classic',$atts);

    for( $i=1; $i<=$testimonial_classic_num; $i++ ){
        $slieds[$i] = shortcode_atts( array(
            'testimonial_classic_img_'.$i      => '',
            'testimonial_classic_desc_'.$i     => 'Ipsum dol conse ctetuer adipis cing elit. Morbi com modo, ipsum sed pharetr gravida, orciut magna rhoncus neque,id pulvinaodio lorem non sansunioto koriot.Morbcom magna rhoncus neque,id',
            'testimonial_classic_name_job_'.$i => 'Randy Nicklson . ATC resident manager co.',
        ), $atts );

        $slieds[$i]['testimonial_classic_desc_'.$i]= pixflow_checkBase64($slieds[$i]['testimonial_classic_desc_'.$i]);
    };

    $id = pixflow_sc_id('testimonial_classic');
    $func_id = uniqid();

    $output .= '<div data-flex-id="'.$id.'" clone="false" class="testimonial-classic testimonial-classic-'. $md_testimonial_alignment .' '.esc_attr($animation['has-animation']).'" '.$animation['animation-attrs'].'>';
    $output .= '<h3 data-flex-id="'.$id.'" clone="false" class="title"> <span class="quote icon-quote4"></span> '. $testimonial_classic_title .'</h3>';

    $output .= '<div id="'.$id.'" flex="false" class="flexslider clearfix">';
    $output .= '<ul data-flex-id="'.$id.'" class="slides clearfix">';

    foreach( $slieds as $key=>$slide )
    {
        $image = $slide['testimonial_classic_img_' . $key];
        $description = $slide['testimonial_classic_desc_' . $key];
        $nameJob = $slide['testimonial_classic_name_job_' . $key];

        if ($image != '' && is_numeric($image)) {
            $image = wp_get_attachment_url($image);
            $image = (false == $image)?PIXFLOW_PLACEHOLDER_BLANK:$image;
        }

        $output .= '<li>';
        $output .= '<div class="detail">';
        $output .= '<'. $md_testimonial_text_size .' class="paragraph">'. preg_replace("/&lt;(.*?)&gt;/i",'',esc_attr($description)) .'</'. $md_testimonial_text_size .'>';
        $output .= '</div>';
        if ($image != '')
            $output .= '<div class="slide-image" style="background-image:url(\'' . esc_attr($image) . '\');"></div>';
        $output .= '<div class="name-job">'. $nameJob .'</div>';
        $output .= '</li>';
    }

    $output .= '</ul>';
    $output .= '</div>';
    $output .= '</div>';  // End Testimonial Classic

    ob_start();
    ?>

    <style scoped>

        [data-flex-id = <?php echo esc_attr($id) ?>].testimonial-classic .title,
        [data-flex-id = <?php echo esc_attr($id) ?>].testimonial-classic .title .quote,
        [data-flex-id = <?php echo esc_attr($id) ?>].testimonial-classic .flexslider .detail .paragraph,
        [data-flex-id = <?php echo esc_attr($id) ?>].testimonial-classic .flexslider .name-job{
            color: <?php echo esc_attr( $md_testimonial_solid_color ); ?>
        }

    </style>

    <script type="text/javascript">
        var $ = jQuery;
        if(typeof $.flexslider == 'function'){
            $('#<?php echo esc_attr($id); ?>').flexslider({
                animation: "fade",
                slideshow: true,
                slideshowSpeed: 5000,
                directionNav: false,
                controlNav: false,
            });
        }
        <?php pixflow_callAnimation(); ?>
    </script>

    <?php
    $output .= ob_get_clean();
    return $output;
}
