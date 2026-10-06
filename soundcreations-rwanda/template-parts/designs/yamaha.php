<?php
/**
 * Page design: yamaha. Rendered by the page template AND by the
 * "SC Page Design" Elementor widget, so the designed layout can be placed,
 * reordered and combined with Elementor sections.
 *
 * @package SoundCreations
 */

if ( defined( 'ABSPATH' ) === false ) {
	exit;
}

$y_img   = get_stylesheet_directory_uri() . '/assets/img/';
$y_shop  = home_url( '/products/?brand=yamaha#catalogue' );
$y_quote = home_url( '/request-a-quote/' );
$y_arrow = '<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>';
$y_phone = sc_setting( 'phone', '+250 783 141 050' );
$y_tel   = sc_setting( 'phone_link', '+250783141050' );
$y_wa    = sc_setting( 'whatsapp', '250783141050' );
$y_mail  = sc_setting( 'email', 'stefic@soundcreationsltd.com' );
$y_mail2 = sc_setting( 'email2', 'fred@soundcreationsltd.com' );
$y_phone2 = sc_setting( 'phone2', '+250 782 739 889' );
$y_tel2   = sc_setting( 'phone2_link', '+250782739889' );
$y_map   = sc_setting( 'map_url', 'https://www.google.com/maps/search/?api=1&query=Sound+Creations+Ltd+KN1+Rd+Muhima+Kigali' );

/** Product slug => fallback title (the product post title is used when it exists). */
if ( ! function_exists( 'scrw_yamaha_title' ) ) {
	function scrw_yamaha_title( $slug, $fallback ) {
		$p = get_page_by_path( $slug, OBJECT, 'sc_product' );
		return ( $p && 'publish' === $p->post_status ) ? get_the_title( $p ) : $fallback;
	}
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
	array( 'Intare Kivu Arena', 'Gisenyi, Rubavu', 'Yamaha TF5 console', 'projects/intare-kivu-arena-hall.webp', '/projects/intare-kivu-arena/' ),
	array( 'Romantic Garden', 'Kigali', 'Yamaha TF5 console', 'projects/romantic-garden-interior-hd.webp', '/projects/romantic-garden/' ),
	array( 'Ntare Louisenlund School', 'Mulindi Hall', 'Yamaha MG16XU + DXS18', 'projects/ntare-louisenlund-hall-hd.webp', '/projects/ntare-louisenlund-school/' ),
	array( 'Atelier du Vin', 'Kigali', 'Yamaha MG16XU + DXS18', 'projects/atelier-du-vin-interior-hd.webp', '/projects/atelier-du-vin/' ),
);
?>

<section class="scrw-yh">
	<div class="sc-container scrw-yh__grid">
		<div class="scrw-yh__copy">
			<?php echo sc_breadcrumb( array( array( 'Home', home_url( '/' ) ), array( 'Yamaha', '' ) ) ); // phpcs:ignore ?>
			<p class="scrw-yh__badge"><span aria-hidden="true"></span><?php esc_html_e( 'Authorised Distributor in Rwanda', 'soundcreations-rwanda' ); ?></p>
			<?php $y_logo = function_exists( 'sc_brand_logo_url' ) ? sc_brand_logo_url( 'yamaha' ) : ''; ?>
			<?php if ( $y_logo ) : ?><img class="scrw-yh__logo" src="<?php echo esc_url( $y_logo ); ?>" alt="Yamaha" width="180" height="96" decoding="async" style="display:block;height:72px;width:auto;margin:0 0 1rem;background:#fff;padding:8px 14px;border-radius:10px;"><?php endif; ?>
			<h1 class="scrw-yh__title"><?php esc_html_e( 'Yamaha in Rwanda, backed by Sound Creations.', 'soundcreations-rwanda' ); ?></h1>
			<p class="scrw-yh__lead"><?php esc_html_e( 'Sound Creations Ltd Rwanda is the authorised Yamaha distributor in Rwanda. From mixing consoles and loudspeakers to keyboards, pianos, guitars and drums, we supply genuine Yamaha products with local stock, warranty and expert support from our Kigali showroom.', 'soundcreations-rwanda' ); ?></p>
			<div class="scrw-yh__cta">
				<a class="sc-btn sc-btn--primary" href="<?php echo esc_url( $y_shop ); ?>"><?php esc_html_e( 'Explore Yamaha products', 'soundcreations-rwanda' ); ?> <?php echo $y_arrow; // phpcs:ignore ?></a>
				<a class="sc-btn sc-btn--ghost" href="<?php echo esc_url( $y_quote ); ?>"><?php esc_html_e( 'Request a quote', 'soundcreations-rwanda' ); ?></a>
			</div>
			<div class="scrw-yh__quick">
				<span class="scrw-yh__quick-k"><?php esc_html_e( 'Call or WhatsApp', 'soundcreations-rwanda' ); ?></span>
				<a href="tel:<?php echo esc_attr( $y_tel ); ?>"><?php echo esc_html( $y_phone ); ?></a>
				<a href="tel:<?php echo esc_attr( $y_tel2 ); ?>"><?php echo esc_html( $y_phone2 ); ?></a>
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
	<div class="sc-container">
		<div class="scrw-yvisit">
			<div class="scrw-yvisit__media">
				<img src="<?php echo esc_url( $y_img . 'about-hero-showroom.webp' ); ?>" alt="<?php esc_attr_e( 'Guitars, keyboards and Yamaha mixers at the Sound Creations Rwanda showroom', 'soundcreations-rwanda' ); ?>" loading="lazy" decoding="async">
				<span class="scrw-yvisit__pin"><svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 21s-7-6.2-7-11.5A7 7 0 0 1 19 9.5C19 14.8 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.5"/></svg><?php esc_html_e( 'KN1 Rd, Muhima, Kigali', 'soundcreations-rwanda' ); ?></span>
			</div>
			<div class="scrw-yvisit__copy">
				<p class="sc-eyebrow"><?php esc_html_e( 'Visit the showroom', 'soundcreations-rwanda' ); ?></p>
				<h2><?php esc_html_e( 'See, hear and play Yamaha in Kigali.', 'soundcreations-rwanda' ); ?></h2>
				<p class="sc-lead"><?php esc_html_e( 'Try mixers, monitors, keyboards, pianos and guitars, and talk to our team about the right setup for your church, school, studio or venue.', 'soundcreations-rwanda' ); ?></p>
				<div class="scrw-yvisit__facts">
					<div class="scrw-yvisit__fact">
						<span class="scrw-yvisit__ico" aria-hidden="true"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s-7-6.2-7-11.5A7 7 0 0 1 19 9.5C19 14.8 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.5"/></svg></span>
						<div><strong><?php esc_html_e( 'Address', 'soundcreations-rwanda' ); ?></strong><span>KN1 Rd, Muhima, Kigali</span><a href="<?php echo esc_url( $y_map ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Get directions', 'soundcreations-rwanda' ); ?> &rarr;</a></div>
					</div>
					<div class="scrw-yvisit__fact">
						<span class="scrw-yvisit__ico" aria-hidden="true"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></span>
						<div><strong><?php esc_html_e( 'Opening hours', 'soundcreations-rwanda' ); ?></strong><span><?php esc_html_e( 'Mon–Fri 9:00 AM – 6:00 PM', 'soundcreations-rwanda' ); ?></span><span><?php esc_html_e( 'Sat 9:00 AM – 1:30 PM · Sun closed', 'soundcreations-rwanda' ); ?></span></div>
					</div>
				</div>
			</div>
		</div>

		<div class="scrw-ycontact">
			<p class="scrw-ycontact__title"><?php esc_html_e( 'Talk to our Yamaha team', 'soundcreations-rwanda' ); ?></p>
			<div class="scrw-ycontact__grid">
				<?php
				$y_lines = array(
					array( __( 'Main line', 'soundcreations-rwanda' ), $y_phone, $y_tel ),
					array( __( 'Second line', 'soundcreations-rwanda' ), $y_phone2, $y_tel2 ),
				);
				foreach ( $y_lines as $ln ) :
					$y_digits = preg_replace( '/[^0-9]/', '', (string) $ln[2] );
					?>
					<div class="scrw-ycontact__card">
						<span class="scrw-ycontact__k"><?php echo esc_html( $ln[0] ); ?></span>
						<a class="scrw-ycontact__num" href="tel:<?php echo esc_attr( $ln[2] ); ?>"><?php echo esc_html( $ln[1] ); ?></a>
						<div class="scrw-ycontact__btns">
							<a class="scrw-ycontact__btn" href="tel:<?php echo esc_attr( $ln[2] ); ?>"><svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2z"/></svg><?php esc_html_e( 'Call', 'soundcreations-rwanda' ); ?></a>
							<a class="scrw-ycontact__btn scrw-ycontact__btn--wa" href="<?php echo esc_url( 'https://wa.me/' . $y_digits . '?text=' . rawurlencode( 'Hello Sound Creations Rwanda, I would like to enquire about Yamaha products.' ) ); ?>" target="_blank" rel="noopener"><svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 11.5a8.4 8.4 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.4 8.4 0 0 1-3.8-.9L3 21l1.9-5.7a8.4 8.4 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.4 8.4 0 0 1 3.8-.9h.5a8.5 8.5 0 0 1 8 8z"/></svg><?php esc_html_e( 'WhatsApp', 'soundcreations-rwanda' ); ?></a>
						</div>
					</div>
				<?php endforeach; ?>
				<div class="scrw-ycontact__card">
					<span class="scrw-ycontact__k"><?php esc_html_e( 'Email', 'soundcreations-rwanda' ); ?></span>
					<a class="scrw-ycontact__mail" href="mailto:<?php echo esc_attr( $y_mail ); ?>"><?php echo esc_html( $y_mail ); ?></a>
					<?php if ( $y_mail2 ) : ?><a class="scrw-ycontact__mail" href="mailto:<?php echo esc_attr( $y_mail2 ); ?>"><?php echo esc_html( $y_mail2 ); ?></a><?php endif; ?>
				</div>
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
