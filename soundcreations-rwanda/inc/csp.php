<?php
/**
 * Strict Content-Security-Policy with nonces + Trusted Types (2.3.8).
 *
 * Adds to the existing CSP header, for visitors on the public site only:
 *   script-src 'nonce-…' 'strict-dynamic' https: 'unsafe-inline';
 *   require-trusted-types-for 'script'
 * - Every <script> tag in the page gets the per-request nonce (output buffer),
 *   so theme, plugin, Rank Math and Google tag scripts keep working. Scripts
 *   they load are trusted through 'strict-dynamic'. Injected scripts without
 *   the nonce are blocked. ('https:' and 'unsafe-inline' are only fallbacks
 *   for very old browsers; modern browsers ignore them when a nonce is set.)
 * - Trusted Types: a default policy (printed first in <head>) cleans HTML
 *   written into the page (removes <script>, on* handlers and javascript:
 *   links) and only allows script URLs from this site and Google's tag and
 *   analytics domains.
 * Not applied in wp-admin, for signed-in users (editors, Elementor, admin bar),
 * in previews, REST or AJAX.
 * Off switch: Settings > General > "Strict script security" (untick), or the
 * filter scrw_strict_csp.
 *
 * @package SoundCreationsRwanda
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function scrw_csp_enabled() {
	static $on = null;
	if ( null !== $on ) {
		return $on;
	}
	$on = true;
	if ( is_admin() || wp_doing_ajax() || wp_doing_cron() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) || ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) ) {
		$on = false;
	} elseif ( is_user_logged_in() ) {
		$on = false;
	} elseif ( isset( $_GET['elementor-preview'] ) || isset( $_GET['preview'] ) || ( function_exists( 'is_customize_preview' ) && is_customize_preview() ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		$on = false;
	} elseif ( '0' === (string) get_option( 'scrw_strict_csp', '1' ) ) {
		$on = false;
	}
	$on = (bool) apply_filters( 'scrw_strict_csp', $on );
	return $on;
}

function scrw_csp_nonce() {
	static $nonce = null;
	if ( null === $nonce ) {
		$nonce = rtrim( strtr( base64_encode( random_bytes( 18 ) ), '+/', '-_' ), '=' );
	}
	return $nonce;
}

/* Header: append to whatever CSP the parent theme / Core already send. */
add_filter(
	'wp_headers',
	function ( $headers ) {
		if ( ! scrw_csp_enabled() ) {
			return $headers;
		}
		$base = isset( $headers['Content-Security-Policy'] ) ? rtrim( trim( (string) $headers['Content-Security-Policy'] ), ';' ) : "object-src 'none'; base-uri 'self'";
		if ( false === stripos( $base, 'object-src' ) ) {
			$base .= "; object-src 'none'";
		}
		if ( false === stripos( $base, 'base-uri' ) ) {
			$base .= "; base-uri 'self'";
		}
		$headers['Content-Security-Policy'] = $base . "; script-src 'nonce-" . scrw_csp_nonce() . "' 'strict-dynamic' https: 'unsafe-inline'; require-trusted-types-for 'script'";
		return $headers;
	},
	99
);

/* Give every <script> tag the nonce. */
function scrw_csp_buffer( $html ) {
	if ( ! is_string( $html ) || '' === $html || false === stripos( $html, '<script' ) ) {
		return $html;
	}
	$nonce = scrw_csp_nonce();
	return (string) preg_replace_callback(
		'/<script\b([^>]*)>/i',
		function ( $m ) use ( $nonce ) {
			if ( preg_match( '/\snonce\s*=/i', $m[1] ) ) {
				return $m[0];
			}
			return '<script nonce="' . $nonce . '"' . $m[1] . '>';
		},
		$html
	);
}

add_action(
	'template_redirect',
	function () {
		if ( scrw_csp_enabled() ) {
			ob_start( 'scrw_csp_buffer' );
		}
	},
	0
);

/* Also tag scripts printed through WordPress APIs (belt and braces). */
add_filter(
	'wp_script_attributes',
	function ( $attr ) {
		if ( scrw_csp_enabled() && empty( $attr['nonce'] ) ) {
			$attr['nonce'] = scrw_csp_nonce();
		}
		return $attr;
	}
);
add_filter(
	'wp_inline_script_attributes',
	function ( $attr ) {
		if ( scrw_csp_enabled() && empty( $attr['nonce'] ) ) {
			$attr['nonce'] = scrw_csp_nonce();
		}
		return $attr;
	}
);

/* Trusted Types default policy: must run before any other script. */
add_action(
	'wp_head',
	function () {
		if ( ! scrw_csp_enabled() ) {
			return;
		}
		$own = wp_json_encode( home_url() );
		echo '<script nonce="' . esc_attr( scrw_csp_nonce() ) . '">(function(){var T=window.trustedTypes;if(!T||!T.createPolicy){return;}var own=new URL(' . $own . ').origin;var hosts=/(^|\.)(googletagmanager\.com|google-analytics\.com|google\.com|gstatic\.com|doubleclick\.net)$/i;function clean(h){return String(h).replace(/<script\b[\s\S]*?<\/script\s*>/gi,"").replace(/<script\b[^>]*>/gi,"").replace(/\son[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/gi,"").replace(/(href|src|action|formaction|xlink:href)\s*=\s*(["\']?)\s*javascript:/gi,"$1=$2#blocked:");}try{T.createPolicy("default",{createHTML:clean,createScript:function(s){return s;},createScriptURL:function(u){var x;try{x=new URL(u,location.href);}catch(e){throw new TypeError("Blocked script URL");}if(x.origin===own||x.origin===location.origin||(x.protocol==="https:"&&hosts.test(x.hostname))){return u;}throw new TypeError("Blocked script URL: "+x.origin);}});}catch(e){}})();</script>' . "\n";
	},
	-1000
);

/* Off switch: Settings > General. */
add_action(
	'admin_init',
	function () {
		register_setting(
			'general',
			'scrw_strict_csp',
			array(
				'type'              => 'string',
				'default'           => '1',
				'sanitize_callback' => function ( $v ) {
					return '1' === (string) $v ? '1' : '0';
				},
			)
		);
		add_settings_field(
			'scrw_strict_csp',
			__( 'Strict script security', 'soundcreations-rwanda' ),
			function () {
				$v = (string) get_option( 'scrw_strict_csp', '1' );
				echo '<input type="hidden" name="scrw_strict_csp" value="0">';
				echo '<label><input type="checkbox" name="scrw_strict_csp" value="1" ' . checked( '1', $v, false ) . '> ' . esc_html__( 'Only allow approved scripts on the public site (recommended). Untick only if a new plugin stops working, then contact your developer.', 'soundcreations-rwanda' ) . '</label>';
			},
			'general'
		);
	}
);
