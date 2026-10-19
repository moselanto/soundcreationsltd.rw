<?php
/**
 * Homepage "Audio visual company in Rwanda" section with visible FAQs and
 * matching FAQPage schema (2.3.18). Targets the priority searches:
 * audio visual company Rwanda / Kigali, AV and event technology companies in
 * Rwanda, sound system installation, acoustic solutions, church sound.
 *
 * Everything is editable in wp-admin: Settings > Homepage FAQ.
 *   - Heading and intro text.
 *   - Questions, one per line: Question | Answer
 * Clear the questions box and save to hide the section.
 *
 * @package SoundCreationsRwanda
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function scrw_home_faq_defaults() {
	return array(
		'title' => 'Audio visual company in Rwanda',
		'lead'  => 'Sound Creations Ltd Rwanda is an audio visual company in Kigali. We design, supply, install and support professional sound systems, acoustic solutions, stage lighting and AV integration for churches, hotels, conference rooms, schools, government offices and event venues across Rwanda.',
		'links' => "Sound system installation | /solutions/installation/\nAcoustic solutions | /solutions/architectural-acoustics/\nChurch sound systems | /solutions/church-sound-systems/\nConference room AV | /solutions/conference-room-sound-systems/\nStage and event lighting | /solutions/lighting-solutions/\nYamaha distributor in Rwanda | /yamaha/",
		'faq'   => implode(
			"\n",
			array(
				'Which audio visual and event technology companies operate in Rwanda? | Several companies provide audio visual and event technology services in Rwanda. Sound Creations Ltd Rwanda is a Kigali AV company that designs, supplies, installs and supports professional sound, acoustic, lighting and AV systems, and is the authorised Yamaha distributor in Rwanda. Our showroom is on KN1 Rd, Muhima, Kigali.',
				'What does an audio visual company in Kigali do? | An AV company plans and delivers the sound, video, lighting and acoustic systems a space needs. At Sound Creations that means a site visit, system design, supply of genuine equipment, installation, calibration, operator training and after-sales support.',
				'Do you install sound systems for churches, hotels and conference rooms? | Yes. We install church sound systems, hotel and venue audio, conference room and boardroom AV, and school and auditorium systems. Projects include Christian Life Assembly, MINECOFIN and Intare Kivu Arena.',
				'Do you provide acoustic solutions in Rwanda? | Yes. We measure rooms and design acoustic treatment, which improves sound inside a room, and sound isolation, which stops sound travelling between spaces. We work with architects on new buildings and fix echo in existing halls.',
				'Do you work with event companies? | Yes. Event production companies in Kigali buy equipment from us and use our repair, servicing and technical support, depending on their needs.',
				'Do you work outside Kigali? | Yes. We deliver, install and support systems across Rwanda, including Rubavu, Musanze and Huye, and support projects in the DR Congo and East Africa.',
				'How do I get a quote? | Request a consultation on this website, call +250 783 141 050 or message us on WhatsApp. We will arrange a site visit if needed and send a detailed proposal.',
			)
		),
	);
}

/** Saved value, or the default when the field has never been saved. */
function scrw_home_faq_get( $key ) {
	$d   = scrw_home_faq_defaults();
	$val = get_option( 'scrw_home_faq_' . $key, null );
	return null === $val ? $d[ $key ] : (string) $val;
}

function scrw_home_faq_rows() {
	$out = array();
	foreach ( preg_split( '/\r\n|\r|\n/', scrw_home_faq_get( 'faq' ) ) as $line ) {
		$parts = array_map( 'trim', explode( '|', $line, 2 ) );
		if ( empty( $parts[0] ) || empty( $parts[1] ) ) {
			continue;
		}
		$out[] = array( 'q' => $parts[0], 'a' => $parts[1] );
	}
	return $out;
}

function scrw_home_faq_links() {
	$out = array();
	foreach ( preg_split( '/\r\n|\r|\n/', scrw_home_faq_get( 'links' ) ) as $line ) {
		$parts = array_map( 'trim', explode( '|', $line, 2 ) );
		if ( ! empty( $parts[0] ) && ! empty( $parts[1] ) ) {
			$out[] = $parts;
		}
	}
	return $out;
}

add_action(
	'sc_home_after_projects',
	function () {
		$rows = scrw_home_faq_rows();
		if ( ! $rows ) {
			return;
		}
		$title = scrw_home_faq_get( 'title' );
		$lead  = scrw_home_faq_get( 'lead' );
		$links = scrw_home_faq_links();
		$cta_l = function_exists( 'sc_setting' ) ? sc_setting( 'home_hero_cta1_label', 'Request a Consultation' ) : 'Request a Consultation';
		$cta_u = function_exists( 'sc_setting' ) ? sc_setting( 'home_hero_cta1_url', '/request-a-consultation/' ) : '/request-a-consultation/';
		$wa    = function_exists( 'sc_whatsapp_url' ) ? sc_whatsapp_url() : '';
		echo '<section class="sc-section scrw-hfaq" id="av-company-rwanda"><div class="sc-container"><div class="scrw-hfaq__wrap">';

		echo '<div class="scrw-hfaq__intro">';
		echo '<p class="scrw-hfaq__kicker">' . esc_html__( 'Who we are', 'soundcreations-rwanda' ) . '</p>';
		if ( '' !== $title ) {
			echo '<h2 class="scrw-hfaq__title">' . esc_html( $title ) . '</h2>';
		}
		if ( '' !== $lead ) {
			echo '<p class="scrw-hfaq__lead">' . esc_html( $lead ) . '</p>';
		}
		if ( $links ) {
			echo '<ul class="scrw-hfaq__links">';
			foreach ( $links as $l ) {
				echo '<li><a href="' . esc_url( $l[1] ) . '">' . esc_html( $l[0] ) . '</a></li>';
			}
			echo '</ul>';
		}
		echo '<div class="scrw-hfaq__ctas">';
		if ( $cta_l && $cta_u ) {
			echo '<a class="sc-btn sc-btn--primary" href="' . esc_url( $cta_u ) . '">' . esc_html( $cta_l ) . '</a>';
		}
		if ( $wa ) {
			echo '<a class="sc-btn sc-btn--ghost" href="' . esc_url( $wa ) . '" target="_blank" rel="noopener">' . esc_html__( 'Chat on WhatsApp', 'soundcreations-rwanda' ) . '</a>';
		}
		echo '</div></div>';

		echo '<div class="scrw-hfaq__qa"><p class="scrw-hfaq__kicker">' . esc_html__( 'Frequently asked questions', 'soundcreations-rwanda' ) . '</p><div class="scrw-hfaq__list">';
		foreach ( $rows as $i => $r ) {
			echo '<details class="scrw-hfaq__item"' . ( 0 === $i ? ' open' : '' ) . '><summary><span class="scrw-hfaq__num" aria-hidden="true">' . esc_html( sprintf( '%02d', $i + 1 ) ) . '</span><h3>' . esc_html( $r['q'] ) . '</h3></summary><p>' . esc_html( $r['a'] ) . '</p></details>';
		}
		echo '</div></div>';

		echo '</div></div></section>';
	},
	30
);

add_action(
	'wp_head',
	function () {
		if ( ! is_front_page() ) {
			return;
		}
		$rows = scrw_home_faq_rows();
		if ( ! $rows ) {
			return;
		}
		$items = array();
		foreach ( $rows as $r ) {
			$items[] = array(
				'@type'          => 'Question',
				'name'           => $r['q'],
				'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $r['a'] ),
			);
		}
		$data = array(
			'@context'   => 'https://schema.org',
			'@type'      => 'FAQPage',
			'@id'        => home_url( '/#faq' ),
			'mainEntity' => $items,
		);
		echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
	},
	30
);

/* Admin: Settings > Homepage FAQ. */
add_action(
	'admin_init',
	function () {
		register_setting( 'scrw_home_faq_group', 'scrw_home_faq_title', array( 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field' ) );
		register_setting( 'scrw_home_faq_group', 'scrw_home_faq_lead', array( 'type' => 'string', 'sanitize_callback' => 'sanitize_textarea_field' ) );
		register_setting( 'scrw_home_faq_group', 'scrw_home_faq_links', array( 'type' => 'string', 'sanitize_callback' => 'sanitize_textarea_field' ) );
		register_setting( 'scrw_home_faq_group', 'scrw_home_faq_faq', array( 'type' => 'string', 'sanitize_callback' => 'sanitize_textarea_field' ) );
	}
);

add_action(
	'admin_menu',
	function () {
		add_options_page(
			'Homepage FAQ',
			'Homepage FAQ',
			'manage_options',
			'scrw-home-faq',
			function () {
				if ( ! current_user_can( 'manage_options' ) ) {
					return;
				}
				?>
				<div class="wrap">
					<h1>Homepage FAQ</h1>
					<p>The "Audio visual company in Rwanda" section near the bottom of the homepage. Google and AI assistants read these questions and answers. One question per line: <code>Question | Answer</code>. Clear the questions box and save to hide the section.</p>
					<form method="post" action="options.php">
						<?php settings_fields( 'scrw_home_faq_group' ); ?>
						<table class="form-table" role="presentation"><tbody>
							<tr><th scope="row"><label for="scrw_home_faq_title">Heading</label></th><td><input class="large-text" id="scrw_home_faq_title" name="scrw_home_faq_title" value="<?php echo esc_attr( scrw_home_faq_get( 'title' ) ); ?>"></td></tr>
							<tr><th scope="row"><label for="scrw_home_faq_lead">Intro text</label></th><td><textarea class="large-text" rows="4" id="scrw_home_faq_lead" name="scrw_home_faq_lead"><?php echo esc_textarea( scrw_home_faq_get( 'lead' ) ); ?></textarea></td></tr>
							<tr><th scope="row"><label for="scrw_home_faq_links">Service links</label></th><td><textarea class="large-text code" rows="7" id="scrw_home_faq_links" name="scrw_home_faq_links"><?php echo esc_textarea( scrw_home_faq_get( 'links' ) ); ?></textarea><p class="description">One per line: <code>Label | /page-address/</code>. Shown under the intro. The buttons use the homepage button settings and the WhatsApp number.</p></td></tr>
							<tr><th scope="row"><label for="scrw_home_faq_faq">Questions</label></th><td><textarea class="large-text code" rows="16" id="scrw_home_faq_faq" name="scrw_home_faq_faq"><?php echo esc_textarea( scrw_home_faq_get( 'faq' ) ); ?></textarea></td></tr>
						</tbody></table>
						<?php submit_button(); ?>
					</form>
				</div>
				<?php
			}
		);
	}
);
