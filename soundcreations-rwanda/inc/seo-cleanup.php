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
