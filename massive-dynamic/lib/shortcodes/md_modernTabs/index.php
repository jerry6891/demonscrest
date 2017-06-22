<?php
/**
 * Modern Tabs Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_modernTabs', 'pixflow_get_style_script'); //pixflow_sc_modernTabs

function pixflow_sc_modernTabs( $atts, $content = null ){
    $output  = $general_color = $interval = $title = $tab_id =  '';

    extract( shortcode_atts( array(
        'interval'         => '',
        'general_color'    => 'rgb(60,60,60)',
        'height'       => '400',

    ), $atts ) );
    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_modernTabs',$atts);

    wp_enqueue_script('jquery-ui-tabs');

    $element = 'wpb_tabs';
    $id = esc_attr(pixflow_sc_id('md_modernTabs'));
    ob_start();
    ?>
    <style scoped>

        .<?php echo esc_attr($id); ?>.wpb_content_element .px_tabs_nav.md-custom-tab > li.ui-tabs-active,

        .<?php echo esc_attr($id); ?>.wpb_content_element .px_tabs_nav.md-custom-tab > li.ui-tabs-active a,
        .<?php echo esc_attr($id); ?>.wpb_content_element .px_tabs_nav.md-custom-tab > li.ui-tabs-active a i,

        .<?php echo esc_attr($id); ?>.wpb_content_element .px_tabs_nav.md-custom-tab > li:hover > a,
        .<?php echo esc_attr($id); ?>.wpb_content_element .px_tabs_nav.md-custom-tab > li:hover > a i{
            color: <?php echo esc_attr(pixflow_colorConvertor($general_color,'rgba',1)); ?>;
        }
        .<?php echo esc_attr($id); ?>.wpb_content_element .px_tabs_nav.md-custom-tab > li > a,
        .<?php echo esc_attr($id); ?>.wpb_content_element .px_tabs_nav.md-custom-tab > li > a i{
            color: <?php echo esc_attr(pixflow_colorConvertor($general_color,'rgba',0.5)); ?>;
            transition: color 300ms;
        }
        .<?php echo esc_attr($id); ?>.wpb_content_element .px_tabs_nav.md-custom-tab .md-modernTab-add-tab strong{
            font-size: <?php echo esc_attr(pixflow_get_theme_mod('link_size',PIXFLOW_LINK_SIZE))+5; ?>px;
        }
    </style>

    <?php
    $output.=ob_get_clean();
    preg_match_all( '/md_modernTab ([^\]]+)/i', $content, $matches, PREG_OFFSET_CAPTURE );
    $tab_titles = array();

    if ( isset( $matches[1] ) && count($matches[1]) ) {
        $tab_titles = $matches[1];
    }else{
        if(preg_match_all( '~[^mBuilder\-element]*?<li .*?<a href="#tab-(.*?)".*?<i class=.*? (icon-.*?)["\']></i><div class="modernTabTitle">(.*?)</div>~is', $content, $matches, PREG_OFFSET_CAPTURE )){
            foreach($matches[3] as $key =>$titles){
                $tab_titles[$key] = array(' title="'.$titles[0].'" tab_icon_class="'.$matches[2][$key][0].'" tab_id="'.$matches[1][$key][0].'"');
            }
            $content = preg_replace('~[^mBuilder\-element]*?<ul class="px_tabs_nav.*?>.*?</ul>~is','',$content);
        }else{
            $tab_titles[] = array('title="' . esc_attr__( 'TAB', 'massive-dynamic' ) . '" tab_id="'.uniqid('tab').'"');
            $tab_titles[] = array('title="' . esc_attr__( 'TAB', 'massive-dynamic' ) . '" tab_id="'.uniqid('tab').'"');
        }
    }

    $tabs_nav = '';
    $tabs_nav .= '<ul class="px_tabs_nav ui-tabs-nav vc_clearfix md-custom-tab">';
    $i=0;

    foreach ( $tab_titles as $tab ) {
        $i++;
        $tab_atts = shortcode_parse_atts($tab[0]);
        $tab_atts['title'] = !array_key_exists('title',$tab_atts)?'Tab ':$tab_atts['title'];
        $tab_atts['tab_icon'] = !array_key_exists('tab_icon',$tab_atts)?'icon-cog':$tab_atts['tab_icon'];
        $tab_atts['tab_icon_class'] = !array_key_exists('tab_icon_class',$tab_atts)?'icon-cog':$tab_atts['tab_icon_class'];
        if (isset($tab_atts['title']) || isset($tab_atts['tab_icon'])) {
            $tabs_nav .= '<li data-model="md_modernTabs">
                <a href="#tab-' . (isset($tab_atts['tab_id']) ? $tab_atts['tab_id'] : sanitize_title($tab_atts['title'])) . '">';
            if($tab_atts['tab_icon_class']!='icon-empty') {
                $tabs_nav .='<i class="left-icon '.$tab_atts['tab_icon_class'].'"></i>';
            }
            if($tab_atts['title']!='') {
                $tabs_nav .='<div class="modernTabTitle">'.$tab_atts['title'].'</div>';
            }
            $tabs_nav .='</a></li>';
        }
    }

    $tabs_nav .= '</ul>' . "\n";

    $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, trim( $element . ' wpb_content_element md_modernTab ' ), 'md_modernTabs', $atts );

    $output .= "\n\t" . '<div class="'.$id.' '. $css_class .' '. esc_attr($animation['has-animation']) . '" data-interval="' . $interval . '"'.esc_attr($animation['animation-attrs']).'>';
    $output .= "\n\t\t" . '<div class="wpb_wrapper disable-sort wpb_tour_tabs_wrapper ui-tabs vc_clearfix">';
    $output .= pixflow_widget_title(array('title' => $title, 'extraclass' => $element . '_heading'));
    $output .= "\n\t\t\t" . $tabs_nav;
    $output .= "\n\t\t\t" . pixflow_js_remove_wpautop( $content );

    $output .= "\n\t\t" . '</div> ' ;
    $output .= "\n\t" . '</div> ';

    ob_start();
    ?>
    <script type="text/javascript">
        var $ = jQuery;
        $(function(){
            pixflow_modernTabshortcode("<?php echo esc_attr($id); ?>");

        });
        <?php pixflow_callAnimation(); ?>
    </script>
    <?php
    $output.=ob_get_clean();
    return $output;
}
