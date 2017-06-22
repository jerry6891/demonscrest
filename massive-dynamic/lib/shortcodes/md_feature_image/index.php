<?php
/**
 * Feature Image Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_feature_image', 'pixflow_get_style_script'); // pixflow_sc_feature_image

function pixflow_sc_feature_image( $atts, $content = null ) {

    extract(shortcode_atts(array(
        'feature_image_background_image' => PIXFLOW_THEME_IMAGES_URI."/place-holder.jpg",
        'feature_image_icon_class'       => 'icon-romance-love-target',
        'feature_image_title'            => 'Imagine & Create',
        'feature_image_description'      => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque',
        'feature_image_background_color' => 'rgb(255,255,255)',
        'feature_image_foreground_color' => 'rgb(24,24,24)',
        'feature_image_hover_color'      => 'rgb(26,192,182)',
        'feature_image_height_slider'    => '300',

    ), $atts));

    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_feature_image',$atts);
    $id = pixflow_sc_id('md_feature_image');

    if(is_numeric($feature_image_background_image)){
        $feature_image_background_image =  wp_get_attachment_image_src( $feature_image_background_image, 'pixflow_feature_image-thumb') ;
        $feature_image_background_image = (false == $feature_image_background_image)?PIXFLOW_PLACEHOLDER1:$feature_image_background_image[0];
    }

    ob_start();
    ?>


    <?php



    ?>

    <style scoped>

        .<?php echo esc_attr($id); ?>.sc-feature_image .image-container {
            height: <?php echo esc_attr($feature_image_height_slider)?>px;
        }

        .<?php echo esc_attr($id); ?>.sc-feature_image .feature_image-image {
            height: <?php echo esc_attr($feature_image_height_slider)?>px;
        }

        .<?php echo esc_attr($id); ?>.sc-feature_image .main {
            background-color: <?php echo esc_attr($feature_image_background_color)?>;
        }

        .<?php echo esc_attr($id); ?>.sc-feature_image .main h3 {
            color: <?php echo esc_attr($feature_image_foreground_color)?>;
        }

        .<?php echo esc_attr($id); ?>.sc-feature_image .main p {
            color: <?php echo esc_attr( pixflow_colorConvertor( $feature_image_foreground_color, 'rgba', .6 ) )?>;
        }

        .<?php echo esc_attr($id); ?>.sc-feature_image .main i {
            color: <?php echo esc_attr($feature_image_hover_color)?>;
        }

        .<?php echo esc_attr($id); ?>.sc-feature_image:hover .main i {
            color: <?php echo esc_attr($feature_image_background_color)?>;
        }

        .<?php echo esc_attr($id); ?>.sc-feature_image:hover .main h3 {
            color: <?php echo esc_attr($feature_image_background_color)?>;
        }
        .<?php echo esc_attr($id); ?>.sc-feature_image:hover .main p {
            color: <?php echo esc_attr( pixflow_colorConvertor( $feature_image_background_color, 'rgba', 1 ) )?>;
        }

        .<?php echo esc_attr($id); ?>.sc-feature_image:hover .main {
            background-color: <?php echo esc_attr($feature_image_hover_color)?>;
        }

    </style>

    <div class="sc-feature_image <?php echo esc_attr($id.' '.$animation['has-animation']); ?>" <?php echo esc_attr($animation['animation-attrs']); ?>>

        <div class="image-container">
            <div class="feature_image-image" style=" background-image:url('<?php echo esc_attr($feature_image_background_image) ?>') " ></div>
        </div>

        <div class="main">

            <i class="icon <?php echo esc_attr($feature_image_icon_class); ?>"></i>
            <h3> <?php echo esc_attr($feature_image_title); ?> </h3>
            <p> <?php echo preg_replace("/&lt;(.*?)&gt;/i",'',esc_attr($feature_image_description)); ?> </p>

        </div>

    </div> <!-- feature image ends -->

    <?php
    pixflow_callAnimation(true);
    return ob_get_clean();
}