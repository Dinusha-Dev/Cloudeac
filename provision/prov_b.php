@set_time_limit( 0 );
$map = get_option( 'ki_media_map', array() );
$out = array();

// ---- Identity & permalinks
update_option( 'blogname', 'K I Packaging Solutions' );
update_option( 'blogdescription', 'Offset Printing & Packaging Solutions' );
update_option( 'timezone_string', 'Asia/Colombo' );
update_option( 'permalink_structure', '/%postname%/' );
update_option( 'site_icon', $map['site-icon'] );

// ---- Pages
$pages = array(
	'home'                => array( 'Home', 'K I Packaging Solutions in Kadawatha, Sri Lanka: offset printing, cartons, labels, carry bags and custom packaging with faster turnaround. Request a quotation today.' ),
	'about-us'            => array( 'About Us', 'About K I Packaging Solutions: high-quality, cutting-edge printing built on commitment, dedication and customer satisfaction. Our mission, vision, values and machinery.' ),
	'printing-services'   => array( 'Printing Services', 'Offset and commercial printing in Kadawatha: corporate stationery, brochures, catalogues, books, posters, calendars and more from K I Packaging Solutions.' ),
	'packaging-solutions' => array( 'Packaging Solutions', 'Custom cartons, E & B fluted boxes, PVC boxes, labels, stickers, carry bags and garment accessories from K I Packaging Solutions, Kadawatha.' ),
	'gallery'             => array( 'Gallery', 'See the machines, printing, packaging and products from K I Packaging Solutions in Kadawatha, Sri Lanka.' ),
	'contact-us'          => array( 'Contact Us', 'Contact K I Packaging Solutions, Kirillawala, Kadawatha. Request a quotation for printing and packaging online.' ),
);
$ids = array();
foreach ( $pages as $slug => $v ) {
	$existing = get_page_by_path( $slug );
	$args = array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => $v[0], 'post_name' => $slug, 'post_content' => '' );
	if ( $existing ) { $args['ID'] = $existing->ID; $id = wp_update_post( $args ); } else { $id = wp_insert_post( $args ); }
	update_post_meta( $id, '_ki_desc', $v[1] );
	$ids[ $slug ] = $id;
}
// Shop page = Our Products (reuse the WooCommerce Shop page).
$shop_id = (int) get_option( 'woocommerce_shop_page_id' );
wp_update_post( array( 'ID' => $shop_id, 'post_title' => 'Our Products', 'post_name' => 'our-products', 'post_status' => 'publish' ) );
update_post_meta( $shop_id, '_ki_desc', 'Browse printing and packaging products from K I Packaging Solutions: brochures, catalogues, cartons, labels, carry bags, hang tags and more. Request a quotation.' );
$ids['our-products'] = $shop_id;

update_option( 'show_on_front', 'page' );
update_option( 'page_on_front', $ids['home'] );
update_option( 'page_for_posts', 0 );

// Default WordPress sample page is not part of the site: move to trash (recoverable).
$sample = get_page_by_path( 'sample-page' );
if ( $sample && 'publish' === $sample->post_status ) { wp_trash_post( $sample->ID ); $out['sample_page'] = 'trashed'; }

// ---- WooCommerce as catalogue
update_option( 'woocommerce_currency', 'LKR' );
update_option( 'woocommerce_default_country', 'LK' );
update_option( 'woocommerce_enable_reviews', 'no' );
update_option( 'woocommerce_enable_coupons', 'no' );
update_option( 'woocommerce_manage_stock', 'no' );
update_option( 'woocommerce_calc_taxes', 'no' );
update_option( 'woocommerce_coming_soon', 'no' );
update_option( 'woocommerce_store_pages_only', 'no' );
update_option( 'woocommerce_allow_tracking', 'no' );
update_option( 'woocommerce_store_address', '# 411/C/4/1 Kirillawala' );
update_option( 'woocommerce_store_city', 'Kadawatha' );

// ---- Primary menu
$menu_name = 'Primary Menu';
$menu = wp_get_nav_menu_object( $menu_name );
$menu_id = $menu ? $menu->term_id : wp_create_nav_menu( $menu_name );
foreach ( (array) wp_get_nav_menu_items( $menu_id ) as $it ) { wp_delete_post( $it->ID, true ); }
$order = array( 'home' => 'Home', 'about-us' => 'About Us', 'printing-services' => 'Printing Services', 'packaging-solutions' => 'Packaging Solutions', 'our-products' => 'Our Products', 'gallery' => 'Gallery', 'contact-us' => 'Contact Us' );
$pos = 1;
foreach ( $order as $slug => $label ) {
	wp_update_nav_menu_item( $menu_id, 0, array(
		'menu-item-title' => $label, 'menu-item-object' => 'page', 'menu-item-object-id' => $ids[ $slug ],
		'menu-item-type' => 'post_type', 'menu-item-status' => 'publish', 'menu-item-position' => $pos++,
	) );
}
$locs = get_theme_mod( 'nav_menu_locations', array() );

// ---- Theme
switch_theme( 'ki-packaging' );
set_theme_mod( 'nav_menu_locations', array( 'primary' => $menu_id ) );
set_theme_mod( 'custom_logo', $map['logo'] );

// ---- Business settings (contact numbers are intentionally blank until the client supplies them)
if ( ! get_option( 'ki_settings' ) ) { update_option( 'ki_settings', array() ); }

flush_rewrite_rules();
$out['pages'] = $ids;
$out['menu_id'] = $menu_id;
$out['theme'] = wp_get_theme()->get_stylesheet();
$out['front'] = get_option( 'page_on_front' );
$out['home_url'] = home_url( '/' );
$out['shop_url'] = get_permalink( $shop_id );
return $out;
