<?php
/**
 * Full Button Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_full_button', 'pixflow_get_style_script'); // pixflow_sc_full_button

function pixflow_sc_full_button($atts, $content = null)
{

    extract(shortcode_atts(array(
        'full_button_height'        => '90',
        'full_button_text'        => esc_attr__('Read more','massive-dynamic'),
        'full_button_text_size'        => '19',
        'full_button_heading'        => 'h3',
        'full_button_hover_letter_spacing'  => '2',
        'full_button_url'  => '#',
        'full_button_target'  => '_self',
        'full_button_bg_color'  => '#202020',
        'full_button_text_color'  => '#FFF',
        'full_button_bg_hover_color'  => '#3E005D',
        'full_button_hover_color'  => '#FFF',
    ), $atts));

    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_full_button',$atts);
    $id = pixflow_sc_id('textbox');

    ob_start(); ?>

    <style scoped>
        <?php echo '.'.esc_attr($id) ?>{
            height: <?php echo esc_attr($full_button_height); ?>px;
            background-color: <?php echo esc_attr($full_button_bg_color); ?>;
        }
        <?php echo '.'.esc_attr($id) ?>:hover{
            background-color: <?php echo esc_attr($full_button_bg_hover_color); ?>;
        }
        <?php echo '.'.esc_attr($id) ?> <?php echo esc_attr($full_button_heading); ?>{
            font-size: <?php echo esc_attr($full_button_text_size); ?>px;
            color: <?php echo esc_attr($full_button_text_color); ?>;
            transition: 0.3s; /*don't move or remove this line*/
        }
        <?php echo '.'.esc_attr($id) ?>:hover <?php echo esc_attr($full_button_heading); ?>{
            letter-spacing: <?php echo esc_attr($full_button_hover_letter_spacing); ?>px;
            color: <?php echo esc_attr($full_button_hover_color); ?>;
        }
    </style>

    <div class="full-width-button <?php echo esc_attr($id.' '.$animation['has-animation']); ?>" <?php echo esc_attr($animation['animation-attrs']); ?>>
        <a href="<?php echo esc_url($full_button_url); ?>" target="<?php echo esc_attr($full_button_target); ?>">
            <<?php echo esc_attr($full_button_heading); ?> class="title">
            <?php echo esc_attr($full_button_text); ?>
        </<?php echo esc_attr($full_button_heading); ?>>
        </a>
    </div>

    <?php pixflow_callAnimation(true); ?>

    <?php
    return ob_get_clean();
}