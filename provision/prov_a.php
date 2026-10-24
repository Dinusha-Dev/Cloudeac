require_once ABSPATH . 'wp-admin/includes/plugin.php';
@set_time_limit( 0 );
$out = array();
foreach ( array( 'woocommerce/woocommerce.php', 'ki-packaging-core/ki-packaging-core.php' ) as $p ) {
	if ( is_plugin_active( $p ) ) { $out[ $p ] = 'already active'; continue; }
	$r = activate_plugin( $p );
	$out[ $p ] = is_wp_error( $r ) ? 'ERROR ' . $r->get_error_message() : 'activated';
}
delete_transient( '_wc_activation_redirect' );
$out['wc_version'] = defined( 'WC_VERSION' ) ? WC_VERSION : 'n/a';
$out['blog_public'] = get_option( 'blog_public' );
$out['permalinks'] = get_option( 'permalink_structure' );
$out['wc_pages'] = array_map( function ( $k ) { $id = get_option( 'woocommerce_' . $k . '_page_id' ); return $id . ':' . ( $id ? get_the_title( $id ) : '' ); }, array( 'shop' => 'shop', 'cart' => 'cart', 'checkout' => 'checkout', 'myaccount' => 'myaccount' ) );
return $out;
