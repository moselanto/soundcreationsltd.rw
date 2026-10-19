<?php
/**
 * One-time content corrections (2.3.12), from the full-site crawl of 19 Oct 2026.
 *
 * Runs once per SCRW_VERSION on the first wp-admin load by an administrator.
 * It only replaces the exact old text listed below, so anything the team has
 * since rewritten is left alone (same rule as the seeders, see AGENTS.md).
 *
 * @package SoundCreationsRwanda
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Exact old => new, applied to content, excerpt and the spec/scope meta of published items. */
function scrw_content_fix_pairs() {
	return array(
		// UK English in product data.
		'Analog inputs'               => 'Analogue inputs',
		'Analog outputs'              => 'Analogue outputs',
		'5-input analog mixer'        => '5-input analogue mixer',
		'TFT Color '                  => 'TFT Colour ',
		'350 W program into'          => '350 W programme into',
		'zippered pouch'              => 'zipped pouch',
		'zippered carrying case'      => 'zipped carrying case',
		'Zippered carrying case'      => 'Zipped carrying case',
		// Product facts.
		'the white version is cosmetically identical to the standard black model' => 'the white version performs identically to the standard black model',
		'A BLX1 bodypack transmitter clamps the mic to the instrument bell and sends to a BLX4 single-channel receiver' => 'The microphone clamps to the instrument bell and plugs into a BLX1 bodypack transmitter, which sends to a BLX4 single-channel receiver',
		'308 x 493 x 389 mm'          => '308 x 493 x 289 mm',
		// Company facts and grammar.
		'With over 13 years of experience in the local and regional market' => 'With more than 22 years of experience in the local and regional market',
		'serving East Africa since 1989' => 'serving the region for more than 22 years',
		'exams announcements'         => 'exam announcements',
		'the manufacturer published specification' => 'the manufacturer\'s published specification',
		'in accordance with the manufacturer and our instructions' => 'in accordance with the manufacturer\'s and our instructions',
		'at our or the manufacturer discretion' => 'at our or the manufacturer\'s discretion',
		// Rwanda contact details on warranty and after-sales copy (group-site text).
		'Sound Creations Ltd, Mpaka Plaza, Mpaka Road, Westlands, Nairobi, Kenya' => 'Sound Creations Ltd Rwanda, KN1 Rd, Muhima, Kigali, Rwanda',
		'info@soundcreationsltd.com or +254 715 754 758' => 'fred@soundcreationsltd.com or +250 783 141 050',
		'info@soundcreationsltd.com — +254 715 754 758' => 'fred@soundcreationsltd.com — +250 783 141 050',
		'+254 715 754 758'            => '+250 783 141 050',
	);
}

function scrw_run_content_fixes() {
	$pairs = scrw_content_fix_pairs();
	$keys  = array( '_sc_specs', '_sc_scope', '_sc_result', '_sc_challenge', '_sc_solution', '_sc_technology', '_sc_summary' );
	$ids   = get_posts(
		array(
			'post_type'      => array( 'page', 'post', 'sc_product', 'sc_service', 'sc_solution', 'sc_project', 'sc_brand' ),
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'no_found_rows'  => true,
		)
	);
	$changed = 0;
	foreach ( $ids as $id ) {
		$p   = get_post( $id );
		$upd = array( 'ID' => $id );
		foreach ( array( 'post_content', 'post_excerpt' ) as $f ) {
			$new = strtr( (string) $p->$f, $pairs );
			$new = preg_replace( '/Fingerboard: 22 fret(?!s)/', 'Fingerboard: 22 frets', $new );
			if ( $new !== (string) $p->$f ) {
				$upd[ $f ] = $new;
			}
		}
		if ( count( $upd ) > 1 ) {
			wp_update_post( wp_slash( $upd ) );
			$changed++;
		}
		foreach ( $keys as $k ) {
			$v = get_post_meta( $id, $k, true );
			if ( is_string( $v ) && '' !== $v ) {
				$nv = preg_replace( '/Fingerboard: 22 fret(?!s)/', 'Fingerboard: 22 frets', strtr( $v, $pairs ) );
				if ( $nv !== $v ) {
					update_post_meta( $id, $k, wp_slash( $nv ) );
					$changed++;
				}
			}
		}
		if ( 'sc_product' === $p->post_type && 'VC520 Pro' === trim( (string) get_post_meta( $id, '_sc_model', true ) ) ) {
			update_post_meta( $id, '_sc_model', 'VC520 Pro2' );
			$changed++;
		}
	}
	update_option( 'scrw_content_fixes_log', gmdate( 'c' ) . ' changed ' . $changed, false );
}

add_action(
	'admin_init',
	function () {
		if ( ! current_user_can( 'manage_options' ) || get_option( 'scrw_content_fixes_ver' ) === SCRW_VERSION ) {
			return;
		}
		update_option( 'scrw_content_fixes_ver', SCRW_VERSION, false );
		scrw_run_content_fixes();
	},
	30
);
