<?php
/**
 * Elementor integration for the Rwanda site.
 *
 * Goal: editors build and update pages visually in Elementor while the site
 * keeps the group look. This file:
 *   1. Routes any page built with Elementor to a full-width template that
 *      keeps the site header and footer, including pages that normally use a
 *      hardcoded template (the homepage, About, Contact...). Switching a page
 *      back to the normal editor restores the original template.
 *   2. Seeds the Elementor Site Kit with the brand colours, Inter typography
 *      and the 1180px container, so new sections are on-brand by default.
 *   3. Enables Elementor on Solutions, Projects and Services as well as Pages.
 *   4. Stops Elementor loading Google Fonts (the theme ships Inter locally).
 *   5. Registers Elementor Pro theme-builder locations when Pro is present.
 *   6. Adds a "Sound Creations" widget panel (see elementor-widgets.php).
 *
 * Elementor itself is a third-party plugin and is not in this repository:
 * install it from Plugins -> Add New. Nothing here runs until it is active.
 *
 * @package SoundCreationsRwanda
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SCRW_ELEMENTOR_VERSION', 'rw-elementor-1' );

/** Is Elementor loaded? */
function scrw_elementor_active() {
	return did_action( 'elementor/loaded' ) > 0 && class_exists( '\Elementor\Plugin' );
}

/** Was this post built with Elementor? */
function scrw_built_with_elementor( $post_id ) {
	if ( ! $post_id || ! scrw_elementor_active() ) {
		return false;
	}
	$plugin = \Elementor\Plugin::$instance;
	if ( ! isset( $plugin->documents ) ) {
		return false;
	}
	$doc = $plugin->documents->get( $post_id );
	return $doc && $doc->is_built_with_elementor();
}

/* 1. Template routing. */
add_filter(
	'template_include',
	function ( $template ) {
		if ( ! is_singular() ) {
			return $template;
		}
		$id = (int) get_queried_object_id();
		if ( ! scrw_built_with_elementor( $id ) ) {
			return $template;
		}
		// Respect Elementor's own Canvas / Full Width page templates.
		$chosen = (string) get_post_meta( $id, '_wp_page_template', true );
		if ( in_array( $chosen, array( 'elementor_canvas', 'elementor_header_footer' ), true ) ) {
			return $template;
		}
		// Safety net: a designed page (About, Home, Contact...) whose Elementor
		// data does not yet hold the SC Page Design block keeps its normal
		// designed template, so the live page never collapses to plain text.
		// Opening the page in Elementor adds the block, and from then on the
		// Elementor version is shown.
		if ( function_exists( 'scrw_design_for_page' ) && '' !== scrw_design_for_page( $id ) ) {
			$raw = (string) get_post_meta( $id, '_elementor_data', true );
			if ( false === strpos( $raw, 'scrw_page_design' ) ) {
				return $template;
			}
		}
		$full = locate_template( 'template-elementor.php' );
		return $full ? $full : $template;
	},
	99
);

/* Body class so CSS can target Elementor-built pages. */
add_filter(
	'body_class',
	function ( $classes ) {
		if ( is_singular() && scrw_built_with_elementor( (int) get_queried_object_id() ) ) {
			$classes[] = 'scrw-elementor-page';
		}
		return $classes;
	}
);

/* 4. Local fonts only. */
add_filter( 'elementor/frontend/print_google_fonts', '__return_false' );

/* 5. Elementor Pro theme builder (header, footer, single, archive). */
add_action(
	'elementor/theme/register_locations',
	function ( $manager ) {
		if ( is_object( $manager ) && method_exists( $manager, 'register_all_core_location' ) ) {
			$manager->register_all_core_location();
		}
	}
);

/* 6. Widget category and widgets. */
add_action(
	'elementor/elements/categories_registered',
	function ( $elements_manager ) {
		$elements_manager->add_category(
			'sound-creations',
			array(
				'title' => 'Sound Creations',
				'icon'  => 'eicon-site-identity',
			)
		);
	}
);

add_action(
	'elementor/widgets/register',
	function ( $widgets_manager ) {
		require_once SCRW_DIR . 'inc/elementor-widgets.php';
		$widgets_manager->register( new SCRW_Widget_Contact_Card() );
		$widgets_manager->register( new SCRW_Widget_Enquiry_Form() );
		$widgets_manager->register( new SCRW_Widget_Projects_Grid() );
		$widgets_manager->register( new SCRW_Widget_Brands() );
		$widgets_manager->register( new SCRW_Widget_CTA_Band() );
		$widgets_manager->register( new SCRW_Widget_Page_Design() );
	}
);

/* 2 + 3. One-time Elementor configuration. */
function scrw_configure_elementor() {
	// Post types editable with Elementor.
	$cpts = get_option( 'elementor_cpt_support', array( 'page', 'post' ) );
	if ( ! is_array( $cpts ) ) {
		$cpts = array( 'page', 'post' );
	}
	$cpts = array_values( array_unique( array_merge( $cpts, array( 'page', 'post', 'sc_solution', 'sc_project', 'sc_service' ) ) ) );
	update_option( 'elementor_cpt_support', $cpts );

	// Use the Site Kit, not Elementor's legacy default colours and fonts.
	update_option( 'elementor_disable_color_schemes', 'yes' );
	update_option( 'elementor_disable_typography_schemes', 'yes' );
	update_option( 'elementor_font_display', 'swap' );

	$kit_id = (int) get_option( 'elementor_active_kit' );
	if ( $kit_id < 1 ) {
		return false;
	}
	$s = get_post_meta( $kit_id, '_elementor_page_settings', true );
	if ( ! is_array( $s ) ) {
		$s = array();
	}
	$s['system_colors'] = array(
		array( '_id' => 'primary', 'title' => 'Brand Purple', 'color' => '#624489' ),
		array( '_id' => 'secondary', 'title' => 'Deep Purple', 'color' => '#46305F' ),
		array( '_id' => 'text', 'title' => 'Text', 'color' => '#F5F5F7' ),
		array( '_id' => 'accent', 'title' => 'Action Red', 'color' => '#BA0B0B' ),
	);
	if ( empty( $s['custom_colors'] ) ) {
		$s['custom_colors'] = array(
			array( '_id' => 'scbg', 'title' => 'Background', 'color' => '#0B0B0F' ),
			array( '_id' => 'scsurface', 'title' => 'Surface', 'color' => '#131318' ),
			array( '_id' => 'scmuted', 'title' => 'Muted Text', 'color' => '#A0A0AC' ),
			array( '_id' => 'scwhite', 'title' => 'White', 'color' => '#FFFFFF' ),
		);
	}
	$s['system_typography'] = array(
		array( '_id' => 'primary', 'title' => 'Headings', 'typography_typography' => 'custom', 'typography_font_family' => 'Inter', 'typography_font_weight' => '600' ),
		array( '_id' => 'secondary', 'title' => 'Sub-headings', 'typography_typography' => 'custom', 'typography_font_family' => 'Inter', 'typography_font_weight' => '500' ),
		array( '_id' => 'text', 'title' => 'Body', 'typography_typography' => 'custom', 'typography_font_family' => 'Inter', 'typography_font_weight' => '400' ),
		array( '_id' => 'accent', 'title' => 'Buttons', 'typography_typography' => 'custom', 'typography_font_family' => 'Inter', 'typography_font_weight' => '600' ),
	);
	$s['container_width'] = array( 'unit' => 'px', 'size' => 1180, 'sizes' => array() );
	update_post_meta( $kit_id, '_elementor_page_settings', $s );

	$plugin = \Elementor\Plugin::$instance;
	if ( isset( $plugin->files_manager ) && method_exists( $plugin->files_manager, 'clear_cache' ) ) {
		$plugin->files_manager->clear_cache();
	}
	return true;
}

add_action(
	'admin_init',
	function () {
		if ( ! scrw_elementor_active() || get_option( 'scrw_elementor_ver' ) === SCRW_ELEMENTOR_VERSION ) {
			return;
		}
		if ( scrw_configure_elementor() ) {
			update_option( 'scrw_elementor_ver', SCRW_ELEMENTOR_VERSION );
		}
	},
	20
);

/* Reminder in wp-admin while Elementor is not installed or active. */
add_action(
	'admin_notices',
	function () {
		if ( scrw_elementor_active() || ! current_user_can( 'install_plugins' ) ) {
			return;
		}
		$url = admin_url( 'plugin-install.php?s=elementor&tab=search&type=term' );
		echo '<div class="notice notice-info"><p><strong>Sound Creations Rwanda:</strong> pages are designed to be edited with Elementor. <a href="' . esc_url( $url ) . '">Install and activate Elementor</a> to enable visual editing and the Sound Creations widgets.</p></div>';
	}
);

/* ============================================================
   7. Start Elementor pages from the real design, not a blank page.

   Before: opening "Edit with Elementor" on About, Contact, the homepage etc.
   switched the page to the full-width template and showed ONLY the plain
   page text -- the designed hero, sections and brand walls disappeared.

   Now, the first time an editor opens one of these pages in Elementor, an
   "SC Page Design" block holding the full design is placed at the top of the
   page. Anything already built in Elementor is kept underneath it. Runs once
   per page (tracked in post meta), so later edits are never overwritten.
   ============================================================ */
function scrw_design_for_page( $post_id ) {
	$post = get_post( $post_id );
	if ( ! $post || 'page' !== $post->post_type ) {
		return '';
	}
	if ( (int) get_option( 'page_on_front' ) === (int) $post_id ) {
		return 'home';
	}
	$map = array(
		'about'                  => 'about',
		'contact'                => 'contact',
		'request-a-consultation' => 'consultation',
		'fane'                   => 'fane',
		'yamaha'                 => 'yamaha',
	);
	return isset( $map[ $post->post_name ] ) ? $map[ $post->post_name ] : '';
}

function scrw_el_id() {
	return substr( md5( wp_generate_password( 12, false ) ), 0, 7 );
}

function scrw_seed_design_section( $post_id ) {
	$design = scrw_design_for_page( $post_id );
	if ( '' === $design || get_post_meta( $post_id, '_scrw_design_seeded', true ) ) {
		return;
	}
	$raw  = get_post_meta( $post_id, '_elementor_data', true );
	$data = is_string( $raw ) && '' !== $raw ? json_decode( $raw, true ) : array();
	if ( ! is_array( $data ) ) {
		$data = array();
	}
	if ( false !== strpos( (string) $raw, 'scrw_page_design' ) ) {
		update_post_meta( $post_id, '_scrw_design_seeded', 1 );
		return;
	}
	$section = array(
		'id'       => scrw_el_id(),
		'elType'   => 'section',
		'settings' => array(
			'layout'  => 'full_width',
			'gap'     => 'no',
			'padding' => array( 'unit' => 'px', 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'isLinked' => true ),
		),
		'elements' => array(
			array(
				'id'       => scrw_el_id(),
				'elType'   => 'column',
				'settings' => array( '_column_size' => 100, 'padding' => array( 'unit' => 'px', 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'isLinked' => true ) ),
				'elements' => array(
					array(
						'id'         => scrw_el_id(),
						'elType'     => 'widget',
						'widgetType' => 'scrw_page_design',
						'settings'   => array( 'design' => $design ),
						'elements'   => array(),
					),
				),
				'isInner'  => false,
			),
		),
		'isInner'  => false,
	);
	array_unshift( $data, $section );
	update_post_meta( $post_id, '_elementor_data', wp_slash( wp_json_encode( $data ) ) );
	update_post_meta( $post_id, '_elementor_edit_mode', 'builder' );
	if ( ! get_post_meta( $post_id, '_elementor_template_type', true ) ) {
		update_post_meta( $post_id, '_elementor_template_type', 'wp-page' );
	}
	delete_post_meta( $post_id, '_elementor_css' );
	update_post_meta( $post_id, '_scrw_design_seeded', 1 );
}

add_action(
	'admin_init',
	function () {
		// phpcs:disable WordPress.Security.NonceVerification.Recommended -- read-only routing check; capability enforced below.
		if ( ! scrw_elementor_active() || ! isset( $_GET['action'], $_GET['post'] ) || 'elementor' !== $_GET['action'] ) {
			return;
		}
		$post_id = absint( $_GET['post'] );
		// phpcs:enable
		if ( $post_id && current_user_can( 'edit_post', $post_id ) ) {
			scrw_seed_design_section( $post_id );
		}
	},
	5
);

/* Full-bleed designs inside Elementor: remove the column/widget spacing so
   the design looks identical to the normal page. */
add_action(
	'wp_head',
	function () {
		echo '<style id="scrw-page-design">.scrw-page-design{width:100%}.elementor-widget-scrw_page_design>.elementor-widget-container{margin:0;padding:0}.elementor-widget-scrw_page_design{margin-bottom:0}</style>' . "\n";
	},
	20
);
