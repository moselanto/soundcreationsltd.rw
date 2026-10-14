<?php
/**
 * One consistent business story across website schema and Google Business
 * Profile (v1.8.0, 14 Oct 2026).
 *
 *  1. The Kigali LocalBusiness node gets hasOfferCatalog with the SAME 14
 *     services, names and descriptions that are on the Google Business
 *     Profile, each linked to its matching page on this site. Google can then
 *     tie the Maps listing, the website and the services together.
 *  2. Organization and LocalBusiness get sameAs links to the official Rwanda
 *     social profiles and alternateName "Sound Creations Rwanda" (the Business
 *     Profile name).
 *  3. The five price-guide pages get FAQPage (the questions shown on the
 *     page) and an ItemList of the products they link to.
 *
 * No prices are published: the business is inquiry-based.
 *
 * @package SoundCreationsRwanda
 */

if ( defined( 'ABSPATH' ) === false ) {
	exit;
}

/** Services exactly as listed on the Google Business Profile. */
function scrw_gbp_services() {
	$led = get_page_by_path( 'led-screens', OBJECT, 'sc_solution' );
	return array(
		array( 'Sound system design and installation', 'Professional sound and PA system design and installation in Rwanda: line arrays, speakers, wireless microphones and mixers for churches, halls, hotels and events in Kigali. Site visit, design, installation, tuning and training by our certified team.', '/solutions/installation/' ),
		array( 'Church sound systems', 'Church sound systems in Rwanda: clear speech PA, worship band audio, wireless microphones, digital mixers and worship lighting, designed and installed in Kigali and across Rwanda by Sound Creations.', '/solutions/church-sound-systems/' ),
		array( 'Conference room and boardroom AV', 'Conference room and boardroom AV in Kigali: delegate and ceiling microphones, ceiling speakers and AVer video conferencing cameras for Zoom and Microsoft Teams rooms, for offices, NGOs and government.', '/solutions/conference-room-sound-systems/' ),
		array( 'Hotel, restaurant and event venue sound', 'Background music, paging and live sound systems for hotels, bars, restaurants and event venues in Kigali and across Rwanda, with zoned music and simple controls.', '/solutions/hotel-bar-restaurant-sound/' ),
		array( 'School and auditorium sound systems', 'School, university and auditorium sound systems in Rwanda: clear speech for classrooms, halls and graduation venues, with wireless microphones, speakers and mixers.', '/solutions/school-and-auditorium-sound/' ),
		array( 'LED screens and video walls', 'Indoor and outdoor LED screens and video walls in Rwanda: supply, installation, control and support for churches, events, conference halls, retail and outdoor advertising in Kigali and across Rwanda.', $led ? '/solutions/led-screens/' : '' ),
		array( 'Acoustic treatment and soundproofing', 'Acoustic treatment and soundproofing in Rwanda: acoustic panels, Rockfon and Barrisol ceilings, echo reduction and sound isolation for churches, studios, offices and auditoriums.', '/solutions/architectural-acoustics/' ),
		array( 'Stage and event lighting', 'Stage, worship and event lighting in Rwanda: supply, rigging, control and installation for churches, auditoriums, venues and events.', '/solutions/lighting-solutions/' ),
		array( 'Recording studio setup', 'Recording studio and podcast setup in Kigali: Yamaha HS studio monitors, Shure SM7B and studio microphones, audio interfaces and acoustic treatment.', '/solutions/studio-solutions/' ),
		array( 'DJ equipment and solutions', 'DJ controllers, speakers, subwoofers and lighting for DJs, clubs and entertainment venues in Kigali and across Rwanda.', '/solutions/dj-solutions/' ),
		array( 'Pro audio equipment supply', 'Authorised supplier of Yamaha, Shure, dB Technologies, Bose Professional and FANE pro audio in Rwanda: speakers, mixers, microphones and amplifiers, genuine with warranty. Ask for today\'s price.', '/solutions/professional-audio/' ),
		array( 'Musical instruments', 'Official Yamaha music distributor in Rwanda: Yamaha keyboards (PSR-E and PSR-SX), digital pianos, guitars and drums at our Kigali showroom on KN1 Rd, Muhima. Ask for today\'s price on WhatsApp.', '/yamaha/' ),
		array( 'Maintenance, repair and backup', 'Sound system maintenance and repairs in Rwanda: troubleshooting, servicing, genuine spare parts, warranty support and fast backup for churches, venues and businesses.', '/solutions/service-and-backup/' ),
		array( 'Sound operator training', 'Hands-on training for church, venue and event sound teams in Kigali: mixer operation, microphone technique, system setup and live-sound basics.', '/solutions/installation/' ),
	);
}

/** Official Rwanda profiles (same links as the Google Business Profile). */
function scrw_gbp_same_as() {
	return array(
		'https://www.facebook.com/soundcreationsrwanda/',
		'https://www.instagram.com/soundcreations_rwanda/',
		'https://www.tiktok.com/@soundcreationsrw',
		'https://mea.yamaha.com/en/distributors/3-africa/rwanda.html',
	);
}

/** Visible FAQs on the price-guide pages, keyed by page slug. */
function scrw_guide_faqs() {
	$where = 'At our showroom on KN1 Rd, Muhima, Kigali, open Monday to Friday 9:00 to 18:00 and Saturday 9:00 to 13:30, or request a quote online.';
	return array(
		'microphone-price-rwanda'     => array(
			array( 'How much is a wireless microphone in Rwanda?', 'It depends on the system and the number of microphones. Send us a WhatsApp message with what you need and we will reply with today\'s price in RWF.' ),
			array( 'Are your Shure microphones genuine?', 'Yes. We supply through official brand channels, with manufacturer warranty and after-sales support in Kigali.' ),
			array( 'Where can I buy a microphone in Kigali?', $where ),
		),
		'speaker-price-rwanda'        => array(
			array( 'How much is a sound system in Rwanda?', 'A complete sound system price depends on the venue. Tell us about your space and we will quote in RWF, including installation.' ),
			array( 'Do you install speakers in Kigali and across Rwanda?', 'Yes. Our technical team installs, tunes and supports systems across Rwanda.' ),
			array( 'Where can I see the speakers?', $where ),
		),
		'keyboard-piano-price-rwanda' => array(
			array( 'How much is a Yamaha keyboard in Rwanda?', 'Prices depend on the model and exchange rates. Message us on WhatsApp and we will send today\'s price in RWF.' ),
			array( 'Are your Yamaha keyboards original?', 'Yes. Sound Creations is the official Yamaha music distributor in Rwanda, so you get genuine products with warranty.' ),
			array( 'Where can I buy a keyboard in Kigali?', $where ),
		),
		'guitar-price-rwanda'         => array(
			array( 'How much is a Yamaha guitar in Rwanda?', 'Prices depend on the model and exchange rates. WhatsApp us for today\'s price in RWF.' ),
			array( 'Is there a guitar shop in Kigali?', 'Yes. Visit the Sound Creations showroom on KN1 Rd, Muhima, Kigali, Monday to Friday 9:00 to 18:00 and Saturday 9:00 to 13:30.' ),
		),
		'mixer-price-rwanda'          => array(
			array( 'How much is a mixer in Rwanda?', 'Mixer prices depend on the model and channel count. WhatsApp us and we will send today\'s price in RWF.' ),
			array( 'Do you sell mixer amplifiers?', 'Tell us your needs and we will recommend a powered mixer or a mixer with amplified speakers.' ),
			array( 'Where can I buy a mixer in Kigali?', $where ),
		),
	);
}

add_filter(
	'sc_seo_graph',
	function ( $graph ) {
		if ( is_array( $graph ) === false ) {
			return $graph;
		}
		$org_id = home_url( '/#organization' );
		$lb_id  = function_exists( 'scrw_schema_localbusiness_id' ) ? scrw_schema_localbusiness_id() : '';

		foreach ( $graph as $i => $node ) {
			if ( is_array( $node ) === false || isset( $node['@id'] ) === false ) {
				continue;
			}
			$id = (string) $node['@id'];
			if ( $id !== $org_id && $id !== $lb_id ) {
				continue;
			}
			$same          = isset( $node['sameAs'] ) ? (array) $node['sameAs'] : array();
			$node['sameAs'] = array_values( array_unique( array_merge( $same, scrw_gbp_same_as() ) ) );
			$alt           = isset( $node['alternateName'] ) ? (array) $node['alternateName'] : array();
			$node['alternateName'] = array_values( array_unique( array_merge( $alt, array( 'Sound Creations Rwanda', 'Sound Creations Kigali' ) ) ) );

			if ( $id === $lb_id ) {
				// Same wording as the Google Business Profile description.
				$node['description'] = 'Sound Creations Rwanda is the authorised Yamaha music distributor in Rwanda and a professional audio, lighting and acoustics company in Kigali. From our showroom on KN1 Rd, Muhima, we supply genuine Yamaha keyboards, digital pianos, guitars and mixers, Shure microphones, dB Technologies speakers, Bose Professional and FANE loudspeakers, all with manufacturer warranty. Our certified team designs, installs and supports sound systems, LED screens and stage lighting for churches, conference rooms, hotels, schools, event venues and studios across Rwanda, including acoustic treatment.';
				$offers = array();
				foreach ( scrw_gbp_services() as $s ) {
					$service = array(
						'@type'       => 'Service',
						'name'        => $s[0],
						'description' => $s[1],
						'provider'    => array( '@id' => $lb_id ),
						'areaServed'  => array( '@type' => 'Country', 'name' => 'Rwanda' ),
					);
					if ( '' !== $s[2] ) {
						$service['url'] = home_url( $s[2] );
					}
					$offers[] = array(
						'@type'       => 'Offer',
						'itemOffered' => $service,
					);
				}
				$node['hasOfferCatalog'] = array(
					'@type'           => 'OfferCatalog',
					'name'            => 'Sound Creations Rwanda services',
					'itemListElement' => $offers,
				);
			}
			$graph[ $i ] = $node;
		}

		if ( is_page() ) {
			$post  = get_queried_object();
			$slug  = ( $post instanceof WP_Post ) ? $post->post_name : '';
			$faqs  = scrw_guide_faqs();
			if ( isset( $faqs[ $slug ] ) ) {
				$url     = get_permalink( $post );
				$page_id = untrailingslashit( $url ) . '/#webpage';
				$q       = array();
				foreach ( $faqs[ $slug ] as $row ) {
					$q[] = array(
						'@type'          => 'Question',
						'name'           => $row[0],
						'acceptedAnswer' => array(
							'@type' => 'Answer',
							'text'  => $row[1],
						),
					);
				}
				$graph[] = array(
					'@type'      => 'FAQPage',
					'@id'        => untrailingslashit( $url ) . '/#faq',
					'isPartOf'   => array( '@id' => $page_id ),
					'mainEntity' => $q,
				);

				$items = array();
				if ( preg_match_all( '#href="(/products/[a-z0-9-]+/)"#i', (string) $post->post_content, $m ) ) {
					$pos = 1;
					foreach ( array_unique( $m[1] ) as $path ) {
						$items[] = array(
							'@type'    => 'ListItem',
							'position' => $pos,
							'url'      => home_url( $path ),
						);
						++$pos;
					}
				}
				if ( $items ) {
					$graph[] = array(
						'@type'           => 'ItemList',
						'@id'             => untrailingslashit( $url ) . '/#products',
						'name'            => get_the_title( $post ),
						'itemListElement' => $items,
					);
				}
			}
		}
		return $graph;
	},
	60
);
