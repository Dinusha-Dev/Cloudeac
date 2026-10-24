<?php
/**
 * Slide-in quotation modal, opened by any [data-quote] element.
 */
if ( ! function_exists( 'ki_quote_form_html' ) ) {
	return;
}
?>
<div class="modal" id="quote-modal" role="dialog" aria-modal="true" aria-labelledby="qm-title" hidden>
	<div class="modal-backdrop" data-modal-close></div>
	<div class="modal-panel">
		<button class="modal-close" type="button" aria-label="Close quotation form" data-modal-close><?php ki_the_icon( 'x', '', 22 ); ?></button>
		<aside class="modal-side">
			<span class="eyebrow eyebrow--light">Free quotation</span>
			<h2 id="qm-title">Tell us what you need.</h2>
			<p>Share your printing or packaging requirement and we will come back with a quotation.</p>
			<ul class="tick-list">
				<li><?php ki_the_icon( 'check', '', 18 ); ?> Offset printing &amp; commercial print</li>
				<li><?php ki_the_icon( 'check', '', 18 ); ?> Cartons, boxes, labels &amp; bags</li>
				<li><?php ki_the_icon( 'check', '', 18 ); ?> Proof before production</li>
				<li><?php ki_the_icon( 'check', '', 18 ); ?> Faster turnaround</li>
			</ul>
			<img class="modal-logo" src="<?php echo esc_url( ki_asset( 'img/icon-light.png' ) ); ?>" alt="" width="64" height="64" loading="lazy">
		</aside>
		<div class="modal-main">
			<?php echo ki_quote_form_html( 'modal' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
	</div>
</div>
