<?php
/**
 * Custom post types.
 *
 * @package SoundCreationsCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function sc_core_register_post_types() {
	$types = array(
		'sc_project'       => array( 'Project', 'Projects', 'projects', 'dashicons-portfolio' ),
		'sc_product'       => array( 'Product', 'Products', 'products', 'dashicons-products' ),
		'sc_brand'         => array( 'Brand', 'Brands', 'brands', 'dashicons-awards' ),
		'sc_solution'      => array( 'Solution', 'Solutions', 'solutions', 'dashicons-analytics' ),
		'sc_case_study'    => array( 'Case Study', 'Case Studies', 'case-studies', 'dashicons-media-document' ),
		'sc_resource'      => array( 'Video', 'Videos', 'videos', 'dashicons-format-video' ),
		'sc_fane_resource' => array( 'FANE Resource', 'FANE Resources', 'fane-resources', 'dashicons-format-audio' ),
	);

	foreach ( $types as $slug => $data ) {
		list( $single, $plural, $rewrite, $icon ) = $data;
		register_post_type(
			$slug,
			array(
				'labels'       => array(
					'name'          => $plural,
					'singular_name' => $single,
					'add_new_item'  => 'Add New ' . $single,
					'edit_item'     => 'Edit ' . $single,
					'menu_name'     => $plural,
				),
				'public'       => true,
				'has_archive'  => true,
				'menu_icon'    => $icon,
				'rewrite'      => array( 'slug' => $rewrite, 'with_front' => false ),
				'show_in_rest' => true,
				'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes' ),
			)
		);
	}

	// Services: content pages for the homepage "What we do" section. Single URLs at
	// /service/{slug}/ (Consultancy, Distribution & Dealership, Integration, After-Sale Services).
	register_post_type(
		'sc_service',
		array(
			'labels'       => array(
				'name'          => 'Services',
				'singular_name' => 'Service',
				'add_new_item'  => 'Add New Service',
				'edit_item'     => 'Edit Service',
				'menu_name'     => 'Services',
			),
			'public'       => true,
			'has_archive'  => false,
			'menu_icon'    => 'dashicons-superhero',
			'rewrite'      => array( 'slug' => 'service', 'with_front' => false ),
			'show_in_rest' => true,
			'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes' ),
		)
	);

	// Enquiries: private storage so no lead is ever lost.
	register_post_type(
		'sc_enquiry',
		array(
			'labels'          => array( 'name' => 'Enquiries', 'singular_name' => 'Enquiry', 'menu_name' => 'Enquiries' ),
			'public'          => false,
			'show_ui'         => true,
			'menu_icon'       => 'dashicons-email-alt',
			'capability_type' => 'post',
			'supports'        => array( 'title', 'custom-fields' ),
		)
	);
}
add_action( 'init', 'sc_core_register_post_types' );

/**
 * The standalone Products archive was retired. The represented-products list now
 * lives on the Brands archive (relabeled "Products"). Redirect the old /products/
 * archive to /brands/ so existing menu links and bookmarks keep working.
 */
function sc_core_redirect_product_archive() {
	if ( is_post_type_archive( 'sc_product' ) ) {
		$dest = get_post_type_archive_link( 'sc_brand' );
		if ( empty( $dest ) ) {
			$dest = home_url( '/brands/' );
		}
		wp_safe_redirect( $dest, 301 );
		exit;
	}
}
add_action( 'template_redirect', 'sc_core_redirect_product_archive' );

/**
 * The Resources archive was rebranded to "Videos" and now lives at /videos/.
 * 301-redirect the old /resources/ archive and single URLs to the matching
 * /videos/ path so existing links and bookmarks keep working.
 */
function sc_core_redirect_resources_to_videos() {
	if ( is_admin() ) {
		return;
	}
	$uri = isset( $_SERVER['REQUEST_URI'] ) ? (string) wp_unslash( $_SERVER['REQUEST_URI'] ) : '';
	if ( '' === $uri ) {
		return;
	}
	if ( preg_match( '#/resources(/|$)#', $uri ) === 1 ) {
		$new = preg_replace( '#/resources(/|$)#', '/videos$1', $uri, 1 );
		if ( is_string( $new ) ) {
			$host = isset( $_SERVER['HTTP_HOST'] ) ? $_SERVER['HTTP_HOST'] : '';
			$dest = ( is_ssl() ? 'https://' : 'http://' ) . $host . $new;
			wp_safe_redirect( $dest, 301 );
			exit;
		}
	}
}
add_action( 'template_redirect', 'sc_core_redirect_resources_to_videos' );

/**
 * The Brands archive is presented as the "Products" page, so its document <title>
 * should read "Products" rather than the "Brands" post-type label.
 */
function sc_core_products_archive_title( $parts ) {
	if ( is_post_type_archive( 'sc_brand' ) ) {
		$parts['title'] = 'Products';
	}
	return $parts;
}
add_filter( 'document_title_parts', 'sc_core_products_archive_title' );
