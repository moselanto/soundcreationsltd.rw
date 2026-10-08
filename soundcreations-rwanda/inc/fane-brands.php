<?php
/**
 * FANE Africa on the Brands page (management request, 8 Oct 2026).
 *
 * FANE Africa left the top menu; instead the Brands page opens with a
 * featured "Official partner" panel for it, right under the hero and above
 * the full brand grid. The full /fane/ page stays live (linked from here and
 * from the FANE brand card) and the menu highlights "Brands" while on it.
 *
 * @package SoundCreationsRwanda
 */

if ( defined( 'ABSPATH' ) === false ) {
	exit;
}

function scrw_render_fane_feature() {
	$prod  = get_stylesheet_directory_uri() . '/assets/img/products-official/';
	$arrow = '<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>';
	$tick  = '<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>';
	$range = array(
		array( 'fane-sovereign-15-600', __( 'Sovereign', 'soundcreations-rwanda' ), __( 'Pro bass and mid-bass drivers', 'soundcreations-rwanda' ) ),
		array( 'fane-colossus-18xb', __( 'Colossus', 'soundcreations-rwanda' ), __( 'High-power subwoofer drivers', 'soundcreations-rwanda' ) ),
		array( 'fane-cd140', __( 'Compression drivers', 'soundcreations-rwanda' ), __( 'Clear, detailed high frequencies', 'soundcreations-rwanda' ) ),
	);
	$points = array(
		__( 'Genuine FANE components with local stock', 'soundcreations-rwanda' ),
		__( 'Technical advice on driver choice and cabinets', 'soundcreations-rwanda' ),
		__( 'Dealer programme across East and Central Africa', 'soundcreations-rwanda' ),
	);
	ob_start();
	?>
	<section class="sc-section scrw-fanef" id="fane-africa" aria-labelledby="scrw-fanef-title">
		<div class="sc-container">
			<div class="scrw-fanef__panel">
				<div class="scrw-fanef__copy">
					<p class="scrw-fanef__badge"><span aria-hidden="true"></span><?php esc_html_e( 'Official FANE Africa partner', 'soundcreations-rwanda' ); ?></p>
					<h2 class="sc-sectitle" id="scrw-fanef-title"><?php esc_html_e( 'FANE Africa', 'soundcreations-rwanda' ); ?></h2>
					<p class="sc-secsub"><?php esc_html_e( 'British loudspeaker engineering since 1958.', 'soundcreations-rwanda' ); ?></p>
					<p class="scrw-fanef__text"><?php esc_html_e( 'Precision drivers, subwoofers and compression drivers for touring rigs, installations and cabinet builders, supplied and supported by our team in Kigali.', 'soundcreations-rwanda' ); ?></p>
					<ul class="scrw-fanef__points">
						<?php foreach ( $points as $pt ) : ?>
							<li><?php echo $tick; // phpcs:ignore -- static SVG. ?><?php echo esc_html( $pt ); ?></li>
						<?php endforeach; ?>
					</ul>
					<div class="scrw-fanef__cta">
						<a class="sc-btn sc-btn--primary" href="<?php echo esc_url( home_url( '/fane/' ) ); ?>"><?php esc_html_e( 'Explore FANE Africa', 'soundcreations-rwanda' ); ?> <?php echo $arrow; // phpcs:ignore ?></a>
						<a class="sc-btn sc-btn--ghost" href="<?php echo esc_url( home_url( '/products/?brand=fane#catalogue' ) ); ?>"><?php esc_html_e( 'Shop FANE products', 'soundcreations-rwanda' ); ?></a>
						<a class="scrw-fanef__link" href="<?php echo esc_url( home_url( '/become-a-dealer/' ) ); ?>"><?php esc_html_e( 'Become a FANE dealer', 'soundcreations-rwanda' ); ?> <span aria-hidden="true">&rarr;</span></a>
					</div>
				</div>
				<ul class="scrw-fanef__range" aria-label="<?php esc_attr_e( 'FANE product ranges', 'soundcreations-rwanda' ); ?>">
					<?php foreach ( $range as $r ) : ?>
						<li>
							<a href="<?php echo esc_url( home_url( '/products/?brand=fane#catalogue' ) ); ?>">
								<span class="scrw-fanef__plate"><img src="<?php echo esc_url( $prod . $r[0] . '.webp' ); ?>" alt="" loading="lazy" decoding="async" width="800" height="800"></span>
								<span class="scrw-fanef__name"><?php echo esc_html( $r[1] ); ?></span>
								<span class="scrw-fanef__desc"><?php echo esc_html( $r[2] ); ?></span>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

add_action(
	'sc_brands_after_hero',
	function () {
		echo scrw_render_fane_feature(); // phpcs:ignore -- escaped above.
	}
);
