<?php
/**
 * Permanent Rank Math sitemap rewrite rules (v1.7.4).
 *
 * Live check 14 Oct 2026: /sitemap_index.xml and every child sitemap returned
 * a WordPress 404, while /?sitemap=1 returned the correct Rank Math XML. So the
 * sitemap module works, but its pretty-URL rewrite rules keep going missing.
 * Several one-time seed routines (and seo-health.php) flush rewrite rules on
 * admin_init, and a flush that runs where Rank Math has not registered its
 * sitemap rules rebuilds the rule list without them. Resaving permalinks in
 * wp-admin did not bring them back either.
 *
 * Fix: add the same rules through the rewrite_rules_array filter, which runs on
 * every rebuild in every context, so no later flush can drop them. The query
 * vars are registered here too. One flush on the next front-end request writes
 * the rules; the old 12-hour self-heal in seo-health.php stays as a backstop.
 *
 * @package SoundCreationsRwanda
 */

if ( defined( 'ABSPATH' ) === false ) {
	exit;
}

define( 'SCRW_SITEMAP_RULES_VERSION', 'rw-sitemap-rules-1' );

add_filter(
	'rewrite_rules_array',
	function ( $rules ) {
		$sitemap = array(
			'sitemap_index\.xml$'             => 'index.php?sitemap=1',
			'([^/]+?)-sitemap([0-9]+)?\.xml$' => 'index.php?sitemap=$matches[1]&sitemap_n=$matches[2]',
			'([a-z]+)?-?sitemap\.xsl$'        => 'index.php?xsl=$matches[1]',
		);
		return array_merge( $sitemap, (array) $rules );
	},
	99
);

add_filter(
	'query_vars',
	function ( $vars ) {
		foreach ( array( 'sitemap', 'sitemap_n', 'xsl' ) as $var ) {
			if ( in_array( $var, $vars, true ) === false ) {
				$vars[] = $var;
			}
		}
		return $vars;
	}
);

add_action(
	'wp_loaded',
	function () {
		if ( get_option( 'scrw_sitemap_rules_ver' ) === SCRW_SITEMAP_RULES_VERSION ) {
			return;
		}
		update_option( 'scrw_sitemap_rules_ver', SCRW_SITEMAP_RULES_VERSION );
		flush_rewrite_rules( false );
	},
	99
);
