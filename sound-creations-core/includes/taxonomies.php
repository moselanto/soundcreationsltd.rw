<?php
/**
 * Custom taxonomies.
 *
 * @package SoundCreationsCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function sc_core_register_taxonomies() {
	$taxes = array(
		'sc_product_category' => array( 'Product Category', 'Product Categories', 'product-category', array( 'sc_product' ) ),
		'sc_brand_tax'        => array( 'Brand', 'Brands', 'product-brand', array( 'sc_product', 'sc_project' ) ),
		'sc_industry'         => array( 'Industry', 'Industries', 'industry', array( 'sc_project', 'sc_solution', 'sc_case_study' ) ),
		'sc_application'      => array( 'Application', 'Applications', 'application', array( 'sc_product', 'sc_solution' ) ),
		'sc_project_type'     => array( 'Project Type', 'Project Types', 'project-type', array( 'sc_project', 'sc_case_study' ) ),
		'sc_location'         => array( 'Location', 'Locations', 'location', array( 'sc_project', 'sc_case_study' ) ),
		'sc_solution_area'    => array( 'Solution Area', 'Solution Areas', 'solution-area', array( 'sc_solution' ) ),
	);

	foreach ( $taxes as $slug => $data ) {
		list( $single, $plural, $rewrite, $objects ) = $data;
		register_taxonomy(
			$slug,
			$objects,
			array(
				'labels'            => array( 'name' => $plural, 'singular_name' => $single, 'menu_name' => $plural ),
				'hierarchical'      => true,
				'public'            => true,
				'show_admin_column' => true,
				'show_in_rest'      => true,
				'rewrite'           => array( 'slug' => $rewrite, 'with_front' => false ),
			)
		);
	}
}
add_action( 'init', 'sc_core_register_taxonomies' );
