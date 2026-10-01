<?php
/**
 * Sound Creations theme bootstrap.
 * Keep this file thin - logic lives in /inc modules.
 *
 * @package SoundCreations
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SC_THEME_VERSION', (string) ( file_exists( get_template_directory() . '/assets/css/main.css' ) ? filemtime( get_template_directory() . '/assets/css/main.css' ) : '0.5.9' ) );
define( 'SC_THEME_DIR', get_template_directory() );
define( 'SC_THEME_URI', get_template_directory_uri() );

// Disable the built-in theme/plugin file editors (blocks code edits from the dashboard).
if ( defined( 'DISALLOW_FILE_EDIT' ) === false ) {
	define( 'DISALLOW_FILE_EDIT', true );
}

require_once SC_THEME_DIR . '/inc/setup.php';
require_once SC_THEME_DIR . '/inc/enqueue.php';
require_once SC_THEME_DIR . '/inc/template-tags.php';
require_once SC_THEME_DIR . '/inc/shortcodes.php';
require_once SC_THEME_DIR . '/inc/hardening.php';
require_once SC_THEME_DIR . '/inc/customizer.php';
