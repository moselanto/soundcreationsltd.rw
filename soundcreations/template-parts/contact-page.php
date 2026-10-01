<?php
/**
 * Contact Us page body. Included by page-contact.php.
 *
 * A general "get in touch" page: quick contact methods, a short message form,
 * business hours and the regional offices map. This is intentionally distinct
 * from template-parts/consult-page.php (the detailed project-brief page).
 * Renders its own enquiry form via shortcode, so it never calls the_content()
 * (which would auto-append a second form on the mapped slug).
 *
 * @package SoundCreations
 */

if ( defined( 'ABSPATH' ) === false ) {
	exit;
}

$sc_map_img = SC_THEME_URI . '/assets/img/map-africa-me.jpg';

$sc_pin   = '<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>';
$sc_i_call = '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92Z"/>';
$sc_i_mail = '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/>';
$sc_i_chat = '<path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>';
$sc_i_clock = '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>';

$sc_offices = array(
	array( 'id' => 'nairobi',  'name' => 'Nairobi, Kenya',     'addr' => 'Mpaka Plaza, Mpaka Road, Westlands, Nairobi', 'short' => 'Nairobi',  'x' => 62, 'y' => 61 ),
	array( 'id' => 'kigali',   'name' => 'Kigali, Rwanda',     'addr' => 'KN1 Rd Muhima, Kigali (near BTN)',            'short' => 'Kigali',   'x' => 56, 'y' => 63 ),
	array( 'id' => 'kinshasa', 'name' => 'Kinshasa, DR Congo', 'addr' => 'Kinshasa, Democratic Republic of Congo',       'short' => 'Kinshasa', 'x' => 46, 'y' => 64 ),
	array( 'id' => 'dubai',    'name' => 'Dubai, UAE',         'addr' => 'Dubai, United Arab Emirates',                  'short' => 'Dubai',    'x' => 79, 'y' => 33 ),
);

$sc_phone      = sc_setting( 'phone', '+254 715 754 758' );
$sc_phone_link = sc_setting( 'phone_link', '+254715754758' );
$sc_email      = sc_setting( 'email', 'info@soundcreationsltd.com' );
$sc_hours_week = sc_setting( 'hours_week', 'Mon - Fri: 9:00 AM - 5:30 PM' );
$sc_hours_sat  = sc_setting( 'hours_sat', 'Sat: 9:00 AM - 1:30 PM' );
$sc_addr       = sc_setting( 'address', 'Mpaka Plaza, Mpaka Road, Westlands, Nairobi' );
$sc_whatsapp   = trim( (string) sc_setting( 'whatsapp', '' ) );
$sc_wa_digits  = preg_replace( '/[^0-9]/', '', $sc_whatsapp );

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

<section class="sc-section sc-section--tight sc-section--compact sc-contactpage-hero">
	<div class="sc-container sc-contactpage-hero__inner">
		<span class="sc-eyebrow"><?php echo esc_html( sc_setting( 'contact_eyebrow', 'Get in touch' ) ); ?></span>
		<h1 class="sc-contactpage-hero__title"><?php echo esc_html( sc_setting( 'contact_title', 'Contact Us' ) ); ?></h1>
		<p class="sc-lead sc-contactpage-hero__lead"><?php echo sc_rich_e( sc_setting( 'contact_lead', 'Have a question, need a quote, or want to visit us? Reach our team through any of the channels below, or send us a message and we will get back to you quickly.' ) ); ?></p>
	</div>
</section>

<section class="sc-section sc-section--compact sc-contactpage-main">
	<div class="sc-container sc-contact-layout">
		<div class="sc-formcard sc-contact-formcard">
			<h2 class="sc-formcard__title"><?php echo esc_html( sc_setting( 'contact_form_title', 'Send us a message' ) ); ?></h2>
			<p class="sc-formcard__sub"><?php echo esc_html( sc_setting( 'contact_form_sub', 'Fill in the form below and our team will respond as soon as possible.' ) ); ?></p>
			<?php echo do_shortcode( '[sc_enquiry_form type="contact"]' ); ?>
		</div>

		<aside class="sc-teamcard sc-contact-info" id="sc-offices">
			<h2 class="sc-teamcard__title"><?php echo esc_html( sc_setting( 'contact_info_title', 'Reach us directly' ) ); ?></h2>
			<ul class="sc-getintouch__list sc-teamcard__contact">
				<li>
					<span class="sc-getintouch__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><?php echo $sc_i_call; ?></svg></span>
					<div><span class="sc-getintouch__k"><?php esc_html_e( 'Phone', 'soundcreations' ); ?></span><a href="tel:<?php echo esc_attr( $sc_phone_link ); ?>" data-sc-phone><?php echo esc_html( $sc_phone ); ?></a></div>
				</li>
				<li>
					<span class="sc-getintouch__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><?php echo $sc_i_mail; ?></svg></span>
					<div><span class="sc-getintouch__k"><?php esc_html_e( 'Email', 'soundcreations' ); ?></span><a href="mailto:<?php echo esc_attr( $sc_email ); ?>" data-sc-email><?php echo esc_html( $sc_email ); ?></a></div>
				</li>
				<li>
					<span class="sc-getintouch__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><?php echo $sc_i_clock; ?></svg></span>
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
	</div>
</section>
