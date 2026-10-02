<?php
/**
 * FANE products on the Rwanda catalogue, matched to fane-international.com
 * (official descriptions summarised, specifications from each FANE product
 * page; checked 2 Oct 2026). Updates the Core plugin's FANE stubs, replaces
 * the Imperium 18XL with the Colossus Prime 18XS, renames the Sovereign 15-600
 * to the current Sovereign Pro 15-600, and files all five under their own
 * "FANE Loudspeaker Components" category so they have a FANE filter on
 * /products/. Photos: assets/img/products-official/{slug}.webp.
 *
 * @package SoundCreationsRwanda
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SCRW_FANE_VERSION', 'rw-fane-1' );

/** slug, title, model, description, specs, FANE URL, order. */
function scrw_fane_products() {
	return array(
		array( 'fane-cd140', 'FANE CD-140', 'CD-140', 'A 1-inch exit ferrite compression driver with a titanium diaphragm and 1.75-inch copper clad aluminium voice coil. High BL and a lightweight diaphragm give very high output and clarity from a compact unit, with response from 2 kHz to 18 kHz. Industry-standard throat and fixings match commercial HF horns, for touring and fixed installation.', 'Speaker type: Compression driver' . "\n" . 'Impedance: 8 ohm' . "\n" . 'Power handling: 40 W (A.E.S.)' . "\n" . 'Usable frequency range (-6 dB): 2 kHz - 18 kHz' . "\n" . 'Sensitivity (1 W / 1 m): 105 dB' . "\n" . 'Throat size: 1 in / 25.4 mm' . "\n" . 'Voice coil diameter: 1.75 in / 44 mm' . "\n" . 'Diaphragm: Titanium, double sinusoidal roll surround' . "\n" . 'Recommended crossover: 3.5 kHz (18 dB/oct)' . "\n" . 'Flux density: 1.35 Tesla' . "\n" . 'Magnet: Ferrite' . "\n" . 'Overall diameter: 102 mm' . "\n" . 'Depth: 51 mm' . "\n" . 'Weight: 1.54 kg', 'https://www.fane-international.com/view-product/CD-140', 1 ),
		array( 'fane-sovereign-12-250tc', 'FANE Sovereign 12-250TC', 'Sovereign 12-250TC', 'A 12-inch full range driver whose triple cone design extends response up to 17 kHz, making it a strong choice for compact PA systems and houses of worship, with clear vocal presence. It handles 250 W (A.E.S.), 500 W programme, with 100 dB sensitivity and a 2-inch copper clad aluminium voice coil.', 'Speaker type: Full range driver' . "\n" . 'Nominal diameter: 12 in / 304.8 mm' . "\n" . 'Impedance: 8 ohm' . "\n" . 'Power handling: 250 W (A.E.S.)' . "\n" . 'Programme power: 500 W' . "\n" . 'Peak power: 1000 W' . "\n" . 'Usable frequency range (-6 dB): 45 Hz - 17 kHz' . "\n" . 'Sensitivity (1 W / 1 m): 100 dB' . "\n" . 'Voice coil diameter: 2 in / 50.8 mm' . "\n" . 'Magnet: Ferrite, 56 oz' . "\n" . 'Fs: 50 Hz' . "\n" . 'Qts: 0.64' . "\n" . 'Vas: 78.06 litres' . "\n" . 'Xmax: 3.5 mm' . "\n" . 'Chassis: Pressed steel', 'https://www.fane-international.com/view-product/SOVEREIGN-12-250TC', 2 ),
		array( 'fane-sovereign-15-600', 'FANE Sovereign Pro 15-600', 'Sovereign Pro 15-600', 'A 15-inch driver with linear response and well controlled bass down to about 40 Hz, suited to horn-loaded, band-pass and compact bass reflex enclosures. With high BL and a 3-inch copper voice coil it delivers maximum punch in two- and three-way systems, handling 600 W (A.E.S.), 1200 W programme.', 'Speaker type: Sub bass driver' . "\n" . 'Nominal diameter: 15 in / 381 mm' . "\n" . 'Impedance: 4 / 8 / 16 ohm' . "\n" . 'Power handling: 600 W (A.E.S.)' . "\n" . 'Programme power: 1200 W' . "\n" . 'Peak power: 2400 W' . "\n" . 'Usable frequency range (-6 dB): 38 Hz - 3.5 kHz' . "\n" . 'Sensitivity (1 W / 1 m): 98 dB' . "\n" . 'Voice coil diameter: 3 in / 76.2 mm' . "\n" . 'Magnet: Ferrite, 85 oz' . "\n" . 'Fs: 38 Hz' . "\n" . 'Qts: 0.351' . "\n" . 'Vas: 201 litres' . "\n" . 'Xmax: 6 mm' . "\n" . 'Chassis: Die-cast aluminium', 'https://www.fane-international.com/view-product/SOVEREIGN-PRO-15-600', 3 ),
		array( 'fane-colossus-18xb', 'FANE Colossus 18XB', 'Colossus 18XB', 'A high-output 18-inch sub bass driver with a 4-inch inside/outside-wound voice coil in a symmetric magnetic field and dual suspensions for linearity at high excursion. A vented die-cast chassis and rear heatsink keep power compression very low, so it handles 1000 W (A.E.S.) with peaks over 4000 W. Designed for 100 to 250 litre ported enclosures.', 'Speaker type: Sub bass driver' . "\n" . 'Nominal diameter: 18 in / 457.2 mm' . "\n" . 'Impedance: 4 / 8 / 16 ohm' . "\n" . 'Power handling: 1000 W (A.E.S.)' . "\n" . 'Programme power: 2000 W' . "\n" . 'Peak power: 4000 W' . "\n" . 'Usable frequency range (-6 dB): 35 Hz - 1 kHz' . "\n" . 'Sensitivity (1 W / 1 m): 99 dB' . "\n" . 'Voice coil diameter: 4 in / 101.6 mm, inside/outside windings' . "\n" . 'Magnet: Ferrite, 120 oz' . "\n" . 'Fs: 33 Hz' . "\n" . 'Qts: 0.337' . "\n" . 'Vas: 236 litres' . "\n" . 'Xmax: 7.5 mm' . "\n" . 'Chassis: Die-cast aluminium', 'https://www.fane-international.com/view-product/COLOSSUS-18XB', 4 ),
		array( 'fane-colossus-prime-18xs', 'FANE Colossus Prime 18XS', 'Colossus Prime 18XS', 'An 18-inch sub bass driver with a 4-inch inside/outside-wound voice coil, laminated silicone suspensions and a 12 mm Xmax (60 mm peak to peak) for fast, accurate bass at high excursion. Its polycellulose cone and vented die-cast chassis handle 1200 W (A.E.S.) with peaks over 4800 W, and it reaches 29 Hz (-6 dB) in a 200 litre ported enclosure.', 'Speaker type: Sub bass driver' . "\n" . 'Nominal diameter: 18 in / 457.2 mm' . "\n" . 'Impedance: 4 / 8 / 16 ohm' . "\n" . 'Power handling: 1200 W (A.E.S.)' . "\n" . 'Programme power: 2400 W' . "\n" . 'Peak power: 4800 W' . "\n" . 'Usable frequency range (-6 dB): 35 Hz - 500 Hz' . "\n" . 'Sensitivity (1 W / 1 m): 100 dB' . "\n" . 'Voice coil diameter: 4 in / 101.6 mm, inside/outside windings' . "\n" . 'Magnet: Ferrite Y35, 145 oz' . "\n" . 'Fs: 33 Hz' . "\n" . 'Qts: 0.385' . "\n" . 'Vas: 257 litres' . "\n" . 'Xmax: 12 mm' . "\n" . 'Chassis: Die-cast aluminium', 'https://www.fane-international.com/view-product/COLOSSUS-PRIME-18XS', 5 ),
	);
}

function scrw_sync_fane_products() {
	$cat = 'FANE Loudspeaker Components';
	foreach ( scrw_fane_products() as $p ) {
		list( $slug, $title, $model, $desc, $specs, $url, $order ) = $p;
		$content = '<p>' . esc_html( $desc ) . '</p>'
			. '<p>Available from Sound Creations Ltd Rwanda in Kigali as part of the FANE Africa dealer network, with technical support.</p>'
			. '<p><a href="' . esc_url( $url ) . '" target="_blank" rel="noopener">FANE product page and datasheet</a></p>';
		$post = get_page_by_path( $slug, OBJECT, 'sc_product' );
		$args = array(
			'post_title'   => $title,
			'post_excerpt' => $desc,
			'post_content' => $content,
			'post_status'  => 'publish',
			'menu_order'   => (int) $order,
		);
		if ( $post ) {
			$cur = (string) $post->post_content;
			// Only overwrite the Core stub or our own earlier copy, never a hand edit.
			if ( false === strpos( $cur, '[VERIFY]' ) && false === strpos( $cur, 'FANE Africa dealer network' ) && '' !== trim( $cur ) ) {
				continue;
			}
			$args['ID'] = $post->ID;
			$id = wp_update_post( $args );
		} else {
			$args['post_name'] = $slug;
			$args['post_type'] = 'sc_product';
			$id = wp_insert_post( $args );
		}
		if ( ! is_int( $id ) || $id < 1 ) {
			continue;
		}
		update_post_meta( $id, '_sc_brand_name', 'FANE' );
		update_post_meta( $id, '_sc_model', $model );
		update_post_meta( $id, '_sc_availability', 'Available to order in Kigali' );
		update_post_meta( $id, '_sc_specs', $specs );
		update_post_meta( $id, '_sc_datasheet', $url );
		if ( taxonomy_exists( 'sc_product_category' ) ) {
			wp_set_object_terms( $id, $cat, 'sc_product_category', false );
		}
		if ( taxonomy_exists( 'sc_brand_tax' ) ) {
			wp_set_object_terms( $id, 'FANE', 'sc_brand_tax', false );
		}
	}
	// Discontinued on fane-international.com: replaced by the Colossus Prime 18XS.
	$old = get_page_by_path( 'fane-imperium-18xl', OBJECT, 'sc_product' );
	if ( $old && 'publish' === $old->post_status ) {
		wp_update_post( array( 'ID' => $old->ID, 'post_status' => 'draft' ) );
	}
}

add_action(
	'admin_init',
	function () {
		if ( get_option( 'scrw_fane_ver' ) === SCRW_FANE_VERSION ) {
			return;
		}
		if ( ! post_type_exists( 'sc_product' ) || '' === (string) get_option( 'sc_core_seed_version', '' ) ) {
			return; // Wait for Core to seed its FANE stubs first.
		}
		scrw_sync_fane_products();
		update_option( 'scrw_fane_ver', SCRW_FANE_VERSION );
	},
	53
);
