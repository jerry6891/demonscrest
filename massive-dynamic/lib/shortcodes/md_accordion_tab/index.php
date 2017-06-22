<?php
/**
 * Accordion Tab Shortcode
 *
 * @author Pixflow
 */

/*Accordion Tab*/
add_shortcode('md_accordion_tab', 'pixflow_get_style_script'); // pixflow_sc_accordion_tab

/*-----------------------------------------------------------------------------------*/
/*  Accordion Tab
/*-----------------------------------------------------------------------------------*/

function pixflow_sc_accordion_tab( $atts, $content = null ) {
    $output = $title = $el_id =$icon = '';

    extract( shortcode_atts( array(
        'title'       => 'Section',
        'icon'        => 'icon-laptop',
    ), $atts ) );

    $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_accordion_section group', 'md_accordion_tab', $atts );
    $id= pixflow_sc_id('acc_section');
    $output .= "\n\t\t\t" . '<div id="'.$id.'" class="'.$css_class.'">';
    $output .= "\n\t\t\t\t" . '<h3 class="wpb_accordion_header ui-accordion-header"><div class="icon_left"><span class="icon '.$icon.'"></span></div><a href="#'.sanitize_title($title).'" onclick="return false">'.$title.'</a></h3>';

    $output .= "\n\t\t\t\t" . '<div class="wpb_accordion_content ui-accordion-content vc_clearfix">';
    $output .= ($content=='' || $content==' ') ? esc_attr__("Empty section. Edit page to add content here.", 'massive-dynamic') : "\n\t\t\t\t" . pixflow_js_remove_wpautop($content);
    $output .= "\n\t\t\t\t" . '</div>';
    $output .= "\n\t\t\t" . '</div> '. "\n";

    ob_start();
    ?>

    <script type="text/javascript">
        var $ = jQuery;

        $(function(){
            if($('body').hasClass('vc_editor')){
                $('#<?php echo esc_attr($id); ?>').closest('.vc_md_accordion').find('.md-accordion-add-tab').remove();
                var addSectionBtn = $('<a  class="md-accordion-add-tab vc_control-btn"><strong>+</strong>Add new tab</a>');

                $('#<?php echo esc_attr($id); ?>').closest('.vc_md_accordion').find('.wpb_accordion_section').last().append(addSectionBtn);
                $('#<?php echo esc_attr($id); ?>').closest('.vc_md_accordion').find('.md-accordion-add-tab').click(function(e){
                    e.preventDefault();
                    $(this).closest('.mBuilder-element').parent().closest('.mBuilder-element').find('a.vc_control-btn[title="Add new Section"] .vc_btn-content').click();
                })
            }

            // Remove padding if icon does not exist
            if ( $('#<?php echo esc_attr($id); ?>').find('.icon').hasClass('icon-empty') ) {
                $('#<?php echo esc_attr($id); ?>').find('h3').css('padding', '0');
            }
        });

        // Centerize text and icons
        $(window).load(function(){

            $('#<?php echo esc_attr($id); ?>').find('h3').find('> a').css('line-height', '30px');
            $('#<?php echo esc_attr($id); ?>').find('h3').find('.icon_left').css('margin', '27px 0 0 15px');
            $('#<?php echo esc_attr($id); ?>').find('h3').find('> span').css('line-height', '30px');
        });

    </script>

    <?php
    $output.=ob_get_clean();
    return $output;
}