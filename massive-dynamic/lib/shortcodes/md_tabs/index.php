<?php
/**
 * Tabs Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_tabs', 'pixflow_get_style_script'); // pixflow_sc_tabs

function pixflow_sc_tabs( $atts, $content = null ){
    $output = $title = $tab_icon = $tab_icon_class = $tab_color = $title_color=$tab_active_color = $interval = '';

    extract( shortcode_atts( array(
        'interval'         => '',
        'tab_color'        => 'rgba(43,42,40,1)',
        'tab_active_color' => 'rgba(235,78,1,1)',
        'title_color'      => 'rgba(255,255,255,1)',
        'tabs_background'  => 'rgba(247,247,247,1)'
    ), $atts ) );
    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_tabs',$atts);

    wp_enqueue_script('jquery-ui-tabs');

    $element = 'wpb_tabs';
    $id = esc_attr(pixflow_sc_id('md_tabs'));
    ob_start();
    ?>
    <style scoped>
        .<?php echo esc_attr($id); ?>.wpb_content_element .px_tabs_nav.md-custom-tab{
            background: <?php echo esc_attr($tab_color);?>;
        }
        .<?php echo esc_attr($id); ?>.wpb_content_element .px_tabs_nav{
            border-bottom: 4px solid <?php echo esc_attr($tab_active_color);?>;
        }
        .<?php echo esc_attr($id); ?>.wpb_content_element .px_tabs_nav.md-custom-tab > li.ui-tabs-active{
            background-color: <?php echo esc_attr($tab_active_color);?>;
        }
        .<?php echo esc_attr($id); ?>.wpb_content_element .px_tabs_nav.md-custom-tab > li:not(.ui-tabs-active):hover{
            background-color: <?php echo esc_attr(pixflow_colorConvertor($tab_active_color,'rgba',0.5));?>;
        }
        .<?php echo esc_attr($id); ?>.wpb_content_element .px_tabs_nav.md-custom-tab > li{
            border-right: 1px solid <?php echo esc_attr(pixflow_colorConvertor($title_color,'rgba',0.2)); ?>;
        }
        .<?php echo esc_attr($id); ?>.wpb_content_element .px_tabs_nav.md-custom-tab > li.ui-tabs-active{
            color: <?php echo esc_attr($title_color); ?>;
        }

        .<?php echo esc_attr($id); ?>.wpb_content_element .px_tabs_nav.md-custom-tab > li.ui-tabs-active a,
        .<?php echo esc_attr($id); ?>.wpb_content_element .px_tabs_nav.md-custom-tab > li.ui-tabs-active a i{
            color: <?php echo esc_attr($title_color); ?>;
        }
        .<?php echo esc_attr($id); ?>.wpb_content_element .px_tabs_nav.md-custom-tab > li:not(.ui-tabs-active):hover > a,
        .<?php echo esc_attr($id); ?>.wpb_content_element .px_tabs_nav.md-custom-tab > li:not(.ui-tabs-active):hover > a i{
            color: <?php echo esc_attr(pixflow_colorConvertor($title_color,'rgba',0.5)); ?>;
        }
        .<?php echo esc_attr($id); ?>.wpb_content_element .px_tabs_nav.md-custom-tab > li > a{
            color: <?php echo esc_attr(pixflow_colorConvertor($title_color,'rgba',0.4)); ?>;
        }
        .<?php echo esc_attr($id); ?>.wpb_content_element .px_tabs_nav.md-custom-tab > li a i{
            color: <?php echo esc_attr(pixflow_colorConvertor($title_color,'rgba',0.4)); ?>;
        }

        .<?php echo esc_attr($id); ?>.wpb_content_element .px_tabs_nav.md-custom-tab > li > a,
        .<?php echo esc_attr($id); ?>.wpb_content_element .px_tabs_nav.md-custom-tab .md-tab-add-tab strong{
            font-size: <?php echo esc_attr(pixflow_get_theme_mod('link_size',PIXFLOW_LINK_SIZE)); ?>px;
        }

        .<?php echo esc_attr($id); ?>.wpb_content_element .wpb_wrapper > .vc_md_tab > .wpb_tab,
        .<?php echo esc_attr($id); ?>.wpb_content_element .wpb_wrapper > .wpb_tab{
            background-color:<?php echo esc_attr($tabs_background);?>;
        }

    </style>

    <?php
    $output.=ob_get_clean();
    preg_match_all( '/\[md_tab ([^\]]*)/i', $content, $matches, PREG_OFFSET_CAPTURE );
    $tab_titles = array();
    if ( isset( $matches[1] ) && count($matches[1]) ) {
        $tab_titles = $matches[1];
    }else{
        $tab_titles[] = array('title="' . esc_attr__( 'TAB', 'massive-dynamic' ) . '" tab_id="'.uniqid('tab').'"');
        $tab_titles[] = array('title="' . esc_attr__( 'TAB', 'massive-dynamic' ) . '" tab_id="'.uniqid('tab').'"');
    }

    $tabs_nav = '';
    $tabs_nav .= '<ul class="px_tabs_nav ui-tabs-nav vc_clearfix md-custom-tab">';
    $i=0;

    foreach ( $tab_titles as $tab ) {
        $i++;
        $tab_atts = shortcode_parse_atts($tab[0]);
        if(!is_array($tab_atts)){
            $tab_atts = array();
        }
        $tab_atts['title'] = !array_key_exists('title',$tab_atts)?'Tab ':$tab_atts['title'];
        $tab_atts['tab_icon'] = !array_key_exists('tab_icon',$tab_atts)?'icon-cog':$tab_atts['tab_icon'];
        $tab_atts['tab_icon_class'] = !array_key_exists('tab_icon_class',$tab_atts)?'icon-cog':$tab_atts['tab_icon_class'];
        if (isset($tab_atts['title']) || isset($tab_atts['tab_icon'])) {
            $tabs_nav .= '<li data-model="md_tabs">
                    <a href="#tab-' . (isset($tab_atts['tab_id']) ? $tab_atts['tab_id'] : sanitize_title($tab_atts['title'])) . '"><i class="left-icon '.$tab_atts['tab_icon_class'].'"></i><span>'.$tab_atts['title'].'</span></a>
                </li>';
        }
    }

    $tabs_nav .= '</ul>' . "\n";

    $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, trim( $element . ' wpb_content_element md_custom_tabs' ), 'md_tabs', $atts );

    $output .= "\n\t" . '<div class="'.$id.' '. $css_class .' '. esc_attr($animation["has-animation"]) .'" data-interval="' . $interval . '" '.esc_attr($animation["animation-attrs"]).'>';
    $output .= "\n\t\t" . '<div class="wpb_wrapper disable-sort wpb_tour_tabs_wrapper ui-tabs vc_clearfix">';
    $output .= pixflow_widget_title( array( 'title' => $title, 'extraclass' => $element . '_heading' ) );
    $output .= "\n\t\t\t" . $tabs_nav;
    $output .= "\n\t\t\t" . pixflow_js_remove_wpautop( $content );

    $output .= "\n\t\t" . '</div> ' ;
    $output .= "\n\t" . '</div> ';

    ob_start();
    ?>
    <script type="text/javascript">
        var $ = jQuery;
        $(function(){
            if($('body').hasClass('vc_editor')){
                $('.<?php echo esc_attr($id); ?>').closest('.vc_md_tabs').find('.md-tab-add-tab').parent().remove();
                $('.<?php echo esc_attr($id); ?>').closest('.vc_md_tabs').find('.px_tabs_nav').append('<li class="unsortable"><a  class="md-tab-add-tab vc_control-btn"><strong>+</strong>ADD TAB</a></li>');
                $('.<?php echo esc_attr($id); ?>').closest('.vc_md_tabs').find('.md-tab-add-tab').click(function(e){
                    e.preventDefault();
                    $(this).closest('.mBuilder-element').find('>.mBuilder_controls .vc_control-btn[data-control="add_section"] .vc_btn-content').click();
                })
            }
            setTimeout(function(){
                var navWidth = $('.<?php echo esc_attr($id); ?>').find('.px_tabs_nav').width()-1;
                var length = $('.<?php echo esc_attr($id); ?>').find('.px_tabs_nav li').length;
                $('.<?php echo esc_attr($id); ?>').find('.px_tabs_nav li').css('min-width',Math.floor(navWidth/length));
            },100)

            var doIt;
            $(window).resize(function(){
                if(doIt){
                    clearTimeout(doIt);
                }
                var selector_re = $('.<?php echo esc_attr($id); ?>');
                doIt = setTimeout(function(){
                    var navWidth = selector_re.find('.px_tabs_nav').width()-1;
                    var length = selector_re.find('.px_tabs_nav li').length;
                    if(selector_re.find('.md-tab-add-tab').length){
                        if(!selector_re.find('.md-tab-add-tab').is(':visible')){
                            length -=1;
                        }
                    }
                    selector_re.find('.px_tabs_nav li').css('min-width',Math.floor(navWidth/length));
                },150)
            })
            if (!$('.<?php echo esc_attr($id); ?>').data("ui-tabs")) {
                $('.<?php echo esc_attr($id); ?>').on('click.tab','li a',function(e){$(this).closest('li').click();return false;});
                $('.<?php echo esc_attr($id); ?>').on('click','.ui-tabs-nav li',function(e){
                    e.preventDefault();

                    $(this).parent().parent().find('.ui-tabs-panel').css('display','none');
                    $(this).parent().parent().find($(this).find('a').attr('href')).css('display','block');
                    $(this).siblings('li').removeClass('ui-tabs-active');
                    $(this).addClass('ui-tabs-active');
                })
                $('.<?php echo esc_attr($id); ?> .ui-tabs-nav li').first().click();
            }
        });
        if(typeof pixflow_tabShortcode == 'function'){
            pixflow_tabShortcode();
        }
        <?php pixflow_callAnimation(); ?>

    </script>
    <?php
    $output.=ob_get_clean();

    return $output;
}
