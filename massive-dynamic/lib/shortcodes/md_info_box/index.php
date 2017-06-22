<?php
/**
 * Info Box Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_info_box', 'pixflow_get_style_script'); // pixflow_sc_info_box

function pixflow_sc_info_box( $atts, $content = null ) {

    extract(shortcode_atts(array(
        'info_box_title'       => 'Planning for the
future.',
        'info_box_checkbox'    => 'yes',
        'info_box_description' => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.',
        'info_box_icon_class'  => 'icon-romance-love-target',

        'info_box_title_color'       => '#0338a2',
        'info_box_description_color' => '#7e7e7e',
        'info_box_border_color'      => 'rgba(31,213,190, .1)',

        'info_box_button'                => 'yes',
        'info_box_button_style'          => 'fill-rectangle',
        'info_box_button_text'           => 'View more',
        'info_box_button_icon_class'     => 'icon-empty',
        'info_box_button_color'          => '#017eff',
        'info_box_button_text_color'     => '#fff',
        'info_box_button_bg_hover_color' => '#017eff',
        'info_box_button_hover_color'    => '#fff',
        'info_box_button_size'           => 'standard',
        'info_box_button_padding'        => 30,
        'info_box_button_url'            => '#',
        'info_box_button_target'         => '_self',
    ), $atts));

    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_info_box',$atts);
    $id = pixflow_sc_id('md_info_box');

    $borderColor = pixflow_colorConvertor($info_box_border_color, 'rgb');

    ob_start();
    ?>

    <style scoped>

        .<?php echo esc_attr($id); ?>.sc-info-box {
            border-color: <?php echo esc_attr($info_box_border_color);?>;
        }

        .<?php echo esc_attr($id); ?>.sc-info-box i {
            color: <?php echo pixflow_colorConvertor($borderColor,'rgba',.2);?>;
        }

        .<?php echo esc_attr($id); ?>.sc-info-box .title {
            color: <?php echo esc_attr($info_box_title_color)?>;
        }

        .<?php echo esc_attr($id); ?>.sc-info-box .separator{
            background-color: <?php echo esc_attr($info_box_title_color)?>;
        }


        .<?php echo esc_attr($id); ?>.sc-info-box .description {
            color: <?php echo esc_attr($info_box_description_color)?>;
        }

        .<?php echo esc_attr($id); ?>.sc-info-box:hover {
            box-shadow:inset 0 0 0 3px <?php echo esc_attr(pixflow_colorConvertor($info_box_border_color,'rgb'))?>;
            border-color :<?php echo esc_attr(pixflow_colorConvertor($info_box_border_color,'rgb'))?>;
        }

    </style>

    <div class="sc-info-box <?php echo esc_attr($id.' '.$animation['has-animation']); ?>" <?php echo esc_attr($animation['animation-attrs']); ?>>

        <i class="<?php echo esc_attr($info_box_icon_class); ?>"></i>

        <h3 class="title"><?php echo preg_replace("/&lt;(.*?)&gt;/i",'',esc_attr($info_box_title)); ?></h3>

        <?php if ($info_box_checkbox == 'yes') { ?>
            <hr class="separator" />
        <?php } ?>

        <p class="description"><?php echo preg_replace("/&lt;(.*?)&gt;/i",'',esc_attr($info_box_description)); ?></p>

        <div class="price-box-button">
            <?php echo ('yes' == $info_box_button)?pixflow_buttonMaker($info_box_button_style,$info_box_button_text,$info_box_button_icon_class,$info_box_button_url,$info_box_button_target,'center',$info_box_button_size,$info_box_button_color,$info_box_button_hover_color,$info_box_button_padding,$info_box_button_text_color,$info_box_button_bg_hover_color):''; ?>
        </div>

    </div> <!-- info box ends -->

    <?php
    pixflow_callAnimation(true);
    return ob_get_clean();
}
