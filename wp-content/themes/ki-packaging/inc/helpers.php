<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ------------------------------------------------ Settings (plugin-backed) */

function ki_opt( $key, $default = '' ) {
	return function_exists( 'ki_get' ) ? ki_get( $key, $default ) : $default;
}

function ki_wa_url( $msg = null ) {
	return function_exists( 'ki_whatsapp_url' ) ? ki_whatsapp_url( $msg ) : '';
}

function ki_tel( $raw ) {
	return 'tel:+' . ( function_exists( 'ki_phone_digits' ) ? ki_phone_digits( $raw ) : preg_replace( '/\D+/', '', $raw ) );
}

/* ------------------------------------------------------------- Pages/URLs */

function ki_page_url( $slug ) {
	$page = get_page_by_path( $slug );
	return $page ? get_permalink( $page ) : home_url( '/' . $slug . '/' );
}

function ki_quote_url( $product = '' ) {
	$url = ki_page_url( 'contact-us' );
	if ( $product ) {
		$url = add_query_arg( 'item', rawurlencode( $product ), $url );
	}
	return $url . '#quote';
}

function ki_shop_url() {
	if ( function_exists( 'wc_get_page_permalink' ) ) {
		$u = wc_get_page_permalink( 'shop' );
		if ( $u ) {
			return $u;
		}
	}
	return ki_page_url( 'our-products' );
}

/* ----------------------------------------------------------------- Media */

function ki_media_map() {
	static $map = null;
	if ( null === $map ) {
		$map = (array) get_option( 'ki_media_map', array() );
	}
	return $map;
}

function ki_img_id( $key ) {
	$map = ki_media_map();
	return isset( $map[ $key ] ) ? (int) $map[ $key ] : 0;
}

/** <img> for a media-library image registered under $key. */
function ki_img( $key, $size = 'large', $attr = array() ) {
	$id = ki_img_id( $key );
	if ( ! $id ) {
		return '<span class="img-fallback" aria-hidden="true"></span>';
	}
	$attr = wp_parse_args( $attr, array( 'loading' => 'lazy', 'decoding' => 'async' ) );
	return wp_get_attachment_image( $id, $size, false, $attr );
}

function ki_img_url( $key, $size = 'large' ) {
	$id = ki_img_id( $key );
	if ( ! $id ) {
		return '';
	}
	$src = wp_get_attachment_image_src( $id, $size );
	return $src ? $src[0] : '';
}

function ki_asset( $path ) {
	return get_theme_file_uri( 'assets/' . ltrim( $path, '/' ) );
}

/* ------------------------------------------------------------------ Hero */

function ki_has_hero() {
	if ( is_front_page() || is_page() ) {
		return true;
	}
	return function_exists( 'is_woocommerce' ) && is_woocommerce();
}

/**
 * Inner-page banner.
 * args: eyebrow, title, lead, image (media key), crumbs (array of [label,url])
 */
function ki_page_hero( $args = array() ) {
	$args = wp_parse_args( $args, array(
		'eyebrow' => '',
		'title'   => get_the_title(),
		'lead'    => '',
		'image'   => 'press-roll',
		'crumbs'  => array(),
	) );
	get_template_part( 'template-parts/page-hero', null, $args );
}

function ki_breadcrumbs( $crumbs = array() ) {
	$items = array( array( 'Home', home_url( '/' ) ) );
	$items = array_merge( $items, $crumbs );
	echo '<nav class="crumbs" aria-label="Breadcrumb"><ol>';
	$last = count( $items ) - 1;
	foreach ( $items as $i => $c ) {
		if ( $i === $last || empty( $c[1] ) ) {
			echo '<li aria-current="page">' . esc_html( $c[0] ) . '</li>';
		} else {
			echo '<li><a href="' . esc_url( $c[1] ) . '">' . esc_html( $c[0] ) . '</a></li>';
		}
	}
	echo '</ol></nav>';
}

/** Words wrapped in spans so the headline can animate word by word. */
function ki_split_words( $text, $start = 0 ) {
	$out = '';
	$i   = $start;
	foreach ( preg_split( '/\s+/', trim( $text ) ) as $w ) {
		$out .= '<span class="w"><span style="--i:' . (int) $i . '">' . esc_html( $w ) . '</span></span> ';
		$i++;
	}
	return $out;
}

/* ------------------------------------------------------------------ Menu */

function ki_primary_fallback() {
	$items = array(
		'Home'                => home_url( '/' ),
		'About Us'            => ki_page_url( 'about-us' ),
		'Printing Services'   => ki_page_url( 'printing-services' ),
		'Packaging Solutions' => ki_page_url( 'packaging-solutions' ),
		'Our Products'        => ki_shop_url(),
		'Gallery'             => ki_page_url( 'gallery' ),
		'Contact Us'          => ki_page_url( 'contact-us' ),
	);
	echo '<ul class="nav-list">';
	foreach ( $items as $label => $url ) {
		echo '<li class="menu-item"><a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a></li>';
	}
	echo '</ul>';
}

/** Static content used by several templates. */
function ki_industries() {
	return array(
		'Hospitals'            => 'hospital',
		'Healthcare'           => 'heart',
		'Home Appliance'       => 'home',
		'Food & Beverages'     => 'cocktail',
		'Resorts & Spa'        => 'sun',
		'Cosmetics & Soaps'    => 'droplet',
		'Ayurvedic'            => 'leaf',
		'Gherkin Industries'   => 'jar',
	);
}

function ki_sectors() {
	return array(
		'IT Companies', 'Banking & Finance', 'Consumer Electronics', 'Builders', 'Automotive', 'Telecom',
		'Educational Institutes', 'Media & Entertainment', 'Product Packaging',
	);
}

function ki_values() {
	return array(
		array( 'Outstanding quality', 'award', 'Every job is checked against the proof so what you approve is what you receive.' ),
		array( 'Latest technologies', 'cpu', 'Offset presses, guillotine and die-cutting machines keep results sharp and consistent.' ),
		array( 'Faster turnaround', 'zap', 'Streamlined production and responsive planning to meet your deadlines.' ),
		array( 'Proactive service', 'users', 'We solve practical problems before they reach the job, and stay in touch throughout.' ),
	);
}

function ki_machines() {
	return array(
		array( 'machine-sorkz', 'Heidelberg SORKZ', 'Two-colour offset press for high-quality commercial printing.' ),
		array( 'machine-gto', 'Heidelberg GTO', 'Compact, precise offset printing for stationery and short runs.' ),
		array( 'machine-guillotine', 'Guillotine cutter', 'Programmable cutting for exact trim sizes on every job.' ),
		array( 'machine-diecut', 'Die cutting machine', 'Cuts and creases cartons, boxes and special shapes.' ),
	);
}
