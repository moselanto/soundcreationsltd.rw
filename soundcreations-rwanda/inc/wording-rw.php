<?php
/**
 * Rwanda-first visible wording (v2.1.0).
 *
 * Live check 18 Oct 2026: the homepage "What we do" lead still said "we
 * deliver ... across Rwanda, Africa and the Middle East". That came from the
 * 1.x settings seed. seo-health.php only localises meta descriptions, not
 * visible copy, so the Rwanda homepage was telling buyers (and answer
 * engines) that the Kigali company serves the Middle East, which contradicts
 * the schema areaServed (Rwanda, DRC, East Africa).
 *
 * Fix: replace the seeded sentence only when it is still the untouched seed
 * value. Anything an editor has typed is never changed.
 *
 * @package SoundCreationsRwanda
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Seeded strings that should read Rwanda-first. old => new. */
function scrw_wording_map() {
	return array(
		'home_whatwedo_lead' => array(
			'If it sounds good, it’s Sound Creations. From acoustic design and system engineering to equipment, integration, commissioning and support, we deliver world-class technology and expertise across Rwanda, Africa and the Middle East.',
			'If it sounds good, it’s Sound Creations. From our Kigali office on KN1 Rd, Muhima, we design, supply, install and support professional audio, acoustic, lighting and AV systems for venues across Rwanda, with projects in the DRC and East Africa.',
		),
	);
}

add_filter(
	'option_soundcreations_settings',
	function ( $opts ) {
		if ( ! is_array( $opts ) || is_admin() ) {
			return $opts;
		}
		foreach ( scrw_wording_map() as $key => $pair ) {
			if ( isset( $opts[ $key ] ) && trim( (string) $opts[ $key ] ) === $pair[0] ) {
				$opts[ $key ] = $pair[1];
			}
		}
		return $opts;
	}
);
