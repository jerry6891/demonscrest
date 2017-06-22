<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

// Ensure visibility
if ( ! $product || ! $product->is_visible() ) {
	return;
}

global $product;
$attachment_ids = $product->get_gallery_attachment_ids();
$first_image = '';
foreach( $attachment_ids as $attachment_id )
{
    $image = wp_get_attachment_url( $attachment_id );
    $image = (false == $image)?PIXFLOW_PLACEHOLDER_LARGE:$image;
    $first_image =  $image_link = $image;
	break;
}
?>
<li <?php post_class(); ?> data-img="<?php echo esc_url($first_image); ?>">

	<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>
    <div class="purchase-buttom-holder">
        <?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
        <?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
    </div>
    <a class="title-link" href="">
        <?php
        /**
         * woocommerce_shop_loop_item_title hook
         *
         * @hooked woocommerce_template_loop_product_title - 10
         */
        do_action( 'woocommerce_shop_loop_item_title' );
        ?>
    </a>
    <?php
        /**
         * woocommerce_after_shop_loop_item_title hook
         *
         * @hooked woocommerce_template_loop_rating - 5
         * @hooked woocommerce_template_loop_price - 10
         */
        remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
        add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 11 );
        do_action( 'woocommerce_after_shop_loop_item_title' );

	?>

</li>
