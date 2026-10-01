<?php
/**
 * Title tag templates: the layer where target keywords actually land.
 *
 * WHAT WAS WRONG BEFORE
 * ---------------------
 * A crawl of all 58 real pages on 29 Sep 2026 found every one of them using
 * WordPress's default "Page Title - Site Title" pattern, which produced:
 *
 *   About - Sound creations ltd            (27 chars, no keyword, wrong casing)
 *   Support - Sound creations ltd          (29 chars)
 *   Products - Sound creations ltd         (on /brands/, so the title names
 *                                           the wrong thing entirely)
 *
 * Two separate problems. First, the brand half was rendering as "Sound
 * creations ltd" because that is the raw value of the WordPress Site Title
 * option; lowercase "creations" in a company name looks like a typo in a search
 * result and weakens brand recall. Second, roughly 30 characters of the ~60 that
 * Google displays were being spent on a bare noun like "Support" that nobody
 * searches for, while the terms people do search -- "acoustic treatment Nairobi",
 * "PA systems Kenya", "AV installation companies Kenya" -- appeared nowhere.
 *
 * THE APPROACH
 * ------------
 * Keyword first, qualifier second, brand last. Search engines weight the front
 * of the title and users scan it, so the distinguishing term leads. Every
 * template stays inside a ~60 character budget so nothing is truncated in the
 * result.
 *
 * Templates are defaults, not decrees: a value typed into the per-page SEO
 * Title field always wins, and every template passes through a filter. This
 * file exists so that a page nobody has hand-tuned still has a competent
 * title, not to prevent hand-tuning.
 *
 * HONESTY CONSTRAINT
 * ------------------
 * Templates never assert a commercial relationship. A brand page gets
 * "{Brand} in Kenya", never "{Brand} Authorised Distributor Kenya", because
 * authorisation is a contractual claim that varies per brand and is not
 * recorded anywhere in the CMS. Where such a claim is true, it belongs in the
 * per-page SEO Title field, entered deliberately by someone who knows it.
 *
 * @package SoundCreationsCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Roughly what Google renders before truncating. */
function sc_title_budget() {
	return 60;
}

/** The brand string, properly cased, for the tail of a title. */
function sc_title_brand() {
	$brand = trim( (string) sc_core_get( 'company_name' ) );
	if ( '' === $brand ) {
		$brand = trim( (string) get_bloginfo( 'name' ) );
	}
	// Repair the lowercase Site Title option without forcing the site owner to
	// change a WordPress setting that also appears in other places.
	if ( 0 === strcasecmp( $brand, 'sound creations ltd' ) ) {
		$brand = 'Sound Creations Ltd';
	}
	return $brand;
}

/**
 * Join a lead phrase to the brand, dropping the brand if it would overflow.
 *
 * A truncated brand ("... | Sound Creati") is worse than no brand, so when the
 * budget is tight the lead phrase wins and the brand is omitted entirely.
 */
function sc_title_compose( $lead, $brand = '' ) {
	$lead  = trim( preg_replace( '/\s+/', ' ', (string) $lead ) );
	$brand = '' === $brand ? sc_title_brand() : trim( $brand );
	if ( '' === $lead ) {
		return $brand;
	}
	// Already brand-bearing: do not say it twice.
	if ( '' !== $brand && false !== stripos( $lead, $brand ) ) {
		return $lead;
	}
	$joined = $lead . ' | ' . $brand;
	$len    = function_exists( 'mb_strlen' ) ? mb_strlen( $joined ) : strlen( $joined );
	if ( $len <= sc_title_budget() || '' === $brand ) {
		return $joined;
	}
	return $lead;
}

/** First brand term attached to a post, for product titles. */
function sc_title_brand_of( $post_id ) {
	$b = get_post_meta( $post_id, '_sc_brand_name', true );
	if ( $b ) {
		return trim( $b );
	}
	$terms = get_the_terms( $post_id, 'sc_brand_tax' );
	if ( $terms && ! is_wp_error( $terms ) ) {
		$first = reset( $terms );
		return $first->name;
	}
	return '';
}

/* ============================================================
   ARCHIVE TITLES

   These are the highest-value titles on the site: an archive is
   what ranks for a category query ("AV companies in Kenya",
   "professional audio equipment Kenya"), and each was previously
   a single generic noun.

   /brands/ is the worst offender -- it was titled "Products",
   naming something it does not list.
   ============================================================ */
function sc_title_archive_map() {
	return apply_filters(
		'sc_seo_archive_titles',
		array(
			'sc_solution'   => 'Audio Visual & Acoustic Solutions Kenya',
			'sc_product'    => 'Professional Audio Equipment Kenya',
			'sc_project'    => 'Sound & AV Installation Projects Kenya',
			'sc_brand'      => 'Professional Audio & Acoustic Brands Kenya',
			'sc_resource'   => 'Professional Audio & AV Videos',
			'sc_case_study' => 'Audio & AV Case Studies Kenya',
		)
	);
}

/* ============================================================
   SINGULAR TITLES
   ============================================================ */
function sc_title_for_singular( $post_id, $raw_title ) {
	$pt    = get_post_type( $post_id );
	$title = trim( (string) $raw_title );

	switch ( $pt ) {

		case 'sc_product':
			// "FANE Colossus 18XB - FANE Kenya". Brand plus model plus country
			// is the exact shape of the product queries in the research
			// ("Db technologies opera 12 speakers in Kenya").
			$brand = sc_title_brand_of( $post_id );
			if ( $brand && false === stripos( $title, $brand ) ) {
				return sc_title_compose( $title . ' - ' . $brand . ' Kenya' );
			}
			return sc_title_compose( $title . ' Kenya' );

		case 'sc_brand':
			// Deliberately NOT "Authorised Distributor" -- see file header.
			return sc_title_compose( $title . ' Kenya - Supply & Installation' );

		case 'sc_solution':
		case 'sc_service':
			// Solutions carry the service-plus-location intent that most of the
			// research list is made of.
			if ( preg_match( '/kenya|nairobi/i', $title ) ) {
				return sc_title_compose( $title );
			}
			return sc_title_compose( $title . ' in Kenya' );

		case 'sc_project':
		case 'sc_case_study':
			return sc_title_compose( $title . ' - Sound & AV Installation' );

		case 'sc_resource':
			return sc_title_compose( $title );
	}

	return sc_title_compose( $title );
}

/* ============================================================
   THE FILTER

   Priority 20 so it runs after the per-post override in seo.php
   (which uses the default priority) and after the products
   archive title helper in post-types.php. A hand-entered SEO
   Title must always beat a template, so that case returns early.
   ============================================================ */
add_filter(
	'document_title_parts',
	function ( $parts ) {
		if ( is_admin() || is_feed() ) {
			return $parts;
		}

		// Correct the brand half everywhere, whatever else happens below.
		if ( isset( $parts['site'] ) ) {
			$parts['site'] = sc_title_brand();
		}

		// A deliberate per-page title wins outright.
		if ( is_singular() ) {
			$pid = get_queried_object_id();
			if ( $pid && get_post_meta( $pid, '_sc_seo_title', true ) ) {
				return $parts;
			}
		}

		if ( is_front_page() ) {
			// The home page should read as the company plus what it does.
			$tagline = trim( (string) sc_core_get( 'tagline', get_bloginfo( 'description' ) ) );
			$parts['title'] = $tagline
				? sc_title_compose( $tagline )
				: sc_title_compose( 'AV, Audio & Acoustic Solutions Kenya' );
			unset( $parts['site'], $parts['tagline'] );
			return $parts;
		}

		if ( is_post_type_archive() ) {
			$pt = get_query_var( 'post_type' );
			if ( is_array( $pt ) ) {
				$pt = reset( $pt );
			}
			$map = sc_title_archive_map();
			if ( isset( $map[ $pt ] ) ) {
				$parts['title'] = sc_title_compose( $map[ $pt ] );
				unset( $parts['site'] );
			}
			return $parts;
		}

		if ( is_singular() ) {
			$pid = get_queried_object_id();
			if ( ! $pid ) {
				return $parts;
			}
			$built = sc_title_for_singular( $pid, isset( $parts['title'] ) ? $parts['title'] : get_the_title( $pid ) );

			/**
			 * Filter a generated singular title.
			 *
			 * @param string $built   Generated title.
			 * @param int    $post_id Post being titled.
			 */
			$built = apply_filters( 'sc_seo_singular_title', $built, $pid );

			$parts['title'] = $built;
			// The brand is already inside the composed string when it fits, so
			// leaving $parts['site'] would repeat it.
			unset( $parts['site'] );
			return $parts;
		}

		return $parts;
	},
	20
);
