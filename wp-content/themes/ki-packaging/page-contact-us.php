<?php
/**
 * Contact Us.
 */
get_header();
ki_main_open();
ki_page_hero( array(
	'eyebrow' => 'Get in touch',
	'title'   => 'Contact Us',
	'lead'    => 'Tell us about your printing or packaging requirement and we will come back with a quotation.',
	'image'   => 'press-vintage',
	'crumbs'  => array( array( 'Contact Us', '' ) ),
) );
$phone  = ki_opt( 'phone' );
$phone2 = ki_opt( 'phone2' );
$email  = ki_opt( 'email' );
$hours  = ki_opt( 'hours' );
$wa     = ki_wa_url();
$socials = function_exists( 'ki_socials' ) ? ki_socials() : array();
$map    = ki_opt( 'map_query' );
?>

<section class="section section--first">
	<div class="container contact-grid">
		<aside class="contact-info" data-reveal="left">
			<span class="eyebrow">Contact details</span>
			<h2 class="h2">We would love to hear from you.</h2>
			<p class="lead">Visit our Kadawatha facility, call us, or send a quotation request online.</p>

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

			<div class="contact-next" data-reveal>
				<div class="contact-next-media"><?php echo ki_img( 'press-roll', 'ki-wide' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
				<div class="contact-next-body">
					<h3>What happens next?</h3>
					<ol>
						<li><span>1</span> We review your requirement and artwork.</li>
						<li><span>2</span> We send you a quotation and a proof.</li>
						<li><span>3</span> You approve, and we print and deliver.</li>
					</ol>
				</div>
			</div>

			<?php if ( $socials ) : ?>
				<ul class="social-list social-list--solid">
					<?php foreach ( $socials as $key => $s ) : ?>
						<li><a href="<?php echo esc_url( $s['url'] ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( $s['label'] ); ?>"><?php ki_the_icon( $key, '', 20 ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</aside>

		<div class="quote-card" id="quote" data-reveal>
			<div class="quote-card-head">
				<span class="eyebrow">Get a Quotation</span>
				<h2 class="h3">Request your quotation</h2>
				<p>Fill in the details below. The more you tell us, the faster we can quote.</p>
			</div>
			<?php echo function_exists( 'ki_quote_form_html' ) ? ki_quote_form_html( 'contact' ) : '<p>The quotation form is temporarily unavailable.</p>'; // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
	</div>
</section>

<?php if ( $map ) : ?>
<section class="map-section" data-reveal>
	<iframe title="Map showing the location of K I Packaging Solutions in Kadawatha" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
		src="<?php echo esc_url( 'https://maps.google.com/maps?q=' . rawurlencode( $map ) . '&t=&z=15&ie=UTF8&iwloc=&output=embed' ); ?>"></iframe>
</section>
<?php endif; ?>

<?php
ki_main_close();
get_footer();
