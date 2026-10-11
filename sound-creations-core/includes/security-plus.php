<?php
/**
 * Production security additions (audit of 11 Oct 2026).
 *
 * Lives in the Core plugin so the controls survive a theme change. Everything
 * here is additive: it does not remove or replace any control in security.php
 * or the theme's hardening.php.
 *
 *  1. Error display off in production (no file paths leaked on a fatal error).
 *  2. HTTPS everywhere: plain-HTTP front-end requests are 301'd to HTTPS.
 *  3. Session expiry: shorter login cookies (12 h, or 7 days with "Remember me").
 *  4. Password policy: minimum 12 characters, not the username or email.
 *  5. CORS: REST responses only allow this site's own origin.
 *  6. CSP: adds form-action 'self' and upgrade-insecure-requests.
 *  7. Placeholder guard: editorial markers such as [VERIFY] never reach visitors.
 *
 * Every value can be changed with a filter, so nothing is hardcoded.
 *
 * @package SoundCreationsCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* 1. Never print PHP errors to visitors unless debugging is switched on. */
if ( ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) && function_exists( 'ini_set' ) ) {
	@ini_set( 'display_errors', '0' ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged, WordPress.PHP.IniSet.display_errors_Disallowed
}

/* 2. HTTPS everywhere (front end; wp-admin is covered by FORCE_SSL_ADMIN). */
add_action(
	'template_redirect',
	function () {
		if ( is_ssl() || ! apply_filters( 'sc_sec_force_https', true ) ) {
			return;
		}
		if ( 0 !== strpos( home_url( '/' ), 'https://' ) ) {
			return; // Site is not configured for HTTPS; do not create a loop.
		}
		// Behind a proxy that terminates TLS, trust its declaration.
		if ( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && 'https' === strtolower( (string) wp_unslash( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) ) ) {
			return;
		}
		if ( isset( $_SERVER['HTTP_CF_VISITOR'] ) && false !== stripos( (string) wp_unslash( $_SERVER['HTTP_CF_VISITOR'] ), 'https' ) ) {
			return; // Cloudflare already served HTTPS to the visitor.
		}
		$uri = isset( $_SERVER['REQUEST_URI'] ) ? (string) wp_unslash( $_SERVER['REQUEST_URI'] ) : '/';
		wp_safe_redirect( home_url( $uri ), 301 );
		exit;
	},
	0
);

/* 3. Session expiry. */
add_filter(
	'auth_cookie_expiration',
	function ( $length, $user_id, $remember ) {
		unset( $user_id );
		$short = (int) apply_filters( 'sc_sec_session_hours', 12 ) * HOUR_IN_SECONDS;
		$long  = (int) apply_filters( 'sc_sec_session_remember_days', 7 ) * DAY_IN_SECONDS;
		$want  = $remember ? $long : $short;
		return ( $want > 0 && $want < $length ) ? $want : $length;
	},
	10,
	3
);

/* 4. Password policy for new and changed passwords. WordPress already stores
   passwords as salted hashes (bcrypt on current versions); this stops weak
   ones being chosen in the first place. */
function sc_sec_password_problem( $pass, $user = null ) {
	$pass = (string) $pass;
	$min  = (int) apply_filters( 'sc_sec_password_min_length', 12 );
	if ( strlen( $pass ) < $min ) {
		/* translators: %d: minimum password length. */
		return sprintf( __( 'Password must be at least %d characters long.', 'soundcreations' ), $min );
	}
	if ( $user && isset( $user->user_login ) ) {
		$lower = strtolower( $pass );
		if ( strtolower( (string) $user->user_login ) === $lower || strtolower( (string) $user->user_email ) === $lower ) {
			return __( 'Password must not be your username or email address.', 'soundcreations' );
		}
	}
	return '';
}

add_action(
	'user_profile_update_errors',
	function ( $errors, $update, $user ) {
		unset( $update );
		if ( empty( $_POST['pass1'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing -- core verifies the profile nonce.
			return;
		}
		$msg = sc_sec_password_problem( wp_unslash( $_POST['pass1'] ), $user ); // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- passwords must not be altered.
		if ( '' !== $msg ) {
			$errors->add( 'sc_weak_password', $msg );
		}
	},
	10,
	3
);

add_action(
	'validate_password_reset',
	function ( $errors, $user ) {
		if ( empty( $_POST['pass1'] ) || is_wp_error( $user ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing -- core verifies the reset key.
			return;
		}
		$msg = sc_sec_password_problem( wp_unslash( $_POST['pass1'] ), $user ); // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		if ( '' !== $msg ) {
			$errors->add( 'sc_weak_password', $msg );
		}
	},
	10,
	2
);

/* 5. CORS: WordPress reflects any Origin on REST responses. Allow only this
   site (and any origins added through the filter). */
add_action(
	'rest_api_init',
	function () {
		remove_filter( 'rest_pre_serve_request', 'rest_send_cors_headers' );
		add_filter(
			'rest_pre_serve_request',
			function ( $served ) {
				$origin = get_http_origin();
				if ( ! $origin ) {
					return $served;
				}
				$site    = wp_parse_url( home_url() );
				$self    = $site['scheme'] . '://' . $site['host'] . ( isset( $site['port'] ) ? ':' . $site['port'] : '' );
				$allowed = (array) apply_filters( 'sc_sec_cors_origins', array( $self ) );
				if ( in_array( $origin, $allowed, true ) ) {
					header( 'Access-Control-Allow-Origin: ' . esc_url_raw( $origin ) );
					header( 'Access-Control-Allow-Credentials: true' );
					header( 'Vary: Origin', false );
				}
				return $served;
			}
		);
	},
	15
);

/* 6. Tighten the existing CSP without adding script/style rules that could
   break Elementor or the cache plugin. */
add_filter(
	'wp_headers',
	function ( $headers ) {
		$csp = isset( $headers['Content-Security-Policy'] ) ? trim( (string) $headers['Content-Security-Policy'], " ;" ) : '';
		foreach ( array( "form-action 'self'", 'upgrade-insecure-requests' ) as $directive ) {
			$name = strtok( $directive, ' ' );
			if ( false === stripos( $csp, $name ) ) {
				$csp .= ( '' === $csp ? '' : '; ' ) . $directive;
			}
		}
		if ( '' !== $csp ) {
			$headers['Content-Security-Policy'] = $csp;
		}
		return $headers;
	},
	30
);

/* 7. Placeholder guard: seeded stubs carry editor-only markers. Strip the
   marker sentence from anything shown to visitors; editors still see it in
   wp-admin so they know to complete the text. */
function sc_sec_strip_placeholders( $text ) {
	if ( ! is_string( $text ) || ( false === strpos( $text, '[VERIFY]' ) && false === strpos( $text, '[CONTENT TO BE CONFIRMED' ) && false === strpos( $text, '[insert' ) ) ) {
		return $text;
	}
	$text = preg_replace( '/\s*\[VERIFY\][^<\[]*?(?:before publishing\.|$)/i', '', $text );
	$text = preg_replace( '/\s*\[(?:CONTENT TO BE CONFIRMED|insert)[^\]]*\]/i', '', $text );
	return $text;
}
foreach ( array( 'the_content', 'the_excerpt', 'get_the_excerpt' ) as $sc_sec_hook ) {
	add_filter(
		$sc_sec_hook,
		function ( $text ) {
			return is_admin() ? $text : sc_sec_strip_placeholders( $text );
		},
		99
	);
}
unset( $sc_sec_hook );
