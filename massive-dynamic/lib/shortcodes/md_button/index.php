<?php
/**
 * Button Shortcode
 *
 * @author Pixflow
 */

/*  MD Button */
add_shortcode('md_button', 'pixflow_get_style_script'); // pixflow_sc_button

function pixflow_sc_button($atts, $content = null)
{

    extract(shortcode_atts(array(
        'button_style'       => 'fade-square',
        'button_text'        => 'Read More',
        'button_icon_class'  => 'icon-Layers',
        'button_url'         => '#',
        'button_target'      => '_self',
        'button_align'       => 'left',
        'button_size'        => 'standard',
        'button_color'       => '#000',
        'button_text_color'  => '#fff',
        'button_bg_hover_color' => '#9b9b9b',
        'button_hover_color' => '#fff',
        'left_right_padding' => '0',
    ), $atts));

    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_button',$atts);

    $output = pixflow_buttonMaker($button_style,$button_text,$button_icon_class,$button_url,$button_target,$button_align,$button_size,$button_color,$button_hover_color,$left_right_padding,$button_text_color,$button_bg_hover_color,$animation,true,true);
    $output .= pixflow_callAnimation(true);
    return $output;
}
