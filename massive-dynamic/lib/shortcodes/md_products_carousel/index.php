<?php
/**
 * Product Carousel Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_products_carousel', 'pixflow_get_style_script'); // pixflow_sc_products_carousel

function pixflow_sc_products_carousel( $atts, $content = null ){
    if ( !(in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || class_exists( 'WooCommerce' )) ) {
        $url = admin_url('themes.php?page=install-required-plugins');
        $a='<a href="'.$url.'">WooCommerce</a>';

        $mis = '<div class="miss-shortcode"><p class="title">'. esc_attr__('Oops!! Something\'s Missing','massive-dynamic').'</p><p class="desc">'.sprintf(esc_attr__('Please install and activate %s first, then add some products to use this shortcode','massive-dynamic'),$a).'</p></div>';

        return $mis;
    }
    $output = '';
    extract( shortcode_atts( array(
        'products_carousel_categories'  => '',
        'products_carousel_cols'  => 3,
        'products_height'  => '500',
    ), $atts ) );
    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_products_carousel',$atts);
    $id = pixflow_sc_id('products');
    //Convert slugs to IDs
    $catArr = array();
    $catArr  = pixflow_slugs_to_ids(explode(',', $products_carousel_categories), 'product_cat');
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
    );
    $args['tax_query'] =  array(
        array(
            'taxonomy' => 'product_cat',
            'field'    => 'id',
            'terms'    => $catArr
        ));
    $loop = new WP_Query( $args );
    $posts = array();
    if ( $loop->have_posts() ) {
        while ( $loop->have_posts() ) : $loop->the_post();
            $posts[] = get_the_ID();
        endwhile;
    }
    wp_reset_postdata();
    ob_start(); ?>
    <div class="<?php echo esc_attr($animation['has-animation']) ?>" <?php echo esc_attr($animation['animation-attrs']) ?>>
        <?php echo do_shortcode('[products ids="'.implode(',',$posts).'" columns="'.$products_carousel_cols.'"]');?>
        <div class="thumbnails-height"></div>
    </div>
    <?php
    pixflow_callAnimation(true);
    return ob_get_clean();
}
