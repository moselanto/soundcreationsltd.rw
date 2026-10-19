<?php
/**
 * Project proof on the Church and Conference pages (2.3.20).
 *
 * Prints a "Project in detail" block under the page content, read live from
 * the project's own fields (challenge, solution, equipment, result). Edit the
 * project under Projects in wp-admin and these pages follow automatically.
 * The church page also lists other churches from the Complete Projects list.
 *
 * Off switch: add_filter( 'scrw_sector_proof', '__return_false' );
 *
 * @package SoundCreationsRwanda
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Page slug => project slug and heading. */
function scrw_sector_proof_map() {
	return array(
		'church-sound-systems'          => array( 'christian-life-assembly-church', 'Church sound system installation in Rwanda: Christian Life Assembly, Kigali' ),
		'conference-room-sound-systems' => array( 'minecofin-conference-system', 'Conference room AV in Kigali: Ministry of Finance (MINECOFIN)' ),
	);
}

function scrw_sector_proof_field( $id, $key ) {
	return trim( (string) get_post_meta( $id, '_sc_' . $key, true ) );
}

function scrw_render_sector_proof( $page_slug ) {
	$map = scrw_sector_proof_map();
	if ( ! isset( $map[ $page_slug ] ) ) {
		return '';
	}
	$proj = get_page_by_path( $map[ $page_slug ][0], OBJECT, 'sc_project' );
	if ( ! $proj || 'publish' !== $proj->post_status ) {
		return '';
	}
	$id    = $proj->ID;
	$rows  = array(
		'The brief'   => scrw_sector_proof_field( $id, 'challenge' ),
		'What we did' => scrw_sector_proof_field( $id, 'solution' ),
		'The result'  => scrw_sector_proof_field( $id, 'result' ),
	);
	$tech  = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', scrw_sector_proof_field( $id, 'technology' ) ) ) );
	$loc   = scrw_sector_proof_field( $id, 'location' );
	$img   = get_the_post_thumbnail_url( $id, 'large' );
	$html  = '<section class="sc-section scrw-proof"><div class="sc-container">';
	$html .= '<h2 class="scrw-proof__title">' . esc_html( $map[ $page_slug ][1] ) . '</h2>';
	if ( $loc ) {
		$html .= '<p class="scrw-proof__loc">' . esc_html( $loc ) . '</p>';
	}
	$html .= '<div class="scrw-proof__grid"><div class="scrw-proof__text">';
	foreach ( $rows as $label => $text ) {
		if ( '' !== $text ) {
			$html .= '<h3>' . esc_html( $label ) . '</h3><p>' . esc_html( $text ) . '</p>';
		}
	}
	$html .= '</div>';
	if ( $tech || $img ) {
		$html .= '<div class="scrw-proof__side">';
		if ( $img ) {
			$html .= '<img src="' . esc_url( $img ) . '" alt="' . esc_attr( get_the_title( $id ) ) . '" loading="lazy" decoding="async" width="640" height="400">';
		}
		if ( $tech ) {
			$html .= '<h3>Equipment installed</h3><ul>';
			foreach ( $tech as $t ) {
				$html .= '<li>' . esc_html( $t ) . '</li>';
			}
			$html .= '</ul>';
		}
		$html .= '</div>';
	}
	$html .= '</div><p><a class="sc-btn sc-btn--primary" href="' . esc_url( get_permalink( $id ) ) . '">' . esc_html( 'See the full ' . get_the_title( $id ) . ' project' ) . '</a></p>';

	if ( 'church-sound-systems' === $page_slug && function_exists( 'scrw_complete_projects' ) ) {
		$all = scrw_complete_projects();
		if ( ! empty( $all['worship'][2] ) ) {
			$html .= '<h3>Other churches we have equipped in Rwanda</h3><ul class="scrw-proof__chips">';
			foreach ( $all['worship'][2] as $name ) {
				if ( false === stripos( $name, 'Christian Life Assembly' ) ) {
					$html .= '<li>' . esc_html( $name ) . '</li>';
				}
			}
			$html .= '</ul>';
		}
	}
	$html .= '</div></section>';
	return $html;
}

add_filter(
	'the_content',
	function ( $content ) {
		if ( ! apply_filters( 'scrw_sector_proof', true ) || ! is_singular( 'sc_solution' ) || ! in_the_loop() || ! is_main_query() ) {
			return $content;
		}
		$slug = get_post_field( 'post_name', get_queried_object_id() );
		return $content . scrw_render_sector_proof( $slug );
	},
	30
);
