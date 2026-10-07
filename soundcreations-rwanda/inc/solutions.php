<?php
/**
 * Rwanda versions of the group-site solution pages (Professional Audio and
 * Installation): same layout as soundcreationsltd.com, with Rwanda photos,
 * wording and projects. Hooks into filters in the parent single-sc_solution.php.
 *
 * @package SoundCreationsRwanda
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function scrw_img( $file ) {
	return get_stylesheet_directory_uri() . '/assets/img/' . $file;
}

/* Hero photos. */
add_filter(
	'sc_solution_hero_img',
	function ( $url, $kind ) {
		if ( 'audio' === $kind ) {
			return scrw_img( 'projects/christian-life-assembly-service.webp' ); // Christian Life Assembly, Kigali.
		}
		if ( 'integration' === $kind ) {
			return scrw_img( 'service-integration-ntare-rw.webp' ); // Ntare Louisenlund School auditorium.
		}
		return $url;
	},
	10,
	2
);

/* Installation: intro and the two showcase photos. */
add_filter(
	'sc_solution_integration_intro',
	function () {
		return 'Sound Creations Ltd Rwanda designs, supplies, installs and supports complete sound and acoustic systems across Rwanda. From a single boardroom to a full auditorium, our Kigali team handles the entire project - site survey and system design, professional installation and cabling, calibration and commissioning, operator training and ongoing technical support - so your venue performs reliably from the first event.';
	}
);
add_filter(
	'sc_solution_integration_shots',
	function () {
		return array(
			array( scrw_img( 'projects/christian-life-assembly-stage.webp' ), 'Christian Life Assembly, Kigali - line-array sound and stage lighting by Sound Creations Rwanda', 'House of worship: Christian Life Assembly, Kigali' ),
			array( scrw_img( 'projects/intare-kivu-arena-hall.webp' ), 'Intare Kivu Arena, Rubavu - acoustic treatment and sound system by Sound Creations Rwanda', 'Auditorium: Intare Kivu Arena, Rubavu' ),
		);
	}
);

/* Professional Audio: industry cards with Rwanda project photos. */
add_filter(
	'sc_solution_industries',
	function () {
		return array(
			array( 'Clubs and Bars', 'An effortless guest experience', 'Warm, even coverage that keeps conversation easy and the energy right - consistent, refined audio for clubs, bars, lounges and event gardens, as at Romantic Garden and Atelier du Vin in Kigali.', scrw_img( 'projects/romantic-garden-exterior-hd.webp' ) ),
			array( 'Worship', 'Every word, every seat', 'Intelligible speech and full-range music for services of any style, engineered around your room - as at Christian Life Assembly, Kigali.', scrw_img( 'projects/christian-life-assembly-worship.webp' ) ),
			array( 'Live Performance & Events', 'Built for the moment', 'Line arrays, subwoofers, monitoring and digital mixing for conferences, ceremonies and concerts - as at Intare Kivu Arena, Rubavu.', scrw_img( 'projects/intare-kivu-arena-hall-2.webp' ) ),
			array( 'Corporate & Conferencing', 'Heard, clearly', 'Conference microphones and loudspeakers for boardrooms and hybrid meetings where every voice has to land - as at the Ministry of Finance (MINECOFIN).', scrw_img( 'projects/minecofin-hd.webp' ) ),
			array( 'Education', 'Clarity that carries', 'Reliable, easy-to-run sound for school halls, lecture rooms and auditoriums, from the front row to the back - as at Ntare Louisenlund School.', scrw_img( 'projects/ntare-louisenlund-hall-hd.webp' ) ),
		);
	}
);

/* Rwanda projects strip under each page. */
add_action(
	'sc_solution_after_kind',
	function ( $kind ) {
		$map = array(
			'audio'       => array( 'christian-life-assembly-church', 'intare-kivu-arena', 'minecofin-conference-system' ),
			'integration' => array( 'ntare-louisenlund-school', 'intare-kivu-arena', 'christian-life-assembly-church' ),
		);
		if ( ! isset( $map[ $kind ] ) || ! function_exists( 'sc_media_card' ) ) {
			return;
		}
		$q = new WP_Query(
			array(
				'post_type'      => 'sc_project',
				'post_status'    => 'publish',
				'post_name__in'  => $map[ $kind ],
				'orderby'        => 'post_name__in',
				'posts_per_page' => 3,
				'no_found_rows'  => true,
			)
		);
		if ( ! $q->have_posts() ) {
			return;
		}
		echo '<section class="sc-section sc-section--tight"><div class="sc-container"><div class="sc-section__head"><div><p class="sc-eyebrow">' . esc_html__( 'Our work in Rwanda', 'soundcreations-rwanda' ) . '</p><h2 class="sc-svc-h2" style="margin:0;">' . esc_html__( 'Recent projects', 'soundcreations-rwanda' ) . '</h2></div><a class="sc-link-arrow" href="' . esc_url( home_url( '/projects/' ) ) . '">' . esc_html__( 'View All Projects', 'soundcreations-rwanda' ) . ' &rarr;</a></div><div class="sc-grid">';
		while ( $q->have_posts() ) {
			$q->the_post();
			echo sc_media_card( get_the_ID(), sc_field( 'client' ), sc_field( 'year' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- theme helper returns escaped markup.
		}
		wp_reset_postdata();
		echo '</div></div></section>';
	}
);
