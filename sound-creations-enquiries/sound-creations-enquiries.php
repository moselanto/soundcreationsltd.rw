<?php
/**
 * Plugin Name:       Sound Creations Enquiries
 * Plugin URI:        https://soundcreationsltd.com/
 * Description:       Professional B2B enquiry system for Sound Creations: consultation, quote, product, dealer, FANE and support forms with conditional fields, lead routing, secure storage, email notification and spam protection.
 * Version:           0.2.0
 * Requires at least: 6.4
 * Requires PHP:      8.0
 * Author:            Sound Creations Ltd
 * License:           GPL-2.0-or-later
 * Text Domain:       sc-enquiries
 *
 * @package SoundCreationsEnquiries
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SC_ENQ_VERSION', '0.2.0' );
define( 'SC_ENQ_DIR', plugin_dir_path( __FILE__ ) );
define( 'SC_ENQ_URI', plugin_dir_url( __FILE__ ) );

require_once SC_ENQ_DIR . 'includes/security.php';
require_once SC_ENQ_DIR . 'includes/antispam.php';
require_once SC_ENQ_DIR . 'includes/forms.php';
require_once SC_ENQ_DIR . 'includes/handler.php';
require_once SC_ENQ_DIR . 'includes/admin.php';
require_once SC_ENQ_DIR . 'includes/mail.php';

/**
 * Capabilities for the enquiry store.
 *
 * Enquiries hold customer PII (name, e-mail, phone, IP). With the default
 * 'post' capability_type any Contributor, Author or Editor could read every
 * enquiry the site has ever received. These map to a dedicated capability set
 * granted only to administrators.
 *
 * create_posts is 'do_not_allow' on purpose: enquiries may only ever arrive
 * through the public form. wp_insert_post() does not check capabilities, so
 * this blocks hand-authoring in wp-admin without affecting submissions.
 */
function sc_enq_caps() {
	return array(
		'edit_post'              => 'edit_sc_enquiry',
		'read_post'              => 'read_sc_enquiry',
		'delete_post'            => 'delete_sc_enquiry',
		'edit_posts'             => 'edit_sc_enquiries',
		'edit_others_posts'      => 'edit_others_sc_enquiries',
		'delete_posts'           => 'delete_sc_enquiries',
		'delete_others_posts'    => 'delete_others_sc_enquiries',
		'publish_posts'          => 'publish_sc_enquiries',
		'read_private_posts'     => 'read_private_sc_enquiries',
		'create_posts'           => 'do_not_allow',
	);
}

/**
 * Grant the enquiry capabilities to the administrator role. Version-gated so
 * the role object is only rewritten when this list actually changes.
 */
function sc_enq_sync_caps() {
	$stamp = get_option( 'sc_enq_caps_version' );
	if ( '2' === $stamp ) {
		return;
	}
	$role = get_role( 'administrator' );
	if ( $role ) {
		foreach ( sc_enq_caps() as $cap ) {
			if ( 'do_not_allow' !== $cap ) {
				$role->add_cap( $cap );
			}
		}
	}
	update_option( 'sc_enq_caps_version', '2', false );
}
add_action( 'admin_init', 'sc_enq_sync_caps' );
register_activation_hook( __FILE__, 'sc_enq_sync_caps' );

// Clear the retention cron when the plugin is deactivated.
register_deactivation_hook(
	__FILE__,
	function () {
		$next = wp_next_scheduled( 'sc_enq_daily_purge' );
		if ( $next ) {
			wp_unschedule_event( $next, 'sc_enq_daily_purge' );
		}
	}
);

// Ensure a private enquiry store exists even if the Core plugin is not active.
add_action(
	'init',
	function () {
		if ( ! post_type_exists( 'sc_enquiry' ) ) {
			register_post_type(
				'sc_enquiry',
				array(
					'labels'          => array( 'name' => 'Enquiries', 'singular_name' => 'Enquiry', 'menu_name' => 'Enquiries' ),
					'public'          => false,
					'show_ui'         => true,
					'menu_icon'       => 'dashicons-email-alt',
					'capability_type' => array( 'sc_enquiry', 'sc_enquiries' ),
					'map_meta_cap'    => true,
					'capabilities'    => sc_enq_caps(),
					'exclude_from_search' => true,
					'supports'        => array( 'title', 'editor', 'custom-fields' ),
				)
			);
		}
	},
	20
);
