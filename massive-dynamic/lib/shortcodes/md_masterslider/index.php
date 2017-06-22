<?php
/**
 * Master Slider Shortcode
 *
 * @author Pixflow
 */
add_shortcode('md_masterslider', 'pixflow_get_style_script'); // pixflow_sc_masterslider

function pixflow_sc_masterslider( $atts, $content = null ){
    if (!(is_plugin_active( 'masterslider/masterslider.php' ) || defined( 'MSWP_AVERTA_VERSION' ))) {
        $url = admin_url('themes.php?page=install-required-plugins');
        $a='<a href="'.$url.'">Master Slider</a>';

        $mis = '<div class="miss-shortcode"><p class="title">'. esc_attr__('Oops!! Something\'s Missing','massive-dynamic').'</p><p class="desc">'.sprintf(esc_attr__('Please install and activate %s to use this shortcode','massive-dynamic'),$a).'</p></div>';

        return $mis;
    }
    // prevent Load in customizer because not run on customizer
    if (pixflow_called_from_mbuilder()) {
        $mis = '<div class="miss-shortcode"><p class="title">'. esc_attr__('Master Slider Compatiblity','massive-dynamic').'</p><p class="desc">'.esc_attr__('Master slider is not showing it\'s slides in Massive Builder. But don\'t worry, it works fine in your website. Use this box for editing Master Slider\'s shortcode.','massive-dynamic').'</p></div>';
        return $mis;
    }
    extract( shortcode_atts( array(
        'md_masterslider_alias'  =>'',
        'md_masterslider_id'  =>''
    ),$atts ));
    ob_start();
    $md_masterslider_alias = esc_attr($md_masterslider_alias);
    $md_masterslider_id = esc_attr($md_masterslider_id);

    if($md_masterslider_alias != '' && $md_masterslider_id==''){
        print(do_shortcode("[masterslider alias='$md_masterslider_alias']"));
        return ob_get_clean();
    }
    if ($md_masterslider_id == ''){
        $sliders = get_masterslider_names( 'title-id' );
        if ( is_array($sliders) && (count($sliders) > 0)){
            echo do_shortcode('[masterslider id="'.esc_attr(current($sliders)).'"]');
        }else{
            $url = admin_url('themes.php?page=install-required-plugins');
            $a='<a href="'.$url.'">Master Slider</a>';
            $mis = '<div class="miss-shortcode"><p class="title">'. esc_attr__('Oops!! Something\'s Missing','massive-dynamic').'</p><p class="desc">'.sprintf(esc_attr__('Can\'t find any slider, please create a slider in %s, then use this shortcode. ','massive-dynamic'),$a).'</p></div>';
            return $mis;
        }
    }else{
        print(do_shortcode("[masterslider id='$md_masterslider_id']"));
    }
    return ob_get_clean();

}
