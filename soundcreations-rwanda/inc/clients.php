<?php
/**
 * "Our Clients" logo wall for the Rwanda site, shown on the homepage (after
 * Featured Projects) and on the About page, and available as [scrw_clients].
 *
 * To add a client: drop a logo into assets/img/clients/ (webp, png or jpg,
 * any size; a white background works best). It appears automatically. The
 * display name comes from scrw_client_names() below, or from the file name
 * (green-hills-academy.webp -> Green Hills Academy).
 *
 * @package SoundCreationsRwanda
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** File name (no extension) => client name, in display order. */
function scrw_client_names() {
	return array(
		'christian-life-assembly'          => 'Christian Life Assembly',
		'goethe-institut'                  => 'Goethe-Institut Kigali',
		'afriprecast'                      => 'Afriprecast',
		'green-hills-academy'              => 'Green Hills Academy',
		'mother-mary-international-school' => 'Mother Mary International School Complex',
	);
}

function scrw_clients_list() {
	$dir   = get_stylesheet_directory() . '/assets/img/clients/';
	$uri   = get_stylesheet_directory_uri() . '/assets/img/clients/';
	$files = glob( $dir . '*.{webp,png,jpg,jpeg}', GLOB_BRACE );
	if ( ! $files ) {
		return array();
	}
	$names = scrw_client_names();
	$items = array();
	foreach ( $files as $f ) {
		$base = pathinfo( $f, PATHINFO_FILENAME );
		if ( isset( $items[ $base ] ) ) {
			continue;
		}
		$items[ $base ] = array(
			'name' => isset( $names[ $base ] ) ? $names[ $base ] : ucwords( str_replace( array( '-', '_' ), ' ', $base ) ),
			'src'  => $uri . basename( $f ),
		);
	}
	// Named clients first, in the order above; any new files after them.
	$ordered = array();
	foreach ( array_keys( $names ) as $k ) {
		if ( isset( $items[ $k ] ) ) {
			$ordered[] = $items[ $k ];
			unset( $items[ $k ] );
		}
	}
	return array_merge( $ordered, array_values( $items ) );
}

function scrw_render_clients() {
	$items = scrw_clients_list();
	if ( ! $items ) {
		return '';
	}
	ob_start();
	?>
	<section class="sc-section scrw-clients" id="our-clients">
		<div class="sc-container">
			<div class="scrw-clients__head">
				<p class="sc-eyebrow"><?php esc_html_e( 'Our Clients', 'soundcreations-rwanda' ); ?></p>
				<h2><?php esc_html_e( 'Trusted by organisations across Rwanda.', 'soundcreations-rwanda' ); ?></h2>
				<p class="sc-lead"><?php esc_html_e( 'Churches, schools, cultural institutions, businesses and venues rely on Sound Creations Rwanda for their sound, lighting and acoustics.', 'soundcreations-rwanda' ); ?></p>
			</div>
			<ul class="scrw-clients__grid">
				<?php foreach ( $items as $c ) : ?>
					<li class="scrw-clients__item" title="<?php echo esc_attr( $c['name'] ); ?>">
						<img src="<?php echo esc_url( $c['src'] ); ?>" alt="<?php echo esc_attr( $c['name'] ); ?>" loading="lazy" decoding="async" width="400" height="220">
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

add_shortcode( 'scrw_clients', 'scrw_render_clients' );

$scrw_clients_echo = function () {
	echo scrw_render_clients(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in the renderer.
};
add_action( 'sc_home_after_projects', $scrw_clients_echo );
add_action( 'sc_about_after_brands', $scrw_clients_echo );
