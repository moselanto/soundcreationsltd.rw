<?php
/**
 * Brand authority sections (2.3.29, Master Audit Gap 4 / R4).
 *
 * Adds a structured, answer-first section under each brand page's own text:
 * our relationship with the brand in Rwanda, what the manufacturer makes,
 * related Rwanda projects (only when the project page exists), how to get a
 * quote, and three visible questions and answers (also added to the single
 * JSON-LD graph as FAQPage through sc_seo_graph).
 *
 * Owner decision 31 Oct 2026: every brand listed is an AUTHORISED dealer /
 * partner relationship, not exclusive. Yamaha: authorised distributor in
 * Rwanda (already confirmed). Never write "exclusive" here.
 *
 * Text is presentation only (no database writes); edit via the
 * scrw_brand_authority_data filter. /brands/yamaha/ and /brands/fane/ 301 to
 * their full pages, so they are not handled here.
 *
 * @package soundcreations-rwanda
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Per-brand facts. 'makes' = what the manufacturer is known for (public facts only). */
function scrw_brand_authority_data() {
	$data = array(
		'db-technologies'   => array(
			'makes'    => 'dB Technologies is an Italian manufacturer of professional active loudspeakers, line-array systems, subwoofers and stage monitors for live sound, events and permanent installations.',
			'uses'     => 'event venues, churches, conference halls and touring sound',
			'projects' => array( 'christian-life-assembly-church', 'romantic-garden' ),
		),
		'shure'             => array(
			'makes'    => 'Shure is an American audio company known for wired and wireless microphones, in-ear monitoring and conference systems such as Microflex.',
			'uses'     => 'churches, conference rooms, government offices, studios and live performance',
			'projects' => array( 'minecofin-conference-system' ),
		),
		'bose-professional' => array(
			'makes'    => 'Bose Professional makes loudspeakers, amplifiers and audio processing for commercial installations, including ceiling, surface-mount and column loudspeakers.',
			'uses'     => 'hotels, restaurants, offices, retail and conference spaces',
			'projects' => array(),
		),
		'allen-heath'       => array(
			'makes'    => 'Allen & Heath is a British manufacturer of digital and analogue mixing consoles, including the SQ series, for live sound, worship and installation.',
			'uses'     => 'churches, live events and venues that need reliable mixing',
			'projects' => array( 'christian-life-assembly-church' ),
		),
		'midas'             => array(
			'makes'    => 'Midas is a British-founded brand of digital and analogue live-sound mixing consoles.',
			'uses'     => 'live events, churches and venues',
			'projects' => array(),
		),
		'behringer'         => array(
			'makes'    => 'Behringer makes affordable audio equipment, including digital and analogue mixers, microphones, studio monitors and audio interfaces.',
			'uses'     => 'small venues, churches, schools, home and project studios',
			'projects' => array(),
		),
		'rockfon'           => array(
			'makes'    => 'Rockfon, part of the ROCKWOOL Group, makes stone wool acoustic ceiling and wall solutions that control echo and noise in rooms.',
			'uses'     => 'offices, schools, auditoriums, hotels and public buildings',
			'projects' => array(),
		),
		'barrisol'          => array(
			'makes'    => 'Barrisol is a French manufacturer of stretch ceilings and walls, including acoustic stretch-ceiling systems.',
			'uses'     => 'auditoriums, hotels, offices and architectural interiors',
			'projects' => array(),
		),
		'sommer-cable'      => array(
			'makes'    => 'Sommer Cable is a German manufacturer of audio, video and data cables and connectors for professional installations.',
			'uses'     => 'installed sound systems, studios, stages and broadcast',
			'projects' => array(),
		),
		'aid'               => array(
			'makes'    => '',
			'uses'     => 'professional audio and acoustic projects',
			'projects' => array(),
		),
		'asona'             => array(
			'makes'    => '',
			'uses'     => 'acoustic treatment and room acoustics projects',
			'projects' => array(),
		),
	);
	return (array) apply_filters( 'scrw_brand_authority_data', $data );
}

/** Data for the brand page being viewed, or null. */
function scrw_brand_authority_current() {
	if ( ! is_singular( 'sc_brand' ) ) {
		return null;
	}
	$pid  = get_queried_object_id();
	$slug = get_post_field( 'post_name', $pid );
	$all  = scrw_brand_authority_data();
	if ( empty( $all[ $slug ] ) ) {
		return null;
	}
	$d          = $all[ $slug ];
	$d['slug']  = $slug;
	$d['name']  = html_entity_decode( get_the_title( $pid ), ENT_QUOTES | ENT_HTML5, 'UTF-8' );
	return $d;
}

/** The three questions and answers for a brand. */
function scrw_brand_authority_faq( $d ) {
	$n = $d['name'];
	return array(
		array(
			'q' => sprintf( 'Where can I buy %s equipment in Rwanda?', $n ),
			'a' => sprintf( 'Sound Creations Ltd Rwanda is an authorised dealer and partner for %s in Rwanda. Our showroom is on KN1 Rd, Muhima, Kigali. Call +250 783 141 050, email stefic@soundcreationsltd.com or request a quotation on this website.', $n ),
		),
		array(
			'q' => sprintf( 'Can Sound Creations design and install a system with %s products?', $n ),
			'a' => sprintf( 'Yes. Our Kigali team can recommend the right %s products for your room, supply genuine equipment, install and commission it, train your operators and provide after-sales support across Rwanda.', $n ),
		),
		array(
			'q' => sprintf( 'How much do %s products cost in Rwanda?', $n ),
			'a' => 'Prices change with each shipment, so we do not publish them. Send us the model or describe your project and we will reply with a current quotation in Rwandan francs.',
		),
	);
}

add_filter(
	'the_content',
	function ( $content ) {
		if ( ! in_the_loop() || ! is_main_query() ) {
			return $content;
		}
		$d = scrw_brand_authority_current();
		if ( ! $d ) {
			return $content;
		}
		$n   = $d['name'];
		$out = '<section class="scrw-brand-auth" aria-label="' . esc_attr( sprintf( '%s in Rwanda', $n ) ) . '">';
		$out .= '<h2>' . esc_html( sprintf( '%s in Rwanda', $n ) ) . '</h2>';
		$out .= '<p><strong>' . esc_html( sprintf( 'Sound Creations Ltd Rwanda is an authorised dealer and partner for %s in Rwanda.', $n ) ) . '</strong> ' . esc_html( sprintf( 'We supply genuine %s products from our Kigali showroom and design, install and support systems for %s.', $n, $d['uses'] ) ) . '</p>';
		if ( '' !== $d['makes'] ) {
			$out .= '<h3>' . esc_html( sprintf( 'About %s', $n ) ) . '</h3><p>' . esc_html( $d['makes'] ) . '</p>';
		}
		$links = array();
		foreach ( (array) $d['projects'] as $ps ) {
			$p = get_page_by_path( $ps, OBJECT, 'sc_project' );
			if ( $p && 'publish' === $p->post_status ) {
				$links[] = '<li><a href="' . esc_url( get_permalink( $p ) ) . '">' . esc_html( get_the_title( $p ) ) . '</a></li>';
			}
		}
		if ( $links ) {
			$out .= '<h3>' . esc_html( sprintf( '%s in our Rwanda projects', $n ) ) . '</h3><ul>' . implode( '', $links ) . '</ul>';
		}
		$out .= '<h3>' . esc_html( sprintf( 'Get a %s quotation in Rwanda', $n ) ) . '</h3>';
		$out .= '<p>' . esc_html( 'Tell us the model you need, or describe your venue and what it must do. We reply with a current price, availability and, where useful, a site visit.' ) . '</p>';
		$out .= '<p><a class="sc-btn" href="' . esc_url( home_url( '/request-a-consultation/' ) ) . '">' . esc_html( 'Request a quotation' ) . '</a> <a class="sc-btn sc-btn--ghost" href="tel:+250783141050">+250 783 141 050</a></p>';
		$out .= '<h3>' . esc_html( 'Questions and answers' ) . '</h3><dl class="scrw-brand-auth__faq">';
		foreach ( scrw_brand_authority_faq( $d ) as $f ) {
			$out .= '<dt>' . esc_html( $f['q'] ) . '</dt><dd>' . esc_html( $f['a'] ) . '</dd>';
		}
		$out .= '</dl></section>';
		return $content . $out;
	},
	20
);

/* FAQPage for the visible questions, inside the one graph. */
add_filter(
	'sc_seo_graph',
	function ( $graph ) {
		$d = scrw_brand_authority_current();
		if ( ! $d || ! is_array( $graph ) ) {
			return $graph;
		}
		foreach ( $graph as $n ) {
			if ( is_array( $n ) && isset( $n['@type'] ) && 'FAQPage' === $n['@type'] ) {
				return $graph; // Never two FAQPage nodes.
			}
		}
		$items = array();
		foreach ( scrw_brand_authority_faq( $d ) as $f ) {
			$items[] = array(
				'@type'          => 'Question',
				'name'           => $f['q'],
				'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $f['a'] ),
			);
		}
		$url     = get_permalink( get_queried_object_id() );
		$graph[] = array(
			'@type'            => 'FAQPage',
			'@id'              => trailingslashit( $url ) . '#faq',
			'mainEntityOfPage' => array( '@id' => trailingslashit( $url ) . '#webpage' ),
			'mainEntity'       => $items,
		);
		return $graph;
	},
	45
);

/* Home service card and Distribution highlights: authorised, not exclusive (needs parent 0.10.24). */
add_filter(
	'sc_home_services',
	function ( $services ) {
		foreach ( $services as $i => $svc ) {
			if ( isset( $svc[3] ) && is_string( $svc[3] ) ) {
				$services[ $i ][3] = str_replace( 'Certified exclusive dealers for leading global brands', 'Authorised dealers and partners for leading global brands', $svc[3] );
			}
		}
		return $services;
	}
);
add_filter(
	'sc_service_highlights',
	function ( $high ) {
		if ( isset( $high['distribution'][0] ) ) {
			$high['distribution'][0] = array( 'Authorised dealer and partner', 'Authorised dealer and partner for leading global brands, including authorised Yamaha distribution in Rwanda.' );
		}
		return $high;
	}
);
