<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ki_register_gallery() {
	register_post_type( 'ki_gallery', array(
		'labels'       => array(
			'name'          => 'Gallery',
			'singular_name' => 'Gallery Item',
			'add_new_item'  => 'Add Gallery Item',
			'edit_item'     => 'Edit Gallery Item',
			'menu_name'     => 'Gallery',
		),
		'public'       => false,
		'show_ui'      => true,
		'menu_icon'    => 'dashicons-format-gallery',
		'menu_position' => 26,
		'supports'     => array( 'title', 'thumbnail', 'page-attributes' ),
	) );

	register_taxonomy( 'ki_gallery_cat', 'ki_gallery', array(
		'labels'       => array( 'name' => 'Gallery Categories', 'singular_name' => 'Gallery Category' ),
		'public'       => false,
		'show_ui'      => true,
		'hierarchical' => true,
		'show_admin_column' => true,
	) );
}
add_action( 'init', 'ki_register_gallery' );

/** Gallery items as arrays, ordered by menu order. */
function ki_gallery_items( $limit = -1 ) {
	$q = new WP_Query( array(
		'post_type'      => 'ki_gallery',
		'posts_per_page' => $limit,
		'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
		'no_found_rows'  => true,
	) );
	$items = array();
	foreach ( $q->posts as $p ) {
		$thumb = get_post_thumbnail_id( $p );
		if ( ! $thumb ) {
			continue;
		}
		$terms   = wp_get_post_terms( $p->ID, 'ki_gallery_cat' );
		$items[] = array(
			'id'    => $p->ID,
			'title' => get_the_title( $p ),
			'thumb' => $thumb,
			'cats'  => wp_list_pluck( $terms, 'slug' ),
			'names' => wp_list_pluck( $terms, 'name' ),
		);
	}
	return $items;
}
