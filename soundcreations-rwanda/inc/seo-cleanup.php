<?php
/**
 * 2.3.24: search clean-up found in the 31 Oct 2026 full-site crawl.
 *
 * - /brands/fane/ and /brands/yamaha/ 301 to /fane/ and /yamaha/
 *   (inc/pages-seed.php) but were still listed in the Rank Math sitemap.
 *   Sitemaps must list final URLs only, so they are left out.
 * - The WordPress sample post "Hello world!" (/hello-world/) was published
 *   and indexable. It is moved to draft once, only while it still holds the
 *   untouched WordPress sample text (never touches an edited post).
 *
 * @package soundcreations-rwanda
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** URLs that redirect and so must not appear in the sitemap. */
function scrw_sitemap_excluded_paths() {
	return (array) apply_filters( 'scrw_sitemap_excluded_paths', array( '/brands/fane/', '/brands/yamaha/' ) );
}

add_filter(
	'rank_math/sitemap/entry',
	function ( $url ) {
		if ( ! is_array( $url ) || empty( $url['loc'] ) ) {
			return $url;
		}
		$path = wp_parse_url( $url['loc'], PHP_URL_PATH );
		$path = $path ? trailingslashit( $path ) : '';
		if ( in_array( $path, scrw_sitemap_excluded_paths(), true ) ) {
			return false;
		}
		return $url;
	},
	10,
	1
);

/* One-time: unpublish the untouched WordPress sample post. */
add_action(
	'admin_init',
	function () {
		if ( ! current_user_can( 'manage_options' ) || get_option( 'scrw_cleanup_hello_world' ) ) {
			return;
		}
		update_option( 'scrw_cleanup_hello_world', SCRW_VERSION, false );
		$post = get_page_by_path( 'hello-world', OBJECT, 'post' );
		if ( ! $post || 'publish' !== $post->post_status ) {
			return;
		}
		if ( false === strpos( (string) $post->post_content, 'Welcome to WordPress' ) ) {
			return; // Edited by a person: leave it alone.
		}
		wp_update_post(
			array(
				'ID'          => $post->ID,
				'post_status' => 'draft',
			)
		);
	}
);

/*
 * 2.3.25: Rank Math stores built sitemaps in its own cache, so the exclusions
 * above did not show after the 2.3.24 upload (verified 31 Oct 2026: both URLs
 * still listed). Clear that cache once per theme version on admin load.
 */
add_action(
	'admin_init',
	function () {
		if ( ! current_user_can( 'manage_options' ) || SCRW_VERSION === get_option( 'scrw_sitemap_cache_cleared' ) ) {
			return;
		}
		update_option( 'scrw_sitemap_cache_cleared', SCRW_VERSION, false );
		if ( class_exists( '\RankMath\Sitemap\Cache' ) && method_exists( '\RankMath\Sitemap\Cache', 'invalidate_storage' ) ) {
			\RankMath\Sitemap\Cache::invalidate_storage();
		}
	}
);

/*
 * 2.3.26: Search Console page-indexing fixes (report read 31 Oct 2026).
 *
 * 1. "Not found (404)" listed literal URLs such as /wp-content/*,
 *    /wp-content/plugins/* and /*. They are not pages: they are the URL
 *    patterns in the speculative-loading script, which Google reads as
 *    links. 2.3.27: the script is printed by the PARENT theme
 *    (soundcreations/inc/enqueue.php), not WordPress core, so the core filter
 *    alone did nothing; the parent off switch sc_speculation_rules is used too. Turning that script off removes the false 404s (the
 *    speed gain from it is small on this site, already 99-100 Lighthouse).
 *    Off switch: filter scrw_keep_speculation_rules -> true.
 * 2. "Indexed, though blocked by robots.txt": the company profile PDF. The
 *    core hygiene rule blocks PDFs in uploads; this one is a public company
 *    document worth having in search, so it is explicitly allowed.
 */
add_filter(
	'sc_speculation_rules',
	function () {
		return (bool) apply_filters( 'scrw_keep_speculation_rules', false );
	}
);
add_filter(
	'wp_speculation_rules_configuration',
	function ( $config ) {
		return apply_filters( 'scrw_keep_speculation_rules', false ) ? $config : null;
	},
	20
);

/** Public PDFs in uploads that search engines may crawl. */
function scrw_public_pdfs() {
	return (array) apply_filters( 'scrw_public_pdfs', array( '/wp-content/uploads/2026/10/SCL-RW-COMPANY-PROFILE-2025.pdf' ) );
}

add_filter(
	'robots_txt',
	function ( $output ) {
		$lines = '';
		foreach ( scrw_public_pdfs() as $path ) {
			$lines .= 'Allow: ' . esc_url_raw( $path ) . "\n";
		}
		if ( '' === $lines ) {
			return $output;
		}
		return $output . "\n# Public documents (Sound Creations Rwanda)\nUser-agent: *\n" . $lines;
	},
	99
);

/*
 * 2.3.27 (Master Audit 5.3 C): Brand nodes link to the manufacturer's
 * official website so search and AI systems can match the brand entity.
 * Only fills sameAs when the brand has no "_sc_brand_url" set in wp-admin.
 * Official manufacturer sites only; no claim about our relationship.
 */
function scrw_brand_official_sites() {
	return (array) apply_filters(
		'scrw_brand_official_sites',
		array(
			'yamaha'            => 'https://www.yamaha.com/',
			'fane'              => 'https://www.fane-international.com/',
			'shure'             => 'https://www.shure.com/',
			'db-technologies'   => 'https://www.dbtechnologies.com/',
			'bose-professional' => 'https://www.boseprofessional.com/',
			'allen-heath'       => 'https://www.allen-heath.com/',
			'midas'             => 'https://www.midasconsoles.com/',
			'behringer'         => 'https://www.behringer.com/',
			'rockfon'           => 'https://www.rockfon.com/',
			'barrisol'          => 'https://www.barrisol.com/',
			'sommer-cable'      => 'https://www.sommercable.com/',
		)
	);
}

add_filter(
	'sc_seo_graph',
	function ( $graph ) {
		if ( ! is_singular( 'sc_brand' ) || ! is_array( $graph ) ) {
			return $graph;
		}
		$slug  = get_post_field( 'post_name', get_queried_object_id() );
		$sites = scrw_brand_official_sites();
		if ( empty( $sites[ $slug ] ) ) {
			return $graph;
		}
		foreach ( $graph as $i => $node ) {
			if ( is_array( $node ) && isset( $node['@type'] ) && 'Brand' === $node['@type'] && empty( $node['sameAs'] ) ) {
				$graph[ $i ]['sameAs'] = esc_url_raw( $sites[ $slug ] );
			}
		}
		return $graph;
	},
	40
);
