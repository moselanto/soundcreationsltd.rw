<?php
/**
 * Rwanda SEO layer, built to work with Rank Math.
 *
 * 1. Rank Math owns the <head> meta: title, description, canonical, robots,
 *    Open Graph, Twitter and the XML sitemap. The Core plugin's duplicate
 *    meta block is switched off while Rank Math is active, so Google never
 *    sees two canonicals or two descriptions.
 * 2. One schema graph only. The theme's connected @graph (Organization,
 *    LocalBusiness, WebSite, WebPage, Breadcrumb, Product, Brand, FAQ) stays;
 *    Rank Math's own JSON-LD is turned off so the two never contradict each
 *    other. This file enriches that graph for Rwanda and the brands carried.
 * 3. Keyword-targeted default titles and descriptions for every page type.
 *    Anything typed into the Rank Math box on a page always wins.
 * 4. Visible FAQs (with FAQPage schema) on Professional Audio and Installation.
 *
 * Ground rule kept from Core: never claim what the business cannot back up.
 * "Authorised distributor" is used only for Yamaha; FANE is "FANE Africa
 * partner"; other brands are "supplied in Rwanda". No prices, ratings or
 * stock claims.
 *
 * @package SoundCreationsRwanda
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SCRW_SEO_VERSION', 'rw-seo-1' );

function scrw_rank_math_active() {
	return defined( 'RANK_MATH_VERSION' ) || class_exists( 'RankMath' );
}

/* ---------- 1 + 2: Rank Math coexistence ---------- */
add_action(
	'wp',
	function () {
		if ( ! scrw_rank_math_active() || is_admin() ) {
			return;
		}
		// Core prints meta + OG + the schema graph in one function. Drop it and
		// print only the graph; Rank Math prints the meta.
		remove_action( 'wp_head', 'sc_seo_head', 1 );
		if ( function_exists( 'sc_seo_graph' ) ) {
			add_action( 'wp_head', 'sc_seo_graph', 20 );
		}
	}
);
add_filter(
	'rank_math/json_ld',
	function ( $data ) {
		return is_admin() ? $data : array();
	},
	999
);

/* ---------- 3: keyword map ---------- */

/** Trim to a search-friendly length at a word boundary. */
function scrw_seo_clip( $text, $max ) {
	$text = trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( (string) $text ) ) );
	if ( strlen( $text ) <= $max ) {
		return $text;
	}
	$cut = substr( $text, 0, $max - 1 );
	$sp  = strrpos( $cut, ' ' );
	return rtrim( false !== $sp ? substr( $cut, 0, $sp ) : $cut, ' ,.-|' );
}

/** Pages and solutions by slug: array( title, description ). */
function scrw_seo_slug_map() {
	return array(
		// Pages.
		'yamaha'                  => array( 'Yamaha Rwanda | Authorised Yamaha Distributor in Kigali', 'Sound Creations is the authorised Yamaha distributor in Rwanda. Yamaha mixers, speakers, keyboards, digital pianos and guitars in Kigali, with local warranty and support across East Africa.' ),
		'fane'                    => array( 'FANE Africa | FANE Speakers & Loudspeaker Drivers Rwanda', 'FANE Africa partner in Kigali: FANE Sovereign, Colossus and compression drivers for PA, line array and installed sound across Rwanda and East Africa. Genuine parts, expert advice.' ),
		'about'                   => array( 'About Sound Creations Rwanda | Audio Visual Company Kigali', 'Sound Creations Ltd Rwanda: Kigali audio visual company for sound systems, lighting, acoustics and studios. Part of the Sound Creations group serving East Africa since 2011.' ),
		'contact'                 => array( 'Contact Sound Creations Rwanda | Sound Systems Kigali', 'Visit our showroom on KN1 Rd, Muhima, Kigali, call +250 783 141 050 or WhatsApp us for sound systems, Yamaha equipment, lighting and acoustics in Rwanda.' ),
		'request-a-consultation'  => array( 'Book an AV & Acoustic Consultation in Kigali, Rwanda', 'Book a site visit with our Kigali engineers for sound system design, acoustic treatment, lighting and AV for churches, hotels, schools and conference rooms in Rwanda.' ),
		'request-a-quote'         => array( 'Request a Quote | Sound, Lighting & AV Equipment Rwanda', 'Get a quotation for Yamaha, FANE, dB Technologies, Shure and more, or a complete sound, lighting or acoustic installation anywhere in Rwanda.' ),
		'become-a-dealer'         => array( 'Become a Dealer | Pro Audio Distribution Rwanda & East Africa', 'Partner with Sound Creations to resell Yamaha, FANE and leading pro audio brands in Rwanda and East Africa, with trade pricing and technical backing.' ),
		// Solutions.
		'professional-audio'      => array( 'Professional Audio & PA Systems in Kigali, Rwanda', 'PA and sound systems for churches, hotels, conference halls, schools and live events in Rwanda: line arrays, speakers, mixers and microphones, designed and installed in Kigali.' ),
		'installation'            => array( 'Sound System Installation Rwanda | AV Integration Kigali', 'Turnkey sound, acoustic and AV installation in Rwanda: site survey, design, installation, calibration and training by our Kigali team. Churches, halls, hotels, offices.' ),
		'acoustics'               => array( 'Acoustic Treatment & Soundproofing in Rwanda | Kigali', 'Acoustic design, measurement and treatment in Rwanda: wall panels, diffusers, acoustic ceilings and soundproofing for churches, auditoriums, studios and offices in Kigali.' ),
		'architectural-acoustics' => array( 'Architectural Acoustics Rwanda | Acoustic Design Kigali', 'Architectural acoustic design and treatment for new and existing buildings in Rwanda. RT60 measurement, soundproofing and verified results, with architects in Kigali.' ),
		'lighting-solutions'      => array( 'Stage & Event Lighting Rwanda | Church Lighting Kigali', 'Stage, church, event and architectural lighting in Rwanda: moving heads, LED wash, DMX control, installation and programming by our Kigali team.' ),
		'lighting'                => array( 'Stage & Architectural Lighting Rwanda | Kigali', 'Stage, event and architectural lighting systems in Rwanda, designed, supplied, installed and programmed by Sound Creations in Kigali.' ),
		'dj-solutions'            => array( 'DJ Equipment Rwanda | DJ Controllers & Mixers Kigali', 'DJ controllers, mixers, monitors and complete DJ booths for clubs, lounges, hotels and mobile DJs in Kigali and across Rwanda. Genuine gear with warranty.' ),
		'studio-solutions'        => array( 'Recording Studio Equipment & Setup Rwanda | Kigali', 'Recording, podcast, radio and streaming studios in Rwanda: acoustic treatment, microphones, interfaces, monitors and consoles, installed in Kigali.' ),
		'broadcast-recording'     => array( 'Broadcast & Recording Studio Systems Rwanda', 'Broadcast audio, recording and studio systems in Rwanda, specified, integrated and commissioned by Sound Creations in Kigali.' ),
		'service-and-backup'      => array( 'Sound System Repair & Maintenance Rwanda | Kigali', 'Sound, lighting and AV maintenance, repairs, warranty support, equipment backup and operator training in Rwanda, from our Kigali service team.' ),
		'support-training'        => array( 'AV Support & Training Rwanda | Sound Creations Kigali', 'Technical support, product training and after-sales service for sound, lighting and AV systems in Rwanda.' ),
	);
}

/** Archives: array( title, description ). */
function scrw_seo_archive_map() {
	return array(
		'sc_product'  => array( 'Pro Audio Equipment Rwanda | Speakers, Mixers & Mics Kigali', 'Shop professional audio in Kigali: Yamaha mixers and keyboards, FANE drivers, dB Technologies speakers, Shure microphones and more, with warranty and support in Rwanda.' ),
		'sc_brand'    => array( 'Pro Audio Brands in Rwanda | Yamaha, FANE, dB Technologies', 'Global audio, lighting and AV brands supplied in Rwanda by Sound Creations, including the authorised Yamaha distributorship and FANE Africa partnership.' ),
		'sc_project'  => array( 'Sound & AV Installation Projects in Rwanda | Our Work', 'Sound, acoustic, lighting and AV projects across Rwanda: MINECOFIN, Intare Kivu Arena, Ntare Louisenlund School, Christian Life Assembly and more.' ),
		'sc_solution' => array( 'Audio Visual, Lighting & Acoustic Solutions Rwanda', 'Professional audio, acoustics, lighting, DJ, studio and AV integration solutions for venues in Kigali and across Rwanda.' ),
		'sc_resource' => array( 'Pro Audio & AV Videos | Sound Creations Rwanda', 'Installation videos, product demos and events from Sound Creations in Rwanda and East Africa.' ),
	);
}

/** Default title/description for the current request, or null. */
function scrw_seo_defaults() {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache ? $cache : null;
	}
	$out = false;
	if ( is_front_page() ) {
		$out = array( 'Sound Creations Rwanda | Yamaha Distributor & Pro Audio Kigali', 'Authorised Yamaha distributor and FANE Africa partner in Kigali. Sound systems, PA, lighting, acoustics, studio and AV installation across Rwanda and East Africa.' );
	} elseif ( is_post_type_archive() ) {
		$map = scrw_seo_archive_map();
		$pt  = get_query_var( 'post_type' );
		$pt  = is_array( $pt ) ? reset( $pt ) : $pt;
		if ( isset( $map[ $pt ] ) ) {
			$out = $map[ $pt ];
		}
	} elseif ( is_tax() || is_category() ) {
		$term = get_queried_object();
		if ( $term && isset( $term->name ) ) {
			$out = array( $term->name . ' in Rwanda | Sound Creations Kigali', 'Browse ' . $term->name . ' from leading brands at Sound Creations, Kigali. Genuine equipment, expert advice, warranty and support across Rwanda.' );
		}
	} elseif ( is_singular() ) {
		$post = get_queried_object();
		$slug = $post->post_name;
		$name = get_the_title( $post );
		$map  = scrw_seo_slug_map();
		if ( isset( $map[ $slug ] ) ) {
			$out = $map[ $slug ];
		} elseif ( 'sc_product' === $post->post_type ) {
			$brand = function_exists( 'sc_title_brand_of' ) ? sc_title_brand_of( $post->ID ) : '';
			$ex    = has_excerpt( $post ) ? get_the_excerpt( $post ) : '';
			$out   = array(
				$name . ' in Rwanda | Buy in Kigali',
				scrw_seo_clip( $name . ' available in Kigali from Sound Creations Rwanda' . ( $brand ? ', your ' . $brand . ' supplier' : '' ) . '. ' . $ex . ' Genuine product, warranty and local support.', 158 ),
			);
		} elseif ( 'sc_brand' === $post->post_type ) {
			if ( 'yamaha' === $slug ) {
				$out = $map['yamaha'];
			} elseif ( false !== stripos( $name, 'fane' ) ) {
				$out = $map['fane'];
			} else {
				$out = array( $name . ' Rwanda | ' . $name . ' Dealer in Kigali', $name . ' equipment supplied in Rwanda by Sound Creations, Kigali: genuine products, expert specification, installation, warranty and after-sales support across East Africa.' );
			}
		} elseif ( 'sc_project' === $post->post_type ) {
			$loc = (string) get_post_meta( $post->ID, '_sc_location', true );
			$out = array( $name . ' | Sound & AV Project' . ( $loc ? ', ' . $loc : ', Rwanda' ), scrw_seo_clip( ( has_excerpt( $post ) ? get_the_excerpt( $post ) : 'Sound, acoustic, lighting and AV project by Sound Creations Rwanda.' ) . ' See the scope, equipment and results.', 158 ) );
		} elseif ( 'sc_service' === $post->post_type ) {
			$out = array( $name . ' | Sound Creations Rwanda, Kigali', scrw_seo_clip( $name . ' from Sound Creations Rwanda: professional audio, lighting and acoustic expertise for projects across Kigali and Rwanda.', 158 ) );
		} elseif ( 'sc_solution' === $post->post_type ) {
			$out = array( $name . ' in Rwanda | Sound Creations Kigali', scrw_seo_clip( $name . ' designed, supplied and installed by Sound Creations Rwanda for venues across Kigali and Rwanda.', 158 ) );
		}
	}
	if ( $out ) {
		$out[0] = scrw_seo_clip( $out[0], 66 );
		$out[1] = scrw_seo_clip( $out[1], 160 );
	}
	$cache = $out;
	return $out ? $out : null;
}

/** True when an editor typed a value in the Rank Math box for this page. */
function scrw_rm_has_manual( $key ) {
	if ( is_singular() ) {
		return '' !== trim( (string) get_post_meta( get_queried_object_id(), 'rank_math_' . $key, true ) );
	}
	if ( is_tax() || is_category() ) {
		return '' !== trim( (string) get_term_meta( get_queried_object_id(), 'rank_math_' . $key, true ) );
	}
	return false;
}

add_filter(
	'rank_math/frontend/title',
	function ( $title ) {
		$d = scrw_seo_defaults();
		return ( $d && ! scrw_rm_has_manual( 'title' ) ) ? $d[0] : $title;
	},
	20
);
add_filter(
	'rank_math/frontend/description',
	function ( $desc ) {
		$d = scrw_seo_defaults();
		return ( $d && ! scrw_rm_has_manual( 'description' ) ) ? $d[1] : $desc;
	},
	20
);
// Without Rank Math, still apply the titles.
add_filter(
	'pre_get_document_title',
	function ( $title ) {
		if ( scrw_rank_math_active() ) {
			return $title;
		}
		$d = scrw_seo_defaults();
		return $d ? $d[0] : $title;
	},
	20
);

/* ---------- 2b: schema enrichment ---------- */

/** Published brand names, cached for 12 hours. */
function scrw_seo_brand_names() {
	$names = get_transient( 'scrw_seo_brands' );
	if ( false === $names ) {
		$names = wp_list_pluck( get_posts( array( 'post_type' => 'sc_brand', 'post_status' => 'publish', 'numberposts' => 60, 'orderby' => 'menu_order', 'order' => 'ASC' ) ), 'post_title' );
		set_transient( 'scrw_seo_brands', $names, 12 * HOUR_IN_SECONDS );
	}
	return (array) $names;
}
add_action( 'save_post_sc_brand', function () { delete_transient( 'scrw_seo_brands' ); } );

function scrw_seo_keywords() {
	return array(
		'Yamaha distributor Rwanda', 'Yamaha Rwanda', 'Yamaha Africa', 'Yamaha dealer Kigali', 'Yamaha keyboards Rwanda', 'Yamaha digital piano Rwanda', 'Yamaha mixer Rwanda',
		'FANE Africa', 'FANE speakers Rwanda', 'FANE drivers',
		'sound systems Kigali', 'PA system Rwanda', 'professional audio Rwanda', 'audio visual company Rwanda', 'AV installation Kigali',
		'church sound system Rwanda', 'conference room AV Kigali', 'stage lighting Kigali', 'acoustic treatment Rwanda', 'soundproofing Kigali',
		'studio equipment Rwanda', 'DJ equipment Kigali', 'line array Rwanda', 'speakers Kigali', 'microphones Rwanda',
	);
}

function scrw_seo_area_served() {
	$areas = array( array( '@type' => 'Country', 'name' => 'Rwanda' ) );
	foreach ( array( 'Kigali', 'Musanze', 'Rubavu', 'Huye', 'Rusizi', 'Muhanga', 'Nyagatare', 'Rwamagana' ) as $c ) {
		$areas[] = array( '@type' => 'City', 'name' => $c, 'containedInPlace' => array( '@type' => 'Country', 'name' => 'Rwanda' ) );
	}
	$areas[] = array( '@type' => 'Country', 'name' => 'Democratic Republic of the Congo' );
	$areas[] = array( '@type' => 'AdministrativeArea', 'name' => 'East Africa' );
	return $areas;
}

function scrw_seo_is_business( $node ) {
	foreach ( isset( $node['@type'] ) ? (array) $node['@type'] : array() as $t ) {
		if ( is_string( $t ) && ( 'Organization' === $t || false !== strpos( $t, 'Business' ) || 'Store' === $t || 'ProfessionalService' === $t ) ) {
			return true;
		}
	}
	return false;
}

add_filter(
	'sc_seo_graph',
	function ( $graph ) {
		if ( ! is_array( $graph ) ) {
			return $graph;
		}
		$brands = array();
		foreach ( scrw_seo_brand_names() as $b ) {
			$brands[] = array( '@type' => 'Brand', 'name' => $b );
		}
		foreach ( $graph as $i => $node ) {
			if ( ! is_array( $node ) || ! scrw_seo_is_business( $node ) ) {
				continue;
			}
			// The group parent node added by inc/seo.php is left alone.
			if ( isset( $node['url'] ) && untrailingslashit( (string) $node['url'] ) === untrailingslashit( SCRW_GROUP_URL ) ) {
				continue;
			}
			$node['areaServed'] = scrw_seo_area_served();
			$node['knowsAbout'] = scrw_seo_keywords();
			$node['keywords']   = implode( ', ', array_slice( scrw_seo_keywords(), 0, 15 ) );
			if ( empty( $node['slogan'] ) ) {
				$node['slogan'] = "If it sounds good, it's Sound Creations";
			}
			if ( $brands ) {
				$node['brand'] = $brands;
			}
			if ( empty( $node['description'] ) ) {
				$node['description'] = 'Authorised Yamaha distributor and FANE Africa partner in Kigali, Rwanda: professional audio, lighting, acoustics, studio and AV installation.';
			}
			$graph[ $i ] = $node;
		}

		// Yamaha and FANE pages: say what the page is about, explicitly.
		if ( is_singular() ) {
			$slug  = get_post_field( 'post_name', get_queried_object_id() );
			$about = null;
			if ( 'yamaha' === $slug ) {
				$about = array( '@type' => 'Brand', 'name' => 'Yamaha', 'url' => 'https://www.yamaha.com/', 'sameAs' => array( 'https://en.wikipedia.org/wiki/Yamaha_Corporation' ) );
			} elseif ( 'fane' === $slug ) {
				$about = array( '@type' => 'Brand', 'name' => 'FANE', 'url' => 'https://www.fane-international.com/' );
			}
			if ( $about ) {
				foreach ( $graph as $i => $node ) {
					if ( is_array( $node ) && isset( $node['@type'] ) && in_array( 'WebPage', (array) $node['@type'], true ) ) {
						$graph[ $i ]['about'] = $about;
					}
				}
			}
		}
		return $graph;
	},
	30
);

/* ---------- 4: visible FAQs on the two solution pages ---------- */
function scrw_seo_faqs() {
	return array(
		'professional-audio' => array(
			array( 'q' => 'Where can I buy professional sound equipment in Kigali?', 'a' => 'At the Sound Creations Rwanda showroom on KN1 Rd, Muhima, Kigali. We stock Yamaha, dB Technologies, Shure, FANE and other leading brands, with warranty and local support.' ),
			array( 'q' => 'Are you an authorised Yamaha distributor in Rwanda?', 'a' => 'Yes. Sound Creations Rwanda is the authorised Yamaha distributor in Rwanda, supplying Yamaha mixers, loudspeakers, keyboards, digital pianos and guitars with manufacturer warranty.' ),
			array( 'q' => 'Do you design PA systems for churches and conference halls?', 'a' => 'Yes. We survey the room, design the speaker coverage, supply and install the system and train your team. Recent projects include Christian Life Assembly, Intare Kivu Arena and MINECOFIN.' ),
			array( 'q' => 'Do you work outside Kigali?', 'a' => 'Yes. We deliver and install across Rwanda, including Rubavu, Musanze and Huye, and support projects in the wider East Africa region with the Sound Creations group.' ),
		),
		'installation'       => array(
			array( 'q' => 'What does a sound system installation in Rwanda include?', 'a' => 'A site survey, system design, supply of genuine equipment, professional installation and cabling, calibration and commissioning, operator training and after-sales support from our Kigali team.' ),
			array( 'q' => 'Can you improve the acoustics of an existing hall or church?', 'a' => 'Yes. We measure the room, design acoustic treatment such as wall panels, diffusers and ceiling absorbers, install it and then tune the sound system to the treated space.' ),
			array( 'q' => 'How long does an AV installation take?', 'a' => 'It depends on the size of the venue. A boardroom can take a few days; a full auditorium or church usually takes a few weeks. We agree the timeline with you after the site survey.' ),
			array( 'q' => 'How do I get a quote for an installation?', 'a' => 'Request a consultation online, call +250 783 141 050 or message us on WhatsApp. We will arrange a site visit and send a detailed proposal.' ),
		),
	);
}

/* Seed once; never overwrite FAQs an editor has written. */
add_action(
	'admin_init',
	function () {
		if ( get_option( 'scrw_seo_ver' ) === SCRW_SEO_VERSION || ! post_type_exists( 'sc_solution' ) ) {
			return;
		}
		foreach ( scrw_seo_faqs() as $slug => $rows ) {
			$p = get_page_by_path( $slug, OBJECT, 'sc_solution' );
			if ( ! $p ) {
				return; // Pages not created yet (rw-content-5 runs at priority 12); try next load.
			}
			$cur = get_post_meta( $p->ID, '_sc_faq', true );
			if ( empty( $cur ) ) {
				update_post_meta( $p->ID, '_sc_faq', $rows );
			}
		}
		update_option( 'scrw_seo_ver', SCRW_SEO_VERSION );
	},
	40
);

/*
 * These two pages use the group layout and print no body text, so Core's
 * the_content FAQ block never runs. Print it under the project strip. If an
 * editor adds body text later, Core prints it instead and this steps aside.
 */
add_action(
	'sc_solution_after_kind',
	function ( $kind ) {
		if ( ! function_exists( 'sc_faq_render' ) || ! in_array( $kind, array( 'audio', 'integration' ), true ) ) {
			return;
		}
		if ( '' !== trim( (string) get_post_field( 'post_content', get_queried_object_id() ) ) ) {
			return;
		}
		$block = sc_faq_render( get_queried_object_id(), __( 'Frequently asked questions', 'soundcreations-rwanda' ) );
		if ( $block ) {
			echo '<section class="sc-section sc-section--tight"><div class="sc-container">' . $block . '</div></section>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Core renders escaped markup.
		}
	},
	20
);
