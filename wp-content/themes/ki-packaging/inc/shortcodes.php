<?php
/**
 * Shortcodes for data-driven blocks that live inside Elementor pages.
 * Each one renders from WordPress data (products, gallery, settings) so the content stays in one place.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** [ki_products limit="8" featured="1" category="slug" cols="4"] */
add_shortcode( 'ki_products', function ( $atts ) {
	if ( ! function_exists( 'wc_get_products' ) ) {
		return '';
	}
	$a    = shortcode_atts( array( 'limit' => 8, 'featured' => '1', 'category' => '', 'cols' => 4 ), $atts, 'ki_products' );
	$args = array( 'limit' => (int) $a['limit'], 'status' => 'publish', 'orderby' => 'menu_order', 'order' => 'ASC' );
	if ( $a['category'] ) {
		$args['category'] = array_map( 'trim', explode( ',', $a['category'] ) );
	}
	$products = array();
	if ( '1' === (string) $a['featured'] ) {
		$products = wc_get_products( $args + array( 'featured' => true ) );
	}
	if ( count( $products ) < 2 ) {
		$products = wc_get_products( $args );
	}
	if ( ! $products ) {
		return '';
	}
	ob_start();
	echo '<div class="pgrid pgrid--' . (int) $a['cols'] . '">';
	foreach ( $products as $p ) {
		echo '<div class="pgrid-item rv">';
		ki_product_card( $p );
		echo '</div>';
	}
	echo '</div>';
	return ob_get_clean();
} );

/** [ki_breadcrumbs] : Home / Current page. */
add_shortcode( 'ki_breadcrumbs', function () {
	ob_start();
	ki_breadcrumbs( array( array( get_the_title(), '' ) ) );
	return ob_get_clean();
} );

/** [ki_contact_info] : address, phone, email, hours, WhatsApp and socials from Settings > KI Packaging. */
add_shortcode( 'ki_contact_info', function () {
	$phone   = ki_opt( 'phone' );
	$phone2  = ki_opt( 'phone2' );
	$email   = ki_opt( 'email' );
	$hours   = ki_opt( 'hours' );
	$wa      = ki_wa_url();
	$socials = function_exists( 'ki_socials' ) ? ki_socials() : array();
	ob_start();
	?>
	<ul class="info-cards">
		<li class="info-card">
			<span class="info-ico"><?php ki_the_icon( 'map-pin', '', 24 ); ?></span>
			<div><h3>Address</h3><p><?php echo esc_html( ki_opt( 'address' ) ); ?></p></div>
		</li>
		<?php if ( $phone ) : ?>
		<li class="info-card">
			<span class="info-ico"><?php ki_the_icon( 'phone', '', 24 ); ?></span>
			<div><h3>Phone</h3><p><a href="<?php echo esc_url( ki_tel( $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a><?php if ( $phone2 ) : ?><br><a href="<?php echo esc_url( ki_tel( $phone2 ) ); ?>"><?php echo esc_html( $phone2 ); ?></a><?php endif; ?></p></div>
		</li>
		<?php endif; ?>
		<?php if ( $email ) : ?>
		<li class="info-card">
			<span class="info-ico"><?php ki_the_icon( 'mail', '', 24 ); ?></span>
			<div><h3>Email</h3><p><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></p></div>
		</li>
		<?php endif; ?>
		<?php if ( $hours ) : ?>
		<li class="info-card">
			<span class="info-ico"><?php ki_the_icon( 'clock', '', 24 ); ?></span>
			<div><h3>Opening hours</h3><p><?php echo esc_html( $hours ); ?></p></div>
		</li>
		<?php endif; ?>
		<?php if ( $wa ) : ?>
		<li class="info-card info-card--wa">
			<span class="info-ico"><?php ki_the_icon( 'whatsapp', '', 24 ); ?></span>
			<div><h3>WhatsApp</h3><p><a href="<?php echo esc_url( $wa ); ?>" target="_blank" rel="noopener">Chat with our team</a></p></div>
		</li>
		<?php endif; ?>
	</ul>
	<?php if ( $socials ) : ?>
		<ul class="social-list social-list--solid">
			<?php foreach ( $socials as $key => $s ) : ?>
				<li><a href="<?php echo esc_url( $s['url'] ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( $s['label'] ); ?>"><?php ki_the_icon( $key, '', 20 ); ?></a></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
	<?php
	return ob_get_clean();
} );

/** Gallery markup, shared by the shortcode and the legacy PHP template. */
function ki_gallery_html() {
	$items = function_exists( 'ki_gallery_items' ) ? ki_gallery_items() : array();
	$cats  = array();
	foreach ( $items as $it ) {
		foreach ( $it['cats'] as $i => $slug ) {
			$cats[ $slug ] = $it['names'][ $i ];
		}
	}
	ksort( $cats );
	ob_start();
	if ( $cats ) :
		?>
		<div class="filter-bar" role="tablist" aria-label="Gallery filter" data-filter-bar>
			<button type="button" class="pill is-active" data-filter="*">All</button>
			<?php foreach ( $cats as $slug => $name ) : ?>
				<button type="button" class="pill" data-filter="<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $name ); ?></button>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
	<div class="gallery-grid" data-gallery>
		<?php
		foreach ( $items as $i => $it ) :
			$full  = wp_get_attachment_image_src( $it['thumb'], 'large' );
			$class = ( 0 === $i % 7 ) ? ' g-wide' : ( ( 3 === $i % 7 ) ? ' g-tall' : '' );
			?>
			<a class="g-item<?php echo esc_attr( $class ); ?> rv" href="<?php echo esc_url( $full[0] ); ?>" data-cats="<?php echo esc_attr( implode( ' ', $it['cats'] ) ); ?>" data-title="<?php echo esc_attr( $it['title'] ); ?>">
				<?php echo wp_get_attachment_image( $it['thumb'], 'ki-card', false, array( 'loading' => 'lazy', 'alt' => $it['title'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<span class="g-cap"><span><?php echo esc_html( $it['title'] ); ?></span><?php ki_the_icon( 'plus', '', 22 ); ?></span>
			</a>
		<?php endforeach; ?>
		<?php if ( ! $items ) : ?><p class="muted">Gallery images are being added. Please check back soon.</p><?php endif; ?>
	</div>
	<div class="lightbox" data-lightbox hidden role="dialog" aria-modal="true" aria-label="Image viewer">
		<button class="lb-close" type="button" aria-label="Close" data-lb-close><?php ki_the_icon( 'x', '', 26 ); ?></button>
		<button class="lb-nav lb-prev" type="button" aria-label="Previous image" data-lb-prev><?php ki_the_icon( 'chevron-left', '', 30 ); ?></button>
		<figure class="lb-figure"><img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" alt=""><figcaption></figcaption></figure>
		<button class="lb-nav lb-next" type="button" aria-label="Next image" data-lb-next><?php ki_the_icon( 'chevron-right', '', 30 ); ?></button>
	</div>
	<?php
	return ob_get_clean();
}

/** [ki_gallery] */
add_shortcode( 'ki_gallery', function () {
	return '<div class="ki-gallery">' . ki_gallery_html() . '</div>';
} );

/** [ki_wa_button] : WhatsApp button, only rendered once a number is set in Settings > KI Packaging. */
add_shortcode( 'ki_wa_button', function ( $atts ) {
	$a  = shortcode_atts( array( 'label' => 'WhatsApp', 'class' => 'btn btn-outline-dark btn-lg' ), $atts, 'ki_wa_button' );
	$wa = ki_wa_url();
	if ( ! $wa ) {
		return '';
	}
	return '<a class="' . esc_attr( $a['class'] ) . '" href="' . esc_url( $wa ) . '" target="_blank" rel="noopener">' . ki_icon( 'whatsapp', '', 20 ) . ' ' . esc_html( $a['label'] ) . '</a>';
} );
