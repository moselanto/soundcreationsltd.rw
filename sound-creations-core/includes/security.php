<?php
/**
 * Security hardening: enumeration, crawler control, and surface reduction.
 *
 * WHY THIS FILE EXISTS SEPARATELY FROM THE THEME'S hardening.php
 * --------------------------------------------------------------
 * The theme already carries a substantial hardening module, and it is good. This
 * file lives in the PLUGIN for the same reason seo.php does: a control that
 * disappears when somebody switches or rebuilds the theme is not a control. The
 * items here are the ones that must not be lost, plus the fix for a documented
 * control that was silently not working.
 *
 * AUDIT BASIS
 * -----------
 * Live probe of the production site, 30 September 2026. Confirmed working and
 * deliberately NOT duplicated here: all eight response security headers,
 * XML-RPC returning 404, REST users/media/comments routes removed, no exposed
 * .env / .git / debug.log / wp-config backups, no directory listing, and a
 * login form that does not hint at username validity.
 *
 * Also checked and found to be FALSE POSITIVES, recorded so nobody "fixes"
 * them later: /wp-content/plugins/, /wp-content/themes/ and /wp-cron.php all
 * return HTTP 200 with a zero-byte body. That is a blank index.php doing its
 * job, not a directory leak.
 *
 * WHAT WAS ACTUALLY BROKEN
 * ------------------------
 * SECURITY-AUDIT.md section 4 claims "?author=N scans are 301'd to the
 * homepage". The probe showed otherwise:
 *
 *     GET /?author=1  ->  301  ->  /author/moses/   (HTTP 200)
 *
 * So the guard was not merely absent in effect: the redirect chain ACTIVELY
 * CONFIRMED that user ID 1 maps to the username "moses". Cause: the theme hooks
 * template_redirect at the default priority 10, and WordPress core's
 * redirect_canonical() is also on template_redirect at priority 10 but is
 * registered first, so core resolved ?author=1 to the pretty author URL and
 * exited before the guard ever ran. The guard also only inspected
 * $_GET['author'], so the pretty permalink /author/moses/ was never covered at
 * all.
 *
 * A username is half of a credential. Publishing it turns brute force from a
 * two-unknown problem into a one-unknown problem.
 *
 * @package SoundCreationsCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ============================================================
   1. AUTHOR ENUMERATION -- the broken control, properly closed

   Priority 0 so this runs BEFORE core's redirect_canonical().
   Responds 410 Gone rather than redirecting: a redirect to the
   homepage still tells a scanner "that ID exists and maps
   somewhere", whereas 410 ends the conversation. Author archives
   have no legitimate purpose on a single-author corporate site.
   ============================================================ */
add_action(
	'template_redirect',
	function () {
		if ( is_admin() || is_user_logged_in() ) {
			return;
		}

		$is_author_query = isset( $_GET['author'] ) && '' !== $_GET['author']; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only guard, value never used.

		if ( $is_author_query || is_author() ) {
			status_header( 410 );
			nocache_headers();
			// Explicitly noindex: a 410 body should never be indexable.
			header( 'X-Robots-Tag: noindex, nofollow', true );
			wp_die(
				esc_html__( 'This page is not available.', 'soundcreations' ),
				esc_html__( 'Not available', 'soundcreations' ),
				array( 'response' => 410 )
			);
		}
	},
	0
);

/* ============================================================
   2. USER SITEMAP -- WordPress was feeding Google the username

   The probe found /wp-sitemap-users-1.xml returning 200 with
   exactly one entry: /author/moses/. The site was not merely
   leaking the admin username, it was submitting it to search
   engines for indexing. SECURITY-AUDIT.md does not mention this
   vector at all.

   Removing the provider unregisters the users sitemap and drops
   it from the sitemap index in one move.
   ============================================================ */
add_filter(
	'wp_sitemaps_add_provider',
	function ( $provider, $name ) {
		if ( 'users' === $name ) {
			return false;
		}
		return $provider;
	},
	10,
	2
);

// Belt and braces: if a provider is registered elsewhere, expose no users.
add_filter( 'wp_sitemaps_users_query_args', function ( $args ) {
	$args['include'] = array( 0 );
	return $args;
} );

/* ============================================================
   3. REST API ROOT -- stop publishing the route map

   /wp-json/ returned 200 disclosing the site name, every
   registered namespace, and configuration values such as
   page_on_front. Route discovery is the first step of automated
   WordPress attack tooling: it reveals which plugins are present
   and which endpoints exist to probe.

   The site's own front end needs no REST access while logged
   out. This keeps oEmbed working (some embeds rely on it) and
   allows anything a logged-in editor needs, while returning 401
   to anonymous callers.
   ============================================================ */
add_filter(
	'rest_authentication_errors',
	function ( $result ) {
		// Never override an existing decision, success or failure.
		if ( null !== $result && true !== $result ) {
			return $result;
		}
		if ( is_user_logged_in() ) {
			return $result;
		}

		$route = isset( $GLOBALS['wp']->query_vars['rest_route'] )
			? (string) $GLOBALS['wp']->query_vars['rest_route']
			: '';

		/**
		 * Routes that must stay open to anonymous callers.
		 *
		 * Keep this list as short as possible. Add a route only when
		 * something on the public site genuinely breaks without it.
		 *
		 * @param array $allowed Regex fragments matched against the route.
		 */
		$allowed = apply_filters(
			'sc_sec_public_rest_routes',
			array(
				'#^/oembed/#',
			)
		);

		foreach ( $allowed as $pattern ) {
			if ( preg_match( $pattern, $route ) ) {
				return $result;
			}
		}

		return new WP_Error(
			'rest_forbidden',
			__( 'REST API access is restricted.', 'soundcreations' ),
			array( 'status' => 401 )
		);
	},
	20
);

// Stop advertising the REST endpoint in headers, <head> and oEmbed discovery.
remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
remove_action( 'template_redirect', 'rest_output_link_header', 11 );
remove_action( 'xmlrpc_rsd_apis', 'rest_output_rsd' );

/* ============================================================
   4. CRAWLER CONTROL

   The live robots.txt was the WordPress default: it disallowed
   only /wp-admin/ and listed the sitemap. Nothing steered
   crawlers away from endpoints that cost CPU and leak structure,
   and nothing addressed scraper traffic at all.

   IMPORTANT AND DELIBERATE: robots.txt is advisory. It is obeyed
   by Google, Bing and other well-behaved crawlers, and ignored
   completely by the scrapers and vulnerability scanners that
   actually cause harm. It is therefore included here as crawl-
   budget and hygiene management, NOT as a security control. The
   real blocking for hostile traffic belongs at the web-server or
   WAF layer -- see the .htaccess section of the accompanying
   handover document. Treating robots.txt as protection is a
   common and expensive mistake.
   ============================================================ */
add_filter(
	'robots_txt',
	function ( $output ) {
		$lines = array(
			'',
			'# --- Sound Creations: crawl hygiene ---',
			'# Endpoints that cost server time or expose structure without',
			'# ever being a useful search result.',
			'User-agent: *',
			'Disallow: /wp-json/',
			'Disallow: /wp-admin/',
			'Allow: /wp-admin/admin-ajax.php',
			'Disallow: /wp-login.php',
			'Disallow: /xmlrpc.php',
			'Disallow: /wp-cron.php',
			'Disallow: /author/',
			'Disallow: /*?author=',
			'Disallow: /?s=',
			'Disallow: /search/',
			'Disallow: /*?replytocom=',
			'Disallow: /trackback/',
			'Disallow: /*/feed/',
			'Disallow: /comments/feed/',
			'Disallow: /wp-content/uploads/*.pdf$',
			'',
			'# Crawl-delay is honoured by Bing and Yandex; Google ignores it',
			'# and is controlled through Search Console instead.',
			'User-agent: bingbot',
			'Crawl-delay: 2',
			'',
			'User-agent: YandexBot',
			'Crawl-delay: 5',
			'',
			'# Content scrapers and SEO crawlers with no upside for this site.',
			'# These identify themselves honestly, so a robots directive does',
			'# work on them; the malicious ones are handled server-side.',
		);

		$bots = array(
			'AhrefsBot',
			'SemrushBot',
			'DotBot',
			'MJ12bot',
			'BLEXBot',
			'DataForSeoBot',
			'PetalBot',
			'SeekportBot',
			'Barkrowler',
			'ZoominfoBot',
			'serpstatbot',
		);
		foreach ( $bots as $bot ) {
			$lines[] = '';
			$lines[] = 'User-agent: ' . $bot;
			$lines[] = 'Disallow: /';
		}

		return $output . implode( "\n", $lines ) . "\n";
	}
);

/* ============================================================
   5. SURFACE REDUCTION
   ============================================================ */

// Registration is not used by this site. If it were ever switched on in
// Settings, it would become an unauthenticated account-creation endpoint and
// a spam vector, so it is refused in code regardless of the option value.
add_filter( 'option_users_can_register', '__return_zero' );

// Application Passwords are an alternative authentication path to the whole
// REST surface. Nothing here uses them, so the capability is withdrawn.
add_filter( 'wp_is_application_passwords_available', '__return_false' );

/**
 * Deny access to the WordPress installer once the site is installed.
 *
 * /wp-admin/install.php returned 200 with "WordPress Already Installed". Low
 * severity on its own -- it cannot reinstall over a populated database -- but
 * it is a reliable WordPress fingerprint and it should not answer strangers.
 * Blocking it properly belongs in .htaccess (install.php runs before most
 * hooks); this covers the case where the server rules are lost.
 */
add_action(
	'init',
	function () {
		if ( ! isset( $_SERVER['REQUEST_URI'] ) ) {
			return;
		}
		$uri = (string) wp_unslash( $_SERVER['REQUEST_URI'] );
		if ( preg_match( '#/wp-admin/(install|setup-config)\.php#i', $uri ) && ! is_user_logged_in() ) {
			status_header( 403 );
			nocache_headers();
			exit;
		}
	},
	0
);

/* ============================================================
   6. HEADERS the theme does not already set

   Verified present already and NOT repeated here:
   X-Content-Type-Options, X-Frame-Options, Referrer-Policy,
   Permissions-Policy, Cross-Origin-Opener-Policy,
   X-Permitted-Cross-Domain-Policies, Content-Security-Policy
   (frame-ancestors/object-src/base-uri) and HSTS.
   ============================================================ */
add_filter(
	'wp_headers',
	function ( $headers ) {
		// Isolates the browsing context group; pairs with the existing COOP.
		if ( empty( $headers['Cross-Origin-Resource-Policy'] ) ) {
			$headers['Cross-Origin-Resource-Policy'] = 'same-site';
		}
		// Opt out of Google's FLoC/Topics cohort inference.
		//
		// BUG FIXED 30 Sep 2026: this previously only assigned the header when
		// it was EMPTY. The theme's own hardening module always sets
		// Permissions-Policy, so the guard was always false and interest-cohort
		// was never actually sent -- verified by live probe, which returned
		// 'geolocation=(), microphone=(), camera=()' with no cohort directive.
		// A guard written to avoid clobbering another module's value instead
		// silently disabled the control. Now appends to whatever is already
		// there rather than competing with it.
		$pp = isset( $headers['Permissions-Policy'] ) ? (string) $headers['Permissions-Policy'] : '';
		if ( '' === trim( $pp ) ) {
			$headers['Permissions-Policy'] = 'geolocation=(), microphone=(), camera=(), interest-cohort=()';
		} elseif ( false === stripos( $pp, 'interest-cohort' ) ) {
			$headers['Permissions-Policy'] = rtrim( $pp, ' ,' ) . ', interest-cohort=()';
		}
		return $headers;
	},
	20
);

/**
 * Send X-Robots-Tag on responses that must never be indexed.
 *
 * Complements the wp_robots meta tag: a meta tag only works in an HTML body,
 * so it cannot protect a feed, a JSON response or an attachment.
 */
add_action(
	'template_redirect',
	function () {
		if ( is_feed() || is_search() || is_404() || is_attachment() ) {
			header( 'X-Robots-Tag: noindex, follow', true );
		}
	},
	1
);
