<?php
/**
 * Client testimonials (2.3.5).
 *
 * Shown on the homepage after Our Clients, and available as [scrw_testimonials].
 * Hidden until at least one quote is entered under
 * Settings > Client Testimonials (one quote per line:
 * Quote | Name | Role, Organisation).
 * Only use real quotes that the client has approved for publication.
 *
 * @package SoundCreationsRwanda
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Parsed testimonials: array of array( quote, name, role ). */
function scrw_testimonials() {
	$raw = (string) get_option( 'scrw_testimonials', '' );
	$out = array();
	foreach ( preg_split( '/\r\n|\r|\n/', $raw ) as $line ) {
		$parts = array_map( 'trim', explode( '|', $line ) );
		if ( empty( $parts[0] ) || empty( $parts[1] ) ) {
			continue;
		}
		$out[] = array(
			'quote' => $parts[0],
			'name'  => $parts[1],
			'role'  => isset( $parts[2] ) ? $parts[2] : '',
		);
	}
	return array_slice( $out, 0, 6 );
}

function scrw_render_testimonials() {
	$items = scrw_testimonials();
	if ( ! $items ) {
		return '';
	}
	ob_start();
	?>
	<section class="sc-section scrw-testi" id="testimonials">
		<div class="sc-container">
			<div class="scrw-testi__head">
				<p class="sc-eyebrow"><?php esc_html_e( 'Client feedback', 'soundcreations-rwanda' ); ?></p>
				<h2 class="scrw-testi__title"><?php esc_html_e( 'What our clients say', 'soundcreations-rwanda' ); ?></h2>
			</div>
			<div class="scrw-testi__grid">
				<?php foreach ( $items as $t ) : ?>
					<figure class="scrw-testi__card">
						<blockquote><p><?php echo esc_html( $t['quote'] ); ?></p></blockquote>
						<figcaption><strong><?php echo esc_html( $t['name'] ); ?></strong><?php if ( '' !== $t['role'] ) : ?><span><?php echo esc_html( $t['role'] ); ?></span><?php endif; ?></figcaption>
					</figure>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

add_shortcode( 'scrw_testimonials', 'scrw_render_testimonials' );

add_action(
	'sc_home_after_projects',
	function () {
		echo scrw_render_testimonials(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in the renderer.
	},
	20
);

/* Admin: Settings > Client Testimonials. */
add_action(
	'admin_init',
	function () {
		register_setting(
			'scrw_testimonials_group',
			'scrw_testimonials',
			array(
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_textarea_field',
				'default'           => '',
			)
		);
	}
);

add_action(
	'admin_menu',
	function () {
		add_options_page(
			__( 'Client Testimonials', 'soundcreations-rwanda' ),
			__( 'Client Testimonials', 'soundcreations-rwanda' ),
			'edit_pages',
			'scrw-testimonials',
			'scrw_testimonials_page'
		);
	}
);

/* Let Editors save this one option (options.php needs manage_options by default). */
add_filter(
	'option_page_capability_scrw_testimonials_group',
	function () {
		return 'edit_pages';
	}
);

function scrw_testimonials_page() {
	if ( ! current_user_can( 'edit_pages' ) ) {
		return;
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Client Testimonials', 'soundcreations-rwanda' ); ?></h1>
		<p><?php esc_html_e( 'One quote per line, in this format: Quote | Name | Role, Organisation. Up to 6 are shown on the homepage, after Our Clients. Leave empty to hide the section. Only publish real quotes the client has approved.', 'soundcreations-rwanda' ); ?></p>
		<p><code>The new sound system transformed our Sunday services. | Pastor Jane Doe | Senior Pastor, Example Church</code></p>
		<form method="post" action="options.php">
			<?php settings_fields( 'scrw_testimonials_group' ); ?>
			<textarea name="scrw_testimonials" rows="10" class="large-text code"><?php echo esc_textarea( (string) get_option( 'scrw_testimonials', '' ) ); ?></textarea>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}
