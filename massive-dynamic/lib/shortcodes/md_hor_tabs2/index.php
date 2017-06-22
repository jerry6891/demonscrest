<?php
/**
 *  Horizontal tabs Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_hor_tabs2', 'pixflow_get_style_script'); // pixflow_sc_hor_tabs2

function pixflow_sc_hor_tabs2( $atts, $content = null ){
    $animation=$output = $title = $tab_icon  = $general_color = $hor_tab_hover_color='';
    extract( shortcode_atts( array(
        'general_color' => 'rgb(0,0,0)',
        'hor_tab_hover_color' => 'rgb(215,176,126)'
    ), $atts ) );
    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_hor_tabs2',$atts);
    wp_enqueue_script('jquery-ui-tabs');
    $element = 'wpb_tabs';
    $id = esc_attr(pixflow_sc_id('md_hor_tabs2'));
    ob_start();
    ?>
    <style scoped>
        .<?php echo esc_attr($id); ?>.wpb_content_element .px_tabs_nav li a i {
            color: <?php echo esc_attr(pixflow_colorConvertor($general_color,'rgba',0.7)); ?>
        }
        .<?php echo esc_attr($id); ?>.wpb_content_element .px_tabs_nav li a .horTabTitle,
        .<?php echo esc_attr($id); ?>.wpb_content_element .px_tabs_nav li a.md-hor-tab2-add-tab{
            color: <?php echo esc_attr($general_color); ?>;
        }

        .<?php echo esc_attr($id); ?>.wpb_content_element .px_tabs_nav li:hover a i,
        .<?php echo esc_attr($id); ?>.wpb_content_element .px_tabs_nav li:hover a .horTabTitle,
        .<?php echo esc_attr($id); ?>.wpb_content_element .px_tabs_nav li:hover a.md-hor-tab2-add-tab{
            color: <?php echo esc_attr($hor_tab_hover_color); ?>;
        }

        .<?php echo esc_attr($id); ?>.wpb_content_element .px_tabs_nav li.ui-tabs-active a i,
        .<?php echo esc_attr($id); ?>.wpb_content_element .px_tabs_nav li.ui-tabs-active a .horTabTitle{
            color: <?php echo esc_attr($hor_tab_hover_color); ?>;
        }
        .<?php echo esc_attr($id); ?>.wpb_content_element .px_tabs_nav li{
            border: solid 1px <?php echo esc_attr(pixflow_colorConvertor($general_color,'hex')); ?>;
            background-image: linear-gradient(<?php echo esc_attr(pixflow_colorConvertor($general_color,'hex')); ?>, <?php echo esc_attr(pixflow_colorConvertor($general_color,'hex')); ?>);
        }
    </style>
    <?php
    $output.=ob_get_clean();
    preg_match_all( '/md_hor_tab2 ([^\]]+)/i', $content, $matches, PREG_OFFSET_CAPTURE );
    $tab_titles = array();
    if ( isset( $matches[1] ) && count($matches[1]) ) {
        $tab_titles = $matches[1];
    }else{
        if(preg_match_all( '~[^mBuilder\-element]*?<li .*?<a href="#tab-(.*?)".*?<i class=.*? (icon-.*?)["\']></i><div class="horTabTitle">(.*?)</div>~is', $content, $matches, PREG_OFFSET_CAPTURE )){
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
        if (isset($tab_atts['title']) || isset($tab_atts['tab_icon'])) {
            $tabs_nav .= '<li data-model="md_hor_tabs2">
                    <a href="#tab-' . (isset($tab_atts['tab_id']) ? $tab_atts['tab_id'] : sanitize_title($tab_atts['title'])) . '"><i class="right-icon '.$tab_atts['tab_icon'].'"></i><div class="horTabTitle">'.$tab_atts['title'].'</div></a>
                </li>';
        }
    }
    $tabs_nav .= '</ul>' . "\n";
    $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, trim( $element . ' wpb_content_element ' ), $atts );
    $output .= "\n\t" . '<div class="'.esc_attr($id).' md_hor_tab2 clearfix '. $css_class .' '. esc_attr($animation["has-animation"]) .'" data-interval="' . 0 . '" '.esc_attr($animation["animation-attrs"]).'>';
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
            if(typeof pixflow_horTab == 'function'){
                pixflow_horTab('<?php echo esc_attr($id); ?>','business');
            }
            $('.<?php echo esc_attr($id); ?>').closest('.vc_md_hor_tabs2').find('.md-hor-tab2-add-tab').parent().remove();
            $('.<?php echo esc_attr($id); ?>').closest('.vc_md_hor_tabs2').find('.px_tabs_nav').append('<li class="unsortable"><a class="md-hor-tab2-add-tab vc_control-btn"><strong>+</strong><div class="modernTabTitle">ADD TAB</div></a></li>');
            $('.<?php echo esc_attr($id); ?>').closest('.vc_md_hor_tabs2').find('.md-hor-tab2-add-tab').click(function(e){
                e.preventDefault();
                $(this).closest('.mBuilder-element').find('>.mBuilder_controls .vc_control-btn[data-control="add_section"] .vc_btn-content').click();
            })
            if (!$('.<?php echo esc_attr($id); ?>').data("ui-tabs")) {
                $('.<?php echo esc_attr($id); ?>').on('click','li a',function(e){$(this).closest('li').click();return false;});
                $('.<?php echo esc_attr($id); ?>').on('click','.ui-tabs-nav li',function(e){
                    e.preventDefault();

                    $(this).parent().parent().find('.ui-tabs-panel,.mBuilder-md_hor_tab2').css('display','none');
                    $(this).parent().parent().find($(this).find('a').attr('href')).css('display','block');
                    $(this).parent().parent().find($(this).find('a').attr('href')).closest('.mBuilder-md_hor_tab2').css('display','');
                    $(this).siblings('li').removeClass('ui-tabs-active');
                    $(this).addClass('ui-tabs-active');
                })
                $('.<?php echo esc_attr($id); ?> .ui-tabs-nav li').first().click();
            }
        });
        <?php pixflow_callAnimation(); ?>
    </script>
    <?php
    $output.=ob_get_clean();
    return $output;
}