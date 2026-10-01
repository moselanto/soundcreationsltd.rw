<?php
/**
 * Setup Wizard: a guided checklist plus a one-click full demo import
 * (pages + solutions + menu + sample catalog + permalinks).
 *
 * The import runs on this admin page itself - no cross-page redirect - so it
 * cannot strand the user on a dead URL if a host mangles admin-post redirects.
 *
 * @package SoundCreationsCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function sc_core_setup_status() {
	$brands = wp_count_posts( 'sc_brand' );
	return array(
		'permalinks' => (bool) get_option( 'permalink_structure' ),
		'pages'      => (bool) get_page_by_path( 'contact', OBJECT, 'page' ),
		'menu'       => (bool) wp_get_nav_menu_object( 'Primary' ),
		'catalog'    => ( $brands && isset( $brands->publish ) && (int) $brands->publish > 0 ),
	);
}

add_action(
	'admin_menu',
	function () {
		add_submenu_page( 'sc-settings', 'Setup Wizard', 'Setup Wizard', 'manage_options', 'sc-wizard', 'sc_core_render_wizard_page' );
	}
);

function sc_core_wizard_row( $ok, $label, $detail ) {
	$mark = $ok ? '<span style="color:#1a7f4b;font-weight:700;">Done</span>' : '<span style="color:#b32d2e;font-weight:700;">To do</span>';
	echo '<tr><td style="width:80px;">' . $mark . '</td><th scope="row" style="text-align:left;">' . esc_html( $label ) . '</th><td>' . esc_html( $detail ) . '</td></tr>';
}

/** Run the whole demo import. Idempotent. */
function sc_core_run_full_setup() {
	if ( function_exists( 'sc_core_run_starter_setup' ) ) {
		sc_core_run_starter_setup();
	}
	if ( function_exists( 'sc_core_seed_catalog' ) ) {
		sc_core_seed_catalog();
	}
	global $wp_rewrite;
	if ( ! get_option( 'permalink_structure' ) ) {
		$wp_rewrite->set_permalink_structure( '/%postname%/' );
	}
	$wp_rewrite->flush_rules( false );
}

function sc_core_render_wizard_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$ran = false;
	if ( isset( $_POST['sc_full_setup'] ) ) {
		check_admin_referer( 'sc_core_full_setup' );
		sc_core_run_full_setup();
		$ran = true;
	}

	$st = sc_core_setup_status();
	?>
	<div class="wrap">
		<h1>Sound Creations - Setup Wizard</h1>
		<p>Get the site fully populated in one click, then refine. Everything here is idempotent and safe to re-run.</p>
		<?php if ( $ran ) : ?>
			<div class="notice notice-success"><p><strong>Full demo import complete.</strong> If any front-end link shows 404, open Settings -> Permalinks and click Save Changes once. Then review Solutions, Products, Brands, Projects and the FANE hub, and replace any [VERIFY] / [CONTENT TO BE CONFIRMED] placeholders.</p></div>
		<?php endif; ?>

		<h2 style="margin-top:1.5rem;">Status</h2>
		<table class="widefat striped" style="max-width:820px;"><tbody>
			<?php
			sc_core_wizard_row( $st['permalinks'], 'Permalinks', 'Post-name URLs enabled so archives resolve.' );
			sc_core_wizard_row( $st['pages'], 'Core pages', 'About, Contact, FANE, dealer and enquiry pages created.' );
			sc_core_wizard_row( $st['menu'], 'Primary menu', 'Main navigation built and assigned.' );
			sc_core_wizard_row( $st['catalog'], 'Sample catalog', 'Starter brands, products and projects seeded.' );
			?>
		</tbody></table>

		<h2 style="margin-top:2rem;">Run the full demo import</h2>
		<p style="max-width:820px;">Creates all core pages and sample Solutions, builds the primary menu, seeds the sample Brands, Products and Projects, and switches on post-name permalinks - in one step. This runs on this page, so it will not redirect you away.</p>
		<form method="post" action="">
			<?php wp_nonce_field( 'sc_core_full_setup' ); ?>
			<input type="hidden" name="sc_full_setup" value="1">
			<?php submit_button( 'Run full demo import', 'primary large' ); ?>
		</form>

		<p style="color:#666;max-width:820px;">Prefer to run steps individually? Use <strong>Starter Setup</strong> (pages and menu) and <strong>Sample Catalog</strong> (brands, products, projects) from the Sound Creations menu.</p>
	</div>
	<?php
}
