<?php
get_header();
while ( have_posts() ) :
	the_post();
	?>
	<article <?php post_class( 'section section--first' ); ?>>
		<div class="container prose">
			<h1 class="h2"><?php the_title(); ?></h1>
			<p class="muted"><?php echo esc_html( get_the_date() ); ?></p>
			<?php the_content(); ?>
		</div>
	</article>
	<?php
endwhile;
get_footer();
