<?php
/**
 * Buyer guides (v2.2.0): answer-first technical articles for AEO / GEO.
 *
 * Why: the audit (docs/SEO-AUDIT-2026-10.md, sections 4 and 6) found no
 * Rwandan AV company publishing practical buying and planning guides. Answer
 * engines quote pages that answer a question directly, explain the criteria
 * and point to a credible local provider. These four guides map to the
 * question clusters in the audit and link to the matching solution pages.
 *
 * Safety:
 *  - Created as DRAFTS in the "Buyer Guides" category. A Sound Creations
 *    engineer must review, add real project photos/examples and publish.
 *  - Created once per guide slug; an existing post with the slug (any status,
 *    including trash) is never touched, so editors' work is never overwritten.
 *  - No invented prices, measurements, percentages or project results.
 *  - Article schema comes from the existing core graph (posts), so there is
 *    still one JSON-LD graph. FAQs are visible text, not extra schema.
 *
 * @package SoundCreationsRwanda
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SCRW_GUIDES_VERSION', 'rw-guides-1' );

/** Shared closing block: next step + contacts. */
function scrw_guide_cta() {
	return '<h2>Talk to our Kigali team</h2>
<p>Sound Creations Ltd Rwanda designs, supplies, installs and supports professional audio, acoustics, lighting and AV systems from KN1 Rd, Muhima, Kigali, and is the authorised Yamaha distributor in Rwanda. Call or WhatsApp +250 783 141 050, <a href="/request-a-consultation/">book a site consultation</a> or <a href="/request-a-quote/">request a quote</a>.</p>';
}

/** Render visible FAQ rows. */
function scrw_guide_faq_html( $rows ) {
	$h = '<h2>Frequently asked questions</h2>';
	foreach ( $rows as $row ) {
		$h .= '<h3>' . esc_html( $row[0] ) . '</h3><p>' . esc_html( $row[1] ) . '</p>';
	}
	return $h;
}

/** The guides. slug => data. */
function scrw_guides() {
	return array(
		'sound-system-quotation-checklist-rwanda' => array(
			'title'   => 'What a Sound System Quotation Should Include: a Procurement Checklist for Rwanda',
			'seo'     => 'Sound System Quotation Checklist for Rwanda | Sound Creations',
			'desc'    => 'What a professional sound or AV quotation in Rwanda should include: site survey, design, genuine equipment, installation, commissioning, training and warranty.',
			'excerpt' => 'A professional sound system quotation should cover more than a list of equipment. It should state the site survey and design, exact makes and models, installation and cabling, commissioning and tuning, operator training, warranty terms and after-sales support, so that tenders from different suppliers can be compared fairly.',
			'html'    => '<p><strong>Short answer:</strong> a professional sound system quotation should cover more than a list of equipment. It should state the site survey and design, exact makes and models, installation and cabling, commissioning and tuning, operator training, warranty terms and after-sales support. Without these, two quotes for "a PA system" cannot be compared fairly.</p>
<p>Schools, churches, ministries, hotels and companies in Rwanda often receive quotes that differ widely in price. The difference is usually in what is left out. Use this checklist when you request or evaluate a quotation.</p>
<h2>1. The brief and the site survey</h2>
<ul><li>Room dimensions, audience size and main uses (speech, live music, recorded music, meetings, streaming).</li><li>Whether the supplier visited the site, and what they observed (hard surfaces, echo, power, cable routes, mounting points).</li><li>Any existing equipment that will be reused.</li></ul>
<h2>2. System design</h2>
<ul><li>Loudspeaker type, quantity and positions, with the reasoning for coverage of every seat.</li><li>Mixer, microphones, amplification and processing, and how they connect.</li><li>Whether acoustic treatment is recommended. See <a href="/solutions/architectural-acoustics/">architectural acoustics</a>.</li></ul>
<h2>3. Equipment, line by line</h2>
<ul><li>Exact brand and model for every item, not "professional speaker" or equivalent.</li><li>Confirmation that equipment is genuine and supplied through official channels, with manufacturer warranty honoured in Rwanda.</li><li>Delivery time and whether items are in stock in Kigali.</li></ul>
<h2>4. Installation and commissioning</h2>
<ul><li>Cabling, mounting hardware, racks, power distribution and labelling.</li><li>System tuning and testing after installation (commissioning), and how the result is checked.</li><li>Who supervises the work on site, and the expected duration.</li></ul>
<h2>5. Training, documentation and support</h2>
<ul><li>Hands-on training for the people who will run the system, such as volunteers, staff or technicians.</li><li>As-built documentation and saved settings.</li><li>Warranty period, response arrangements and preventive maintenance. See <a href="/solutions/service-and-backup/">service and backup</a>.</li></ul>
<h2>6. Commercial terms</h2>
<ul><li>Prices in RWF, with taxes stated, and payment milestones.</li><li>What is excluded, such as civil works, electrical works or ceiling access.</li><li>Validity period of the quote.</li></ul>
<h2>Red flags</h2>
<ul><li>No site visit for a permanent installation.</li><li>No model numbers, or brands that cannot be verified with the manufacturer.</li><li>Installation, commissioning or training missing or described in one line.</li><li>No written warranty or local service arrangement.</li></ul>',
			'faqs'    => array(
				array( 'Why do sound system quotes in Rwanda differ so much in price?', 'Usually because they include different things. One quote may cover design, genuine equipment, installation, commissioning, training and warranty, while another lists only boxes. Compare quotes line by line using the same scope.' ),
				array( 'Should a supplier visit the site before quoting?', 'For a permanent installation, yes. Room size, surfaces, power and cable routes all change the design. A quote without a site survey is an estimate.' ),
				array( 'How can I check that equipment is genuine?', 'Ask for exact models and confirm the supplier with the manufacturer. Yamaha, for example, lists its authorised distributor for Rwanda on its official website.' ),
			),
		),
		'acoustic-treatment-vs-soundproofing' => array(
			'title'   => 'Acoustic Treatment vs Soundproofing: Which Does Your Room Need?',
			'seo'     => 'Acoustic Treatment vs Soundproofing Explained | Sound Creations Rwanda',
			'desc'    => 'Acoustic treatment controls echo inside a room; soundproofing stops sound passing between spaces. How to tell which your church, hall, studio or office needs.',
			'excerpt' => 'Acoustic treatment and soundproofing solve different problems. Acoustic treatment controls how sound behaves inside a room, reducing echo so speech and music are clear. Soundproofing, or sound isolation, stops sound travelling into or out of a room. Many spaces need one, not both.',
			'html'    => '<p><strong>Short answer:</strong> acoustic treatment controls how sound behaves <em>inside</em> a room, reducing echo and reverberation so speech and music are clear. Soundproofing, or sound isolation, stops sound passing <em>into or out of</em> a room. They use different materials and methods, and many spaces need only one of them.</p>
<h2>Acoustic treatment: when the problem is inside the room</h2>
<p>Signs you need treatment: speech is hard to understand even when it is loud, the room "rings" or booms, or microphones pick up a hollow sound in meetings. Hard surfaces such as concrete, glass, tiles and bare ceilings reflect sound, so it keeps bouncing around after the speaker stops.</p>
<p>Treatment uses absorbers (wall and ceiling panels, fabric-wrapped panels, acoustic ceilings), diffusers and bass traps, placed where they control the strongest reflections. A professional approach measures the room first, for example its reverberation time, designs the treatment, installs it and measures again to verify the result.</p>
<h2>Soundproofing: when the problem is between spaces</h2>
<p>Signs you need isolation: neighbours complain about noise from your church, studio or venue, or outside traffic and generators disturb recordings or meetings. Isolation depends on mass, airtightness and separation: heavier or decoupled walls, sealed doors and windows, floating floors and ceilings. Foam or decorative panels on a wall do not stop sound passing through it.</p>
<h2>Which spaces usually need what</h2>
<ul><li><strong>Churches and halls:</strong> mainly treatment for speech clarity; isolation if neighbours are affected. See <a href="/solutions/church-sound-systems/">church sound systems</a>.</li><li><strong>Conference rooms and boardrooms:</strong> treatment, so microphones and remote participants hear clear speech. See <a href="/solutions/conference-room-sound-systems/">conference room systems</a>.</li><li><strong>Recording studios:</strong> both, isolation to keep noise out and treatment for accurate monitoring. See <a href="/solutions/studio-solutions/">studio solutions</a>.</li><li><strong>Bars, clubs and event venues:</strong> isolation to protect neighbours, plus treatment so music is clear at lower levels.</li></ul>
<h2>Why a bigger sound system does not fix echo</h2>
<p>Turning up the volume in a reverberant room adds more reflected sound, which makes speech less clear. Correct loudspeaker coverage and acoustic treatment together are what improve intelligibility. See how we approached a school hall at <a href="/projects/ntare-louisenlund-school/">Ntare Louisenlund School</a>.</p>',
			'faqs'    => array(
				array( 'Will acoustic panels stop my neighbours hearing the music?', 'Not on their own. Panels absorb reflections inside the room. Stopping sound leaving a building needs isolation: mass, sealed openings and decoupled construction.' ),
				array( 'Why is speech unclear in our hall even with good speakers?', 'Hard surfaces cause reverberation that blurs speech. Acoustic treatment combined with loudspeakers aimed to cover the audience evenly is the usual remedy.' ),
				array( 'Do you measure the room before recommending treatment?', 'Yes. We measure, design, treat and then verify, so the treatment is placed where it has the most effect.' ),
			),
		),
		'choosing-pa-system-church-hall-rwanda' => array(
			'title'   => 'How to Choose a PA System for a Church, Hall or Auditorium in Rwanda',
			'seo'     => 'How to Choose a PA System for a Church or Hall in Rwanda',
			'desc'    => 'How to choose a PA system for a church, school hall or auditorium in Rwanda: coverage, speech clarity, mixers, microphones, acoustics, training and support.',
			'excerpt' => 'Choose a PA system for a church, hall or auditorium by starting with the room and how it is used, not with speaker size. The right system covers every seat evenly, keeps speech intelligible, suits the people who will operate it, and comes with installation, training and local support.',
			'html'    => '<p><strong>Short answer:</strong> start with the room and how it is used, not with the size of the speakers. The right PA system covers every seat evenly, keeps speech clear, matches the music you perform, can be run by the people you have, and is installed, tuned and supported locally.</p>
<h2>1. Define the uses</h2>
<p>Preaching and announcements need intelligibility above all. Live worship bands, choirs and concerts need more headroom, low-frequency support and stage monitoring. Streaming and recording need a clean mix feed. Write these down before talking to suppliers.</p>
<h2>2. Coverage before power</h2>
<p>The common mistake is two large speakers at the front, pushed loud enough to reach the back. Front rows get too much level and back rows still struggle to understand. Depending on the room, the answer may be a line array, distributed or delay loudspeakers, or column speakers, chosen so each part of the audience is covered evenly.</p>
<h2>3. Account for the acoustics</h2>
<p>Large halls with hard surfaces are reverberant. Treatment may be needed for clear speech. See <a href="/blog/acoustic-treatment-vs-soundproofing/">acoustic treatment vs soundproofing</a> and <a href="/solutions/architectural-acoustics/">architectural acoustics</a>.</p>
<h2>4. Mixer and microphones for your operators</h2>
<p>Many churches and schools rely on volunteers or staff. A digital mixer with saved scenes, such as a Yamaha console, lets them recall a tested setup every service. Choose wireless, headset and choir microphones for how people actually speak and sing, and plan for enough channels as the team grows.</p>
<h2>5. Monitoring, lighting and streaming</h2>
<p>Worship teams need to hear themselves through wedges or in-ear monitors. Stage lighting and video often follow; plan power and cabling once. See <a href="/solutions/lighting-solutions/">lighting solutions</a>.</p>
<h2>6. Genuine equipment and local support</h2>
<p>Buy through authorised channels so warranty is honoured in Rwanda and spare parts are available. Ask who will tune the system, train operators and respond when something fails.</p>
<h2>Examples from Rwanda</h2>
<p>At <a href="/projects/christian-life-assembly-church/">Christian Life Assembly</a> we installed a dB Technologies line array with subwoofers and stage monitors on an Allen &amp; Heath SQ7 console, with Chauvet lighting. At <a href="/projects/ntare-louisenlund-school/">Ntare Louisenlund School</a> we combined acoustic treatment with a dB Technologies and Yamaha PA. See also <a href="/solutions/church-sound-systems/">church sound systems</a> and <a href="/solutions/school-and-auditorium-sound/">school and auditorium sound</a>.</p>',
			'faqs'    => array(
				array( 'How many speakers does our church need?', 'It depends on the room shape, size, seating and acoustics, not only on the number of people. A site survey and coverage design give the right number and positions.' ),
				array( 'Which mixer is best for volunteer operators?', 'A digital mixer with saved scenes, so a tested setup can be recalled each service. We help choose the channel count and train the team.' ),
				array( 'Do you install outside Kigali?', 'Yes. We survey, deliver and install across Rwanda, including Rubavu, Musanze and Huye.' ),
			),
		),
		'maintaining-commercial-sound-system' => array(
			'title'   => 'How to Maintain a Commercial Sound System: a Practical Guide',
			'seo'     => 'How to Maintain a Commercial Sound System | Sound Creations Rwanda',
			'desc'    => 'Practical maintenance for church, hotel, school and conference sound systems: routine checks, what operators should and should not do, and when to call a technician.',
			'excerpt' => 'A commercial sound system stays reliable with simple routine checks by operators, periodic inspection by a qualified technician, and fast repair with genuine parts. Most failures come from cables, connectors, wireless batteries, heat, dust, power problems and changed settings rather than from the main equipment.',
			'html'    => '<p><strong>Short answer:</strong> keep a sound system reliable with simple routine checks by your operators, periodic inspection and servicing by a qualified technician, and quick repair with genuine parts. Most problems come from cables and connectors, wireless microphone batteries, heat and dust, unstable power and changed settings, not from the main equipment.</p>
<h2>Before every service, event or meeting</h2>
<ul><li>Power on in the agreed order (sources and mixer first, amplifiers or powered speakers last) and off in reverse.</li><li>Recall the saved scene or preset instead of adjusting from scratch.</li><li>Fit fresh or fully charged batteries in wireless microphones.</li><li>Do a quick line check of every microphone and playback source.</li></ul>
<h2>Monthly</h2>
<ul><li>Inspect cables and connectors for damage; replace faulty cables rather than taping them.</li><li>Keep equipment racks ventilated and free of dust.</li><li>Check that settings and passwords are documented and backed up.</li></ul>
<h2>Periodically, by a technician</h2>
<ul><li>Inspect loudspeaker mounts and rigging for safety.</li><li>Test amplifiers, processors and wireless systems, and update firmware where recommended by the manufacturer.</li><li>Re-check system tuning if the room, layout or furniture has changed.</li><li>Review power quality and protection, such as stable supply, surge protection and UPS for critical equipment.</li></ul>
<h2>What operators should not do</h2>
<ul><li>Change processor or amplifier settings that were set at commissioning.</li><li>Run speakers into distortion or clip; it shortens their life.</li><li>Open equipment or attempt repairs, which can void warranty.</li></ul>
<h2>When to call for service</h2>
<p>Call a technician if you hear distortion at normal levels, crackling, hum or buzz, intermittent dropouts, or if any unit runs unusually hot or shows fault indicators. Our <a href="/solutions/service-and-backup/">service and backup team</a> in Kigali handles warranty claims, genuine spare parts, servicing and operator refresher training.</p>',
			'faqs'    => array(
				array( 'How often should a church or hotel sound system be serviced?', 'Operators should do quick checks before every use, and a technician should inspect the system periodically and whenever the room or usage changes. We agree a schedule with each client.' ),
				array( 'Why does our system hum or buzz?', 'Common causes are earthing and power problems, damaged cables or incorrect connections. A technician can trace it safely.' ),
				array( 'Can you service equipment you did not install?', 'Yes. We inspect and service existing systems, and for brands we distribute, such as Yamaha, we provide genuine parts and warranty support in Rwanda.' ),
			),
		),
	);
}

/** Create missing guides as drafts. Never edits an existing post. */
function scrw_seed_guides() {
	$cat_id = 0;
	$term   = term_exists( 'Buyer Guides', 'category' );
	if ( ! $term ) {
		$term = wp_insert_term( 'Buyer Guides', 'category', array( 'slug' => 'buyer-guides' ) );
	}
	if ( is_array( $term ) && ! empty( $term['term_id'] ) ) {
		$cat_id = (int) $term['term_id'];
	}
	foreach ( scrw_guides() as $slug => $g ) {
		$found = get_posts(
			array(
				'post_type'      => 'post',
				'name'           => $slug,
				'post_status'    => array( 'publish', 'draft', 'pending', 'private', 'future', 'trash' ),
				'posts_per_page' => 1,
				'fields'         => 'ids',
			)
		);
		if ( $found ) {
			continue;
		}
		$id = wp_insert_post(
			array(
				'post_title'    => $g['title'],
				'post_name'     => $slug,
				'post_excerpt'  => $g['excerpt'],
				'post_content'  => $g['html'] . scrw_guide_faq_html( $g['faqs'] ) . scrw_guide_cta(),
				'post_status'   => 'draft',
				'post_type'     => 'post',
				'post_category' => $cat_id ? array( $cat_id ) : array(),
			)
		);
		if ( $id && ! is_wp_error( $id ) ) {
			update_post_meta( $id, 'rank_math_title', $g['seo'] );
			update_post_meta( $id, 'rank_math_description', $g['desc'] );
			update_post_meta( $id, '_scrw_guide_needs_review', '1' );
		}
	}
	return true;
}

add_action(
	'admin_init',
	function () {
		if ( get_option( 'scrw_guides_ver' ) === SCRW_GUIDES_VERSION ) {
			return;
		}
		if ( scrw_seed_guides() ) {
			update_option( 'scrw_guides_ver', SCRW_GUIDES_VERSION );
		}
	},
	70
);

/* Admin reminder until every seeded guide has been published or deleted. */
add_action(
	'admin_notices',
	function () {
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}
		$drafts = get_posts(
			array(
				'post_type'      => 'post',
				'post_status'    => 'draft',
				'meta_key'       => '_scrw_guide_needs_review', // phpcs:ignore WordPress.DB.SlowDBQuery
				'posts_per_page' => 10,
				'fields'         => 'ids',
			)
		);
		if ( ! $drafts ) {
			return;
		}
		printf(
			'<div class="notice notice-info"><p>%s <a href="%s">%s</a></p></div>',
			esc_html( sprintf( 'Sound Creations: %d buyer guide draft(s) are ready for an engineer to review, add real photos and publish.', count( $drafts ) ) ),
			esc_url( admin_url( 'edit.php?post_status=draft&post_type=post' ) ),
			esc_html__( 'Review drafts', 'soundcreations' )
		);
	}
);
