<?php
/**
 * Rwanda case studies, taken from the SCL RW Company Profile 2025.
 *
 * Seeded once per version, idempotent by slug. Each project gets its photos
 * from assets/img/projects/ (imported into the Media Library on first run) as
 * the featured image plus gallery. Everything stays editable in wp-admin, and
 * a project the team trashes is never recreated.
 *
 * @package SoundCreationsRwanda
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SCRW_PROJECTS_VERSION', 'rw-projects-2' );

function scrw_projects() {
	return array(
		array(
			'title'      => 'Ministry of Finance (MINECOFIN)',
			'slug'       => 'minecofin-conference-system',
			'industry'   => 'Corporate',
			'images'     => array( 'minecofin' ),
			'summary'    => 'A Shure Microflex Complete conferencing system for Rwanda’s Ministry of Finance and Economic Planning, bringing clear audio and simple meeting control to the boardroom.',
			'client'     => 'Ministry of Finance and Economic Planning (MINECOFIN)',
			'location'   => 'Kigali, Rwanda',
			'scope'      => "Audio-conferencing design\nProcurement\nInstallation and configuration\nUser training",
			'brands'     => 'Shure',
			'challenge'  => 'The ministry needed a conferencing system that gives every delegate clear, intelligible audio, lets the chair manage the meeting, and connects to video and recording systems.',
			'solution'   => 'We supplied and installed a Shure Microflex Complete (MXC) system with delegate and chairman units, configured central control and integration with external audio and video, and trained the ministry’s staff to run it.',
			'technology' => "20 Shure Microflex Complete (MXC) delegate and chairman units with built-in microphones and speakers\n20 Shure MXC-series gooseneck microphones\nCentral Control Unit (CCU) for microphone activation and external integration\nNetworked audio interface for recording and external systems\nMXCWAP wireless access point for Microflex Complete Wireless\nSoftware control interface with video and system management integration",
			'result'     => 'Clearer audio, better meeting management and easier collaboration across the ministry’s meetings.',
			'content'    => 'Sound Creations Rwanda supplied and installed the Shure Microflex conferencing system at Rwanda’s Ministry of Finance.',
		),
		array(
			'title'      => 'Intare Kivu Arena',
			'slug'       => 'intare-kivu-arena',
			'industry'   => 'Events',
			'images'     => array( 'intare-kivu-arena', 'intare-kivu-arena-2' ),
			'summary'    => 'Acoustic design, sound, AV and lighting for the Intare Kivu Arena in Rubavu, built for clear speech and even coverage at every kind of event.',
			'client'     => 'RPF Rubavu',
			'location'   => 'Gisenyi, Rubavu, Rwanda',
			'scope'      => "Acoustic design and treatment\nSound system design\nAudio visual\nLighting",
			'brands'     => 'dB Technologies, Yamaha',
			'challenge'  => 'The arena’s concave walls concentrated reflections and caused echo, so speech and music were uneven across the room. It needed controlled acoustics and a sound system that could serve conferences, ceremonies and performances.',
			'solution'   => 'We treated the floor, walls and ceiling to bring reverberation under control, then designed a sound system to suit the treated room: a dB Technologies mini line array with S30 subwoofers, mixed on a Yamaha TF5 console.',
			'technology' => "Acoustic carpet floor treatment\nSlotted and fabric wall panels for diffusion and control\nAcoustic ceiling panels to manage reverberation\ndB Technologies mini line array\ndB Technologies S30 subwoofers\nYamaha TF5 digital mixing console",
			'result'     => 'Better speech intelligibility and performance sound, with even coverage across the arena.',
			'content'    => 'Sound Creations Rwanda designed and delivered the acoustic and sound system for Intare Kivu Arena in Rubavu.',
		),
		array(
			'title'      => 'Ntare Louisenlund School',
			'slug'       => 'ntare-louisenlund-school',
			'industry'   => 'Education',
			'images'     => array( 'ntare-louisenland', 'ntare-louisenland-2' ),
			'summary'    => 'Acoustic treatment and a PA system that turn the school hall into a clear, professional space for assemblies, presentations and performances.',
			'client'     => 'Ntare Louisenlund School',
			'location'   => 'Rwanda',
			'scope'      => "Acoustic treatment\nPA system design and installation",
			'brands'     => 'dB Technologies, Yamaha',
			'challenge'  => 'The hall hosts assemblies, presentations and performances. Hard surfaces made speech hard to follow, and the room needed even coverage and simple controls for school staff.',
			'solution'   => 'We placed acoustic wall panels and fabric-wrapped absorbers to cut reflections, then installed a dB Technologies and Yamaha PA system with a mixer staff can use easily.',
			'technology' => "Acoustic wall panels\nFabric-wrapped absorption panels, placed for even distribution\ndB Technologies Opera 15 and Opera 10 loudspeakers\ndB Technologies Sub 615 and Yamaha DXS18 subwoofers\nYamaha MG16XU mixer",
			'result'     => 'Clearer speech and presentations, balanced sound across the hall, and controls the school can run itself.',
			'content'    => 'Sound Creations Rwanda installed acoustic treatment and a PA system in the Ntare Louisenlund School hall.',
		),
		array(
			'title'      => 'Christian Life Assembly Church',
			'slug'       => 'christian-life-assembly-church',
			'industry'   => 'Worship',
			'images'     => array( 'christian-life-assembly' ),
			'summary'    => 'A line-array sound system and Chauvet stage lighting that give Christian Life Assembly clear worship audio and a strong stage presence.',
			'client'     => 'Christian Life Assembly',
			'location'   => 'Kigali, Rwanda',
			'scope'      => "Sound system design and installation\nStage lighting",
			'brands'     => 'dB Technologies, Allen & Heath, Chauvet',
			'challenge'  => 'Services combine preaching and live music, so the church needed even coverage for both, dependable stage monitoring and lighting that lifts the stage.',
			'solution'   => 'We installed a dB Technologies K5 line array with KS20 subwoofers and FM15 monitors on an Allen & Heath SQ7 console, plus a Chauvet lighting rig with moving heads, LED wash and spotlights.',
			'technology' => "dB Technologies K5 line array\ndB Technologies KS20 subwoofers\ndB Technologies FM15 stage monitors\nAllen & Heath SQ7 digital mixer\nChauvet stage lighting: moving heads, LED wash and spotlights",
			'result'     => 'Clear, balanced audio for speech and music, immersive lighting and a system the team runs with ease.',
			'content'    => 'Sound Creations Rwanda installed the sound and stage lighting system at Christian Life Assembly.',
		),
		array(
			'title'      => 'Atelier du Vin',
			'slug'       => 'atelier-du-vin',
			'industry'   => 'Hospitality',
			'images'     => array( 'atelier-du-vin', 'atelier-du-vin-2' ),
			'summary'    => 'A premium sound system for Atelier du Vin in Kigali, tuned for background music and live performance.',
			'client'     => 'Atelier du Vin',
			'location'   => 'Kigali, Rwanda',
			'scope'      => 'Sound system design and installation',
			'brands'     => 'dB Technologies, Yamaha',
			'challenge'  => 'The venue wanted sound that sets the mood for guests all evening and can handle live performances, with even coverage throughout.',
			'solution'   => 'We installed dB Technologies Opera loudspeakers with dB Technologies and Yamaha subwoofers, controlled from a Yamaha mixer for quick changes between events.',
			'technology' => "dB Technologies Opera 15 and Opera 10 loudspeakers\ndB Technologies Sub 615 and Yamaha DXS18 subwoofers\nYamaha MG16XU mixer",
			'result'     => 'A better guest atmosphere, clear and balanced audio for music and live sets, and flexible control.',
			'content'    => 'Sound Creations Rwanda designed and installed the sound system at Atelier du Vin, Kigali.',
		),
		array(
			'title'      => 'Romantic Garden',
			'slug'       => 'romantic-garden',
			'industry'   => 'Hospitality',
			'images'     => array( 'romantic-garden', 'romantic-garden-2' ),
			'summary'    => 'A scalable dB Technologies and Yamaha sound system for Romantic Garden, an events venue with clear audio for speeches and music.',
			'client'     => 'Romantic Garden',
			'location'   => 'Rwanda',
			'scope'      => 'Sound system design and installation',
			'brands'     => 'dB Technologies, Yamaha',
			'challenge'  => 'The venue hosts weddings and events of different sizes, so it needed clear, powerful sound that scales to each event.',
			'solution'   => 'We designed and installed a dB Technologies system with Opera loudspeakers, a Sub 618 and B-Hype 10 fill, mixed on a Yamaha TF5.',
			'technology' => "dB Technologies Opera 15 and Opera 12 loudspeakers\ndB Technologies Sub 618 subwoofer\ndB Technologies B-Hype 10 loudspeakers\nYamaha TF5 digital mixing console",
			'result'     => 'Clear, powerful sound for speech and music that adapts to every event.',
			'content'    => 'Sound Creations Rwanda designed and installed the sound system at Romantic Garden.',
		),
	);
}

/** Import a bundled theme image into the Media Library once; return its ID. */
function scrw_import_project_image( $name, $title ) {
	$found = get_posts(
		array(
			'post_type'   => 'attachment',
			'numberposts' => 1,
			'meta_key'    => '_scrw_source',
			'meta_value'  => $name,
			'fields'      => 'ids',
		)
	);
	if ( $found ) {
		return (int) $found[0];
	}
	$src = get_stylesheet_directory() . '/assets/img/projects/' . $name . '.webp';
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
	update_post_meta( $id, '_scrw_source', $name );
	update_post_meta( $id, '_wp_attachment_image_alt', $title . ' - Sound Creations Rwanda project' );
	return (int) $id;
}

function scrw_seed_projects() {
	foreach ( scrw_projects() as $p ) {
		$existing = function_exists( 'sc_core_find_seeded_post' )
			? sc_core_find_seeded_post( $p['slug'], 'sc_project' )
			: get_page_by_path( $p['slug'], OBJECT, 'sc_project' );
		if ( $existing ) {
			continue; // Already seeded, edited or deliberately trashed.
		}
		$pid = wp_insert_post(
			array(
				'post_title'   => $p['title'],
				'post_name'    => $p['slug'],
				'post_content' => $p['content'],
				'post_excerpt' => $p['summary'],
				'post_status'  => 'publish',
				'post_type'    => 'sc_project',
			)
		);
		if ( ! is_int( $pid ) || $pid < 1 ) {
			continue;
		}
		$meta = array(
			'summary'     => $p['summary'],
			'client'      => $p['client'],
			'location'    => $p['location'],
			'year'        => '',
			'scope'       => $p['scope'],
			'brands_used' => $p['brands'],
			'challenge'   => $p['challenge'],
			'solution'    => $p['solution'],
			'technology'  => $p['technology'],
			'result'      => $p['result'],
		);
		foreach ( $meta as $mk => $mv ) {
			update_post_meta( $pid, '_sc_' . $mk, $mv );
		}
		if ( taxonomy_exists( 'sc_industry' ) ) {
			wp_set_object_terms( $pid, $p['industry'], 'sc_industry', false );
		}
		$ids = array();
		foreach ( $p['images'] as $img ) {
			$aid = scrw_import_project_image( $img, $p['title'] );
			if ( $aid ) {
				$ids[] = $aid;
				wp_update_post( array( 'ID' => $aid, 'post_parent' => $pid ) );
			}
		}
		if ( $ids ) {
			set_post_thumbnail( $pid, $ids[0] );
			update_post_meta( $pid, '_sc_gallery', implode( ',', $ids ) );
		}
	}

	// RPF Rubavu Multipurpose Hall (Core starter project, Rubavu) is a Rwanda
	// project and is featured on the homepage, as on the group site.
	// rw-projects-1 had drafted it; publish it again.
	$rpf = get_page_by_path( 'rpf-rubavu-hall', OBJECT, 'sc_project' );
	if ( $rpf && 'draft' === $rpf->post_status ) {
		wp_update_post( array( 'ID' => $rpf->ID, 'post_status' => 'publish' ) );
	}
}

add_action(
	'admin_init',
	function () {
		if ( get_option( 'scrw_projects_ver' ) === SCRW_PROJECTS_VERSION ) {
			return;
		}
		if ( ! post_type_exists( 'sc_project' ) || '' === (string) get_option( 'sc_core_projects_ver', '' ) ) {
			return; // Wait for Core's own project seeding to run first.
		}
		scrw_seed_projects();
		update_option( 'scrw_projects_ver', SCRW_PROJECTS_VERSION );
	},
	40
);
