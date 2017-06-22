<?php
/**
 * Horizontal Tab Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_hor_tab', 'pixflow_get_style_script');

function pixflow_sc_hor_tab( $atts, $content = null ){
    $output = $title = $tab_id = $tab_icon= '';
    extract( shortcode_atts( array(
        'tab_id'         =>'' ,
        'title'        => '',
        'tab_icon' => ''),
        $atts ) );
    wp_enqueue_script( 'jquery_ui_tabs_rotate' );
    $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_tab ui-tabs-panel wpb_ui-tabs-hide vc_clearfix', 'md_hor_tab', $atts );
    $output .= "\n\t\t\t" . '<div id="tab-' . ( empty( $tab_id ) ? sanitize_title( $title ) : $tab_id ) . '" class="' . $css_class . '">';
    $output .= ( $content == '' || $content == ' ' ) ? esc_attr__( "This tab is empty. Drag a shortcode here to add content.", 'massive-dynamic' ) : "\n\t\t\t\t" . pixflow_js_remove_wpautop( $content );
    $output .= "\n\t\t\t" . '</div> ';
    return $output;
}