<?php
/**
 * Product photos that always show.
 *
 * The live catalogue had no featured images (the Media Library import had not
 * run), so every card showed a text placeholder. This resolves a photo for
 * each product without relying on the import: Featured Image first, then the
 * official photo bundled in assets/img/products-official/, then the catalogue
 * photo in assets/img/products/, then the Core plugin's _sc_image key (used by
 * the group FANE products). The same photo is used on the single product page.
 *
 * @package SoundCreationsRwanda
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Best available photo URL for a product, or '' if none. */
function scrw_product_image_url( $post_id, $size = 'large' ) {
	if ( has_post_thumbnail( $post_id ) && ! doing_filter( 'has_post_thumbnail' ) ) {
		$u = get_the_post_thumbnail_url( $post_id, $size );
		if ( $u ) {
			return $u;
		}
	}
	return scrw_product_bundled_image( $post_id );
}

/** Photo bundled with the themes for this product, or ''. */
function scrw_product_bundled_image( $post_id ) {
	$slug  = get_post_field( 'post_name', $post_id );
	$child = get_stylesheet_directory();
	foreach ( array( 'products-official', 'products' ) as $dir ) {
		$rel = '/assets/img/' . $dir . '/' . $slug . '.webp';
		if ( file_exists( $child . $rel ) ) {
			return get_stylesheet_directory_uri() . $rel;
		}
	}
	$key = ltrim( (string) get_post_meta( $post_id, '_sc_image', true ), '/' );
	if ( '' !== $key ) {
		if ( preg_match( '#^(https?:)?//#', $key ) ) {
			return $key;
		}
		$base = get_template_directory() . '/assets/img/';
		$cands = preg_match( '/\.(webp|jpe?g|png)$/i', $key ) ? array( $key ) : array( $key . '.webp', $key . '.jpg', $key . '.png' );
		foreach ( $cands as $c ) {
			if ( file_exists( $base . $c ) ) {
				return get_template_directory_uri() . '/assets/img/' . $c;
			}
		}
	}
	return '';
}

/* Single product page: treat the bundled photo as the featured image. */
add_filter(
	'has_post_thumbnail',
	function ( $has, $post ) {
		if ( $has ) {
			return $has;
		}
		$post = get_post( $post );
		return ( $post && 'sc_product' === $post->post_type && '' !== scrw_product_bundled_image( $post->ID ) ) ? true : $has;
	},
	10,
	2
);

add_filter(
	'post_thumbnail_html',
	function ( $html, $post_id ) {
		if ( '' !== trim( (string) $html ) || 'sc_product' !== get_post_type( $post_id ) ) {
			return $html;
		}
		$u = scrw_product_bundled_image( $post_id );
		if ( '' === $u ) {
			return $html;
		}
		return '<img class="scrw-product-photo" src="' . esc_url( $u ) . '" alt="' . esc_attr( get_the_title( $post_id ) ) . '" loading="lazy" decoding="async">';
	},
	10,
	2
);
