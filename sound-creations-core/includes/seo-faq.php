<?php
/**
 * FAQ blocks: editor fields, visible front-end output, and FAQPage schema.
 *
 * WHY THE VISIBLE RENDER IS NOT OPTIONAL
 * --------------------------------------
 * Google's structured-data policy requires that every question and answer in
 * FAQPage markup be visible to the user on that same page. Schema-only FAQs are
 * a spam signal and a manual-action risk, so this file deliberately couples the
 * two: the schema is generated from the SAME stored pairs that are printed into
 * the page body, and it is emitted only when those pairs exist. There is no
 * code path that can produce invisible FAQ markup.
 *
 * WHY FAQs MATTER HERE SPECIFICALLY
 * ---------------------------------
 * The keyword research is dominated by considered, technical, long-tail queries
 * -- coverage areas, lead times, whether a room needs treatment or a bigger
 * system, whether equipment is stocked or indented. Those are questions, and a
 * question-shaped page is what surfaces for them. FAQ blocks are also the
 * cheapest honest way to lift the many pages currently sitting at 120-230
 * words, because they add genuinely useful text rather than padding.
 *
 * @package SoundCreationsCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** How many Q/A rows the editor offers per page. */
function sc_faq_slots() {
	return 6;
}

/** Post types that can carry an FAQ block. */
function sc_faq_post_types() {
	return array( 'page', 'sc_solution', 'sc_service', 'sc_product', 'sc_brand', 'sc_project', 'sc_resource' );
}

/**
 * Stored FAQ pairs for a post, cleaned and validated.
 *
 * A row survives only when BOTH question and answer are present. A question
 * with no answer would render an empty accordion and produce invalid schema.
 */
function sc_faq_get( $post_id = 0 ) {
	$post_id = $post_id ? (int) $post_id : get_queried_object_id();
	if ( ! $post_id ) {
		return array();
	}
	$raw = get_post_meta( $post_id, '_sc_faq', true );
	if ( ! is_array( $raw ) ) {
		return array();
	}
	$out = array();
	foreach ( $raw as $row ) {
		if ( ! is_array( $row ) ) {
			continue;
		}
		$q = isset( $row['q'] ) ? trim( wp_strip_all_tags( $row['q'] ) ) : '';
		$a = isset( $row['a'] ) ? trim( $row['a'] ) : '';
		if ( '' === $q || '' === wp_strip_all_tags( $a ) ) {
			continue;
		}
		$out[] = array(
			'q' => $q,
			'a' => $a,
		);
	}
	return $out;
}

/* ============================================================
   EDITOR
   ============================================================ */
add_action(
	'add_meta_boxes',
	function () {
		foreach ( sc_faq_post_types() as $pt ) {
			add_meta_box(
				'sc_faq',
				'FAQ (shown on the page and sent to Google)',
				'sc_faq_meta_box',
				$pt,
				'normal',
				'default'
			);
		}
	}
);

function sc_faq_meta_box( $post ) {
	wp_nonce_field( 'sc_faq_save', 'sc_faq_nonce' );
	$rows  = get_post_meta( $post->ID, '_sc_faq', true );
	$rows  = is_array( $rows ) ? $rows : array();
	$slots = sc_faq_slots();

	echo '<p style="margin:0 0 12px;color:#555;">Questions appear on the page in an FAQ block and are also sent to Google as FAQPage structured data. '
		. 'Leave a pair blank to skip it. Write real answers in plain language - Google requires the answer to be visible on the page, '
		. 'and short, honest, specific answers are what actually win the result.</p>';

	for ( $i = 0; $i < $slots; $i++ ) {
		$q = isset( $rows[ $i ]['q'] ) ? $rows[ $i ]['q'] : '';
		$a = isset( $rows[ $i ]['a'] ) ? $rows[ $i ]['a'] : '';
		echo '<p style="margin:0 0 4px;"><strong>Question ' . (int) ( $i + 1 ) . '</strong></p>';
		echo '<input type="text" style="width:100%;margin-bottom:6px;" name="sc_faq[' . (int) $i . '][q]" value="'
			. esc_attr( $q ) . '" placeholder="e.g. Do you cover projects outside Nairobi?">';
		echo '<textarea style="width:100%;margin-bottom:16px;" rows="3" name="sc_faq[' . (int) $i . '][a]" '
			. 'placeholder="Answer in 1-3 sentences.">' . esc_textarea( $a ) . '</textarea>';
	}
}

add_action(
	'save_post',
	function ( $post_id ) {
		if ( ! isset( $_POST['sc_faq_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sc_faq_nonce'] ) ), 'sc_faq_save' ) ) {
			return;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$in  = isset( $_POST['sc_faq'] ) ? wp_unslash( $_POST['sc_faq'] ) : array();
		$out = array();
		if ( is_array( $in ) ) {
			foreach ( $in as $row ) {
				if ( ! is_array( $row ) ) {
					continue;
				}
				$q = isset( $row['q'] ) ? sanitize_text_field( $row['q'] ) : '';
				$a = isset( $row['a'] ) ? wp_kses_post( $row['a'] ) : '';
				if ( '' === trim( $q ) || '' === trim( wp_strip_all_tags( $a ) ) ) {
					continue;
				}
				$out[] = array(
					'q' => $q,
					'a' => $a,
				);
			}
		}

		if ( $out ) {
			update_post_meta( $post_id, '_sc_faq', $out );
		} else {
			delete_post_meta( $post_id, '_sc_faq' );
		}
	}
);

/* ============================================================
   FRONT-END RENDER

   Uses <details>/<summary> so the block is collapsible with no
   JavaScript at all, and so the answer text is present in the
   DOM on first paint. A JS-built accordion would risk the
   answers being absent when the page is crawled.
   ============================================================ */
function sc_faq_render( $post_id = 0, $heading = 'Frequently asked questions' ) {
	$faqs = sc_faq_get( $post_id );
	if ( ! $faqs ) {
		return '';
	}

	$html  = '<section class="sc-faq" aria-labelledby="sc-faq-title">';
	$html .= '<h2 class="sc-faq__title" id="sc-faq-title">' . esc_html( $heading ) . '</h2>';
	$html .= '<div class="sc-faq__list">';
	foreach ( $faqs as $i => $f ) {
		$html .= '<details class="sc-faq__item"' . ( 0 === $i ? ' open' : '' ) . '>';
		$html .= '<summary class="sc-faq__q">' . esc_html( $f['q'] ) . '</summary>';
		$html .= '<div class="sc-faq__a">' . wp_kses_post( wpautop( $f['a'] ) ) . '</div>';
		$html .= '</details>';
	}
	$html .= '</div></section>';

	return $html;
}

/**
 * Append the FAQ block to singular content automatically.
 *
 * Guarded against the many contexts where the_content runs but is not the
 * main article body -- feeds, excerpts, admin, secondary loops -- because
 * duplicating the block would duplicate the questions in the DOM.
 */
add_filter(
	'the_content',
	function ( $content ) {
		if ( is_admin() || is_feed() || ! is_singular() || ! in_the_loop() || ! is_main_query() ) {
			return $content;
		}
		if ( false !== strpos( $content, 'sc-faq__list' ) ) {
			return $content;
		}
		$block = sc_faq_render();
		if ( ! $block ) {
			return $content;
		}
		return $content . $block;
	},
	20
);

/* ============================================================
   SCHEMA

   Returns a node for the unified @graph rather than emitting its
   own <script>, so FAQs join the same connected graph as
   everything else and can point back at the WebPage they belong
   to via mainEntityOfPage.
   ============================================================ */
function sc_seo_node_faq() {
	if ( ! is_singular() ) {
		return null;
	}
	$faqs = sc_faq_get();
	if ( ! $faqs ) {
		return null;
	}

	$items = array();
	foreach ( $faqs as $f ) {
		$items[] = array(
			'@type'          => 'Question',
			'name'           => $f['q'],
			'acceptedAnswer' => array(
				'@type' => 'Answer',
				'text'  => trim( wp_strip_all_tags( $f['a'] ) ),
			),
		);
	}

	return array(
		'@type'            => 'FAQPage',
		'@id'              => sc_seo_page_id( 'faq' ),
		'mainEntity'       => $items,
		'mainEntityOfPage' => array( '@id' => sc_seo_page_id( 'webpage' ) ),
	);
}
