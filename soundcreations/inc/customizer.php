<?php
/**
 * Customizer: Sound Creations Content panel.
 *
 * Adds an organised content editor under Appearance > Customize > Sound
 * Creations Content, so every piece of site text can be edited with live
 * preview. Controls read and write the same soundcreations_settings option
 * used across the whole site, so they stay in sync with the
 * Sound Creations > Settings admin page and never drift apart.
 *
 * @package SoundCreations
 */

if ( defined( 'ABSPATH' ) === false ) {
	exit;
}

function sc_customize_sanitize_text( $value ) {
	return sanitize_text_field( (string) $value );
}

function sc_customize_sanitize_textarea( $value ) {
	return sanitize_textarea_field( (string) $value );
}

function sc_customize_sanitize_url_field( $value ) {
	return esc_url_raw( trim( (string) $value ) );
}

/**
 * Build the Sound Creations Content panel from the shared field map.
 */
function sc_customize_register( $wp_customize ) {
	if ( function_exists( 'sc_core_settings_fields' ) === false ) {
		return;
	}
	$fields   = sc_core_settings_fields();
	$defaults = function_exists( 'sc_default_settings' ) ? sc_default_settings() : array();

	$panel_id = 'sc_content';
	$wp_customize->add_panel(
		$panel_id,
		array(
			'title'       => 'Sound Creations Content',
			'description' => 'Edit the words and images across your site. Changes preview on the right. Click Publish to save.',
			'priority'    => 20,
		)
	);

	$section_id = '';
	$priority   = 0;

	foreach ( $fields as $key => $meta ) {
		$label = isset( $meta[0] ) ? $meta[0] : $key;
		$type  = isset( $meta[1] ) ? $meta[1] : 'text';

		if ( 'heading' === $type ) {
			$priority  += 1;
			$section_id = 'sc_sec_' . sanitize_key( $key );
			$wp_customize->add_section(
				$section_id,
				array(
					'title'    => $label,
					'panel'    => $panel_id,
					'priority' => $priority,
				)
			);
			continue;
		}

		if ( '' === $section_id ) {
			$priority  += 1;
			$section_id = 'sc_sec_general';
			$wp_customize->add_section(
				$section_id,
				array(
					'title'    => 'General',
					'panel'    => $panel_id,
					'priority' => $priority,
				)
			);
		}

		$setting_id = 'soundcreations_settings[' . $key . ']';
		$default    = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';

		if ( 'textarea' === $type ) {
			$sanitize = 'sc_customize_sanitize_textarea';
		} elseif ( 'image' === $type ) {
			$sanitize = 'sc_customize_sanitize_url_field';
		} else {
			$sanitize = 'sc_customize_sanitize_text';
		}

		$wp_customize->add_setting(
			$setting_id,
			array(
				'type'              => 'option',
				'default'           => $default,
				'sanitize_callback' => $sanitize,
				'transport'         => 'refresh',
			)
		);

		$control = array(
			'label'    => $label,
			'section'  => $section_id,
			'settings' => $setting_id,
		);
		if ( 'textarea' === $type ) {
			$control['type'] = 'textarea';
		} elseif ( 'image' === $type ) {
			$control['type']        = 'url';
			$control['description'] = 'Paste a URL. To use a file, upload it under Media first, then paste its link here.';
		} else {
			$control['type'] = 'text';
		}

		$wp_customize->add_control( 'sc_ctrl_' . sanitize_key( $key ), $control );
	}
}
add_action( 'customize_register', 'sc_customize_register' );
