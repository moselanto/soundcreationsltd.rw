<?php
/**
 * Page design: fane. Rendered by the page template AND by the
 * "SC Page Design" Elementor widget, so the designed layout can be placed,
 * reordered and combined with Elementor sections.
 *
 * @package SoundCreations
 */

if ( defined( 'ABSPATH' ) === false ) {
	exit;
}

$sc_img  = SC_THEME_URI . '/assets/img/fane';
$sc_sol  = SC_THEME_URI . '/assets/img/solutions';
$sc_proj = SC_THEME_URI . '/assets/img/projects';
$sc_spec = $sc_img . '/dist-network.jpg';

$sc_consult = home_url( '/request-a-consultation/' );
$sc_products_url = sc_setting( 'fane_products_url', home_url( '/products/' ) );
$sc_apps_url = sc_setting( 'fane_apps_url', home_url( '/projects/' ) );
$sc_catalogue = trim( (string) sc_setting( 'fane_catalogue_url' ) );

$sc_pills = array(
	array( '65+', 'Years of Engineering Heritage', '<circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/>' ),
	array( 'Precision', 'Engineered for performance', '<circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/>' ),
	array( 'Built to Last', 'Reliability in every environment', '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>' ),
	array( 'Global Standards', 'Designed & engineered in the UK', '<circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>' ),
);

$sc_diff = array(
	array( 'Core', 'Engineered for accuracy and clarity' ),
	array( 'Voice Coil', 'High-power handling and efficiency' ),
	array( 'Basket', 'Rigid, lightweight and durable' ),
	array( 'Magnet System', 'High flux, low distortion motor design' ),
	array( 'Complete Driver', 'Engineered as one precision system' ),
);

$sc_timeline = array(
	array( '1958', 'FANE is founded in the UK with a passion for loudspeaker innovation.' ),
	array( '1960s - 70s', 'Growth and expansion of professional audio component technology.' ),
	array( '1980s - 90s', 'FANE becomes a trusted name in professional loudspeaker components.' ),
	array( '2000s', 'Continued innovation and investment in engineering excellence.' ),
	array( 'Today', 'FANE components power systems in the world’s most demanding venues.' ),
);

$sc_apps = array(
	array( 'Live Sound', $sc_proj . '/performance.jpg' ),
	array( 'Installed Audio', $sc_sol . '/audio.jpg' ),
	array( 'Worship', $sc_proj . '/worship.jpg' ),
	array( 'Touring', $sc_img . '/app-touring.jpg' ),
	array( 'Studio & Recording', $sc_sol . '/broadcast.jpg' ),
	array( 'Hospitality', $sc_img . '/app-hospitality.jpg' ),
	array( 'Custom Design', $sc_img . '/app-custom.jpg' ),
);


$sc_why = array(
	array( 'Heritage', 'Decades of loudspeaker engineering experience.', '<circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/>' ),
	array( 'Engineering', 'Precision components designed for demanding applications.', '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>' ),
	array( 'Performance', 'Reliable, consistent performance worldwide.', '<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>' ),
	array( 'Flexibility', 'Solutions from individual drivers to complete loudspeaker systems.', '<path d="M12 2 2 7l10 5 10-5-10-5z"/><path d="m2 17 10 5 10-5"/><path d="m2 12 10 5 10-5"/>' ),
);

$sc_dist = array(
	array( 'Authorised Distribution', '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/>' ),
	array( 'Technical Expertise', '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>' ),
	array( 'System Integration', '<rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><line x1="9" y1="1" x2="9" y2="4"/><line x1="15" y1="1" x2="15" y2="4"/><line x1="9" y1="20" x2="9" y2="23"/><line x1="15" y1="20" x2="15" y2="23"/><line x1="20" y1="9" x2="23" y2="9"/><line x1="20" y1="14" x2="23" y2="14"/><line x1="1" y1="9" x2="4" y2="9"/><line x1="1" y1="14" x2="4" y2="14"/>' ),
	array( 'After-Sales Support', '<path d="M3 18v-6a9 9 0 0 1 18 0v6"/><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3z"/><path d="M3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"/>' ),
);

$sc_arrow = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>';
?>

<section class="sc-fane-hero">
	<div class="sc-container sc-fane-hero__grid">
		<div class="sc-fane-hero__left">
			<div class="sc-fane-mark"><span class="sc-fane-mark__bar" aria-hidden="true"></span>Fane Africa</div>
			<h1 class="sc-fane-hero__title"><?php echo esc_html( sc_setting( 'fane_title', 'Engineering sound since 1958.' ) ); ?></h1>
			<p class="sc-lead sc-fane-hero__lead"><?php echo sc_rich_e( sc_setting( 'fane_lead', 'Precision-engineered loudspeaker components built for demanding professional applications, trusted by sound professionals around the world.' ) ); ?></p>
			<div class="sc-fane-hero__cta">
				<a class="sc-btn sc-btn--primary" href="<?php echo esc_url( $sc_products_url ); ?>"><?php esc_html_e( 'Explore FANE Products', 'soundcreations' ); ?> <?php echo $sc_arrow; ?></a>
			</div>
		</div>
		<div class="sc-fane-hero__media">
			<img src="<?php echo esc_url( sc_setting( 'fane_hero_image', $sc_img . '/fane-hero-sov15.webp' ) ); ?>" alt="<?php esc_attr_e( 'FANE Sovereign 15-400LF professional loudspeaker driver', 'soundcreations' ); ?>">
		</div>
	</div>
</section>

<section class="sc-section sc-fane-dealer" id="fane-catalogue">
	<div class="sc-container">
		<div class="sc-cta-band sc-cta-band--photo" style="background-image:url('<?php echo esc_url( $sc_img . '/fane-hero.jpg' ); ?>');">
			<div class="sc-cta-band__inner">
				<h2><?php echo esc_html( sc_setting( 'fane_cta_title', 'Become a FANE Dealer.' ) ); ?></h2>
				<p class="sc-lead" style="margin:0 0 1.5rem;"><?php echo sc_rich_e( sc_setting( 'fane_cta_text', 'We’re building the FANE dealer network across Kenya, Uganda, Tanzania, Rwanda, Burundi, Ethiopia, South Sudan and the Democratic Republic of Congo. Sound Creations focuses on large, project-based installations, so we partner with dealers who can stock and sell FANE components at the local level, supported by product knowledge, technical support, marketing resources and local availability through Sound Creations.' ) ); ?></p>
				<div class="sc-fane-cta__btns">
					<a class="sc-btn sc-btn--primary" href="<?php echo esc_url( home_url( '/become-a-dealer/' ) ); ?>"><?php esc_html_e( 'Become a FANE Dealer', 'soundcreations' ); ?> <?php echo $sc_arrow; ?></a>
					<?php if ( strlen( $sc_catalogue ) > 0 ) : ?>
					<a class="sc-btn sc-btn--ghost" href="<?php echo esc_url( $sc_catalogue ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Download Catalogue', 'soundcreations' ); ?> <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg></a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="sc-section sc-fane-alt" id="fane-heritage">
	<div class="sc-container">
		<p class="sc-eyebrow"><?php esc_html_e( 'Our heritage', 'soundcreations' ); ?></p>
		<h2 class="sc-fane-heritage__title"><?php echo esc_html( sc_setting( 'fane_heritage_title', '65+ years of loudspeaker engineering.' ) ); ?></h2>
		<div class="sc-fane-heritage">
			<div class="sc-timeline">
				<?php foreach ( $sc_timeline as $t ) : ?>
					<div class="sc-tl-item">
						<span class="sc-tl-item__dot" aria-hidden="true"></span>
						<span class="sc-tl-item__year"><?php echo esc_html( $t[0] ); ?></span>
						<span class="sc-tl-item__text"><?php echo esc_html( $t[1] ); ?></span>
					</div>
				<?php endforeach; ?>
			</div>
			<div class="sc-fane-heritage__img">
				<img src="<?php echo esc_url( $sc_img . '/fane-headquarters.webp' ); ?>" alt="<?php esc_attr_e( 'FANE headquarters and manufacturing facility', 'soundcreations' ); ?>" loading="lazy" width="1180" height="266">
			</div>
		</div>
	</div>
</section>

<section class="sc-section" id="fane-difference">
	<div class="sc-container sc-fane-diff">
		<div class="sc-fane-diff__intro">
			<p class="sc-eyebrow"><?php esc_html_e( 'The FANE difference', 'soundcreations' ); ?></p>
			<h2><?php echo esc_html( sc_setting( 'fane_diff_title', 'Built from the inside out.' ) ); ?></h2>
			<p class="sc-support-sub"><?php echo sc_rich_e( sc_setting( 'fane_diff_body', 'Every FANE component is designed and engineered to work in perfect harmony - delivering the performance, reliability and consistency professionals depend on.' ) ); ?></p>
			<a class="sc-btn sc-btn--ghost" href="https://www.fane-international.com/" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Discover Our Technology', 'soundcreations' ); ?> <?php echo $sc_arrow; ?></a>
		</div>
		<div class="sc-fane-diff__media">
			<img src="<?php echo esc_url( $sc_img . '/fane-exploded.jpg' ); ?>" alt="<?php esc_attr_e( 'Exploded view of a FANE loudspeaker driver', 'soundcreations' ); ?>" loading="lazy">
			<div class="sc-fane-diff__labels">
				<?php foreach ( $sc_diff as $d ) : ?>
					<div class="sc-fane-diff__label"><span class="sc-fane-diff__dot" aria-hidden="true"></span><div><strong><?php echo esc_html( $d[0] ); ?></strong><span><?php echo esc_html( $d[1] ); ?></span></div></div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>



<?php
/*
 * Social channels Sound Creations uses to promote FANE. Every link below is
 * editable in Sound Creations -> Settings (FANE page - social links). Only the
 * FANE fields you fill in are shown - a blank FANE field is hidden, with no
 * company-wide fallback, and the whole bar disappears when none are set.
 */
$sc_fane_soc = array(
	array( 'Facebook',  sc_setting( 'fane_facebook' ),  'facebook',  '<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true"><path d="M14 9h3V6h-3c-1.7 0-3 1.3-3 3v2H8v3h3v7h3v-7h3l1-3h-4V9c0-.6.4-1 1-1z"/></svg>' ),
	array( 'Instagram', sc_setting( 'fane_instagram' ), 'instagram', '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>' ),
	array( 'YouTube',   sc_setting( 'fane_youtube' ),   'youtube',   '<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true"><path d="M23 12s0-3.2-.4-4.7a2.5 2.5 0 0 0-1.8-1.8C19.2 5 12 5 12 5s-7.2 0-8.8.5A2.5 2.5 0 0 0 1.4 7.3C1 8.8 1 12 1 12s0 3.2.4 4.7a2.5 2.5 0 0 0 1.8 1.8C4.8 19 12 19 12 19s7.2 0 8.8-.5a2.5 2.5 0 0 0 1.8-1.8C23 15.2 23 12 23 12zM9.8 15.3V8.7l5.7 3.3z"/></svg>' ),
	array( 'TikTok',    sc_setting( 'fane_tiktok' ),                                'tiktok',    '<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true"><path d="M16 3c.3 2 1.6 3.6 3.5 4v2.4c-1.3 0-2.5-.3-3.5-.9v5.6a5.6 5.6 0 1 1-5.6-5.6c.3 0 .6 0 .9.1v2.5a3.1 3.1 0 1 0 2.2 3V3H16z"/></svg>' ),
	array( 'X',         sc_setting( 'fane_x' ),         'x',         '<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true"><path d="M18.9 2H22l-7.6 8.7L23 22h-6.9l-5-6.6L4.3 22H1.2l8.2-9.4L1 2h7.1l4.5 6 6.3-6zm-2.4 18h1.9L7.6 4H5.6z"/></svg>' ),
	array( 'LinkedIn',  sc_setting( 'fane_linkedin' ),  'linkedin',  '<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true"><path d="M6.94 5a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM3 8.5h3.9V21H3zM9.5 8.5h3.7v1.7h.1c.5-.9 1.8-1.9 3.6-1.9 3.9 0 4.6 2.5 4.6 5.8V21h-3.9v-5.4c0-1.3 0-3-1.8-3s-2.1 1.4-2.1 2.9V21H9.5z"/></svg>' ),
);
$sc_fane_wa = trim( (string) sc_setting( 'fane_whatsapp' ) );
$sc_fane_wa = preg_replace( '/[^0-9]/', '', $sc_fane_wa );
if ( strlen( $sc_fane_wa ) > 0 ) {
	$sc_fane_soc[] = array( 'WhatsApp', 'https://wa.me/' . $sc_fane_wa, 'whatsapp', '<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.5 15.3L2 22l4.8-1.3A10 10 0 1 0 12 2zm0 18a8 8 0 0 1-4.1-1.1l-.3-.2-2.8.7.8-2.7-.2-.3A8 8 0 1 1 12 20zm4.4-6c-.2-.1-1.4-.7-1.6-.8s-.4-.1-.5.1-.6.8-.8 1-.3.2-.5.1a6.5 6.5 0 0 1-3.2-2.8c-.2-.4.2-.4.6-1.2.1-.2 0-.3 0-.5s-.5-1.3-.7-1.8-.4-.4-.5-.4h-.5a1 1 0 0 0-.7.3 3 3 0 0 0-.9 2.2c0 1.3 1 2.6 1.1 2.8s1.9 3 4.7 4.2c1.7.7 2.4.8 3.2.7.5-.1 1.4-.6 1.6-1.1s.2-1 .1-1.1z"/></svg>' );
}
$sc_fane_links = array();
foreach ( $sc_fane_soc as $sc_s ) {
	if ( strlen( trim( (string) $sc_s[1] ) ) > 0 ) {
		$sc_fane_links[] = $sc_s;
	}
}
$sc_fane_links = apply_filters( 'sc_fane_social_links', $sc_fane_links );

// Optional contact block under the social links (e.g. the Rwanda site's
// email and phone tiles). Filterable so a child theme can supply it.
$sc_fane_contact = (string) apply_filters( 'sc_fane_contact_html', '' );
?>
<?php if ( count( $sc_fane_links ) > 0 || '' !== $sc_fane_contact ) : ?>
<section class="sc-section sc-section--tight" id="fane-social">
	<div class="sc-container">
		<div class="sc-fane-social">
			<div class="sc-fane-social__intro">
				<p class="sc-eyebrow"><?php echo esc_html( sc_setting( 'fane_social_title', 'Follow FANE Africa\'s account' ) ); ?></p>
				<p class="sc-fane-social__lead"><?php echo esc_html( sc_setting( 'fane_social_text', 'See FANE loudspeakers, live demos and installations on the channels we use to bring the brand to East Africa and the Middle East.' ) ); ?></p>
			</div>
			<div class="sc-fane-social__links">
				<?php foreach ( $sc_fane_links as $sc_l ) : ?>
					<a class="sc-fane-social__btn sc-fane-social__btn--<?php echo esc_attr( $sc_l[2] ); ?>" href="<?php echo esc_url( $sc_l[1] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $sc_l[0] ); ?>">
						<span class="sc-fane-social__icon" aria-hidden="true"><?php echo $sc_l[3]; ?></span>
						<span class="sc-fane-social__name"><?php echo esc_html( $sc_l[0] ); ?></span>
					</a>
				<?php endforeach; ?>
			</div>
			<?php echo $sc_fane_contact; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by the filter callback. ?>
		</div>
	</div>
</section>
<?php endif; ?>

<?php
