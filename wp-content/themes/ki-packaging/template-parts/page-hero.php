<?php
/**
 * Inner page banner. $args: eyebrow, title, lead, image, crumbs.
 */
$a = wp_parse_args( $args, array( 'eyebrow' => '', 'title' => '', 'lead' => '', 'image' => 'press-roll', 'crumbs' => array() ) );
?>
<section class="page-hero">
	<div class="page-hero-bg" data-parallax="0.25"><?php echo ki_img( $a['image'], 'ki-hero', array( 'loading' => 'eager', 'fetchpriority' => 'high' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
	<div class="page-hero-shade"></div>
	<span class="shape shape-a" aria-hidden="true"></span>
	<span class="shape shape-b" aria-hidden="true"></span>
	<div class="container page-hero-inner">
		<?php ki_breadcrumbs( $a['crumbs'] ? $a['crumbs'] : array( array( $a['title'], '' ) ) ); ?>
		<?php if ( $a['eyebrow'] ) : ?><span class="eyebrow eyebrow--light"><?php echo esc_html( $a['eyebrow'] ); ?></span><?php endif; ?>
		<h1 class="page-title split"><?php echo ki_split_words( $a['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></h1>
		<?php if ( $a['lead'] ) : ?><p class="page-lead"><?php echo esc_html( $a['lead'] ); ?></p><?php endif; ?>
	</div>
</section>
