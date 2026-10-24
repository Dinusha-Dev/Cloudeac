<?php
/**
 * Product card inside the WooCommerce loop (overrides the plugin template).
 */
defined( 'ABSPATH' ) || exit;

global $product;
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>
<li <?php wc_product_class( 'pcard-li', $product ); ?>>
	<?php ki_product_card( $product ); ?>
</li>
