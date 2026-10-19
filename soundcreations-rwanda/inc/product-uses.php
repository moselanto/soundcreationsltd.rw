<?php
/**
 * "Use" filter for the /products/ catalogue (2.3.5).
 *
 * Each product gets one or more uses (church, conference, studio, live,
 * install) worked out from its name, model, brand and category. Staff can
 * override this per product with a custom field named _sc_uses holding a
 * comma-separated list of the slugs below, e.g. "church,live".
 *
 * @package SoundCreationsRwanda
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Use slug => label, in the order shown in the filter. */
function scrw_product_use_labels() {
	return array(
		'church'     => __( 'Church & worship', 'soundcreations-rwanda' ),
		'conference' => __( 'Conference & meetings', 'soundcreations-rwanda' ),
		'live'       => __( 'Live events & DJ', 'soundcreations-rwanda' ),
		'studio'     => __( 'Studio & podcast', 'soundcreations-rwanda' ),
		'install'    => __( 'Installed & public address', 'soundcreations-rwanda' ),
	);
}

/**
 * Uses for one product.
 *
 * @param int    $pid   Product ID.
 * @param string $title Product title.
 * @param string $model Model.
 * @param string $brand Brand name.
 * @param string $cat   Category slug and name.
 * @return string[] Use slugs.
 */
function scrw_product_uses( $pid, $title, $model, $brand, $cat ) {
	$labels   = scrw_product_use_labels();
	$override = trim( (string) get_post_meta( $pid, '_sc_uses', true ) );
	if ( '' !== $override ) {
		$out = array();
		foreach ( array_map( 'trim', explode( ',', strtolower( $override ) ) ) as $u ) {
			if ( isset( $labels[ $u ] ) ) {
				$out[] = $u;
			}
		}
		if ( $out ) {
			return array_values( array_unique( $out ) );
		}
	}

	$h    = ' ' . strtolower( $title . ' ' . $model . ' ' . $brand . ' ' . $cat ) . ' ';
	$uses = array();
	$has  = function ( $words ) use ( $h ) {
		foreach ( (array) $words as $w ) {
			if ( false !== strpos( $h, $w ) ) {
				return true;
			}
		}
		return false;
	};

	if ( $has( array( 'aver', 'vc520', 'vc540', 'camera', 'conferenc' ) ) ) {
		$uses[] = 'conference';
	}
	if ( $has( array( 'dsppa', 'ceiling', 'wall mount', 'column', 'waterproof', 'paging' ) ) ) {
		$uses[] = 'install';
		$uses[] = 'conference';
	}
	if ( $has( array( 'cvl', 'presenter', 'lavalier', 'gooseneck', 'pga48' ) ) ) {
		$uses[] = 'conference';
		$uses[] = 'church';
	}
	if ( $has( array( 'podcast', 'sm7b', 'c-1u', 'hs7', 'hs8', 'studio monitor' ) ) ) {
		$uses[] = 'studio';
	}
	if ( $has( array( 'sm57', 'beta 52', 'beta 98', 'drumkit', 'drum kit', 'p98h' ) ) ) {
		$uses[] = 'live';
		$uses[] = 'studio';
		$uses[] = 'church';
	}
	if ( $has( array( 'sm58', 'pga58', 'blx288' ) ) ) {
		$uses[] = 'church';
		$uses[] = 'live';
		$uses[] = 'conference';
	}
	if ( $has( array( 'mixing console', 'mixer', ' tf1', ' tf3', ' tf5', 'dm3', ' mg06', ' mg10', ' mg12', ' mg16', ' mg20', 'mgp24', 'mgp32' ) ) ) {
		$uses[] = 'church';
		$uses[] = 'live';
		if ( $has( array( 'mg06', 'mg10', 'mg12' ) ) ) {
			$uses[] = 'studio';
			$uses[] = 'conference';
		}
	}
	if ( $has( array( 'reloop', ' dj ', 'serato' ) ) ) {
		$uses[] = 'live';
	}
	if ( $has( array( 'loudspeaker', 'speaker', 'subwoofer', ' sub ', 'line array', 'monitor', 'fane', 'opera', 'b-hype', 'flexsys', 'vio ', 'dbr', 'cbr', 'sovereign', 'colossus', 'compression driver' ) ) && ! $has( array( 'studio monitor', 'dsppa' ) ) ) {
		$uses[] = 'live';
		$uses[] = 'church';
		$uses[] = 'install';
	}
	if ( $has( array( 'guitar', 'piano', 'keyboard', 'psr-', 'arius', 'clarinet', 'tambourine', 'drum set', 'instrument' ) ) && ! $has( array( 'p98h' ) ) ) {
		$uses[] = 'church';
		$uses[] = 'studio';
	}

	return array_values( array_unique( $uses ) );
}
