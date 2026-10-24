// Imports generated Elementor JSON into the site pages and aligns the Elementor kit with the brand.
@set_time_limit( 0 );
$dir = WP_CONTENT_DIR . '/uploads/ki-import/el';
$out = array();

// ---- Kit: brand colours, self-hosted fonts, content width and zero default spacing.
$kit_id = (int) get_option( 'elementor_active_kit' );
$kit = get_post_meta( $kit_id, '_elementor_page_settings', true );
$kit = is_array( $kit ) ? $kit : array();
$kit['system_colors'] = array(
	array( '_id' => 'primary', 'title' => 'Primary (ink)', 'color' => '#0F0F12' ),
	array( '_id' => 'secondary', 'title' => 'Secondary (orange)', 'color' => '#F58220' ),
	array( '_id' => 'text', 'title' => 'Text', 'color' => '#3B3B42' ),
	array( '_id' => 'accent', 'title' => 'Accent (orange)', 'color' => '#F58220' ),
);
$kit['custom_colors'] = array(
	array( '_id' => 'kiorange7', 'title' => 'Orange dark', 'color' => '#B95400' ),
	array( '_id' => 'kipaper1', 'title' => 'Paper', 'color' => '#FAF6F1' ),
	array( '_id' => 'kiinkk2', 'title' => 'Ink 2', 'color' => '#1A1A1F' ),
	array( '_id' => 'kiline03', 'title' => 'Line', 'color' => '#E8E3DD' ),
);
$kit['system_typography'] = array(
	array( '_id' => 'primary', 'title' => 'Primary', 'typography_typography' => 'custom', 'typography_font_family' => 'KI Display', 'typography_font_weight' => '700' ),
	array( '_id' => 'secondary', 'title' => 'Secondary', 'typography_typography' => 'custom', 'typography_font_family' => 'KI Display', 'typography_font_weight' => '600' ),
	array( '_id' => 'text', 'title' => 'Text', 'typography_typography' => 'custom', 'typography_font_family' => 'KI Body', 'typography_font_weight' => '400' ),
	array( '_id' => 'accent', 'title' => 'Accent', 'typography_typography' => 'custom', 'typography_font_family' => 'KI Display', 'typography_font_weight' => '600' ),
);
$kit['container_width'] = array( 'unit' => 'px', 'size' => 1240, 'sizes' => array() );
$kit['space_between_widgets'] = array( 'column' => '0', 'row' => '0', 'isLinked' => true, 'unit' => 'px' );
$kit['container_padding'] = array( 'unit' => 'px', 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'isLinked' => true );
update_post_meta( $kit_id, '_elementor_page_settings', $kit );
update_option( 'elementor_google_font', '0' );
update_option( 'elementor_disable_color_schemes', 'yes' );
update_option( 'elementor_disable_typography_schemes', 'yes' );
update_option( 'elementor_load_fa4_shim', '' );
$out['kit'] = $kit_id;

// ---- Pages. $mode = 'preview' writes to temporary elp-* pages, 'live' writes to the real pages.
$mode = isset( $mode ) ? $mode : 'preview';
foreach ( glob( "$dir/*.json" ) as $file ) {
	$slug = basename( $file, '.json' );
	$real = 'home' === $slug ? get_post( (int) get_option( 'page_on_front' ) ) : get_page_by_path( $slug, OBJECT, 'page' );
	if ( ! $real ) { $out['missing'][] = $slug; continue; }
	if ( 'live' === $mode ) {
		$page = $real;
		if ( ! get_post_meta( $page->ID, '_ki_pre_elementor', true ) ) { update_post_meta( $page->ID, '_ki_pre_elementor', 'php-template' ); }
	} else {
		$page = get_page_by_path( 'elp-' . $slug, OBJECT, 'page' );
		if ( ! $page ) {
			$pid = wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => $real->post_title, 'post_name' => 'elp-' . $slug ) );
			$page = get_post( $pid );
		}
		update_post_meta( $page->ID, '_ki_desc', 'Preview' );
	}
	$json = file_get_contents( $file );
	if ( null === json_decode( $json ) ) { $out['bad_json'][] = $slug; continue; }
	update_post_meta( $page->ID, '_elementor_data', wp_slash( $json ) );
	update_post_meta( $page->ID, '_elementor_edit_mode', 'builder' );
	update_post_meta( $page->ID, '_elementor_template_type', 'wp-page' );
	update_post_meta( $page->ID, '_elementor_version', ELEMENTOR_VERSION );
	update_post_meta( $page->ID, '_wp_page_template', 'default' );
	delete_post_meta( $page->ID, '_elementor_css' );
	$out['pages'][ $slug ] = $page->ID;
}
// Remove the test page created while experimenting.
$t = get_page_by_path( 'ki-el-test', OBJECT, 'page' );
if ( $t ) { wp_delete_post( $t->ID, true ); $out['test_page'] = 'deleted'; }
\Elementor\Plugin::$instance->files_manager->clear_cache();
return $out;
