<?php
/**
 * Price Box Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_price_box', 'pixflow_get_style_script'); // pixflow_sc_price_box

function pixflow_sc_price_box( $atts, $content = null ){

    extract( shortcode_atts( array(

        'price_box_title'                => 'Personal',
        'price_box_title_color'          => '#623e95',
        'price_box_price'                => '69.00',
        'price_box_currency'             => '$',
        'price_box_subtitle'             => 'Monthly',
        'price_box_general_color'        => '#96df5c',
        'price_box_border_color'         => '#cccccc',

        'price_box_use_button'            => 'yes',
        'price_box_button_style'          => 'fill-rectangle',
        'price_box_button_text'           => 'Purchase',
        'price_box_button_icon_class'     => 'icon-empty',
        'price_box_button_color'          => '#f0f0f0',
        'price_box_button_text_color'     => '#7e7e7e',
        'price_box_button_bg_hover_color' => '#96df5c',
        'price_box_button_hover_color'    => '#623e95',
        'price_box_button_size'           => 'standard',
        'price_box_button_padding'        => 30,
        'price_box_button_url'            => '#',
        'price_box_button_target'         => '_self',

        'price_box_item_num'    => 5,
        'price_box_offer_chk'   => 'no',
        'price_box_offer_title' => 'BEST OFFER',
        'price_box_items_color' => '#898989',
        'align' => 'left'

    ), $atts ) );

    $id = pixflow_sc_id('md_price_box');
    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_price_box',$atts);


    for($i=1; $i<=$price_box_item_num; $i++){
        $items[$i] = shortcode_atts( array(
            'price_box_list_item_'.$i => 'This is text for item'.$i,
        ), $atts );
    }

    ob_start();

    $count=($price_box_item_num<4)?"few-items":"";
    ?>
    <?php
    $align = trim($align);
    ?>
    <div class="pixflow-price-box clearfix <?php echo esc_attr($id.' '.$animation['has-animation'] . ' md-align-' . $align . " " . $count) ?>" <?php echo esc_attr($animation['animation-attrs']); ?>>

        <style scoped>

            .<?php echo esc_attr($id); ?> .price-box-container {
                border-color: <?php echo esc_attr($price_box_border_color); ?>;
            }

            .<?php echo esc_attr($id); ?> .price-box-container:hover {
                box-shadow: inset 0 0 0 4px <?php echo esc_attr($price_box_border_color); ?>;, 0 0 1px rgba(0, 0, 0, 0);
            }

            .<?php echo esc_attr($id); ?> .currency,
            .<?php echo esc_attr($id); ?> .price,
            .<?php echo esc_attr($id); ?> .sub-title {
                color: <?php echo esc_attr($price_box_general_color); ?>;
            }

            .<?php echo esc_attr($id); ?> .offer-box {
                background-color: <?php echo esc_attr($price_box_general_color); ?>;
            }

            .<?php echo esc_attr($id); ?> .offer-box .price_box_title {
                color: <?php echo esc_attr($bg_color); ?>;
            }

            .<?php echo esc_attr($id); ?> .title {
                color: <?php echo esc_attr($price_box_title_color); ?>;
            }

            .<?php echo esc_attr($id); ?> .lists .icons {
                color: <?php echo esc_attr($price_box_general_color); ?>;
            }

            .<?php echo esc_attr($id); ?> .item {
                color: <?php echo esc_attr($price_box_items_color); ?>;
            }

            .<?php echo esc_attr($id); ?> .price-box-button .shortcode-btn {
                visibility: <?php echo ('yes' == $price_box_use_button)?'visible':'hidden'; ?>;
            }

        </style>
        <div class="price-box-align-wraper">
            <div class="price-box-container clearfix">

                <div class="price-container">
                    <div class="text-part">
                        <h6 class="title"><?php echo esc_attr($price_box_title); ?></h6>
                        <span class="currency"><?php echo esc_attr($price_box_currency); ?></span>
                        <span class="price"><?php echo esc_attr($price_box_price); ?></span>
                        <p class="sub-title"><?php echo esc_attr($price_box_subtitle) ?></p>
                    </div>
                    <div class="price-box-button">
                        <?php echo pixflow_buttonMaker($price_box_button_style,$price_box_button_text,$price_box_button_icon_class,$price_box_button_url,$price_box_button_target,'center',$price_box_button_size,$price_box_button_color,$price_box_button_hover_color,$price_box_button_padding,$price_box_button_text_color,$price_box_button_bg_hover_color); ?>
                    </div>
                </div>

                <div class="lists">
                    <ul>

                        <?php

                        foreach($items as $key=>$item)
                        {
                            $price_box_title  = $item['price_box_list_item_'.$key];
                            if('' != $price_box_title) {?>
                                <li>
                                    <span class="icons icon-checkmark2"></span>
                                    <span class="item"><?php echo esc_attr($price_box_title); ?></span>
                                </li>
                            <?php } ?>
                        <?php } ?>

                    </ul>
                </div>


                <?php if ($price_box_offer_chk == 'yes') { ?>

                    <div class="offer-box">
                        <span class="title"> <?php echo esc_attr($price_box_offer_title); ?> </span>
                    </div>

                <?php } ?>

            </div>
        </div>

    </div>

    <?php
    pixflow_callAnimation(true);
    return ob_get_clean();
}
