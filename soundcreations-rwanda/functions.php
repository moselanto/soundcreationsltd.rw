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
require_once SCRW_DIR . 'inc/projects-seed.php';
require_once SCRW_DIR . 'inc/menu.php';
require_once SCRW_DIR . 'inc/products-seed.php';
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
			SCRW_VERSION
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
