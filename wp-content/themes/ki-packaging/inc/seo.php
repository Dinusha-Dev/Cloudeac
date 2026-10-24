<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Meta description: per-page custom field, product excerpt, or sensible default. */
function ki_meta_description() {
	$desc = '';
	if ( is_singular() ) {
		$desc = get_post_meta( get_queried_object_id(), '_ki_desc', true );
		if ( ! $desc ) {
			$desc = has_excerpt() ? get_the_excerpt() : '';
		}
	} elseif ( function_exists( 'is_product_category' ) && is_product_category() ) {
		$term = get_queried_object();
		$desc = $term && $term->description ? wp_strip_all_tags( $term->description ) : '';
	}
	if ( ! $desc ) {
		$desc = 'K I Packaging Solutions in Kadawatha, Sri Lanka: offset printing, cartons, labels, carry bags and custom packaging with faster turnaround. Request a quotation today.';
	}
	return wp_html_excerpt( $desc, 158, '…' );
}

add_action( 'wp_head', function () {
	$desc  = ki_meta_description();
	$title = wp_get_document_title();
	$url   = is_singular() ? get_permalink() : home_url( add_query_arg( null, null ) );
	$img   = '';
	if ( is_singular() && has_post_thumbnail() ) {
		$img = get_the_post_thumbnail_url( null, 'ki-wide' );
	}
	if ( ! $img ) {
		$img = ki_img_url( 'hero-machine', 'large' );
	}
	echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
	echo '<meta name="theme-color" content="#0F0F12">' . "\n";
	echo '<meta property="og:type" content="' . ( is_front_page() ? 'website' : 'article' ) . '">' . "\n";
	echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta property="og:description" content="' . esc_attr( $desc ) . '">' . "\n";
	echo '<meta property="og:url" content="' . esc_url( $url ) . '">' . "\n";
	if ( $img ) {
		echo '<meta property="og:image" content="' . esc_url( $img ) . '">' . "\n";
	}
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
}, 2 );

/** LocalBusiness structured data for search engines. */
add_action( 'wp_head', function () {
	$same = array();
	if ( function_exists( 'ki_socials' ) ) {
		foreach ( ki_socials() as $s ) {
			$same[] = $s['url'];
		}
	}
	$data = array(
		'@context'    => 'https://schema.org',
		'@type'       => 'LocalBusiness',
		'@id'         => home_url( '/#business' ),
		'name'        => ki_opt( 'company', 'K I Packaging Solutions' ),
		'description' => 'Offset printing, cartons, labels, carry bags and custom packaging solutions in Kadawatha, Sri Lanka.',
		'url'         => home_url( '/' ),
		'logo'        => ki_asset( 'img/logo-h.png' ),
		'image'       => ki_img_url( 'hero-machine', 'large' ) ? ki_img_url( 'hero-machine', 'large' ) : ki_asset( 'img/logo-h.png' ),
		'address'     => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => '# 411/C/4/1 Kirillawala',
			'addressLocality' => 'Kadawatha',
			'addressCountry'  => 'LK',
		),
	);
	if ( ki_opt( 'phone' ) ) {
		$data['telephone'] = ki_opt( 'phone' );
	}
	if ( ki_opt( 'email' ) ) {
		$data['email'] = ki_opt( 'email' );
	}
	if ( $same ) {
		$data['sameAs'] = $same;
	}
	echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . "</script>\n";
}, 3 );
