<?php
/**
 * Vc column Text Shortcode
 *
 * @author Pixflow
 */
add_shortcode('vc_column_text', 'pixflow_get_style_script'); //vc_sc_column_text

function vc_sc_column_text($atts,$content = null){
    if($atts == ''){
        $atts['md_text_title1'] = '';
        $atts['md_text_title_separator'] = 'no';
    }
    return pixflow_sc_text($atts,$content);
}
