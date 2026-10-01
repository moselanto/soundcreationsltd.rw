<?php
/**
 * Rwanda primary menu.
 *
 * Without an assigned menu the parent theme falls back to the Kenya menu
 * (Kenya solution pages, Videos, and a "Rwanda" link to the old subdomain).
 * This builds a Rwanda menu with proper sub-menus and assigns it to the
 * Primary location, once, and only when no menu with items is assigned there.
 * Edit it afterwards in Appearance -> Menus.
 *
 * @package SoundCreationsRwanda
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SCRW_MENU_VERSION', 'rw-menu-1' );

/** Label => array( path, children ). */
function scrw_menu_tree() {
	return array(
		array( 'Solutions', '/solutions/', array(
			array( 'All Solutions', '/solutions/' ),
			array( 'DJ Solutions', '/solutions/dj-solutions/' ),
			array( 'Lighting Solutions', '/solutions/lighting-solutions/' ),
			array( 'Studio Solutions', '/solutions/studio-solutions/' ),
			array( 'Architectural Acoustics', '/solutions/architectural-acoustics/' ),
			array( 'Service and Backup', '/solutions/service-and-backup/' ),
		) ),
		array( 'Brands', '/brands/', array(
			array( 'All Brands & Products', '/brands/' ),
			array( 'Yamaha - Authorised Distributor', '/brands/yamaha/' ),
			array( 'dB Technologies', '/brands/db-technologies/' ),
			array( 'Shure', '/brands/shure/' ),
			array( 'Bose Professional', '/brands/bose-professional/' ),
			array( 'Allen & Heath', '/brands/allen-heath/' ),
			array( 'FANE', '/fane/' ),
		) ),
		array( 'Services', '/service/consultancy/', array(
			array( 'Consultancy & Design', '/service/consultancy/' ),
			array( 'Distribution & Dealership', '/service/distribution-dealership/' ),
			array( 'Integration', '/service/integration/' ),
			array( 'After-Sale Services', '/service/after-sale-services/' ),
		) ),
		array( 'Projects', '/projects/', array() ),
		array( 'About Us', '/about/', array(
			array( 'About Sound Creations Rwanda', '/about/' ),
			array( 'Request a Consultation', '/request-a-consultation/' ),
			array( 'Request a Quote', '/request-a-quote/' ),
			array( 'Sound Creations Group', 'https://soundcreationsltd.com/' ),
		) ),
		array( 'Contact', '/contact/', array() ),
	);
}

function scrw_menu_url( $path ) {
	return ( 0 === strpos( $path, 'http' ) ) ? $path : home_url( $path );
}

function scrw_seed_menu() {
	$locs = get_nav_menu_locations();
	if ( ! empty( $locs['primary'] ) ) {
		$items = wp_get_nav_menu_items( (int) $locs['primary'] );
		if ( is_array( $items ) && count( $items ) > 0 ) {
			return; // Team already has a primary menu; leave it alone.
		}
	}

	$name = 'Rwanda Main Menu';
	$menu = wp_get_nav_menu_object( $name );
	$id   = $menu ? (int) $menu->term_id : (int) wp_create_nav_menu( $name );
	if ( $id < 1 ) {
		return;
	}
	$existing = wp_get_nav_menu_items( $id );
	if ( ! is_array( $existing ) || 0 === count( $existing ) ) {
		$pos = 0;
		foreach ( scrw_menu_tree() as $top ) {
			$parent = wp_update_nav_menu_item(
				$id,
				0,
				array(
					'menu-item-title'    => $top[0],
					'menu-item-url'      => scrw_menu_url( $top[1] ),
					'menu-item-type'     => 'custom',
					'menu-item-status'   => 'publish',
					'menu-item-position' => ++$pos,
				)
			);
			if ( is_wp_error( $parent ) ) {
				continue;
			}
			foreach ( $top[2] as $sub ) {
				$ext = ( 0 === strpos( $sub[1], 'http' ) );
				wp_update_nav_menu_item(
					$id,
					0,
					array(
						'menu-item-title'     => $sub[0],
						'menu-item-url'       => scrw_menu_url( $sub[1] ),
						'menu-item-type'      => 'custom',
						'menu-item-status'    => 'publish',
						'menu-item-parent-id' => (int) $parent,
						'menu-item-position'  => ++$pos,
						'menu-item-target'    => $ext ? '_blank' : '',
					)
				);
			}
		}
	}
	$locs            = is_array( $locs ) ? $locs : array();
	$locs['primary'] = $id;
	set_theme_mod( 'nav_menu_locations', $locs );
}

add_action(
	'admin_init',
	function () {
		if ( get_option( 'scrw_menu_ver' ) === SCRW_MENU_VERSION ) {
			return;
		}
		scrw_seed_menu();
		update_option( 'scrw_menu_ver', SCRW_MENU_VERSION );
	},
	45
);
