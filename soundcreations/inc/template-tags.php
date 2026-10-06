<?php
/**
 * Template helpers and the central business-settings API.
 * Single source of truth for contact / WhatsApp / social data.
 *
 * @package SoundCreations
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Brief-verified defaults. Overridable in wp-admin when the
 * Sound Creations Core plugin is active (Sound Creations -> Settings).
 */
function sc_default_settings() {
	return array(
		'company_name' => 'Sound Creations Ltd',
		'slogan'       => 'If it sounds good, it’s Sound Creations.',
		'profiles_eyebrow'      => 'Company Profiles',
		'profiles_title'        => 'Download our profiles',
		'profiles_intro'        => 'Get the full picture of who we are and what we deliver. Download our company profile to share with your team.',
		'company_profile_desc'  => 'An overview of Sound Creations — our story, capabilities, brands and the projects we deliver across Africa and the Middle East.',
		'tagline'      => 'Engineered for sound. Built for Africa.',
		'contact_eyebrow'       => 'Contact Us',
		'contact_title'         => 'Let’s talk about your project.',
		'contact_lead'          => 'Whether you’re planning a new installation, upgrading your systems or need expert advice, our team is ready to help.',
		'contact_offices_title' => 'Local presence. Regional impact.',
		'contact_cta_title'     => 'Have a project in mind?',
		'contact_cta_text'      => 'Tell us about your space and application. Our technical team will help you design the right solution.',
		'sol_hero_title'      => 'Engineered solutions. Exceptional experiences.',
		'sol_hero_lead'       => 'We design, integrate and support professional audio, visual, lighting and acoustic solutions for every space, application and performance.',
		'sol_solutions_title' => 'Complete technology solutions for every environment.',
		'sol_process_title'   => 'A proven process. A better result.',
		'sol_sectors_title'   => 'Engineered for how corporates actually operate.',
		'sol_sectors'         => "Corporate & Boardrooms\nHouses of Worship\nEducation & Campuses\nHospitality & Retail\nLive Events & Touring\nGovernment & Public",
		'sol_support_title'   => 'We don’t disappear after installation.',
		'sol_support_text'    => 'Preventive maintenance, genuine spare parts, operator training and rapid response — a single support relationship that keeps your systems performing for years.',
		'sol_support_points'  => "Manufacturer-certified technical team\nGenuine parts & warranty management\nOperator & technical training\nRegional presence for fast on-site help",
		'sol_support_cta'     => 'Talk to our team',
		'sol_about_title'     => 'Why organisations choose Sound Creations.',
		'sol_about_items'     => "20+ Years Experience | Two decades of delivering complex solutions across the region.\nExpert Engineering Team | Highly skilled engineers, technicians and project managers.\nQuality You Can Trust | We work with the world’s leading brands and technologies.\nRegional Presence | On-the-ground presence in Kenya, Rwanda, DRC Congo and the UAE.\nEnd-to-End Solutions | From consultation to support, we’ve got you covered.\nClient-Centric Approach | Your success is our priority. We build long-term partnerships.",
		'sol_cta_title'       => 'Have a project in mind?',
		'sol_cta_text'        => 'Let’s design and deliver the right solution for your space and application.',
		'phone'        => '+254 715 754 758',
		'phone_link'   => '+254715754758',
		'email'        => 'info@soundcreationsltd.com',
		'address'      => 'Mpaka Plaza, Mpaka Road, Westlands, Nairobi, Kenya',
		'hours_week'   => 'Mon-Fri: 9:00 AM - 5:30 PM',
		'hours_sat'    => 'Sat: 9:00 AM - 1:30 PM',
		'hours_sun'    => 'Sun: Closed',
		// --- Homepage proof stats -------------------------------------------
		// These four slots are now rendered by front-page.php (previously the
		// numbers were hardcoded there and these fields did nothing). Clearing a
		// slot in Settings removes that stat from the homepage.
		'home_stat1_num'         => '22+',
		'home_stat1_label'       => 'Years Experience',
		'home_stat2_num'         => '4',
		'home_stat2_label'       => 'Regional Locations',
		'home_stat2_note'        => 'Kenya | Rwanda | DRC Congo | UAE',
		'home_stat3_num'         => '850+',
		'home_stat3_label'       => 'Projects Completed',
		// --- Homepage section headings --------------------------------------
		'home_whatwedo_eyebrow'  => 'What We Do',
		'home_solutions_eyebrow' => 'Our Solutions',
		// --- About ------------------------------------------------------------
		'about_hero_eyebrow'     => 'Who We Are',
		'regions'      => 'Kenya · Rwanda · DRC Congo · UAE',
		'whatsapp'     => '254715754758',
		'facebook'     => 'https://web.facebook.com/soundcreationsKE',
		'x'            => 'https://x.com/SCL_kenya',
		'linkedin'     => 'https://www.linkedin.com/company/soundcreationsltd/',
		'youtube'      => '',
		'home_hero_cta1_label' => 'Request a Consultation',
		'home_hero_cta1_url'   => '/request-a-consultation/',
		'home_hero_cta2_label' => 'Explore Our Solutions',
		'home_hero_cta2_url'   => '/solutions/',
		// Homepage copy — surfaced so the Central Settings page shows these as grey placeholders
		// (values match what the homepage already renders, so the live site is unchanged).
		'home_hero_title'      => '',
		'home_projects_title'  => 'Real spaces. Real results.',
		'home_cta_title'       => 'Have a project in mind?',
		'home_cta_text'        => 'Tell us about your space and application. Our technical team will help you specify the right system.',
		'home_whatwedo_eyebrow'=> 'What we do',
		'home_solutions_eyebrow'=> 'Solutions',
		'home_partners_label'  => 'Global Technology Partners',
		'home_projects_eyebrow'=> 'Featured Projects',
		'home_steps'           => "Consult | We listen, assess and recommend the right solution for your needs.\nDesign | Custom system designs tailored to your space, goals and budget.\nDistribute | Quality audio solutions from trusted global brands.\nIntegrate | Professional installation and system integration done right.\nSupport | Ongoing support and maintenance to keep you performing.",
		'projects_eyebrow'     => 'Our Projects',
		'projects_title'       => 'Real solutions. Real impact.',
		'projects_lead'        => 'Explore a selection of our professional audio, acoustics and integration projects across Africa and the Middle East.',
		// Intentionally EMPTY. This used to hold hardcoded stats that
		// contradicted the homepage (300+ projects vs 850+, 20+ years vs 22+).
		// archive-sc_project.php now derives its tiles from the home_stat*
		// values when this is blank, so the two pages agree by default. Fill
		// this in only to deliberately override the projects page.
		'proj_stats'           => '',
		'projects_cta_title'   => 'Have a project in mind?',
		'projects_cta_text'    => 'Our team of experts is ready to help you design and deliver the right solution.',
		'fane_info'            => '<h3>Why FANE</h3><p>FANE has engineered professional loudspeaker components in the UK since 1958, trusted by manufacturers and sound professionals worldwide. Sound Creations is the authorised FANE partner for the region.</p><p><strong>What sets FANE apart</strong></p><ul><li>Precision-engineered drivers built for demanding professional use</li><li>Consistent performance, reliability and long service life</li><li>A complete range for touring, install, hospitality and custom builds</li></ul><p>Talk to our team about specifying FANE components for your project, or about stocking and reselling FANE as a distribution partner.</p>',
		'hero_video'   => 'https://soundcreationsltd.com/wp-content/uploads/2026/08/dbtechnologies_stories_homepage-1280.mp4',
		'footer_about'       => 'Sound Creations Ltd delivers professional Audio, Visual, Lighting and Acoustic solutions across Africa, backed by expert consultation, quality distribution, acoustic solutions and professional installation.',
		'footer_explore'     => "Home | /\nSolutions | /solutions/\nBrands & Products | /brands/\nProjects | /projects/\nAbout | /about/\nContact | /contact/",
		'footer_solutions'   => "Professional Audio | /solutions/professional-audio/\nAcoustics | /solutions/acoustics/\nConferencing | /solutions/conferencing/\nSystem Integration | /solutions/system-integration/\nFANE Loudspeakers | /fane/",
		'footer_address'     => "Mpaka Plaza, Mpaka Road\nWestlands Nairobi",
		'footer_hours_label' => 'Open Hours',
		'footer_hours'       => "Mon – Fri: 9 am – 5:30 pm\nSat: 9 am – 1:30 pm\nSunday: CLOSED",

		// --- Promoted from template inline fallbacks (2026-09-15). These strings were
		// already what the live pages render via sc_setting( 'key', 'fallback' ), but
		// because they lived in the templates rather than here, the Central Settings
		// screen showed no placeholder and no value -- the fields looked empty even
		// though the pages were not. Registering them here makes them visible and
		// editable. No copy is invented; every value is lifted verbatim.

		'about_exp_eyebrow' => 'What We Do', // page-about.php
		// Our Work Process, the four-step row that replaced the "What We Do" grid on
		// the About page. Copy is lifted verbatim from the original site's homepage
		// (soundcreationsltd.com); the sole edit is a stray space before the full
		// stop in the Distribution line ("warranties ." -> "warranties.").
		//
		// These live on NEW keys rather than reusing about_exp_eyebrow/about_exp_items
		// because about_exp_eyebrow already has a default here and was written into
		// the saved option by the one-shot sc_core_prefill_settings_from_defaults()
		// pass. sc_setting() prefers a stored value over any default, so re-pointing
		// that key would have been silently overridden by the stored "What We Do".
		// Registering the defaults here (rather than leaving only the template's
		// inline fallbacks) is what makes the two fields show real placeholder copy
		// on the Central Settings screen instead of rendering as empty boxes.
		'about_process_eyebrow' => 'Our Work Process', // page-about.php
		// Third field is the link target. This value -- NOT the inline fallback in
		// page-about.php -- is what actually renders, because sc_setting() checks
		// sc_default_settings() before the $default argument. Paths are stored
		// relative and resolved through home_url() in the template, which keeps
		// them correct wherever WordPress is installed. The site now runs at the
		// domain root; it was previously served from the /newwebsite/ subdirectory.
		'about_process_items' => "Consultation & Design | We listen, we visualize with our new client, we propose, we reach agreements & we represent the solution. | /service/consultancy/\nDistribution | From the most affordable to the substantial investments, we keep the quality 100% and the warranties. | /distribution-dealership/\nIntegration | Our promise is professional installations, system trainings, seamless handovers and guaranteed. | /service/integration/\nSupport & Training | Comprehensive after-sales support, including a 1-year warranty service after installation. | /service/after-sale-services/", // page-about.php
		'about_hero_title' => 'If it sounds good, it’s Sound Creations', // page-about.php
		'about_journey_p1' => 'Founded in 2004, Sound Creations Ltd has grown into a leading provider of professional audio, visual, lighting and acoustic solutions across East Africa and beyond. What began as a specialist audio company is today a full-service integrator — designing, supplying, installing and supporting complete systems for the region’s most demanding spaces.', // page-about.php
		'about_partners_eyebrow' => 'Our Brands', // page-about.php
		'about_partners_title' => 'World-class brands, supported locally', // page-about.php
		'fane_cta_text' => 'We’re building the FANE dealer network across Kenya, Uganda, Tanzania, Rwanda, Burundi, Ethiopia, South Sudan and the Democratic Republic of Congo. Sound Creations focuses on large, project-based installations, so we partner with dealers who can stock and sell FANE components at the local level, supported by product knowledge, technical support, marketing resources and local availability through Sound Creations.', // page-fane.php
		'fane_cta_title' => 'Become a FANE Dealer.', // page-fane.php
		'fane_diff_body' => 'Every FANE component is designed and engineered to work in perfect harmony - delivering the performance, reliability and consistency professionals depend on.', // page-fane.php
		'fane_diff_title' => 'Built from the inside out.', // page-fane.php
		'fane_eyebrow' => 'Engineered in the UK. Trusted worldwide.', // page-fane.php
		'fane_heritage_title' => '65+ years of loudspeaker engineering.', // page-fane.php
		'fane_lead' => 'Precision-engineered loudspeaker components built for demanding professional applications, trusted by sound professionals around the world.', // page-fane.php
		'fane_products_title' => 'The FANE component range.', // page-fane.php
		'fane_social_text' => 'See FANE loudspeakers, live demos and installations on the channels we use to bring the brand to East and Central Africa.', // page-fane.php
		'fane_social_title' => 'Follow FANE Africa\'s account', // page-fane.php
		'fane_title' => 'Engineering sound since 1958.', // page-fane.php
		'resources_cta_text' => 'Our technical team can point you to the right video, manual or datasheet for your system.', // archive-sc_resource.php
		'resources_cta_title' => 'Looking for something specific?', // archive-sc_resource.php
		'resources_eyebrow' => 'Videos', // archive-sc_resource.php
		'resources_grid_title' => 'Manuals, datasheets & guides.', // archive-sc_resource.php
		'resources_lead' => 'Watch demos, installations and product highlights, plus manuals and datasheets for the systems and brands we supply and support.', // archive-sc_resource.php
		'resources_title' => 'Videos & resources.', // archive-sc_resource.php
		'resources_videos_title' => 'Videos', // archive-sc_resource.php

	);
}

/**
 * Read one setting, preferring the saved option, then the brief default.
 */
function sc_setting( $key, $default = '' ) {
	$opts = get_option( 'soundcreations_settings', array() );
	if ( is_array( $opts ) && isset( $opts[ $key ] ) && '' !== $opts[ $key ] ) {
		return $opts[ $key ];
	}
	$defaults = sc_default_settings();
	if ( isset( $defaults[ $key ] ) && '' !== $defaults[ $key ] ) {
		return $defaults[ $key ];
	}
	return $default;
}

/**
 * Output a rich-text setting inline-safely: run stored HTML through wp_kses_post
 * and unwrap a single enclosing paragraph so inline formatting (bold, italics,
 * links) renders correctly inside an existing <p> container.
 */
function sc_rich_e( $html ) {
	$html = wp_kses_post( (string) $html );
	$trimmed = trim( $html );
	if ( '' === $trimmed ) {
		return '';
	}
	$lower = strtolower( $trimmed );
	if ( strpos( $lower, '<p' ) === 0 && substr_count( $lower, '<p' ) === 1 && substr( $lower, -4 ) === '</p>' ) {
		$open_end = strpos( $trimmed, '>' );
		if ( $open_end > 0 ) {
			return trim( substr( $trimmed, $open_end + 1, strlen( $trimmed ) - $open_end - 5 ) );
		}
	}
	return $html;
}

/**
 * Output a rich-text setting as a full block (paragraphs, lists, headings).
 * Use inside a block container such as a .sc-prose <div>.
 */
function sc_rich_block( $key, $default = '' ) {
	return wp_kses_post( (string) sc_setting( $key, $default ) );
}

/**
 * Build a wa.me link from the central WhatsApp number. Empty when unset.
 */
/**
 * Split a settings textarea into rows. Each line: 'A | B | C'.
 */
function sc_split_lines( $raw, $parts = 2 ) {
	$rows  = array();
	$lines = preg_split( '/\r\n|\r|\n/', (string) $raw );
	foreach ( $lines as $line ) {
		$line = trim( $line );
		if ( '' === $line ) {
			continue;
		}
		$cols = array_map( 'trim', explode( '|', $line ) );
		while ( count( $cols ) < $parts ) {
			$cols[] = '';
		}
		$rows[] = $cols;
	}
	return $rows;
}

function sc_whatsapp_url() {
	$num = preg_replace( '/[^0-9]/', '', sc_setting( 'whatsapp' ) );
	return $num ? 'https://wa.me/' . $num : '';
}

/**
 * Return label => URL for configured social profiles.
 */
function sc_social_links() {
	$out = array();
	$map = array( 'facebook' => 'Facebook', 'x' => 'X', 'linkedin' => 'LinkedIn', 'youtube' => 'YouTube' );
	foreach ( $map as $key => $label ) {
		$url = sc_setting( $key );
		if ( $url ) {
			$out[ $label ] = esc_url( $url );
		}
	}
	return $out;
}

/**
 * The dark / light colour-theme toggle button.
 */
function sc_theme_toggle_button() {
	echo '<button class="sc-theme-toggle" type="button" data-sc-theme-toggle aria-label="' . esc_attr__( 'Switch colour theme', 'soundcreations' ) . '">'
		. '<span class="sc-theme-toggle__sun" aria-hidden="true">&#9728;</span>'
		. '<span class="sc-theme-toggle__moon" aria-hidden="true">&#9789;</span>'
		. '</button>';
}

/* ------------------------------------------------------------------ *
 * Catalog template helpers (Products / Brands / Projects) - v0.2.0
 * ------------------------------------------------------------------ */

/** Read a Sound Creations Core custom field for a post. */
function sc_field( $key, $id = 0 ) {
	$id = $id ? $id : get_the_ID();
	return get_post_meta( $id, '_sc_' . $key, true );
}

/** Render a "Label: Value" block into a spec table. */
function sc_render_specs( $raw ) {
	$raw = trim( (string) $raw );
	if ( '' === $raw ) {
		return '';
	}
	$rows = '';
	foreach ( preg_split( '/\r\n|\r|\n/', $raw ) as $line ) {
		$line = trim( $line );
		if ( '' === $line ) {
			continue;
		}
		$parts = explode( ':', $line, 2 );
		$label = trim( $parts[0] );
		$val   = isset( $parts[1] ) ? trim( $parts[1] ) : '';
		$rows .= '<tr><th scope="row">' . esc_html( $label ) . '</th><td>' . esc_html( $val ) . '</td></tr>';
	}
	return $rows ? '<table class="sc-specs"><tbody>' . $rows . '</tbody></table>' : '';
}

/** Render newline-separated lines into a tick list. */
function sc_render_ticklist( $raw ) {
	$raw = trim( (string) $raw );
	if ( '' === $raw ) {
		return '';
	}
	$items = '';
	foreach ( preg_split( '/\r\n|\r|\n/', $raw ) as $line ) {
		$line = trim( $line );
		if ( '' === $line ) {
			continue;
		}
		$items .= '<li>' . esc_html( $line ) . '</li>';
	}
	return $items ? '<ul class="sc-ticklist">' . $items . '</ul>' : '';
}

/** Breadcrumb from an array of [label, url]; final crumb is current. */
function sc_breadcrumb( $trail ) {
	$out  = '<nav class="sc-breadcrumb" aria-label="Breadcrumb">';
	$last = count( $trail ) - 1;
	foreach ( $trail as $i => $crumb ) {
		list( $label, $url ) = $crumb;
		if ( $i > 0 ) {
			$out .= '<span class="sep">/</span>';
		}
		if ( $url && $i !== $last ) {
			$out .= '<a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a>';
		} else {
			$out .= '<span aria-current="page">' . esc_html( $label ) . '</span>';
		}
	}
	return $out . '</nav>';
}

/** Filter chips for a taxonomy, highlighting the active term. */
function sc_term_chips( $taxonomy, $base_url ) {
	if ( ! taxonomy_exists( $taxonomy ) ) {
		return '';
	}
	$terms = get_terms( array( 'taxonomy' => $taxonomy, 'hide_empty' => true ) );
	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		return '';
	}
	$current = is_tax( $taxonomy ) ? (int) get_queried_object_id() : 0;
	$out     = '<div class="sc-filters">';
	$out    .= '<a class="sc-chip' . ( $current ? '' : ' is-active' ) . '" href="' . esc_url( $base_url ) . '">All</a>';
	foreach ( $terms as $t ) {
		$active = ( $current === (int) $t->term_id ) ? ' is-active' : '';
		$out   .= '<a class="sc-chip' . $active . '" href="' . esc_url( get_term_link( $t ) ) . '">' . esc_html( $t->name ) . '</a>';
	}
	return $out . '</div>';
}

/** Term chips (tags) for a post. */
function sc_term_tags( $post_id, $taxonomy ) {
	$terms = get_the_terms( $post_id, $taxonomy );
	if ( ! $terms || is_wp_error( $terms ) ) {
		return '';
	}
	$out = '<div class="sc-tags">';
	foreach ( $terms as $t ) {
		$out .= '<a class="sc-tag" href="' . esc_url( get_term_link( $t ) ) . '">' . esc_html( $t->name ) . '</a>';
	}
	return $out . '</div>';
}

/** Name of the first term in a taxonomy, or a fallback. */
function sc_primary_term_name( $post_id, $taxonomy, $fallback = '' ) {
	$terms = get_the_terms( $post_id, $taxonomy );
	if ( $terms && ! is_wp_error( $terms ) ) {
		$first = reset( $terms );
		return $first->name;
	}
	return $fallback;
}

/** A media card for archive/related grids. Falls back to a monogram tile. */
function sc_media_card( $post_id, $kicker = '', $meta = '', $logo = false ) {
	$link     = get_permalink( $post_id );
	$title    = get_the_title( $post_id );
	$mediacls = $logo ? 'sc-card__media sc-card__media--logo' : 'sc-card__media';
	$out      = '<a class="sc-card sc-card--media" href="' . esc_url( $link ) . '">';
	$out     .= '<div class="' . esc_attr( $mediacls ) . '">';
	if ( has_post_thumbnail( $post_id ) ) {
		$out .= get_the_post_thumbnail( $post_id, $logo ? 'medium' : 'large', array( 'loading' => 'lazy', 'alt' => esc_attr( $title ) ) );
	} else {
		$initial = strtoupper( substr( wp_strip_all_tags( $title ), 0, 2 ) );
		$out    .= '<div class="sc-card__ph">' . esc_html( $initial ) . '</div>';
	}
	$out .= '</div><div class="sc-card__body">';
	if ( '' !== trim( (string) $kicker ) ) {
		$out .= '<span class="sc-card__kicker">' . esc_html( $kicker ) . '</span>';
	}
	$out .= '<span class="sc-card__title">' . esc_html( $title ) . '</span>';
	if ( '' !== trim( (string) $meta ) ) {
		$out .= '<span class="sc-card__meta">' . esc_html( $meta ) . '</span>';
	}
	return $out . '</div></a>';
}

if ( function_exists( 'sc_utility_social' ) === false ) {
	function sc_utility_social() {
		// Same networks as the footer (sc_all_social) so the top bar matches the bottom.
		$items = array(
			'facebook' => array( 'Facebook', '<svg viewBox="0 0 24 24" width="13" height="13" fill="currentColor" aria-hidden="true"><path d="M14 9h3V6h-3c-1.7 0-3 1.3-3 3v2H8v3h3v7h3v-7h3l1-3h-4V9c0-.6.4-1 1-1z"/></svg>' ),
			'x'        => array( 'X', '<svg viewBox="0 0 24 24" width="13" height="13" fill="currentColor" aria-hidden="true"><path d="M18.9 2H22l-7.6 8.7L23 22h-6.9l-5-6.6L4.3 22H1.2l8.2-9.4L1 2h7.1l4.5 6 6.3-6zm-2.4 18h1.9L7.6 4H5.6z"/></svg>' ),
			'instagram' => array( 'Instagram', '<svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1.2" fill="currentColor" stroke="none"/></svg>' ),
			'linkedin' => array( 'LinkedIn', '<svg viewBox="0 0 24 24" width="13" height="13" fill="currentColor" aria-hidden="true"><path d="M6.94 5a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM3 8.5h3.9V21H3zM9.5 8.5h3.7v1.7h.1c.5-.9 1.8-1.9 3.6-1.9 3.9 0 4.6 2.5 4.6 5.8V21h-3.9v-5.4c0-1.3 0-3-1.8-3s-2.1 1.4-2.1 2.9V21H9.5z"/></svg>' ),
			'youtube'  => array( 'YouTube', '<svg viewBox="0 0 24 24" width="13" height="13" fill="currentColor" aria-hidden="true"><path d="M23 12s0-3.2-.4-4.7a2.5 2.5 0 0 0-1.8-1.8C19.2 5 12 5 12 5s-7.2 0-8.8.5A2.5 2.5 0 0 0 1.4 7.3C1 8.8 1 12 1 12s0 3.2.4 4.7a2.5 2.5 0 0 0 1.8 1.8C4.8 19 12 19 12 19s7.2 0 8.8-.5a2.5 2.5 0 0 0 1.8-1.8C23 15.2 23 12 23 12zM9.8 15.3V8.7l5.7 3.3z"/></svg>' ),
		);
		foreach ( $items as $key => $it ) {
			$url = sc_setting( $key );
			if ( $url ) {
				echo '<a class="sc-utility__soc" href="' . esc_url( $url ) . '" target="_blank" rel="noopener" aria-label="' . esc_attr( $it[0] ) . '">' . $it[1] . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static inline SVG.
			}
		}
	}
}


if ( function_exists( 'sc_all_social' ) === false ) {
	/**
	 * Render every configured social profile as an icon link (footer).
	 */
	function sc_all_social() {
		$items = array(
			'facebook'  => array( 'Facebook',  '<svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M14 9h3V6h-3c-1.7 0-3 1.3-3 3v2H8v3h3v7h3v-7h3l1-3h-4V9c0-.6.4-1 1-1z"/></svg>' ),
			'x'         => array( 'X',         '<svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M18.9 2H22l-7.6 8.7L23 22h-6.9l-5-6.6L4.3 22H1.2l8.2-9.4L1 2h7.1l4.5 6 6.3-6zm-2.4 18h1.9L7.6 4H5.6z"/></svg>' ),
			'instagram' => array( 'Instagram', '<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>' ),
			'linkedin'  => array( 'LinkedIn',  '<svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M6.94 5a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM3 8.5h3.9V21H3zM9.5 8.5h3.7v1.7h.1c.5-.9 1.8-1.9 3.6-1.9 3.9 0 4.6 2.5 4.6 5.8V21h-3.9v-5.4c0-1.3 0-3-1.8-3s-2.1 1.4-2.1 2.9V21H9.5z"/></svg>' ),
			'youtube'   => array( 'YouTube',   '<svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M23 12s0-3.2-.4-4.7a2.5 2.5 0 0 0-1.8-1.8C19.2 5 12 5 12 5s-7.2 0-8.8.5A2.5 2.5 0 0 0 1.4 7.3C1 8.8 1 12 1 12s0 3.2.4 4.7a2.5 2.5 0 0 0 1.8 1.8C4.8 19 12 19 12 19s7.2 0 8.8-.5a2.5 2.5 0 0 0 1.8-1.8C23 15.2 23 12 23 12zM9.8 15.3V8.7l5.7 3.3z"/></svg>' ),
			'whatsapp'  => array( 'WhatsApp',  '<svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.5 15.3L2 22l4.8-1.3A10 10 0 1 0 12 2zm0 18a8 8 0 0 1-4.1-1.1l-.3-.2-2.8.7.8-2.7-.2-.3A8 8 0 1 1 12 20zm4.4-6c-.2-.1-1.4-.7-1.6-.8s-.4-.1-.5.1-.6.8-.8 1-.3.2-.5.1a6.5 6.5 0 0 1-3.2-2.8c-.2-.4.2-.4.6-1.2.1-.2 0-.3 0-.5s-.5-1.3-.7-1.8-.4-.4-.5-.4h-.5a1 1 0 0 0-.7.3 3 3 0 0 0-.9 2.2c0 1.3 1 2.6 1.1 2.8s1.9 3 4.7 4.2c1.7.7 2.4.8 3.2.7.5-.1 1.4-.6 1.6-1.1s.2-1 .1-1.1z"/></svg>' ),
		);
		foreach ( $items as $key => $it ) {
			$url = ( 'whatsapp' === $key ) ? sc_whatsapp_url() : sc_setting( $key );
			if ( $url ) {
				echo '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener" aria-label="' . esc_attr( $it[0] ) . '">' . $it[1] . '</a>';
			}
		}
	}
}


if ( function_exists( 'sc_youtube_id' ) === false ) {
	/**
	 * Extract an 11-character YouTube video id from a URL or bare id.
	 * Accepts watch, youtu.be, embed and shorts links. Returns '' when none.
	 *
	 * @param string $url YouTube URL or id.
	 * @return string Video id or empty string.
	 */
	function sc_youtube_id( $url ) {
		$url = trim( (string) $url );
		if ( $url === '' ) {
			return '';
		}
		if ( preg_match( '/^[A-Za-z0-9_-]{11}$/', $url ) === 1 ) {
			return $url;
		}
		$patterns = array(
			'/youtu\.be\/([A-Za-z0-9_-]{11})/',
			'/[?&]v=([A-Za-z0-9_-]{11})/',
			'/youtube\.com\/embed\/([A-Za-z0-9_-]{11})/',
			'/youtube\.com\/shorts\/([A-Za-z0-9_-]{11})/',
		);
		foreach ( $patterns as $sc_pat ) {
			if ( preg_match( $sc_pat, $url, $sc_m ) === 1 ) {
				return $sc_m[1];
			}
		}
		return '';
	}
}


/**
 * Resolve a project card image URL, WordPress-native first.
 * Order: Featured Image -> first gallery photo -> legacy seeded theme-file key -> default.
 */
function sc_project_card_image( $post_id ) {
	if ( has_post_thumbnail( $post_id ) ) {
		$sc_u = get_the_post_thumbnail_url( $post_id, 'large' );
		if ( is_string( $sc_u ) && strlen( $sc_u ) > 0 ) {
			return $sc_u;
		}
	}
	$sc_gal = (string) get_post_meta( $post_id, '_sc_gallery', true );
	if ( strlen( $sc_gal ) > 0 ) {
		$sc_ids = array_filter( array_map( 'absint', explode( ',', $sc_gal ) ) );
		if ( count( $sc_ids ) > 0 ) {
			$sc_u = wp_get_attachment_image_url( reset( $sc_ids ), 'large' );
			if ( is_string( $sc_u ) && strlen( $sc_u ) > 0 ) {
				return $sc_u;
			}
		}
	}
	$sc_key = (string) get_post_meta( $post_id, '_sc_image', true );
	if ( strlen( $sc_key ) > 0 ) {
		$sc_rel = 'assets/img/projects/' . $sc_key . '.jpg';
		if ( file_exists( get_theme_file_path( $sc_rel ) ) ) {
			return get_theme_file_uri( $sc_rel );
		}
	}
	return SC_THEME_URI . '/assets/img/projects/boardroom.jpg';
}

/**
 * Bundled brand logo URL for a logo key, preferring a crisp SVG over PNG.
 * Looks in the child theme first, then the parent.
 *
 * @param string $key Logo key, e.g. "yamaha".
 * @return string URL or ''.
 */
function sc_brand_logo_url( $key ) {
	$key = sanitize_key( (string) $key );
	if ( '' === $key ) {
		return '';
	}
	foreach ( array( 'svg', 'webp', 'png' ) as $ext ) {
		$rel = 'assets/img/brands/logos/' . $key . '.' . $ext;
		if ( file_exists( get_theme_file_path( $rel ) ) ) {
			return get_theme_file_uri( $rel );
		}
	}
	return '';
}


/**
 * Intrinsic display size for a bundled brand logo, scaled to the strip height.
 * Lets the browser reserve the right width before the image arrives, so the
 * partner strip does not collapse and jump while logos load.
 *
 * @param string $key    Logo key (file name without extension).
 * @param int    $height Display height in px.
 * @return array{0:int,1:int} Width and height, or [0,0] if unknown.
 */
function sc_brand_logo_dims( $key, $height = 34 ) {
	$key = sanitize_key( (string) $key );
	foreach ( array( 'svg', 'webp', 'png' ) as $ext ) {
		$path = get_theme_file_path( 'assets/img/brands/logos/' . $key . '.' . $ext );
		if ( ! file_exists( $path ) ) {
			continue;
		}
		$w = 0;
		$h = 0;
		if ( 'svg' === $ext ) {
			$svg = (string) file_get_contents( $path, false, null, 0, 4096 );
			if ( preg_match( '/viewBox\s*=\s*["\x27]\s*[-\d.]+[\s,]+[-\d.]+[\s,]+([\d.]+)[\s,]+([\d.]+)/i', $svg, $m ) ) {
				$w = (float) $m[1];
				$h = (float) $m[2];
			}
		} else {
			$info = @getimagesize( $path );
			if ( $info ) {
				$w = (float) $info[0];
				$h = (float) $info[1];
			}
		}
		if ( $w > 0 && $h > 0 ) {
			$dw = (int) round( $w * $height / $h );
			return array( min( $dw, 150 ), (int) $height );
		}
		break;
	}
	return array( 0, 0 );
}
