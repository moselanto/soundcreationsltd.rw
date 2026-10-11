<?php
/**
 * Rwanda schema corrections and enrichment (v1.2.0).
 *
 * Runs on the Core 'sc_seo_graph' filter AFTER inc/seo.php (priority 20) and
 * inc/seo-boost.php (priority 30), so it sees the fully localised graph and
 * only fixes what a live audit of soundcreationsltd.rw found on 11 Oct 2026:
 *
 *  1. The Kigali LocalBusiness node still carried the Kenya @id
 *     "#branch-nairobi" (Core names the primary branch after Nairobi). It is
 *     now "#localbusiness" with the exact business name used everywhere
 *     else, so Google can match it to the Google Business Profile.
 *  2. Service nodes inherited Core's group areaServed (Kenya, UAE). On this
 *     site they now list Rwanda, its main cities, the DRC and East Africa.
 *  3. sameAs listed a Google Maps *search* URL and the Kenya X account.
 *     Neither is a profile of this entity, so both are dropped; the map link
 *     moves to hasMap where it belongs.
 *  4. contactPoint claimed KE and AE. It now covers RW and CD, and the
 *     second Kigali line is published as its own contact point.
 *  5. Organization description was the bare tagline. It is now a factual
 *     sentence using only claims already published on the site.
 *  6. Yamaha and FANE pages gain a Service node describing the distribution
 *     relationship, so the page is about something more specific than the
 *     company as a whole.
 *  7. Projects gain contentLocation (Place in Rwanda) from the project's
 *     location field when no location term is set. Products gain category.
 *
 * Ground rules unchanged: no prices, ratings, review counts, stock levels or
 * invented coordinates. A property is omitted when the site cannot back it.
 *
 * @package SoundCreationsRwanda
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Stable @id for the Kigali business location. Do not change once live. */
function scrw_schema_localbusiness_id() {
	return home_url( '/#localbusiness' );
}

/** Rwanda-only areaServed, shared with seo-boost.php. */
function scrw_schema_area() {
	return function_exists( 'scrw_seo_area_served' ) ? scrw_seo_area_served() : array(
		array(
			'@type' => 'Country',
			'name'  => 'Rwanda',
		),
	);
}

/** A setting from Central Settings, trimmed. */
function scrw_schema_setting( $key ) {
	if ( function_exists( 'sc_core_get' ) ) {
		return trim( (string) sc_core_get( $key ) );
	}
	if ( function_exists( 'sc_setting' ) ) {
		return trim( (string) sc_setting( $key, '' ) );
	}
	return '';
}

/** True when a URL is a real profile of this business (not a search/map link or another country's account). */
function scrw_schema_is_profile_url( $url ) {
	$url = (string) $url;
	if ( '' === $url ) {
		return false;
	}
	if ( preg_match( '#google\.[a-z.]+/maps/search|/maps\?q=|maps/search/\?api#i', $url ) ) {
		return false;
	}
	// Group / Kenya accounts describe a different legal entity.
	if ( preg_match( '#(SCL_kenya|soundcreationskenya|sound-creations-ltd-kenya)#i', $url ) ) {
		return false;
	}
	return true;
}

add_filter(
	'sc_seo_graph',
	function ( $graph ) {
		if ( ! is_array( $graph ) ) {
			return $graph;
		}

		$org_id    = home_url( '/#organization' );
		$lb_old_id = home_url( '/#branch-nairobi' );
		$lb_id     = scrw_schema_localbusiness_id();
		$name      = scrw_schema_setting( 'company_name' );
		$name      = $name ? $name : 'Sound Creations Ltd Rwanda';
		$map_url   = scrw_schema_setting( 'map_url' );
		$phone2    = scrw_schema_setting( 'phone2' );
		$email2    = scrw_schema_setting( 'email2' );

		$description = 'Sound Creations Ltd Rwanda is the Kigali company of the Sound Creations group and the authorised Yamaha distributor in Rwanda. It designs, supplies, installs and supports professional audio, conferencing, lighting and acoustic systems for churches, schools, government, hotels and venues across Rwanda.';

		foreach ( $graph as $i => $node ) {
			if ( ! is_array( $node ) ) {
				continue;
			}
			$types = isset( $node['@type'] ) ? (array) $node['@type'] : array();
			$id    = isset( $node['@id'] ) ? (string) $node['@id'] : '';

			/* ---- 1. Kigali LocalBusiness ---- */
			if ( $id === $lb_old_id || ( in_array( 'LocalBusiness', $types, true ) && $id !== $org_id ) ) {
				$node['@id']   = $lb_id;
				$node['@type'] = array( 'LocalBusiness', 'ElectronicsStore' ); // Kigali showroom sells equipment.
				$node['name']  = $name;
				$node['description'] = $description;
				$node['parentOrganization'] = array( '@id' => $org_id );
				if ( $map_url ) {
					$node['hasMap'] = $map_url;
				}
				// Keyword stuffing on the place node adds nothing; the org carries topics.
				unset( $node['keywords'] );
				$node['areaServed'] = scrw_schema_area();
				$node['currenciesAccepted'] = 'RWF';
				$graph[ $i ] = $node;
				continue;
			}

			/* ---- 2-5. Organization ---- */
			if ( $id === $org_id ) {
				if ( ! empty( $node['sameAs'] ) ) {
					$node['sameAs'] = array_values( array_filter( (array) $node['sameAs'], 'scrw_schema_is_profile_url' ) );
					if ( ! $node['sameAs'] ) {
						unset( $node['sameAs'] );
					}
				}
				if ( $map_url ) {
					$node['hasMap'] = $map_url;
				}
				$tagline = scrw_schema_setting( 'tagline' );
				if ( empty( $node['description'] ) || $node['description'] === $tagline ) {
					$node['description'] = $description;
				}
				$node['alternateName'] = array( 'Sound Creations Rwanda', 'Sound Creations (RW) Ltd' );
				$node['foundingDate']  = '2018'; // Rwanda operation, per the About page.
				$node['location']      = array( '@id' => $lb_id );
				unset( $node['keywords'] );

				$cps = array();
				$p1  = scrw_schema_setting( 'phone' );
				$e1  = scrw_schema_setting( 'email' );
				if ( $p1 ) {
					$cp = array(
						'@type'             => 'ContactPoint',
						'telephone'         => $p1,
						'contactType'       => 'sales',
						'availableLanguage' => array( 'English' ),
						'areaServed'        => array( 'RW', 'CD' ),
					);
					if ( $e1 ) {
						$cp['email'] = $e1;
					}
					$cps[] = $cp;
				}
				if ( $phone2 ) {
					$cp = array(
						'@type'             => 'ContactPoint',
						'telephone'         => $phone2,
						'contactType'       => 'customer service',
						'availableLanguage' => array( 'English' ),
						'areaServed'        => array( 'RW' ),
					);
					if ( $email2 ) {
						$cp['email'] = $email2;
					}
					$cps[] = $cp;
				}
				if ( $cps ) {
					$node['contactPoint'] = $cps;
				}
				$graph[ $i ] = $node;
				continue;
			}

			/* ---- 2. Service nodes: Rwanda geography, not the group's ---- */
			if ( in_array( 'Service', $types, true ) ) {
				$node['areaServed'] = scrw_schema_area();
				if ( is_singular() && empty( $node['image'] ) ) {
					$src = get_the_post_thumbnail_url( get_queried_object_id(), 'full' );
					if ( $src ) {
						$node['image'] = $src;
					}
				}
				if ( is_singular( 'sc_solution' ) && function_exists( 'scrw_sector_audience' ) ) {
					$aud = scrw_sector_audience( get_post_field( 'post_name', get_queried_object_id() ) );
					if ( $aud ) {
						$node['audience'] = array(
							'@type'        => 'BusinessAudience',
							'audienceType' => $aud,
						);
					}
				}
				$node['availableChannel'] = array(
					'@type'          => 'ServiceChannel',
					'serviceUrl'     => home_url( '/request-a-consultation/' ),
					'servicePhone'   => array(
						'@type'     => 'ContactPoint',
						'telephone' => scrw_schema_setting( 'phone' ),
					),
					'serviceLocation' => array( '@id' => $lb_id ),
				);
				$graph[ $i ] = $node;
				continue;
			}

			/* ---- 7a. Projects: where the work was done ---- */
			if ( in_array( 'CreativeWork', $types, true ) && is_singular( array( 'sc_project', 'sc_case_study' ) ) ) {
				if ( empty( $node['contentLocation'] ) ) {
					$loc = trim( (string) get_post_meta( get_queried_object_id(), '_sc_location', true ) );
					if ( $loc ) {
						$node['contentLocation'] = array(
							'@type'            => 'Place',
							'name'             => $loc,
							'containedInPlace' => array(
								'@type' => 'Country',
								'name'  => 'Rwanda',
							),
						);
					}
				}
				$node['provider'] = array( '@id' => $org_id );
				$graph[ $i ] = $node;
				continue;
			}

			/* ---- 7b. Products: category ---- */
			if ( in_array( 'Product', $types, true ) && is_singular( 'sc_product' ) ) {
				$cats = get_the_terms( get_queried_object_id(), 'sc_product_category' );
				if ( $cats && ! is_wp_error( $cats ) ) {
					$first            = reset( $cats );
					$node['category'] = $first->name;
				}
				$graph[ $i ] = $node;
			}
		}

		/* ---- 6. Yamaha / FANE distribution Service ---- */
		if ( is_singular() ) {
			$slug = get_post_field( 'post_name', get_queried_object_id() );
			$svc  = null;
			if ( 'yamaha' === $slug ) {
				$svc = array(
					'name'        => 'Authorised Yamaha distribution in Rwanda',
					'serviceType' => 'Authorised distributor',
					'brand'       => array(
						'@type'  => 'Brand',
						'name'   => 'Yamaha',
						'url'    => 'https://www.yamaha.com/',
						'sameAs' => array( 'https://en.wikipedia.org/wiki/Yamaha_Corporation' ),
					),
					'description' => 'Supply, warranty and local support for Yamaha professional audio (mixers, loudspeakers, amplifiers) and musical instruments (keyboards, digital pianos, guitars) from the Sound Creations showroom in Kigali.',
				);
			} elseif ( 'fane' === $slug ) {
				$svc = array(
					'name'        => 'FANE loudspeaker supply in Rwanda',
					'serviceType' => 'Loudspeaker components supply',
					'brand'       => array(
						'@type' => 'Brand',
						'name'  => 'FANE',
						'url'   => 'https://www.fane-international.com/',
					),
					'description' => 'FANE Africa partner in Kigali supplying FANE loudspeaker drivers and compression drivers for PA, line array and installed sound across Rwanda.',
				);
			}
			if ( $svc ) {
				$page_id = untrailingslashit( get_permalink( get_queried_object_id() ) );
				$graph[] = array_merge(
					array(
						'@type' => 'Service',
						'@id'   => $page_id . '/#service',
					),
					$svc,
					array(
						'provider'         => array( '@id' => $org_id ),
						'areaServed'       => scrw_schema_area(),
						'url'              => get_permalink( get_queried_object_id() ),
						'mainEntityOfPage' => array( '@id' => $page_id . '/#webpage' ),
					)
				);
			}
		}

		/* Front page: a primary image so the WebPage is not imageless. */
		if ( is_front_page() ) {
			$img = scrw_og_fallback_image();
			if ( $img ) {
				$img_id = home_url( '/#primaryimage' );
				foreach ( $graph as $i => $node ) {
					if ( is_array( $node ) && isset( $node['@id'] ) && home_url( '/#webpage' ) === $node['@id'] ) {
						$graph[ $i ]['primaryImageOfPage'] = array( '@id' => $img_id );
					}
				}
				$graph[] = array(
					'@type' => 'ImageObject',
					'@id'   => $img_id,
					'url'   => $img,
				);
			}
		}

		return $graph;
	},
	40
);

/*
 * Any reference elsewhere in the graph to the old Kenya-named branch id is
 * repointed so nothing dangles.
 */
add_filter(
	'sc_seo_graph',
	function ( $graph ) {
		$old = home_url( '/#branch-nairobi' );
		$new = scrw_schema_localbusiness_id();
		$walk = function ( $n ) use ( &$walk, $old, $new ) {
			if ( ! is_array( $n ) ) {
				return $n;
			}
			foreach ( $n as $k => $v ) {
				if ( '@id' === $k && $v === $old ) {
					$n[ $k ] = $new;
				} elseif ( is_array( $v ) ) {
					$n[ $k ] = $walk( $v );
				}
			}
			return $n;
		};
		return is_array( $graph ) ? $walk( $graph ) : $graph;
	},
	45
);

/* ============================================================
   OPEN GRAPH IMAGE FALLBACK

   The live audit found no og:image on the homepage, the Yamaha
   hub or the solution pages, so WhatsApp, LinkedIn and Facebook
   shares showed no picture. Rank Math only prints an image when
   the page has one; this hands it a relevant Rwanda photo from
   the theme when it has none. A featured image or an image set in
   the Rank Math box always wins.
   ============================================================ */
function scrw_og_fallback_image() {
	$base = get_stylesheet_directory_uri() . '/assets/img/';
	$slug = is_singular() ? get_post_field( 'post_name', get_queried_object_id() ) : '';
	$map  = array(
		'yamaha'                  => 'about-hero-showroom.webp',
		'fane'                    => 'service-distribution-showroom-rw.webp',
		'about'                   => 'about-hero-showroom.webp',
		'contact'                 => 'about-hero-showroom.webp',
		'acoustics'               => 'solution-acoustics-rw.webp',
		'architectural-acoustics' => 'solution-acoustics-rw.webp',
		'installation'            => 'solution-integration-rw.webp',
		'professional-audio'      => 'projects-hero-rw.webp',
		'lighting-solutions'      => 'projects-hero-rw.webp',
	);
	if ( $slug && isset( $map[ $slug ] ) ) {
		return $base . $map[ $slug ];
	}
	if ( is_front_page() && function_exists( 'sc_setting' ) ) {
		$poster = sc_setting( 'home_hero_poster', '' );
		if ( $poster ) {
			return $poster;
		}
	}
	return $base . 'projects-hero-rw.webp';
}

foreach ( array( 'facebook', 'twitter' ) as $scrw_network ) {
	add_action(
		'rank_math/opengraph/' . $scrw_network . '/add_additional_images',
		function ( $image ) {
			if ( ! is_object( $image ) || ! method_exists( $image, 'add_image' ) ) {
				return;
			}
			if ( method_exists( $image, 'has_images' ) && $image->has_images() ) {
				return;
			}
			$url = scrw_og_fallback_image();
			if ( $url ) {
				$image->add_image( $url );
			}
		}
	);
}
unset( $scrw_network );
