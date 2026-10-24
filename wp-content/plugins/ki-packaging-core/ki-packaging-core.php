<?php
/**
 * Plugin Name: KI Packaging Core
 * Description: Quotation request system, gallery, WhatsApp chat link and business settings for K I Packaging Solutions.
 * Version:     1.0.0
 * Requires PHP: 7.4
 * Text Domain: ki-core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'KI_CORE_VERSION', '1.0.0' );
define( 'KI_CORE_DIR', plugin_dir_path( __FILE__ ) );
define( 'KI_CORE_URL', plugin_dir_url( __FILE__ ) );

require_once KI_CORE_DIR . 'includes/settings.php';
require_once KI_CORE_DIR . 'includes/gallery.php';
require_once KI_CORE_DIR . 'includes/quotes.php';
require_once KI_CORE_DIR . 'includes/form.php';

register_activation_hook( __FILE__, function () {
	ki_register_gallery();
	ki_register_quotes();
	flush_rewrite_rules();
} );

register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
