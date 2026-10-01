<?php
/**
 * Drag-and-drop ordering for Brands (sc_brand) in wp-admin.
 *
 * The /brands/ archive and the homepage partners strip both display brands in
 * their "Order" (menu_order) value. This module lets the team set that order
 * simply by dragging brand rows up and down on the Brands list screen - no code,
 * and it can be re-ordered as often as needed. It also adds an "Order" column and
 * forces the Brands list to show in the saved order.
 *
 * @package SoundCreationsCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * On the Brands list screen, show every brand on one page and in the saved order
 * so a single drag can move a brand anywhere in the full list.
 */
function sc_brand_order_admin_query( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}
	if ( ! function_exists( 'get_current_screen' ) ) {
		return;
	}
	$screen = get_current_screen();
	if ( $screen && 'edit-sc_brand' === $screen->id ) {
		$query->set( 'orderby', 'menu_order title' );
		$query->set( 'order', 'ASC' );
		$query->set( 'posts_per_page', 500 );
	}
}
add_action( 'pre_get_posts', 'sc_brand_order_admin_query' );

/**
 * Add an "Order" column so the saved position is visible at a glance.
 */
function sc_brand_order_columns( $cols ) {
	$out = array();
	foreach ( $cols as $key => $label ) {
		if ( 'title' === $key ) {
			$out['sc_menu_order'] = __( 'Order', 'sc-core' );
		}
		$out[ $key ] = $label;
	}
	return $out;
}
add_filter( 'manage_sc_brand_posts_columns', 'sc_brand_order_columns' );

/**
 * Render the "Order" column value (the brand's menu_order).
 */
function sc_brand_order_column_value( $col, $post_id ) {
	if ( 'sc_menu_order' === $col ) {
		echo (int) get_post_field( 'menu_order', $post_id );
	}
}
add_action( 'manage_sc_brand_posts_custom_column', 'sc_brand_order_column_value', 10, 2 );

/**
 * Load jQuery UI Sortable and our reorder script, only on the Brands list screen.
 */
function sc_brand_order_enqueue( $hook ) {
	if ( 'edit.php' === $hook ) {
		$screen = get_current_screen();
		if ( $screen && 'edit-sc_brand' === $screen->id ) {
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script(
				'sc-brand-order',
				plugins_url( 'assets/brand-order.js', SC_CORE_DIR . 'sound-creations-core.php' ),
				array( 'jquery', 'jquery-ui-sortable' ),
				SC_CORE_VERSION,
				true
			);
			wp_localize_script(
				'sc-brand-order',
				'scBrandOrder',
				array(
					'ajaxUrl' => admin_url( 'admin-ajax.php' ),
					'nonce'   => wp_create_nonce( 'sc_reorder_brands' ),
				)
			);
		}
	}
}
add_action( 'admin_enqueue_scripts', 'sc_brand_order_enqueue' );

/**
 * Persist a new order: write menu_order 0,1,2,... following the posted sequence.
 */
function sc_brand_order_save() {
	if ( ! current_user_can( 'edit_others_posts' ) ) {
		// Brand order changes what every visitor sees site-wide, so it needs more
		// than Contributor-level 'edit_posts'. Editors and administrators only.
		wp_send_json_error( 'forbidden', 403 );
	}
	$nonce = isset( $_POST['_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['_nonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, 'sc_reorder_brands' ) ) {
		wp_send_json_error( 'bad_nonce', 400 );
	}
	$ids   = ( isset( $_POST['ids'] ) && is_array( $_POST['ids'] ) ) ? array_map( 'absint', wp_unslash( $_POST['ids'] ) ) : array();
	$order = 0;
	foreach ( $ids as $id ) {
		if ( $id > 0 && 'sc_brand' === get_post_type( $id ) ) {
			wp_update_post(
				array(
					'ID'         => $id,
					'menu_order' => $order,
				)
			);
			$order++;
		}
	}
	wp_send_json_success( array( 'reordered' => $order ) );
}
add_action( 'wp_ajax_sc_reorder_brands', 'sc_brand_order_save' );

/**
 * A one-line hint above the Brands list so the team knows they can drag to reorder.
 */
function sc_brand_order_hint() {
	$screen = get_current_screen();
	if ( $screen && 'edit-sc_brand' === $screen->id ) {
		echo '<div class="notice notice-info inline"><p>' . esc_html__( 'Drag brand rows up or down to set the order they appear on the website. The new order saves automatically.', 'sc-core' ) . '</p></div>';
	}
}
add_action( 'admin_notices', 'sc_brand_order_hint' );

/**
 * One-time: seed the team's requested initial brand display order.
 *
 * Assigns menu_order by matching brand titles to a priority list; any brand not
 * in the list keeps its relative order after the listed ones. Runs a single time
 * (guarded by the sc_brand_order_seed option) - after this it never runs again,
 * and the drag-and-drop reordering above fully controls the order from then on.
 */
function sc_brand_order_seed_default() {
	if ( '1' === get_option( 'sc_brand_order_seed' ) ) {
		return;
	}
	$priority = array(
		'bose professional',
		'db technologies',
		'shure',
		'allen & heath',
		'asona',
		'rockfon',
		'aid',
		'barrisol',
	);
	$brands = get_posts(
		array(
			'post_type'   => 'sc_brand',
			'post_status' => 'publish',
			'numberposts' => -1,
			'orderby'     => array(
				'menu_order' => 'ASC',
				'title'      => 'ASC',
			),
		)
	);
	if ( empty( $brands ) ) {
		return; // No brands yet - retry on a later admin load.
	}
	$listed = array_flip( $priority );
	$order  = 0;
	// Listed brands first, in the requested order.
	foreach ( $priority as $name ) {
		foreach ( $brands as $b ) {
			if ( strtolower( trim( $b->post_title ) ) === $name ) {
				wp_update_post(
					array(
						'ID'         => $b->ID,
						'menu_order' => $order,
					)
				);
				++$order;
			}
		}
	}
	// Everything else after, keeping its existing relative order.
	foreach ( $brands as $b ) {
		if ( ! isset( $listed[ strtolower( trim( $b->post_title ) ) ] ) ) {
			wp_update_post(
				array(
					'ID'         => $b->ID,
					'menu_order' => $order,
				)
			);
			++$order;
		}
	}
	update_option( 'sc_brand_order_seed', '1' );
}
add_action( 'admin_init', 'sc_brand_order_seed_default' );
