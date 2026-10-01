<?php
/**
 * Outgoing email identity for the Sound Creations website.
 *
 * Replaces the default WordPress sender (the name "WordPress" from
 * wordpress@domain) with the Sound Creations brand on every message the
 * site sends, so enquiry notifications and all system email look professional
 * and consistent.
 *
 * @package SoundCreationsEnquiries
 */

if ( defined( 'ABSPATH' ) === false ) {
	exit;
}

/**
 * Brand From address. Uses the central email in Sound Creations settings when
 * set, otherwise the company inbox. Kept on the site domain so it stays aligned
 * with SPF and DKIM for good deliverability.
 */
function sc_enq_from_email( $email ) {
	$settings = get_option( 'soundcreations_settings', array() );
	if ( is_array( $settings ) && empty( $settings['email'] ) === false && is_email( $settings['email'] ) ) {
		return $settings['email'];
	}
	return 'info@soundcreationsltd.com';
}
add_filter( 'wp_mail_from', 'sc_enq_from_email' );

/**
 * Brand From name shown to recipients in place of "WordPress".
 */
function sc_enq_from_name( $name ) {
	return 'Sound Creations';
}
add_filter( 'wp_mail_from_name', 'sc_enq_from_name' );
