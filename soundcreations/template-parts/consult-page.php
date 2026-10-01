<?php
/**
 * Shared "Request a Consultation" page body.
 * Included by page-request-a-consultation.php and page-contact.php.
 * Renders its own enquiry form via shortcode, so it never calls the_content()
 * (which would auto-append a second form on the mapped slugs).
 *
 * @package SoundCreations
 */

if ( defined( 'ABSPATH' ) === false ) {
	exit;
}

$sc_hero_img = SC_THEME_URI . '/assets/img/consult-hero.jpg';
$sc_map_img  = SC_THEME_URI . '/assets/img/map-africa-me.jpg';

$sc_pills = array(
	array( 'Expert Advice', 'Get guidance from our technical specialists.', '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="m17 11 2 2 4-4"/>' ),
	array( 'Tailored Solutions', 'Solutions designed for your needs.', '<line x1="4" y1="21" x2="4" y2="14"/><line x1="4" y1="10" x2="4" y2="3"/><line x1="12" y1="21" x2="12" y2="12"/><line x1="12" y1="8" x2="12" y2="3"/><line x1="20" y1="21" x2="20" y2="16"/><line x1="20" y1="12" x2="20" y2="3"/><line x1="1" y1="14" x2="7" y2="14"/><line x1="9" y1="8" x2="15" y2="8"/><line x1="17" y1="16" x2="23" y2="16"/>' ),
	array( 'Fast Response', 'We respond quickly to every enquiry.', '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>' ),
	array( 'End-to-End Support', 'From design to installation and long-term care.', '<path d="M3 18v-6a9 9 0 0 1 18 0v6"/><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3z"/><path d="M3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"/>' ),
);

$sc_pin = '<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>';

// id, name, address, short (map flag), map x/y as percentages tuned to map-africa-me.jpg.
$sc_offices = array(
	array( 'id' => 'nairobi',  'name' => 'Nairobi, Kenya',     'addr' => 'Mpaka Plaza, Mpaka Road, Westlands, Nairobi', 'short' => 'Nairobi',  'x' => 62, 'y' => 61 ),
	array( 'id' => 'kigali',   'name' => 'Kigali, Rwanda',     'addr' => 'KN1 Rd Muhima, Kigali (near BTN)',            'short' => 'Kigali',   'x' => 56, 'y' => 63 ),
	array( 'id' => 'kinshasa', 'name' => 'Kinshasa, DR Congo', 'addr' => 'Kinshasa, Democratic Republic of Congo',       'short' => 'Kinshasa', 'x' => 46, 'y' => 64 ),
	array( 'id' => 'dubai',    'name' => 'Dubai, UAE',         'addr' => 'Dubai, United Arab Emirates',                  'short' => 'Dubai',    'x' => 79, 'y' => 33 ),
);

$sc_phone      = sc_setting( 'phone', '+254 715 754 758' );
$sc_phone_link = sc_setting( 'phone_link', '+254715754758' );
$sc_email      = sc_setting( 'email', 'info@soundcreationsltd.com' );
$sc_hours_week = sc_setting( 'hours_week', 'Mon - Fri: 8:00 AM - 5:30 PM' );
$sc_hours_sat  = sc_setting( 'hours_sat', 'Sat: 9:00 AM - 1:00 PM' );

// Per-office contact details. Kigali uses the Rwanda office line; every other
// office uses the Kenya head-office details. Hours lines are pipe-separated.
$sc_ke_contact = array(
	'phone'      => $sc_phone,
	'phone_link' => $sc_phone_link,
	'email'      => $sc_email,
	'hours'      => $sc_hours_week . ' | ' . $sc_hours_sat,
);
$sc_rw_contact = array(
	'phone'      => '+250 783 141 050 | 782 739 889',
	'phone_link' => '+250783141050',
	'email'      => 'fred@soundcreationsltd.com',
	'hours'      => 'Mon – Fri: 9:00am – 6:00pm',
);
$sc_contacts_map = array(
	'nairobi'  => $sc_ke_contact,
	'kigali'   => $sc_rw_contact,
	'kinshasa' => $sc_ke_contact,
	'dubai'    => $sc_ke_contact,
);
?>

<section class="sc-consult-hero">
	<div class="sc-container sc-consult-hero__grid">
		<div class="sc-consult-hero__text">
			<h1 class="sc-consult-hero__title"><?php echo esc_html( sc_setting( 'consult_title', 'Request a Consultation' ) ); ?></h1>
			<p class="sc-lead sc-consult-hero__lead"><?php echo esc_html( sc_setting( 'consult_lead', 'Tell us about your space and application. Our technical team will help you specify the right solution.' ) ); ?></p>
		</div>
		<div class="sc-consult-hero__media">
			<img src="<?php echo esc_url( $sc_hero_img ); ?>" alt="<?php esc_attr_e( 'Modern boardroom audio-visual installation', 'soundcreations' ); ?>" loading="eager" decoding="async">
		</div>
	</div>
</section>

<section class="sc-consult-pillsec">
	<div class="sc-container">
		<div class="sc-consult-pills">
			<?php foreach ( $sc_pills as $p ) : ?>
				<div class="sc-contact-pill">
					<span class="sc-contact-pill__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><?php echo $p[2]; ?></svg></span>
					<div class="sc-contact-pill__text"><strong><?php echo esc_html( $p[0] ); ?></strong><span><?php echo esc_html( $p[1] ); ?></span></div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="sc-section sc-section--compact sc-consult-main">
	<div class="sc-container sc-consult-grid">
		<aside class="sc-teamcard">
			<h2 class="sc-teamcard__title"><?php echo esc_html( sc_setting( 'team_title', 'Speak to our team' ) ); ?></h2>
			<p class="sc-teamcard__sub"><?php echo esc_html( sc_setting( 'team_sub', 'Prefer to talk? Our team is happy to discuss your project.' ) ); ?></p>
			<ul class="sc-getintouch__list sc-teamcard__contact">
				<li>
					<span class="sc-getintouch__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92Z"/></svg></span>
					<div><span class="sc-getintouch__k"><?php esc_html_e( 'Phone', 'soundcreations' ); ?></span><a href="tel:<?php echo esc_attr( $sc_phone_link ); ?>" data-sc-phone><?php echo esc_html( $sc_phone ); ?></a></div>
				</li>
				<li>
					<span class="sc-getintouch__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/></svg></span>
					<div><span class="sc-getintouch__k"><?php esc_html_e( 'Email', 'soundcreations' ); ?></span><a href="mailto:<?php echo esc_attr( $sc_email ); ?>" data-sc-email><?php echo esc_html( $sc_email ); ?></a></div>
				</li>
				<li>
					<span class="sc-getintouch__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></span>
					<div><span class="sc-getintouch__k"><?php esc_html_e( 'Business Hours', 'soundcreations' ); ?></span><span class="sc-getintouch__v" data-sc-hours><?php echo esc_html( $sc_hours_week ); ?><br><?php echo esc_html( $sc_hours_sat ); ?></span></div>
				</li>
			</ul>
			<hr class="sc-teamcard__rule">
			<h3 class="sc-teamcard__offhead"><?php esc_html_e( 'Our Offices', 'soundcreations' ); ?></h3>
			<div class="sc-teamcard__geo" data-sc-locmap data-sc-contacts="<?php echo esc_attr( wp_json_encode( $sc_contacts_map ) ); ?>">
				<ul class="sc-teamcard__offices">
					<?php foreach ( $sc_offices as $i => $o ) : ?>
						<li class="sc-teamcard__office<?php echo 0 === $i ? ' is-active' : ''; ?>" data-loc="<?php echo esc_attr( $o['id'] ); ?>" tabindex="0" role="button" aria-label="<?php echo esc_attr( sprintf( 'Show %s on the map', $o['name'] ) ); ?>">
							<span class="sc-teamcard__pin" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><?php echo $sc_pin; ?></svg></span>
							<div><strong><?php echo esc_html( $o['name'] ); ?></strong><span><?php echo esc_html( $o['addr'] ); ?></span></div>
						</li>
					<?php endforeach; ?>
				</ul>
				<div class="sc-map__stage sc-teamcard__mapstage">
					<img class="sc-map__bg" src="<?php echo esc_url( $sc_map_img ); ?>" alt="<?php esc_attr_e( 'Map of Sound Creations regional offices', 'soundcreations' ); ?>" loading="lazy">
					<?php foreach ( $sc_offices as $i => $o ) : ?>
						<button type="button" class="sc-map__pin<?php echo 0 === $i ? ' is-active' : ''; ?>" data-loc="<?php echo esc_attr( $o['id'] ); ?>" style="left:<?php echo (int) $o['x']; ?>%;top:<?php echo (int) $o['y']; ?>%;" aria-label="<?php echo esc_attr( $o['name'] ); ?>">
							<span class="sc-map__dot"></span><span class="sc-map__flag"><?php echo esc_html( $o['short'] ); ?></span>
						</button>
					<?php endforeach; ?>
				</div>
			</div>
		</aside>
		<div class="sc-formcard sc-consult-form">
			<h2 class="sc-formcard__title"><?php echo esc_html( sc_setting( 'form_title', 'Your project details' ) ); ?></h2>
			<?php echo do_shortcode( '[sc_enquiry_form type="consultation"]' ); ?>
		</div>
	</div>
</section>
