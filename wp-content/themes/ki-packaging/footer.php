<?php
/**
 * Fallback site footer (used when no Elementor Theme Builder footer exists).
 * The quote popup and floating buttons come from the wp_footer hook in inc/chrome.php.
 */
$cats = function_exists( 'wc_get_product_category_list' ) ? get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => true, 'number' => 7, 'exclude' => array( (int) get_option( 'default_product_cat' ) ) ) ) : array();
?>
<footer class="site-footer">
	<div class="footer-glow" aria-hidden="true"></div>
	<div class="container footer-grid">
		<div class="footer-brand">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
				<img src="<?php echo esc_url( ki_asset( 'img/logo-light-h.png' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="260" height="68" loading="lazy">
			</a>
			<p><?php echo esc_html( ki_opt( 'footer_text', 'High-quality, cutting-edge offset printing and packaging solutions.' ) ); ?></p>
			<?php echo ki_render_social(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
		<div class="footer-col">
			<h3>Explore</h3>
			<ul>
				<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
				<li><a href="<?php echo esc_url( ki_page_url( 'about-us' ) ); ?>">About Us</a></li>
				<li><a href="<?php echo esc_url( ki_page_url( 'printing-services' ) ); ?>">Printing Services</a></li>
				<li><a href="<?php echo esc_url( ki_page_url( 'packaging-solutions' ) ); ?>">Packaging Solutions</a></li>
				<li><a href="<?php echo esc_url( ki_shop_url() ); ?>">Our Products</a></li>
				<li><a href="<?php echo esc_url( ki_page_url( 'gallery' ) ); ?>">Gallery</a></li>
				<li><a href="<?php echo esc_url( ki_page_url( 'contact-us' ) ); ?>">Contact Us</a></li>
			</ul>
		</div>
		<div class="footer-col">
			<h3>What we make</h3>
			<ul>
				<?php if ( $cats && ! is_wp_error( $cats ) ) : foreach ( $cats as $c ) : ?>
					<li><a href="<?php echo esc_url( get_term_link( $c ) ); ?>"><?php echo esc_html( $c->name ); ?></a></li>
				<?php endforeach; endif; ?>
			</ul>
		</div>
		<div class="footer-col footer-contact">
			<h3>Visit &amp; contact</h3>
			<?php echo ki_render_footer_contact(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<a class="btn btn-primary" href="<?php echo esc_url( ki_quote_url() ); ?>" data-quote>Get a Quotation</a>
		</div>
	</div>
	<div class="footer-bottom">
		<div class="container footer-bottom-inner">
			<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( ki_opt( 'company' ) ); ?>. All rights reserved.</p>
			<p class="footer-credit"><?php echo esc_html( ki_opt( 'tagline' ) ); ?> &middot; Kadawatha, Sri Lanka</p>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
