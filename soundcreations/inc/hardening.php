<?php
/**
 * Front-end and admin hardening + lightweight performance cleanup.
 * Conservative by design: nothing here removes functionality the site relies on.
 *
 * @package SoundCreations
 */

if ( defined( 'ABSPATH' ) === false ) {
	exit;
}

/* 1. Trim wp_head cruft (information disclosure + a few bytes). */
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );

/* Strip only the WordPress core version from asset URLs (keeps our filemtime cache-busting). */
function sc_strip_core_ver( $src ) {
	$wpver = get_bloginfo( 'version' );
	if ( $wpver && strpos( $src, 'ver=' . $wpver ) !== false ) {
		$src = remove_query_arg( 'ver', $src );
	}
	return $src;
}
add_filter( 'style_loader_src', 'sc_strip_core_ver', 20 );
add_filter( 'script_loader_src', 'sc_strip_core_ver', 20 );

/* 2. Disable the emoji detection script/styles (removes an inline script + requests). */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );
remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
add_filter(
	'tiny_mce_plugins',
	function ( $plugins ) {
		return is_array( $plugins ) ? array_diff( $plugins, array( 'wpemoji' ) ) : array();
	}
);
add_filter( 'emoji_svg_url', '__return_false' );

/* 3. Disable oEmbed discovery links and the front-end wp-embed.js. */
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
remove_action( 'wp_head', 'wp_oembed_add_host_js' );

/* 1b. Drop the site-wide Comments Feed <link>. The feed itself answers 403 (this
   site takes no comments), so advertising it in wp_head published a broken link
   on every single page -- it was one of only two bad links found in a 32-page
   crawl. feed_links_show_comments_feed is the narrow switch for exactly that one
   link; the main content feed is left advertised and working. */
add_filter( 'feed_links_show_comments_feed', '__return_false' );
add_action(
	'wp_enqueue_scripts',
	function () {
		wp_deregister_script( 'wp-embed' );
	},
	100
);

/* 4. Turn off XML-RPC and pingback (brute-force / DDoS-amplification surface). */
add_filter( 'xmlrpc_enabled', '__return_false' );
add_filter(
	'xmlrpc_methods',
	function ( $methods ) {
		unset( $methods['pingback.ping'], $methods['pingback.extensions.getPingbacks'] );
		return $methods;
	}
);

/* 5. Response headers: strip X-Pingback, add baseline security headers. */
add_filter(
	'wp_headers',
	function ( $headers ) {
		unset( $headers['X-Pingback'] );
		$headers['X-Content-Type-Options'] = 'nosniff';
		$headers['X-Frame-Options']        = 'SAMEORIGIN';
		$headers['Referrer-Policy']        = 'strict-origin-when-cross-origin';
		$headers['Permissions-Policy']     = 'geolocation=(), microphone=(), camera=()';
		$headers['Cross-Origin-Opener-Policy'] = 'same-origin';
		$headers['X-Permitted-Cross-Domain-Policies'] = 'none';
		// A deliberately narrow CSP. It sets NO script-src or style-src: the theme
		// ships inline scripts and Seraphinite Accelerator injects its own inline
		// CSS/JS, so a strict policy would break the site. These three directives
		// close real attack paths (clickjacking, plugin embedding, <base>
		// hijacking) with no risk of blocking legitimate assets.
		$headers['Content-Security-Policy'] = "frame-ancestors 'self'; object-src 'none'; base-uri 'self'";
		if ( is_ssl() ) {
			$headers['Strict-Transport-Security'] = 'max-age=31536000; includeSubDomains';
		}
		return $headers;
	}
);

/* 6. Block user enumeration via ?author=N scans. */
add_action(
	'template_redirect',
	function () {
		if ( is_admin() || is_user_logged_in() ) {
			return;
		}
		if ( isset( $_GET['author'] ) && '' !== $_GET['author'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only guard, value unused.
			wp_safe_redirect( home_url( '/' ), 301 );
			exit;
		}
	}
);

/* 7. Block the REST users endpoints for logged-out visitors (enumeration). */
add_filter(
	'rest_endpoints',
	function ( $endpoints ) {
		if ( is_user_logged_in() ) {
			return $endpoints;
		}
		unset( $endpoints['/wp/v2/users'], $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
		return $endpoints;
	}
);

/* 8. Generic login error (no username/password hinting). */
add_filter(
	'login_errors',
	function () {
		return __( 'Invalid login details. Please try again.', 'soundcreations' );
	}
);

/* 9. Disable the built-in wp-admin theme/plugin file editor (post-compromise pivot). */
if ( defined( 'DISALLOW_FILE_EDIT' ) === false ) {
	define( 'DISALLOW_FILE_EDIT', true );
}

/* 10. Disable comments and trackbacks entirely (spam prevention: the site never uses them). */

// Force comments and pings closed everywhere, ignoring any per-post stored value.
add_filter( 'comments_open', '__return_false', 20 );
add_filter( 'pings_open', '__return_false', 20 );

// Hide any pre-existing comments from the front end.
add_filter( 'comments_array', '__return_empty_array', 20 );

// Reject any comment posted directly (e.g. a bot POSTing to wp-comments-post.php).
add_filter(
	'preprocess_comment',
	function ( $commentdata ) {
		unset( $commentdata );
		wp_die(
			esc_html__( 'Comments are closed.', 'soundcreations' ),
			esc_html__( 'Comments are closed', 'soundcreations' ),
			array( 'response' => 403 )
		);
	},
	0
);

// Remove comment and trackback support from every registered post type.
add_action(
	'init',
	function () {
		foreach ( get_post_types() as $sc_pt ) {
			if ( post_type_supports( $sc_pt, 'comments' ) === true ) {
				remove_post_type_support( $sc_pt, 'comments' );
			}
			if ( post_type_supports( $sc_pt, 'trackbacks' ) === true ) {
				remove_post_type_support( $sc_pt, 'trackbacks' );
			}
		}
	},
	100
);

// Remove the comments REST endpoints (a common spam-injection route).
add_filter(
	'rest_endpoints',
	function ( $endpoints ) {
		foreach ( array_keys( $endpoints ) as $sc_route ) {
			if ( strpos( $sc_route, '/wp/v2/comments' ) === 0 ) {
				unset( $endpoints[ $sc_route ] );
			}
		}
		return $endpoints;
	}
);

// Block the comment feeds so spam can never resurface through them.
add_action(
	'template_redirect',
	function () {
		if ( is_comment_feed() === true ) {
			wp_die(
				esc_html__( 'Comments are closed.', 'soundcreations' ),
				esc_html__( 'Comments are closed', 'soundcreations' ),
				array( 'response' => 403 )
			);
		}
	},
	9
);

// Admin: remove the Comments menu, admin-bar node, dashboard widget and post metaboxes.
add_action(
	'admin_menu',
	function () {
		remove_menu_page( 'edit-comments.php' );
	}
);
add_action(
	'admin_bar_menu',
	function ( $sc_bar ) {
		$sc_bar->remove_node( 'comments' );
	},
	999
);
add_action(
	'wp_dashboard_setup',
	function () {
		remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
	}
);
add_action(
	'add_meta_boxes',
	function () {
		foreach ( get_post_types() as $sc_pt ) {
			remove_meta_box( 'commentsdiv', $sc_pt, 'normal' );
			remove_meta_box( 'commentstatusdiv', $sc_pt, 'normal' );
			remove_meta_box( 'trackbacksdiv', $sc_pt, 'normal' );
		}
	},
	100
);

// Send anyone who reaches the admin Comments screen back to the dashboard.
add_action(
	'admin_init',
	function () {
		global $pagenow;
		if ( 'edit-comments.php' === $pagenow ) {
			wp_safe_redirect( admin_url() );
			exit;
		}
	}
);

/* ============================================================
   11. Login brute-force throttle.

   WordPress ships no limit on failed logins, which makes wp-login.php the
   most attacked endpoint on any install. Five wrong passwords from one IP
   buys a 15-minute lockout; fifteen buys an hour. A successful login clears
   the counter immediately.

   Forwarded IP headers are trivially spoofable, so they are honoured ONLY
   when the connecting peer is a proxy the site owner has declared:

     add_filter( 'sc_trusted_proxies', function ( $ips ) {
         $ips[] = '203.0.113.10';
         return $ips;
     } );
   ============================================================ */

function sc_client_ip() {
	$remote = isset( $_SERVER['REMOTE_ADDR'] ) ? trim( (string) wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	$remote = filter_var( $remote, FILTER_VALIDATE_IP ) ? $remote : '';

	$trusted = apply_filters( 'sc_trusted_proxies', array() );
	if ( '' === $remote || is_array( $trusted ) === false || in_array( $remote, $trusted, true ) === false ) {
		return $remote;
	}
	foreach ( array( 'HTTP_CF_CONNECTING_IP', 'HTTP_X_REAL_IP', 'HTTP_X_FORWARDED_FOR' ) as $header ) {
		if ( empty( $_SERVER[ $header ] ) ) {
			continue;
		}
		$parts = explode( ',', (string) wp_unslash( $_SERVER[ $header ] ) );
		$first = trim( $parts[0] );
		if ( filter_var( $first, FILTER_VALIDATE_IP ) ) {
			return $first;
		}
	}
	return $remote;
}

function sc_login_throttle_key( $ip ) {
	return 'sc_login_fail_' . md5( ( '' === $ip ? 'unknown' : $ip ) . '|' . wp_salt( 'nonce' ) );
}

/* Refuse the attempt before any password is checked. */
add_filter(
	'authenticate',
	function ( $user, $username ) {
		unset( $username );
		$fails = (int) get_transient( sc_login_throttle_key( sc_client_ip() ) );
		if ( $fails >= 15 ) {
			return new WP_Error( 'sc_locked', __( 'Too many failed attempts. Try again in an hour.', 'soundcreations' ) );
		}
		if ( $fails >= 5 ) {
			return new WP_Error( 'sc_locked', __( 'Too many failed attempts. Try again in 15 minutes.', 'soundcreations' ) );
		}
		return $user;
	},
	5,
	2
);

add_action(
	'wp_login_failed',
	function () {
		$key   = sc_login_throttle_key( sc_client_ip() );
		$fails = (int) get_transient( $key ) + 1;
		set_transient( $key, $fails, $fails >= 15 ? HOUR_IN_SECONDS : 15 * MINUTE_IN_SECONDS );
	}
);

add_action(
	'wp_login',
	function () {
		delete_transient( sc_login_throttle_key( sc_client_ip() ) );
	}
);

/* 12. Block REST media enumeration for logged-out visitors. Enquiry
   attachments live under uploads with unguessable names; they should not be
   listable either. */
add_filter(
	'rest_endpoints',
	function ( $endpoints ) {
		if ( is_user_logged_in() ) {
			return $endpoints;
		}
		unset( $endpoints['/wp/v2/media'] );
		return $endpoints;
	}
);
