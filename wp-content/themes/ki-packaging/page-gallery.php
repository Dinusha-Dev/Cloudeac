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
?>

<section class="section section--first">
	<div class="container">
		<?php echo ki_gallery_html(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	</div>
</section>

<?php
get_template_part( 'template-parts/cta-band', null, array( 'title' => 'Like what you see?', 'text' => 'Let us produce your next print or packaging project to the same standard.' ) );
get_footer();
