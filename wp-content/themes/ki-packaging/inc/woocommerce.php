<?php
/**
 * WooCommerce as a quotation catalogue: no prices, no cart, "Request a Quote" on every product.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'after_setup_theme', function () {
	add_theme_support( 'woocommerce', array(
		'thumbnail_image_width' => 720,
		'single_image_width'    => 1100,
		'product_grid'          => array( 'default_columns' => 3, 'min_columns' => 1, 'max_columns' => 4 ),
	) );
	add_theme_support( 'wc-product-gallery-slider' );
} );

// We style everything ourselves.
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

add_action( 'init', function () {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	// Catalogue mode.
	add_filter( 'woocommerce_is_purchasable', '__return_false' );
	add_filter( 'woocommerce_get_price_html', '__return_empty_string' );
	add_filter( 'woocommerce_product_tabs', function ( $tabs ) {
		unset( $tabs['reviews'], $tabs['additional_information'] );
		return $tabs;
	}, 98 );

	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
	remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
	remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
	remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );

	add_action( 'woocommerce_single_product_summary', 'ki_single_product_actions', 30 );
	add_action( 'woocommerce_single_product_summary', 'ki_single_product_meta', 40 );
} );

add_filter( 'woocommerce_show_page_title', '__return_false' );
add_filter( 'loop_shop_columns', function () {
	return 3;
} );
add_filter( 'loop_shop_per_page', function () {
	return 12;
} );
add_filter( 'woocommerce_output_related_products_args', function ( $args ) {
	$args['posts_per_page'] = 3;
	$args['columns']        = 3;
	return $args;
} );
add_filter( 'woocommerce_upsell_display_args', function ( $args ) {
	$args['posts_per_page'] = 3;
	return $args;
} );
add_filter( 'woocommerce_product_description_heading', '__return_empty_string' );
add_filter( 'woocommerce_gallery_image_size', function () {
	return 'large';
} );

function ki_single_product_actions() {
	global $product;
	if ( ! $product ) {
		return;
	}
	$name = $product->get_name();
	echo '<div class="product-actions">';
	echo '<a class="btn btn-primary btn-lg" href="' . esc_url( ki_quote_url( $name ) ) . '" data-quote data-product="' . esc_attr( $name ) . '">Request a quotation ';
	ki_the_icon( 'arrow-right', 'btn-arrow', 18 );
	echo '</a>';
	$wa = ki_wa_url( 'Hello, I would like a quotation for: ' . $name );
	if ( $wa ) {
		echo '<a class="btn btn-whatsapp btn-lg" href="' . esc_url( $wa ) . '" target="_blank" rel="noopener">';
		ki_the_icon( 'whatsapp', '', 20 );
		echo ' WhatsApp us</a>';
	}
	echo '</div>';
}

function ki_single_product_meta() {
	global $product;
	if ( ! $product ) {
		return;
	}
	$cats = wc_get_product_category_list( $product->get_id(), ', ' );
	echo '<ul class="product-points">';
	if ( $cats ) {
		echo '<li>' . ki_icon( 'tag', '', 18 ) . '<span>Category: ' . wp_kses_post( $cats ) . '</span></li>'; // phpcs:ignore WordPress.Security.EscapeOutput
	}
	echo '<li>' . ki_icon( 'layers', '', 18 ) . '<span>Custom sizes, materials and finishes on request</span></li>';
	echo '<li>' . ki_icon( 'zap', '', 18 ) . '<span>Fast turnaround with proof before production</span></li>';
	echo '</ul>';
}

/** Primary category name for a product card. */
function ki_product_cat_name( $product_id ) {
	$terms = get_the_terms( $product_id, 'product_cat' );
	if ( ! $terms || is_wp_error( $terms ) ) {
		return '';
	}
	foreach ( $terms as $t ) {
		if ( 'uncategorized' !== $t->slug ) {
			return $t->name;
		}
	}
	return '';
}

// Hide the cart/checkout-style plumbing from the front end.
add_filter( 'woocommerce_add_to_cart_fragments', '__return_empty_array' );
add_action( 'wp_enqueue_scripts', function () {
	if ( function_exists( 'is_woocommerce' ) ) {
		wp_dequeue_script( 'wc-cart-fragments' );
		wp_dequeue_script( 'wc-add-to-cart' );
	}
}, 99 );

/** One product card, shared by the shop loop and the home page. */
function ki_product_card( $product ) {
	if ( ! $product ) {
		return;
	}
	$id    = $product->get_id();
	$name  = $product->get_name();
	$link  = get_permalink( $id );
	$cat   = ki_product_cat_name( $id );
	$thumb = $product->get_image_id() ? wp_get_attachment_image( $product->get_image_id(), 'ki-card', false, array( 'loading' => 'lazy', 'decoding' => 'async', 'alt' => $name ) ) : '<span class="img-fallback"></span>';
	$short = wp_trim_words( wp_strip_all_tags( $product->get_short_description() ? $product->get_short_description() : $product->get_description() ), 16, '…' );
	?>
	<article class="pcard" data-tilt>
		<a class="pcard-media" href="<?php echo esc_url( $link ); ?>" aria-label="<?php echo esc_attr( $name ); ?>">
			<?php echo $thumb; // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<?php if ( $cat ) : ?><span class="pcard-cat"><?php echo esc_html( $cat ); ?></span><?php endif; ?>
		</a>
		<div class="pcard-body">
			<h3 class="pcard-title"><a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $name ); ?></a></h3>
			<?php if ( $short ) : ?><p class="pcard-text"><?php echo esc_html( $short ); ?></p><?php endif; ?>
			<div class="pcard-actions">
				<a class="link-arrow" href="<?php echo esc_url( $link ); ?>">Details <?php ki_the_icon( 'arrow-right', '', 16 ); ?></a>
				<a class="btn btn-sm btn-dark" href="<?php echo esc_url( ki_quote_url( $name ) ); ?>" data-quote data-product="<?php echo esc_attr( $name ); ?>">Get a quote</a>
			</div>
		</div>
	</article>
	<?php
}
