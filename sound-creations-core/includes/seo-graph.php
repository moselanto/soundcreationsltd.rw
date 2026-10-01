<?php
/**
 * Unified JSON-LD @graph for structured data.
 *
 * WHY THIS FILE REPLACED THE OLD APPROACH
 * ---------------------------------------
 * The previous implementation emitted up to seven separate <script> blocks per
 * page: one @graph holding Organization + WebSite, then standalone Service,
 * Product, CreativeWork, Brand, Article, CollectionPage and BreadcrumbList
 * blocks. Each standalone node had no @id, so Google received a set of
 * disconnected assertions rather than one described entity. Nothing tied the
 * Product on /products/fane-cd140/ to the Organization that sells it, or the
 * BreadcrumbList to the page it describes.
 *
 * Everything now lives in ONE @graph with stable @id values and cross
 * references. That is what lets a crawler resolve "this page is about this
 * product, sold by this company, which operates from these places, and sits
 * at this position in the site" as a single connected statement.
 *
 * GROUND RULES
 * ------------
 * 1. Never assert a fact the site cannot back up. No prices, no stock status,
 *    no review counts, no aggregate ratings, no invented branch addresses.
 *    Fabricated structured data is what triggers manual actions, and it is the
 *    exact class of problem that got a sister property suspended for
 *    Misrepresentation. Absent data means an omitted property, not a guess.
 * 2. Emit a node only when its required properties are genuinely present.
 *    A half-populated node is worse than no node.
 * 3. Drive everything from Central Settings and real published content so the
 *    graph cannot drift out of date as the catalogue changes.
 *
 * @package SoundCreationsCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ============================================================
   IDENTIFIERS

   Stable, absolute, fragment-based @ids. These strings are the
   joints of the graph -- once published they must not change,
   because Google uses them to merge what it learns across
   crawls. Never make one depend on a post title.
   ============================================================ */

/** Site-wide entity id. */
function sc_seo_id( $fragment ) {
	return home_url( '/#' ) . $fragment;
}

/** The canonical URL of whatever is currently being rendered. */
function sc_seo_current_url() {
	if ( function_exists( 'sc_seo_canonical' ) ) {
		$c = sc_seo_canonical();
		if ( $c ) {
			return $c;
		}
	}
	if ( is_singular() ) {
		return get_permalink();
	}
	if ( is_post_type_archive() ) {
		$pt = get_query_var( 'post_type' );
		if ( is_array( $pt ) ) {
			$pt = reset( $pt );
		}
		$l = get_post_type_archive_link( $pt );
		if ( $l ) {
			return $l;
		}
	}
	if ( is_tax() || is_category() || is_tag() ) {
		$t = get_queried_object();
		if ( $t && ! is_wp_error( $t ) ) {
			$l = get_term_link( $t );
			if ( ! is_wp_error( $l ) ) {
				return $l;
			}
		}
	}
	return home_url( '/' );
}

/** Per-page entity ids, derived from the canonical URL. */
function sc_seo_page_id( $fragment = 'webpage' ) {
	return untrailingslashit( sc_seo_current_url() ) . '/#' . $fragment;
}

/* ============================================================
   AREA SERVED

   Typed Country/AdministrativeArea objects rather than bare
   strings. "Kenya" as a string is a label; Country + name is a
   resolvable entity, which is what lets the graph support
   location-qualified queries ("... in Kenya", "... Nairobi").
   ============================================================ */
function sc_seo_areas_served() {
	return array(
		array(
			'@type' => 'Country',
			'name'  => 'Kenya',
		),
		array(
			'@type' => 'Country',
			'name'  => 'Rwanda',
		),
		array(
			'@type' => 'Country',
			'name'  => 'Democratic Republic of the Congo',
		),
		array(
			'@type' => 'Country',
			'name'  => 'United Arab Emirates',
		),
		array(
			'@type' => 'AdministrativeArea',
			'name'  => 'East Africa',
		),
	);
}

/* ============================================================
   BRANCHES

   Sound Creations operates from Nairobi, Kigali, DR Congo and
   Dubai. Each becomes its own LocalBusiness node so that each
   can carry its own address, phone and geo -- which is what
   local search actually ranks on.

   CRITICAL: a branch is emitted ONLY when a real street address
   has been entered in Central Settings. Nairobi's is on file.
   The other three are deliberately silent until Moses supplies
   them. Inventing a Kigali or Dubai address to "complete" the
   markup would be fabricated NAP data: it would conflict with
   every other citation of the business online, and local search
   punishes exactly that inconsistency.
   ============================================================ */
function sc_seo_branches() {
	return array(
		'nairobi' => array(
			'label'    => 'Nairobi',
			'locality' => 'Nairobi',
			'region'   => 'Nairobi County',
			'country'  => 'KE',
			'keys'     => array(
				'address' => 'address',
				'phone'   => 'phone',
				'lat'     => 'geo_lat',
				'lng'     => 'geo_lng',
			),
			'primary'  => true,
		),
		'kigali'  => array(
			'label'    => 'Kigali',
			'locality' => 'Kigali',
			'region'   => 'Kigali City',
			'country'  => 'RW',
			'keys'     => array(
				'address' => 'branch_kigali_address',
				'phone'   => 'branch_kigali_phone',
				'lat'     => 'branch_kigali_lat',
				'lng'     => 'branch_kigali_lng',
			),
		),
		'drc'     => array(
			'label'    => 'DR Congo',
			'locality' => '',
			'region'   => '',
			'country'  => 'CD',
			'keys'     => array(
				'address' => 'branch_drc_address',
				'phone'   => 'branch_drc_phone',
				'lat'     => 'branch_drc_lat',
				'lng'     => 'branch_drc_lng',
			),
		),
		'dubai'   => array(
			'label'    => 'Dubai',
			'locality' => 'Dubai',
			'region'   => 'Dubai',
			'country'  => 'AE',
			'keys'     => array(
				'address' => 'branch_dubai_address',
				'phone'   => 'branch_dubai_phone',
				'lat'     => 'branch_dubai_lat',
				'lng'     => 'branch_dubai_lng',
			),
		),
	);
}

/**
 * Opening hours, parsed from the human-readable Central Settings strings.
 *
 * Settings hold display copy such as "Mon - Fri: 9.00am - 5.30pm", which is
 * right for a footer and useless to a crawler. Rather than duplicate the hours
 * into a second set of machine fields that can silently disagree with the
 * visible ones, this reads the display string and converts it. If it cannot
 * parse confidently it returns nothing -- a missing property beats a wrong one.
 */
function sc_seo_hours_spec() {
	$out = array();

	$map = array(
		array( 'hours_week', array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday' ) ),
		array( 'hours_sat', array( 'Saturday' ) ),
	);

	foreach ( $map as $row ) {
		$raw = (string) sc_core_get( $row[0] );
		if ( '' === $raw ) {
			continue;
		}
		if ( preg_match( '/closed/i', $raw ) ) {
			continue;
		}
		// Grab the two clock times, tolerating "9.00am", "9:00 am", "17:30".
		if ( ! preg_match_all( '/(\d{1,2})[.:]?(\d{2})?\s*(am|pm)?/i', $raw, $m, PREG_SET_ORDER ) ) {
			continue;
		}
		$times = array();
		foreach ( $m as $hit ) {
			if ( '' === $hit[0] || ! isset( $hit[1] ) ) {
				continue;
			}
			$h = (int) $hit[1];
			$i = isset( $hit[2] ) && '' !== $hit[2] ? (int) $hit[2] : 0;
			$ap = isset( $hit[3] ) ? strtolower( $hit[3] ) : '';
			if ( 'pm' === $ap && $h < 12 ) {
				$h += 12;
			}
			if ( 'am' === $ap && 12 === $h ) {
				$h = 0;
			}
			if ( $h > 23 || $i > 59 ) {
				continue;
			}
			$times[] = sprintf( '%02d:%02d', $h, $i );
			if ( count( $times ) === 2 ) {
				break;
			}
		}
		if ( count( $times ) !== 2 ) {
			continue;
		}
		$out[] = array(
			'@type'     => 'OpeningHoursSpecification',
			'dayOfWeek' => $row[1],
			'opens'     => $times[0],
			'closes'    => $times[1],
		);
	}

	return $out;
}

/* ============================================================
   SERVICE CATALOGUE

   Built from the published Solution entries rather than a
   hardcoded list, so the catalogue can never drift out of step
   with the site. Each entry points at the real Service @id, so
   the catalogue is a set of references into the graph instead of
   a parallel copy of the same claims.
   ============================================================ */
function sc_seo_offer_catalog() {
	$solutions = get_posts(
		array(
			'post_type'        => 'sc_solution',
			'post_status'      => 'publish',
			'numberposts'      => 30,
			'orderby'          => 'menu_order title',
			'order'            => 'ASC',
			'suppress_filters' => false,
			'fields'           => 'ids',
		)
	);
	if ( empty( $solutions ) ) {
		return array();
	}

	$items = array();
	foreach ( $solutions as $sid ) {
		$items[] = array(
			'@type'       => 'Offer',
			'itemOffered' => array(
				'@type'       => 'Service',
				'@id'         => untrailingslashit( get_permalink( $sid ) ) . '/#service',
				'name'        => get_the_title( $sid ),
				'serviceType' => get_the_title( $sid ),
				'url'         => get_permalink( $sid ),
				'provider'    => array( '@id' => sc_seo_id( 'organization' ) ),
			),
		);
	}

	return array(
		'@type'           => 'OfferCatalog',
		'@id'             => sc_seo_id( 'services' ),
		'name'            => 'Audio, acoustic and audio-visual services',
		'itemListElement' => $items,
	);
}

/* ============================================================
   ORGANIZATION
   ============================================================ */
function sc_seo_node_organization() {
	$org_id = sc_seo_id( 'organization' );
	$name   = sc_core_get( 'company_name', get_bloginfo( 'name' ) );

	// ProfessionalService is a LocalBusiness subtype. Declaring both it and
	// Organization lets the same entity satisfy company-level queries and
	// local-intent queries without maintaining two competing nodes.
	$org = array(
		'@type'      => array( 'Organization', 'ProfessionalService' ),
		'@id'        => $org_id,
		'name'       => $name,
		'url'        => home_url( '/' ),
		'areaServed' => sc_seo_areas_served(),
	);

	$legal = sc_core_get( 'company_name' );
	if ( $legal && $legal !== $name ) {
		$org['legalName'] = $legal;
	}

	$desc = sc_core_get( 'tagline', get_bloginfo( 'description' ) );
	if ( $desc ) {
		$org['description'] = $desc;
	}

	$slogan = sc_core_get( 'slogan' );
	if ( $slogan ) {
		$org['slogan'] = $slogan;
	}

	$logo = get_site_icon_url( 512 );
	if ( $logo ) {
		$org['logo'] = array(
			'@type' => 'ImageObject',
			'@id'   => sc_seo_id( 'logo' ),
			'url'   => $logo,
		);
		$org['image'] = array( '@id' => sc_seo_id( 'logo' ) );
	}

	$phone = sc_core_get( 'phone' );
	if ( $phone ) {
		$org['telephone'] = $phone;
	}
	$email = sc_core_get( 'email' );
	if ( $email ) {
		$org['email'] = $email;
	}

	// The head-office address doubles as the Organization address so the
	// company entity is itself locatable, not only its branch nodes.
	$addr = sc_core_get( 'address' );
	if ( $addr ) {
		$org['address'] = array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => $addr,
			'addressLocality' => 'Nairobi',
			'addressRegion'   => 'Nairobi County',
			'addressCountry'  => 'KE',
		);
	}

	$org['knowsAbout'] = array(
		'professional audio systems',
		'sound reinforcement',
		'public address systems',
		'loudspeaker system design',
		'line array systems',
		'audio-visual integration',
		'audio-visual installation',
		'architectural acoustics',
		'room acoustics',
		'acoustic treatment',
		'soundproofing',
		'noise control',
		'reverberation control',
		'speech intelligibility',
		'RT60 measurement',
		'acoustic testing and measurement',
		'conferencing systems',
		'sound masking',
		'system commissioning',
		'audio system calibration',
		'digital mixing consoles',
		'professional microphones',
		// Lighting was missing from every keyword and schema surface even
		// though the site's own title tag has always read "AV, Audio,
		// Lighting & Acoustic Solutions". Competitor benchmarking on
		// 30 Sep 2026 found StagePass mentioning lighting 27 times on its
		// home page against 9 here, so the term was being conceded despite
		// being claimed in the title. These entries state the capability
		// the site already advertises; they do not invent a new service.
		'stage lighting',
		'architectural lighting',
		'lighting design',
		'lighting control systems',
		'DMX lighting control',
		'LED screens and video walls',
		'digital signage',
		'stage and venue rigging',
		'equipment distribution and dealership',
	);

	$same = array();
	foreach ( array( 'facebook', 'x', 'linkedin', 'youtube', 'instagram', 'google_business', 'map_url' ) as $s ) {
		$u = sc_core_get( $s );
		if ( $u && preg_match( '#^https?://#i', $u ) ) {
			$same[] = $u;
		}
	}
	$same = array_values( array_unique( $same ) );
	if ( $same ) {
		$org['sameAs'] = $same;
	}

	// Contact route for enquiries. availableLanguage is safe to assert:
	// the entire site is published in English.
	if ( $phone ) {
		$org['contactPoint'] = array(
			array(
				'@type'             => 'ContactPoint',
				'telephone'         => $phone,
				'contactType'       => 'sales',
				'availableLanguage' => array( 'English' ),
				'areaServed'        => array( 'KE', 'RW', 'CD', 'AE' ),
			),
		);
	}

	$catalog = sc_seo_offer_catalog();
	if ( $catalog ) {
		$org['hasOfferCatalog'] = $catalog;
	}

	// Branch references, added only for branches that really resolved.
	$subs = array();
	foreach ( sc_seo_branches() as $slug => $branch ) {
		if ( sc_core_get( $branch['keys']['address'] ) ) {
			$subs[] = array( '@id' => sc_seo_id( 'branch-' . $slug ) );
		}
	}
	if ( count( $subs ) > 1 ) {
		$org['subOrganization'] = $subs;
	}

	return $org;
}

/* ============================================================
   BRANCH NODES
   ============================================================ */
function sc_seo_nodes_branches() {
	$nodes = array();
	$org   = sc_seo_id( 'organization' );
	$name  = sc_core_get( 'company_name', get_bloginfo( 'name' ) );
	$logo  = get_site_icon_url( 512 );
	$hours = sc_seo_hours_spec();

	foreach ( sc_seo_branches() as $slug => $branch ) {
		$addr = sc_core_get( $branch['keys']['address'] );
		if ( ! $addr ) {
			continue; // Deliberate: no address on file means no node.
		}

		$node = array(
			'@type'              => 'LocalBusiness',
			'@id'                => sc_seo_id( 'branch-' . $slug ),
			'name'               => $name . ' - ' . $branch['label'],
			'parentOrganization' => array( '@id' => $org ),
			'url'                => home_url( '/' ),
		);

		$address = array(
			'@type'          => 'PostalAddress',
			'streetAddress'  => $addr,
			'addressCountry' => $branch['country'],
		);
		if ( $branch['locality'] ) {
			$address['addressLocality'] = $branch['locality'];
		}
		if ( $branch['region'] ) {
			$address['addressRegion'] = $branch['region'];
		}
		$node['address'] = $address;

		$phone = sc_core_get( $branch['keys']['phone'] );
		if ( ! $phone && ! empty( $branch['primary'] ) ) {
			$phone = sc_core_get( 'phone' );
		}
		if ( $phone ) {
			$node['telephone'] = $phone;
		}

		$email = sc_core_get( 'email' );
		if ( $email && ! empty( $branch['primary'] ) ) {
			$node['email'] = $email;
		}

		$lat = sc_core_get( $branch['keys']['lat'] );
		$lng = sc_core_get( $branch['keys']['lng'] );
		if ( is_numeric( $lat ) && is_numeric( $lng ) ) {
			$node['geo'] = array(
				'@type'     => 'GeoCoordinates',
				'latitude'  => (float) $lat,
				'longitude' => (float) $lng,
			);
		}

		if ( $logo ) {
			$node['image'] = array( '@id' => sc_seo_id( 'logo' ) );
		}
		if ( $hours && ! empty( $branch['primary'] ) ) {
			$node['openingHoursSpecification'] = $hours;
		}

		$node['areaServed'] = array_values(
			array_filter(
				array(
					$branch['locality'] ? array(
						'@type' => 'City',
						'name'  => $branch['locality'],
					) : null,
					array(
						'@type' => 'Country',
						'name'  => $branch['label'],
					),
				)
			)
		);

		$nodes[] = $node;
	}

	return $nodes;
}

/* ============================================================
   WEBSITE

   SearchAction is declared only because a real search endpoint
   exists at /?s=. It is the prerequisite for a sitelinks
   searchbox, and claiming it without the endpoint would be a
   broken promise to the crawler.
   ============================================================ */
function sc_seo_node_website() {
	return array(
		'@type'           => 'WebSite',
		'@id'             => sc_seo_id( 'website' ),
		'url'             => home_url( '/' ),
		'name'            => sc_core_get( 'company_name', get_bloginfo( 'name' ) ),
		'inLanguage'      => 'en',
		'publisher'       => array( '@id' => sc_seo_id( 'organization' ) ),
		'potentialAction' => array(
			array(
				'@type'       => 'SearchAction',
				'target'      => array(
					'@type'       => 'EntryPoint',
					'urlTemplate' => home_url( '/?s={search_term_string}' ),
				),
				'query-input' => 'required name=search_term_string',
			),
		),
	);
}

/* ============================================================
   WEBPAGE

   Missing entirely before this build. Without it, breadcrumbs
   and entity nodes floated free with nothing stating which page
   they described. The subtype is chosen from context, because
   ContactPage and AboutPage carry meaning that a generic
   WebPage does not.
   ============================================================ */
function sc_seo_node_webpage() {
	$url  = sc_seo_current_url();
	$type = 'WebPage';

	if ( is_front_page() ) {
		$type = 'WebPage';
	} elseif ( is_page( 'contact' ) || is_page( 'request-a-quote' ) || is_page( 'request-a-consultation' ) ) {
		$type = 'ContactPage';
	} elseif ( is_page( 'about' ) ) {
		$type = 'AboutPage';
	} elseif ( is_post_type_archive() || is_tax() || is_category() || is_tag() || is_home() ) {
		$type = 'CollectionPage';
	} elseif ( is_singular( array( 'sc_product', 'sc_brand' ) ) ) {
		$type = 'ItemPage';
	}

	$node = array(
		'@type'      => $type,
		'@id'        => sc_seo_page_id( 'webpage' ),
		'url'        => $url,
		'name'       => wp_get_document_title(),
		'isPartOf'   => array( '@id' => sc_seo_id( 'website' ) ),
		'about'      => array( '@id' => sc_seo_id( 'organization' ) ),
		'inLanguage' => 'en',
	);

	if ( function_exists( 'sc_seo_description' ) ) {
		$d = sc_seo_description();
		if ( $d ) {
			$node['description'] = $d;
		}
	}

	if ( is_singular() ) {
		$pid                    = get_queried_object_id();
		$node['datePublished']  = get_the_date( 'c', $pid );
		$node['dateModified']   = get_the_modified_date( 'c', $pid );
	}

	if ( is_singular() && has_post_thumbnail() ) {
		$src = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
		if ( $src ) {
			$node['primaryImageOfPage'] = array( '@id' => sc_seo_page_id( 'primaryimage' ) );
		}
	}

	$node['breadcrumb'] = array( '@id' => sc_seo_page_id( 'breadcrumb' ) );

	return $node;
}

/** ImageObject for the page's lead image, referenced by primaryImageOfPage. */
function sc_seo_node_primary_image() {
	if ( ! is_singular() || ! has_post_thumbnail() ) {
		return null;
	}
	$id  = get_post_thumbnail_id();
	$src = wp_get_attachment_image_src( $id, 'full' );
	if ( ! $src ) {
		return null;
	}
	$node = array(
		'@type' => 'ImageObject',
		'@id'   => sc_seo_page_id( 'primaryimage' ),
		'url'   => $src[0],
	);
	if ( ! empty( $src[1] ) ) {
		$node['width'] = (int) $src[1];
	}
	if ( ! empty( $src[2] ) ) {
		$node['height'] = (int) $src[2];
	}
	$alt = get_post_meta( $id, '_wp_attachment_image_alt', true );
	if ( $alt ) {
		$node['caption'] = $alt;
	}
	return $node;
}

/* ============================================================
   BREADCRUMBS

   Previously limited to is_singular(), which left every archive
   -- /brands/, /projects/, /solutions/, /videos/ -- with no
   breadcrumb at all. Those are exactly the pages most likely to
   rank for a category query, and the ones where a breadcrumb
   trail most improves the result's appearance.
   ============================================================ */
function sc_seo_node_breadcrumb() {
	$crumbs = array(
		array(
			'name' => sc_core_get( 'company_name', get_bloginfo( 'name' ) ),
			'item' => home_url( '/' ),
		),
	);

	if ( is_singular() ) {
		$pt  = get_post_type();
		$pto = get_post_type_object( $pt );

		if ( 'page' === $pt ) {
			// Honour real page hierarchy so nested pages read correctly.
			$parents = array_reverse( get_post_ancestors( get_queried_object_id() ) );
			foreach ( $parents as $anc ) {
				$crumbs[] = array(
					'name' => get_the_title( $anc ),
					'item' => get_permalink( $anc ),
				);
			}
		} elseif ( $pto && ! empty( $pto->has_archive ) ) {
			$crumbs[] = array(
				'name' => $pto->labels->name,
				'item' => get_post_type_archive_link( $pt ),
			);
		}

		$crumbs[] = array(
			'name' => get_the_title(),
			'item' => get_permalink(),
		);
	} elseif ( is_post_type_archive() ) {
		$crumbs[] = array(
			'name' => post_type_archive_title( '', false ),
			'item' => sc_seo_current_url(),
		);
	} elseif ( is_tax() || is_category() || is_tag() ) {
		$t = get_queried_object();
		if ( $t && ! is_wp_error( $t ) ) {
			$crumbs[] = array(
				'name' => $t->name,
				'item' => sc_seo_current_url(),
			);
		}
	} elseif ( is_search() ) {
		$crumbs[] = array(
			'name' => 'Search results',
			'item' => sc_seo_current_url(),
		);
	}

	if ( count( $crumbs ) < 2 ) {
		return null; // The front page is not a trail.
	}

	$items = array();
	foreach ( $crumbs as $i => $c ) {
		$items[] = array(
			'@type'    => 'ListItem',
			'position' => $i + 1,
			'name'     => $c['name'],
			'item'     => $c['item'],
		);
	}

	return array(
		'@type'           => 'BreadcrumbList',
		'@id'             => sc_seo_page_id( 'breadcrumb' ),
		'itemListElement' => $items,
	);
}

/* ============================================================
   VIDEOOBJECT

   The /videos/ library carried no video markup at all, which
   made five genuinely rich pages invisible to video search.
   Thumbnails are derived from the YouTube id rather than stored
   separately, so they cannot fall out of sync.
   ============================================================ */
function sc_seo_youtube_id( $url ) {
	$url = (string) $url;
	if ( preg_match( '#(?:youtube\.com/(?:watch\?(?:.*&)?v=|embed/|shorts/|live/)|youtu\.be/)([A-Za-z0-9_-]{11})#i', $url, $m ) ) {
		return $m[1];
	}
	return '';
}

function sc_seo_node_video() {
	if ( ! is_singular( 'sc_resource' ) ) {
		return null;
	}
	$pid = get_queried_object_id();
	// fields.php stores every custom field with a '_sc_' prefix (see its
	// update_post_meta call), so the key is '_sc_video_url'. Reading the
	// unprefixed name returned nothing, which silently disabled VideoObject
	// on all five /videos/ pages and let the Article fallback emit instead.
	// The unprefixed fallback stays for any legacy row written directly.
	$raw = get_post_meta( $pid, '_sc_video_url', true );
	if ( '' === trim( (string) $raw ) ) {
		$raw = get_post_meta( $pid, 'video_url', true );
	}
	$vid = sc_seo_youtube_id( $raw );
	if ( ! $vid ) {
		return null; // Not a video resource; emit nothing rather than a stub.
	}

	// description is a REQUIRED field for Google video rich results, and a
	// live check on 30 Sep 2026 found it EMPTY on all five /videos/ pages.
	// Those posts carry no excerpt and almost no post_content (123-131
	// words each, with no embedded player on the singular view), so both
	// original sources returned ''. A VideoObject without description is
	// ineligible for a rich result, so the markup was being wasted.
	//
	// Chain now ends at the per-type meta description seo.php already
	// generates from the real business geography. That is generated copy
	// rather than authored prose, but it is truthful and specific, unlike
	// padding the field by repeating the title back at the crawler.
	$desc = wp_strip_all_tags( (string) get_the_excerpt( $pid ) );
	if ( '' === trim( $desc ) ) {
		$desc = wp_trim_words( wp_strip_all_tags( (string) get_post_field( 'post_content', $pid ) ), 40 );
	}
	if ( '' === trim( $desc ) ) {
		$meta_desc = get_post_meta( $pid, '_sc_seo_desc', true );
		if ( $meta_desc ) {
			$desc = wp_strip_all_tags( $meta_desc );
		}
	}
	if ( '' === trim( $desc ) && function_exists( 'sc_seo_description' ) ) {
		$desc = wp_strip_all_tags( (string) sc_seo_description() );
	}
	if ( '' === trim( $desc ) ) {
		return null; // Nothing truthful to say: emit no node rather than an invalid one.
	}

	$node = array(
		'@type'        => 'VideoObject',
		'@id'          => sc_seo_page_id( 'video' ),
		'name'         => get_the_title( $pid ),
		'description'  => $desc,
		'thumbnailUrl' => array( 'https://i.ytimg.com/vi/' . $vid . '/maxresdefault.jpg' ),
		'uploadDate'   => get_the_date( 'c', $pid ),
		'embedUrl'     => 'https://www.youtube.com/embed/' . $vid,
		'url'          => get_permalink( $pid ),
		'publisher'    => array( '@id' => sc_seo_id( 'organization' ) ),
		'isPartOf'     => array( '@id' => sc_seo_page_id( 'webpage' ) ),
	);

	// Only claim a custom thumbnail when one actually exists.
	if ( has_post_thumbnail( $pid ) ) {
		$src = wp_get_attachment_image_src( get_post_thumbnail_id( $pid ), 'full' );
		if ( $src ) {
			array_unshift( $node['thumbnailUrl'], $src[0] );
		}
	}

	return $node;
}

/* ============================================================
   PER-TYPE ENTITY NODES
   ============================================================ */
function sc_seo_nodes_entities() {
	$out    = array();
	$org    = array( '@id' => sc_seo_id( 'organization' ) );
	$page   = array( '@id' => sc_seo_page_id( 'webpage' ) );

	/* ---- Service (Solutions and Services) ---- */
	if ( is_singular( array( 'sc_solution', 'sc_service' ) ) ) {
		$pid  = get_queried_object_id();
		$node = array(
			'@type'           => 'Service',
			'@id'             => sc_seo_page_id( 'service' ),
			'name'            => get_the_title( $pid ),
			'serviceType'     => get_the_title( $pid ),
			'provider'        => $org,
			'areaServed'      => sc_seo_areas_served(),
			'url'             => get_permalink( $pid ),
			'mainEntityOfPage' => $page,
		);
		$d = wp_strip_all_tags( get_the_excerpt( $pid ) );
		if ( $d ) {
			$node['description'] = $d;
		}
		if ( has_post_thumbnail( $pid ) ) {
			$src = wp_get_attachment_image_src( get_post_thumbnail_id( $pid ), 'full' );
			if ( $src ) {
				$node['image'] = $src[0];
			}
		}
		$out[] = $node;
	}

	/* ---- Product ----
	   Enquiry-only catalogue: NO offers, price, availability, GTIN or rating.
	   Google will report "missing field offers" in Rich Results Test and will
	   not grant a product rich result. That is the correct trade: the business
	   publishes no prices, and inventing them to unlock a rich snippet is the
	   misrepresentation pattern we are specifically avoiding. The node still
	   earns entity recognition for brand plus model queries. */
	if ( is_singular( 'sc_product' ) ) {
		$pid  = get_queried_object_id();
		$node = array(
			'@type'            => 'Product',
			'@id'              => sc_seo_page_id( 'product' ),
			'name'             => get_the_title( $pid ),
			'url'              => get_permalink( $pid ),
			'mainEntityOfPage' => $page,
		);
		$d = wp_strip_all_tags( get_the_excerpt( $pid ) );
		if ( $d ) {
			$node['description'] = $d;
		}
		$brand = get_post_meta( $pid, '_sc_brand_name', true );
		if ( ! $brand ) {
			$terms = get_the_terms( $pid, 'sc_brand_tax' );
			if ( $terms && ! is_wp_error( $terms ) ) {
				$first = reset( $terms );
				$brand = $first->name;
			}
		}
		if ( $brand ) {
			$node['brand'] = array(
				'@type' => 'Brand',
				'name'  => $brand,
			);
		}
		$model = get_post_meta( $pid, '_sc_model', true );
		if ( $model ) {
			$node['sku']   = $model;
			$node['mpn']   = $model;
			$node['model'] = $model;
		}
		if ( has_post_thumbnail( $pid ) ) {
			$src = wp_get_attachment_image_src( get_post_thumbnail_id( $pid ), 'full' );
			if ( $src ) {
				$node['image'] = $src[0];
			}
		}
		$node['seller'] = $org;
		$out[]          = $node;
	}

	/* ---- Brand ---- */
	if ( is_singular( 'sc_brand' ) ) {
		$pid  = get_queried_object_id();
		$node = array(
			'@type'            => 'Brand',
			'@id'              => sc_seo_page_id( 'brand' ),
			'name'             => get_the_title( $pid ),
			'url'              => get_permalink( $pid ),
			'mainEntityOfPage' => $page,
		);
		$d = wp_strip_all_tags( get_the_excerpt( $pid ) );
		if ( $d ) {
			$node['description'] = $d;
		}
		$site = get_post_meta( $pid, '_sc_brand_url', true );
		if ( $site ) {
			$node['sameAs'] = $site;
		}
		if ( has_post_thumbnail( $pid ) ) {
			$src = wp_get_attachment_image_src( get_post_thumbnail_id( $pid ), 'full' );
			if ( $src ) {
				$node['logo']  = $src[0];
				$node['image'] = $src[0];
			}
		}
		$out[] = $node;
	}

	/* ---- Project: real-world proof of capability ---- */
	if ( is_singular( array( 'sc_project', 'sc_case_study' ) ) ) {
		$pid  = get_queried_object_id();
		$node = array(
			'@type'            => 'CreativeWork',
			'@id'              => sc_seo_page_id( 'project' ),
			'name'             => get_the_title( $pid ),
			'url'              => get_permalink( $pid ),
			'creator'          => $org,
			'about'            => $org,
			'mainEntityOfPage' => $page,
		);
		$d = wp_strip_all_tags( get_the_excerpt( $pid ) );
		if ( $d ) {
			$node['description'] = $d;
		}
		if ( has_post_thumbnail( $pid ) ) {
			$src = wp_get_attachment_image_src( get_post_thumbnail_id( $pid ), 'full' );
			if ( $src ) {
				$node['image'] = $src[0];
			}
		}
		$locs = get_the_terms( $pid, 'sc_location' );
		if ( $locs && ! is_wp_error( $locs ) ) {
			$first                   = reset( $locs );
			$node['contentLocation'] = array(
				'@type' => 'Place',
				'name'  => $first->name,
			);
		}
		$brands = get_the_terms( $pid, 'sc_brand_tax' );
		if ( $brands && ! is_wp_error( $brands ) ) {
			$mentions = array();
			foreach ( $brands as $b ) {
				$mentions[] = array(
					'@type' => 'Brand',
					'name'  => $b->name,
				);
			}
			$node['mentions'] = $mentions;
		}
		$inds = get_the_terms( $pid, 'sc_industry' );
		if ( $inds && ! is_wp_error( $inds ) ) {
			$kw = array();
			foreach ( $inds as $i ) {
				$kw[] = $i->name;
			}
			$node['keywords'] = implode( ', ', $kw );
		}
		$out[] = $node;
	}

	/* ---- Article (written resources without a video) ---- */
	if ( is_singular( array( 'sc_resource', 'post' ) ) && ! sc_seo_node_video() ) {
		$pid  = get_queried_object_id();
		$node = array(
			'@type'            => 'Article',
			'@id'              => sc_seo_page_id( 'article' ),
			'headline'         => get_the_title( $pid ),
			'url'              => get_permalink( $pid ),
			'datePublished'    => get_the_date( 'c', $pid ),
			'dateModified'     => get_the_modified_date( 'c', $pid ),
			'publisher'        => $org,
			'mainEntityOfPage' => $page,
			'isPartOf'         => array( '@id' => sc_seo_id( 'website' ) ),
		);
		if ( has_post_thumbnail( $pid ) ) {
			$src = wp_get_attachment_image_src( get_post_thumbnail_id( $pid ), 'full' );
			if ( $src ) {
				$node['image'] = $src[0];
			}
		}
		$author = get_post_meta( $pid, '_sc_author_name', true );
		$node['author'] = $author
			? array(
				'@type' => 'Person',
				'name'  => $author,
			)
			: $org;
		$out[] = $node;
	}

	/* ---- ItemList on archives ----
	   Turns a listing page into an enumerated set rather than an opaque
	   document, which is what supports carousel-style presentation. */
	if ( is_post_type_archive( array( 'sc_solution', 'sc_product', 'sc_project', 'sc_brand', 'sc_resource', 'sc_case_study' ) ) ) {
		global $wp_query;
		$items = array();
		$pos   = 0;
		if ( $wp_query->have_posts() ) {
			foreach ( $wp_query->posts as $p ) {
				++$pos;
				$items[] = array(
					'@type'    => 'ListItem',
					'position' => $pos,
					'url'      => get_permalink( $p ),
					'name'     => get_the_title( $p ),
				);
				if ( $pos >= 60 ) {
					break;
				}
			}
		}
		if ( $items ) {
			$out[] = array(
				'@type'           => 'ItemList',
				'@id'             => sc_seo_page_id( 'itemlist' ),
				'itemListOrder'   => 'https://schema.org/ItemListOrderAscending',
				'numberOfItems'   => count( $items ),
				'itemListElement' => $items,
			);
		}
	}

	return $out;
}

/* ============================================================
   ASSEMBLY
   ============================================================ */
function sc_seo_graph() {
	if ( is_feed() || is_404() || is_admin() ) {
		return;
	}

	$graph = array();

	$graph[] = sc_seo_node_organization();
	$graph[] = sc_seo_node_website();

	foreach ( sc_seo_nodes_branches() as $b ) {
		$graph[] = $b;
	}

	$graph[] = sc_seo_node_webpage();

	$img = sc_seo_node_primary_image();
	if ( $img ) {
		$graph[] = $img;
	}

	$crumb = sc_seo_node_breadcrumb();
	if ( $crumb ) {
		$graph[] = $crumb;
	}

	$video = sc_seo_node_video();
	if ( $video ) {
		$graph[] = $video;
	}

	foreach ( sc_seo_nodes_entities() as $n ) {
		$graph[] = $n;
	}

	if ( function_exists( 'sc_seo_node_faq' ) ) {
		$faq = sc_seo_node_faq();
		if ( $faq ) {
			$graph[] = $faq;
		}
	}

	/**
	 * Late escape hatch for per-page additions.
	 *
	 * @param array $graph The assembled node list.
	 */
	$graph = apply_filters( 'sc_seo_graph', $graph );

	$payload = array(
		'@context' => 'https://schema.org',
		'@graph'   => array_values( $graph ),
	);

	echo "\n" . '<script type="application/ld+json">'
		. wp_json_encode( $payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )
		. '</script>' . "\n";
}
