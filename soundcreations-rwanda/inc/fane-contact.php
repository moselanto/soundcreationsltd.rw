<?php
/**
 * FANE page, "Follow FANE Africa's account" section: adds a "FANE in Rwanda"
 * contact row with the Kigali email and both phone numbers, read live from
 * Sound Creations -> Settings (email, phone, phone2).
 *
 * @package SoundCreationsRwanda
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter(
	'sc_fane_contact_html',
	function () {
		if ( ! function_exists( 'sc_setting' ) ) {
			return '';
		}
		$tiles = array();
		$email = (string) sc_setting( 'email' );
		if ( '' !== $email ) {
			$tiles[] = array( 'Email', $email, 'mailto:' . $email, '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/>' );
		}
		foreach ( array( array( 'phone', 'phone_link' ), array( 'phone2', 'phone2_link' ) ) as $k ) {
			$num = (string) sc_setting( $k[0] );
			if ( '' !== $num ) {
				$tel     = (string) sc_setting( $k[1] );
				$tel     = '' !== $tel ? $tel : preg_replace( '/[^0-9+]/', '', $num );
				$tiles[] = array( 'Call', $num, 'tel:' . $tel, '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92Z"/>' );
			}
		}
		if ( ! $tiles ) {
			return '';
		}
		$html  = '<div class="scrw-fane-contact">';
		$html .= '<p class="scrw-fane-contact__title">' . esc_html__( 'FANE in Rwanda', 'soundcreations-rwanda' ) . '</p>';
		$html .= '<p class="scrw-fane-contact__lead">' . esc_html__( 'Buying, specifying or stocking FANE in Rwanda? Talk to our Kigali team.', 'soundcreations-rwanda' ) . '</p>';
		$html .= '<div class="scrw-fane-contact__grid">';
		foreach ( $tiles as $t ) {
			$html .= '<a class="scrw-fane-contact__tile" href="' . esc_url( $t[2] ) . '">'
				. '<span class="scrw-fane-contact__icon" aria-hidden="true"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">' . $t[3] . '</svg></span>'
				. '<span class="scrw-fane-contact__text"><span class="scrw-fane-contact__label">' . esc_html( $t[0] ) . '</span>'
				. '<span class="scrw-fane-contact__value">' . esc_html( $t[1] ) . '</span></span></a>';
		}
		$html .= '</div></div>';
		return $html;
	}
);
