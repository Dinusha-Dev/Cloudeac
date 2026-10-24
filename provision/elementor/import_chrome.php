// Creates / updates the Theme Builder header and footer templates and publishes them site-wide.
@set_time_limit( 0 );
$dir = WP_CONTENT_DIR . '/uploads/ki-import/chrome';
$mode = isset( $mode ) ? $mode : 'publish';
$out = array();
foreach ( array( 'header' => 'KI Header', 'footer' => 'KI Footer' ) as $type => $title ) {
	$file = "$dir/$type.json";
	if ( ! file_exists( $file ) ) { $out[ $type ] = 'missing json'; continue; }
	$json = file_get_contents( $file );
	if ( null === json_decode( $json ) ) { $out[ $type ] = 'bad json'; continue; }
	$found = get_posts( array( 'post_type' => 'elementor_library', 'post_status' => 'any', 'numberposts' => 1, 'title' => $title ) );
	$id = $found ? $found[0]->ID : wp_insert_post( array( 'post_type' => 'elementor_library', 'post_title' => $title, 'post_status' => 'draft' ) );
	wp_set_object_terms( $id, $type, 'elementor_library_type' );
	update_post_meta( $id, '_elementor_template_type', $type );
	update_post_meta( $id, '_elementor_edit_mode', 'builder' );
	update_post_meta( $id, '_elementor_version', ELEMENTOR_VERSION );
	update_post_meta( $id, '_elementor_data', wp_slash( $json ) );
	update_post_meta( $id, '_elementor_conditions', array( 'include/general' ) );
	update_post_meta( $id, '_wp_page_template', 'default' );
	delete_post_meta( $id, '_elementor_css' );
	wp_update_post( array( 'ID' => $id, 'post_status' => 'publish' === $mode ? 'publish' : 'draft' ) );
	$out[ $type ] = array( $id, get_post_status( $id ) );
}
// Rebuild Theme Builder's condition cache so the templates are picked up.
try {
	$tb = \ElementorPro\Modules\ThemeBuilder\Module::instance();
	$tb->get_conditions_manager()->get_cache()->regenerate();
	$out['conditions_cache'] = 'regenerated';
} catch ( \Throwable $e ) { $out['conditions_cache'] = 'ERR ' . $e->getMessage(); }
\Elementor\Plugin::$instance->files_manager->clear_cache();
return $out;
