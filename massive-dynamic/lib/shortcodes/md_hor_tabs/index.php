<?php
/**
 *  Horizontal Tabs Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_hor_tabs', 'pixflow_get_style_script');

function pixflow_sc_hor_tabs( $atts, $content = null ){
    $animation=$output = $title = $tab_icon  = $general_color = $use_bg=$bg_type = $bg_color = $bg_image='';
    extract( shortcode_atts( array(
        'general_color' => 'rgb(255,255,255)',
        'use_bg'        => 'yes',
        'bg_type'       => 'color',
        'bg_color'      => 'rgb(215,176,126)',
        'hor_tab_hover_color' => 'rgb(215,176,126)',
        'bg_image'      => ''
    ), $atts ) );
    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_hor_tabs',$atts);
    wp_enqueue_script('jquery-ui-tabs');
    $element = 'wpb_tabs';
    $id = esc_attr(pixflow_sc_id('md_hor_tabs'));
    ob_start();
    ?>
    <style scoped>
        .<?php echo esc_attr($id); ?>.wpb_content_element .px_tabs_nav li a i,
        .<?php echo esc_attr($id); ?>.wpb_content_element .px_tabs_nav li a .horTabTitle,
        .<?php echo esc_attr($id); ?>.wpb_content_element .px_tabs_nav li a.md-hor-tab-add-tab{
            color: <?php echo esc_attr($general_color); ?>;
        }
        .<?php echo esc_attr($id); ?>.wpb_content_element .px_tabs_nav li{
            border-bottom: solid 1px <?php echo esc_attr(pixflow_colorConvertor($general_color,'rgba', 0.5)); ?>;
        }
        .<?php echo esc_attr($id); ?>.wpb_content_element .px_tabs_nav li:first-child{
            border-top: solid 1px <?php echo esc_attr(pixflow_colorConvertor($general_color,'rgba', 0.5)); ?>;
        }
        <?php if($use_bg=='yes'){ ?>
        .<?php echo esc_attr($id); ?>.md_hor_tab.wpb_content_element .px_tabs_nav.md-custom-tab > li > a{
            padding: 0 20px 0 20px;
        }
        .<?php echo esc_attr($id); ?>.wpb_content_element .px_tabs_nav li.ui-tabs-active,
        .<?php echo esc_attr($id); ?>.wpb_content_element .px_tabs_nav li:hover{
            background-color: <?php echo esc_attr(pixflow_colorConvertor($general_color,'rgba', 0.3)); ?>;
        }
        <?php }else{ ?>
        .<?php echo esc_attr($id); ?>.md_hor_tab.wpb_content_element .px_tabs_nav.md-custom-tab > li > a{
            padding: 0;
        }
        .<?php echo esc_attr($id); ?>.wpb_content_element .px_tabs_nav li.ui-tabs-active,
        .<?php echo esc_attr($id); ?>.wpb_content_element .px_tabs_nav li:hover{
            background-color: transparent;
        }
        .<?php echo esc_attr($id); ?>.wpb_content_element .px_tabs_nav li .horTabTitle,
        .<?php echo esc_attr($id); ?>.wpb_content_element .px_tabs_nav li i{
            transition: color 0.3s;
        }

        .<?php echo esc_attr($id); ?>.wpb_content_element .px_tabs_nav li.ui-tabs-active .horTabTitle,
        .<?php echo esc_attr($id); ?>.wpb_content_element .px_tabs_nav li:hover .horTabTitle,
        .<?php echo esc_attr($id); ?>.wpb_content_element .px_tabs_nav li.ui-tabs-active i,
        .<?php echo esc_attr($id); ?>.wpb_content_element .px_tabs_nav li:hover i{
            color: <?php echo esc_attr($hor_tab_hover_color); ?>;
        }
        <?php } ?>
        <?php if($use_bg=='yes' && $bg_type=='color'){ ?>
        .<?php echo esc_attr($id); ?>.wpb_content_element.md_hor_tab ul.px_tabs_nav {
            background-color: <?php echo esc_attr($bg_color); ?>;
        }
        .<?php echo esc_attr($id); ?>.wpb_content_element.md_hor_tab .overlay{
            display:none;
        }
        <?php }
        if($use_bg=='yes' && $bg_type=='image'){
            if(is_numeric($bg_image)){
                $bg_image =  wp_get_attachment_image_src( $bg_image,'full') ;
                $bg_image = (false == $bg_image)?PIXFLOW_PLACEHOLDER_BLANK:$bg_image[0];
            }
        ?>
        .<?php echo esc_attr($id); ?>.wpb_content_element.md_hor_tab ul.px_tabs_nav {
            background-image: url(<?php echo esc_url($bg_image); ?>);
            background-color: transparent;
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
        }
        .<?php echo esc_attr($id); ?>.wpb_content_element.md_hor_tab .overlay{
            display:block;
        }
        <?php }else{
        ?>
        .<?php echo esc_attr($id); ?>.wpb_content_element.md_hor_tab .overlay{
            display:none;
        }
        <?php
        } ?>
    </style>
    <?php
    $output.=ob_get_clean();
    preg_match_all( '/md_hor_tab ([^\]]+)/i', $content, $matches, PREG_OFFSET_CAPTURE );
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
    $hasBg = $use_bg;
    $tabs_nav .= '<ul class="px_tabs_nav ui-tabs-nav vc_clearfix md-custom-tab">';
    $i=0;
    foreach ( $tab_titles as $tab ) {
        $i++;
        $tab_atts = shortcode_parse_atts($tab[0]);
        $tab_atts['title'] = !array_key_exists('title',$tab_atts)?'Tab ':$tab_atts['title'];
        $tab_atts['tab_icon'] = !array_key_exists('tab_icon',$tab_atts)?'icon-cog':$tab_atts['tab_icon'];
        if (isset($tab_atts['title']) || isset($tab_atts['tab_icon'])) {
            $tabs_nav .= '<li data-model="md_hor_tabs">
                    <a href="#tab-' . (isset($tab_atts['tab_id']) ? $tab_atts['tab_id'] : sanitize_title($tab_atts['title'])) . '"><i class="right-icon '.$tab_atts['tab_icon'].'"></i><div class="horTabTitle">'.$tab_atts['title'].'</div><i class="right-icon icon-angle-right"></i></a>
                </li>';
        }
    }
    if($hasBg=='yes'){
        $tabs_nav.= '<div class="overlay"></div>';
    }
    $tabs_nav .= '</ul>' . "\n";
    $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, trim( $element . ' wpb_content_element ' ), $atts );
    $output .= "\n\t" . '<div class="'.$id.' md_hor_tab clearfix '. $css_class .' '. esc_attr($animation["has-animation"]) .'" data-interval="' . 0 . '" '.esc_attr($animation["animation-attrs"]).'>';
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
                pixflow_horTab('<?php echo esc_attr($id); ?>','');
            }
            $('.<?php echo esc_attr($id); ?>').closest('.vc_md_hor_tabs').find('.md-hor-tab-add-tab').parent().remove();
            $('.<?php echo esc_attr($id); ?>').closest('.vc_md_hor_tabs').find('.px_tabs_nav').append('<li class="unsortable"><a class="md-hor-tab-add-tab vc_control-btn"><strong>+</strong><div class="modernTabTitle">ADD TAB</div></a></li>');
            $('.<?php echo esc_attr($id); ?>').closest('.vc_md_hor_tabs').find('.md-hor-tab-add-tab').click(function(e){
                e.preventDefault();
                $(this).closest('.mBuilder-element').find('>.mBuilder_controls .vc_control-btn[data-control="add_section"] .vc_btn-content').click();
            })
            if (!$('.<?php echo esc_attr($id); ?>').data("ui-tabs")) {
                $('.<?php echo esc_attr($id); ?>').on('click','li a',function(e){$(this).closest('li').click();return false;});
                $('.<?php echo esc_attr($id); ?>').on('click','.ui-tabs-nav li',function(e){
                    e.preventDefault();

                    $(this).parent().parent().find('.ui-tabs-panel,.mBuilder-md_hor_tab').css('display','none');
                    $(this).parent().parent().find($(this).find('a').attr('href')).css('display','block');
                    $(this).parent().parent().find($(this).find('a').attr('href')).closest('.mBuilder-md_hor_tab').css('display','');
                    $(this).siblings('li').removeClass('ui-tabs-active');
                    $(this).addClass('ui-tabs-active');
                })
                $('.<?php echo esc_attr($id); ?> .ui-tabs-nav li').first().click();
            }
        })
        <?php pixflow_callAnimation(); ?>
    </script>
    <?php
    $output.=ob_get_clean();
    return $output;
}