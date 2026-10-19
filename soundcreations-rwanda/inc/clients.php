<?php
/**
 * "Our Clients" logo wall for the Rwanda site, shown on the homepage (after
 * Featured Projects) and on the About page, and available as [scrw_clients].
 *
 * To add a client: Settings > Our Clients in wp-admin (2.3.20). The folder
 * assets/img/clients/ is only the starting set, used until that page is saved.
 * Old note: drop a logo into assets/img/clients/ (webp, png or jpg,
 * any size; a white background works best). It appears automatically. The
 * display name comes from scrw_client_names() below, or from the file name
 * (green-hills-academy.webp -> Green Hills Academy).
 *
 * @package SoundCreationsRwanda
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Logos managed in wp-admin: Settings > Our Clients (2.3.20).
 * One per line: Logo image URL | Client name. Upload the logo in Media,
 * copy its File URL and paste it here. Never saved = the theme-folder logos.
 * Saved empty = section hidden.
 */
function scrw_clients_from_settings() {
	$raw = get_option( 'scrw_client_logos', null );
	if ( null === $raw ) {
		return null;
	}
	$out = array();
	foreach ( preg_split( '/\r\n|\r|\n/', (string) $raw ) as $line ) {
		$parts = array_map( 'trim', explode( '|', $line, 2 ) );
		if ( empty( $parts[0] ) ) {
			continue;
		}
		$name  = isset( $parts[1] ) && '' !== $parts[1] ? $parts[1] : ucwords( str_replace( array( '-', '_' ), ' ', pathinfo( $parts[0], PATHINFO_FILENAME ) ) );
		$out[] = array( 'key' => sanitize_title( $name ), 'name' => $name, 'src' => esc_url_raw( $parts[0] ) );
	}
	return $out;
}

/** The current logos as "URL | Name" lines (pre-fills the settings box). */
function scrw_clients_as_lines() {
	$lines = array();
	foreach ( scrw_clients_list() as $c ) {
		$lines[] = $c['src'] . ' | ' . $c['name'];
	}
	return implode( "\n", $lines );
}

add_action(
	'admin_init',
	function () {
		register_setting( 'scrw_clients_group', 'scrw_client_logos', array( 'type' => 'string', 'sanitize_callback' => 'sanitize_textarea_field' ) );
	}
);

add_action(
	'admin_menu',
	function () {
		add_options_page(
			'Our Clients',
			'Our Clients',
			'manage_options',
			'scrw-clients',
			function () {
				if ( ! current_user_can( 'manage_options' ) ) {
					return;
				}
				?>
				<div class="wrap">
					<h1>Our Clients (logo strip)</h1>
					<p>The moving logo strip on the homepage and About page. One logo per line: <code>Logo image URL | Client name</code>.</p>
					<p>To add a client: go to <strong>Media &gt; Add New</strong>, upload the logo (white background works best), click it, copy the <strong>File URL</strong>, and paste it here followed by <code> | Client name</code>. Delete a line to remove a client. Change the order by moving lines. Clear the box and save to hide the strip.</p>
					<form method="post" action="options.php">
						<?php settings_fields( 'scrw_clients_group' ); ?>
						<textarea class="large-text code" rows="18" name="scrw_client_logos"><?php echo esc_textarea( null === get_option( 'scrw_client_logos', null ) ? scrw_clients_as_lines() : (string) get_option( 'scrw_client_logos' ) ); ?></textarea>
						<?php submit_button(); ?>
					</form>
				</div>
				<?php
			}
		);
	}
);

function scrw_clients_list() {
	static $in = false;
	if ( ! $in ) {
		$in  = true;
		$set = scrw_clients_from_settings();
		$in  = false;
		if ( null !== $set ) {
			return $set;
		}
	}
	$dir   = get_stylesheet_directory() . '/assets/img/clients/';
	$uri   = get_stylesheet_directory_uri() . '/assets/img/clients/';
	$files = glob( $dir . '*.{webp,png,jpg,jpeg}', GLOB_BRACE );
	if ( ! $files ) {
		return array();
	}
	$names = array();
	foreach ( scrw_client_meta() as $k => $m ) {
		$names[ $k ] = $m[0];
	}
	$items = array();
	foreach ( $files as $f ) {
		$base = pathinfo( $f, PATHINFO_FILENAME );
		if ( isset( $items[ $base ] ) ) {
			continue;
		}
		$items[ $base ] = array(
			'key'  => $base,
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

/**
 * File name (no extension) => client name, in display order.
 * To add a client later: drop a logo into assets/img/clients/ (any name).
 * Add a line here only if you want a nicer name or a fixed position;
 * unlisted logos are still shown, named from the file name.
 */
function scrw_client_meta() {
	return array(
		'minecofin'                        => array( 'Ministry of Finance and Economic Planning (MINECOFIN)' ),
		'intare-conference-arena'          => array( 'Intare Conference Arena' ),
		'christian-life-assembly'          => array( 'Christian Life Assembly' ),
		'rwanda-correctional-service'      => array( 'Rwanda Correctional Service' ),
		'goethe-institut'                  => array( 'Goethe-Institut Kigali' ),
		'ntare-louisenlund-school'         => array( 'Ntare Louisenlund School' ),
		'new-life-bible-church'            => array( 'New Life Bible Church' ),
		'real-contractors'                 => array( 'Real Contractors Ltd' ),
		'green-hills-academy'              => array( 'Green Hills Academy' ),
		'afriprecast'                      => array( 'Afriprecast' ),
		'mother-mary-international-school' => array( 'Mother Mary International School Complex' ),
	);
}

function scrw_render_clients() {
	$items = array_values( scrw_clients_list() );
	if ( ! $items ) {
		return '';
	}
	// Repeat the set so the strip is always wider than the screen, then
	// duplicate it once more so the loop is seamless.
	$set = $items;
	while ( count( $set ) < 12 ) {
		$set = array_merge( $set, $items );
	}
	$secs = max( 30, count( $set ) * 4 );
	ob_start();
	?>
	<section class="sc-section scrw-clients" id="our-clients">
		<div class="sc-container">
			<div class="scrw-clients__head">
				<h2 class="scrw-clients__title"><?php esc_html_e( 'Our Clients', 'soundcreations-rwanda' ); ?></h2>
				<p class="scrw-clients__sub"><?php esc_html_e( 'Trusted by organisations across Rwanda.', 'soundcreations-rwanda' ); ?></p>
			</div>
		</div>
		<div class="scrw-clients__marquee" style="--scrw-dur:<?php echo (int) $secs; ?>s">
			<ul class="scrw-clients__track">
				<?php for ( $loop = 0; $loop < 2; $loop++ ) : ?>
					<?php foreach ( $set as $c ) : ?>
						<li class="scrw-clients__card"<?php echo $loop ? ' aria-hidden="true"' : ''; ?>>
							<?php // v2.2.1: the duplicate loop copy is inside aria-hidden, so screen readers skip it; it keeps the client name as alt so Bing/Google image checks do not flag 22 empty alts. ?>
							<img src="<?php echo esc_url( $c['src'] ); ?>" alt="<?php echo esc_attr( $c['name'] ); ?>" title="<?php echo esc_attr( $c['name'] ); ?>" loading="lazy" decoding="async" width="400" height="220">
						</li>
					<?php endforeach; ?>
				<?php endfor; ?>
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
