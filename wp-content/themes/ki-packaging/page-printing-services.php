<?php
/**
 * Printing Services.
 */
get_header();
ki_page_hero( array(
	'eyebrow' => 'Offset & commercial printing',
	'title'   => 'Printing Services',
	'lead'    => 'From corporate stationery to catalogues and books, printed sharply and finished to your specification.',
	'image'   => 'press-detail',
	'crumbs'  => array( array( 'Printing Services', '' ) ),
) );
$groups = array(
	array( 'Corporate stationery', 'file-text', array( 'Corporate stationers', 'Folders', 'File folders', 'Menu cards', 'Greeting cards' ), 'envelope-kraft' ),
	array( 'Brochures & leaflets', 'layers', array( 'Brochures', 'Leaflets', 'Pamphlets', 'Flyers', 'Literatures' ), 'brochure-display' ),
	array( 'Catalogues & books', 'book', array( 'Catalogues', 'Product catalogues', 'Books', 'Annual reports', 'Newsletters' ), 'books-stack' ),
	array( 'Posters & advertising', 'image', array( 'Posters', 'Streamers', 'Danglers', 'Wobblers', 'POP items', 'Visual ads' ), 'posters-wall' ),
	array( 'Labels & stickers', 'tag', array( 'Stickers', 'Labels', 'Price tags' ), 'stickers' ),
	array( 'Calendars & gifts', 'calendar', array( 'Calendars', 'Greeting cards', 'Carry bags' ), 'calendar-desk' ),
);
?>

<section class="section">
	<div class="container split-2">
		<div class="about-media about-media--single" data-reveal="left">
			<div class="about-img about-img-a" data-reveal="img"><?php echo ki_img( 'machine-sorkz', 'large' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
			<div class="about-img about-img-b" data-reveal="img" style="--d:.2s"><?php echo ki_img( 'pantone', 'ki-card' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
		</div>
		<div class="intro-copy" data-reveal>
			<span class="eyebrow">Printing done right</span>
			<h2 class="h2">Sharp colour, clean finishing, dependable delivery.</h2>
			<p class="lead">Our offset printing is run on Heidelberg presses and finished in-house on precise cutting and die-cutting machines.</p>
			<p>Whether it is a single brochure or a full corporate identity set, we pair experienced operators with modern equipment so your colours, trims and folds come out exactly as approved.</p>
			<ul class="check-list">
				<li><?php ki_the_icon( 'check', '', 20 ); ?> Offset &amp; commercial printing</li>
				<li><?php ki_the_icon( 'check', '', 20 ); ?> Programmable guillotine for exact trims</li>
				<li><?php ki_the_icon( 'check', '', 20 ); ?> In-house die cutting</li>
				<li><?php ki_the_icon( 'check', '', 20 ); ?> Digital library of logos and products</li>
			</ul>
		</div>
	</div>
</section>

<section class="section section--paper">
	<div class="container">
		<div class="section-head center" data-reveal>
			<span class="eyebrow">What we print</span>
			<h2 class="h2">A complete range of printed products.</h2>
		</div>
		<div class="range-grid">
			<?php foreach ( $groups as $i => $g ) : ?>
				<article class="range-card" data-reveal data-tilt="4" style="--d:<?php echo esc_attr( ( $i % 3 ) * 0.1 ); ?>s">
					<div class="range-media"><?php echo ki_img( $g[3], 'ki-card' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span class="range-ico"><?php ki_the_icon( $g[1], '', 26 ); ?></span></div>
					<div class="range-body">
						<h3><?php echo esc_html( $g[0] ); ?></h3>
						<ul class="dot-list">
							<?php foreach ( $g[2] as $item ) : ?><li><?php echo esc_html( $item ); ?></li><?php endforeach; ?>
						</ul>
						<a class="link-arrow" href="<?php echo esc_url( ki_quote_url( $g[0] ) ); ?>" data-quote data-product="<?php echo esc_attr( $g[0] ); ?>">Get a quote <?php ki_the_icon( 'arrow-right', '', 16 ); ?></a>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="section section--dark">
	<div class="glow glow-b" aria-hidden="true"></div>
	<div class="container">
		<div class="section-head center" data-reveal>
			<span class="eyebrow eyebrow--light">Our process</span>
			<h2 class="h2">A simple path from idea to print.</h2>
		</div>
		<ol class="process process--dark">
			<li class="step" data-reveal><span class="step-no">01</span><h3>Brief</h3><p>Share your artwork, size, stock and quantity.</p></li>
			<li class="step" data-reveal style="--d:.12s"><span class="step-no">02</span><h3>Proof</h3><p>Approve the proof before anything goes on press.</p></li>
			<li class="step" data-reveal style="--d:.24s"><span class="step-no">03</span><h3>Print</h3><p>Offset printing with quality checks on the run.</p></li>
			<li class="step" data-reveal style="--d:.36s"><span class="step-no">04</span><h3>Finish</h3><p>Cutting, die cutting and packing, ready to deliver.</p></li>
		</ol>
	</div>
</section>

<section class="section">
	<div class="container">
		<div class="section-head center" data-reveal>
			<span class="eyebrow">Industries</span>
			<h2 class="h2">Print for every sector.</h2>
			<p class="lead">IT, banking and finance, electronics, builders, automotive, telecom, education, media and product packaging all rely on us.</p>
		</div>
		<ul class="chip-row chip-row--center" data-reveal>
			<?php foreach ( ki_sectors() as $s ) : ?><li><?php echo esc_html( $s ); ?></li><?php endforeach; ?>
		</ul>
	</div>
</section>

<?php
get_template_part( 'template-parts/cta-band', null, array( 'title' => 'Have a printing job in mind?', 'text' => 'Tell us the product, size and quantity and we will send you a quotation.' ) );
get_footer();
