<?php
/**
 * Schema tidy-up (v2.3.0), from the live cross-check of 18 Oct 2026
 * (validator.schema.org: 0 errors / 0 warnings on 7 pages; Rich Results
 * Test: Breadcrumbs, Local business and Organisation all valid).
 *
 * Remaining entity-consistency issues it fixes:
 *  1. sameAs listed the same profiles twice in different spellings
 *     (web.facebook.com vs www.facebook.com, Instagram with and without a
 *     trailing slash). Normalised and de-duplicated.
 *  2. The Organization and the LocalBusiness listed different sameAs sets.
 *     Both now carry the same complete list, so search and answer engines
 *     see one consistent entity.
 *  3. The parent company node (Sound Creations Ltd, soundcreationsltd.com)
 *     had no @id. It now uses https://soundcreationsltd.com/#organization,
 *     the same @id the group site publishes (same core code), so the two
 *     websites' graphs join up as parent and branch.
 *
 * Deliberately NOT added: priceRange (owner decision: no prices are shown,
 * each shipment is priced individually) and postalCode (not used for the
 * KN1 Rd address). Google lists both as optional.
 *
 * Runs last on the existing sc_seo_graph filter; still one JSON-LD graph.
 *
 * @package SoundCreationsRwanda
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Normalise a profile URL so spelling variants compare equal. */
function scrw_tidy_profile_url( $url ) {
	$url = trim( (string) $url );
	if ( '' === $url ) {
		return '';
	}
	$url = preg_replace( '#^http://#i', 'https://', $url );
	$url = preg_replace( '#^https://(web\.|m\.)?facebook\.com#i', 'https://www.facebook.com', $url );
	$url = preg_replace( '#^https://instagram\.com#i', 'https://www.instagram.com', $url );
	$parts = wp_parse_url( $url );
	if ( empty( $parts['host'] ) ) {
		return $url;
	}
	$path = isset( $parts['path'] ) ? $parts['path'] : '/';
	// Profile roots get a trailing slash; file-like paths (.html) do not.
	if ( ! preg_match( '#\.[a-z0-9]{2,5}$#i', $path ) ) {
		$path = trailingslashit( $path );
	}
	$query = isset( $parts['query'] ) ? '?' . $parts['query'] : '';
	return 'https://' . strtolower( $parts['host'] ) . $path . $query;
}

/** Normalise and de-duplicate a sameAs list. */
function scrw_tidy_same_as( $list ) {
	$out = array();
	foreach ( (array) $list as $u ) {
		$n = scrw_tidy_profile_url( $u );
		if ( $n && ! in_array( $n, $out, true ) ) {
			$out[] = $n;
		}
	}
	return $out;
}

add_filter(
	'sc_seo_graph',
	function ( $graph ) {
		if ( ! is_array( $graph ) ) {
			return $graph;
		}
		$org_id = home_url( '/#organization' );
		$lb_id  = home_url( '/#localbusiness' );
		$union  = array();
		$idx    = array();

		foreach ( $graph as $i => $node ) {
			if ( ! is_array( $node ) || empty( $node['@id'] ) ) {
				continue;
			}
			if ( $node['@id'] === $org_id || $node['@id'] === $lb_id ) {
				$idx[] = $i;
				if ( ! empty( $node['sameAs'] ) ) {
					$union = array_merge( $union, (array) $node['sameAs'] );
				}
			}
		}
		$union = scrw_tidy_same_as( $union );

		foreach ( $idx as $i ) {
			if ( $union ) {
				$graph[ $i ]['sameAs'] = $union;
			}
			// 3. Parent company @id, matching the group site's own graph.
			if ( ! empty( $graph[ $i ]['parentOrganization'] ) && is_array( $graph[ $i ]['parentOrganization'] ) ) {
				$p = $graph[ $i ]['parentOrganization'];
				if ( empty( $p['@id'] ) && ! empty( $p['url'] ) && false !== stripos( (string) $p['url'], 'soundcreationsltd.com' ) ) {
					$graph[ $i ]['parentOrganization'] = array( '@id' => 'https://soundcreationsltd.com/#organization' ) + $p;
				}
			}
		}
		return $graph;
	},
	99
);
