<?php
/**
 * Site chrome (topbar, navigation, footer contact, loader, quote popup, floating buttons).
 * Rendered by shortcodes inside the Elementor Theme Builder header/footer, and by header.php/footer.php
 * when no Theme Builder templates exist, so the output is identical either way.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* -------------------------------------------- Loader, progress bar, skip link */
add_action( 'wp_body_open', function () {
	?>
	<a class="skip-link" href="#main">Skip to content</a>
	<div class="loader" aria-hidden="true"><img src="<?php echo esc_url( ki_asset( 'img/icon.png' ) ); ?>" alt="" width="72" height="72"><span class="loader-bar"></span></div>
	<div class="scroll-progress" aria-hidden="true"><span></span></div>
	<?php
}, 5 );

/* ----------------------------------------- Quote popup and floating buttons */
add_action( 'wp_footer', function () {
	get_template_part( 'template-parts/quote-modal' );
	$wa = ki_wa_url();
	?>
	<div class="float-actions">
		<?php if ( $wa ) : ?>
			<a class="float-wa" href="<?php echo esc_url( $wa ); ?>" target="_blank" rel="noopener" aria-label="Chat with us on WhatsApp">
				<?php ki_the_icon( 'whatsapp', '', 28 ); ?><span class="float-wa-label">Chat with us</span>
			</a>
		<?php endif; ?>
		<button class="to-top" type="button" aria-label="Back to top" data-to-top><?php ki_the_icon( 'arrow-up', '', 20 ); ?></button>
	</div>
	<?php
}, 5 );

/* ------------------------------------------------------------------ Topbar */
function ki_render_topbar() {
	$phone   = ki_opt( 'phone' );
	$email   = ki_opt( 'email' );
	$socials = function_exists( 'ki_socials' ) ? ki_socials() : array();
	ob_start();
	?>
	<div class="topbar-inner">
		<ul class="topbar-info">
			<li><?php ki_the_icon( 'map-pin', '', 15 ); ?><span><?php echo esc_html( ki_opt( 'address' ) ); ?></span></li>
			<?php if ( $phone ) : ?><li><?php ki_the_icon( 'phone', '', 15 ); ?><a href="<?php echo esc_url( ki_tel( $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></li><?php endif; ?>
			<?php if ( $email ) : ?><li><?php ki_the_icon( 'mail', '', 15 ); ?><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></li><?php endif; ?>
		</ul>
		<div class="topbar-right">
			<?php if ( $socials ) : ?>
				<ul class="social-list">
					<?php foreach ( $socials as $key => $s ) : ?>
						<li><a href="<?php echo esc_url( $s['url'] ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( $s['label'] ); ?>"><?php ki_the_icon( $key, '', 16 ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			<?php else : ?>
				<span class="topbar-tag"><?php echo esc_html( ki_opt( 'tagline' ) ); ?></span>
			<?php endif; ?>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'ki_topbar', 'ki_render_topbar' );

/* -------------------------------------------------------------- Navigation */
function ki_render_nav() {
	$phone = ki_opt( 'phone' );
	ob_start();
	?>
	<nav class="primary-nav" id="primary-nav" aria-label="Primary" data-nav>
		<?php
		wp_nav_menu( array(
			'theme_location' => 'primary',
			'container'      => false,
			'menu_class'     => 'nav-list',
			'depth'          => 1,
			'fallback_cb'    => 'ki_primary_fallback',
		) );
		?>
		<div class="nav-extra">
			<a class="btn btn-primary btn-lg" href="<?php echo esc_url( ki_quote_url() ); ?>" data-quote>Get a Quotation</a>
			<?php if ( $phone ) : ?><a class="nav-phone" href="<?php echo esc_url( ki_tel( $phone ) ); ?>"><?php ki_the_icon( 'phone', '', 18 ); ?> <?php echo esc_html( $phone ); ?></a><?php endif; ?>
			<p><?php echo esc_html( ki_opt( 'address' ) ); ?></p>
		</div>
	</nav>
	<?php
	return ob_get_clean();
}
add_shortcode( 'ki_nav', 'ki_render_nav' );

function ki_render_nav_toggle() {
	return '<button class="nav-toggle" type="button" aria-controls="primary-nav" aria-expanded="false" aria-label="Open menu" data-nav-toggle><span></span><span></span><span></span></button>';
}
add_shortcode( 'ki_nav_toggle', 'ki_render_nav_toggle' );

/* ------------------------------------------------------------------ Footer */
/** Address, phone, email and hours from Settings > KI Packaging. */
function ki_render_footer_contact() {
	$phone  = ki_opt( 'phone' );
	$phone2 = ki_opt( 'phone2' );
	$email  = ki_opt( 'email' );
	$hours  = ki_opt( 'hours' );
	ob_start();
	?>
	<ul class="footer-contact-list">
		<li><?php ki_the_icon( 'map-pin', '', 18 ); ?><span><?php echo esc_html( ki_opt( 'address' ) ); ?></span></li>
		<?php if ( $phone ) : ?><li><?php ki_the_icon( 'phone', '', 18 ); ?><span><a href="<?php echo esc_url( ki_tel( $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a><?php if ( $phone2 ) : ?> / <a href="<?php echo esc_url( ki_tel( $phone2 ) ); ?>"><?php echo esc_html( $phone2 ); ?></a><?php endif; ?></span></li><?php endif; ?>
		<?php if ( $email ) : ?><li><?php ki_the_icon( 'mail', '', 18 ); ?><span><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></span></li><?php endif; ?>
		<?php if ( $hours ) : ?><li><?php ki_the_icon( 'clock', '', 18 ); ?><span><?php echo esc_html( $hours ); ?></span></li><?php endif; ?>
	</ul>
	<?php
	return ob_get_clean();
}
add_shortcode( 'ki_footer_contact', 'ki_render_footer_contact' );

function ki_render_social( $atts = array() ) {
	$socials = function_exists( 'ki_socials' ) ? ki_socials() : array();
	if ( ! $socials ) {
		return '';
	}
	ob_start();
	echo '<ul class="social-list social-list--footer">';
	foreach ( $socials as $key => $s ) {
		echo '<li><a href="' . esc_url( $s['url'] ) . '" target="_blank" rel="noopener" aria-label="' . esc_attr( $s['label'] ) . '">' . ki_icon( $key, '', 18 ) . '</a></li>'; // phpcs:ignore WordPress.Security.EscapeOutput
	}
	echo '</ul>';
	return ob_get_clean();
}
add_shortcode( 'ki_social', 'ki_render_social' );

add_shortcode( 'ki_year', function () {
	return esc_html( gmdate( 'Y' ) );
} );

/** The page's <main> landmark. Opened by templates (not header.php) so it exists with the Theme Builder header too. */
function ki_main_open() {
	echo '<main id="main" class="site-main">' . "\n";
}
function ki_main_close() {
	echo "</main>\n";
}
