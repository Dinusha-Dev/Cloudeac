@set_time_limit( 0 );
$map = get_option( 'ki_media_map', array() );
$out = array( 'cats' => 0, 'products' => 0, 'gallery' => 0 );

$cats = array(
	'corporate-stationery' => array( 'Corporate Stationery', 'Letterheads, folders, file folders and menu cards printed to your brand.' ),
	'calendars-cards'      => array( 'Calendars & Cards', 'Wall and desk calendars and greeting cards for businesses and campaigns.' ),
	'brochures-catalogues' => array( 'Brochures & Catalogues', 'Brochures, leaflets, flyers, catalogues, books and annual reports.' ),
	'posters-advertising'  => array( 'Posters & Advertising', 'Posters, danglers, wobblers and point-of-purchase items that get noticed.' ),
	'labels-stickers'      => array( 'Labels & Stickers', 'Product labels, stickers and price tags in the shapes and sizes you need.' ),
	'cartons-boxes'        => array( 'Cartons & Boxes', 'Printed cartons and E & B fluted boxes for retail, transport and storage.' ),
	'carry-bags'           => array( 'Carry Bags', 'Branded paper carry bags for retail and events.' ),
	'garment-accessories'  => array( 'Garment Accessories', 'Hang tags, card inserts, collar supports and PVC boxes for the garment industry.' ),
);
$cid = array();
foreach ( $cats as $slug => $v ) {
	$t = get_term_by( 'slug', $slug, 'product_cat' );
	if ( ! $t ) {
		$r = wp_insert_term( $v[0], 'product_cat', array( 'slug' => $slug, 'description' => $v[1] ) );
		if ( is_wp_error( $r ) ) { continue; }
		$cid[ $slug ] = $r['term_id']; $out['cats']++;
	} else { $cid[ $slug ] = $t->term_id; }
}

// slug => [name, cat, image key, short, featured]
$products = array(
	array( 'brochures', 'Brochures', 'brochures-catalogues', 'brochure-display', 'Folded and stapled brochures that present your products and services with sharp colour.', 1 ),
	array( 'printed-cartons', 'Printed Cartons', 'cartons-boxes', 'box-kraft', 'Custom printed cartons cut and creased to fit your product exactly.', 1 ),
	array( 'paper-carry-bags', 'Paper Carry Bags', 'carry-bags', 'paper-bag', 'Branded paper carry bags that turn every purchase into advertising.', 1 ),
	array( 'hang-tags', 'Hang Tags', 'garment-accessories', 'hangtag-textile', 'Printed hang tags and swing tags for apparel and retail products.', 1 ),
	array( 'stickers', 'Stickers', 'labels-stickers', 'stickers', 'Printed stickers in custom shapes and sizes for products, promotions and packaging.', 1 ),
	array( 'e-b-fluted-boxes', 'E & B Fluted Boxes', 'cartons-boxes', 'open-boxes', 'Strong corrugated fluted boxes for storage, shipping and bulk packing.', 1 ),
	array( 'catalogues', 'Catalogues & Product Catalogues', 'brochures-catalogues', 'magazines', 'Multi-page catalogues that showcase your full product range.', 1 ),
	array( 'pvc-boxes', 'PVC Boxes', 'garment-accessories', 'gift-box', 'Clear and rigid PVC boxes for garments, gifts and premium products.', 1 ),
	array( 'corporate-stationery-sets', 'Corporate Stationery Sets', 'corporate-stationery', 'business-cards', 'Letterheads, cards and office stationery printed to a consistent corporate identity.', 0 ),
	array( 'folders-file-folders', 'Folders & File Folders', 'corporate-stationery', 'envelope-kraft', 'Presentation folders and file folders printed with your brand and die-cut to shape.', 0 ),
	array( 'menu-cards', 'Menu Cards', 'corporate-stationery', 'menu-card', 'Restaurant, hotel and resort menu cards on quality stock.', 0 ),
	array( 'calendars', 'Calendars', 'calendars-cards', 'calendar-desk', 'Wall, desk and planner calendars that keep your brand visible all year.', 0 ),
	array( 'greeting-cards', 'Greeting Cards', 'calendars-cards', 'pen-envelope', 'Festive and corporate greeting cards with matching envelopes.', 0 ),
	array( 'leaflets-flyers-pamphlets', 'Leaflets, Flyers & Pamphlets', 'brochures-catalogues', 'pantone', 'Single-sheet leaflets, flyers and pamphlets for promotions and information.', 0 ),
	array( 'books-annual-reports', 'Books & Annual Reports', 'brochures-catalogues', 'books-stack', 'Books, annual reports and newsletters bound and finished to a professional standard.', 0 ),
	array( 'posters', 'Posters', 'posters-advertising', 'posters-wall', 'Full-colour posters for campaigns, events and in-store promotion.', 0 ),
	array( 'pop-items-wobblers-danglers', 'POP Items, Wobblers & Danglers', 'posters-advertising', 'posters-street', 'Point-of-purchase displays, wobblers, danglers and streamers that lift in-store sales.', 0 ),
	array( 'labels-price-tags', 'Labels & Price Tags', 'labels-stickers', 'tag-dark', 'Product labels and price tags for retail, garments and packaging.', 0 ),
	array( 'cosmetic-soap-cartons', 'Cosmetic & Soap Cartons', 'cartons-boxes', 'cosmetic-boxes', 'Elegant cartons for cosmetics, soaps and Ayurvedic products.', 0 ),
	array( 'retail-product-boxes', 'Retail Product Boxes', 'cartons-boxes', 'boxes-labelled', 'Printed product boxes designed to stand out on the shelf.', 0 ),
	array( 'bulk-packing-cartons', 'Bulk Packing Cartons', 'cartons-boxes', 'boxes-stack', 'Sturdy cartons for bulk packing, storage and transport.', 0 ),
	array( 'card-inserts-collar-supports', 'Card Inserts & Collar Supports', 'garment-accessories', 'hangtag-tee', 'Card inserts, back supports and collar supports that keep garments neat and presentable.', 0 ),
);
$menu_order = 0;
foreach ( $products as $p ) {
	list( $slug, $name, $catslug, $imgkey, $short, $feat ) = $p;
	$found = get_posts( array( 'name' => $slug, 'post_type' => 'product', 'post_status' => 'any', 'numberposts' => 1 ) );
	$existing = $found ? $found[0] : null;
	$prod = $existing ? wc_get_product( $existing->ID ) : new WC_Product_Simple();
	$prod->set_name( $name );
	$prod->set_slug( $slug );
	$prod->set_status( 'publish' );
	$prod->set_catalog_visibility( 'visible' );
	$prod->set_short_description( $short );
	$prod->set_description( '<p>' . esc_html( $short ) . ' Every job is produced to your artwork and specification on our offset presses and finishing machines, with a proof for approval before production.</p><p>Sizes, materials, colours and finishes can be tailored to your requirement. Send us the quantity and details through the quotation form and our team will come back with a quotation.</p>' );
	$prod->set_category_ids( array( $cid[ $catslug ] ) );
	$prod->set_image_id( $map[ $imgkey ] );
	$prod->set_featured( (bool) $feat );
	$prod->set_menu_order( $menu_order++ );
	$prod->set_manage_stock( false );
	$prod->set_stock_status( 'instock' );
	$prod->set_sold_individually( true );
	$prod->save();
	$out['products']++;
}

// ---- Gallery
$gcats = array( 'machinery' => 'Machinery', 'production' => 'Production floor', 'printing' => 'Printing', 'packaging' => 'Packaging', 'garment' => 'Garment accessories' );
foreach ( $gcats as $slug => $name ) { if ( ! term_exists( $slug, 'ki_gallery_cat' ) ) { wp_insert_term( $name, 'ki_gallery_cat', array( 'slug' => $slug ) ); } }
$gallery = array(
	array( 'machine-sorkz', 'Heidelberg SORKZ offset press', 'machinery' ),
	array( 'press-hall', 'Multi-unit offset press line', 'production' ),
	array( 'box-kraft', 'Kraft carton detail', 'packaging' ),
	array( 'brochure-display', 'Brochures and booklets', 'printing' ),
	array( 'machine-gto', 'Heidelberg GTO offset press', 'machinery' ),
	array( 'hangtag-textile', 'Printed hang tag', 'garment' ),
	array( 'press-operators', 'Checking the print sheet', 'production' ),
	array( 'boxes-labelled', 'Labelled retail boxes', 'packaging' ),
	array( 'stickers', 'Printed stickers', 'printing' ),
	array( 'machine-guillotine', 'Programmable guillotine cutter', 'machinery' ),
	array( 'paper-bag', 'Kraft paper carry bag', 'packaging' ),
	array( 'press-roland', 'Four-colour printing units', 'production' ),
	array( 'posters-wall', 'Poster printing', 'printing' ),
	array( 'gift-box', 'Premium gift box', 'packaging' ),
	array( 'machine-diecut', 'Die cutting machine', 'machinery' ),
	array( 'hangtag-tee', 'Garment tags and inserts', 'garment' ),
	array( 'press-feeder', 'Press feeder and paper stack', 'production' ),
	array( 'open-boxes', 'Corrugated fluted boxes', 'packaging' ),
	array( 'magazines', 'Catalogues and magazines', 'printing' ),
	array( 'press-twounit', 'Two-unit offset press', 'production' ),
	array( 'cosmetic-boxes', 'Cosmetic cartons', 'packaging' ),
	array( 'calendar-desk', 'Calendars', 'printing' ),
	array( 'press-detail', 'Press rollers close-up', 'production' ),
	array( 'tag-dark', 'Price tags', 'garment' ),
	array( 'business-cards', 'Corporate stationery', 'printing' ),
	array( 'press-man', 'Offset printing line', 'production' ),
	array( 'boxes-stack', 'Stacked cartons', 'packaging' ),
	array( 'pantone', 'Colour matching guides', 'printing' ),
	array( 'press-rollers', 'Press controls', 'production' ),
	array( 'menu-card', 'Menu cards', 'printing' ),
	array( 'books-stack', 'Books and reports', 'printing' ),
	array( 'pallets', 'Cartons ready for dispatch', 'packaging' ),
);
$order = 0;
foreach ( $gallery as $g ) {
	$title = $g[1];
	$exists = get_posts( array( 'post_type' => 'ki_gallery', 'title' => $title, 'post_status' => 'any', 'numberposts' => 1, 'fields' => 'ids' ) );
	if ( $exists ) { continue; }
	$id = wp_insert_post( array( 'post_type' => 'ki_gallery', 'post_status' => 'publish', 'post_title' => $title, 'menu_order' => $order++ ) );
	set_post_thumbnail( $id, $map[ $g[0] ] );
	wp_set_object_terms( $id, array( $g[2] ), 'ki_gallery_cat' );
	$out['gallery']++;
}
return $out;
