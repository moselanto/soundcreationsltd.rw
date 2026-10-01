<?php
/**
 * Enquiry submission handler: validation, spam protection, storage and notification.
 *
 * @package SoundCreationsEnquiries
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function sc_enq_redirect( $url, $status, $code ) {
	$url = remove_query_arg( array( 'sc_sent', 'sc_error' ), $url );
	if ( 'sent' === $status ) {
		$url = add_query_arg( 'sc_sent', '1', $url );
	} else {
		$url = add_query_arg( 'sc_error', $code, $url );
	}
	wp_safe_redirect( $url . '#sc-form' );
	exit;
}

function sc_enq_recipient( $type ) {
	$over = get_option( 'sc_enq_recipients', array() );
	if ( is_array( $over ) && ! empty( $over[ $type ] ) && is_email( $over[ $type ] ) ) {
		return $over[ $type ];
	}
	if ( is_array( $over ) && ! empty( $over['default'] ) ) {
		return $over['default'];
	}
	$settings = get_option( 'soundcreations_settings', array() );
	if ( is_array( $settings ) && ! empty( $settings['email'] ) && is_email( $settings['email'] ) ) {
		return $settings['email'];
	}
	return 'info@soundcreationsltd.com';
}

function sc_enq_summary( $form, $data, $file_url, $ip ) {
	$lines   = array();
	$lines[] = 'New ' . $form['subject'] . ' enquiry';
	$lines[] = '';
	foreach ( $form['fields'] as $fld ) {
		if ( 'file' === $fld[2] ) {
			continue;
		}
		$name = $fld[0];
		$val  = isset( $data[ $name ] ) ? $data[ $name ] : '';
		if ( '' !== $val ) {
			$lines[] = $fld[1] . ': ' . $val;
		}
	}
	if ( $file_url ) {
		$lines[] = 'Attachment: ' . $file_url;
	}
	if ( '' !== $ip ) {
		$lines[] = '';
		$lines[] = 'IP: ' . $ip;
		$lines[] = 'Submitted: ' . current_time( 'mysql' );
	}
	return implode( "\n", $lines );
}

function sc_enq_handle() {
	$forms    = sc_enq_forms();
	$type     = isset( $_POST['sc_type'] ) ? sanitize_key( wp_unslash( $_POST['sc_type'] ) ) : '';
	$redirect = isset( $_POST['sc_redirect'] ) ? esc_url_raw( wp_unslash( $_POST['sc_redirect'] ) ) : home_url( '/' );

	// Resolve the client once; every abuse control below keys off it.
	$ip = sc_enq_client_ip();

	// Hard per-IP ceiling, counted on EVERY attempt including ones that go on to
	// fail validation, so a bot cannot hammer the endpoint for free by
	// deliberately submitting garbage.
	if ( sc_enq_throttle( 'attempt', 12, 10 * MINUTE_IN_SECONDS, $ip ) ) {
		sc_enq_redirect( $redirect, 'error', 'rate' );
	}

	$nonce = isset( $_POST['sc_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['sc_nonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, 'sc_enquiry_submit' ) ) {
		sc_enq_redirect( $redirect, 'error', 'spam' );
	}
	if ( ! isset( $forms[ $type ] ) ) {
		sc_enq_redirect( $redirect, 'error', 'spam' );
	}
	// Honeypot: silently accept so bots do not retry.
	if ( ! empty( $_POST['sc_website'] ) ) {
		sc_enq_redirect( $redirect, 'sent', '1' );
	}
	// Time trap: a human cannot read and complete the form in under 3 seconds.
	$rendered = isset( $_POST['sc_rendered'] ) ? absint( $_POST['sc_rendered'] ) : 0;
	if ( $rendered && ( time() - $rendered ) < 3 ) {
		sc_enq_redirect( $redirect, 'error', 'spam' );
	}
	// Stale render stamp: a replayed or scripted payload, not a live form.
	if ( $rendered && ( time() - $rendered ) > DAY_IN_SECONDS ) {
		sc_enq_redirect( $redirect, 'error', 'spam' );
	}
	// A real browser always sends a User-Agent.
	if ( empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
		sc_enq_redirect( $redirect, 'error', 'spam' );
	}
	// Sustained-volume cap over a longer window.
	if ( sc_enq_throttle( 'submit', 5, HOUR_IN_SECONDS, $ip ) ) {
		sc_enq_redirect( $redirect, 'error', 'rate' );
	}

	$form = $forms[ $type ];

	if ( ! empty( $form['consent'] ) && empty( $_POST['sc_consent'] ) ) {
		sc_enq_redirect( $redirect, 'error', 'validation' );
	}

	$data = array();
	foreach ( $form['fields'] as $fld ) {
		$name  = $fld[0];
		$ftype = $fld[2];
		$req   = ! empty( $fld[3] );
		if ( 'file' === $ftype ) {
			continue;
		}
		$raw = isset( $_POST[ $name ] ) ? wp_unslash( $_POST[ $name ] ) : '';
		if ( 'textarea' === $ftype ) {
			$val = sanitize_textarea_field( $raw );
		} elseif ( 'email' === $name ) {
			$val = sanitize_email( $raw );
		} else {
			$val = sanitize_text_field( $raw );
		}
		if ( $req && '' === $val ) {
			sc_enq_redirect( $redirect, 'error', 'validation' );
		}
		if ( 'email' === $name && $val && ! is_email( $val ) ) {
			sc_enq_redirect( $redirect, 'error', 'validation' );
		}
		$data[ $name ] = $val;
	}

	// Content-based spam scoring. Silently 'accepted' so the bot sees success and
	// does not retry, but never stored or mailed -- staff are not trained to
	// ignore a noisy queue.
	if ( sc_enq_spam_score( $data ) >= 5 ) {
		sc_enq_redirect( $redirect, 'sent', '1' );
	}

	// Byte-identical payload already received from this client.
	if ( sc_enq_is_duplicate( $type, $data, $ip ) ) {
		sc_enq_redirect( $redirect, 'sent', '1' );
	}

	$file_url = sc_enq_handle_upload();

	$country = isset( $data['country'] ) ? $data['country'] : '';
	$who     = isset( $data['name'] ) ? $data['name'] : 'Unknown';
	$title   = $form['subject'] . ' - ' . $who . ( $country ? ' (' . $country . ')' : '' );

	$post_id = wp_insert_post(
		array(
			'post_type'    => 'sc_enquiry',
			'post_status'  => 'publish',
			'post_title'   => wp_strip_all_tags( $title ),
			'post_content' => sc_enq_summary( $form, $data, $file_url, $ip ),
		)
	);
	if ( $post_id && ! is_wp_error( $post_id ) ) {
		update_post_meta( $post_id, '_sc_type', $type );
		foreach ( $data as $k => $v ) {
			update_post_meta( $post_id, '_sc_' . $k, $v );
		}
		if ( $file_url ) {
			update_post_meta( $post_id, '_sc_file', $file_url );
		}
		update_post_meta( $post_id, '_sc_ip', $ip );
		update_post_meta( $post_id, '_sc_source', $redirect );
	}

	// Notify.
	$recipient = sc_enq_recipient( $type );
	$subject   = 'New ' . $form['subject'] . ' enquiry' . ( $country ? ' - ' . $country : '' );
	$body      = sc_enq_summary( $form, $data, $file_url, '' ) . "\n\nSource: " . $redirect;
	$headers   = array( 'Content-Type: text/plain; charset=UTF-8' );
	if ( ! empty( $data['email'] ) && is_email( $data['email'] ) ) {
		// The display name is attacker-supplied: scrub CR/LF and quoting
		// characters so it can never forge an extra header or spoof a sender.
		$from_name = sc_enq_header_safe( ! empty( $data['name'] ) ? $data['name'] : 'Website enquiry' );
		$from_addr = sanitize_email( $data['email'] );
		if ( '' === $from_name ) {
			$from_name = 'Website enquiry';
		}
		if ( $from_addr && is_email( $from_addr ) ) {
			$headers[] = 'Reply-To: ' . $from_name . ' <' . $from_addr . '>';
		}
	}
	wp_mail( $recipient, $subject, $body, $headers );

	sc_enq_redirect( $redirect, 'sent', '1' );
}
add_action( 'admin_post_nopriv_sc_enquiry', 'sc_enq_handle' );
add_action( 'admin_post_sc_enquiry', 'sc_enq_handle' );
