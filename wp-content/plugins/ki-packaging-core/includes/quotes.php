<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ki_quote_statuses() {
	return array(
		'new'       => 'New',
		'contacted' => 'Contacted',
		'quoted'    => 'Quotation sent',
		'won'       => 'Confirmed',
		'closed'    => 'Closed',
	);
}

function ki_register_quotes() {
	register_post_type( 'ki_quote', array(
		'labels'        => array(
			'name'          => 'Quotation Requests',
			'singular_name' => 'Quotation Request',
			'menu_name'     => 'Quotations',
			'search_items'  => 'Search requests',
			'not_found'     => 'No quotation requests yet.',
		),
		'public'        => false,
		'show_ui'       => true,
		'menu_icon'     => 'dashicons-clipboard',
		'menu_position' => 25,
		'supports'      => array( 'title' ),
		'capabilities'  => array( 'create_posts' => 'do_not_allow' ),
		'map_meta_cap'  => true,
	) );
}
add_action( 'init', 'ki_register_quotes' );

/* ---------------------------------------------------------------- Submit */

function ki_quote_token() {
	$ts = time();
	return $ts . '.' . substr( wp_hash( 'kiq' . $ts ), 0, 16 );
}

function ki_quote_token_ok( $token ) {
	$parts = explode( '.', (string) $token );
	if ( 2 !== count( $parts ) || ! ctype_digit( $parts[0] ) ) {
		return false;
	}
	if ( ! hash_equals( substr( wp_hash( 'kiq' . $parts[0] ), 0, 16 ), $parts[1] ) ) {
		return false;
	}
	$age = time() - (int) $parts[0];
	return $age >= 3 && $age <= DAY_IN_SECONDS * 2;
}

function ki_client_ip() {
	return isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '0';
}

add_action( 'wp_ajax_nopriv_ki_submit_quote', 'ki_ajax_submit_quote' );
add_action( 'wp_ajax_ki_submit_quote', 'ki_ajax_submit_quote' );

function ki_ajax_submit_quote() {
	if ( ! isset( $_POST['ki_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ki_nonce'] ) ), 'ki_quote' ) ) {
		wp_send_json_error( array( 'message' => 'Your session has expired. Please refresh the page and try again.' ), 403 );
	}
	// Honeypot and timing trap: pretend success so bots learn nothing.
	$token = isset( $_POST['ki_token'] ) ? sanitize_text_field( wp_unslash( $_POST['ki_token'] ) ) : '';
	if ( ! empty( $_POST['ki_website'] ) || ! ki_quote_token_ok( $token ) ) {
		wp_send_json_success( array( 'message' => 'Thank you. We have received your request.', 'ref' => '' ) );
	}

	$rate_key = 'ki_q_' . md5( ki_client_ip() );
	$count    = (int) get_transient( $rate_key );
	if ( $count >= 5 ) {
		wp_send_json_error( array( 'message' => 'Too many requests from your connection. Please call us or try again later.' ), 429 );
	}

	$f = array(
		'name'     => isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '',
		'company'  => isset( $_POST['company'] ) ? sanitize_text_field( wp_unslash( $_POST['company'] ) ) : '',
		'email'    => isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '',
		'phone'    => isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '',
		'type'     => isset( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : '',
		'product'  => isset( $_POST['product'] ) ? sanitize_text_field( wp_unslash( $_POST['product'] ) ) : '',
		'quantity' => isset( $_POST['quantity'] ) ? sanitize_text_field( wp_unslash( $_POST['quantity'] ) ) : '',
		'size'     => isset( $_POST['size'] ) ? sanitize_text_field( wp_unslash( $_POST['size'] ) ) : '',
		'needed'   => isset( $_POST['needed'] ) ? sanitize_text_field( wp_unslash( $_POST['needed'] ) ) : '',
		'message'  => isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '',
	);

	$errors = array();
	if ( strlen( $f['name'] ) < 2 ) {
		$errors['name'] = 'Please enter your name.';
	}
	if ( ! is_email( $f['email'] ) ) {
		$errors['email'] = 'Please enter a valid email address.';
	}
	if ( strlen( preg_replace( '/\D+/', '', $f['phone'] ) ) < 7 ) {
		$errors['phone'] = 'Please enter a valid phone number.';
	}
	if ( '' === $f['type'] ) {
		$errors['type'] = 'Please choose what you need.';
	}
	if ( '' === $f['quantity'] ) {
		$errors['quantity'] = 'Please tell us the approximate quantity.';
	}
	if ( $errors ) {
		wp_send_json_error( array( 'message' => 'Please check the highlighted fields.', 'fields' => $errors ), 422 );
	}

	// Optional artwork upload.
	$file_url = '';
	$file_dir = '';
	if ( ! empty( $_FILES['artwork']['name'] ) && UPLOAD_ERR_NO_FILE !== (int) $_FILES['artwork']['error'] ) {
		if ( (int) $_FILES['artwork']['size'] > 8 * MB_IN_BYTES ) {
			wp_send_json_error( array( 'message' => 'The file is too large. Maximum size is 8 MB.', 'fields' => array( 'artwork' => 'Maximum size is 8 MB.' ) ), 422 );
		}
		require_once ABSPATH . 'wp-admin/includes/file.php';
		$filter = function ( $dirs ) {
			$dirs['subdir'] = '/ki-quotes';
			$dirs['path']   = $dirs['basedir'] . '/ki-quotes';
			$dirs['url']    = $dirs['baseurl'] . '/ki-quotes';
			return $dirs;
		};
		add_filter( 'upload_dir', $filter );
		$_FILES['artwork']['name'] = wp_generate_password( 10, false ) . '-' . sanitize_file_name( $_FILES['artwork']['name'] );
		$up = wp_handle_upload( $_FILES['artwork'], array(
			'test_form' => false,
			'mimes'     => array(
				'pdf'          => 'application/pdf',
				'jpg|jpeg|jpe' => 'image/jpeg',
				'png'          => 'image/png',
				'webp'         => 'image/webp',
			),
		) );
		remove_filter( 'upload_dir', $filter );
		if ( ! empty( $up['error'] ) ) {
			wp_send_json_error( array( 'message' => 'The file could not be uploaded. Please attach a PDF, JPG or PNG.', 'fields' => array( 'artwork' => 'PDF, JPG or PNG only.' ) ), 422 );
		}
		$file_url = $up['url'];
		$file_dir = $up['file'];
	}

	$id = wp_insert_post( array(
		'post_type'   => 'ki_quote',
		'post_status' => 'publish',
		'post_title'  => $f['name'] . ' - ' . ( $f['product'] ? $f['product'] : $f['type'] ),
	), true );
	if ( is_wp_error( $id ) ) {
		wp_send_json_error( array( 'message' => 'Sorry, something went wrong. Please try again or contact us directly.' ), 500 );
	}
	$ref = 'KIQ-' . str_pad( (string) $id, 5, '0', STR_PAD_LEFT );
	wp_update_post( array( 'ID' => $id, 'post_title' => $ref . ' - ' . $f['name'] . ' - ' . ( $f['product'] ? $f['product'] : $f['type'] ) ) );
	foreach ( $f as $k => $v ) {
		update_post_meta( $id, '_ki_' . $k, $v );
	}
	update_post_meta( $id, '_ki_status', 'new' );
	update_post_meta( $id, '_ki_ref', $ref );
	update_post_meta( $id, '_ki_ip', ki_client_ip() );
	if ( $file_url ) {
		update_post_meta( $id, '_ki_file_url', esc_url_raw( $file_url ) );
		update_post_meta( $id, '_ki_file_dir', $file_dir );
	}
	set_transient( $rate_key, $count + 1, HOUR_IN_SECONDS );

	ki_send_quote_emails( $id, $ref, $f, $file_url );

	wp_send_json_success( array(
		'message' => 'Thank you, ' . $f['name'] . '. Your quotation request has been received.',
		'ref'     => $ref,
	) );
}

/** wp_mail that can never take the request down (PHP errors from a broken mail transport are contained). */
function ki_safe_mail( $to, $subject, $message, $headers ) {
	try {
		return (bool) wp_mail( $to, $subject, $message, $headers );
	} catch ( \Throwable $e ) {
		error_log( 'KI quote mail failed: ' . $e->getMessage() ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions
		return false;
	}
}

function ki_send_quote_emails( $id, $ref, $f, $file_url ) {
	if ( ! ki_mail_available() ) {
		update_post_meta( $id, '_ki_mail', 'skipped: no mail transport' );
		return;
	}
	$company = ki_get( 'company' );
	$to      = ki_get( 'notify_email' ) ? ki_get( 'notify_email' ) : get_option( 'admin_email' );
	$headers = array( 'Content-Type: text/html; charset=UTF-8', 'Reply-To: ' . $f['name'] . ' <' . $f['email'] . '>' );

	$rows = array(
		'Reference'    => $ref,
		'Name'         => $f['name'],
		'Company'      => $f['company'],
		'Email'        => $f['email'],
		'Phone'        => $f['phone'],
		'Needed'       => $f['type'],
		'Product'      => $f['product'],
		'Quantity'     => $f['quantity'],
		'Size / specs' => $f['size'],
		'Required by'  => $f['needed'],
		'Message'      => $f['message'],
		'Artwork'      => $file_url,
	);
	$html = '<div style="font-family:Arial,sans-serif;max-width:620px;margin:auto;border:1px solid #eee;border-radius:10px;overflow:hidden">'
		. '<div style="background:#111;color:#fff;padding:18px 24px;border-bottom:4px solid #F58220"><strong style="font-size:18px">New quotation request</strong><br><span style="color:#F58220">' . esc_html( $ref ) . '</span></div>'
		. '<table style="width:100%;border-collapse:collapse;font-size:14px">';
	foreach ( $rows as $label => $val ) {
		if ( '' === (string) $val ) {
			continue;
		}
		$cell = 'Artwork' === $label ? '<a href="' . esc_url( $val ) . '">Download file</a>' : nl2br( esc_html( $val ) );
		$html .= '<tr><td style="padding:10px 24px;border-top:1px solid #f0f0f0;color:#777;width:140px;vertical-align:top">' . esc_html( $label ) . '</td><td style="padding:10px 24px;border-top:1px solid #f0f0f0">' . $cell . '</td></tr>';
	}
	$html .= '</table><div style="padding:16px 24px;background:#faf7f3;font-size:13px"><a href="' . esc_url( admin_url( 'post.php?post=' . $id . '&action=edit' ) ) . '">Open in WordPress</a></div></div>';
	$sent = ki_safe_mail( $to, '[' . $company . '] New quotation request ' . $ref, $html, $headers );
	update_post_meta( $id, '_ki_mail', $sent ? 'sent' : 'failed' );

	// Acknowledgement to the customer.
	$ack = '<div style="font-family:Arial,sans-serif;max-width:560px;margin:auto;line-height:1.6;color:#222">'
		. '<h2 style="margin:0 0 8px">Thank you, ' . esc_html( $f['name'] ) . '</h2>'
		. '<p>We have received your quotation request <strong>' . esc_html( $ref ) . '</strong> for <strong>' . esc_html( $f['product'] ? $f['product'] : $f['type'] ) . '</strong>. Our team will review the details and get back to you shortly.</p>'
		. '<p style="color:#666;font-size:13px">' . esc_html( $company ) . '<br>' . esc_html( ki_get( 'address' ) ) . '</p></div>';
	$ack_headers = array( 'Content-Type: text/html; charset=UTF-8' );
	ki_safe_mail( $f['email'], 'We received your quotation request ' . $ref, $ack, $ack_headers );
}

/* ----------------------------------------------------------------- Admin */

add_filter( 'manage_ki_quote_posts_columns', function () {
	return array(
		'cb'        => '<input type="checkbox" />',
		'ki_ref'    => 'Reference',
		'ki_who'    => 'Customer',
		'ki_what'   => 'Request',
		'ki_status' => 'Status',
		'date'      => 'Date',
	);
} );

add_action( 'manage_ki_quote_posts_custom_column', function ( $col, $id ) {
	$m = function ( $k ) use ( $id ) {
		return get_post_meta( $id, '_ki_' . $k, true );
	};
	switch ( $col ) {
		case 'ki_ref':
			printf( '<strong><a href="%s">%s</a></strong>', esc_url( get_edit_post_link( $id ) ), esc_html( $m( 'ref' ) ) );
			break;
		case 'ki_who':
			echo '<strong>' . esc_html( $m( 'name' ) ) . '</strong>';
			if ( $m( 'company' ) ) {
				echo '<br>' . esc_html( $m( 'company' ) );
			}
			echo '<br><a href="mailto:' . esc_attr( $m( 'email' ) ) . '">' . esc_html( $m( 'email' ) ) . '</a><br>' . esc_html( $m( 'phone' ) );
			break;
		case 'ki_what':
			echo esc_html( $m( 'product' ) ? $m( 'product' ) : $m( 'type' ) ) . '<br><span style="color:#777">Qty: ' . esc_html( $m( 'quantity' ) ) . '</span>';
			break;
		case 'ki_status':
			$s   = $m( 'status' ) ? $m( 'status' ) : 'new';
			$all = ki_quote_statuses();
			$bg  = array( 'new' => '#F58220', 'contacted' => '#2271b1', 'quoted' => '#7a3fbf', 'won' => '#1a8f4c', 'closed' => '#888' );
			printf( '<span style="background:%s;color:#fff;padding:3px 9px;border-radius:99px;font-size:12px">%s</span>', esc_attr( $bg[ $s ] ?? '#888' ), esc_html( $all[ $s ] ?? $s ) );
			break;
	}
}, 10, 2 );

add_action( 'add_meta_boxes_ki_quote', function () {
	add_meta_box( 'ki_quote_details', 'Request details', 'ki_quote_metabox', 'ki_quote', 'normal', 'high' );
} );

function ki_quote_metabox( $post ) {
	wp_nonce_field( 'ki_quote_admin', 'ki_quote_admin_nonce' );
	$fields = array(
		'name' => 'Name', 'company' => 'Company', 'email' => 'Email', 'phone' => 'Phone', 'type' => 'Needed', 'product' => 'Product',
		'quantity' => 'Quantity', 'size' => 'Size / specs', 'needed' => 'Required by', 'message' => 'Message',
	);
	echo '<table class="form-table"><tbody>';
	foreach ( $fields as $k => $label ) {
		$v = get_post_meta( $post->ID, '_ki_' . $k, true );
		if ( '' === $v ) {
			continue;
		}
		$out = 'email' === $k ? '<a href="mailto:' . esc_attr( $v ) . '">' . esc_html( $v ) . '</a>' : nl2br( esc_html( $v ) );
		echo '<tr><th>' . esc_html( $label ) . '</th><td>' . $out . '</td></tr>'; // phpcs:ignore WordPress.Security.EscapeOutput
	}
	$file = get_post_meta( $post->ID, '_ki_file_url', true );
	if ( $file ) {
		echo '<tr><th>Artwork</th><td><a href="' . esc_url( $file ) . '" target="_blank" rel="noopener">Download attached file</a></td></tr>';
	}
	$wa = ki_phone_digits( get_post_meta( $post->ID, '_ki_phone', true ) );
	if ( $wa ) {
		echo '<tr><th>Reply</th><td><a class="button" href="https://wa.me/' . esc_attr( $wa ) . '" target="_blank" rel="noopener">WhatsApp customer</a></td></tr>';
	}
	$mail = get_post_meta( $post->ID, '_ki_mail', true );
	if ( $mail ) {
		echo '<tr><th>Email notice</th><td>' . esc_html( $mail ) . '</td></tr>';
	}
	$cur = get_post_meta( $post->ID, '_ki_status', true );
	echo '<tr><th><label for="ki_status">Status</label></th><td><select id="ki_status" name="ki_status">';
	foreach ( ki_quote_statuses() as $k => $label ) {
		printf( '<option value="%s"%s>%s</option>', esc_attr( $k ), selected( $cur ? $cur : 'new', $k, false ), esc_html( $label ) );
	}
	echo '</select></td></tr></tbody></table>';
}

add_action( 'save_post_ki_quote', function ( $post_id ) {
	if ( ! isset( $_POST['ki_quote_admin_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ki_quote_admin_nonce'] ) ), 'ki_quote_admin' ) ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) || ! isset( $_POST['ki_status'] ) ) {
		return;
	}
	$s = sanitize_key( wp_unslash( $_POST['ki_status'] ) );
	if ( isset( ki_quote_statuses()[ $s ] ) ) {
		update_post_meta( $post_id, '_ki_status', $s );
	}
} );

// Count of new requests as a bubble on the Quotations menu.
add_action( 'admin_menu', function () {
	global $menu;
	$n = count( get_posts( array(
		'post_type'      => 'ki_quote',
		'post_status'    => 'publish',
		'posts_per_page' => 99,
		'fields'         => 'ids',
		'meta_key'       => '_ki_status',
		'meta_value'     => 'new',
	) ) );
	if ( ! $n || ! is_array( $menu ) ) {
		return;
	}
	foreach ( $menu as $i => $item ) {
		if ( isset( $item[2] ) && 'edit.php?post_type=ki_quote' === $item[2] ) {
			$menu[ $i ][0] .= ' <span class="awaiting-mod">' . (int) $n . '</span>'; // phpcs:ignore WordPress.WP.GlobalVariablesOverride
		}
	}
}, 99 );
