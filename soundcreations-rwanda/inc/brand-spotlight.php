<?php
/**
 * Homepage: authorised-brands spotlight right under the hero, so Yamaha and
 * FANE Africa are visible at a glance. Also available as [scrw_brand_spotlight].
 *
 * @package SoundCreationsRwanda
 */

if ( defined( 'ABSPATH' ) === false ) {
	exit;
}

function scrw_render_brand_spotlight() {
	$img   = get_stylesheet_directory_uri() . '/assets/img/products-official/';
	$arrow = '<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>';
	$cards = array(
		array(
			'key'    => 'yamaha',
			'badge'  => 'Authorised Distributor in Rwanda',
			'word'   => 'YAMAHA',
			'title'  => 'Genuine Yamaha, supplied and supported in Kigali.',
			'text'   => 'Mixing consoles, loudspeakers, studio monitors, keyboards, digital pianos, guitars and drums, with local stock, warranty and expert support.',
			'points' => array( 'Official warranty', 'Showroom demos', 'Installation & training' ),
			'images' => array( 'yamaha-tf5', 'yamaha-hs8i', 'yamaha-psr-sx920' ),
			'cta'    => array( 'Explore Yamaha', '/yamaha/' ),
			'cta2'   => array( 'Shop Yamaha products', '/products/?brand=yamaha#catalogue' ),
		),
		array(
			'key'    => 'fane',
			'badge'  => 'Official FANE Africa Partner',
			'word'   => 'FANE',
			'title'  => 'British loudspeaker engineering since 1958.',
			'text'   => 'Precision drivers, subwoofers and compression drivers for touring, installation and DIY cabinet builders across Rwanda and the region.',
			'points' => array( 'Genuine components', 'Technical advice', 'Dealer programme' ),
			'images' => array( 'fane-sovereign-15-600', 'fane-colossus-18xb', 'fane-cd140' ),
			'cta'    => array( 'Explore FANE Africa', '/fane/' ),
			'cta2'   => array( 'Shop FANE products', '/products/?brand=fane#catalogue' ),
		),
	);
	ob_start();
	?>
	<section class="scrw-spot" aria-labelledby="scrw-spot-title">
		<div class="sc-container">
			<div class="scrw-spot__head">
				<p class="sc-eyebrow"><?php esc_html_e( 'Our authorised brands', 'soundcreations-rwanda' ); ?></p>
				<h2 id="scrw-spot-title"><?php esc_html_e( 'Official partners for the brands that matter.', 'soundcreations-rwanda' ); ?></h2>
			</div>
			<div class="scrw-spot__grid">
				<?php foreach ( $cards as $c ) : ?>
					<article class="scrw-spot__card scrw-spot__card--<?php echo esc_attr( $c['key'] ); ?>">
						<div class="scrw-spot__copy">
							<p class="scrw-spot__badge"><span aria-hidden="true"></span><?php echo esc_html( $c['badge'] ); ?></p>
							<p class="scrw-spot__word" aria-hidden="true"><?php echo esc_html( $c['word'] ); ?></p>
							<h3><?php echo esc_html( $c['title'] ); ?></h3>
							<p class="scrw-spot__text"><?php echo esc_html( $c['text'] ); ?></p>
							<ul class="scrw-spot__points">
								<?php foreach ( $c['points'] as $pt ) : ?><li><?php echo esc_html( $pt ); ?></li><?php endforeach; ?>
							</ul>
							<div class="scrw-spot__cta">
								<a class="sc-btn sc-btn--primary" href="<?php echo esc_url( home_url( $c['cta'][1] ) ); ?>"><?php echo esc_html( $c['cta'][0] ); ?> <?php echo $arrow; // phpcs:ignore ?></a>
								<a class="scrw-spot__link" href="<?php echo esc_url( home_url( $c['cta2'][1] ) ); ?>"><?php echo esc_html( $c['cta2'][0] ); ?></a>
							</div>
						</div>
						<div class="scrw-spot__media" aria-hidden="true">
							<?php foreach ( $c['images'] as $i => $im ) : ?>
								<span class="scrw-spot__plate scrw-spot__plate--<?php echo (int) $i; ?>"><img src="<?php echo esc_url( $img . $im . '.webp' ); ?>" alt="" loading="lazy" decoding="async" width="800" height="800"></span>
							<?php endforeach; ?>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

add_shortcode( 'scrw_brand_spotlight', 'scrw_render_brand_spotlight' );
add_action(
	'sc_home_after_hero',
	function () {
		echo scrw_render_brand_spotlight(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
);
