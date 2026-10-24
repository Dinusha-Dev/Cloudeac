<?php
/**
 * Elementor integration: pages built with Elementor render full width, with the theme header and footer around them.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ki_is_elementor_page( $post_id = 0 ) {
	if ( ! class_exists( '\Elementor\Plugin' ) ) {
		return false;
	}
	$post_id = $post_id ? $post_id : get_queried_object_id();
	if ( ! $post_id ) {
		return false;
	}
	$doc = \Elementor\Plugin::$instance->documents->get( $post_id );
	return $doc && $doc->is_built_with_elementor();
}

// Elementor-built pages use one clean template, so the page content is exactly what the editor shows.
add_filter( 'template_include', function ( $template ) {
	if ( is_singular( 'page' ) && ki_is_elementor_page() ) {
		$t = get_theme_file_path( 'template-elementor.php' );
		if ( file_exists( $t ) ) {
			return $t;
		}
	}
	return $template;
}, 99 );

// Elementor loads its own styles; ours must come after so the brand rules win.
add_action( 'wp_enqueue_scripts', function () {
	if ( ! class_exists( '\Elementor\Plugin' ) ) {
		return;
	}
	$css = get_theme_file_path( 'assets/css/elementor.css' );
	wp_enqueue_style( 'ki-elementor', get_theme_file_uri( 'assets/css/elementor.css' ), array( 'ki-main' ), file_exists( $css ) ? filemtime( $css ) : KI_THEME_VERSION );
}, 30 );

add_action( 'after_setup_theme', function () {
	add_theme_support( 'elementor' );
} );

// Let the editor use the theme's own fonts and keep Elementor off external font servers.
add_filter( 'elementor/frontend/print_google_fonts', '__return_false' );

/** True when the Our Products page has been built in Elementor (its content becomes the shop banner). */
function ki_shop_has_elementor_hero() {
	if ( ! function_exists( 'wc_get_page_id' ) ) {
		return false;
	}
	$id = (int) wc_get_page_id( 'shop' );
	return $id > 0 && ki_is_elementor_page( $id );
}

function ki_render_shop_hero() {
	$id = (int) wc_get_page_id( 'shop' );
	echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $id, true ); // phpcs:ignore WordPress.Security.EscapeOutput
}
