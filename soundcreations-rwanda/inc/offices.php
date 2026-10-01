<?php
/**
 * Contact and Request a Consultation pages: Kigali is the main office on
 * this site, so it is listed first (and selected on the map by default),
 * followed by the other group offices. Kigali uses the Rwanda details from
 * Sound Creations -> Settings; the other offices use the group head-office
 * details in Nairobi (on this site the Settings hold the Rwanda values, so
 * the Kenya ones are set here).
 *
 * @package SoundCreationsRwanda
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter(
	'sc_offices',
	function ( $offices ) {
		$first = array();
		$rest  = array();
		foreach ( (array) $offices as $o ) {
			if ( isset( $o['id'] ) && 'kigali' === $o['id'] ) {
				$o['name'] = 'Kigali, Rwanda (Main Office)';
				$o['addr'] = 'KN1 Rd, Muhima, Kigali (near BTN)';
				$first[]   = $o;
			} else {
				$rest[] = $o;
			}
		}
		return array_merge( $first, $rest );
	}
);

add_filter(
	'sc_office_contacts',
	function ( $map ) {
		$s      = function ( $k, $d = '' ) {
			return function_exists( 'sc_setting' ) ? (string) sc_setting( $k, $d ) : $d;
		};
		$phones = $s( 'phone', '+250 783 141 050' );
		if ( '' !== $s( 'phone2' ) ) {
			$phones .= ' | ' . $s( 'phone2' );
		}
		$rw = array(
			'phone'      => $phones,
			'phone_link' => $s( 'phone_link', '+250783141050' ),
			'email'      => $s( 'email', 'sales@soundcreationsltd.com' ),
			'hours'      => $s( 'hours_week', 'Mon-Fri: 9:00 AM - 6:00 PM' ) . ' | ' . $s( 'hours_sat', 'Sat: 9:00 AM - 1:30 PM' ),
		);
		$ke = array(
			'phone'      => '+254 715 754 758',
			'phone_link' => '+254715754758',
			'email'      => 'info@soundcreationsltd.com',
			'hours'      => 'Mon - Fri: 9:00 AM - 5:30 PM | Sat: 9:00 AM - 1:30 PM',
		);
		$out = array( 'kigali' => $rw );
		foreach ( (array) $map as $id => $c ) {
			if ( 'kigali' !== $id ) {
				$out[ $id ] = $ke;
			}
		}
		return $out;
	}
);
