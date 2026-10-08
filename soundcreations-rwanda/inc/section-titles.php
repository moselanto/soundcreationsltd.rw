<?php
/**
 * Section and page titles, site-wide (owner request 2026-10-08).
 *
 * Every title on the front end is written as a small coloured label
 * (p.sc-eyebrow) followed by a heading (h1 or h2). The owner wants the label
 * to be the big heading and the heading text to become the small line under
 * it, on every page. This swaps the two at output time, so it covers theme
 * templates, shortcodes and Elementor widgets alike:
 *
 *   <p class="sc-eyebrow">Our Projects</p><h1 class="x">Real solutions.</h1>
 *   becomes
 *   <h1 class="x sc-sectitle">Our Projects</h1><p class="sc-secsub sc-secsub--h1">Real solutions.</p>
 *
 * Exception: on a single product, project, brand, resource, service or
 * solution page the h1 is the item's own name (e.g. "Yamaha TF5") and the
 * label is only its category, so that h1 is kept; its h2 sections still swap.
 * Filters:
 *   scrw_swap_section_titles  (bool)  false turns the whole feature off.
 *   scrw_swap_page_titles     (bool)  false keeps every h1 as it is.
 *
 * @package SoundCreationsRwanda
 */

if ( defined( 'ABSPATH' ) === false ) {
	exit;
}

/** Swap every "label then h1/h2" pair in a chunk of HTML. */
function scrw_swap_section_titles( $html ) {
	if ( ! is_string( $html ) || false === strpos( $html, 'sc-eyebrow' ) ) {
		return $html;
	}
	$pattern = '#<p\s+class="(?:[^"]*\s)?sc-eyebrow(?:\s[^"]*)?"[^>]*>(.*?)</p>(\s*)<(h[12])((?:\s+[^>]*)?)>(.*?)</\3>#s';
	$out     = preg_replace_callback(
		$pattern,
		function ( $m ) {
			$label = trim( $m[1] );
			$tag   = strtolower( $m[3] );
			$title = trim( $m[5] );
			if ( '' === $label || '' === $title || false !== strpos( $label, '<h' ) ) {
				return $m[0];
			}
			if ( 'h1' === $tag && empty( $GLOBALS['scrw_swap_h1'] ) ) {
				return $m[0];
			}
			// Keep the heading's id (anchor links) and its classes; drop inline styles.
			$attrs = (string) $m[4];
			$id    = preg_match( '#\sid="([^"]*)"#', $attrs, $im ) ? ' id="' . $im[1] . '"' : '';
			$cls   = preg_match( '#\sclass="([^"]*)"#', $attrs, $cm ) ? $cm[1] . ' ' : '';
			return '<' . $tag . ' class="' . $cls . 'sc-sectitle"' . $id . '>' . $label . '</' . $tag . '>' . $m[2]
				. '<p class="sc-secsub sc-secsub--' . $tag . '">' . $title . '</p>';
		},
		$html
	);
	if ( ! is_string( $out ) ) {
		return $html;
	}
	// A label that stands alone at the end of a section head (no heading after it,
	// e.g. "Our Solutions") is the section title itself, so make it the big h2.
	$lone = preg_replace(
		'#<p\s+class="(?:[^"]*\s)?sc-eyebrow(?:\s[^"]*)?"[^>]*>((?:(?!</p>).)*?)</p>(\s*</div>)#s',
		'<h2 class="sc-sectitle">$1</h2>$2',
		$out
	);
	return is_string( $lone ) ? $lone : $out;
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
		$item_page             = is_singular( array( 'sc_product', 'sc_project', 'sc_brand', 'sc_resource', 'sc_service', 'sc_solution', 'post' ) );
		$GLOBALS['scrw_swap_h1'] = ( ! $item_page ) && apply_filters( 'scrw_swap_page_titles', true );
		ob_start( 'scrw_swap_section_titles' );
	},
	0
);
