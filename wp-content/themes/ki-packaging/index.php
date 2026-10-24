<?php
get_header();
ki_main_open();
?>
<section class="section section--first">
	<div class="container prose">
		<h1 class="h2"><?php echo is_home() ? esc_html__( 'Latest news', 'ki-packaging' ) : esc_html( get_the_archive_title() ); ?></h1>
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<article <?php post_class( 'post-row' ); ?>>
				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<p class="muted"><?php echo esc_html( get_the_date() ); ?></p>
				<?php the_excerpt(); ?>
			</article>
		<?php endwhile; the_posts_pagination(); else : ?>
			<p>Nothing to show here yet.</p>
		<?php endif; ?>
	</div>
</section>
<?php
ki_main_close();
get_footer();
