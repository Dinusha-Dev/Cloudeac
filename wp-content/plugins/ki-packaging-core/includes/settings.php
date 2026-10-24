<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Business settings (Settings > KI Packaging). Stored in one option.
 */
function ki_settings_defaults() {
	return array(
		'company'      => 'K I Packaging Solutions',
		'tagline'      => 'Offset Printing & Packaging Solutions',
		'address'      => '# 411/C/4/1 Kirillawala, Kadawatha',
		'footer_text'  => 'High-quality, cutting-edge offset printing and packaging solutions. Commitment, dedication and customer satisfaction are the heart of how we work.',
		'phone'        => '',
		'phone2'       => '',
		'whatsapp'     => '',
		'email'        => '',
		'notify_email' => '',
		'hours'        => '',
		'facebook'     => '',
		'instagram'    => '',
		'linkedin'     => '',
		'youtube'      => '',
		'tiktok'       => '',
		'map_query'    => '411/C/4/1 Kirillawala, Kadawatha, Sri Lanka',
		'smtp_host'    => '',
		'smtp_port'    => '587',
		'smtp_secure'  => 'tls',
		'smtp_user'    => '',
		'smtp_pass'    => '',
		'mail_from'    => '',
		'wa_message'   => 'Hello K I Packaging Solutions, I would like to know more about your printing and packaging services.',
	);
}

function ki_get( $key, $default = '' ) {
	static $opts = null;
	if ( null === $opts || did_action( 'ki_settings_saved' ) ) {
		$opts = wp_parse_args( (array) get_option( 'ki_settings', array() ), ki_settings_defaults() );
	}
	return isset( $opts[ $key ] ) && '' !== $opts[ $key ] ? $opts[ $key ] : $default;
}

/** Digits-only international number for wa.me and tel: links (Sri Lanka default). */
function ki_phone_digits( $raw ) {
	$d = preg_replace( '/\D+/', '', (string) $raw );
	if ( '' === $d ) {
		return '';
	}
	if ( 0 === strpos( $d, '00' ) ) {
		$d = substr( $d, 2 );
	} elseif ( 0 === strpos( $d, '0' ) ) {
		$d = '94' . substr( $d, 1 );
	}
	return $d;
}

function ki_whatsapp_url( $message = null ) {
	$n = ki_phone_digits( ki_get( 'whatsapp' ) );
	if ( '' === $n ) {
		return '';
	}
	$message = null === $message ? ki_get( 'wa_message' ) : $message;
	return 'https://wa.me/' . $n . ( $message ? '?text=' . rawurlencode( $message ) : '' );
}

function ki_socials() {
	$labels = array(
		'facebook'  => 'Facebook',
		'instagram' => 'Instagram',
		'linkedin'  => 'LinkedIn',
		'youtube'   => 'YouTube',
		'tiktok'    => 'TikTok',
	);
	$out = array();
	foreach ( $labels as $key => $label ) {
		$url = ki_get( $key );
		if ( $url ) {
			$out[ $key ] = array( 'label' => $label, 'url' => $url );
		}
	}
	return $out;
}

function ki_settings_fields() {
	return array(
		'Business'       => array(
			'company'  => array( 'Company name', 'text' ),
			'tagline'  => array( 'Tagline', 'text' ),
			'address'  => array( 'Address', 'text' ),
			'hours'    => array( 'Opening hours', 'text', 'e.g. Mon - Sat: 8.00 am - 5.30 pm' ),
			'footer_text' => array( 'Footer description', 'text', 'Short text shown under the logo in the site footer' ),
		),
		'Contact'        => array(
			'phone'        => array( 'Phone', 'text', 'Shown on the site and used for click-to-call' ),
			'phone2'       => array( 'Second phone', 'text' ),
			'email'        => array( 'Public email', 'email' ),
			'notify_email' => array( 'Quotation notifications go to', 'email', 'Leave blank to use the WordPress admin email' ),
			'whatsapp'     => array( 'WhatsApp number', 'text', 'e.g. 077 123 4567 or +94 77 123 4567. The chat button stays hidden until this is set.' ),
			'wa_message'   => array( 'WhatsApp greeting', 'text', 'Pre-filled message when a visitor opens the chat' ),
		),
		'Social media'   => array(
			'facebook'  => array( 'Facebook URL', 'url' ),
			'instagram' => array( 'Instagram URL', 'url' ),
			'linkedin'  => array( 'LinkedIn URL', 'url' ),
			'youtube'   => array( 'YouTube URL', 'url' ),
			'tiktok'    => array( 'TikTok URL', 'url' ),
		),
		'Email delivery (SMTP)' => array(
			'smtp_host'   => array( 'SMTP host', 'text', 'e.g. mail.yourdomain.com. Needed because this server does not allow PHP mail().' ),
			'smtp_port'   => array( 'SMTP port', 'text', '587 (TLS) or 465 (SSL)' ),
			'smtp_secure' => array( 'Encryption', 'select', array( 'tls' => 'TLS (STARTTLS)', 'ssl' => 'SSL', 'none' => 'None' ) ),
			'smtp_user'   => array( 'SMTP username', 'text', 'Usually the full mailbox address' ),
			'smtp_pass'   => array( 'SMTP password', 'password', 'Leave blank to keep the saved password' ),
			'mail_from'   => array( 'Send emails from', 'email', 'Defaults to the SMTP username' ),
		),
		'Map'            => array(
			'map_query' => array( 'Google Maps search text', 'text', 'Used for the embedded map on the Contact page' ),
		),
	);
}

add_action( 'admin_menu', function () {
	add_options_page( 'KI Packaging', 'KI Packaging', 'manage_options', 'ki-settings', 'ki_settings_page' );
} );

add_action( 'admin_init', function () {
	register_setting( 'ki_settings_group', 'ki_settings', array(
		'type'              => 'array',
		'sanitize_callback' => 'ki_sanitize_settings',
	) );
} );

function ki_sanitize_settings( $input ) {
	$clean = array();
	foreach ( ki_settings_fields() as $fields ) {
		foreach ( $fields as $key => $def ) {
			$val = isset( $input[ $key ] ) ? wp_unslash( $input[ $key ] ) : '';
			switch ( $def[1] ) {
				case 'password':
					$old = (array) get_option( 'ki_settings', array() );
					$clean[ $key ] = '' !== trim( (string) $val ) ? trim( (string) $val ) : ( isset( $old[ $key ] ) ? $old[ $key ] : '' );
					break;
				case 'select':
					$clean[ $key ] = array_key_exists( $val, $def[2] ) ? $val : 'tls';
					break;
				case 'email':
					$clean[ $key ] = sanitize_email( $val );
					break;
				case 'url':
					$clean[ $key ] = esc_url_raw( $val );
					break;
				default:
					$clean[ $key ] = sanitize_text_field( $val );
			}
		}
	}
	do_action( 'ki_settings_saved' );
	return $clean;
}

function ki_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$opts = wp_parse_args( (array) get_option( 'ki_settings', array() ), ki_settings_defaults() );
	echo '<div class="wrap"><h1>KI Packaging &ndash; Business Settings</h1>';
	echo '<form method="post" action="options.php">';
	settings_fields( 'ki_settings_group' );
	foreach ( ki_settings_fields() as $section => $fields ) {
		echo '<h2>' . esc_html( $section ) . '</h2><table class="form-table" role="presentation">';
		foreach ( $fields as $key => $def ) {
			$field = '';
			if ( 'select' === $def[1] ) {
				$field = '<select id="ki_' . esc_attr( $key ) . '" name="ki_settings[' . esc_attr( $key ) . ']">';
				foreach ( $def[2] as $v => $l ) {
					$field .= '<option value="' . esc_attr( $v ) . '"' . selected( $opts[ $key ], $v, false ) . '>' . esc_html( $l ) . '</option>';
				}
				$field .= '</select>';
				$hint   = '';
			} else {
				$val   = 'password' === $def[1] ? '' : $opts[ $key ];
				$ph    = ( 'password' === $def[1] && $opts[ $key ] ) ? ' placeholder="Saved"' : '';
				$field = sprintf( '<input type="%1$s" id="ki_%2$s" name="ki_settings[%2$s]" value="%3$s" class="regular-text" autocomplete="off"%4$s />', esc_attr( $def[1] ), esc_attr( $key ), esc_attr( $val ), $ph ); // phpcs:ignore WordPress.Security.EscapeOutput
				$hint  = ! empty( $def[2] ) ? $def[2] : '';
			}
			printf(
				'<tr><th scope="row"><label for="ki_%1$s">%2$s</label></th><td>%3$s%4$s</td></tr>',
				esc_attr( $key ),
				esc_html( $def[0] ),
				$field, // phpcs:ignore WordPress.Security.EscapeOutput
				$hint ? '<p class="description">' . esc_html( $hint ) . '</p>' : ''
			);
		}
		echo '</table>';
	}
	submit_button();
	echo '</form></div>';
}

/** True when the site can actually send email (SMTP configured, or PHP mail() available). */
function ki_mail_available() {
	return (bool) ki_get( 'smtp_host' ) || function_exists( 'mail' );
}

add_action( 'phpmailer_init', function ( $m ) {
	$host = ki_get( 'smtp_host' );
	if ( ! $host ) {
		return;
	}
	$m->isSMTP();
	$m->Host = $host;
	$m->Port = (int) ki_get( 'smtp_port', 587 );
	$sec     = ki_get( 'smtp_secure', 'tls' );
	if ( 'none' === $sec ) {
		$m->SMTPSecure  = '';
		$m->SMTPAutoTLS = false;
	} else {
		$m->SMTPSecure = $sec;
	}
	$user = ki_get( 'smtp_user' );
	if ( $user ) {
		$m->SMTPAuth = true;
		$m->Username = $user;
		$m->Password = ki_get( 'smtp_pass' );
	}
} );

add_filter( 'wp_mail_from', function ( $from ) {
	if ( ! ki_get( 'smtp_host' ) ) {
		return $from;
	}
	$f = ki_get( 'mail_from' ) ? ki_get( 'mail_from' ) : ki_get( 'smtp_user' );
	return is_email( $f ) ? $f : $from;
} );

add_filter( 'wp_mail_from_name', function ( $name ) {
	return ki_get( 'smtp_host' ) ? ki_get( 'company' ) : $name;
} );

add_action( 'admin_notices', function () {
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( ! $screen || ! in_array( $screen->id, array( 'settings_page_ki-settings', 'edit-ki_quote' ), true ) || ki_mail_available() ) {
		return;
	}
	echo '<div class="notice notice-warning"><p><strong>Quotation emails are off.</strong> This server blocks PHP mail(). Requests are still saved here, but to receive email notifications enter your mailbox details under <a href="' . esc_url( admin_url( 'options-general.php?page=ki-settings' ) ) . '">Settings &gt; KI Packaging &gt; Email delivery (SMTP)</a>.</p></div>';
} );
