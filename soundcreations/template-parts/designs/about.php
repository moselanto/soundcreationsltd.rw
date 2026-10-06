<?php
/**
 * Page design: about. Rendered by the page template AND by the
 * "SC Page Design" Elementor widget, so the designed layout can be placed,
 * reordered and combined with Elementor sections.
 *
 * @package SoundCreations
 */

if ( defined( 'ABSPATH' ) === false ) {
	exit;
}

// About hero photo: the CITAM auditorium install (2026-09-22 owner request),
// replacing about-photo.jpg. This is the FALLBACK only -- a URL saved in
// Settings ("About hero: photo") wins, because sc_setting() prefers a stored
// value. about_hero_image has no entry in sc_default_settings() and the image
// pickers were deliberately left unprefilled, so this fallback is what renders
// unless someone has pasted a URL into that field.
// The matching LCP preload hint in inc/enqueue.php points at the same file and
// must be kept in step with it.
$sc_about_photo = apply_filters( 'sc_about_hero_image', sc_setting( 'about_hero_image', SC_THEME_URI . '/assets/img/about-citam.webp' ) );

/*
 * Our Work Process (2026-09-22, owner request).
 *
 * Replaces the eight-card "What We Do" grid with the four-step work process
 * carried on the original site's homepage (soundcreationsltd.com). Titles and
 * copy are lifted verbatim from there; the only edit is a stray space before
 * the full stop in the Distribution line ("warranties ." -> "warranties.").
 *
 * NEW SETTING KEYS ON PURPOSE. The old section read 'about_exp_eyebrow', which
 * IS present in sc_default_settings() and was therefore written into the
 * soundcreations_settings option by the one-shot
 * sc_core_prefill_settings_from_defaults() pass. sc_setting() prefers a stored
 * value over any default, so re-pointing that key at new copy would have been
 * silently overridden by the stored "What We Do" and the change would not have
 * appeared on the live site. 'about_process_eyebrow' and 'about_process_items'
 * have no stored value, so these defaults resolve immediately -- and both keys
 * are registered in the core plugin so the owner can still edit them.
 */
$sc_proc_default = "Consultation & Design | We listen, we visualize with our new client, we propose, we reach agreements & we represent the solution. | /service/consultancy/\nDistribution | From the most affordable to the substantial investments, we keep the quality 100% and the warranties. | /distribution-dealership/\nIntegration | Our promise is professional installations, system trainings, seamless handovers and guaranteed. | /service/integration/\nSupport & Training | Comprehensive after-sales support, including a 1-year warranty service after installation. | /service/after-sale-services/";
$sc_proc_raw   = sc_setting( 'about_process_items', $sc_proc_default );
$sc_proc_items = array();
foreach ( preg_split( "/\r\n|\r|\n/", $sc_proc_raw ) as $sc_line ) {
	$sc_line = trim( $sc_line );
	if ( $sc_line === '' ) {
		continue;
	}
	// Third field is an optional link target (2026-09-22 owner request). The
	// limit is 3 so a description containing a pipe still parses, and a step
	// written with only "Title | Description" keeps working -- it simply renders
	// as a plain card rather than a link.
	$sc_parts = array_map( 'trim', explode( '|', $sc_line, 3 ) );
	$sc_url   = isset( $sc_parts[2] ) ? $sc_parts[2] : '';
	// Relative paths MUST go through home_url() so they stay correct wherever
	// WordPress is installed. The site now runs at the domain root; it was
	// previously served from the /newwebsite/ subdirectory, where a bare
	// "/service/consultancy/" resolved against the domain root and 404'd.
	// Absolute URLs pass through unchanged.
	if ( '' !== $sc_url && 0 !== strpos( $sc_url, 'http' ) ) {
		$sc_url = home_url( $sc_url );
	}
	$sc_proc_items[] = array(
		'title' => $sc_parts[0],
		'desc'  => isset( $sc_parts[1] ) ? $sc_parts[1] : '',
		'url'   => $sc_url,
	);
}
// Icons follow the original site's set: consultation (speech + group),
// distribution (globe), integration (operator at a console), support (gear).
// Indexed modulo the array so an owner adding a fifth step still renders.
$sc_proc_icons = array(
	'<path d="M2.5 5A1.5 1.5 0 0 1 4 3.5h7A1.5 1.5 0 0 1 12.5 5v3.5A1.5 1.5 0 0 1 11 10H6l-3.5 2.5V10z"/><path d="M15 6.5h5A1.5 1.5 0 0 1 21.5 8v3.5a1.5 1.5 0 0 1-1.5 1.5v2l-2.5-2h-2"/><circle cx="6" cy="17" r="1.5"/><circle cx="12" cy="17" r="1.5"/><circle cx="18" cy="17" r="1.5"/><path d="M3 22a3 3 0 0 1 6 0"/><path d="M9 22a3 3 0 0 1 6 0"/><path d="M15 22a3 3 0 0 1 6 0"/>',
	'<circle cx="12" cy="12" r="9"/><path d="M3 12h18"/><path d="M12 3c2.6 2.5 4 5.6 4 9s-1.4 6.5-4 9c-2.6-2.5-4-5.6-4-9s1.4-6.5 4-9z"/>',
	'<circle cx="12" cy="6.4" r="2.6"/><path d="M8.5 13.4a3.5 3.5 0 0 1 7 0"/><path d="M5 17h14l1.6 3.6H3.4z"/>',
	'<circle cx="12" cy="12" r="3.2"/><path d="M19.2 14.9a1.7 1.7 0 0 0 .34 1.87l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.7 1.7 0 0 0-2.9 1.22V21a2 2 0 1 1-4 0v-.11a1.7 1.7 0 0 0-2.9-1.22l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.7 1.7 0 0 0-1.22-2.9H3a2 2 0 1 1 0-4h.11a1.7 1.7 0 0 0 1.22-2.9l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.7 1.7 0 0 0 2.9-1.22V3a2 2 0 1 1 4 0v.11a1.7 1.7 0 0 0 2.9 1.22l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.7 1.7 0 0 0 1.22 2.9H21a2 2 0 1 1 0 4h-.11a1.7 1.7 0 0 0-1.56 1.03z"/>',
);
?>

<section class="sc-about-hero sc-section--tight" id="about-intro">
	<div class="sc-container sc-journey">
		<div class="sc-journey__text">
			<nav class="sc-breadcrumb" aria-label="Breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span class="sep">/</span><span aria-current="page">About</span></nav>
			<p class="sc-eyebrow"><?php echo esc_html( sc_setting( 'about_hero_eyebrow', 'Who We Are' ) ); ?></p>
			<h1 class="sc-about-hero__title"><?php echo esc_html( sc_setting( 'about_hero_title', 'If it sounds good, it’s Sound Creations' ) ); ?></h1>
			<p class="sc-journey__lead"><?php echo sc_rich_e( sc_setting( 'about_journey_p1', 'Founded in 2004, Sound Creations Ltd has grown into a leading provider of professional audio, visual, lighting and acoustic solutions across East Africa and beyond. What began as a specialist audio company is today a full-service integrator — designing, supplying, installing and supporting complete systems for the region’s most demanding spaces.' ) ); ?></p>
			<div class="sc-about-hero__actions">
				<a class="sc-btn sc-btn--primary" href="<?php echo esc_url( home_url( '/request-a-consultation/' ) ); ?>"><?php esc_html_e( 'Request a Consultation', 'soundcreations' ); ?> <span aria-hidden="true">&rarr;</span></a>
				<a class="sc-btn sc-btn--ghost" href="<?php echo esc_url( home_url( '/projects/' ) ); ?>"><?php esc_html_e( 'See our projects', 'soundcreations' ); ?></a>
			</div>
		</div>
		<div class="sc-about-hero__media" style="align-self:stretch;background-image:url('<?php echo esc_url( $sc_about_photo ); ?>');" role="img" aria-label="<?php esc_attr_e( 'Sound Creations at work', 'soundcreations' ); ?>"></div>
	</div>
</section>

<section class="sc-section sc-section--tight" id="work-process">
	<div class="sc-container">
		<div class="sc-workproc__head">
			<p class="sc-eyebrow"><?php echo esc_html( sc_setting( 'about_process_eyebrow', 'Our Work Process' ) ); ?></p>
			<h2 class="sc-about-sec__title"><?php echo esc_html( sc_setting( 'about_process_title', 'One partner, from first conversation to long-term support' ) ); ?></h2>
		</div>
		<div class="sc-workproc">
			<?php
			foreach ( $sc_proc_items as $sc_i => $sc_step ) :
				// Each step links to its Service page when a third field is present.
				// The whole card becomes the anchor so the icon, title and copy are
				// one target, rather than burying a small "read more" link. Steps
				// without a URL still render as a plain div.
				$sc_has_link = ( '' !== $sc_step['url'] );
				$sc_tag      = $sc_has_link ? 'a' : 'div';
				?>
				<<?php echo $sc_tag; ?> class="sc-workproc__item"<?php echo $sc_has_link ? ' href="' . esc_url( $sc_step['url'] ) . '"' : ''; ?>>
					<span class="sc-workproc__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><?php echo $sc_proc_icons[ $sc_i % count( $sc_proc_icons ) ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static inline SVG. ?></svg></span>
					<h3 class="sc-workproc__title"><?php echo esc_html( $sc_step['title'] ); ?></h3>
					<?php if ( '' !== $sc_step['desc'] ) : ?>
						<p class="sc-workproc__desc"><?php echo esc_html( $sc_step['desc'] ); ?></p>
					<?php endif; ?>
				</<?php echo $sc_tag; ?>>
				<?php
			endforeach;
			?>
		</div>
	</div>
</section>

<section class="sc-section sc-section--tight sc-about-brands" id="our-brands">
	<div class="sc-container sc-about-brands__head">
		<div>
			<p class="sc-eyebrow"><?php echo esc_html( sc_setting( 'about_partners_eyebrow', 'Our Brands' ) ); ?></p>
			<h2 class="sc-about-sec__title"><?php echo esc_html( sc_setting( 'about_partners_title', 'World-class brands, supported locally' ) ); ?></h2>
		</div>
		<a class="sc-about-brands__all" href="<?php echo esc_url( get_post_type_archive_link( 'sc_brand' ) ? get_post_type_archive_link( 'sc_brand' ) : home_url( '/brands/' ) ); ?>"><?php esc_html_e( 'View all brands', 'soundcreations' ); ?> <span aria-hidden="true">&rarr;</span></a>
	</div>
	<div class="sc-section--partners sc-about-brands__strip">
		<div class="sc-container sc-partners__wrap">
			<span class="sc-partners__label"><?php echo esc_html( sc_setting( 'home_partners_label', 'Global Technology Partners' ) ); ?></span>
			<?php echo do_shortcode( '[sc_partners]' ); ?>
		</div>
	</div>
</section>

<?php do_action( 'sc_about_after_brands' ); ?>

<section class="sc-section sc-section--tight" id="company-profiles">
	<div class="sc-container">
		<?php echo do_shortcode( '[sc_profiles]' ); ?>
	</div>
</section>

<?php
