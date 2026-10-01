<?php
/**
 * Rwanda product catalogue, from the SCL RW Product Catalogue (Dec 2025).
 *
 * Seeds each product once (idempotent by slug) with brand, model, category,
 * key specifications and a photo from assets/img/products/ (imported into the
 * Media Library). Everything stays editable under Products in wp-admin, and a
 * product the team trashes is never recreated.
 *
 * Also re-enables the /products/ archive, which the Core plugin redirects to
 * /brands/ on the group site, so the catalogue has its own page.
 *
 * @package SoundCreationsRwanda
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SCRW_PRODUCTS_VERSION', 'rw-products-1' );

require_once __DIR__ . '/products-data.php';

add_action(
	'init',
	function () {
		remove_action( 'template_redirect', 'sc_core_redirect_product_archive' );
	}
);

/** slug, title, brand, model, category, description, specs, order. */
function scrw_catalogue() {
	return array(
		array( 'db-opera-series', 'dB Technologies OPERA Series', 'dB Technologies', 'OPERA', 'Loudspeakers', 'Active loudspeakers with leading-edge DSP processing, advanced design and user-friendly operation, delivering vigorous yet pristine sound for playback and live music.', 'Type: Active 2-way loudspeakers' . "\n" . 'Processing: Onboard DSP' . "\n" . 'Applications: Live music, playback, venues', 1 ),
		array( 'db-b-hype-series', 'dB Technologies B-Hype Series', 'dB Technologies', 'B-Hype 8 / 10 / 12', 'Loudspeakers', 'Professional, efficient active speakers combining a Class D amplifier with high sound pressure levels and accurate reproduction.', 'B-Hype 8: 260 W peak, 120 dB max SPL, 70 Hz - 19.6 kHz' . "\n" . 'B-Hype 10: 260 W peak, 121 dB max SPL, 62 Hz - 19.6 kHz' . "\n" . 'B-Hype 12: 400 W peak, 126 dB max SPL, 61 Hz - 19.5 kHz', 2 ),
		array( 'db-sub-600-series', 'dB Technologies SUB 615 / SUB 618', 'dB Technologies', 'SUB 615 / SUB 618', 'Loudspeakers', 'Bass-reflex active subwoofers with 600 W RMS Class D amplification. Build a complete PA with one sub and two full-range speakers, with a built-in balanced crossover.', 'SUB 615: 1 x 15 in, 1200 W peak, 131 dB max SPL, 42 - 124 Hz, 25.5 kg' . "\n" . 'SUB 618: 1 x 18 in, 1200 W peak, 133 dB max SPL, 35 - 130 Hz, 31.1 kg', 3 ),
		array( 'db-flexsys-fmx', 'dB Technologies Flexsys FMX12 / FMX15', 'dB Technologies', 'FMX12 / FMX15', 'Loudspeakers', 'Two-way coaxial active stage monitors with 1200 W peak Class D amplification.', 'FMX12: 128 dB max SPL, 46 Hz - 19 kHz' . "\n" . 'FMX15: 128 dB max SPL, 52 Hz - 19 kHz' . "\n" . 'Amplifier: 1200 W peak Class D', 4 ),
		array( 'db-vio-l208-s118', 'dB Technologies VIO L208 & VIO S118', 'dB Technologies', 'VIO L208 / VIO S118', 'Loudspeakers', 'Compact active line array module and flyable horn-loaded subwoofer for touring and installed systems.', 'VIO L208: 900 W RMS DIGIPRO G3, 133.5 dB max SPL, 75 Hz - 20 kHz, 2 x 8 in + 1.4 in HF' . "\n" . 'VIO S118: 1600 W RMS DIGIPRO G4, 139 dB max SPL, 1 x 18 in, 45.1 kg' . "\n" . 'Networking: Dante and A2NET ready', 5 ),
		array( 'yamaha-dbr12', 'Yamaha DBR12', 'Yamaha', 'DBR12', 'Loudspeakers', 'A balance of size and performance with 1000 W of power and outstanding resolution. Ideal for front-of-house, floor monitoring or side fills.', 'Power: 1000 W' . "\n" . 'Applications: FOH, floor monitor, side fill', 6 ),
		array( 'yamaha-cbr10', 'Yamaha CBR10', 'Yamaha', 'CBR10', 'Loudspeakers', 'The most compact model in its series, delivering astonishing power for its size and weight for portable and installed use.', 'Type: Passive 2-way loudspeaker' . "\n" . 'Applications: Portable and installed', 7 ),
		array( 'yamaha-hs7', 'Yamaha HS7 (White)', 'Yamaha', 'HS7', 'Studio Monitors', 'Two-way bi-amplified nearfield studio monitor with a 6.5 in woofer and 1 in tweeter.', 'Frequency response: 43 Hz - 30 kHz' . "\n" . 'Power: 60 W LF + 35 W HF' . "\n" . 'Controls: Room control and high trim' . "\n" . 'Inputs: XLR and TRS', 8 ),
		array( 'yamaha-hs8i', 'Yamaha HS8I', 'Yamaha', 'HS8I', 'Studio Monitors', 'Two-way bi-amplified nearfield studio monitor with an 8 in woofer, with mounting points on four surfaces for installation.', 'Frequency response: 38 Hz - 30 kHz (-10 dB)' . "\n" . 'Power: 75 W LF + 45 W HF' . "\n" . 'Controls: Room control and high trim' . "\n" . 'Inputs: XLR and TRS', 9 ),
		array( 'dsppa-dsp7011', 'DSPPA DSP7011 Ceiling Speaker', 'DSPPA', 'DSP7011', 'Installed Sound & PA', 'Durable anti-UV ceiling speaker for public address systems, with selectable 70 V / 100 V input.', 'Power: 6 - 10 W' . "\n" . 'Frequency response: 120 Hz - 16 kHz' . "\n" . 'Sensitivity: 90 dB' . "\n" . 'Cutout: 165 - 170 mm', 10 ),
		array( 'dsppa-dsp8063b', 'DSPPA DSP8063B Wall Mount Speaker', 'DSPPA', 'DSP8063B', 'Installed Sound & PA', 'Minimalist two-way wall speaker in white or black with aluminium mesh and fireproof ABS enclosure.', 'Drivers: Silk dome tweeter, polypropylene woofer' . "\n" . 'Enclosure: UL94V-0 fireproof ABS' . "\n" . 'Power: Rear adjustable', 11 ),
		array( 'dsppa-dsp5040', 'DSPPA DSP5040 Waterproof Speaker', 'DSPPA', 'DSP5040', 'Installed Sound & PA', 'IP66 all-weather wall speaker for indoor and outdoor public address in schools, offices, hotels and stations.', 'Power: 20 W / 40 W' . "\n" . 'Rating: IP66' . "\n" . 'Input: 70 V / 100 V and 8 ohm', 12 ),
		array( 'dsppa-dsp455ii', 'DSPPA DSP455II Outdoor Column Speaker', 'DSPPA', 'DSP455II', 'Installed Sound & PA', 'Waterproof aluminium column speaker for outdoor and indoor installations, with power taps.', 'Enclosure: Solid aluminium alloy' . "\n" . 'Use: Indoor and outdoor', 13 ),
		array( 'shure-beta-52a', 'Shure Beta 52A', 'Shure', 'Beta 52A', 'Microphones', 'Supercardioid dynamic kick drum microphone with integrated locking stand adapter, built for very high SPL.', 'Type: Dynamic, supercardioid' . "\n" . 'Use: Kick drum, bass instruments', 14 ),
		array( 'shure-blx288-sm58', 'Shure BLX288/SM58 Dual Vocal System', 'Shure', 'BLX288/SM58', 'Wireless Systems', 'Dual wireless vocal system with two SM58 handheld transmitters and up to 14 hours battery life.', 'Receiver: BLX88 dual-channel' . "\n" . 'Transmitters: 2 x SM58 handheld' . "\n" . 'Battery: Up to 14 hours', 15 ),
		array( 'shure-pga48', 'Shure PGA48', 'Shure', 'PGA48-XLR-E', 'Microphones', 'Cardioid vocal microphone tailored for speech clarity, with on/off switch and XLR cable.', 'Type: Dynamic, cardioid' . "\n" . 'Includes: 4.57 m XLR cable, stand adapter, pouch', 16 ),
		array( 'shure-sm58', 'Shure SM58', 'Shure', 'SM58', 'Microphones', 'The legendary vocal microphone, with a brightened midrange, built-in pop filter and shock mount.', 'Type: Dynamic, cardioid' . "\n" . 'Frequency response: 50 Hz - 15 kHz' . "\n" . 'Cartridge: R59', 17 ),
		array( 'shure-pga58', 'Shure PGA58', 'Shure', 'PGA58', 'Microphones', 'Cardioid vocal microphone with natural clarity, on/off switch and rugged construction.', 'Type: Dynamic, cardioid', 18 ),
		array( 'shure-sm7b', 'Shure SM7B', 'Shure', 'SM7B', 'Microphones', 'The broadcast and podcast icon: smooth, warm vocals with excellent rejection of background noise.', 'Type: Dynamic' . "\n" . 'Gain: Works best with +60 dB preamps', 19 ),
		array( 'shure-sm57', 'Shure SM57', 'Shure', 'SM57', 'Microphones', 'Durable instrument microphone for drums, percussion and amplifiers.', 'Type: Dynamic, cardioid' . "\n" . 'Frequency response: 40 Hz - 15 kHz', 20 ),
		array( 'shure-pgadrumkit7', 'Shure PGADRUMKIT7', 'Shure', 'PGADRUMKIT7', 'Microphones', 'Complete seven-piece drum microphone kit for performance and recording.', 'Includes: 7 drum microphones with mounts and case', 21 ),
		array( 'shure-blx14-cvl', 'Shure BLX14/CVL Presenter System', 'Shure', 'BLX14/CVL', 'Wireless Systems', 'Wireless lavalier system for presenters with up to 14 hours battery life.', 'Receiver: BLX4' . "\n" . 'Transmitter: BLX1 bodypack with CVL lavalier', 22 ),
		array( 'shure-blx14-p98h', 'Shure BLX14/P98H Instrument System', 'Shure', 'BLX14/P98H', 'Wireless Systems', 'Wireless instrument system with clip-on PGA98H microphone.', 'Receiver: BLX4' . "\n" . 'Transmitter: BLX1 bodypack with PGA98H', 23 ),
		array( 'shure-blx188-cvl', 'Shure BLX188/CVL Dual Presenter System', 'Shure', 'BLX188/CVL', 'Wireless Systems', 'Dual wireless lavalier system for two presenters.', 'Receiver: BLX88 dual-channel' . "\n" . 'Transmitters: 2 x BLX1 with CVL lavaliers', 24 ),
		array( 'shure-beta-98ds', 'Shure Beta 98D/S', 'Shure', 'Beta 98D/S', 'Microphones', 'Miniature supercardioid condenser for drums and percussion, with A98D gooseneck mount.', 'Type: Electret condenser, supercardioid' . "\n" . 'Frequency response: 20 Hz - 20 kHz' . "\n" . 'Max SPL: 160 dB' . "\n" . 'Power: 48 V phantom', 25 ),
		array( 'dsppa-rm20', 'DSPPA RM20 Remote Paging Microphone', 'DSPPA', 'RM20', 'Installed Sound & PA', 'Two-zone remote paging microphone with chimes and RJ45 connection up to 500 m.', 'Zones: 2' . "\n" . 'Connection: RJ45, up to 500 m' . "\n" . 'Power: From MP600U / MP1000U', 26 ),
		array( 'behringer-podcastudio-2-usb', 'Behringer PODCASTUDIO 2 USB', 'Behringer', 'PODCASTUDIO 2 USB', 'Recording & Podcast', 'Everything needed for podcasts and home recording: XENYX 302USB mixer, XM8500 microphone and HPM1000 headphones.', 'Mixer: XENYX 302USB with USB interface' . "\n" . 'Microphone: XM8500' . "\n" . 'Headphones: HPM1000', 27 ),
		array( 'behringer-c-1u', 'Behringer C-1U', 'Behringer', 'C-1U', 'Recording & Podcast', 'USB large-diaphragm condenser microphone for direct recording on PC or Mac.', 'Pattern: Cardioid' . "\n" . 'Frequency response: 40 Hz - 20 kHz' . "\n" . 'Max SPL: 136 dB', 28 ),
		array( 'aver-vc520-pro', 'AVer VC520 Pro', 'AVer', 'VC520 Pro', 'Conferencing', 'Conferencing system for mid-to-large rooms with 12x optical zoom and daisy-chain speakerphone.', 'Camera: 1080p, 12x optical zoom, Sony True WDR' . "\n" . 'Management: IP-based', 29 ),
		array( 'aver-vc540', 'AVer VC540', 'AVer', 'VC540', 'Conferencing', '4K conference camera with Bluetooth speakerphone for medium-to-large rooms.', 'Camera: 4K, 16x total zoom' . "\n" . 'Management: IP-based', 30 ),
		array( 'yamaha-tf5', 'Yamaha TF5 Digital Mixing Console', 'Yamaha', 'TF5', 'Mixing Consoles', 'High input capacity and fader count for larger applications.', 'Faders: 33 motorised' . "\n" . 'Inputs: 48 mixing channels, 32 mic/line' . "\n" . 'Outputs: 16 XLR' . "\n" . 'USB: 34 x 34 recording', 31 ),
		array( 'yamaha-tf3', 'Yamaha TF3 Digital Mixing Console', 'Yamaha', 'TF3', 'Mixing Consoles', 'Ample input capacity and hands-on control in a compact console.', 'Faders: 25 motorised' . "\n" . 'Inputs: 48 mixing channels, 24 mic/line' . "\n" . 'Outputs: 16 XLR' . "\n" . 'USB: 34 x 34 recording', 32 ),
		array( 'yamaha-tf1', 'Yamaha TF1 Digital Mixing Console', 'Yamaha', 'TF1', 'Mixing Consoles', 'Compact, portable and rack-mountable for smaller systems.', 'Faders: 17 motorised' . "\n" . 'Inputs: 40 mixing channels, 16 mic/line' . "\n" . 'Outputs: 16 XLR' . "\n" . 'USB: 34 x 34 recording', 33 ),
		array( 'yamaha-dm3s', 'Yamaha DM3 Standard', 'Yamaha', 'DM3S', 'Mixing Consoles', 'Portable compact digital console with superb sound and fast setup.', 'Faders: 8 + 1' . "\n" . 'Screen: 9 in multi-touch' . "\n" . 'Inputs: 16 mic/line' . "\n" . 'Sampling: 48 / 96 kHz' . "\n" . 'Weight: 6.5 kg', 34 ),
		array( 'reloop-elite', 'Reloop Elite', 'Reloop', 'Elite', 'DJ Equipment', 'Professional two-channel DVS battle mixer for Serato DJ Pro.', 'Pads: 16 RGB' . "\n" . 'Audio: Dual 10-in/10-out 24-bit USB' . "\n" . 'Faders: 3 Mini Innofader Pro', 35 ),
		array( 'yamaha-mg06x', 'Yamaha MG06X', 'Yamaha', 'MG06X', 'Mixing Consoles', 'Six-channel mixer with D-PRE preamps and SPX effects.', 'Inputs: 2 mic / 6 line' . "\n" . 'Effects: SPX, 6 programs' . "\n" . 'Weight: 0.9 kg', 36 ),
		array( 'yamaha-mg10xu', 'Yamaha MG10XU', 'Yamaha', 'MG10XU', 'Mixing Consoles', 'Ten-channel mixer with USB audio, compressors and SPX effects.', 'Inputs: 4 mic / 10 line' . "\n" . 'USB: 24-bit / 192 kHz' . "\n" . 'Effects: SPX, 24 programs', 37 ),
		array( 'yamaha-mg12xu', 'Yamaha MG12XU', 'Yamaha', 'MG12XU', 'Mixing Consoles', 'Twelve-channel mixer with two group buses and USB audio.', 'Inputs: 6 mic / 12 line' . "\n" . 'Buses: 2 group + stereo' . "\n" . 'USB: 24-bit / 192 kHz', 38 ),
		array( 'yamaha-mg16xu', 'Yamaha MG16XU', 'Yamaha', 'MG16XU', 'Mixing Consoles', 'Sixteen-channel mixer with four groups, four aux and rack kit included.', 'Inputs: 10 mic / 16 line' . "\n" . 'Buses: 4 group + stereo' . "\n" . 'USB: 24-bit / 192 kHz', 39 ),
		array( 'yamaha-mg20xu', 'Yamaha MG20XU', 'Yamaha', 'MG20XU', 'Mixing Consoles', 'Twenty-channel mixer with USB audio and rack kit included.', 'Inputs: 16 mic / 20 line' . "\n" . 'Buses: 4 group + stereo' . "\n" . 'USB: 24-bit / 192 kHz', 40 ),
		array( 'yamaha-mgp24x', 'Yamaha MGP24X', 'Yamaha', 'MGP24X', 'Mixing Consoles', '24-channel premium mixing console.', 'Inputs: 16 mic / 24 line' . "\n" . 'Sends: 6 aux + 2 FX' . "\n" . 'Outputs: 2 matrix, 1 mono', 41 ),
		array( 'yamaha-mgp32x', 'Yamaha MGP32X', 'Yamaha', 'MGP32X', 'Mixing Consoles', '32-channel premium mixing console.', 'Inputs: 24 mic / 32 line' . "\n" . 'Sends: 6 aux + 2 FX' . "\n" . 'Outputs: 2 matrix, 1 mono', 42 ),
		array( 'yamaha-c40', 'Yamaha C40 Classical Guitar', 'Yamaha', 'C40', 'Guitars', 'Classical nylon-string guitar, a trusted choice for students and beginners.', 'Type: Classical', 43 ),
		array( 'yamaha-f310', 'Yamaha F310 Acoustic Guitar', 'Yamaha', 'F310', 'Guitars', 'Steel-string acoustic guitar in Tobacco Brown Sunburst.', 'Type: Acoustic' . "\n" . 'Finish: Tobacco Brown Sunburst', 44 ),
		array( 'encore-e99blk', 'Encore E99BLK Electric Guitar', 'Encore', 'E99BLK', 'Guitars', 'Electric guitar in gloss black with bolt-on maple neck.', 'Neck: Bolt-on maple' . "\n" . 'Scale length: About 25.5 in' . "\n" . 'Finish: Gloss black', 45 ),
		array( 'odyssey-debut-clarinet', 'Odyssey Debut Clarinet Outfit', 'JHS', 'Odyssey Debut', 'Wind Instruments', 'Popular student Bb clarinet designed by Peter Pollard, supplied with case.', 'Key: Bb' . "\n" . 'Keywork: 17 nickel-plated keys' . "\n" . 'Body: ABS resin', 46 ),
		array( 'pp-half-moon-tambourine', 'PP Half Moon Tambourine (Black)', 'JHS', 'PP Half Moon', 'Drums & Percussion', 'Headless tambourine with 16 pairs of jingles and padded handle.', 'Jingles: 16 pairs' . "\n" . 'Diameter: 21.5 cm', 47 ),
		array( 'yamaha-psr-sx920', 'Yamaha PSR-SX920', 'Yamaha', 'PSR-SX920', 'Keyboards & Digital Pianos', 'Flagship arranger workstation with a 7 in touchscreen, vocal harmoniser and four speakers.', 'Styles: 575' . "\n" . 'Voices: 587 + 63 drum/SFX kits + 480 XG' . "\n" . 'Screen: 7 in touch', 48 ),
		array( 'yamaha-psr-sx720', 'Yamaha PSR-SX720', 'Yamaha', 'PSR-SX720', 'Keyboards & Digital Pianos', 'Arranger workstation with enhanced voices and styles.', 'Styles: 450' . "\n" . 'Voices: 1,377 + 56 drum/SFX kits + 480 XG', 49 ),
		array( 'yamaha-psr-sx700', 'Yamaha PSR-SX700', 'Yamaha', 'PSR-SX700', 'Keyboards & Digital Pianos', 'Arranger workstation with 7 in touchscreen and live control.', 'Styles: 400' . "\n" . 'Voices: 986 + 41 drum/SFX kits', 50 ),
		array( 'yamaha-psr-sx600', 'Yamaha PSR-SX600', 'Yamaha', 'PSR-SX600', 'Keyboards & Digital Pianos', 'Arranger keyboard with assignable controls and audio recording.', 'Styles: 415' . "\n" . 'Voices: 850 + 43 drum/SFX kits + 480 XG', 51 ),
		array( 'yamaha-psr-e473', 'Yamaha PSR-E473', 'Yamaha', 'PSR-E473', 'Keyboards & Digital Pianos', 'Portable keyboard with Quick Sampling, Groove Creator and USB audio/MIDI.', 'Styles: 290' . "\n" . 'Voices: 820' . "\n" . 'Speakers: 2 x 6 W', 52 ),
		array( 'yamaha-ydp-105', 'Yamaha ARIUS YDP-105', 'Yamaha', 'YDP-105', 'Keyboards & Digital Pianos', '88-key digital piano with weighted GHS keyboard and Smart Pianist app support.', 'Keys: 88, GHS weighted' . "\n" . 'Voices: 10' . "\n" . 'Amplifier: 6 W x 2', 53 ),
		array( 'yamaha-p-145', 'Yamaha P-145 Digital Piano', 'Yamaha', 'P-145', 'Keyboards & Digital Pianos', 'Compact portable digital piano for beginning players.', 'Series: P Series' . "\n" . 'Optional: LP-5A three-pedal unit', 54 ),
		array( 'yamaha-sbp2f5', 'Yamaha SBP2F5 Drum Set (Raven Black)', 'Yamaha', 'SBP2F5', 'Drums & Percussion', 'Stage Custom Birch five-piece drum set in Raven Black.', 'Finish: Raven Black', 55 ),
	);
}

/** Import a bundled theme image once; return its attachment ID. */
function scrw_import_theme_image( $dir, $name, $title, $alt ) {
	$key   = $dir . '/' . $name;
	$found = get_posts(
		array(
			'post_type'   => 'attachment',
			'numberposts' => 1,
			'meta_key'    => '_scrw_source',
			'meta_value'  => $key,
			'fields'      => 'ids',
		)
	);
	if ( $found ) {
		return (int) $found[0];
	}
	$src = get_stylesheet_directory() . '/assets/img/' . $dir . '/' . $name . '.webp';
	if ( ! file_exists( $src ) ) {
		return 0;
	}
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';
	$tmp = wp_tempnam( $name . '.webp' );
	if ( ! $tmp || ! copy( $src, $tmp ) ) {
		return 0;
	}
	$id = media_handle_sideload( array( 'name' => $name . '.webp', 'tmp_name' => $tmp ), 0, $title );
	if ( is_wp_error( $id ) ) {
		@unlink( $tmp ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		return 0;
	}
	update_post_meta( $id, '_scrw_source', $key );
	update_post_meta( $id, '_wp_attachment_image_alt', $alt );
	return (int) $id;
}

function scrw_seed_products() {
	foreach ( scrw_catalogue() as $p ) {
		list( $slug, $title, $brand, $model, $cat, $desc, $specs, $order ) = $p;
		$existing = function_exists( 'sc_core_find_seeded_post' )
			? sc_core_find_seeded_post( $slug, 'sc_product' )
			: get_page_by_path( $slug, OBJECT, 'sc_product' );
		if ( $existing ) {
			continue;
		}
		$pid = wp_insert_post(
			array(
				'post_title'   => $title,
				'post_name'    => $slug,
				'post_excerpt' => $desc,
				'post_content' => '<p>' . esc_html( $desc ) . '</p><p>Available from Sound Creations Ltd Rwanda in Kigali, with manufacturer warranty and local after-sales support.</p>',
				'post_status'  => 'publish',
				'post_type'    => 'sc_product',
				'menu_order'   => (int) $order,
			)
		);
		if ( ! is_int( $pid ) || $pid < 1 ) {
			continue;
		}
		update_post_meta( $pid, '_sc_brand_name', $brand );
		update_post_meta( $pid, '_sc_model', $model );
		update_post_meta( $pid, '_sc_availability', 'Available in Kigali' );
		update_post_meta( $pid, '_sc_specs', $specs );
		update_post_meta( $pid, '_scrw_catalogue', 1 );
		if ( taxonomy_exists( 'sc_product_category' ) ) {
			wp_set_object_terms( $pid, $cat, 'sc_product_category', false );
		}
		if ( taxonomy_exists( 'sc_brand_tax' ) ) {
			wp_set_object_terms( $pid, $brand, 'sc_brand_tax', false );
		}
		$aid = scrw_import_theme_image( 'products', $slug, $title, $title . ' - ' . $cat . ' available from Sound Creations Rwanda' );
		if ( $aid ) {
			wp_update_post( array( 'ID' => $aid, 'post_parent' => $pid ) );
			set_post_thumbnail( $pid, $aid );
		}
	}
}

add_action(
	'admin_init',
	function () {
		if ( get_option( 'scrw_products_ver' ) === SCRW_PRODUCTS_VERSION ) {
			return;
		}
		if ( ! post_type_exists( 'sc_product' ) || '' === (string) get_option( 'sc_core_seed_version', '' ) ) {
			return;
		}
		// Importing ~55 photos can take a while on first run.
		if ( function_exists( 'set_time_limit' ) ) {
			@set_time_limit( 300 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		}
		scrw_seed_products();
		update_option( 'scrw_products_ver', SCRW_PRODUCTS_VERSION );
		flush_rewrite_rules( false );
	},
	50
);

/*
 * rw-products-2: replace the catalogue-PDF descriptions and short specs with
 * the official manufacturer data (inc/products-data.php), and swap in the
 * official product photo where one is bundled in assets/img/products-official/.
 * A product is only updated while its specs still match what rw-products-1
 * seeded, so anything edited by hand in wp-admin is left alone.
 */
function scrw_update_products_official() {
	if ( ! function_exists( 'scrw_catalogue_official' ) ) {
		return;
	}
	$official = scrw_catalogue_official();
	foreach ( scrw_catalogue() as $p ) {
		list( $slug, $title, $brand, $model, $cat, $desc, $specs ) = $p;
		if ( ! isset( $official[ $slug ] ) ) {
			continue;
		}
		$post = get_page_by_path( $slug, OBJECT, 'sc_product' );
		if ( ! $post ) {
			continue;
		}
		$cur = trim( str_replace( "\r", '', (string) get_post_meta( $post->ID, '_sc_specs', true ) ) );
		if ( '' !== $cur && trim( $specs ) !== $cur ) {
			continue; // Edited by hand.
		}
		list( $odesc, $ospecs, $ourl ) = $official[ $slug ];
		$content = '<p>' . esc_html( $odesc ) . '</p>'
			. '<p>Available from Sound Creations Ltd Rwanda in Kigali, with manufacturer warranty and local after-sales support.</p>';
		if ( '' !== $ourl ) {
			$content .= '<p><a href="' . esc_url( $ourl ) . '" target="_blank" rel="noopener">' . esc_html( $brand ) . ' product page</a></p>';
		}
		wp_update_post(
			array(
				'ID'           => $post->ID,
				'post_excerpt' => $odesc,
				'post_content' => $content,
			)
		);
		if ( '' !== trim( $ospecs ) ) {
			update_post_meta( $post->ID, '_sc_specs', $ospecs );
		}
		$aid = scrw_import_theme_image( 'products-official', $slug, $title, $title . ' - ' . $cat . ' available from Sound Creations Rwanda' );
		if ( $aid ) {
			wp_update_post( array( 'ID' => $aid, 'post_parent' => $post->ID ) );
			set_post_thumbnail( $post->ID, $aid );
		}
	}
}

add_action(
	'admin_init',
	function () {
		if ( 'rw-products-1' !== get_option( 'scrw_products_ver' ) ) {
			return; // Runs once, right after the first seed has completed.
		}
		scrw_update_products_official();
		update_option( 'scrw_products_ver', 'rw-products-2' );
	},
	51
);

/*
 * rw-products-3: more official photos bundled in assets/img/products-official/
 * (Shure, DSPPA, AVer, Yamaha keyboards). Swap them in for any product still
 * showing its catalogue-PDF photo; a photo chosen by hand is left alone.
 */
function scrw_update_products_photos() {
	foreach ( scrw_catalogue() as $p ) {
		list( $slug, $title, $brand, $model, $cat ) = $p;
		$post = get_page_by_path( $slug, OBJECT, 'sc_product' );
		if ( ! $post ) {
			continue;
		}
		$thumb = (int) get_post_thumbnail_id( $post->ID );
		$src   = $thumb ? (string) get_post_meta( $thumb, '_scrw_source', true ) : '';
		if ( $thumb && 'products/' . $slug !== $src ) {
			continue; // Already official, or replaced by hand.
		}
		$aid = scrw_import_theme_image( 'products-official', $slug, $title, $title . ' - ' . $cat . ' available from Sound Creations Rwanda' );
		if ( $aid ) {
			wp_update_post( array( 'ID' => $aid, 'post_parent' => $post->ID ) );
			set_post_thumbnail( $post->ID, $aid );
		}
	}
}

add_action(
	'admin_init',
	function () {
		if ( 'rw-products-2' !== get_option( 'scrw_products_ver' ) ) {
			return;
		}
		if ( function_exists( 'set_time_limit' ) ) {
			@set_time_limit( 300 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		}
		scrw_update_products_photos();
		update_option( 'scrw_products_ver', 'rw-products-3' );
	},
	52
);
