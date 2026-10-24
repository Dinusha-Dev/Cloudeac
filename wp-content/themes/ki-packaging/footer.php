<?php
/**
 * Site footer, quote modal and floating actions.
 */
$phone   = ki_opt( 'phone' );
$phone2  = ki_opt( 'phone2' );
$email   = ki_opt( 'email' );
$hours   = ki_opt( 'hours' );
$socials = function_exists( 'ki_socials' ) ? ki_socials() : array();
$wa      = ki_wa_url();
?>
</main>

<footer class="site-footer">
	<div class="footer-glow" aria-hidden="true"></div>
	<div class="container footer-grid">
		<div class="footer-brand">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
				<img src="<?php echo esc_url( ki_asset( 'img/logo-light-h.png' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="260" height="68" loading="lazy">
			</a>
			<p>High-quality, cutting-edge offset printing and packaging solutions. Commitment, dedication and customer satisfaction are the heart of how we work.</p>
			<?php if ( $socials ) : ?>
				<ul class="social-list social-list--footer">
					<?php foreach ( $socials as $key => $s ) : ?>
						<li><a href="<?php echo esc_url( $s['url'] ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( $s['label'] ); ?>"><?php ki_the_icon( $key, '', 18 ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
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
				<?php
				$cats = function_exists( 'wc_get_product_category_list' ) ? get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => true, 'number' => 7, 'exclude' => array( (int) get_option( 'default_product_cat' ) ) ) ) : array();
				if ( $cats && ! is_wp_error( $cats ) ) :
					foreach ( $cats as $c ) :
						?>
						<li><a href="<?php echo esc_url( get_term_link( $c ) ); ?>"><?php echo esc_html( $c->name ); ?></a></li>
						<?php
					endforeach;
				else :
					?>
					<li><a href="<?php echo esc_url( ki_page_url( 'printing-services' ) ); ?>">Offset printing</a></li>
					<li><a href="<?php echo esc_url( ki_page_url( 'packaging-solutions' ) ); ?>">Cartons &amp; boxes</a></li>
				<?php endif; ?>
			</ul>
		</div>

		<div class="footer-col footer-contact">
			<h3>Visit &amp; contact</h3>
			<ul>
				<li><?php ki_the_icon( 'map-pin', '', 18 ); ?><span><?php echo esc_html( ki_opt( 'address' ) ); ?></span></li>
				<?php if ( $phone ) : ?><li><?php ki_the_icon( 'phone', '', 18 ); ?><a href="<?php echo esc_url( ki_tel( $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a><?php if ( $phone2 ) : ?> / <a href="<?php echo esc_url( ki_tel( $phone2 ) ); ?>"><?php echo esc_html( $phone2 ); ?></a><?php endif; ?></li><?php endif; ?>
				<?php if ( $email ) : ?><li><?php ki_the_icon( 'mail', '', 18 ); ?><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></li><?php endif; ?>
				<?php if ( $hours ) : ?><li><?php ki_the_icon( 'clock', '', 18 ); ?><span><?php echo esc_html( $hours ); ?></span></li><?php endif; ?>
			</ul>
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

<?php get_template_part( 'template-parts/quote-modal' ); ?>

<div class="float-actions">
	<?php if ( $wa ) : ?>
		<a class="float-wa" href="<?php echo esc_url( $wa ); ?>" target="_blank" rel="noopener" aria-label="Chat with us on WhatsApp">
			<?php ki_the_icon( 'whatsapp', '', 28 ); ?><span class="float-wa-label">Chat with us</span>
		</a>
	<?php endif; ?>
	<button class="to-top" type="button" aria-label="Back to top" data-to-top><?php ki_the_icon( 'arrow-up', '', 20 ); ?></button>
</div>

<?php wp_footer(); ?>
</body>
</html>
