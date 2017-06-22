<?php
/**
 * product Compare Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_product_compare', 'pixflow_get_style_script'); // pixflow_sc_product_compare

function pixflow_sc_product_compare( $atts, $content = null ) {
    $output = $el_class= $class =$css_class= '';
    extract(shortcode_atts(array(
        'product_compare_image'         => PIXFLOW_THEME_IMAGES_URI."/place-holder.jpg",
        'product_compare_price'         => '150',
        'product_compare_currency'      => '$',
        'product_compare_title'         => 'GENERAL',
        'product_compare_heading'       => 'h1',
        'product_compare_text'          => 'Show your work & create imperssive portfolios without knowing any HTML or how to code.',
        'product_compare_add_image'     => 'yes',
        'product_compare_button'        => 'yes',
        'product_compare_button_style'  => 'fade-oval',
        'product_compare_button_text'   => 'BUY IT',
        'product_compare_button_size'   => 'standard',
        'product_compare_button_url'    => '#',
        'product_compare_button_target' => '_self',
        'product_compare_icon_class'    => 'icon-shopcart',
        'product_compare_general_color' => '#000000',
        'product_compare_button_color'  => '#000',
        'product_compare_hover_color'   => '#ffffff',
        'product_compare_button_text_color' => '#fff',
        'product_compare_button_bg_hover_color'=>'#959595',
        'product_compare_left_right_padding'  => '0',
        'align'                         => 'center'
    ), $atts));
    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_product_compare',$atts);
    $id = esc_attr(pixflow_sc_id('md_product_compare'));

    ob_start();

    ?>
    <style scoped>

        <?php echo '.'.esc_attr($id) ?> p,
        <?php echo '.'.esc_attr($id) ?> .product_compare_priceholder,
        <?php echo '.'.esc_attr($id); echo ' '.esc_attr($product_compare_heading); ?>{
            color: <?php echo esc_attr($product_compare_general_color); ?>;
        }

    </style>

    <?php
    $css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_product_compare '.$id.' wpb_content_element '. $el_class . ' not-column-inherit '.esc_attr($animation['has-animation']), 'md_product_compare', $atts);


    if(is_numeric($product_compare_image)){
        $imageSrc = wp_get_attachment_image_src( $product_compare_image, 'pixflow_product-compare') ;
        $imageSrc = (false == $imageSrc)?PIXFLOW_PLACEHOLDER_BLANK:$imageSrc[0];
        $image = $imageSrc ;
        $product_compare_image =  $imageSrc ;
    }

    ?>
    <?php
    $align = trim($align);
    ?>
    <div class="<?php echo esc_attr($css_class); ?> <?php echo esc_attr($id.' '.$animation['has-animation']); ?> md-align-<?php echo esc_attr($align);?>" <?php echo esc_attr($animation['animation-attrs']); ?> >
        <div class="wpb_wrapper wpb_product_compare_wrapper ui-product_compare">

            <?php if($product_compare_add_image=='yes'){?>
                <div class="product_compare_img"><img src="<?php echo esc_url($product_compare_image); ?>" /></div>
            <?php } ?>

            <div class="product_compare_priceholder"><span class="product_compare_currency"><?php echo esc_attr($product_compare_currency); ?></span><span class="product_compare_price"><?php echo esc_attr($product_compare_price); ?></span></div>
            <div class="product_compare_title_holder"><<?php echo esc_attr($product_compare_heading); ?> class="product_compare_title"><?php echo esc_attr($product_compare_title); ?></<?php echo esc_attr($product_compare_heading)?>></div>
        <div class="product_compare_text"><p><?php echo preg_replace("/&lt;(.*?)&gt;/i",'',esc_attr($product_compare_text)); ?></p></div>
        <?php if($product_compare_button=='yes') {?>
            <div class="button-parent">
                <?php echo pixflow_buttonMaker($product_compare_button_style,$product_compare_button_text,$product_compare_icon_class,$product_compare_button_url,$product_compare_button_target,'center',$product_compare_button_size,$product_compare_button_color,$product_compare_hover_color,$product_compare_left_right_padding,$product_compare_button_text_color,$product_compare_button_bg_hover_color); ?>
            </div>
        <?php } ?>
    </div>
    </div>

    <?php
    pixflow_callAnimation(true);
    $output .= ob_get_clean();

    return $output;
}
