<?php
/**
 * Core pages for the Rwanda site.
 *
 * The Core plugin only creates About, Contact, Request a Consultation and
 * Request a Quote when someone runs its Starter Setup screen, and the Rwanda
 * theme marks Core's legal-page version as done (to keep Kenya-law copy off
 * this site). On a fresh install that left those URLs returning 404. This
 * creates each page if it is missing, or republishes it if it was left in
 * draft or trash, then refreshes permalinks once. The theme's page-about.php,
 * page-contact.php and page-request-a-consultation.php supply the layouts.
 *
 * @package SoundCreationsRwanda
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SCRW_PAGES_VERSION', 'rw-pages-3' ); // rw-pages-3: adds the Yamaha page.

/** slug => array( title, content ). */
function scrw_core_pages() {
	$pages = array(
		'about'                  => array( 'About', '<p>Sound Creations Ltd Rwanda is the Kigali operation of the Sound Creations Ltd group, delivering professional audio, visual, lighting and acoustic solutions across Rwanda.</p>' ),
		'contact'                => array( 'Contact', '<p>Talk to our Kigali team about your project.</p>' ),
		'request-a-consultation' => array( 'Request a Consultation', '<p>Tell us about your space and application and our Kigali team will help you specify the right system.</p>' ),
		'fane'                   => array( 'FANE', '<p>FANE professional loudspeaker components, available in Rwanda from Sound Creations Ltd Rwanda.</p>' ),
		'yamaha'                 => array( 'Yamaha', '<p>Sound Creations Ltd Rwanda is the authorised Yamaha distributor in Rwanda.</p>' ),
		'request-a-quote'        => array( 'Request a Quote', '<p>Tell us what you need and our Kigali sales team will come back with pricing and availability.</p>[sc_enquiry_form type="quote"]' ),
	);
	if ( function_exists( 'scrw_legal_pages' ) ) {
		$titles = array( 'privacy-policy' => 'Privacy Policy', 'terms' => 'Terms and Conditions' );
		foreach ( scrw_legal_pages() as $slug => $html ) {
			if ( isset( $titles[ $slug ] ) ) {
				$pages[ $slug ] = array( $titles[ $slug ], $html );
			}
		}
	}
	return $pages;
}

function scrw_seed_core_pages() {
	foreach ( scrw_core_pages() as $slug => $pg ) {
		$page = get_page_by_path( $slug, OBJECT, 'page' );
		if ( null === $page ) {
			// get_page_by_path() misses trashed pages (slug becomes "x__trashed").
			$trashed = get_posts(
				array(
					'post_type'   => 'page',
					'post_status' => 'trash',
					'name'        => $slug . '__trashed',
					'numberposts' => 1,
				)
			);
			$page = $trashed ? $trashed[0] : null;
		}
		if ( $page ) {
			if ( 'publish' !== $page->post_status ) {
				wp_update_post(
					array(
						'ID'          => $page->ID,
						'post_name'   => $slug,
						'post_status' => 'publish',
					)
				);
			}
			continue;
		}
		wp_insert_post(
			array(
				'post_title'   => $pg[0],
				'post_name'    => $slug,
				'post_content' => $pg[1],
				'post_status'  => 'publish',
				'post_type'    => 'page',
			)
		);
	}
	flush_rewrite_rules( false );
}

add_action( 'after_switch_theme', 'scrw_seed_core_pages', 40 );

add_action(
	'admin_init',
	function () {
		if ( get_option( 'scrw_pages_ver' ) === SCRW_PAGES_VERSION ) {
			return;
		}
		scrw_seed_core_pages();
		update_option( 'scrw_pages_ver', SCRW_PAGES_VERSION );
	},
	55
);

/*
 * /brands/fane/ (the generic brand page) -> /fane/ (the full FANE page, same
 * as the group site). Only once the FANE page exists, otherwise WordPress
 * would send /fane/ back to /brands/fane/ and loop.
 */
add_action(
	'template_redirect',
	function () {
		if ( ! is_singular( 'sc_brand' ) || 'fane' !== get_post_field( 'post_name', get_queried_object_id() ) ) {
			return;
		}
		$page = get_page_by_path( 'fane', OBJECT, 'page' );
		if ( $page && 'publish' === $page->post_status ) {
			wp_safe_redirect( get_permalink( $page ), 301 );
			exit;
		}
	},
	5
);

/* /brands/yamaha/ -> /yamaha/ (the full Yamaha page), once that page exists. */
add_action(
	'template_redirect',
	function () {
		if ( is_singular( 'sc_brand' ) === false || 'yamaha' !== get_post_field( 'post_name', get_queried_object_id() ) ) {
			return;
		}
		$page = get_page_by_path( 'yamaha', OBJECT, 'page' );
		if ( $page && 'publish' === $page->post_status ) {
			wp_safe_redirect( get_permalink( $page ), 301 );
			exit;
		}
	},
	5
);
