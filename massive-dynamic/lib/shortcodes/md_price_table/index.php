<?php
/**
 * Price Table Shortcode
 *
 * @author Pixflow
 */
add_shortcode('md_price_table', 'pixflow_get_style_script'); // pixflow_sc_price_table

function pixflow_sc_price_table( $atts, $content = null ){
    extract( shortcode_atts( array(
        'title' =>  'Personal Plan',
        'title_color' =>  '#623e95',
        'price' =>  '50',
        'currency' =>  '$',
        'description' =>
            "Mobile-Optimized
Powerful Metrics
Free Domain
Annual Purchase
24/7 Support",
        'general_color' =>  '#898989',
        'bg_color' =>  '#fff',
        'use_button' =>  'yes',
        'button_style'         => 'fill-oval',
        'button_text'          => 'PURCHASE',
        'button_icon_class'    => 'icon-empty',
        'button_color'         => '#b3b3b3',
        'button_text_color'    => '#fff',
        'button_bg_hover_color'=> '#623e95',
        'button_hover_color'   => '#fff',
        'button_size'          => 'standard',
        'button_padding'           => 30,
        'button_url'           => '#',
        'button_target'        => '_self',
        'align'        => 'center',
    ), $atts ) );
    $id = pixflow_sc_id('md_price_table');
    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_price_table',$atts);
    ob_start();
    ?>
    <?php
    $align = trim($align);
    ?>
    <div class="pixflow-price-table clearfix <?php echo esc_attr($id.' '.$animation['has-animation'].' md-align-'.$align); ?>" <?php echo esc_attr($animation['animation-attrs']); ?>>
        <style scoped>
            .<?php echo esc_attr($id); ?> .price-table-container{
                background-color:<?php echo esc_attr($bg_color); ?>;
            }
            .<?php echo esc_attr($id); ?> .price-container,
            .<?php echo esc_attr($id); ?> .description{
                color:<?php echo esc_attr($general_color); ?>;
            }
            .<?php echo esc_attr($id); ?> .title,
            .<?php echo esc_attr($id); ?> .separator{
                color:<?php echo esc_attr($title_color); ?>;
            }
        </style>
        <div class="price-table-container">
            <div class="price-container">
                <span class="currency"><?php echo esc_attr($currency); ?></span>
                <span class="price"><?php echo esc_attr($price); ?></span>
            </div>
            <div class="title"><?php echo esc_attr($title); ?></div>
            <div class="separator"><span class="icon-zigzag"></span></div>
            <p class="description"><?php echo preg_replace("/&lt;(.*?)&gt;/i",'',esc_attr($description)); ?></p>
            <div class="price-table-button">
                <?php echo ('yes' == $use_button)?pixflow_buttonMaker($button_style,$button_text,$button_icon_class,$button_url,$button_target,'center',$button_size,$button_color,$button_hover_color,$button_padding,$button_text_color,$button_bg_hover_color):''; ?>
            </div>
        </div>
    </div>
    <?php
    pixflow_callAnimation(true);
    return ob_get_clean();
}
