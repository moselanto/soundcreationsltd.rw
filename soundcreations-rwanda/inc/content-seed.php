<?php
/**
 * Rwanda content: the four service areas, Rwanda legal pages, and guards
 * that stop the Core plugin seeding Kenya-only starter content here.
 *
 * Everything is idempotent and never overwrites a page an editor has changed.
 *
 * @package SoundCreationsRwanda
 */

if ( \! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SCRW_CONTENT_VERSION', 'rw-content-2' );

/**
 * The five Rwanda service areas, with full copy (proposal section B).
 * Slug => array( title, excerpt, html ).
 */
function scrw_services() {
	$cta = '<p><a href="/request-a-quote/">Request a quote</a> or <a href="/request-a-consultation/">book a site consultation</a> with our Kigali team. Call <a href="tel:+250783141050">+250 783 141 050</a> or message us on <a href="https://wa.me/250783141050">WhatsApp</a>.</p>';

	return array(
		'dj-solutions'            => array(
			'DJ Solutions',
			'DJ controllers, mixers, players, monitors and complete DJ booths for clubs, lounges, hotels, events and mobile DJs in Kigali and across Rwanda.',
			'<p>From a mobile DJ starting out to a club or hotel lounge that needs a reliable booth every night, Sound Creations Ltd Rwanda supplies and sets up DJ systems that sound professional and stand up to heavy use.</p>
<h2>What we deliver</h2>
<ul>
<li><strong>DJ controllers and mixers</strong> - all-in-one controllers, club mixers and players for every level, from first gig to residency.</li>
<li><strong>DJ monitoring and PA</strong> - booth monitors, headphones and powered speakers sized for your venue or event.</li>
<li><strong>Club and lounge booths</strong> - complete DJ booth design and installation for clubs, bars, hotels and event spaces, integrated with the venue sound and lighting.</li>
<li><strong>Accessories and cabling</strong> - genuine cables, stands, cases and spares so a set never stops because of a small part.</li>
</ul>
<h2>Advice from people who know the gear</h2>
<p>Visit or call our Kigali team to compare options, get honest advice on what fits your budget and style, and buy genuine equipment with manufacturer warranty and local after-sales support.</p>
' + $cta,
		),
		'lighting-solutions'      => array(
			'Lighting Solutions',
			'Stage, architectural, event and church lighting for venues in Kigali and across Rwanda - designed, supplied, installed and programmed.',
			'<p>Good lighting changes how a space feels and how an audience experiences it. Sound Creations Ltd Rwanda designs and delivers lighting systems for churches, conference centres, hotels, event venues, concert stages, studios and television sets across Kigali and the rest of Rwanda.</p>
<h2>What we deliver</h2>
<ul>
<li><strong>Stage and event lighting</strong> - moving heads, LED wash and spot fixtures, profiles and effects for concerts, worship and live events.</li>
<li><strong>Architectural lighting</strong> - facade, feature and interior lighting for hotels, offices and public buildings.</li>
<li><strong>Lighting control</strong> - DMX and networked control consoles, programming and operator training so your team can run the rig confidently.</li>
<li><strong>Studio and broadcast lighting</strong> - even, flicker-free lighting for video production, streaming and TV.</li>
</ul>
<h2>How we work</h2>
<p>We start with a site visit in Rwanda to understand the room, the rigging points, the power available and how the space is used. We then design the lighting plot, specify fixtures from trusted global brands, install and focus them, programme the scenes and train your operators. After handover, our local team is on hand for service and backup.</p>
' + $cta,
		),
		'studio-solutions'        => array(
			'Studio Solutions',
			'Recording, broadcast, podcast and streaming studios in Rwanda - acoustically treated, equipped and commissioned end to end.',
			'<p>Whether you are building a music recording studio, a radio or TV broadcast suite, a podcast room or a church streaming setup, Sound Creations Ltd Rwanda delivers studios that sound accurate and work reliably from day one.</p>
<h2>What we deliver</h2>
<ul>
<li><strong>Studio design and acoustics</strong> - room layout, isolation and acoustic treatment so what you hear is what you record.</li>
<li><strong>Recording and monitoring</strong> - microphones, audio interfaces, mixing consoles, studio monitors and headphone systems.</li>
<li><strong>Broadcast and streaming</strong> - on-air consoles, talkback, video capture and streaming workflows for radio, TV and online services.</li>
<li><strong>Integration and cabling</strong> - clean, labelled, documented wiring that is easy to maintain and expand.</li>
</ul>
<h2>Built for Rwandan studios</h2>
<p>We specify equipment for the realities of the local market: reliable power protection, serviceable components, and genuine equipment with manufacturer warranty, supported by a team that is based in Kigali rather than overseas.</p>
' + $cta,
		),
		'architectural-acoustics' => array(
			'Architectural Acoustics',
			'Acoustic design, measurement and treatment for churches, auditoriums, conference rooms, hotels and offices in Rwanda.',
			'<p>A room that echoes, booms or leaks sound undermines even the best audio system. Sound Creations Ltd Rwanda treats acoustics as an engineering discipline: we measure, analyse, design, treat and verify, so speech is clear and music sounds the way it should.</p>
<h2>What we deliver</h2>
<ul>
<li><strong>Acoustic measurement</strong> - reverberation time (RT60), background noise and speech-intelligibility testing on site.</li>
<li><strong>Acoustic design</strong> - treatment plans for new builds and existing buildings, working alongside architects and contractors in Rwanda.</li>
<li><strong>Acoustic treatment</strong> - wall and ceiling absorbers, diffusers, acoustic ceilings and bass traps from leading manufacturers.</li>
<li><strong>Soundproofing and noise control</strong> - isolation between rooms and from outside noise for studios, hotels, offices and places of worship.</li>
</ul>
<h2>Verified results</h2>
<p>Every acoustic project ends with a measurement against the original baseline, so you can see the improvement in numbers as well as hear it.</p>
' + $cta,
		),
		'service-and-backup'      => array(
			'Service and Backup',
			'Maintenance, repairs, warranty support, equipment backup and operator training for audio, lighting and AV systems in Rwanda.',
			'<p>An installation is only as good as the support behind it. Sound Creations Ltd Rwanda keeps your audio, lighting and AV systems running with local service, genuine parts and fast backup when you need it most.</p>
<h2>What we deliver</h2>
<ul>
<li><strong>Preventive maintenance</strong> - scheduled inspections, cleaning, firmware updates and system checks.</li>
<li><strong>Repairs and warranty support</strong> - diagnosis, genuine replacement parts and manufacturer warranty handling.</li>
<li><strong>Equipment backup</strong> - standby equipment and on-site support for critical services and events.</li>
<li><strong>Training</strong> - hands-on operator and technician training so your team gets the most from the system.</li>
</ul>
<h2>Support in Rwanda, backed by the group</h2>
<p>Our Kigali team handles day-to-day service and is backed by the wider Sound Creations Ltd engineering team for complex faults and specialist work.</p>
' + $cta,
		),
	);
}

/** Core starter solutions that duplicate the four Rwanda service areas. */
function scrw_duplicate_core_solutions() {
	return array( 'lighting', 'acoustics', 'broadcast-recording', 'support-training' );
}

function scrw_seed_services() {
	foreach ( scrw_services() as $slug => $svc ) {
		list( $title, $excerpt, $html ) = $svc;
		if ( get_page_by_path( $slug, OBJECT, 'sc_solution' ) ) {
			continue;
		}
		wp_insert_post(
			array(
				'post_title'   => $title,
				'post_name'    => $slug,
				'post_excerpt' => $excerpt,
				'post_content' => $html,
				'post_status'  => 'publish',
				'post_type'    => 'sc_solution',
				'menu_order'   => 0,
			)
		);
	}

	// Draft the overlapping Core starter solutions, but only while they still
	// hold the one-line starter text (an edited post is left alone). Drafts
	// still occupy their slug, so Core will not re-create them.
	if ( function_exists( 'sc_core_starter_solutions' ) ) {
		$starter = array();
		foreach ( sc_core_starter_solutions() as $row ) {
			$starter[ $row[1] ] = $row[2];
		}
		foreach ( scrw_duplicate_core_solutions() as $slug ) {
			$post = get_page_by_path( $slug, OBJECT, 'sc_solution' );
			if ( $post && 'publish' === $post->post_status && isset( $starter[ $slug ] ) && trim( $post->post_content ) === $starter[ $slug ] ) {
				wp_update_post(
					array(
						'ID'          => $post->ID,
						'post_status' => 'draft',
					)
				);
			}
		}
	}
}

/**
 * Rwanda legal pages. Core seeds Kenya-law Privacy and Terms pages; on this
 * site they are replaced, but only while they still contain the untouched
 * Kenya copy (detected by the Nairobi registered-office line).
 * LEGAL REVIEW: have Rwandan counsel confirm before relying on these.
 */
function scrw_legal_pages() {
	$contact = '<p>Sound Creations Ltd Rwanda, KN1 Rd, Muhima, Kigali, Rwanda - sales@soundcreationsltd.com - +250 783 141 050.</p>';

	return array(
		'privacy-policy' => '<p>This policy explains how Sound Creations Ltd Rwanda ("we", "us") collects and uses personal data through soundcreationsltd.rw. It is designed to align with Law N° 058/2021 of 13/10/2021 relating to the protection of personal data and privacy in Rwanda.</p>
<h2>What we collect</h2>
<p>When you submit an enquiry, quote or consultation request we collect the details you provide: your name, organisation, email address, phone number, country and the content of your message, plus any file you choose to attach. Our website also records basic technical data (such as IP address and browser type) for security and, where you consent, anonymous analytics.</p>
<h2>Why we use it</h2>
<p>We use your data to respond to your enquiry, prepare quotations, deliver and support projects, and protect the website from abuse. We do not sell personal data.</p>
<h2>Who we share it with</h2>
<p>Your enquiry may be shared with colleagues in the Sound Creations Ltd group (including our offices in Kenya, the DR Congo and the UAE) where they are needed to serve you, and with service providers that host our website and email. Where data leaves Rwanda we take steps to keep it protected as required by law.</p>
<h2>How long we keep it</h2>
<p>Enquiry records are kept for up to 24 months and then deleted, unless they become part of a contract or we are required by law to keep them longer.</p>
<h2>Your rights</h2>
<p>You may ask to access, correct or delete your personal data, object to its use, or withdraw consent at any time. You may also lodge a complaint with the National Cyber Security Authority (NCSA), Rwanda\'s data protection supervisory authority.</p>
<h2>Contact</h2>
' . $contact,
		'terms'          => '<p>These terms govern your use of soundcreationsltd.rw and quotations issued by Sound Creations Ltd Rwanda.</p>
<h2>Website content</h2>
<p>Information on this website is provided for general guidance. Product specifications, availability and prices may change without notice; a written quotation from us is the only binding statement of price and scope.</p>
<h2>Quotations and orders</h2>
<p>Quotations are valid for the period stated on them. Orders, delivery, installation and payment are governed by the quotation or contract agreed with you, which takes precedence over these website terms.</p>
<h2>Warranty</h2>
<p>Equipment we supply carries the manufacturer\'s warranty. Warranty claims are handled through our Kigali office.</p>
<h2>Intellectual property</h2>
<p>Text, images and logos on this website belong to Sound Creations Ltd or its brand partners and may not be reused without permission.</p>
<h2>Governing law</h2>
<p>These terms are governed by the laws of the Republic of Rwanda. The parties will first seek to resolve any dispute amicably; failing that, disputes shall be subject to the competent courts of Rwanda.</p>
<h2>Contact</h2>
' . $contact,
	);
}

function scrw_seed_legal() {
	$titles = array(
		'privacy-policy' => 'Privacy Policy',
		'terms'          => 'Terms and Conditions',
	);
	foreach ( scrw_legal_pages() as $slug => $html ) {
		$page = get_page_by_path( $slug, OBJECT, 'page' );
		if ( null === $page ) {
			wp_insert_post(
				array(
					'post_title'   => $titles[ $slug ],
					'post_name'    => $slug,
					'post_content' => $html,
					'post_status'  => 'publish',
					'post_type'    => 'page',
				)
			);
			continue;
		}
		$cur     = (string) $page->post_content;
		$untouch = ( '' === trim( $cur ) || false \!== strpos( $cur, 'Mpaka Plaza' ) || false \!== strpos( $cur, '[CONTENT TO BE CONFIRMED' ) );
		if ( $untouch ) {
			wp_update_post(
				array(
					'ID'           => $page->ID,
					'post_content' => $html,
				)
			);
		}
	}
}

/*
 * Guards. Core seeds Kenya case studies (West Nairobi School, Sarit Expo...)
 * and Kenya-law legal copy once per version on admin_init (priorities 6-7).
 * On the Rwanda site we mark those versions done first, so the Projects
 * archive stays reserved for Rwanda projects supplied by the Rwanda team.
 * If Core bumps its version strings, update them here too.
 */
add_action(
	'admin_init',
	function () {
		if ( '' === (string) get_option( 'sc_core_projects_ver', '' ) ) {
			update_option( 'sc_core_projects_ver', 'projects-2026-09-06' );
		}
		if ( '' === (string) get_option( 'sc_core_legal_ver', '' ) ) {
			update_option( 'sc_core_legal_ver', 'legal-2026-09-06' );
		}
	},
	1
);

function scrw_seed_content() {
	scrw_seed_services();
	scrw_seed_legal();
}

add_action( 'after_switch_theme', 'scrw_seed_content', 30 );

add_action(
	'admin_init',
	function () {
		if ( get_option( 'scrw_content_ver' ) === SCRW_CONTENT_VERSION ) {
			return;
		}
		if ( \! post_type_exists( 'sc_solution' ) ) {
			return; // Core plugin not active yet; try again next admin load.
		}
		scrw_seed_content();
		update_option( 'scrw_content_ver', SCRW_CONTENT_VERSION );
	},
	12
);
