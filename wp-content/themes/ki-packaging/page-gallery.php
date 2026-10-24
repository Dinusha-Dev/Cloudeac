<?php
/**
 * Gallery.
 */
get_header();
ki_page_hero( array(
	'eyebrow' => 'Our work & production floor',
	'title'   => 'Gallery',
	'lead'    => 'A look at the machines, products and packaging that come out of K I Packaging Solutions.',
	'image'   => 'pantone',
	'crumbs'  => array( array( 'Gallery', '' ) ),
) );
$items = function_exists( 'ki_gallery_items' ) ? ki_gallery_items() : array();
$cats  = array();
foreach ( $items as $it ) {
	foreach ( $it['cats'] as $i => $slug ) {
		$cats[ $slug ] = $it['names'][ $i ];
	}
}
ksort( $cats );
?>

<section class="section section--first">
	<div class="container">
		<?php if ( $cats ) : ?>
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
				<a class="g-item<?php echo esc_attr( $class ); ?>" href="<?php echo esc_url( $full[0] ); ?>" data-cats="<?php echo esc_attr( implode( ' ', $it['cats'] ) ); ?>" data-title="<?php echo esc_attr( $it['title'] ); ?>" data-reveal>
					<?php echo wp_get_attachment_image( $it['thumb'], 'ki-card', false, array( 'loading' => 'lazy', 'alt' => $it['title'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<span class="g-cap"><span><?php echo esc_html( $it['title'] ); ?></span><?php ki_the_icon( 'plus', '', 22 ); ?></span>
				</a>
			<?php endforeach; ?>
			<?php if ( ! $items ) : ?><p class="muted">Gallery images are being added. Please check back soon.</p><?php endif; ?>
		</div>
	</div>
</section>

<div class="lightbox" data-lightbox hidden role="dialog" aria-modal="true" aria-label="Image viewer">
	<button class="lb-close" type="button" aria-label="Close" data-lb-close><?php ki_the_icon( 'x', '', 26 ); ?></button>
	<button class="lb-nav lb-prev" type="button" aria-label="Previous image" data-lb-prev><?php ki_the_icon( 'chevron-left', '', 30 ); ?></button>
	<figure class="lb-figure"><img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" alt=""><figcaption></figcaption></figure>
	<button class="lb-nav lb-next" type="button" aria-label="Next image" data-lb-next><?php ki_the_icon( 'chevron-right', '', 30 ); ?></button>
</div>

<?php
get_template_part( 'template-parts/cta-band', null, array( 'title' => 'Like what you see?', 'text' => 'Let us produce your next print or packaging project to the same standard.' ) );
get_footer();
