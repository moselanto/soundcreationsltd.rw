<?php
/**
 * Asset loading.
 *
 * @package SoundCreations
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'wp_enqueue_scripts',
	function () {
		// Design tokens + @font-face are inlined in <head> (see below) to remove two
		// render-blocking requests and apply the preloaded fonts immediately.
		wp_enqueue_style( 'sc-main', SC_THEME_URI . '/assets/css/main.css', array(), SC_THEME_VERSION );
		wp_enqueue_style( 'sc-content', SC_THEME_URI . '/assets/css/content.css', array( 'sc-main' ), SC_THEME_VERSION );

		wp_enqueue_script( 'sc-theme', SC_THEME_URI . '/assets/js/theme.js', array(), SC_THEME_VERSION, true );
		wp_enqueue_script( 'sc-map', SC_THEME_URI . '/assets/js/map.js', array(), SC_THEME_VERSION, true );
		wp_enqueue_script( 'sc-loader', SC_THEME_URI . '/assets/js/loader.js', array(), SC_THEME_VERSION, true );
	},
	20
);

// Set the saved / default (dark) colour theme before first paint to avoid a flash.
add_action(
	'wp_head',
	function () {
		echo "<script>(function(){try{var t=localStorage.getItem('sc-theme');if(t!=='light'&&t!=='dark'){t='dark';}document.documentElement.setAttribute('data-theme',t);}catch(e){document.documentElement.setAttribute('data-theme','dark');}})();</script>\n";
	},
	0
);

// Preload the primary self-hosted fonts and the LCP hero image for faster first paint.
add_action(
	'wp_head',
	function () {
		$u = SC_THEME_URI;
		echo '<link rel="preload" as="font" type="font/woff2" crossorigin href="' . esc_url( $u . '/assets/fonts/inter-600.woff2' ) . '">' . "\n";
		if ( is_front_page() ) {
			echo '<link rel="preload" as="image" fetchpriority="high" href="' . esc_url( $u . '/assets/img/hero-poster.webp' ) . '">' . "\n";
		} elseif ( is_page( 'about' ) ) {
			// Must match the About hero fallback in page-about.php. If these drift,
			// the browser high-priority-fetches an image the page never paints and
			// the real LCP image waits behind it.
			echo '<link rel="preload" as="image" fetchpriority="high" href="' . esc_url( $u . '/assets/img/about-citam.webp' ) . '">' . "\n";
		}
	},
	1
);


// Inline critical CSS (design tokens + @font-face) so above-the-fold content can
// be styled and painted without waiting for two extra render-blocking stylesheets.
// Relative font URLs are rewritten to absolute so they resolve from the document.
add_action(
	'wp_head',
	function () {
		$sc_css = '';
		foreach ( array( 'tokens.css', 'fonts.css', 'loader.css' ) as $sc_file ) {
			$sc_path = SC_THEME_DIR . '/assets/css/' . $sc_file;
			if ( is_readable( $sc_path ) ) {
				$sc_css .= file_get_contents( $sc_path );
			}
		}
		if ( '' === $sc_css ) {
			return;
		}
		$sc_css = str_replace( '../fonts/', SC_THEME_URI . '/assets/fonts/', $sc_css );
		echo '<style id="sc-critical-inline">' . $sc_css . '</style>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- first-party CSS.
	},
	2
);

/* Brand loader bootstrap. Inline and tiny because it must run before first
   paint; all it does is put one class on <html>.

   Three escape hatches, because a loader that can trap content is a bug:
     - no JS          -> class never added, overlay never shows
     - reduced motion -> skipped entirely
     - already seen   -> skipped for the rest of the session, so only the
                         first navigation of a visit ever pays for it
   MINIMUM DISPLAY TIME: the site paints so fast that DOMContentLoaded often
   fires within ~200ms, which made the overlay flash imperceptibly -- it read
   as a glitch rather than a brand moment. It now stays for a floor of 700ms
   once shown. That is a deliberate, small cost paid ONCE per visit.

   The 2.5s timeout is a hard failsafe: even if DOMContentLoaded never fires,
   the overlay still lifts. */
add_action(
	'wp_head',
	function () {
		echo "<script>(function(){try{if(sessionStorage.getItem('sc-preload')==='1'){return;}if(window.matchMedia&&window.matchMedia('(prefers-reduced-motion: reduce)').matches){return;}sessionStorage.setItem('sc-preload','1');}catch(e){}var h=document.documentElement;var t0=Date.now();h.className+=' sc-loading';var off=function(){h.className=h.className.replace(/\\s*sc-loading/,'');};var d=function(){var w=700-(Date.now()-t0);if(w<0){w=0;}window.setTimeout(off,w);};if(document.readyState==='loading'){document.addEventListener('DOMContentLoaded',d);}else{d();}window.setTimeout(off,2500);})();</script>\n";
	},
	3
);

/* Performance: defer the two front-end scripts (they already load in the footer). */
function sc_defer_scripts( $tag, $handle ) {
	if ( in_array( $handle, array( 'sc-theme', 'sc-map', 'sc-loader' ), true ) && strpos( $tag, ' defer' ) === false ) {
		$tag = str_replace( ' src=', ' defer src=', $tag );
	}
	return $tag;
}
add_filter( 'script_loader_tag', 'sc_defer_scripts', 10, 2 );


/* ============================================================
   Navigation speed: Speculation Rules API.

   Chromium prerenders a page when the visitor hovers or starts pressing a
   link, so the next navigation paints from memory and feels instant. Falls
   back silently to normal navigation in browsers without support.

   Patterns are derived from the real site path via home_url(), so they stay
   correct wherever WordPress is installed. The site now runs at the domain
   root (it was previously served from the /newwebsite/ subdirectory); deriving
   the base keeps hardcoded '/wp-admin/*' style patterns out of the codebase.

   Excluded: wp-admin, wp-login, wp-json, wp-comments-post, the enquiry POST
   endpoint, and anything carrying a query string, so nothing with a side
   effect is ever speculatively fetched. Logged-in users are skipped entirely
   (their pages are personalised and uncacheable).
   ============================================================ */
add_action(
	'wp_head',
	function () {
		if ( is_user_logged_in() ) {
			return;
		}

		$base = wp_parse_url( home_url( '/' ), PHP_URL_PATH );
		if ( is_string( $base ) === false || '' === $base ) {
			$base = '/';
		}
		$base = trailingslashit( $base );

		$rules = array(
			'prerender' => array(
				array(
					'where'     => array(
						'and' => array(
							array( 'href_matches' => $base . '*' ),
							array(
								'not' => array(
									'href_matches' => array(
										$base . 'wp-admin/*',
										$base . 'wp-login.php',
										$base . 'wp-json/*',
										$base . 'wp-comments-post.php',
										$base . '*\?*',
									),
								),
							),
						),
					),
					'eagerness' => 'moderate',
				),
			),
		);

		echo '<script type="speculationrules">' . wp_json_encode( $rules ) . '</script>' . "\n";
	},
	3
);
