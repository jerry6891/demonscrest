<?php
/**
 * Accordion Shortcode
 *
 * @author Pixflow
 */

/*Accordion*/
add_shortcode('md_accordion', 'pixflow_get_style_script'); // pixflow_sc_accordion

function pixflow_sc_accordion( $atts, $content = null )
{
    wp_enqueue_script('jquery-ui-accordion');
    $output = $title = $interval = $el_class=$main_color=$hover_color = $collapsible = $disable_keyboard = $active_tab=$heading_size=$theme_style = '';
    extract(shortcode_atts(array(
        'title'            => '',
        'interval'         => '',
        'el_class'         => '',
        'collapsible'      => '',
        'disable_keyboard' => '',
        'active_tab'       => '1',
        'theme_style'      => 'with_border',
        'main_color'       => 'rgb(0,0,0)',
        'hover_color'      => 'rgb(220,220,220)',
    ), $atts));

    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_accordion',$atts);
    $id = pixflow_sc_id('md_accordion');
    //define accordion type classes
    $acc_class = "";

    switch($theme_style) {
        case "with_border":
            $acc_class = "with_border";
            break;
        case "without_border":
            $acc_class = "without_border";
            break;
    }

    $main_color = esc_attr($main_color);
    ob_start();?>

    <style scoped>
        .<?php echo esc_attr($id);?>.with_border .wpb_accordion_wrapper h3:hover a,
        .<?php echo esc_attr($id);?>.with_border .wpb_accordion_wrapper h3:hover span {
            color:<?php echo esc_attr($main_color) ?>!important;
        }
        .<?php echo esc_attr($id);?>.with_border .wpb_accordion_wrapper h3:after {
            background: <?php echo esc_attr($hover_color); ?>;
        }
        .<?php echo esc_attr($id);?>.with_border{
            border: 1px solid <?php echo esc_attr($main_color) ?>;
            border-bottom: none;
        }

        .<?php echo esc_attr($id);?> .wpb_accordion_section{
            border-bottom: 1px solid <?php echo esc_attr(pixflow_colorConvertor($main_color,'rgba',.6)) ?>;
        }

        /* with border */
        .<?php echo esc_attr($id);?>.with_border .wpb_accordion_wrapper h3.wpb_accordion_header a,
        .<?php echo esc_attr($id);?>.with_border .wpb_accordion_wrapper h3.wpb_accordion_header span,
        .<?php echo esc_attr($id);?>.with_border .wpb_accordion_section .icon{
            color: <?php echo esc_attr($main_color) ?>;
            z-index: 99;
            position: absolute;
        }
        .<?php echo esc_attr($id);?>.with_border .wpb_accordion_section h3.wpb_accordion_header.ui-state-active{
            background: <?php echo esc_attr($hover_color); ?>!important;
        }
        .<?php echo esc_attr($id);?>.with_border .wpb_accordion_section h3.wpb_accordion_header.ui-state-active a,
        .<?php echo esc_attr($id);?>.with_border .wpb_accordion_wrapper h3.ui-state-active span{
            color:<?php echo esc_attr($main_color); ?>!important;
        }
        .<?php echo esc_attr($id);?>.with_border .wpb_accordion_wrapper h3.ui-state-active a,
        .<?php echo esc_attr($id);?>.with_border .wpb_accordion_wrapper h3.ui-state-active span{
            color:<?php echo esc_attr($hover_color); ?>;
        }
        .<?php echo esc_attr($id);?>.with_border .wpb_accordion_wrapper h3.ui-state-active:hover a,
        .<?php echo esc_attr($id);?>.with_border .wpb_accordion_wrapper h3.ui-state-active:hover span{
            color:<?php echo esc_attr($main_color); ?>;
        }

        /* without border */

        .<?php echo esc_attr($id);?>.without_border .wpb_accordion_wrapper h3.wpb_accordion_header a,
        .<?php echo esc_attr($id);?>.without_border .wpb_accordion_wrapper h3.wpb_accordion_header span,
        .<?php echo esc_attr($id);?>.without_border .wpb_accordion_section .icon{
            color: <?php echo esc_attr($main_color) ?>;
        }
        .<?php echo esc_attr($id);?>.without_border .wpb_accordion_wrapper h3:hover a,
        .<?php echo esc_attr($id);?>.without_border .wpb_accordion_wrapper h3:hover span {
            color: <?php echo esc_attr($hover_color); ?>;
        }

    </style>

    <?php
    $output .= ob_get_clean();
    $css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_accordion '.$id.' wpb_content_element '. $acc_class .' '. $el_class . ' not-column-inherit '.esc_attr($animation['has-animation']), 'md_accordion', $atts);
    $output .= "\n\t" . '<div class="' . $css_class . '" data-collapsible="' . $collapsible . '" data-vc-disable-keydown="' . (esc_attr(('yes' == $disable_keyboard ? 'true' : 'false'))) . '" data-active-tab="' . $active_tab . '"'. esc_attr($animation['animation-attrs']) .'>'; //data-interval="'.$interval.'"
    $output .= "\n\t\t" . '<div class="wpb_wrapper disable-sort wpb_accordion_wrapper ui-accordion">';
    $output .= pixflow_widget_title(array('title' => $title, 'extraclass' => 'wpb_accordion_heading'));

    $output .= "\n\t\t\t" . pixflow_js_remove_wpautop($content);

    $output .= "\n\t\t" . '</div> ';
    $output .= "\n\t" . '</div> ';
    ob_start();
    ?>

    <script type="text/javascript">

        var <?php echo str_replace('-','_',$id);?>_saveBtnClicked = false,
            $ = jQuery;

        $(function(){
            $('body').on('click','.wpb_accordion_header a:last',function(e){
                e.preventDefault();

                window.onresize();
            });

            $('body').on('click', '.<?php echo esc_attr($id);?> .wpb_accordion_header', function (e) {
                e.preventDefault();
                if(!$(this).parent().parent().hasClass('mBuilder-element')){
                    var others = $(this).parent().parent().find('.wpb_accordion_content');
                    var otherHeaders = $(this).parent().parent().find('.wpb_accordion_header');
                }else{
                    var others = $(this).parent().parent().parent().find('.wpb_accordion_content');
                    var otherHeaders = $(this).parent().parent().parent().find('.wpb_accordion_header');
                }
                others.stop().slideUp();
                var totalHeight=0;
                $(this).parent().find(' > .wpb_accordion_content ').children().each(function(){
                    totalHeight = totalHeight + $(this).outerHeight(true);
                });
                window.onresize();
                $(this).parent().find(' > .wpb_accordion_content ').stop().slideDown();
                otherHeaders.removeClass('ui-state-active').find('.ui-icon-triangle-1-e').removeClass('ui-icon-triangle-1-e').addClass('ui-icon-triangle-1-s')
                $(this).addClass('ui-state-active').find('.ui-icon-triangle-1-s').removeClass('.ui-icon-triangle-1-s').addClass('.ui-icon-triangle-1-e');
            });

        });

        $('.<?php echo esc_attr($id); ?> .wpb_accordion_header').removeClass('ui-state-active');
        $('.<?php echo esc_attr($id); ?> .wpb_accordion_content').slideUp();

        <?php
        $active_tab = trim($active_tab);
        $active_tab = (int)$active_tab;
        ?>

        $('.<?php echo esc_attr($id)?>').find('.wpb_accordion_content').eq(<?php echo esc_attr($active_tab)-1; ?>).slideDown().parent().find('.wpb_accordion_header').addClass('ui-state-active');
        <?php pixflow_callAnimation();  ?>

    </script>
    <?php

    $output.=ob_get_clean();
    return $output;
}