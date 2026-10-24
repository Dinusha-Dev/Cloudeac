<?php
/**
 * Home page.
 */
get_header();
ki_main_open();

$slides     = array( 'press-detail', 'press-roll', 'open-boxes' );
$industries = ki_industries();
$marquee    = array_merge( array_keys( $industries ), ki_sectors() );
?>

<section class="hero" data-hero>
	<div class="hero-slides" aria-hidden="true">
		<?php foreach ( $slides as $i => $key ) : ?>
			<div class="hero-slide<?php echo 0 === $i ? ' is-active' : ''; ?>">
				<?php echo ki_img( $key, 'ki-hero', 0 === $i ? array( 'loading' => 'eager', 'fetchpriority' => 'high' ) : array() ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</div>
		<?php endforeach; ?>
	</div>
	<div class="hero-shade"></div>
	<div class="hero-grid-lines" aria-hidden="true"></div>
	<span class="shape shape-a" aria-hidden="true"></span>
	<span class="shape shape-b" aria-hidden="true"></span>

	<div class="container hero-inner">
		<div class="hero-content">
			<span class="eyebrow eyebrow--light hero-eyebrow">Offset printing &middot; Packaging solutions</span>
			<h1 class="hero-title split">
				<?php echo ki_split_words( 'Print that impresses.' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><br>
				<span class="accent"><?php echo ki_split_words( 'Packaging that protects.', 3 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
			</h1>
			<p class="hero-lead">K I Packaging Solutions delivers high-quality, cutting-edge printing and custom packaging from Kadawatha, with the commitment and faster turnaround your business deserves.</p>
			<div class="hero-actions">
				<a class="btn btn-primary btn-lg" href="<?php echo esc_url( ki_quote_url() ); ?>" data-quote data-magnetic>Get a Quotation <?php ki_the_icon( 'arrow-right', 'btn-arrow', 18 ); ?></a>
				<a class="btn btn-ghost btn-lg" href="<?php echo esc_url( ki_page_url( 'printing-services' ) ); ?>">Explore services</a>
			</div>
			<ul class="hero-points">
				<li><?php ki_the_icon( 'check-circle', '', 20 ); ?> Outstanding quality</li>
				<li><?php ki_the_icon( 'check-circle', '', 20 ); ?> Latest technologies</li>
				<li><?php ki_the_icon( 'check-circle', '', 20 ); ?> Faster turnaround</li>
			</ul>
		</div>

		<div class="hero-visual" aria-hidden="true">
			<div class="hv-card hv-main" data-tilt="6"><?php echo ki_img( 'hero-machine', 'large', array( 'loading' => 'eager' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
			<div class="hv-card hv-sub"><?php echo ki_img( 'box-kraft', 'ki-card', array( 'loading' => 'eager' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
			<div class="hv-chip hv-chip-a"><?php ki_the_icon( 'printer', '', 20 ); ?><span>Offset printing</span></div>
			<div class="hv-chip hv-chip-b"><?php ki_the_icon( 'package', '', 20 ); ?><span>Custom packaging</span></div>
			<svg class="hv-badge" viewBox="0 0 120 120" width="120" height="120">
				<defs><path id="hvcircle" d="M60,60 m-44,0 a44,44 0 1,1 88,0 a44,44 0 1,1 -88,0"/></defs>
				<text><textPath href="#hvcircle" textLength="268" lengthAdjust="spacing">PRINT &#8226; PACK &#8226; DELIVER &#8226;</textPath></text>
			</svg>
		</div>
	</div>

	<a class="scroll-cue" href="#intro" aria-label="Scroll to content"><span></span></a>
</section>

<div class="marquee" aria-label="Industries we serve">
	<div class="marquee-track">
		<?php for ( $r = 0; $r < 2; $r++ ) : ?>
			<ul<?php echo $r ? ' aria-hidden="true"' : ''; ?>>
				<?php foreach ( $marquee as $m ) : ?><li><?php ki_the_icon( 'star', '', 14 ); ?><?php echo esc_html( $m ); ?></li><?php endforeach; ?>
			</ul>
		<?php endfor; ?>
	</div>
</div>

<section class="section" id="intro">
	<div class="container split-2">
		<div class="intro-media" data-reveal="left">
			<div class="intro-img intro-img-a" data-reveal="img"><?php echo ki_img( 'press-rollers', 'large' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
			<div class="intro-img intro-img-b" data-reveal="img" style="--d:.2s"><?php echo ki_img( 'machine-sorkz', 'ki-card' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
			<div class="intro-badge"><strong data-count="17" data-suffix="+">17+</strong><span>industries served</span></div>
		</div>
		<div class="intro-copy" data-reveal>
			<span class="eyebrow">Who we are</span>
			<h2 class="h2">A collective group of people trying to make a difference in print.</h2>
			<p class="lead">Welcome to K I Packaging Solutions. We offer high-quality, cutting-edge printing services to our customers. Commitment, dedication and customer satisfaction are the key components of our philosophy.</p>
			<p>We provide solutions to the practical problems that can come up during a job, and we maintain an extensive digital library of logos and products. It is how we have won the satisfaction and trust of our customers.</p>
			<ul class="check-list">
				<li><?php ki_the_icon( 'check', '', 20 ); ?> Individualised solutions for every client</li>
				<li><?php ki_the_icon( 'check', '', 20 ); ?> Extensive digital library of logos and products</li>
				<li><?php ki_the_icon( 'check', '', 20 ); ?> Results that count, job after job</li>
			</ul>
			<a class="btn btn-dark" href="<?php echo esc_url( ki_page_url( 'about-us' ) ); ?>">More about us <?php ki_the_icon( 'arrow-right', 'btn-arrow', 18 ); ?></a>
		</div>
	</div>
</section>

<section class="stats">
	<div class="container stats-grid">
		<div class="stat" data-reveal><strong data-count="17" data-suffix="+">17+</strong><span>Industries served</span></div>
		<div class="stat" data-reveal style="--d:.1s"><strong data-count="30" data-suffix="+">30+</strong><span>Print &amp; packaging products</span></div>
		<div class="stat" data-reveal style="--d:.2s"><strong data-count="4">4</strong><span>Core values we work by</span></div>
	</div>
</section>

<section class="section section--paper">
	<div class="container">
		<div class="section-head center" data-reveal>
			<span class="eyebrow">What we do</span>
			<h2 class="h2">Two disciplines. One dependable partner.</h2>
			<p class="lead">From a single brochure to thousands of cartons, we handle printing and packaging under one roof.</p>
		</div>
		<div class="svc-duo">
			<article class="svc-card" data-reveal data-tilt="3">
				<div class="svc-media"><?php echo ki_img( 'brochure-display', 'ki-wide' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
				<div class="svc-body">
					<span class="svc-ico"><?php ki_the_icon( 'printer', '', 28 ); ?></span>
					<h3>Printing Services</h3>
					<p>Offset and commercial printing for corporate stationery, brochures, catalogues, books, posters, calendars and more, produced on our Heidelberg presses.</p>
					<ul class="chip-row"><li>Stationery</li><li>Brochures</li><li>Catalogues</li><li>Books</li><li>Posters</li></ul>
					<a class="link-arrow" href="<?php echo esc_url( ki_page_url( 'printing-services' ) ); ?>">View printing services <?php ki_the_icon( 'arrow-right', '', 18 ); ?></a>
				</div>
			</article>
			<article class="svc-card" data-reveal data-tilt="3" style="--d:.15s">
				<div class="svc-media"><?php echo ki_img( 'boxes-labelled', 'ki-wide' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
				<div class="svc-body">
					<span class="svc-ico"><?php ki_the_icon( 'package', '', 28 ); ?></span>
					<h3>Packaging Solutions</h3>
					<p>Cartons, E &amp; B fluted boxes, PVC boxes, labels, stickers, carry bags and garment accessories made to fit your product and your brand.</p>
					<ul class="chip-row"><li>Cartons</li><li>Fluted boxes</li><li>Labels</li><li>Carry bags</li><li>Hang tags</li></ul>
					<a class="link-arrow" href="<?php echo esc_url( ki_page_url( 'packaging-solutions' ) ); ?>">View packaging solutions <?php ki_the_icon( 'arrow-right', '', 18 ); ?></a>
				</div>
			</article>
		</div>
	</div>
</section>

<?php
$featured = array();
if ( function_exists( 'wc_get_products' ) ) {
	$featured = wc_get_products( array( 'limit' => 8, 'status' => 'publish', 'featured' => true, 'orderby' => 'menu_order', 'order' => 'ASC' ) );
	if ( count( $featured ) < 4 ) {
		$featured = wc_get_products( array( 'limit' => 8, 'status' => 'publish', 'orderby' => 'date', 'order' => 'DESC' ) );
	}
}
if ( $featured ) :
	?>
<section class="section">
	<div class="container">
		<div class="section-head split-head" data-reveal>
			<div>
				<span class="eyebrow">Our products</span>
				<h2 class="h2">Made to order, made to impress.</h2>
			</div>
			<a class="btn btn-outline" href="<?php echo esc_url( ki_shop_url() ); ?>">View all products <?php ki_the_icon( 'arrow-right', 'btn-arrow', 18 ); ?></a>
		</div>
		<div class="pgrid" data-stagger>
			<?php foreach ( $featured as $p ) : ?>
				<div class="pgrid-item" data-reveal><?php ki_product_card( $p ); ?></div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<section class="section section--dark values-section">
	<div class="glow glow-a" aria-hidden="true"></div>
	<div class="container">
		<div class="section-head center" data-reveal>
			<span class="eyebrow eyebrow--light">Why K I Packaging</span>
			<h2 class="h2">Four values behind every job.</h2>
			<p class="lead">We will be judged not only by our words but by our actions.</p>
		</div>
		<div class="values-grid">
			<?php foreach ( ki_values() as $i => $v ) : ?>
				<article class="value-card" data-reveal style="--d:<?php echo esc_attr( $i * 0.1 ); ?>s">
					<span class="value-num"><?php echo esc_html( str_pad( $i + 1, 2, '0', STR_PAD_LEFT ) ); ?></span>
					<span class="value-ico"><?php ki_the_icon( $v[1], '', 30 ); ?></span>
					<h3><?php echo esc_html( $v[0] ); ?></h3>
					<p><?php echo esc_html( $v[2] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="section">
	<div class="container">
		<div class="section-head center" data-reveal>
			<span class="eyebrow">Our production floor</span>
			<h2 class="h2">Serious machinery behind every print.</h2>
			<p class="lead">Precision presses, cutters and die-cutting machines keep quality consistent from the first sheet to the last.</p>
		</div>
		<div class="machines">
			<?php foreach ( ki_machines() as $i => $m ) : ?>
				<article class="machine" data-reveal style="--d:<?php echo esc_attr( $i * 0.08 ); ?>s">
					<div class="machine-media"><?php echo ki_img( $m[0], 'large' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
					<div class="machine-body"><h3><?php echo esc_html( $m[1] ); ?></h3><p><?php echo esc_html( $m[2] ); ?></p></div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="section section--paper">
	<div class="container">
		<div class="section-head center" data-reveal>
			<span class="eyebrow">Industries</span>
			<h2 class="h2">Trusted across many industries.</h2>
			<p class="lead">Packaging and print for hospitals, hotels, manufacturers and brands of every size.</p>
		</div>
		<div class="industry-grid">
			<?php $n = 0; foreach ( $industries as $label => $icon ) : ?>
				<div class="industry" data-reveal style="--d:<?php echo esc_attr( $n++ * 0.06 ); ?>s">
					<span class="industry-ico"><?php ki_the_icon( $icon, '', 30 ); ?></span>
					<span class="industry-name"><?php echo esc_html( $label ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>
		<ul class="chip-row chip-row--center" data-reveal>
			<?php foreach ( ki_sectors() as $s ) : ?><li><?php echo esc_html( $s ); ?></li><?php endforeach; ?>
		</ul>
	</div>
</section>

<section class="section">
	<div class="container">
		<div class="section-head center" data-reveal>
			<span class="eyebrow">How it works</span>
			<h2 class="h2">From brief to delivery in four steps.</h2>
		</div>
		<ol class="process">
			<li class="step" data-reveal><span class="step-no">01</span><h3>Share your brief</h3><p>Tell us what you need, the quantity and your timeline through our quotation form.</p></li>
			<li class="step" data-reveal style="--d:.12s"><span class="step-no">02</span><h3>Quotation &amp; proof</h3><p>We confirm the details, send your quotation and prepare a proof for approval.</p></li>
			<li class="step" data-reveal style="--d:.24s"><span class="step-no">03</span><h3>Print &amp; produce</h3><p>Your job runs on our offset presses and finishing machines with quality checks throughout.</p></li>
			<li class="step" data-reveal style="--d:.36s"><span class="step-no">04</span><h3>Finish &amp; deliver</h3><p>Cut, folded, packed and delivered, ready to launch your product or campaign.</p></li>
		</ol>
	</div>
</section>

<?php
get_template_part( 'template-parts/cta-band' );
ki_main_close();
get_footer();
