<?php
/**
 * Empty Space Shortcode
 *
 * @author Pixflow
 */

add_shortcode("vc_empty_space",'mBuilder_vcEmptySpace');

function mBuilder_vcEmptySpace($atts,$content){
    extract( shortcode_atts( array(
        'height'                   => '100'
    ), $atts ));
    $id = uniqid('column');
    ob_start();
    ?>

    <div class='vc_empty_space gizmo-container small-gizmo clearfix <?php echo esc_attr($id);?>' style='height:<?php echo esc_attr($height); ?>px'></div>
    <?php
    return ob_get_clean();
}

