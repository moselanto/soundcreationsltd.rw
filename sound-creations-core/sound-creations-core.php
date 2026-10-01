<?php
/**
 * Plugin Name:       Sound Creations Core
 * Plugin URI:        https://soundcreationsltd.com/
 * Description:       Core content types, taxonomies, central business-settings store, and one-click starter setup for the Sound Creations website. Keep functionality here so it survives theme changes.
 * Version:           0.5.25
 * Requires at least: 6.4
 * Requires PHP:      8.0
 * Author:            Sound Creations Ltd
 * License:           GPL-2.0-or-later
 * Text Domain:       sc-core
 *
 * @package SoundCreationsCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SC_CORE_VERSION', '0.5.24' );
define( 'SC_CORE_SEED_VERSION', '9' );
define( 'SC_CORE_DIR', plugin_dir_path( __FILE__ ) );

require_once SC_CORE_DIR . 'includes/post-types.php';
require_once SC_CORE_DIR . 'includes/taxonomies.php';
require_once SC_CORE_DIR . 'includes/settings.php';
require_once SC_CORE_DIR . 'includes/starter-setup.php';
require_once SC_CORE_DIR . 'includes/fields.php';
require_once SC_CORE_DIR . 'includes/seed-catalog.php';
require_once SC_CORE_DIR . 'includes/security.php';
require_once SC_CORE_DIR . 'includes/seo.php';
require_once SC_CORE_DIR . 'includes/seo-graph.php';
require_once SC_CORE_DIR . 'includes/seo-faq.php';
require_once SC_CORE_DIR . 'includes/seo-titles.php';
require_once SC_CORE_DIR . 'includes/setup-wizard.php';
require_once SC_CORE_DIR . 'includes/brand-order.php';

register_activation_hook(
	__FILE__,
	function () {
		sc_core_register_post_types();
		sc_core_register_taxonomies();
		sc_core_ensure_core_pages();
		flush_rewrite_rules();
		update_option( 'sc_core_pages_seed', SC_CORE_SEED_VERSION );
	}
);

register_deactivation_hook(
	__FILE__,
	function () {
		flush_rewrite_rules();
	}
);

// Self-heal: guarantee the core legal pages exist and rewrite rules are fresh even if
// the site was updated without re-running Starter Setup. Runs once per seed-version bump.
add_action(
	'admin_init',
	function () {
		if ( get_option( 'sc_core_pages_seed' ) === SC_CORE_SEED_VERSION ) {
			return;
		}
		sc_core_register_post_types();
		sc_core_register_taxonomies();
		if ( function_exists( 'sc_core_ensure_core_pages' ) ) {
			sc_core_ensure_core_pages();
		}
		flush_rewrite_rules();
		update_option( 'sc_core_pages_seed', SC_CORE_SEED_VERSION );
	}
);
