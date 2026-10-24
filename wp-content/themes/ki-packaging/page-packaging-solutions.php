<?php
/**
 * Packaging Solutions.
 */
get_header();
ki_page_hero( array(
	'eyebrow' => 'Custom packaging',
	'title'   => 'Packaging Solutions',
	'lead'    => 'Cartons, boxes, labels and bags designed to protect your product and present your brand.',
	'image'   => 'boxes-stack',
	'crumbs'  => array( array( 'Packaging Solutions', '' ) ),
) );
$range = array(
	array( 'Cartons', 'box-kraft', 'package', 'Printed cartons for retail and consumer products, cut and creased on our die-cutting machine.' ),
	array( 'E & B fluted boxes', 'open-boxes', 'layers', 'Strong corrugated boxes for storage, transport and bulk packing.' ),
	array( 'PVC boxes', 'gift-box', 'shield', 'Clear and rigid presentation boxes for gifts, accessories and premium goods.' ),
	array( 'Labels & stickers', 'stickers', 'tag', 'Product labels and stickers in the shapes, sizes and finishes your range needs.' ),
	array( 'Carry bags', 'paper-bag', 'shopping-bag', 'Branded paper carry bags that turn every purchase into a walking advertisement.' ),
	array( 'Garment accessories', 'hangtag-tee', 'tag', 'Hang tags, price tags, card inserts and collar supports for apparel brands.' ),
);
?>

<section class="section">
	<div class="container split-2">
		<div class="intro-copy" data-reveal>
			<span class="eyebrow">Packaging that works</span>
			<h2 class="h2">Packaging built around your product.</h2>
			<p class="lead">The right pack protects the product, sells the brand and fits the supply chain. We help you get all three.</p>
			<p>From cosmetics and Ayurvedic products to food, home appliances and garments, we design and produce packaging that suits the product and the shelf. Tell us what you are packing and we will recommend the right style, board and finish.</p>
			<ul class="check-list">
				<li><?php ki_the_icon( 'check', '', 20 ); ?> Custom sizes, structures and print</li>
				<li><?php ki_the_icon( 'check', '', 20 ); ?> Die-cut and creased in-house</li>
				<li><?php ki_the_icon( 'check', '', 20 ); ?> Suitable for retail, export and bulk</li>
			</ul>
			<a class="btn btn-primary" href="<?php echo esc_url( ki_quote_url() ); ?>" data-quote>Get a packaging quote <?php ki_the_icon( 'arrow-right', 'btn-arrow', 18 ); ?></a>
		</div>
		<div class="about-media" data-reveal="left">
			<div class="about-img about-img-a" data-reveal="img"><?php echo ki_img( 'boxes-labelled', 'ki-wide' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
			<div class="about-img about-img-b" data-reveal="img" style="--d:.2s"><?php echo ki_img( 'cosmetic-boxes', 'ki-card' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
			<span class="about-ring" aria-hidden="true"></span>
		</div>
	</div>
</section>

<section class="section section--paper">
	<div class="container">
		<div class="section-head center" data-reveal>
			<span class="eyebrow">Our packaging range</span>
			<h2 class="h2">Everything you need to pack and present.</h2>
		</div>
		<div class="range-grid">
			<?php foreach ( $range as $i => $r ) : ?>
				<article class="range-card" data-reveal data-tilt="4" style="--d:<?php echo esc_attr( ( $i % 3 ) * 0.1 ); ?>s">
					<div class="range-media"><?php echo ki_img( $r[1], 'ki-card' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span class="range-ico"><?php ki_the_icon( $r[2], '', 26 ); ?></span></div>
					<div class="range-body">
						<h3><?php echo esc_html( $r[0] ); ?></h3>
						<p><?php echo esc_html( $r[3] ); ?></p>
						<a class="link-arrow" href="<?php echo esc_url( ki_quote_url( $r[0] ) ); ?>" data-quote data-product="<?php echo esc_attr( $r[0] ); ?>">Get a quote <?php ki_the_icon( 'arrow-right', '', 16 ); ?></a>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="section">
	<div class="container split-2 split-2--rev">
		<div class="gar-media" data-reveal="left">
			<div class="gar-img gar-img-a" data-reveal="img"><?php echo ki_img( 'hangtag-textile', 'ki-card' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
			<div class="gar-img gar-img-b" data-reveal="img" style="--d:.15s"><?php echo ki_img( 'tag-dark', 'ki-card' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
		</div>
		<div class="intro-copy" data-reveal>
			<span class="eyebrow">For the garment industry</span>
			<h2 class="h2">Finishing touches for apparel brands.</h2>
			<p class="lead">Tags, inserts and supports that make a garment look finished and ready for the shelf.</p>
			<ul class="tick-grid">
				<li><?php ki_the_icon( 'check-circle', '', 20 ); ?> Card inserts</li>
				<li><?php ki_the_icon( 'check-circle', '', 20 ); ?> Hang tags</li>
				<li><?php ki_the_icon( 'check-circle', '', 20 ); ?> Price tags &amp; stickers</li>
				<li><?php ki_the_icon( 'check-circle', '', 20 ); ?> Back &amp; support boards</li>
				<li><?php ki_the_icon( 'check-circle', '', 20 ); ?> Collar back support</li>
				<li><?php ki_the_icon( 'check-circle', '', 20 ); ?> Product catalogues</li>
				<li><?php ki_the_icon( 'check-circle', '', 20 ); ?> PVC boxes</li>
			</ul>
		</div>
	</div>
</section>

<section class="section section--dark">
	<div class="glow glow-a" aria-hidden="true"></div>
	<div class="container">
		<div class="section-head center" data-reveal>
			<span class="eyebrow eyebrow--light">Industries we pack for</span>
			<h2 class="h2">Packaging for the products that matter.</h2>
		</div>
		<div class="industry-grid industry-grid--dark">
			<?php $n = 0; foreach ( ki_industries() as $label => $icon ) : ?>
				<div class="industry" data-reveal style="--d:<?php echo esc_attr( $n++ * 0.06 ); ?>s">
					<span class="industry-ico"><?php ki_the_icon( $icon, '', 30 ); ?></span>
					<span class="industry-name"><?php echo esc_html( $label ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php
get_template_part( 'template-parts/cta-band', null, array( 'title' => 'Need packaging made for your product?', 'text' => 'Share the product, size and quantity and our team will recommend the right pack and send a quotation.' ) );
get_footer();
