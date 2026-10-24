<?php
get_header();
ki_main_open();
?>
<section class="section section--first notfound">
	<div class="container center">
		<p class="big-404" aria-hidden="true">404</p>
		<h1 class="h2">This page could not be found</h1>
		<p class="muted">The page may have moved. Head back home or request a quotation for your next print job.</p>
		<p><a class="btn btn-primary btn-lg" href="<?php echo esc_url( home_url( '/' ) ); ?>">Back to home</a></p>
	</div>
</section>
<?php
ki_main_close();
get_footer();
