<?php
/**
 * Price Table Shortcode
 *
 * @author Pixflow
 */
add_shortcode('md_pricetabel', 'pixflow_get_style_script'); // pixflow_sc_pricetable

function pixflow_sc_pricetabel( $atts, $content = null ){
    global $md_allowed_HTML_tags;
    if ( !(in_array( 'go_pricing/go_pricing.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || class_exists( 'GW_GoPricing' )) ) {
        $url = admin_url('themes.php?page=install-required-plugins');
        $a='<a href="'.esc_url($url).'">Go Pricing</a>';

        $mis = '<div class="miss-shortcode"><p class="title">'. esc_attr__('Oops!! Something\'s Missing','massive-dynamic').'</p><p class="desc">'.sprintf(esc_attr__('Please install and activate %s, then create a table. When it\'s done, you can drop the table using this shortcode. ','massive-dynamic'),$a).'</p></div>';

        return $mis;
    }
    extract( shortcode_atts( array(
        'pricetable_id' =>  '',
    ), $atts ) );

    ob_start();

    if ($pricetable_id == ''){
        $gopricing = get_posts( 'post_type="go_pricing_tables"&numberposts=-1' );
        if ( is_array($gopricing) && count($gopricing) > 0){
            $index = count($gopricing)-1;
            $pricetable_id = $gopricing[$index]->post_excerpt;
            echo do_shortcode('[go_pricing id="'.esc_attr($pricetable_id).'"]');
        }else{
            $url = admin_url('themes.php?page=install-required-plugins');
            $a='<a href="'.$url.'">Go Pricing</a>';

            $mis = '<div class="miss-shortcode"><p class="title">'. esc_attr__('Oops!! Something\'s Missing','massive-dynamic').'</p><p class="desc">'.sprintf(esc_attr__('No table found, make sure you have created a table in %s before using this shortcode. ','massive-dynamic'),$a).'</p></div>';

            echo wp_kses($mis,$md_allowed_HTML_tags);
        }

    }else{
        echo do_shortcode('[go_pricing id="'.$pricetable_id.'"]');
    }
    return ob_get_clean();
}
