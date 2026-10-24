require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/image.php';
@set_time_limit( 0 );
@ini_set( 'memory_limit', '512M' );

function kim_manifest() {
	$px = function ( $id ) { return "https://images.pexels.com/photos/$id/pexels-photo-$id.jpeg?auto=compress&cs=tinysrgb&w=1920"; };
	$loc = WP_CONTENT_DIR . '/uploads/ki-import/media/';
	$m = array();
	// Local brand + machine images (from the company profile and logo).
	$m[] = array( 'site-icon', 'file', $loc . 'site-icon.png', 'KI Packaging site icon', 'K I Packaging Solutions icon', '' );
	$m[] = array( 'logo', 'file', $loc . 'logo.png', 'KI Packaging logo', 'K I Packaging Solutions logo', '' );
	$m[] = array( 'hero-machine', 'file', $loc . 'hero-machine.jpg', 'Heidelberg offset press', 'Modern Heidelberg offset printing press', 'Company profile' );
	$m[] = array( 'press-rollers', 'file', $loc . 'press-rollers.jpg', 'Printing press rollers and controls', 'Close-up of offset printing press controls and rollers', 'Company profile' );
	$m[] = array( 'machine-sorkz', 'file', $loc . 'machine-sorkz.jpg', 'Heidelberg SORKZ printing machine', 'Heidelberg SORKZ two-colour offset printing machine', 'Company profile' );
	$m[] = array( 'machine-gto', 'file', $loc . 'machine-gto.jpg', 'Heidelberg GTO printing machine', 'Heidelberg GTO offset printing machine', 'Company profile' );
	$m[] = array( 'machine-guillotine', 'file', $loc . 'machine-guillotine.jpg', 'Guillotine paper cutting machine', 'Programmable guillotine paper cutting machine', 'Company profile' );
	$m[] = array( 'machine-diecut', 'file', $loc . 'machine-diecut.jpg', 'Die cutting machine', 'Green die cutting and creasing machine', 'Company profile' );
	// Wikimedia Commons press photos (licence recorded in credit).
	foreach ( array( 1 => array( 'press-hall', 'Large multi-unit offset printing press in a print shop' ), 2 => array( 'press-feeder', 'Offset press feeder loaded with a stack of paper' ), 6 => array( 'press-roland', 'Row of four-colour offset printing units' ), 7 => array( 'press-twounit', 'Two-unit sheetfed offset printing press' ), 8 => array( 'press-man', 'Multi-colour offset printing line in a pressroom' ), 10 => array( 'press-operators', 'Printers checking a freshly printed sheet at the press' ) ) as $i => $v ) {
		$m[] = array( $v[0], 'wiki', $i, ucfirst( str_replace( '-', ' ', $v[0] ) ), $v[1], '' );
	}
	// Pexels photos (free licence, no attribution required).
	$pex = array(
		'press-detail' => array( 1440504, 'Printing press rollers and type in close-up' ),
		'box-kraft' => array( 17260157, 'Close-up of kraft cardboard box corner' ),
		'boxes-labelled' => array( 9594428, 'Kraft boxes with printed labels on a cream background' ),
		'open-boxes' => array( 5025503, 'Brown corrugated cardboard boxes' ),
		'boxes-stack' => array( 15086618, 'Stacked cardboard boxes in striped light' ),
		'gift-box' => array( 10924502, 'Premium rigid gift box with ribbon' ),
		'cosmetic-boxes' => array( 10825661, 'Printed cosmetic boxes with serum bottles' ),
		'stickers' => array( 2783837, 'Assorted colourful stickers on a table' ),
		'calendar-desk' => array( 6170644, 'Person writing on a desk calendar' ),
		'hangtag-textile' => array( 11485130, 'Blank paper hang tag on textile' ),
		'hangtag-tee' => array( 9594693, 'Hang tag on a green t-shirt' ),
		'tag-dark' => array( 11702921, 'Paper price tag on a dark background' ),
		'magazines' => array( 4238492, 'Stack of printed magazines' ),
		'envelope-kraft' => array( 7958169, 'Kraft envelope with blank card' ),
		'pen-envelope' => array( 5420902, 'Blank paper, envelope and pen' ),
		'paper-bag' => array( 11348561, 'Kraft paper carry bag with handles' ),
		'business-cards' => array( 4466176, 'Stacks of blank business cards' ),
		'card-laptop' => array( 6373930, 'Business card resting on a laptop' ),
		'brochure-display' => array( 29452731, 'Brochures and booklets on display racks' ),
		'posters-wall' => array( 10600740, 'Printed posters on a wall' ),
		'posters-street' => array( 3964819, 'Posters pasted on a street wall' ),
		'pallets' => array( 38195854, 'Cartons stacked on warehouse pallet racking' ),
		'pantone' => array( 9421350, 'Colour swatch guides used for print colour matching' ),
		'menu-card' => array( 18978083, 'Printed restaurant menus on a table' ),
		'books-stack' => array( 5009158, 'Stacks of printed books' ),
	);
	foreach ( $pex as $k => $v ) {
		$m[] = array( $k, 'url', $px( $v[0] ), ucfirst( str_replace( '-', ' ', $k ) ), $v[1], 'Pexels (free licence)' );
	}
	return $m;
}

function kim_import( $row ) {
	list( $key, $type, $src, $title, $alt, $credit ) = $row;
	$map = get_option( 'ki_media_map', array() );
	if ( ! empty( $map[ $key ] ) && get_post( $map[ $key ] ) ) {
		return 'exists';
	}
	$ext = 'jpg';
	if ( 'file' === $type ) {
		if ( ! file_exists( $src ) ) { return 'missing file'; }
		$ext = pathinfo( $src, PATHINFO_EXTENSION );
		$tmp = wp_tempnam( $src );
		copy( $src, $tmp );
	} else {
		if ( 'wiki' === $type ) {
			$cands = get_option( 'kicand', array() );
			$c     = $cands['offset printing press'][ $src ];
			$d     = kic_api( array( 'action' => 'query', 'titles' => $c['title'], 'prop' => 'imageinfo', 'iiprop' => 'url', 'iiurlwidth' => 2000 ) );
			$page  = reset( $d['query']['pages'] );
			$src   = $page['imageinfo'][0]['thumburl'];
			$credit = trim( ( $c['author'] ? $c['author'] : 'Unknown' ) . ' / Wikimedia Commons / ' . $c['lic'] . ' / ' . $c['page'] );
		}
		$tmp = download_url( $src, 40, false );
		if ( is_wp_error( $tmp ) ) { return 'download error: ' . $tmp->get_error_message(); }
	}
	$file = array( 'name' => sanitize_file_name( 'ki-' . $key . '.' . $ext ), 'tmp_name' => $tmp );
	$id   = media_handle_sideload( $file, 0, $title, array( 'post_excerpt' => $credit ) );
	if ( is_wp_error( $id ) ) { @unlink( $tmp ); return 'sideload error: ' . $id->get_error_message(); }
	update_post_meta( $id, '_wp_attachment_image_alt', $alt );
	update_post_meta( $id, '_ki_key', $key );
	if ( $credit ) { update_post_meta( $id, '_ki_credit', $credit ); }
	$map = get_option( 'ki_media_map', array() );
	$map[ $key ] = $id;
	update_option( 'ki_media_map', $map, false );
	return 'ok #' . $id;
}
