<?php
/**
 * Products Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_products', 'pixflow_get_style_script'); // pixflow_sc_products

function pixflow_sc_products( $atts, $content = null ){
    if ( !(in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || class_exists( 'WooCommerce' )) ) {
        $url = admin_url('themes.php?page=install-required-plugins');
        $a='<a href="'.$url.'">WooCommerce</a>';

        $mis = '<div class="miss-shortcode"><p class="title">'. esc_attr__('Oops!! Something\'s Missing','massive-dynamic').'</p><p class="desc">'.sprintf(esc_attr__('Please install and activate %s first, then add some products to use this shortcode','massive-dynamic'),$a).'</p></div>';

        return $mis;
    }
    $output = '';
    $product_cats = array();
    $terms = get_terms( 'product_cat', 'orderby=count&hide_empty=0' );
    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
        foreach ( $terms as $term ) {
            $product_cats[] = $term->name;
        }
    }
    extract( shortcode_atts( array(
        'products_categories'  => $product_cats[0],
        'products_cols'  => 3,
        'products_height'  => '500',
        'products_align'  => 'left',
        'products_style'  => 'classic',
        'products_number'  => '-1',
        'products_use_button'           => 'yes',
        'products_button_style'         => 'fade-oval',
        'products_button_text'          => 'More Products',
        'products_button_icon_class'    => 'icon-chevron-right',
        'products_button_color'         => 'rgba(0,0,0,1)',
        'products_button_text_color'    => '#fff',
        'products_button_bg_hover_color'=>'#9b9b9b',
        'products_button_hover_color'   => 'rgb(255,255,255)',
        'products_button_size'          => 'standard',
        'products_left_right_padding'           => 0,
        'products_button_url'           => '#',
        'products_button_target'        => '_self',
        'products_sale_bg_color'        => 'rgba(255,255,255,1)',
        'products_sale_text_color'      => 'rgba(0,0,0,1)'
    ), $atts ) );
    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_products',$atts);
    if($products_style == 'modern'){
        $products_style = 'modern-style-product';
    } else{
        $products_style = '';
    }
    $id = pixflow_sc_id('md-product');
    ob_start();
    ?>
    <style scoped>
        <?php if('left' == $products_align){ ?>
        .<?php echo esc_attr($id) ?> .products .product{
            text-align: left;
        }

        .<?php echo esc_attr($id) ?> .products-button{
            margin: 0;
        }
        <?php } elseif('center' == $products_align){ ?>
        .<?php echo esc_attr($id) ?> .products .product{
            text-align: center;
        }

        .<?php echo esc_attr($id) ?> .products .star-rating{
            margin: 0 auto 0.5em;
        }

        .<?php echo esc_attr($id) ?> .products-button{
            margin: 0 auto;
            display: table;
        }
        <?php } ?>
        .<?php echo esc_attr($id) ?> .products .product .onsale{
            background-color: <?php echo esc_attr($products_sale_bg_color); ?>;
            color: <?php echo esc_attr($products_sale_text_color); ?>;
        }
    </style>
    <div class="<?php echo esc_attr($id.' '.$animation['has-animation']) ?> thumbnails-height <?php echo esc_attr($products_style)?>" data-thumbnail-height="<?php echo esc_attr($products_height)?>" <?php echo esc_attr($animation['animation-attrs']) ?>>
        <?php
        echo do_shortcode('[product_category category="'.$products_categories.'" columns="'.$products_cols.'" per_page="'.$products_number.'"]');?>
        <?php if($products_use_button=='yes'){?>
            <div class="products-button">
                <?php echo pixflow_buttonMaker($products_button_style,$products_button_text,$products_button_icon_class,$products_button_url,$products_button_target,$products_align,$products_button_size,$products_button_color,$products_button_hover_color,$products_left_right_padding,$products_button_text_color,$products_button_bg_hover_color); ?>
            </div>
        <?php } ?>
    </div>
    <script type="text/javascript">
        var $ = jQuery;

        if ( typeof pixflow_Products == 'function' ){
            pixflow_Products();
        }
        <?php pixflow_callAnimation(); ?>
    </script>
    <?php
    return ob_get_clean();
}