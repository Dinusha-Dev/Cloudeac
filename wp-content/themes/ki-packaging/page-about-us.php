<?php
/**
 * About Us.
 */
get_header();
ki_main_open();
ki_page_hero( array(
	'eyebrow' => 'About K I Packaging Solutions',
	'title'   => 'About Us',
	'lead'    => 'High-quality, cutting-edge printing and packaging, built on commitment, dedication and customer satisfaction.',
	'image'   => 'paper-stack',
	'crumbs'  => array( array( 'About Us', '' ) ),
) );
$sectors = array(
	'IT Companies' => 'laptop', 'Banking & Finance' => 'bank', 'Consumer Electronics' => 'tv', 'Builders' => 'building',
	'Automotive' => 'car', 'Telecom' => 'signal', 'Educational Institutes' => 'school', 'Media & Entertainment' => 'film', 'Product Packaging' => 'package',
);
?>

<section class="section">
	<div class="container split-2">
		<div class="intro-copy" data-reveal>
			<span class="eyebrow">Our story</span>
			<h2 class="h2">Welcome to K I Packaging Solutions.</h2>
			<p class="lead">We offer high-quality and cutting-edge printing services to our customers. Commitment, dedication and customer satisfaction are the key components in our philosophy.</p>
			<p>We are a collective group of people trying to make a difference in this world. We provide solutions to potential or existing practical problems which may be encountered during the job, and we maintain an extensive digital library of logos and products.</p>
			<p>It is this approach that has won us the satisfaction and trust of our customers, from first-time clients to long-standing business partners.</p>
			<a class="btn btn-primary" href="<?php echo esc_url( ki_quote_url() ); ?>" data-quote>Request a quotation <?php ki_the_icon( 'arrow-right', 'btn-arrow', 18 ); ?></a>
		</div>
		<div class="about-media" data-reveal="left">
			<div class="about-img about-img-a" data-reveal="img"><?php echo ki_img( 'press-vintage', 'ki-wide' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
			<div class="about-img about-img-b" data-reveal="img" style="--d:.2s"><?php echo ki_img( 'press-rollers', 'ki-card' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
			<span class="about-ring" aria-hidden="true"></span>
		</div>
	</div>
</section>

<section class="section section--dark mv-section">
	<div class="glow glow-a" aria-hidden="true"></div>
	<div class="container mv-grid">
		<article class="mv-card" data-reveal>
			<span class="mv-ico"><?php ki_the_icon( 'target', '', 32 ); ?></span>
			<h2>Our Mission</h2>
			<p>Our mission is to provide exceptional service and to follow through on our promises. We will strive to deliver individualised solutions to all our clients&rsquo; printing needs and add value to our clients&rsquo; businesses.</p>
			<p>The K I Packaging Solutions team is focused on providing great service and forming lasting relationships with our clients. We will be judged not only by our words but by our actions.</p>
		</article>
		<article class="mv-card" data-reveal style="--d:.15s">
			<span class="mv-ico"><?php ki_the_icon( 'eye', '', 32 ); ?></span>
			<h2>Our Vision</h2>
			<p>K I Packaging Solutions endeavours to become recognised as the benchmark of outstanding service, not just in the printing industry but across all industries.</p>
			<p>We pride ourselves on bringing results that count in everything that we set out to do, and we are constantly striving for successful outcomes in all areas of our business.</p>
		</article>
	</div>
</section>

<section class="section">
	<div class="container">
		<div class="section-head center" data-reveal>
			<span class="eyebrow">Our values</span>
			<h2 class="h2">What we stand for.</h2>
		</div>
		<div class="values-grid values-grid--light">
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

<section class="section section--paper">
	<div class="container">
		<div class="section-head center" data-reveal>
			<span class="eyebrow">Our production floor</span>
			<h2 class="h2">The machines behind the quality.</h2>
			<p class="lead">Offset presses, a programmable guillotine and a die-cutting machine let us take a job from sheet to finished product in-house.</p>
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

<section class="section">
	<div class="container">
		<div class="section-head center" data-reveal>
			<span class="eyebrow">We provide print services to</span>
			<h2 class="h2">Sectors we work with.</h2>
		</div>
		<div class="sector-grid">
			<?php $n = 0; foreach ( $sectors as $label => $icon ) : ?>
				<div class="sector" data-reveal style="--d:<?php echo esc_attr( $n++ * 0.05 ); ?>s">
					<?php ki_the_icon( $icon, '', 26 ); ?><span><?php echo esc_html( $label ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php
get_template_part( 'template-parts/cta-band' );
ki_main_close();
get_footer();
