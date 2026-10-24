<?php
/**
 * KI Packaging theme bootstrap.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'KI_THEME_VERSION', '1.0.0' );

require_once get_template_directory() . '/inc/icons.php';
require_once get_template_directory() . '/inc/helpers.php';
require_once get_template_directory() . '/inc/seo.php';
require_once get_template_directory() . '/inc/woocommerce.php';

add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'custom-logo', array( 'height' => 120, 'width' => 400, 'flex-width' => true, 'flex-height' => true ) );
	register_nav_menus( array(
		'primary' => 'Primary menu',
		'footer'  => 'Footer menu',
	) );
	add_image_size( 'ki-card', 720, 540, true );
	add_image_size( 'ki-wide', 1280, 720, true );
	add_image_size( 'ki-hero', 1920, 1080, true );
} );

add_action( 'wp_enqueue_scripts', function () {
	$css = get_theme_file_path( 'assets/css/main.css' );
	$js  = get_theme_file_path( 'assets/js/main.js' );
	wp_enqueue_style( 'ki-main', get_theme_file_uri( 'assets/css/main.css' ), array(), file_exists( $css ) ? filemtime( $css ) : KI_THEME_VERSION );
	wp_enqueue_script( 'ki-main', get_theme_file_uri( 'assets/js/main.js' ), array(), file_exists( $js ) ? filemtime( $js ) : KI_THEME_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
	wp_localize_script( 'ki-main', 'KIT', array(
		'home' => home_url( '/' ),
	) );
	if ( ! is_singular() || ! comments_open() ) {
		wp_dequeue_script( 'comment-reply' );
	}
}, 20 );

// Lean head: drop emoji and extra links we do not use.
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );

// Tell CSS the browser runs JS before first paint (enables reveal animations without a flash).
add_action( 'wp_head', function () {
	echo "<script>document.documentElement.classList.replace('no-js','js');try{if(sessionStorage.getItem('ki_seen'))document.documentElement.classList.add('no-loader')}catch(e){}</script>\n";
}, 0 );

add_filter( 'language_attributes', function ( $out ) {
	return $out . ' class="no-js"';
} );

add_action( 'wp_head', function () {
	foreach ( array( 'outfit-var.woff2', 'inter-var.woff2' ) as $f ) {
		if ( file_exists( get_theme_file_path( 'assets/fonts/' . $f ) ) ) {
			printf( '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n", esc_url( get_theme_file_uri( 'assets/fonts/' . $f ) ) );
		}
	}
}, 1 );

add_filter( 'body_class', function ( $classes ) {
	$classes[] = ki_has_hero() ? 'has-hero' : 'header-solid';
	return $classes;
} );

add_filter( 'excerpt_length', function () {
	return 28;
} );

add_filter( 'excerpt_more', '__return_empty_string' );
