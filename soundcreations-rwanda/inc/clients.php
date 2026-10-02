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
	// Each marquee track needs enough logos to overflow wide screens, so
	// short lists are repeated; the track is then doubled for a seamless loop.
	$fill = function ( $list ) {
		$out = $list;
		while ( count( $out ) < 10 ) {
			$out = array_merge( $out, $list );
		}
		return $out;
	};
	$row1 = $fill( $items );
	$row2 = $fill( array_reverse( $items ) );
	$tile = function ( $c, $hidden ) {
		return '<li class="scrw-clients__tile"' . ( $hidden ? ' aria-hidden="true"' : '' ) . '>'
			. '<img src="' . esc_url( $c['src'] ) . '" alt="' . ( $hidden ? '' : esc_attr( $c['name'] ) ) . '" loading="lazy" decoding="async" width="400" height="220">'
			. '<span class="scrw-clients__name">' . esc_html( $c['name'] ) . '</span></li>';
	};
	$track = function ( $list ) use ( $tile ) {
		$html = '';
		$unique = count( array_unique( array_column( $list, 'src' ) ) );
		foreach ( array( false, true ) as $dup ) {
			foreach ( $list as $i => $c ) {
				$html .= $tile( $c, $dup || $i >= $unique );
			}
		}
		return $html;
	};
	ob_start();
	?>
	<section class="sc-section scrw-clients" id="our-clients">
		<div class="sc-container scrw-clients__intro">
			<div class="scrw-clients__head">
				<p class="sc-eyebrow"><?php esc_html_e( 'Our Clients', 'soundcreations-rwanda' ); ?></p>
				<h2><?php esc_html_e( 'Trusted by organisations across Rwanda.', 'soundcreations-rwanda' ); ?></h2>
				<p class="sc-lead"><?php esc_html_e( 'From ministries and embassies to churches, schools, hotels and the venues that host Kigali’s biggest nights, our clients rely on us for sound, lighting and acoustics that simply work.', 'soundcreations-rwanda' ); ?></p>
			</div>
			<div class="scrw-clients__stats">
				<div><strong>70+</strong><span><?php esc_html_e( 'clients served in Rwanda', 'soundcreations-rwanda' ); ?></span></div>
				<div><strong>6</strong><span><?php esc_html_e( 'sectors: worship, education, government, hospitality, corporate, events', 'soundcreations-rwanda' ); ?></span></div>
			</div>
		</div>
		<div class="scrw-clients__stage">
			<ul class="scrw-clients__track scrw-clients__track--a"><?php echo $track( $row1 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in $tile. ?></ul>
			<ul class="scrw-clients__track scrw-clients__track--b"><?php echo $track( $row2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in $tile. ?></ul>
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
