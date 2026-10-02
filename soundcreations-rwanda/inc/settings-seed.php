<?php
/**
 * Rwanda business details and copy, written into the shared settings option.
 *
 * sc_setting() prefers the saved option and falls back to the parent theme's
 * defaults, which are the KENYA head-office values. On a fresh Rwanda install
 * that would show a Nairobi phone and address. This seeder writes the Rwanda
 * values into the option, but only into fields that are still empty or still
 * hold the untouched Kenya default, so anything an editor has typed in
 * Sound Creations -> Settings is never overwritten.
 *
 * @package SoundCreationsRwanda
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SCRW_SEED_VERSION', 'rw-settings-9' ); // rw-settings-9: stefic@ + fred@ contact emails, brand line in the top bar.

/**
 * Rwanda values. Contact details are taken from the current live Rwanda site.
 * Fields marked CONFIRM should be checked with the Rwanda office.
 */
function scrw_settings() {
	return array(
		'company_name'       => 'Sound Creations Ltd Rwanda',
		'tagline'            => 'Audio Visual, Lighting & Acoustic Solutions in Kigali, Rwanda',
		'phone'              => '+250 783 141 050',
		'phone_link'         => '+250783141050',
		'phone2'             => '+250 782 739 889',
		'phone2_link'        => '+250782739889',
		'instagram'          => 'https://www.instagram.com/soundcreations_rwanda/',
		// FANE page: same FANE Africa accounts as the group site.
		'fane_facebook'      => 'https://web.facebook.com/profile.php?id=61593413537880',
		'fane_instagram'     => 'https://www.instagram.com/faneloudspeakers_africa/',
		'fane_social_title'  => 'Talk to FANE in Rwanda',
		'fane_social_text'   => 'Buying, specifying or stocking FANE loudspeakers in Rwanda? Email, call or WhatsApp our Kigali team.',
		'email'              => 'stefic@soundcreationsltd.com', // Main Rwanda contact.
		'email2'             => 'fred@soundcreationsltd.com',   // Second Rwanda contact.
		'address'            => 'KN1 Rd, Muhima, Kigali, Rwanda',
		'hours_week'         => 'Mon-Fri: 9:00 AM - 6:00 PM',
		'hours_sat'          => 'Sat: 9:00 AM - 1:30 PM',
		'hours_sun'          => 'Sun: Closed',
		'regions'            => 'Authorised Yamaha Distributor · FANE Africa Partner · Kigali',
		'whatsapp'           => '250783141050',
		'whatsapp_prefill'   => 'Hello Sound Creations Rwanda, I would like to enquire about your services.',
		'map_url'            => 'https://www.google.com/maps/search/?api=1&query=Sound+Creations+Ltd+KN1+Rd+Muhima+Kigali',
		'footer_address'     => "KN1 Rd, Muhima\nKigali, Rwanda", // Both phones now show as their own lines.
		'footer_hours_label' => 'Open Hours',
		'footer_hours'       => "Mon - Fri: 9 am - 6 pm\nSat: 9 am - 1:30 pm\nSunday: CLOSED",
		'footer_about'       => 'Sound Creations Ltd Rwanda designs, supplies, installs and supports professional audio, DJ, lighting, studio and acoustic solutions for venues across Kigali and Rwanda, backed by the engineering depth of the Sound Creations Ltd group.',
		'footer_solutions'   => "DJ Solutions | /solutions/dj-solutions/\nLighting Solutions | /solutions/lighting-solutions/\nStudio Solutions | /solutions/studio-solutions/\nArchitectural Acoustics | /solutions/architectural-acoustics/\nService and Backup | /solutions/service-and-backup/",
		'footer_explore'     => "Home | /\nSolutions | /solutions/\nBrands & Products | /brands/\nProjects | /projects/\nAbout | /about/\nContact | /contact/\nRequest a Quote | /request-a-quote/",

		// Homepage.
		'home_hero_title'     => '', // No visible headline, as on the group site.
		'home_whatwedo_title' => '',
		'home_whatwedo_lead'  => 'If it sounds good, it’s Sound Creations. From acoustic design and system engineering to equipment, integration, commissioning and support, we deliver world-class technology and expertise across Rwanda, Africa and the Middle East.',
		'home_solutions_title'=> '',
		'home_projects_title' => 'Real spaces. Real results.',
		'home_stat1_num'      => '22+',
		'home_stat1_label'    => 'Years of Group Experience',
		'home_stat2_num'      => '4',
		'home_stat2_label'    => 'Regional Locations',
		'home_stat2_note'     => 'Rwanda | Kenya | DRC Congo | UAE',
		'home_stat3_num'      => '850+',
		'home_stat3_label'    => 'Group Projects Completed',
		'home_cta_title'      => 'Planning a project in Rwanda?',
		'home_cta_text'       => 'Tell us about your venue and what it needs to do. Our Kigali team will recommend, quote and deliver the right system.',
		'home_steps'          => "Consultation & Design | We listen, visit your site, and design a system around your space, goals and budget.\nDistribution | Genuine equipment from trusted global brands, supplied with full manufacturer warranty.\nIntegration | Professional installation, commissioning and calibration by our technical team.\nSupport & Training | Operator training, preventive maintenance and fast after-sales backup in Rwanda.",

		// About.
		'about_hero_title'    => 'If it sounds good, it’s Sound Creations Rwanda',
		'about_journey_p1'    => 'Sound Creations began in Nairobi in 1989 as Nipul Electronics and became Sound Creations Ltd in 2004. The group opened its Rwanda operation in 2018, began work in the DR Congo in 2022 and partnered with LC Acoustic in Dubai in 2024. From our office on KN1 Road, Muhima, our Kigali team consults, supplies, installs and supports professional audio, acoustic, lighting and visual systems for houses of worship, corporate and commercial spaces, education institutions and entertainment venues across Rwanda.',
		'about_process_items' => "Consultation & Design | We listen, visualise with you, propose, agree and represent the solution. | /request-a-consultation/\nDistribution | From the most affordable to the substantial investments, we supply genuine equipment with warranty. | /brands/\nIntegration | Installation, commissioning and calibration by a certified technical team. | /solutions/\nSupport & Training | Training, maintenance and fast backup for systems across Rwanda. | /solutions/service-and-backup/",

		'projects_lead'       => 'A selection of professional audio, lighting, studio and acoustics projects delivered in Rwanda and across the Sound Creations Ltd group.',
	);
}

/**
 * Values written by earlier Rwanda seed versions that have since been
 * corrected. A field still holding one of these was never edited by hand, so
 * it is safe to replace with the current value.
 *
 * rw-settings-2 (1 Oct 2026): Rwanda keeps the same opening pattern as Kenya
 * (weekdays plus Saturday morning, Sunday closed) on Kigali time, with
 * weekdays running 9 am - 6 pm.
 */
function scrw_superseded_settings() {
	return array(
		'hours_sat'    => array( 'Sat: Closed' ),
		// rw-settings-9: main contact email and top-bar line.
		'email'        => array( 'sales@soundcreationsltd.com', 'fred@soundcreationsltd.com' ),
		'regions'      => array( 'Kigali · Rwanda · Part of the Sound Creations Ltd group' ),
		'footer_hours' => array( "Mon - Fri: 9 am - 6 pm\nSat - Sun: Closed" ),
		// rw-settings-5: About story from the SCL RW Company Profile 2025.
		'about_journey_p1'     => array( 'Sound Creations Ltd Rwanda is the Kigali operation of the Sound Creations Ltd group, a professional audio, visual, lighting and acoustic company founded in Nairobi in 2004 and today working across Kenya, Rwanda, the DR Congo and the UAE. In Rwanda we bring the group’s selection philosophy, technology, reliability, ease of use and affordability, to every project, with a local team that consults, supplies, installs and supports on the ground.' ),
		// rw-settings-4: phones moved out of the address into phone / phone2.
		// rw-settings-8: any address that still carries a phone line (the phones print on their own lines below it).
		'footer_address'       => array( "KN1 Rd, Muhima\nKigali, Rwanda\n+250 783 141 050 | +250 782 739 889", "KN1 Rd, Muhima\nKigali, Rwanda\n+250 783 141 050", "KN1 Rd, Muhima\nKigali, Rwanda\n+250 783 141 050\n+250 782 739 889", "KN1 Rd, Muhima\nKigali, Rwanda\n+250 782 739 889" ),
		// rw-settings-3: homepage copy aligned with the group site.
		'home_hero_title'      => array( 'Professional sound, lighting and acoustics for Rwanda.' ),
		'home_whatwedo_title'  => array( 'One partner, from first consultation to long-term support.' ),
		'home_whatwedo_lead'   => array( 'From our Kigali office we help churches, hotels, conference centres, schools, studios and event venues across Rwanda get systems that sound right, look right and keep working. We consult and design, supply genuine equipment from world-class brands, integrate and commission on site, and stay on hand for service, training and backup.' ),
		'home_solutions_title' => array( 'Expertise solutions and services in Rwanda.' ),
	);
}

/**
 * Write Rwanda values into empty, still-Kenya-default or superseded fields.
 */
function scrw_seed_settings() {
	$opts = get_option( 'soundcreations_settings', array() );
	if ( ! is_array( $opts ) ) {
		$opts = array();
	}
	$kenya      = function_exists( 'sc_default_settings' ) ? sc_default_settings() : array();
	$superseded = scrw_superseded_settings();

	foreach ( scrw_settings() as $key => $val ) {
		$cur = isset( $opts[ $key ] ) ? trim( (string) $opts[ $key ] ) : '';
		$def = isset( $kenya[ $key ] ) ? trim( (string) $kenya[ $key ] ) : null;
		$old = isset( $superseded[ $key ] ) ? $superseded[ $key ] : array();
		if ( '' === $cur || ( null !== $def && $cur === $def ) || in_array( $cur, $old, true ) ) {
			$opts[ $key ] = $val;
		}
	}
	update_option( 'soundcreations_settings', $opts );

	// Enquiry and quote forms route to the Rwanda sales inbox (proposal section D).
	$routing = get_option( 'sc_enq_recipients', array() );
	if ( ! is_array( $routing ) ) {
		$routing = array();
	}
	$old_routes = array( '', 'sales@soundcreationsltd.com', 'fred@soundcreationsltd.com' );
	if ( empty( $routing['default'] ) || in_array( trim( (string) $routing['default'] ), $old_routes, true ) ) {
		// Website enquiries go to both Rwanda contacts.
		$routing['default'] = 'stefic@soundcreationsltd.com, fred@soundcreationsltd.com';
		update_option( 'sc_enq_recipients', $routing );
	}
}

add_action( 'after_switch_theme', 'scrw_seed_settings', 20 );

add_action(
	'admin_init',
	function () {
		if ( get_option( 'scrw_seed_ver' ) === SCRW_SEED_VERSION ) {
			return;
		}
		scrw_seed_settings();
		update_option( 'scrw_seed_ver', SCRW_SEED_VERSION );
	},
	3
);
