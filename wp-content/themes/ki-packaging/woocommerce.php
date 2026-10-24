<?php
/**
 * WooCommerce wrapper: shop, categories and single products.
 */
get_header();

$crumbs = array();
if ( is_singular( 'product' ) ) {
	$title  = get_the_title( get_queried_object_id() );
	$eyebrow = ki_product_cat_name( get_queried_object_id() );
	$crumbs = array( array( 'Our Products', ki_shop_url() ), array( $title, '' ) );
	$lead   = '';
} elseif ( is_product_taxonomy() ) {
	$term    = get_queried_object();
	$title   = $term ? $term->name : 'Our Products';
	$eyebrow = 'Product category';
	$crumbs  = array( array( 'Our Products', ki_shop_url() ), array( $title, '' ) );
	$lead    = $term && $term->description ? wp_strip_all_tags( $term->description ) : '';
} else {
	$title   = 'Our Products';
	$eyebrow = 'Printing & packaging catalogue';
	$crumbs  = array( array( 'Our Products', '' ) );
	$lead    = 'Browse our range of printed materials and packaging, then request a quotation for the items you need.';
}

if ( is_shop() && function_exists( 'ki_shop_has_elementor_hero' ) && ki_shop_has_elementor_hero() ) {
	ki_render_shop_hero();
} else {
	ki_page_hero( array(
		'eyebrow' => $eyebrow,
		'title'   => $title,
		'lead'    => $lead,
		'image'   => is_singular( 'product' ) ? 'press-detail' : 'pallets',
		'crumbs'  => $crumbs,
	) );
}
?>

<section class="section section--first shop-section">
	<div class="container">
		<?php if ( ! is_singular( 'product' ) ) : ?>
			<?php
			$cats = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => true, 'exclude' => array( (int) get_option( 'default_product_cat' ) ) ) );
			$cur  = is_product_taxonomy() ? get_queried_object_id() : 0;
			?>
			<div class="shop-toolbar" data-reveal>
				<div class="filter-bar">
					<a class="pill<?php echo $cur ? '' : ' is-active'; ?>" href="<?php echo esc_url( ki_shop_url() ); ?>">All products</a>
					<?php if ( $cats && ! is_wp_error( $cats ) ) : foreach ( $cats as $c ) : ?>
						<a class="pill<?php echo $cur === $c->term_id ? ' is-active' : ''; ?>" href="<?php echo esc_url( get_term_link( $c ) ); ?>"><?php echo esc_html( $c->name ); ?></a>
					<?php endforeach; endif; ?>
				</div>
				<form class="shop-search" role="search" method="get" action="<?php echo esc_url( ki_shop_url() ); ?>">
					<label class="screen-reader-text" for="shop-s">Search products</label>
					<?php ki_the_icon( 'search', '', 18 ); ?>
					<input id="shop-s" type="search" name="s" placeholder="Search products" value="<?php echo esc_attr( get_search_query() ); ?>">
					<input type="hidden" name="post_type" value="product">
				</form>
			</div>
		<?php endif; ?>

		<div class="shop-content<?php echo is_singular( 'product' ) ? ' is-single' : ''; ?>">
			<?php woocommerce_content(); ?>
		</div>
	</div>
</section>

<?php
get_template_part( 'template-parts/cta-band', null, array( 'title' => "Can't find what you need?", 'text' => 'We produce custom printing and packaging to your specification. Tell us what you have in mind.' ) );
get_footer();
