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

function scrw_clients_list() {
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

/** File name => array( client name, sector key, featured ). Display order. */
function scrw_client_meta() {
	return array(
		'minecofin'                        => array( 'Ministry of Finance and Economic Planning (MINECOFIN)', 'gov', true ),
		'intare-conference-arena'          => array( 'Intare Conference Arena', 'biz', true ),
		'christian-life-assembly'          => array( 'Christian Life Assembly', 'worship', true ),
		'rwanda-correctional-service'      => array( 'Rwanda Correctional Service', 'gov', false ),
		'goethe-institut'                  => array( 'Goethe-Institut Kigali', 'gov', false ),
		'ntare-louisenlund-school'         => array( 'Ntare Louisenlund School', 'edu', false ),
		'new-life-bible-church'            => array( 'New Life Bible Church', 'worship', false ),
		'real-contractors'                 => array( 'Real Contractors Ltd', 'biz', false ),
		'green-hills-academy'              => array( 'Green Hills Academy', 'edu', false ),
		'afriprecast'                      => array( 'Afriprecast', 'biz', false ),
		'mother-mary-international-school' => array( 'Mother Mary International School Complex', 'edu', false ),
	);
}

/** Sector key => label. */
function scrw_client_sectors() {
	return array(
		'gov'     => 'Government & Institutions',
		'worship' => 'Houses of Worship',
		'edu'     => 'Education',
		'biz'     => 'Business & Venues',
	);
}

function scrw_render_clients() {
	$items = scrw_clients_list();
	if ( ! $items ) {
		return '';
	}
	$meta    = scrw_client_meta();
	$sectors = scrw_client_sectors();
	$counts  = array();
	foreach ( $items as $k => $c ) {
		$m                   = isset( $meta[ $c['key'] ] ) ? $meta[ $c['key'] ] : array( $c['name'], 'biz', false );
		$items[ $k ]['name'] = $m[0];
		$items[ $k ]['sec']  = $m[1];
		$items[ $k ]['big']  = (bool) $m[2];
		$counts[ $m[1] ]     = isset( $counts[ $m[1] ] ) ? $counts[ $m[1] ] + 1 : 1;
	}
	ob_start();
	?>
	<section class="sc-section scrw-clients" id="our-clients" data-scrw-clients>
		<div class="sc-container">
			<div class="scrw-clients__intro">
				<div class="scrw-clients__head">
					<p class="sc-eyebrow"><?php esc_html_e( 'Our Clients', 'soundcreations-rwanda' ); ?></p>
					<h2><?php esc_html_e( 'Trusted by organisations across Rwanda.', 'soundcreations-rwanda' ); ?></h2>
					<p class="sc-lead"><?php esc_html_e( 'From ministries and cultural institutions to churches, schools, contractors and the venues that host Kigali’s biggest events, our clients rely on us for sound, lighting and acoustics that simply work.', 'soundcreations-rwanda' ); ?></p>
				</div>
				<div class="scrw-clients__stats">
					<div><strong data-scrw-count="70">70</strong><em>+</em><span><?php esc_html_e( 'clients served in Rwanda', 'soundcreations-rwanda' ); ?></span></div>
					<div><strong data-scrw-count="<?php echo (int) count( $sectors ); ?>"><?php echo (int) count( $sectors ); ?></strong><span><?php esc_html_e( 'sectors, one trusted partner', 'soundcreations-rwanda' ); ?></span></div>
				</div>
			</div>

			<div class="scrw-clients__filters" role="group" aria-label="<?php esc_attr_e( 'Filter clients by sector', 'soundcreations-rwanda' ); ?>">
				<button type="button" class="scrw-clients__filter is-active" data-sec=""><?php esc_html_e( 'All clients', 'soundcreations-rwanda' ); ?><span><?php echo (int) count( $items ); ?></span></button>
				<?php foreach ( $sectors as $key => $label ) : ?>
					<?php if ( ! empty( $counts[ $key ] ) ) : ?>
						<button type="button" class="scrw-clients__filter" data-sec="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?><span><?php echo (int) $counts[ $key ]; ?></span></button>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>

			<ul class="scrw-clients__wall">
				<?php foreach ( $items as $i => $c ) : ?>
					<li class="scrw-clients__card<?php echo $c['big'] ? ' scrw-clients__card--big' : ''; ?>" data-sec="<?php echo esc_attr( $c['sec'] ); ?>" style="--d:<?php echo (int) $i * 60; ?>ms">
						<div class="scrw-clients__logo"><img src="<?php echo esc_url( $c['src'] ); ?>" alt="<?php echo esc_attr( $c['name'] ); ?>" loading="lazy" decoding="async" width="400" height="220"></div>
						<div class="scrw-clients__cap">
							<span class="scrw-clients__sector"><?php echo esc_html( isset( $sectors[ $c['sec'] ] ) ? $sectors[ $c['sec'] ] : '' ); ?></span>
							<span class="scrw-clients__name"><?php echo esc_html( $c['name'] ); ?></span>
						</div>
					</li>
				<?php endforeach; ?>
				<li class="scrw-clients__card scrw-clients__card--cta" data-sec="" style="--d:<?php echo (int) count( $items ) * 60; ?>ms">
					<span class="scrw-clients__cta-k"><?php esc_html_e( 'Your project next?', 'soundcreations-rwanda' ); ?></span>
					<a class="scrw-clients__cta-btn" href="<?php echo esc_url( home_url( '/request-a-consultation/' ) ); ?>"><?php esc_html_e( 'Talk to our Kigali team', 'soundcreations-rwanda' ); ?> &rarr;</a>
				</li>
			</ul>
		</div>
	</section>
	<script>
	(function(){
		var root=document.querySelector('[data-scrw-clients]'); if(root===null){return;}
		var cards=root.querySelectorAll('.scrw-clients__card'), btns=root.querySelectorAll('.scrw-clients__filter');
		Array.prototype.forEach.call(btns,function(b){b.addEventListener('click',function(){
			Array.prototype.forEach.call(btns,function(x){x.classList.remove('is-active');});
			b.classList.add('is-active'); var s=b.getAttribute('data-sec');
			Array.prototype.forEach.call(cards,function(c){
				var cs=c.getAttribute('data-sec'); c.classList.toggle('is-dim', s!=='' && cs!=='' && cs!==s);
			});
		});});
		var reduce=window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
		function reveal(){
			root.classList.add('is-in');
			if(reduce){return;}
			Array.prototype.forEach.call(root.querySelectorAll('[data-scrw-count]'),function(el){
				var end=parseInt(el.getAttribute('data-scrw-count'),10)||0, t0=null;
				function step(t){ if(t0===null){t0=t;} var p=Math.min(1,(t-t0)/1400); el.textContent=Math.round(end*(1-Math.pow(1-p,3))); if(p<1){requestAnimationFrame(step);} }
				requestAnimationFrame(step);
			});
		}
		if('IntersectionObserver' in window && reduce===false){
			var io=new IntersectionObserver(function(es){es.forEach(function(e){if(e.isIntersecting){reveal();io.disconnect();}});},{threshold:.2});
			io.observe(root);
		}else{ reveal(); }
	})();
	</script>
	<?php
	return (string) ob_get_clean();
}

add_shortcode( 'scrw_clients', 'scrw_render_clients' );

$scrw_clients_echo = function () {
	echo scrw_render_clients(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in the renderer.
};
add_action( 'sc_home_after_projects', $scrw_clients_echo );
add_action( 'sc_about_after_brands', $scrw_clients_echo );
