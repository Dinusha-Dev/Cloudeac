<?php
/**
 * Plugin Name: KI Memory Headroom
 * Description: Raises PHP memory to 256M for Elementor (the host default of 128M is exhausted when Elementor builds CSS).
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$ki_limit = ini_get( 'memory_limit' );
if ( $ki_limit && '-1' !== $ki_limit && (int) $ki_limit < 256 ) {
	@ini_set( 'memory_limit', '256M' ); // phpcs:ignore WordPress.PHP.IniSet
}
