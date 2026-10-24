<?php
/**
 * Closing call-to-action band. $args: title, text.
 */
$a  = wp_parse_args( $args, array(
	'title' => 'Ready to bring your project to life?',
	'text'  => 'Send us your printing or packaging requirement and get a quotation from the K I Packaging team.',
) );
$wa = ki_wa_url();
?>
<section class="cta-band" data-reveal>
	<div class="cta-band-bg" aria-hidden="true"></div>
	<div class="container cta-band-inner">
		<div>
			<span class="eyebrow eyebrow--dark">Get a Quotation</span>
			<h2><?php echo esc_html( $a['title'] ); ?></h2>
			<p><?php echo esc_html( $a['text'] ); ?></p>
		</div>
		<div class="cta-band-actions">
			<a class="btn btn-dark btn-lg" href="<?php echo esc_url( ki_quote_url() ); ?>" data-quote data-magnetic>Request a quotation <?php ki_the_icon( 'arrow-right', 'btn-arrow', 18 ); ?></a>
			<?php if ( $wa ) : ?><a class="btn btn-outline-dark btn-lg" href="<?php echo esc_url( $wa ); ?>" target="_blank" rel="noopener"><?php ki_the_icon( 'whatsapp', '', 20 ); ?> WhatsApp</a><?php endif; ?>
		</div>
	</div>
</section>
