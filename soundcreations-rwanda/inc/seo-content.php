<?php
/**
 * Rwanda search content (v1.3.0): sector landing pages, visible FAQs and
 * internal links for the searches buyers in Rwanda actually make.
 *
 * WHY
 * ---
 * The site had strong pages for brands (Yamaha, FANE) and disciplines
 * (professional audio, acoustics, lighting) but nothing that answered the
 * sector questions buyers type: "conference room sound system Kigali",
 * "church sound system Rwanda", "school PA system Rwanda", "hotel / bar
 * sound system Kigali". Competitors in Kigali mostly market event
 * production and rental; the opportunity here is permanent, engineered
 * installations backed by real Rwandan projects. Each page below has ONE
 * primary search intent and is built on a real project already published on
 * the site, so none of them is a doorway page.
 *
 * TITLES
 * ------
 * The parent template picks a layout from the solution title. A title that
 * contains "audio" hides the body text; "acoustic", "install", "integ" or
 * "visual" switch to other layouts. These titles deliberately avoid those
 * words so the body and FAQ always render.
 *
 * SAFETY
 * ------
 * Seeded once per version. A page that already exists is never overwritten,
 * and FAQs are only written into pages whose FAQ box is still empty. Every
 * FAQ is printed visibly on the page by Core (the_content filter), which is
 * what Google requires for FAQPage markup.
 *
 * @package SoundCreationsRwanda
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SCRW_SEO_CONTENT_VERSION', 'rw-seo-content-1' );

/** Shared call to action. */
function scrw_seo_cta() {
	return '<h2>Talk to our Kigali team</h2><p>Visit the showroom on KN1 Rd, Muhima, call <a href="tel:+250783141050">+250 783 141 050</a> or <a href="tel:+250782739889">+250 782 739 889</a>, message us on <a href="https://wa.me/250783141050">WhatsApp</a>, or <a href="/request-a-consultation/">book a site consultation</a>. Prefer a price first? <a href="/request-a-quote/">Request a quote</a>.</p>';
}

/** Related links block so every sector page links into the rest of the site. */
function scrw_seo_related( $links ) {
	$html = '<h2>Related</h2><ul>';
	foreach ( $links as $label => $url ) {
		$html .= '<li><a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a></li>';
	}
	return $html . '</ul>';
}

/**
 * Sector landing pages. slug => title, excerpt, html, faqs, projects.
 */
function scrw_sector_pages() {
	return array(
		'conference-room-sound-systems' => array(
			'title'    => 'Conference Room & Boardroom Sound Systems',
			'excerpt'  => 'Conference microphones, ceiling and table microphone systems, loudspeakers and hybrid-meeting audio for boardrooms, ministries, hotels and training rooms in Kigali and across Rwanda.',
			'projects' => array( 'minecofin-conference-system' ),
			'html'     => '<p>In a meeting room the only thing that matters is that every person, in the room and on the call, hears every word. Sound Creations Ltd Rwanda designs, supplies and installs conference and boardroom sound systems for government offices, corporate headquarters, hotels, NGOs and training centres in Kigali and across Rwanda.</p>
<h2>Proven at the Ministry of Finance</h2>
<p>For Rwanda\'s Ministry of Finance and Economic Planning (MINECOFIN) we delivered a Shure Microflex Complete conferencing system, giving delegates clear audio and simple meeting control. <a href="/projects/minecofin-conference-system/">See the MINECOFIN project</a>.</p>
<h2>What we supply and install</h2>
<ul>
<li><strong>Conference microphone systems</strong> - delegate and chairman units with push-to-talk and speaker control, wired or wireless.</li>
<li><strong>Ceiling and table array microphones</strong> - clean tables, full-room pickup for hybrid meetings.</li>
<li><strong>Loudspeakers and voice reinforcement</strong> - even, intelligible speech to every seat, including large council and training rooms.</li>
<li><strong>Hybrid meeting audio</strong> - USB and network connection to Zoom, Microsoft Teams and Google Meet, with echo cancellation, plus conference cameras such as AVer.</li>
<li><strong>Room acoustics</strong> - absorption to stop echo, because no microphone can fix a reverberant room. See <a href="/solutions/architectural-acoustics/">architectural acoustics</a>.</li>
</ul>
<h2>How a project runs</h2>
<p>Site survey in Kigali or anywhere in Rwanda, system design sized to the room and the number of delegates, supply of genuine equipment with manufacturer warranty, installation and tidy cabling, tuning, and training for the people who will run the meetings. After handover our local <a href="/solutions/service-and-backup/">service team</a> keeps the system working.</p>
' . scrw_seo_related(
				array(
					'Shure microphones and conferencing products' => '/products/',
					'Sound system installation in Rwanda'         => '/solutions/installation/',
					'Professional audio and PA systems'           => '/solutions/professional-audio/',
				)
			) . scrw_seo_cta(),
			'faqs'     => array(
				array( 'q' => 'Who installs conference room sound systems in Kigali?', 'a' => 'Sound Creations Ltd Rwanda designs, supplies and installs conference and boardroom audio from its Kigali office on KN1 Rd, Muhima, including the Shure Microflex Complete system at MINECOFIN.' ),
				array( 'q' => 'Do conference systems work with Zoom and Microsoft Teams?', 'a' => 'Yes. We connect the room microphones and loudspeakers to your meeting PC or video bar by USB or network, with echo cancellation, so remote participants hear the room clearly.' ),
				array( 'q' => 'Wired or wireless conference microphones?', 'a' => 'Wireless delegate units suit rooms that change layout or where cabling is difficult; wired units suit fixed council and boardroom tables. We recommend after seeing the room.' ),
				array( 'q' => 'Why does our meeting room echo, and can you fix it?', 'a' => 'Hard walls, glass and tiled floors make speech reverberate. We measure the room and add acoustic panels or ceiling absorbers so microphones pick up clear speech.' ),
				array( 'q' => 'How long does a boardroom installation take?', 'a' => 'A typical boardroom takes a few days on site once equipment is available. Larger council chambers take longer; we agree the timeline after the site survey.' ),
			),
		),
		'church-sound-systems'          => array(
			'title'    => 'Church Sound Systems & Worship Lighting',
			'excerpt'  => 'Church sound systems, line arrays, mixers, wireless microphones, stage lighting and acoustic treatment for churches and houses of worship in Kigali and across Rwanda.',
			'projects' => array( 'christian-life-assembly-church' ),
			'html'     => '<p>A church needs two things from its sound system: every word of the sermon understood in every seat, and worship music that sounds full without being painfully loud. Sound Creations Ltd Rwanda designs, supplies and installs church sound, lighting and acoustic treatment for congregations of every size in Kigali and across Rwanda.</p>
<h2>Proven at Christian Life Assembly, Kigali</h2>
<p>At Christian Life Assembly we delivered line-array sound, beam lighting and screens for the main stage. <a href="/projects/christian-life-assembly-church/">See the Christian Life Assembly project</a>.</p>
<h2>What we supply and install</h2>
<ul>
<li><strong>Main PA and line arrays</strong> - coverage designed for your hall so the back rows hear as clearly as the front.</li>
<li><strong>Mixing consoles</strong> - Yamaha and other digital mixers that volunteers can learn quickly, with saved scenes for each service. We are the <a href="/yamaha/">authorised Yamaha distributor in Rwanda</a>.</li>
<li><strong>Wireless and choir microphones</strong> - reliable handheld, headset and choir microphones, including Shure.</li>
<li><strong>Stage monitoring</strong> - wedges or in-ear monitoring for worship teams.</li>
<li><strong>Worship lighting</strong> - stage wash, beams and DMX control. See <a href="/solutions/lighting-solutions/">lighting solutions</a>.</li>
<li><strong>Streaming audio</strong> - a clean feed for online services and radio.</li>
<li><strong>Acoustic treatment</strong> - controlling echo in large halls so speech is clear. See <a href="/solutions/architectural-acoustics/">architectural acoustics</a>.</li>
<li><strong>Musical instruments</strong> - Yamaha keyboards, digital pianos and guitars from our Kigali showroom.</li>
</ul>
<h2>Training your team</h2>
<p>Most church systems are run by volunteers. Every installation includes hands-on training, and our <a href="/solutions/service-and-backup/">service team in Kigali</a> is on hand afterwards.</p>
' . scrw_seo_related(
				array(
					'Professional audio and PA systems' => '/solutions/professional-audio/',
					'Sound system installation'         => '/solutions/installation/',
					'Yamaha Rwanda'                     => '/yamaha/',
				)
			) . scrw_seo_cta(),
			'faqs'     => array(
				array( 'q' => 'Where can I buy a church sound system in Kigali?', 'a' => 'At Sound Creations Ltd Rwanda, KN1 Rd, Muhima, Kigali. We supply and install complete church systems, from microphones and mixers to line arrays and lighting, as at Christian Life Assembly.' ),
				array( 'q' => 'Why is the sermon hard to understand at the back of our church?', 'a' => 'Usually the loudspeakers do not cover the room evenly, or the hall is too reverberant. We survey the church, design coverage for every seat and add acoustic treatment where needed.' ),
				array( 'q' => 'Which mixer is best for a church run by volunteers?', 'a' => 'A digital mixer with saved scenes, such as a Yamaha console, lets volunteers recall a tested setup each service. We help choose the model for your channel count and train the team.' ),
				array( 'q' => 'Do you install church lighting as well as sound?', 'a' => 'Yes. We design and install stage wash, beam and effects lighting with DMX control, and programme scenes for worship, preaching and events.' ),
				array( 'q' => 'Do you work with churches outside Kigali?', 'a' => 'Yes. We survey, deliver and install across Rwanda, including Rubavu, Musanze and Huye.' ),
			),
		),
		'school-and-auditorium-sound'   => array(
			'title'    => 'School, University & Auditorium Sound Systems',
			'excerpt'  => 'PA systems, auditorium sound, stage lighting, projection and acoustic treatment for schools, universities, halls and conference arenas in Rwanda.',
			'projects' => array( 'ntare-louisenlund-school', 'intare-kivu-arena' ),
			'html'     => '<p>School halls and auditoriums host assemblies, exams announcements, graduations, drama and concerts, often in the same week. Sound Creations Ltd Rwanda designs and installs sound, lighting, projection and acoustic treatment that is easy for staff to run and built to last.</p>
<h2>Proven in Rwanda</h2>
<ul>
<li><strong>Ntare Louisenlund School</strong> - auditorium with lighting truss, loudspeakers and projection. <a href="/projects/ntare-louisenlund-school/">See the project</a>.</li>
<li><strong>Intare Kivu Arena, Rubavu</strong> - acoustic treatment with timber wall diffusers and ceiling absorbers, plus the sound system. <a href="/projects/intare-kivu-arena/">See the project</a>.</li>
</ul>
<h2>What we supply and install</h2>
<ul>
<li><strong>School PA systems</strong> - assembly and sports-ground PA, paging and announcements.</li>
<li><strong>Auditorium and hall sound</strong> - speech-first loudspeaker design with enough headroom for music and performances.</li>
<li><strong>Lecture room audio</strong> - lectern and wireless microphones, voice lift and lecture capture.</li>
<li><strong>Stage lighting and projection</strong> - for drama, concerts and graduations.</li>
<li><strong>Acoustics</strong> - treatment that makes large halls intelligible. See <a href="/solutions/architectural-acoustics/">architectural acoustics</a>.</li>
<li><strong>Music rooms</strong> - Yamaha keyboards, digital pianos and instruments from the <a href="/yamaha/">authorised Yamaha distributor in Rwanda</a>.</li>
</ul>
' . scrw_seo_related(
				array(
					'Sound system installation in Rwanda' => '/solutions/installation/',
					'Lighting solutions'                  => '/solutions/lighting-solutions/',
					'All projects'                        => '/projects/',
				)
			) . scrw_seo_cta(),
			'faqs'     => array(
				array( 'q' => 'Who supplies school PA systems in Rwanda?', 'a' => 'Sound Creations Ltd Rwanda designs, supplies and installs school PA, hall and auditorium systems across Rwanda, including the auditorium at Ntare Louisenlund School.' ),
				array( 'q' => 'Can teachers run the system without a sound engineer?', 'a' => 'Yes. We design simple controls and preset scenes, label everything and train staff at handover.' ),
				array( 'q' => 'Our hall echoes badly. Will bigger speakers help?', 'a' => 'Usually not. Echo is a room problem; louder speakers make it worse. Acoustic treatment, as at Intare Kivu Arena, combined with correctly aimed loudspeakers is the fix.' ),
				array( 'q' => 'Do you supply instruments for school music departments?', 'a' => 'Yes. As the authorised Yamaha distributor in Rwanda we supply Yamaha keyboards, digital pianos, guitars and more, with warranty.' ),
			),
		),
		'hotel-bar-restaurant-sound'    => array(
			'title'    => 'Hotel, Bar, Restaurant & Event Venue Sound',
			'excerpt'  => 'Background music, zoned sound, DJ booths, lighting and event sound for hotels, bars, restaurants, lounges and event gardens in Kigali and Rwanda.',
			'projects' => array( 'romantic-garden', 'atelier-du-vin', 'jalia-events-venue' ),
			'html'     => '<p>Guests notice sound when it is wrong: too loud to talk, muddy, or dead in one corner. Sound Creations Ltd Rwanda designs and installs hospitality sound that sets the mood in every zone and scales up for events, for hotels, bars, restaurants, lounges and event gardens in Kigali and across Rwanda.</p>
<h2>Proven in Kigali</h2>
<p>Warm, even coverage for venues such as <a href="/projects/romantic-garden/">Romantic Garden</a> and <a href="/projects/atelier-du-vin/">Atelier du Vin</a>.</p>
<h2>What we supply and install</h2>
<ul>
<li><strong>Zoned background music</strong> - independent volume and source for restaurant, bar, lobby, pool and terrace.</li>
<li><strong>Event and conference sound</strong> - for hotel ballrooms, meeting rooms and garden weddings. See <a href="/solutions/conference-room-sound-systems/">conference room sound</a>.</li>
<li><strong>DJ booths</strong> - mixers, controllers and monitoring. See <a href="/solutions/dj-solutions/">DJ solutions</a>.</li>
<li><strong>Lighting</strong> - ambient, architectural and event lighting. See <a href="/solutions/lighting-solutions/">lighting solutions</a>.</li>
<li><strong>Noise control</strong> - keeping music inside and neighbours happy. See <a href="/solutions/architectural-acoustics/">acoustics and soundproofing</a>.</li>
</ul>
' . scrw_seo_related(
				array(
					'Professional audio and PA systems' => '/solutions/professional-audio/',
					'Brands we supply'                  => '/brands/',
					'All projects'                      => '/projects/',
				)
			) . scrw_seo_cta(),
			'faqs'     => array(
				array( 'q' => 'Who installs sound systems for hotels and bars in Kigali?', 'a' => 'Sound Creations Ltd Rwanda designs and installs hospitality sound in Kigali, including projects at Romantic Garden and Atelier du Vin.' ),
				array( 'q' => 'Can different areas play different music?', 'a' => 'Yes. A zoned system lets the restaurant, bar, lobby and terrace each have their own source and volume from simple wall controls or an app.' ),
				array( 'q' => 'How do we stop noise complaints from neighbours?', 'a' => 'We aim loudspeakers away from boundaries, set volume limits and add sound insulation where needed, after measuring the site.' ),
				array( 'q' => 'Can the system handle weddings and live events?', 'a' => 'Yes. We design inputs for DJs, bands and microphones so the venue can host events without hiring extra sound each time.' ),
			),
		),
	);
}

/** FAQs for existing solution pages that render their body text. */
function scrw_existing_page_faqs() {
	return array(
		'architectural-acoustics' => array(
			array( 'q' => 'Where can I get acoustic treatment in Kigali?', 'a' => 'Sound Creations Ltd Rwanda measures, designs and installs acoustic treatment across Rwanda from its Kigali office, as at Intare Kivu Arena in Rubavu.' ),
			array( 'q' => 'What is the difference between acoustic treatment and soundproofing?', 'a' => 'Acoustic treatment controls echo inside a room so speech and music are clear. Soundproofing stops sound passing between rooms or to neighbours. Many projects need both.' ),
			array( 'q' => 'Do you measure the room before and after?', 'a' => 'Yes. We measure reverberation time (RT60) and background noise before treatment and again afterwards, so the improvement is verified in numbers.' ),
			array( 'q' => 'Do you work with architects on new buildings?', 'a' => 'Yes. Involving acoustics at design stage is the most cost-effective approach, and we work alongside architects and contractors in Rwanda.' ),
		),
		'lighting-solutions'      => array(
			array( 'q' => 'Who supplies stage lighting in Rwanda?', 'a' => 'Sound Creations Ltd Rwanda designs, supplies, installs and programmes stage, church, event and architectural lighting from Kigali.' ),
			array( 'q' => 'Do you programme the lighting and train our operators?', 'a' => 'Yes. We programme scenes on the DMX console and train your team so they can run services and events confidently.' ),
			array( 'q' => 'Can you light a church as well as a concert stage?', 'a' => 'Yes. Worship lighting, as at Christian Life Assembly in Kigali, uses the same fixtures and control as concert lighting, designed around how the space is used.' ),
		),
		'dj-solutions'            => array(
			array( 'q' => 'Where can I buy DJ equipment in Kigali?', 'a' => 'At the Sound Creations Ltd Rwanda showroom on KN1 Rd, Muhima, Kigali, with genuine equipment, manufacturer warranty and local support.' ),
			array( 'q' => 'Do you install complete DJ booths for clubs and hotels?', 'a' => 'Yes. We design and install DJ booths integrated with the venue sound and lighting.' ),
		),
		'studio-solutions'        => array(
			array( 'q' => 'Who builds recording studios in Rwanda?', 'a' => 'Sound Creations Ltd Rwanda designs, treats, equips and commissions recording, broadcast, podcast and church streaming studios across Rwanda.' ),
			array( 'q' => 'Do I need acoustic treatment for a home or podcast studio?', 'a' => 'Yes, for accurate recordings. Even a small room benefits from absorption and bass control; we size treatment to the room and budget.' ),
		),
		'service-and-backup'      => array(
			array( 'q' => 'Do you repair sound systems in Kigali?', 'a' => 'Yes. Our Kigali team diagnoses and repairs audio, lighting and AV equipment, handles manufacturer warranty claims and supplies genuine parts.' ),
			array( 'q' => 'Do you offer maintenance contracts?', 'a' => 'Yes. Scheduled preventive maintenance, firmware updates and system checks keep critical systems such as churches and conference rooms reliable.' ),
		),
	);
}

function scrw_seed_seo_content() {
	if ( ! post_type_exists( 'sc_solution' ) ) {
		return false;
	}
	$order = 20;
	foreach ( scrw_sector_pages() as $slug => $p ) {
		$found = get_posts(
			array(
				'post_type'      => 'sc_solution',
				'name'           => $slug,
				'post_status'    => array( 'publish', 'draft', 'pending', 'private', 'future', 'trash' ),
				'posts_per_page' => 1,
			)
		);
		if ( $found ) {
			$id = (int) $found[0]->ID; // Exists: never overwrite an editor's page.
		} else {
			$id = wp_insert_post(
				array(
					'post_title'   => $p['title'],
					'post_name'    => $slug,
					'post_excerpt' => $p['excerpt'],
					'post_content' => $p['html'],
					'post_status'  => 'publish',
					'post_type'    => 'sc_solution',
					'menu_order'   => $order,
				)
			);
		}
		++$order;
		if ( $id && ! is_wp_error( $id ) && empty( get_post_meta( $id, '_sc_faq', true ) ) ) {
			update_post_meta( $id, '_sc_faq', $p['faqs'] );
		}
	}
	foreach ( scrw_existing_page_faqs() as $slug => $rows ) {
		$post = get_page_by_path( $slug, OBJECT, 'sc_solution' );
		if ( $post && empty( get_post_meta( $post->ID, '_sc_faq', true ) ) ) {
			update_post_meta( $post->ID, '_sc_faq', $rows );
		}
	}
	flush_rewrite_rules( false );
	return true;
}

add_action(
	'admin_init',
	function () {
		if ( get_option( 'scrw_seo_content_ver' ) === SCRW_SEO_CONTENT_VERSION ) {
			return;
		}
		if ( scrw_seed_seo_content() ) {
			update_option( 'scrw_seo_content_ver', SCRW_SEO_CONTENT_VERSION );
		}
	},
	60
);

/* Project strip under each sector page (internal links to the proof). */
add_action(
	'sc_solution_after_kind',
	function () {
		if ( ! is_singular( 'sc_solution' ) || ! function_exists( 'sc_media_card' ) ) {
			return;
		}
		$slug  = get_post_field( 'post_name', get_queried_object_id() );
		$pages = scrw_sector_pages();
		if ( empty( $pages[ $slug ]['projects'] ) ) {
			return;
		}
		$q = new WP_Query(
			array(
				'post_type'      => 'sc_project',
				'post_status'    => 'publish',
				'post_name__in'  => $pages[ $slug ]['projects'],
				'orderby'        => 'post_name__in',
				'posts_per_page' => 3,
				'no_found_rows'  => true,
			)
		);
		if ( ! $q->have_posts() ) {
			return;
		}
		echo '<section class="sc-section sc-section--tight"><div class="sc-container"><div class="sc-section__head"><div><p class="sc-eyebrow">' . esc_html__( 'Our work in Rwanda', 'soundcreations-rwanda' ) . '</p><h2 class="sc-svc-h2" style="margin:0;">' . esc_html__( 'Related projects', 'soundcreations-rwanda' ) . '</h2></div><a class="sc-link-arrow" href="' . esc_url( home_url( '/projects/' ) ) . '">' . esc_html__( 'View All Projects', 'soundcreations-rwanda' ) . ' &rarr;</a></div><div class="sc-grid">';
		while ( $q->have_posts() ) {
			$q->the_post();
			echo sc_media_card( get_the_ID(), function_exists( 'sc_field' ) ? sc_field( 'client' ) : '', function_exists( 'sc_field' ) ? sc_field( 'year' ) : '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- theme helper returns escaped markup.
		}
		wp_reset_postdata();
		echo '</div></div></section>';
	},
	15
);

/* Audience for each sector page's Service node. */
function scrw_sector_audience( $slug ) {
	$map = array(
		'conference-room-sound-systems' => 'Government offices, corporate boardrooms, hotels and training centres',
		'church-sound-systems'          => 'Churches and houses of worship',
		'school-and-auditorium-sound'   => 'Schools, universities and auditoriums',
		'hotel-bar-restaurant-sound'    => 'Hotels, bars, restaurants and event venues',
	);
	return isset( $map[ $slug ] ) ? $map[ $slug ] : '';
}
