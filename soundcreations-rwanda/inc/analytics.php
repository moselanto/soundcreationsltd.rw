<?php
/**
 * Google Analytics 4 and Search Console verification (proposal section C).
 * Fill in Settings -> General -> "Rwanda: GA4 Measurement ID" and
 * "Rwanda: Search Console verification code". Nothing is output while blank.
 *
 * @package SoundCreationsRwanda
 */

if ( \! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'admin_init',
	function () {
		register_setting(
			'general',
			'scrw_ga4_id',
			array(
				'type'              => 'string',
				'sanitize_callback' => function ( $v ) {
					$v = strtoupper( trim( (string) $v ) );
					return preg_match( '/^G-[A-Z0-9]{4,20}$/', $v ) ? $v : '';
				},
				'default'           => '',
			)
		);
		register_setting(
			'general',
			'scrw_gsc_code',
			array(
				'type'              => 'string',
				'sanitize_callback' => function ( $v ) {
					return preg_replace( '/[^A-Za-z0-9_\-]/', '', (string) $v );
				},
				'default'           => '',
			)
		);
		add_settings_field(
			'scrw_ga4_id',
			'Rwanda: GA4 Measurement ID',
			function () {
				printf( '<input type="text" name="scrw_ga4_id" value="%s" class="regular-text" placeholder="G-XXXXXXXXXX">', esc_attr( get_option( 'scrw_ga4_id', '' ) ) );
			},
			'general'
		);
		add_settings_field(
			'scrw_gsc_code',
			'Rwanda: Search Console verification code',
			function () {
				printf( '<input type="text" name="scrw_gsc_code" value="%s" class="regular-text" placeholder="content value of the google-site-verification tag">', esc_attr( get_option( 'scrw_gsc_code', '' ) ) );
			},
			'general'
		);
	}
);

add_action(
	'wp_head',
	function () {
		$gsc = (string) get_option( 'scrw_gsc_code', '' );
		if ( '' \!== $gsc ) {
			echo '<meta name="google-site-verification" content="' . esc_attr( $gsc ) . '">' . "\n";
		}
		$ga = (string) get_option( 'scrw_ga4_id', '' );
		if ( '' === $ga || is_user_logged_in() ) {
			return;
		}
		echo '<script async src="https://www.googletagmanager.com/gtag/js?id=' . esc_attr( $ga ) . '"></script>' . "\n";
		echo "<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','" . esc_js( $ga ) . "');</script>\n";
	},
	3
);
