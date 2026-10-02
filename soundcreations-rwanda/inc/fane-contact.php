<?php
/**
 * FANE page, bottom section: on the Rwanda site the "Follow FANE Africa's
 * account" social buttons are replaced by contact tiles for the Kigali team -
 * email, WhatsApp and both phone numbers - read live from
 * Sound Creations -> Settings (email, whatsapp, phone, phone2).
 *
 * @package SoundCreationsRwanda
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'sc_fane_social_links', '__return_empty_array' );

add_filter(
	'sc_fane_contact_html',
	function () {
		if ( ! function_exists( 'sc_setting' ) ) {
			return '';
		}
		$ico_mail = '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/>';
		$ico_call = '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92Z"/>';
		$ico_wa   = '<path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>';

		$tiles = array();
		$email = (string) sc_setting( 'email' );
		if ( '' !== $email ) {
			$tiles[] = array( 'Email', $email, 'mailto:' . $email, $ico_mail, '' );
		}
		$email2 = (string) sc_setting( 'email2' );
		if ( '' !== $email2 ) {
			$tiles[] = array( 'Email', $email2, 'mailto:' . $email2, $ico_mail, '' );
		}
		$wa = preg_replace( '/[^0-9]/', '', (string) sc_setting( 'whatsapp' ) );
		if ( '' !== $wa ) {
			$tiles[] = array( 'WhatsApp', '+' . $wa, 'https://wa.me/' . $wa, $ico_wa, 'wa' );
		}
		foreach ( array( array( 'phone', 'phone_link' ), array( 'phone2', 'phone2_link' ) ) as $k ) {
			$num = (string) sc_setting( $k[0] );
			if ( '' !== $num ) {
				$tel     = (string) sc_setting( $k[1] );
				$tel     = '' !== $tel ? $tel : preg_replace( '/[^0-9+]/', '', $num );
				$tiles[] = array( 'Call', $num, 'tel:' . $tel, $ico_call, '' );
			}
		}
		if ( ! $tiles ) {
			return '';
		}
		$html = '<div class="scrw-fane-contact"><div class="scrw-fane-contact__grid">';
		foreach ( $tiles as $t ) {
			$ext   = ( 0 === strpos( $t[2], 'http' ) ) ? ' target="_blank" rel="noopener"' : '';
			$html .= '<a class="scrw-fane-contact__tile' . ( $t[4] ? ' scrw-fane-contact__tile--' . $t[4] : '' ) . '" href="' . esc_url( $t[2] ) . '"' . $ext . '>'
				. '<span class="scrw-fane-contact__icon" aria-hidden="true"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">' . $t[3] . '</svg></span>'
				. '<span class="scrw-fane-contact__text"><span class="scrw-fane-contact__label">' . esc_html( $t[0] ) . '</span>'
				. '<span class="scrw-fane-contact__value">' . esc_html( $t[1] ) . '</span></span></a>';
		}
		$html .= '</div></div>';
		return $html;
	}
);
