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

define( 'SCRW_VERSION', '1.1.0' );
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
require_once SCRW_DIR . 'inc/solutions.php';
require_once SCRW_DIR . 'inc/seo.php';
require_once SCRW_DIR . 'inc/analytics.php';
require_once SCRW_DIR . 'inc/elementor.php';

add_action(
	'wp_enqueue_scripts',
	function () {
		wp_enqueue_style(
			'soundcreations-rwanda',
			get_stylesheet_uri(),
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
