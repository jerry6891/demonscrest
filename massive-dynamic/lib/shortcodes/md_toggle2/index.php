<?php
/**
 * Business Toggle Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_toggle2', 'pixflow_get_style_script'); // pixflow_sc_toggle2

function pixflow_sc_toggle2( $atts, $content = null )
{
    wp_enqueue_script('multi-open-accordion');
    $output = $title = $interval = $heading_size=$el_class = $collapsible = $main_color= $hover_color=$disable_keyboard = $active_tab = '';
    extract(shortcode_atts(array(
        'el_class'         => '',
        'active_tab'       => '',
        'main_color'       => 'rgb(0,0,0)',
        'hover_color'      => 'rgb(255,255,255)',
    ), $atts));
    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_toggle2',$atts);
    $id = pixflow_sc_id('md_toggle2');

    //define accordion type classes
    $acc_class = "";
    $active_tab = explode(",",$active_tab);
    ob_start();?>

    <style scoped>
        .<?php echo esc_attr($id);?> h3:hover a,
        .<?php echo esc_attr($id);?> h3:hover span {
            color :<?php echo esc_attr($hover_color); ?>!important;
        }
        .<?php echo esc_attr($id);?> h3:hover {
            background: <?php echo esc_attr($main_color); ?>;
        }

        .<?php echo esc_attr($id);?> .wpb_accordion_section h3.wpb_toggle_header{
            border: 2px solid <?php echo esc_attr($main_color); ?>;
        }
        .<?php echo esc_attr($id);?> .wpb_toggle_wrapper h3.wpb_toggle_header span.ui-accordion-header-icon{
            border-right: 2px solid <?php echo esc_attr($main_color); ?>;
        }

        .<?php echo esc_attr($id);?> h3.wpb_toggle_header a,
        .<?php echo esc_attr($id);?> h3.wpb_toggle_header span,
        .<?php echo esc_attr($id);?> .wpb_accordion_section .icon{
            color: <?php echo esc_attr($main_color); ?>;
            z-index: 99;
            position: absolute;
        }
        .<?php echo esc_attr($id);?> .wpb_accordion_section h3.wpb_toggle_header.ui-state-active{
            color :<?php echo esc_attr($hover_color); ?>!important;
            background: <?php echo esc_attr($main_color); ?>!important;
        }
        .<?php echo esc_attr($id);?> .wpb_accordion_section h3.wpb_toggle_header.ui-state-active a,
        .<?php echo esc_attr($id);?> .wpb_toggle_wrapper h3.ui-state-active span{
            color:<?php echo esc_attr($hover_color); ?>!important;
        }
        .<?php echo esc_attr($id);?> h3.ui-state-active a,
        .<?php echo esc_attr($id);?> h3.ui-state-active span{
            color:<?php echo esc_attr($hover_color); ?>;

        }
        .<?php echo esc_attr($id);?> h3.ui-state-active:hover a,
        .<?php echo esc_attr($id);?> h3.ui-state-active:hover span{
            color:<?php echo esc_attr($hover_color); ?>;
        }

    </style>
    <?php
    $output .= ob_get_clean();
    $css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_md_toggle2 '.$id.' wpb_content_element '. $el_class . ' not-column-inherit '.esc_attr($animation['has-animation']), 'md_toggle2', $atts);
    $output .= "\n\t" . '<div id="'.$id.'" class="' . $css_class . '" data-collapsible="' . $collapsible . '" data-vc-disable-keydown="' . (esc_attr(('yes' == $disable_keyboard ? 'true' : 'false'))) . '"'. esc_attr($animation['animation-attrs']) .'>'; //data-interval="'.$interval.'"
    $output .= "\n\t\t" . '<div class="wpb_wrapper disable-sort wpb_toggle_wrapper ui-accordion">';
    $output .= pixflow_widget_title(array('title' => $title, 'extraclass' => 'wpb_toggle_heading'));

    $output .= "\n\t\t\t" . pixflow_js_remove_wpautop($content);
    $output .= "\n\t\t" . '</div> ';
    $output .= "\n\t" . '</div> ';
    ob_start();
    ?>
    <script type="text/javascript">
        var <?php echo str_replace('-','_',$id);?>_saveBtnClicked = false;
        var $ = jQuery;
        $(function() {

            $('body').on('click', '.wpb_toggle_header a:last', function (e) {
                e.preventDefault();
                window.onresize();
            })
            $('body', window.parent.document).on('click', '.vc_panel-btn-save', function (e) {
                <?php echo str_replace('-','_',$id);?>_saveBtnClicked = true;
            })
            $('body').off('click','.<?php echo esc_attr($id);?> .wpb_toggle_header').on('click', '.<?php echo esc_attr($id);?> .wpb_toggle_header', function (e) {
                $(this).parent().find(' > .wpb_toggle_content ').slideToggle();
                if ($(this).hasClass('ui-state-active')) {
                    $(this).removeClass('ui-state-active').find('.ui-icon-triangle-1-e').removeClass('.ui-icon-triangle-1-e').addClass('.ui-icon-triangle-1-s');
                } else {
                    $(this).addClass('ui-state-active').find('.ui-icon-triangle-1-s').removeClass('.ui-icon-triangle-1-s').addClass('.ui-icon-triangle-1-e');
                }
            })
            $('#<?php echo esc_attr($id); ?> .wpb_toggle_header').removeClass('ui-state-active');
            $('#<?php echo esc_attr($id); ?> .wpb_toggle_content').slideUp();
            window.onresize();
            <?php
            foreach($active_tab as $active){
            $active = trim($active);
            $active = (int)$active;
            if($active==0) continue;
            ?>
            $('.<?php echo esc_attr($id)?>').find('.wpb_toggle_content').eq(<?php echo esc_attr($active)-1; ?>).slideDown().parent().find('.wpb_toggle_header').addClass('ui-state-active');
            <?php
            }
            ?>

        });
        <?php pixflow_callAnimation(); ?>
    </script>
    <?php
    $output.=ob_get_clean();
    return $output;
}