<?php
/**
 * Product Categories Shortcode
 *
 * @author Pixflow
 */

add_shortcode('md_product_categories', 'pixflow_get_style_script'); // pixflow_sc_product_categories

function pixflow_sc_product_categories( $atts, $content = null ){
    if ( !(in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || class_exists( 'WooCommerce' )) ) {

        $url = admin_url('themes.php?page=install-required-plugins');
        $a='<a href="'.$url.'">WooCommerce</a>';

        $mis = '<div class="miss-shortcode"><p class="title">'. esc_attr__('Oops!! Something\'s Missing','massive-dynamic').'</p><p class="desc">'.sprintf(esc_attr__('Please install and activate %s first, then add some products to use this shortcode','massive-dynamic'),$a).'</p></div>';

        return $mis;
    }
    $output = '';
    extract(shortcode_atts(array(
        'product_categories' => '',
        'product_categories_cols' => 3,
        'product_categories_overlay_color' => 'rgba(0,0,0,0.2)',
        'product_categories_hover_text' => 'SEE THE COLLECTION',
        'product_categories_align' => 'center',
        'product_categories_height'  => '400',
        'product_categories_hover_color'=>'#fff'
    ), $atts));
    $animation = array();
    $animation = pixflow_shortcodeAnimation('md_product_categories',$atts);
    $id = pixflow_sc_id('product_categories');
    //Convert slugs to IDs
    $catArr = array();
    $catArr = pixflow_slugs_to_ids(explode(',', $product_categories), 'product_cat');
    $arg = array(
        'include' => $catArr,
        'orderby' => 'count',
        'hide_empty' => 0
    );
    $terms = get_terms('product_cat', $arg);
    if (!empty($terms) && !is_wp_error($terms)) {
        foreach ($terms as $term) {
            $thumbnail_id = get_woocommerce_term_meta( $term->term_id, 'thumbnail_id', true );
            $image = wp_get_attachment_url( $thumbnail_id );
            $image = (false == $image)?PIXFLOW_PLACEHOLDER1:$image;
            $product_cats[$term->term_id]['title'] = $term->name;
            $product_cats[$term->term_id]['subtitle'] = $term->description;
            $product_cats[$term->term_id]['url'] = get_term_link($term);
            $product_cats[$term->term_id]['image'] = $image;
        }
        $id = pixflow_sc_id('product-categories');
        ob_start();
        ?>
        <style>
            .<?php echo esc_attr($id);?> .overlay {
                background-color: <?php echo esc_attr($product_categories_overlay_color); ?>;
            }
        </style>
        <div class="<?php echo esc_attr($id.' '.$animation['has-animation']);?> product-categories clearfix" data-thumbnail-height="<?php echo esc_attr($product_categories_height)?>" <?php echo esc_attr($animation['animation-attrs']);?> data-cols="<?php echo esc_attr($product_categories_cols); ?>">
            <?php
            foreach ($product_cats as $cat) {
                ?>
                <div class="category">
                    <a href="<?php echo esc_url($cat['url']); ?>">
                        <div class="background" style="background-image: url('<?php echo esc_attr($cat['image'])!=''?esc_attr($cat['image']): PIXFLOW_THEME_ASSETS_URI.'/img/place-holder.jpg'; ?>')"></div>
                        <div class="overlay"></div>
                        <div class="border-holder">
                            <div class="top-border" style="background-color:<?php echo esc_attr($product_categories_hover_color); ?>"></div>
                            <div class="right-border" style="background-color:<?php echo esc_attr($product_categories_hover_color); ?>"></div>
                            <div class="bottom-border" style="background-color:<?php echo esc_attr($product_categories_hover_color); ?>"></div>
                            <div class="left-border" style="background-color:<?php echo esc_attr($product_categories_hover_color); ?>"></div>
                        </div>
                        <div class="meta <?php echo esc_attr($product_categories_align); ?>">
                            <h5 class="subtitle" style="color:<?php echo esc_attr($product_categories_hover_color); ?>"><?php echo esc_attr($cat['subtitle']); ?></h5>
                            <h4 class="title" style="color:<?php echo esc_attr($product_categories_hover_color); ?>"><?php echo esc_attr($cat['title']); ?></h4>
                        </div>
                        <h6 class="hover-text <?php echo esc_attr($product_categories_align); ?>" style="color:<?php echo esc_attr($product_categories_hover_color); ?>"><?php echo esc_attr($product_categories_hover_text)?></h6>
                    </a>
                </div>
                <?php
            }
            ?>
        </div>
        <script>
            "use strict";
            if ( typeof pixflow_productCategory == 'function' ){
                pixflow_productCategory();
            }
            <?php pixflow_callAnimation(); ?>
        </script>
        <?php
        return ob_get_clean();
    }
}