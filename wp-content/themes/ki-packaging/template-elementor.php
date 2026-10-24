<?php
/**
 * Template for pages built with Elementor: the editor content, full width, inside the site header and footer.
 */
get_header();
while ( have_posts() ) :
	the_post();
	the_content();
endwhile;
get_footer();
