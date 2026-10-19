<?php
/**
 * Sound Creations Rwanda child theme.
 *
 * The Rwanda site is an independent WordPress install at
 * soundcreationsltd.rw. It runs the same parent theme and the same
 * two first-party plugins as soundcreationsltd.com, so both sites read as one
 * group brand, while everything below makes this install speak for the
 * Kigali operation: its own contact details, content, titles and schema.
 *
 * @package SoundCreationsRwanda
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SCRW_VERSION', '2.3.17' );
define( 'SCRW_DIR', trailingslashit( get_stylesheet_directory() ) );
define( 'SCRW_GROUP_URL', 'https://soundcreationsltd.com/' );

require_once SCRW_DIR . 'inc/settings-seed.php';
require_once SCRW_DIR . 'inc/content-seed.php';
require_once SCRW_DIR . 'inc/pages-seed.php';
require_once SCRW_DIR . 'inc/projects-seed.php';
require_once SCRW_DIR . 'inc/menu.php';
require_once SCRW_DIR . 'inc/offices.php';
require_once SCRW_DIR . 'inc/fane-contact.php';
require_once SCRW_DIR . 'inc/products-seed.php';
require_once SCRW_DIR . 'inc/product-images.php';
require_once SCRW_DIR . 'inc/fane-products.php';
require_once SCRW_DIR . 'inc/clients.php';
require_once SCRW_DIR . 'inc/hero.php';
require_once SCRW_DIR . 'inc/complete-projects.php';
require_once SCRW_DIR . 'inc/brand-spotlight.php';
require_once SCRW_DIR . 'inc/fane-brands.php';
require_once SCRW_DIR . 'inc/solutions.php';
require_once SCRW_DIR . 'inc/seo.php';
require_once SCRW_DIR . 'inc/seo-boost.php';
require_once SCRW_DIR . 'inc/seo-content.php';
require_once SCRW_DIR . 'inc/seo-health.php';
require_once SCRW_DIR . 'inc/seo-market.php';
require_once SCRW_DIR . 'inc/about-content.php';
require_once SCRW_DIR . 'inc/sitemap-rules.php';
require_once SCRW_DIR . 'inc/schema-services.php';
require_once SCRW_DIR . 'inc/schema-rw.php';
require_once SCRW_DIR . 'inc/seo-ai.php';
require_once SCRW_DIR . 'inc/wording-rw.php';
require_once SCRW_DIR . 'inc/guides-seed.php';
require_once SCRW_DIR . 'inc/schema-tidy.php';
require_once SCRW_DIR . 'inc/analytics.php';
require_once SCRW_DIR . 'inc/elementor.php';
require_once SCRW_DIR . 'inc/section-titles.php';
require_once SCRW_DIR . 'inc/whatsapp-chat.php';
require_once SCRW_DIR . 'inc/login.php';
require_once SCRW_DIR . 'inc/product-uses.php';
require_once SCRW_DIR . 'inc/testimonials.php';
require_once SCRW_DIR . 'inc/csp.php';
require_once SCRW_DIR . 'inc/content-fixes.php';

/* 2.3.5: never show the parent theme's stand-in projects (Nairobi, Dubai) on the Rwanda site. */
add_filter( 'sc_home_projects_fallback', '__return_false' );

/* ---------- 2.3.6: quality-audit fixes ---------- */

/* Hero video: only play a video hosted on this site. The default points at the
   group site, which failed to load (console error, slower first paint); the
   hero then uses its poster image instead. */
add_filter(
	'sc_hero_video',
	function ( $url ) {
		$url = (string) $url;
		if ( '' === $url ) {
			$url = 'https://external.invalid/';
		}
		$own = wp_parse_url( home_url(), PHP_URL_HOST );
		$vid = wp_parse_url( $url, PHP_URL_HOST );
		if ( $vid && $own && strtolower( $vid ) === strtolower( $own ) ) {
			return $url;
		}
		// 2.3.9: the group-site video could not load from this site. Use the
		// web-optimised copy shipped with the theme (same footage, 1280x720,
		// 25 fps, no sound, about 4 MB, fast start).
		$local = 'assets/video/hero.mp4';
		return file_exists( SCRW_DIR . $local ) ? get_stylesheet_directory_uri() . '/' . $local . '?ver=' . SCRW_VERSION : '';
	}
);

/* Contact and consultation pages: list the Kigali office only. Group offices
   are described in text on About, so Google sees one local business here. */
add_filter(
	'sc_offices',
	function ( $offices ) {
		$kigali = array();
		foreach ( (array) $offices as $o ) {
			if ( isset( $o['id'] ) && 'kigali' === $o['id'] ) {
				$o['addr'] = 'KN1 Rd, Muhima, Kigali, Rwanda';
				$kigali[]  = $o;
			}
		}
		return $kigali ? $kigali : $offices;
	}
);

/* One URL per page: the brand pages duplicate /yamaha/ and /fane/ (301), and
   /guides/ opens the Buyer Guides list. */
add_action(
	'template_redirect',
	function () {
		if ( is_admin() ) {
			return;
		}
		if ( is_singular( 'sc_brand' ) ) {
			$map  = array(
				'yamaha' => '/yamaha/',
				'fane'   => '/fane/',
			);
			$slug = (string) get_post_field( 'post_name', get_queried_object_id() );
			if ( isset( $map[ $slug ] ) && get_page_by_path( trim( $map[ $slug ], '/' ) ) ) {
				wp_safe_redirect( home_url( $map[ $slug ] ), 301 );
				exit;
			}
		}
		if ( is_404() ) {
			$path = trim( (string) wp_parse_url( isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '', PHP_URL_PATH ), '/' ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
			$moved = array(
				'solutions/acoustics'          => '/solutions/architectural-acoustics/',
				'solutions/conferencing'       => '/solutions/conference-room-sound-systems/',
				'solutions/system-integration' => '/solutions/installation/',
			);
			if ( isset( $moved[ $path ] ) ) {
				wp_safe_redirect( home_url( $moved[ $path ] ), 301 );
				exit;
			}
			if ( in_array( $path, array( 'guides', 'buyer-guides', 'blog' ), true ) ) {
				$cat = get_category_by_slug( 'buyer-guides' );
				if ( $cat ) {
					wp_safe_redirect( get_category_link( $cat->term_id ), 301 );
					exit;
				}
			}
		}
	},
	5
);


/*
 * 1.9.0 UI layer: mobile drawer + accordion sub-menus, floating WhatsApp chat,
 * mobile dock (Home, Call, Consultation, Menu). Loaded after style.css.
 */
add_action(
	'wp_enqueue_scripts',
	function () {
		$css = file_exists( SCRW_DIR . 'assets/css/scrw-ui.min.css' ) ? 'assets/css/scrw-ui.min.css' : 'assets/css/scrw-ui.css';
		$js  = 'assets/js/scrw-ui.js';
		wp_enqueue_style( 'scrw-ui', get_stylesheet_directory_uri() . '/' . $css, array( 'soundcreations-rwanda' ), (string) filemtime( SCRW_DIR . $css ) );
		wp_enqueue_script( 'scrw-ui', get_stylesheet_directory_uri() . '/' . $js, array(), (string) filemtime( SCRW_DIR . $js ), true );
	},
	31
);

add_action(
	'wp_enqueue_scripts',
	function () {
		wp_enqueue_style(
			'soundcreations-rwanda',
			get_stylesheet_directory_uri() . '/' . ( function_exists( 'sc_min_css' ) ? sc_min_css( get_stylesheet_directory(), 'style.css' ) : 'style.css' ),
			array( 'sc-main' ),
			(string) filemtime( get_stylesheet_directory() . '/style.css' ) // Changes on every upload, so browsers and caches fetch the new CSS.
		);
	},
	30
);

/*
 * Group link band under the footer: internal linking between the Rwanda site
 * and the main group site (proposal section C).
 */
add_action(
	'wp_footer',
	function () {
		echo '<div class="scrw-group-band"><div class="sc-container">'
			. esc_html__( 'Sound Creations Ltd Rwanda is part of the Sound Creations Ltd.', 'soundcreations-rwanda' )
			. ' <a href="' . esc_url( SCRW_GROUP_URL ) . '">' . esc_html__( 'Visit the SCL Kenya website', 'soundcreations-rwanda' ) . '</a>'
			. '</div></div>';
	},
	5
);

/*
 * zlib output compression collision (same workaround as soundcreations-child).
 * The real fix is zlib.output_compression = Off on the host; this is a no-op
 * when that is already the case.
 */
add_action(
	'init',
	function () {
		if ( ! ini_get( 'zlib.output_compression' ) ) {
			return;
		}
		remove_action( 'shutdown', 'wp_ob_end_flush_all', 1 );
		add_action(
			'shutdown',
			function () {
				while ( ob_get_level() > 0 ) {
					@ob_end_flush(); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
				}
			},
			1
		);
	}
);

/* Our Projects page hero: Christian Life Assembly stage (sound, beam lighting and screens by Sound Creations Rwanda). */
add_filter(
	'sc_projects_hero_img',
	function () {
		return get_stylesheet_directory_uri() . '/assets/img/projects-hero-rw.webp';
	}
);
add_filter(
	'sc_projects_hero_alt',
	function () {
		return __( 'Christian Life Assembly, Kigali - stage sound, lighting and screens by Sound Creations Rwanda', 'soundcreations-rwanda' );
	}
);

/* About page top-right photo: Sound Creations Rwanda showroom, KN1 Rd, Muhima, Kigali (guitars, keyboards, mixers and speakers). */
add_filter(
	'sc_about_hero_image',
	function () {
		return get_stylesheet_directory_uri() . '/assets/img/about-hero-showroom.webp';
	}
);

/* Solutions card photos (homepage and /solutions/). Sound & Acoustic Integration: Christian Life Assembly stage, Kigali. */
add_filter(
	'sc_home_solution_img',
	function ( $url, $key ) {
		if ( 'home_sol2_img' === $key ) {
			// Acoustics: Intare Conference Arena main hall, Kigali (timber wall diffusers, ceiling absorbers).
			return get_stylesheet_directory_uri() . '/assets/img/solution-acoustics-rw.webp';
		}
		if ( 'home_sol3_img' === $key ) {
			return get_stylesheet_directory_uri() . '/assets/img/solution-integration-rw.webp';
		}
		return $url;
	},
	10,
	2
);

/*
 * Homepage "What We Do" service card photos (Rwanda).
 *  - Consultancy: MINECOFIN conference room, Kigali.
 *  - Distribution & Dealership: Sound Creations Rwanda showroom, KN1 Rd, Muhima (Yamaha instruments, mixers, speakers).
 *  - Integration: Ntare Louisenlund School auditorium (lighting truss, speakers, projection).
 * These take priority over Service page Featured Images. Remove a line to fall back to the upload.
 */
add_filter(
	'sc_home_service_img',
	function ( $url, $key ) {
		$img = get_stylesheet_directory_uri() . '/assets/img/';
		$map = array(
			'home_svc1_img' => $img . 'projects/minecofin-hd.webp',
			'home_svc2_img' => $img . 'service-distribution-showroom-rw.webp',
			'home_svc3_img' => $img . 'service-integration-ntare-rw.webp',
		);
		return isset( $map[ $key ] ) ? $map[ $key ] : $url;
	},
	10,
	2
);

/*
 * Service page hero photos (Rwanda). Distribution & Dealership: two African
 * professionals shaking hands in a pro-audio showroom (AI-generated image, no
 * real people or brand logos). Takes priority over the page's Featured Image.
 */
add_filter(
	'sc_service_hero_img',
	function ( $url, $kind ) {
		if ( 'distribution' === $kind ) {
			return get_stylesheet_directory_uri() . '/assets/img/service-distribution-hero-rw.webp';
		}
		return $url;
	},
	10,
	2
);

/* Main menu dropdowns: a short description under each sub-item. */
add_filter(
	'nav_menu_item_title',
	function ( $title, $item, $args, $depth ) {
		if ( (int) $depth < 1 || empty( $args->theme_location ) || 'primary' !== $args->theme_location ) {
			return $title;
		}
		$notes = array(
			'/solutions/professional-audio/'            => 'PA, line arrays, mixers and microphones',
			'/solutions/installation/'                  => 'Design, install, calibrate and train',
			'/solutions/conference-room-sound-systems/' => 'Boardrooms and hybrid meetings',
			'/solutions/church-sound-systems/'          => 'Worship sound, lighting and streaming',
			'/solutions/school-and-auditorium-sound/'   => 'School PA, halls and auditoriums',
			'/solutions/hotel-bar-restaurant-sound/'    => 'Zoned music and event sound',
			'/solutions/architectural-acoustics/'       => 'Echo control and soundproofing',
			'/solutions/lighting-solutions/'            => 'Stage, church and event lighting',
			'/solutions/dj-solutions/'                  => 'DJ booths, studios and podcast rooms',
			'/solutions/service-and-backup/'            => 'Maintenance, warranty and backup',
			'/yamaha/'                     => 'Authorised distributor in Rwanda',
			'/fane/'                       => 'Professional loudspeaker components',
			'/brands/'                     => 'Every global brand we supply',
			'/products/'                   => 'Full catalogue with search and filters',
			'/about/'                      => 'Our story, team and Kigali office',
			'/videos/'                     => 'Installations, demos and events',
			'https://soundcreationsltd.com/' => 'Visit the group website',
		);
		$url  = (string) $item->url;
		$path = ( 0 === strpos( $url, 'https://soundcreationsltd.com' ) ) ? 'https://soundcreationsltd.com/' : (string) wp_parse_url( $url, PHP_URL_PATH );
		if ( isset( $notes[ $path ] ) ) {
			$title = '<span class="scrw-sub__t">' . $title . '</span><span class="scrw-sub__d">' . esc_html( $notes[ $path ] ) . '</span>';
		}
		return $title;
	},
	10,
	4
);

/* ==== Speed ===================================================================
 * 1) The theme does not use the block editor on the front end, so WordPress's
 *    block-library / global-styles CSS is dead weight on every page.
 * 2) The homepage hero poster is the largest thing painted first (LCP): ask
 *    the browser to fetch it straight away, before the CSS that references it.
 */
add_action(
	'wp_enqueue_scripts',
	function () {
		if ( is_admin() ) {
			return;
		}
		foreach ( array( 'wp-block-library', 'wp-block-library-theme', 'global-styles', 'classic-theme-styles' ) as $h ) {
			wp_dequeue_style( $h );
		}
	},
	100
);
add_action(
	'wp_head',
	function () {
		if ( is_front_page() === false || function_exists( 'sc_setting' ) === false ) {
			return;
		}
		$poster = sc_setting( 'home_hero_poster', get_template_directory_uri() . '/assets/img/hero-poster.webp' );
		if ( $poster ) {
			echo '<link rel="preload" as="image" href="' . esc_url( $poster ) . '" fetchpriority="high">' . "\n";
		}
	},
	2
);

/* ---------- 2.3.7: server rules (.htaccess) ----------
   Written once per theme version, between "# BEGIN SCRW Performance" markers,
   using WordPress's own insert_with_markers(). Nothing else in .htaccess is
   touched. If the file is not writable, nothing happens (see docs).
   - Long browser cache for versioned CSS, JS, fonts and images (all theme
     files carry ?ver=, so updates still show at once).
   - Block the public WordPress readme.html, license.txt and sample config. */
function scrw_htaccess_rules() {
	return array(
		'<IfModule mod_expires.c>',
		'ExpiresActive On',
		'ExpiresByType text/css "access plus 1 year"',
		'ExpiresByType application/javascript "access plus 1 year"',
		'ExpiresByType text/javascript "access plus 1 year"',
		'ExpiresByType image/webp "access plus 1 year"',
		'ExpiresByType image/png "access plus 1 year"',
		'ExpiresByType image/jpeg "access plus 1 year"',
		'ExpiresByType image/svg+xml "access plus 1 year"',
		'ExpiresByType font/woff2 "access plus 1 year"',
		'</IfModule>',
		'<IfModule mod_headers.c>',
		'<FilesMatch "\\.(css|js|woff2|webp|png|jpe?g|svg)$">',
		'Header set Cache-Control "public, max-age=31536000, immutable"',
		'</FilesMatch>',
		'</IfModule>',
		'<FilesMatch "^(readme\\.html|license\\.txt|wp-config-sample\\.php)$">',
		'<IfModule mod_authz_core.c>',
		'Require all denied',
		'</IfModule>',
		'<IfModule !mod_authz_core.c>',
		'Order allow,deny',
		'Deny from all',
		'</IfModule>',
		'</FilesMatch>',
	);
}

add_action(
	'admin_init',
	function () {
		if ( ! current_user_can( 'manage_options' ) || get_option( 'scrw_htaccess_ver' ) === SCRW_VERSION ) {
			return;
		}
		$file = ABSPATH . '.htaccess';
		if ( ! file_exists( $file ) || ! wp_is_writable( $file ) ) {
			update_option( 'scrw_htaccess_status', 'not-writable', false );
			return;
		}
		if ( ! function_exists( 'insert_with_markers' ) ) {
			require_once ABSPATH . 'wp-admin/includes/misc.php';
		}
		$ok = insert_with_markers( $file, 'SCRW Performance', scrw_htaccess_rules() );
		update_option( 'scrw_htaccess_status', $ok ? 'written' : 'failed', false );
		if ( $ok ) {
			update_option( 'scrw_htaccess_ver', SCRW_VERSION, false );
		}
	}
);

/* Remove the rules again if the theme is switched off. */
add_action(
	'switch_theme',
	function () {
		$file = ABSPATH . '.htaccess';
		if ( file_exists( $file ) && wp_is_writable( $file ) ) {
			if ( ! function_exists( 'insert_with_markers' ) ) {
				require_once ABSPATH . 'wp-admin/includes/misc.php';
			}
			insert_with_markers( $file, 'SCRW Performance', array() );
		}
		delete_option( 'scrw_htaccess_ver' );
	}
);

/* ---------- 2.3.14: speed on About (Elementor) and Brands ---------- */

/* jQuery is only needed by Elementor's footer scripts on the public site, so
   load it in the footer for visitors: it no longer blocks the first paint
   (About page LCP text was waiting on it). Admin and signed-in users unchanged. */
add_action(
	'wp_enqueue_scripts',
	function () {
		if ( is_admin() || is_user_logged_in() ) {
			return;
		}
		$s = wp_scripts();
		foreach ( array( 'jquery', 'jquery-core', 'jquery-migrate' ) as $h ) {
			if ( isset( $s->registered[ $h ] ) ) {
				$s->add_data( $h, 'group', 1 );
			}
		}
	},
	100
);

/* Brands page: start downloading the hero background image straight away. */
add_action(
	'wp_head',
	function () {
		if ( is_post_type_archive( 'sc_brand' ) ) {
			$f = get_template_directory() . '/assets/img/brands/brands-hero.webp';
			if ( file_exists( $f ) ) {
				echo '<link rel="preload" as="image" fetchpriority="high" href="' . esc_url( get_template_directory_uri() . '/assets/img/brands/brands-hero.webp' ) . '">' . "\n";
			}
		}
	},
	1
);
