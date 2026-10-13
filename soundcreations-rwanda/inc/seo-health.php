<?php
/**
 * SEO health fixes (v1.6.0), from the 13 Oct 2026 live check.
 *
 *  1. XML sitemap. robots.txt points to /sitemap_index.xml and both
 *     /wp-sitemap.xml and /sitemap.xml 301 there (Rank Math's sitemap module
 *     is on), but /sitemap_index.xml itself returned a WordPress 404. That is
 *     the classic "Rank Math sitemap rewrite rules missing" state, so Google
 *     had no working sitemap. Fix: flush rewrite rules once per release on the
 *     next wp-admin load, and self-heal (at most once every 12 hours) if a
 *     sitemap URL ever 404s again on the front end.
 *  2. Group-wide wording. Core's fallback meta descriptions for products,
 *     projects, services and categories say "across Africa and the Middle
 *     East". The Rwanda site should describe what the Kigali company
 *     actually serves, so those phrases are rewritten for this install only.
 *     Descriptions typed into Rank Math are never touched.
 *
 * @package SoundCreationsRwanda
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SCRW_SEO_HEALTH_VERSION', 'rw-seo-health-1' );

/* ---- 1a. One flush per release, after every plugin has registered its rules ---- */
add_action(
	'admin_init',
	function () {
		if ( get_option( 'scrw_seo_health_ver' ) === SCRW_SEO_HEALTH_VERSION ) {
			return;
		}
		flush_rewrite_rules( false );
		update_option( 'scrw_seo_health_ver', SCRW_SEO_HEALTH_VERSION );
	},
	99
);

/* ---- 1b. Front-end self-heal when a sitemap URL 404s ---- */
add_action(
	'template_redirect',
	function () {
		if ( ! is_404() ) {
			return;
		}
		$path = isset( $_SERVER['REQUEST_URI'] ) ? (string) wp_parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ), PHP_URL_PATH ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- only matched against a fixed pattern.
		if ( ! preg_match( '#/(sitemap_index|[a-z0-9_-]+-sitemap[0-9]*)\.xml$#i', $path ) ) {
			return;
		}
		if ( get_transient( 'scrw_sitemap_heal' ) ) {
			return;
		}
		set_transient( 'scrw_sitemap_heal', 1, 12 * HOUR_IN_SECONDS );
		flush_rewrite_rules( false );
		// Retry the same URL once with the rebuilt rules; the transient stops loops.
		wp_safe_redirect( home_url( $path ), 302 );
		exit;
	},
	1
);

/* ---- 2. Rwanda wording in Core's fallback descriptions ---- */
function scrw_localise_group_wording( $text ) {
	if ( ! is_string( $text ) || false === stripos( $text, 'Middle East' ) ) {
		return $text;
	}
	$map = array(
		'across East Africa and the Middle East' => 'in Rwanda and East Africa',
		'across Africa and the Middle East'      => 'across Rwanda',
		'for Africa and the Middle East'         => 'for Rwanda',
		'Africa and the Middle East'             => 'Rwanda',
	);
	return str_ireplace( array_keys( $map ), array_values( $map ), $text );
}

add_filter(
	'rank_math/frontend/description',
	function ( $desc ) {
		if ( is_singular() && '' !== trim( (string) get_post_meta( get_queried_object_id(), 'rank_math_description', true ) ) ) {
			return $desc;
		}
		return scrw_localise_group_wording( $desc );
	},
	25
);
add_filter( 'rank_math/opengraph/facebook/og_description', 'scrw_localise_group_wording', 25 );
add_filter( 'rank_math/opengraph/twitter/description', 'scrw_localise_group_wording', 25 );
