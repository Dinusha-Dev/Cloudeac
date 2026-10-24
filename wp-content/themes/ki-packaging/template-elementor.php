<?php
/**
 * Template for pages built with Elementor: the editor content, full width, inside the site header and footer.
 */
get_header();
ki_main_open();
while ( have_posts() ) :
	the_post();
	the_content();
endwhile;
ki_main_close();
get_footer();
