<?php
/**
 * Section titles, site-wide (owner request 2026-10-08).
 *
 * Every section head on the front end is written as a small coloured label
 * (p.sc-eyebrow) followed by a heading (h2). The owner wants the section name
 * to be the big title and the sentence under it small, as on the homepage.
 * This swaps the two at output time, so it covers theme templates, shortcodes
 * and Elementor widgets alike:
 *
 *   <p class="sc-eyebrow">Our Clients</p><h2>Trusted by ...</h2>
 *   becomes
 *   <h2 class="sc-sectitle">Our Clients</h2><p class="sc-secsub">Trusted by ...</p>
 *
 * Page heroes (label + h1) are left alone. Turn the whole thing off with
 *   add_filter( 'scrw_swap_section_titles', '__return_false' );
 *
 * @package SoundCreationsRwanda
 */

if ( defined( 'ABSPATH' ) === false ) {
	exit;
}

/** Swap every "label then h2" pair in a chunk of HTML. */
function scrw_swap_section_titles( $html ) {
	if ( ! is_string( $html ) || false === strpos( $html, 'sc-eyebrow' ) ) {
		return $html;
	}
	$pattern = '#<p\s+class="sc-eyebrow"\s*>(.*?)</p>(\s*)<h2((?:\s+[^>]*)?)>(.*?)</h2>#s';
	$out     = preg_replace_callback(
		$pattern,
		function ( $m ) {
			$label = trim( $m[1] );
			$title = trim( $m[4] );
			if ( '' === $label || false !== strpos( $label, '<h' ) ) {
				return $m[0];
			}
			// Keep the h2's id (anchor links) and any extra classes; drop inline styles.
			$attrs = (string) $m[3];
			$id    = preg_match( '#\sid="([^"]*)"#', $attrs, $im ) ? ' id="' . $im[1] . '"' : '';
			$extra = preg_match( '#\sclass="([^"]*)"#', $attrs, $cm ) ? ' ' . $cm[1] : '';
			return '<h2 class="sc-sectitle' . $extra . '"' . $id . '>' . $label . '</h2>' . $m[2]
				. '<p class="sc-secsub">' . $title . '</p>';
		},
		$html
	);
	return is_string( $out ) ? $out : $html;
}

add_action(
	'template_redirect',
	function () {
		if ( is_admin() || is_feed() || wp_doing_ajax() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
			return;
		}
		if ( is_customize_preview() || isset( $_GET['elementor-preview'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			return;
		}
		if ( ! apply_filters( 'scrw_swap_section_titles', true ) ) {
			return;
		}
		ob_start( 'scrw_swap_section_titles' );
	},
	0
);
