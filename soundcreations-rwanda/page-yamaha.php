<?php
/**
 * Template for the /yamaha/ page: Sound Creations Rwanda, Authorised Yamaha
 * Distributor in Rwanda. Same idea as the FANE page: recognition for the
 * brand, the range we carry, featured products, Yamaha at work in our
 * projects, and the Kigali showroom.
 *
 * Product cards use the bundled official photos (assets/img/products-official/)
 * and link to each product page; titles come from the product posts when they
 * exist. Edit the lists in the arrays below.
 *
 * @package SoundCreationsRwanda
 */

if ( defined( 'ABSPATH' ) === false ) {
	exit;
}
get_header();

$y_img   = get_stylesheet_directory_uri() . '/assets/img/';
$y_shop  = home_url( '/products/?brand=yamaha#catalogue' );
$y_quote = home_url( '/request-a-quote/' );
$y_arrow = '<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>';
$y_phone = sc_setting( 'phone', '+250 783 141 050' );
$y_tel   = sc_setting( 'phone_link', '+250783141050' );
$y_wa    = sc_setting( 'whatsapp', '250783141050' );
$y_mail  = sc_setting( 'email', 'sales@soundcreationsltd.com' );
$y_map   = sc_setting( 'map_url', 'https://www.google.com/maps/search/?api=1&query=Sound+Creations+Ltd+KN1+Rd+Muhima+Kigali' );

/** Product slug => fallback title (the product post title is used when it exists). */
function scrw_yamaha_title( $slug, $fallback ) {
	$p = get_page_by_path( $slug, OBJECT, 'sc_product' );
	return ( $p && 'publish' === $p->post_status ) ? get_the_title( $p ) : $fallback;
}

$y_trust = array(
	array( 'Authorised distributor', 'Officially appointed to supply Yamaha professional audio and musical instruments in Rwanda.', '<path d="M12 3l7 3v6c0 4.5-3 8-7 9-4-1-7-4.5-7-9V6z"/><path d="M9 12l2 2 4-4"/>' ),
	array( 'Genuine products', 'Every unit comes through official Yamaha channels, never grey imports.', '<circle cx="12" cy="9" r="6"/><path d="M8.5 14.5L7 22l5-3 5 3-1.5-7.5"/>' ),
	array( 'Local warranty', 'Warranty claims handled here in Kigali, with genuine spare parts.', '<rect x="4" y="3" width="16" height="18" rx="2"/><path d="M8 8h8M8 12h8M8 16h5"/>' ),
	array( 'Expert support', 'System design, installation, training and after-sale service from our team.', '<path d="M4 13v-1a8 8 0 0 1 16 0v1"/><path d="M20 14a2 2 0 0 1-2 2h-2v-5h2a2 2 0 0 1 2 2zM4 14a2 2 0 0 0 2 2h2v-5H6a2 2 0 0 0-2 2z"/>' ),
);

$y_range = array(
	array( 'Digital Mixing Consoles', 'TF and DM3 series for live sound, churches and venues.', 'yamaha-tf5' ),
	array( 'Analogue Mixers', 'MG and MGP series, reliable and simple to run.', 'yamaha-mg16xu' ),
	array( 'Powered Loudspeakers', 'DBR and CBR series for PA, events and installs.', 'yamaha-dbr12' ),
	array( 'Studio Monitors', 'The HS series, the studio standard for accurate mixing.', 'yamaha-hs8i' ),
	array( 'Arranger Keyboards', 'PSR-SX and PSR-E series for worship, stage and study.', 'yamaha-psr-sx920' ),
	array( 'Digital Pianos', 'Arius and P series with authentic piano touch.', 'yamaha-ydp-105' ),
	array( 'Guitars', 'Acoustic and classical guitars for every player.', 'yamaha-f310' ),
	array( 'Acoustic Drums', 'Stage Custom Birch kits with classic Yamaha tone.', 'yamaha-sbp2f5' ),
);

$y_featured = array(
	'yamaha-tf5'       => 'Yamaha TF5 Digital Mixing Console',
	'yamaha-dm3s'      => 'Yamaha DM3 Standard Digital Mixer',
	'yamaha-mg16xu'    => 'Yamaha MG16XU Mixer',
	'yamaha-dbr12'     => 'Yamaha DBR12 Powered Loudspeaker',
	'yamaha-hs8i'      => 'Yamaha HS8I Studio Monitor',
	'yamaha-psr-sx920' => 'Yamaha PSR-SX920 Arranger Workstation',
	'yamaha-ydp-105'   => 'Yamaha Arius YDP-105 Digital Piano',
	'yamaha-f310'      => 'Yamaha F310 Acoustic Guitar',
);

$y_projects = array(
	array( 'Intare Kivu Arena', 'Gisenyi, Rubavu', 'Yamaha TF5 digital console', 'projects/intare-kivu-arena-hall.webp', '/projects/intare-kivu-arena/' ),
	array( 'Romantic Garden', 'Kigali', 'Yamaha TF5 digital console', 'projects/romantic-garden-interior-hd.webp', '/projects/romantic-garden/' ),
	array( 'Ntare Louisenlund School', 'Mulindi Hall', 'Yamaha MG16XU mixer, DXS18 subwoofer', 'projects/ntare-louisenlund-hall-hd.webp', '/projects/ntare-louisenlund-school/' ),
	array( 'Atelier du Vin', 'Kigali', 'Yamaha MG16XU mixer, DXS18 subwoofer', 'projects/atelier-du-vin-interior-hd.webp', '/projects/atelier-du-vin/' ),
);
?>

<section class="scrw-yh">
	<div class="sc-container scrw-yh__grid">
		<div class="scrw-yh__copy">
			<?php echo sc_breadcrumb( array( array( 'Home', home_url( '/' ) ), array( 'Yamaha', '' ) ) ); // phpcs:ignore ?>
			<p class="scrw-yh__badge"><span aria-hidden="true"></span><?php esc_html_e( 'Authorised Distributor in Rwanda', 'soundcreations-rwanda' ); ?></p>
			<p class="scrw-yh__word" aria-hidden="true">YAMAHA</p>
			<h1 class="scrw-yh__title"><?php esc_html_e( 'Yamaha in Rwanda, backed by Sound Creations.', 'soundcreations-rwanda' ); ?></h1>
			<p class="scrw-yh__lead"><?php esc_html_e( 'Sound Creations Ltd Rwanda is the authorised Yamaha distributor in Rwanda. From mixing consoles and loudspeakers to keyboards, pianos, guitars and drums, we supply genuine Yamaha products with local stock, warranty and expert support from our Kigali showroom.', 'soundcreations-rwanda' ); ?></p>
			<div class="scrw-yh__cta">
				<a class="sc-btn sc-btn--primary" href="<?php echo esc_url( $y_shop ); ?>"><?php esc_html_e( 'Explore Yamaha products', 'soundcreations-rwanda' ); ?> <?php echo $y_arrow; // phpcs:ignore ?></a>
				<a class="sc-btn sc-btn--ghost" href="<?php echo esc_url( $y_quote ); ?>"><?php esc_html_e( 'Request a quote', 'soundcreations-rwanda' ); ?></a>
			</div>
		</div>
		<figure class="scrw-yh__media">
			<img src="<?php echo esc_url( $y_img . 'yamaha-hero-showroom.webp' ); ?>" alt="<?php esc_attr_e( 'Yamaha display at the Sound Creations Rwanda showroom in Kigali', 'soundcreations-rwanda' ); ?>" width="1400" height="1120" fetchpriority="high" decoding="async">
			<figcaption class="scrw-yh__tag"><strong><?php esc_html_e( 'Kigali showroom', 'soundcreations-rwanda' ); ?></strong><span><?php esc_html_e( 'KN1 Rd, Muhima. Try before you buy.', 'soundcreations-rwanda' ); ?></span></figcaption>
		</figure>
	</div>
</section>

<section class="scrw-ytrust" aria-label="<?php esc_attr_e( 'Why buy Yamaha from Sound Creations Rwanda', 'soundcreations-rwanda' ); ?>">
	<div class="sc-container scrw-ytrust__grid">
		<?php foreach ( $y_trust as $t ) : ?>
			<div class="scrw-ytrust__item">
				<span class="scrw-ytrust__icon" aria-hidden="true"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><?php echo $t[2]; // phpcs:ignore ?></svg></span>
				<div><h3><?php echo esc_html( $t[0] ); ?></h3><p><?php echo esc_html( $t[1] ); ?></p></div>
			</div>
		<?php endforeach; ?>
	</div>
</section>

<section class="sc-section" id="yamaha-range">
	<div class="sc-container">
		<div class="scrw-ysec-head">
			<div>
				<p class="sc-eyebrow"><?php esc_html_e( 'The Yamaha range', 'soundcreations-rwanda' ); ?></p>
				<h2><?php esc_html_e( 'One brand for the stage, the studio and the classroom.', 'soundcreations-rwanda' ); ?></h2>
			</div>
			<a class="scrw-ylink" href="<?php echo esc_url( $y_shop ); ?>"><?php esc_html_e( 'View all Yamaha products', 'soundcreations-rwanda' ); ?> <?php echo $y_arrow; // phpcs:ignore ?></a>
		</div>
		<div class="scrw-yrange">
			<?php foreach ( $y_range as $r ) : ?>
				<a class="scrw-yrange__card" href="<?php echo esc_url( $y_shop ); ?>">
					<span class="scrw-yrange__plate"><img src="<?php echo esc_url( $y_img . 'products-official/' . $r[2] . '.webp' ); ?>" alt="" loading="lazy" decoding="async" width="800" height="800"></span>
					<span class="scrw-yrange__body"><h3><?php echo esc_html( $r[0] ); ?></h3><p><?php echo esc_html( $r[1] ); ?></p></span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="sc-section scrw-yalt" id="yamaha-featured">
	<div class="sc-container">
		<div class="scrw-ysec-head">
			<div>
				<p class="sc-eyebrow"><?php esc_html_e( 'Featured Yamaha products', 'soundcreations-rwanda' ); ?></p>
				<h2><?php esc_html_e( 'Popular with Rwanda’s churches, venues and studios.', 'soundcreations-rwanda' ); ?></h2>
			</div>
		</div>
		<div class="scrw-yfeat">
			<?php foreach ( $y_featured as $slug => $fallback ) : ?>
				<?php $title = scrw_yamaha_title( $slug, $fallback ); ?>
				<article class="scrw-yfeat__card">
					<a class="scrw-yfeat__plate" href="<?php echo esc_url( home_url( '/products/' . $slug . '/' ) ); ?>" tabindex="-1" aria-hidden="true"><img src="<?php echo esc_url( $y_img . 'products-official/' . $slug . '.webp' ); ?>" alt="" loading="lazy" decoding="async" width="800" height="800"></a>
					<div class="scrw-yfeat__body">
						<p class="scrw-yfeat__brand">Yamaha</p>
						<h3><a href="<?php echo esc_url( home_url( '/products/' . $slug . '/' ) ); ?>"><?php echo esc_html( $title ); ?></a></h3>
						<div class="scrw-yfeat__actions">
							<a class="scrw-btn scrw-btn--ghost" href="<?php echo esc_url( home_url( '/products/' . $slug . '/' ) ); ?>"><?php esc_html_e( 'View details', 'soundcreations-rwanda' ); ?></a>
							<a class="scrw-btn scrw-btn--primary" href="<?php echo esc_url( $y_quote ); ?>"><?php esc_html_e( 'Get a quote', 'soundcreations-rwanda' ); ?></a>
						</div>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="sc-section" id="yamaha-projects">
	<div class="sc-container">
		<div class="scrw-ysec-head">
			<div>
				<p class="sc-eyebrow"><?php esc_html_e( 'Yamaha at work in Rwanda', 'soundcreations-rwanda' ); ?></p>
				<h2><?php esc_html_e( 'Installed and supported by our team.', 'soundcreations-rwanda' ); ?></h2>
			</div>
			<a class="scrw-ylink" href="<?php echo esc_url( home_url( '/projects/' ) ); ?>"><?php esc_html_e( 'All projects', 'soundcreations-rwanda' ); ?> <?php echo $y_arrow; // phpcs:ignore ?></a>
		</div>
		<div class="scrw-yproj">
			<?php foreach ( $y_projects as $p ) : ?>
				<a class="scrw-yproj__card" href="<?php echo esc_url( home_url( $p[4] ) ); ?>">
					<img src="<?php echo esc_url( $y_img . $p[3] ); ?>" alt="" loading="lazy" decoding="async">
					<span class="scrw-yproj__shade" aria-hidden="true"></span>
					<span class="scrw-yproj__body">
						<span class="scrw-yproj__gear"><?php echo esc_html( $p[2] ); ?></span>
						<h3><?php echo esc_html( $p[0] ); ?></h3>
						<span class="scrw-yproj__loc"><?php echo esc_html( $p[1] ); ?></span>
					</span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="sc-section scrw-yalt" id="yamaha-showroom">
	<div class="sc-container scrw-yshow">
		<div class="scrw-yshow__media"><img src="<?php echo esc_url( $y_img . 'about-hero-showroom.webp' ); ?>" alt="<?php esc_attr_e( 'Guitars, keyboards and Yamaha mixers at the Sound Creations Rwanda showroom', 'soundcreations-rwanda' ); ?>" loading="lazy" decoding="async"></div>
		<div class="scrw-yshow__copy">
			<p class="sc-eyebrow"><?php esc_html_e( 'Visit the showroom', 'soundcreations-rwanda' ); ?></p>
			<h2><?php esc_html_e( 'See, hear and play Yamaha in Kigali.', 'soundcreations-rwanda' ); ?></h2>
			<p class="sc-lead"><?php esc_html_e( 'Come in to try mixers, monitors, keyboards, pianos and guitars, and talk to our team about the right setup for your church, school, studio or venue.', 'soundcreations-rwanda' ); ?></p>
			<ul class="scrw-yshow__list">
				<li><strong><?php esc_html_e( 'Address', 'soundcreations-rwanda' ); ?></strong><span>KN1 Rd, Muhima, Kigali</span></li>
				<li><strong><?php esc_html_e( 'Hours', 'soundcreations-rwanda' ); ?></strong><span><?php esc_html_e( 'Mon–Fri 9:00 AM–6:00 PM · Sat 9:00 AM–1:30 PM', 'soundcreations-rwanda' ); ?></span></li>
				<li><strong><?php esc_html_e( 'Phone', 'soundcreations-rwanda' ); ?></strong><a href="tel:<?php echo esc_attr( $y_tel ); ?>"><?php echo esc_html( $y_phone ); ?></a></li>
				<li><strong><?php esc_html_e( 'Email', 'soundcreations-rwanda' ); ?></strong><a href="mailto:<?php echo esc_attr( $y_mail ); ?>"><?php echo esc_html( $y_mail ); ?></a></li>
			</ul>
			<div class="scrw-yh__cta">
				<a class="sc-btn sc-btn--primary" href="<?php echo esc_url( 'https://wa.me/' . $y_wa . '?text=' . rawurlencode( 'Hello Sound Creations Rwanda, I would like to enquire about Yamaha products.' ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Chat on WhatsApp', 'soundcreations-rwanda' ); ?></a>
				<a class="sc-btn sc-btn--ghost" href="<?php echo esc_url( $y_map ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Get directions', 'soundcreations-rwanda' ); ?></a>
			</div>
		</div>
	</div>
</section>

<section class="sc-section">
	<div class="sc-container">
		<div class="scrw-ycta">
			<div>
				<p class="scrw-yh__badge scrw-yh__badge--light"><span aria-hidden="true"></span><?php esc_html_e( 'Authorised Distributor in Rwanda', 'soundcreations-rwanda' ); ?></p>
				<h2><?php esc_html_e( 'Planning a Yamaha system?', 'soundcreations-rwanda' ); ?></h2>
				<p><?php esc_html_e( 'Tell us about your venue or project and we will recommend, quote and deliver the right Yamaha setup, with installation and support from our Kigali team.', 'soundcreations-rwanda' ); ?></p>
			</div>
			<div class="scrw-ycta__btns">
				<a class="sc-btn sc-btn--primary" href="<?php echo esc_url( home_url( '/request-a-consultation/' ) ); ?>"><?php esc_html_e( 'Request a consultation', 'soundcreations-rwanda' ); ?> <?php echo $y_arrow; // phpcs:ignore ?></a>
				<a class="sc-btn sc-btn--ghost" href="https://www.yamaha.com/" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Visit yamaha.com', 'soundcreations-rwanda' ); ?></a>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
