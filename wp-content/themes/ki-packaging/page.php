<?php
get_header();
while ( have_posts() ) :
	the_post();
	ki_page_hero( array( 'title' => get_the_title(), 'image' => 'press-roll' ) );
	?>
	<section class="section">
		<div class="container prose">
			<?php the_content(); ?>
		</div>
	</section>
	<?php
endwhile;
get_template_part( 'template-parts/cta-band' );
get_footer();
