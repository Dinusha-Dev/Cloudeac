<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Choices for the "What do you need?" field. Uses WooCommerce categories when available. */
function ki_quote_types() {
	$types = array();
	if ( taxonomy_exists( 'product_cat' ) ) {
		$terms = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => false, 'exclude' => array( (int) get_option( 'default_product_cat' ) ) ) );
		if ( ! is_wp_error( $terms ) ) {
			foreach ( $terms as $t ) {
				$types[] = $t->name;
			}
		}
	}
	if ( ! $types ) {
		$types = array( 'Offset / commercial printing', 'Cartons & boxes', 'Labels & stickers', 'Carry bags', 'Garment accessories' );
	}
	$types[] = 'Something else / not sure';
	return $types;
}

/**
 * Quotation form markup. $ctx makes element IDs unique when the form appears twice on a page.
 */
function ki_quote_form_html( $ctx = 'page' ) {
	$id = 'kiq-' . sanitize_html_class( $ctx );
	ob_start();
	?>
	<form class="kiq-form" id="<?php echo esc_attr( $id ); ?>" method="post" enctype="multipart/form-data" novalidate data-kiq>
		<input type="hidden" name="action" value="ki_submit_quote">
		<input type="hidden" name="ki_nonce" value="<?php echo esc_attr( wp_create_nonce( 'ki_quote' ) ); ?>">
		<input type="hidden" name="ki_token" value="<?php echo esc_attr( ki_quote_token() ); ?>">
		<div class="kiq-hp" aria-hidden="true"><label>Website <input type="text" name="ki_website" tabindex="-1" autocomplete="off"></label></div>

		<div class="kiq-grid">
			<div class="kiq-field">
				<label for="<?php echo esc_attr( $id ); ?>-name">Full name <span>*</span></label>
				<input type="text" id="<?php echo esc_attr( $id ); ?>-name" name="name" autocomplete="name" required>
			</div>
			<div class="kiq-field">
				<label for="<?php echo esc_attr( $id ); ?>-company">Company</label>
				<input type="text" id="<?php echo esc_attr( $id ); ?>-company" name="company" autocomplete="organization">
			</div>
			<div class="kiq-field">
				<label for="<?php echo esc_attr( $id ); ?>-email">Email <span>*</span></label>
				<input type="email" id="<?php echo esc_attr( $id ); ?>-email" name="email" autocomplete="email" required>
			</div>
			<div class="kiq-field">
				<label for="<?php echo esc_attr( $id ); ?>-phone">Phone / WhatsApp <span>*</span></label>
				<input type="tel" id="<?php echo esc_attr( $id ); ?>-phone" name="phone" autocomplete="tel" required>
			</div>
			<div class="kiq-field">
				<label for="<?php echo esc_attr( $id ); ?>-type">What do you need? <span>*</span></label>
				<select id="<?php echo esc_attr( $id ); ?>-type" name="type" required>
					<option value="">Select a category</option>
					<?php foreach ( ki_quote_types() as $t ) : ?>
						<option value="<?php echo esc_attr( $t ); ?>"><?php echo esc_html( $t ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="kiq-field">
				<label for="<?php echo esc_attr( $id ); ?>-product">Product / item</label>
				<input type="text" id="<?php echo esc_attr( $id ); ?>-product" name="product" placeholder="e.g. Brochures, hang tags, cartons">
			</div>
			<div class="kiq-field">
				<label for="<?php echo esc_attr( $id ); ?>-qty">Quantity <span>*</span></label>
				<input type="text" id="<?php echo esc_attr( $id ); ?>-qty" name="quantity" placeholder="e.g. 5,000 pcs" required>
			</div>
			<div class="kiq-field">
				<label for="<?php echo esc_attr( $id ); ?>-needed">Needed by</label>
				<input type="date" id="<?php echo esc_attr( $id ); ?>-needed" name="needed" min="<?php echo esc_attr( gmdate( 'Y-m-d' ) ); ?>">
			</div>
			<div class="kiq-field kiq-full">
				<label for="<?php echo esc_attr( $id ); ?>-size">Size, paper / material, colours</label>
				<input type="text" id="<?php echo esc_attr( $id ); ?>-size" name="size" placeholder="e.g. A4, 150gsm art paper, full colour both sides">
			</div>
			<div class="kiq-field kiq-full">
				<label for="<?php echo esc_attr( $id ); ?>-message">Project details</label>
				<textarea id="<?php echo esc_attr( $id ); ?>-message" name="message" rows="4" placeholder="Tell us more about your requirement"></textarea>
			</div>
			<div class="kiq-field kiq-full kiq-file">
				<label for="<?php echo esc_attr( $id ); ?>-artwork">Attach artwork or reference (optional)</label>
				<input type="file" id="<?php echo esc_attr( $id ); ?>-artwork" name="artwork" accept=".pdf,.jpg,.jpeg,.png,.webp">
				<small>PDF, JPG or PNG, up to 8 MB</small>
			</div>
		</div>

		<div class="kiq-status" role="status" aria-live="polite"></div>
		<button type="submit" class="btn btn-primary btn-lg kiq-submit">
			<span class="btn-label">Send quotation request</span>
			<svg class="btn-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
		</button>
		<p class="kiq-note">We reply with a quotation as soon as we have reviewed your requirement.</p>
	</form>
	<?php
	return ob_get_clean();
}

add_shortcode( 'ki_quote_form', function ( $atts ) {
	$atts = shortcode_atts( array( 'id' => 'sc' ), $atts );
	return ki_quote_form_html( $atts['id'] );
} );

add_action( 'wp_enqueue_scripts', function () {
	wp_register_script( 'ki-quote', KI_CORE_URL . 'assets/quote.js', array(), KI_CORE_VERSION, true );
	wp_localize_script( 'ki-quote', 'KIQ', array(
		'ajax'     => admin_url( 'admin-ajax.php' ),
		'whatsapp' => ki_whatsapp_url( 'Hello, I just sent a quotation request from your website.' ),
	) );
	wp_enqueue_script( 'ki-quote' );
} );
