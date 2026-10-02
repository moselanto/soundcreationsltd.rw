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

define( 'SCRW_MENU_VERSION', 'rw-menu-5' ); // rw-menu-5: YAMAHA and FANE AFRICA as highlighted top-level items; logo links home.

/*
 * rw-menu-2: same top-level menu as soundcreationsltd.com (flat, no
 * sub-menus). "Products" opens the Rwanda product catalogue; the last item
 * links to the group site, where the Kenya site links to Rwanda.
 */
function scrw_menu_tree() {
	return array(
		array( 'Solutions', '/solutions/', array() ),
		array( 'Products', '/products/', array() ),
		array( 'Projects', '/projects/', array() ),
		array( 'YAMAHA', '/yamaha/', array(), 'scrw-nav-brand scrw-nav-brand--yamaha' ),
		array( 'FANE AFRICA', '/fane/', array(), 'scrw-nav-brand scrw-nav-brand--fane' ),
		array(
			'About',
			'/about/',
			array(
				array( 'About Sound Creations Rwanda', '/about/' ),
				array( 'All brands we carry', '/brands/' ),
				array( 'Videos', '/videos/' ),
				array( 'Sound Creations Kenya ↗', 'https://soundcreationsltd.com/' ),
			),
		),
		array( 'Contact', '/contact/', array() ),
	);
}

function scrw_menu_url( $path ) {
	return ( 0 === strpos( $path, 'http' ) ) ? $path : home_url( $path );
}

function scrw_seed_menu() {
	$locs = get_nav_menu_locations();
	$name = 'Rwanda Main Menu';
	$menu = wp_get_nav_menu_object( $name );
	if ( ! empty( $locs['primary'] ) && ( ! $menu || (int) $locs['primary'] !== (int) $menu->term_id ) ) {
		$items = wp_get_nav_menu_items( (int) $locs['primary'] );
		if ( is_array( $items ) && count( $items ) > 0 ) {
			return; // Team assigned a menu of their own; leave it alone.
		}
	}
	// Our own menu from an earlier version: clear it so it is rebuilt.
	if ( $menu ) {
		$old = wp_get_nav_menu_items( (int) $menu->term_id, array( 'post_status' => 'any' ) );
		foreach ( (array) $old as $item ) {
			wp_delete_post( $item->ID, true );
		}
	}
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
					'menu-item-target'   => ( 0 === strpos( $top[1], 'http' ) ) ? '_blank' : '',
					'menu-item-classes'  => isset( $top[3] ) ? $top[3] : '',
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
