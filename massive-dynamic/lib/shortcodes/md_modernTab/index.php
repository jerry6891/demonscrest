<?php
/**
 * Modern Tab Shortcode
 *
 * @author Pixflow
 */
add_shortcode('md_modernTab', 'pixflow_get_style_script'); //pixflow_sc_modernTab

function pixflow_sc_modernTab( $atts, $content = null ){

    $output = $title = $tab_id = $tab_icon_class= '';
    extract( shortcode_atts( array(
        'tab_id'      =>'' ,
        'title'       =>'',
        'tab_icon_class' => ''), $atts ) );

    wp_enqueue_script( 'jquery_ui_tabs_rotate' );

    $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_tab ui-tabs-panel wpb_ui-tabs-hide vc_clearfix', 'md_modernTab', $atts );
    $output .= "\n\t\t\t" . '<div id="tab-' . ( empty( $tab_id ) ? sanitize_title( $title ) : $tab_id ) . '" class="' . $css_class . '" >';
    $output .= ( $content == '' || $content == ' ' ) ? esc_attr__( "This tab is empty. Drag a shortcode here to add content.", 'massive-dynamic' ) : "\n\t\t\t\t" . pixflow_js_remove_wpautop( $content );
    $output .= "\n\t\t\t" . '</div> ';
    return $output;
}
