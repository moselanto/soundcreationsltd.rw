<?php
/**
 * Abuse controls for public enquiry submissions: client-IP resolution,
 * pre-emptive rate limiting, spam scoring and upload hardening.
 *
 * Everything here runs BEFORE an enquiry is stored, mailed or a file is
 * accepted, so a bot cannot spend server resources by failing validation.
 *
 * @package SoundCreationsEnquiries
 */

if ( defined( 'ABSPATH' ) === false ) {
	exit;
}

/**
 * Resolve the real client IP.
 *
 * Forwarded headers (X-Forwarded-For, CF-Connecting-IP) are trivially spoofable,
 * so they are ONLY honoured when the connecting peer is a proxy the site owner
 * has explicitly declared. Otherwise every bot could rotate a header value and
 * defeat rate limiting outright.
 *
 * Behind Cloudflare or a load balancer, declare the proxy addresses:
 *
 *   add_filter( 'sc_enq_trusted_proxies', function ( $ips ) {
 *       $ips[] = '203.0.113.10';
 *       return $ips;
 *   } );
 *
 * @return string Validated IP address, or '' when it cannot be established.
 */
function sc_enq_client_ip() {
	$remote = isset( $_SERVER['REMOTE_ADDR'] ) ? trim( (string) wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	$remote = filter_var( $remote, FILTER_VALIDATE_IP ) ? $remote : '';

	$trusted = apply_filters( 'sc_enq_trusted_proxies', array() );
	if ( '' === $remote || ! is_array( $trusted ) || ! in_array( $remote, $trusted, true ) ) {
		return $remote;
	}

	foreach ( array( 'HTTP_CF_CONNECTING_IP', 'HTTP_X_REAL_IP', 'HTTP_X_FORWARDED_FOR' ) as $header ) {
		if ( empty( $_SERVER[ $header ] ) ) {
			continue;
		}
		$parts = explode( ',', (string) wp_unslash( $_SERVER[ $header ] ) );
		$first = trim( $parts[0] );
		if ( filter_var( $first, FILTER_VALIDATE_IP ) ) {
			return $first;
		}
	}
	return $remote;
}

/**
 * Signed render token: "<unix time>.<hmac>".
 *
 * The old field was a plain timestamp, so a bot could simply omit it (the
 * time trap was then skipped entirely) or forge "now minus 10 seconds".
 * The HMAC makes the stamp unforgeable and the handler now REQUIRES it.
 */
function sc_enq_issue_token( $time = null ) {
	$time = null === $time ? time() : (int) $time;
	return $time . '.' . substr( hash_hmac( 'sha256', 'sc_enq|' . $time, wp_salt( 'nonce' ) ), 0, 20 );
}

/**
 * @return int Issue time when the token is authentic, otherwise 0.
 */
function sc_enq_verify_token( $token ) {
	$token = (string) $token;
	if ( ! preg_match( '/^(\d{9,11})\.([a-f0-9]{20})$/', $token, $m ) ) {
		return 0;
	}
	$expected = sc_enq_issue_token( (int) $m[1] );
	return hash_equals( $expected, $token ) ? (int) $m[1] : 0;
}

/**
 * Bucket key for a rate-limit counter. Hashed so raw IPs are not written into
 * option/transient names.
 */
function sc_enq_rl_key( $bucket, $ip ) {
	return 'sc_enq_' . $bucket . '_' . md5( $ip . '|' . wp_salt( 'nonce' ) );
}

/**
 * Count one attempt against a bucket and report whether the cap is now exceeded.
 * Counted on EVERY attempt, including ones that go on to fail validation, so
 * hammering the endpoint with invalid payloads still trips the limit.
 *
 * @param string $bucket  Counter name.
 * @param int    $cap     Maximum attempts allowed in the window.
 * @param int    $window  Window length in seconds.
 * @param string $ip      Client IP.
 * @return bool True when the caller is over the cap.
 */
function sc_enq_throttle( $bucket, $cap, $window, $ip ) {
	if ( '' === $ip ) {
		// Unknown origin: fall back to a single shared bucket rather than no limit.
		$ip = 'unknown';
	}
	$key   = sc_enq_rl_key( $bucket, $ip );
	$count = (int) get_transient( $key );
	++$count;
	set_transient( $key, $count, $window );
	return $count > $cap;
}

/**
 * Score a submission for spam signals. Higher is worse; 5 or more is rejected.
 * Deliberately signal-based rather than a single hard rule, so one false
 * positive cannot block a legitimate enquiry on its own.
 *
 * @param array $data Sanitized field values.
 * @return int Score.
 */
function sc_enq_spam_score( $data ) {
	$score = 0;
	$blob  = '';
	foreach ( $data as $key => $val ) {
		if ( 'email' === $key ) {
			continue;
		}
		$blob .= ' ' . (string) $val;
	}
	$blob  = trim( $blob );
	$lower = strtolower( $blob );

	// 1. Link stuffing: the single strongest signal in B2B enquiry spam.
	$links = preg_match_all( '#https?://|www\.|\[url|<a\s#i', $blob );
	if ( $links >= 1 ) {
		$score += 2;
	}
	if ( $links >= 3 ) {
		$score += 3;
	}

	// 2. BBCode / raw HTML in a plain-text field: never legitimate here.
	if ( preg_match( '#\[/?(url|link|b|img)\]|</?[a-z]+>#i', $blob ) ) {
		$score += 3;
	}

	// 3. Classic spam vocabulary.
	$terms = array(
		'seo service', 'seo expert', 'backlink', 'guest post', 'casino', 'viagra',
		'cialis', 'porn', 'crypto investment', 'forex signal', 'bitcoin doubler',
		'loan offer', 'work from home', 'make money fast', 'rank your website',
		'buy followers', 'cheap traffic', 'binary option',
	);
	foreach ( $terms as $term ) {
		if ( false !== strpos( $lower, $term ) ) {
			$score += 3;
			break;
		}
	}

	// 4. Cyrillic / CJK bulk in an English-language B2B form.
	if ( preg_match_all( '/[\x{0400}-\x{04FF}\x{4E00}-\x{9FFF}]/u', $blob ) > 8 ) {
		$score += 3;
	}

	// 5. Disposable-domain sender.
	$email = isset( $data['email'] ) ? strtolower( (string) $data['email'] ) : '';
	if ( $email && preg_match( '/@(mailinator|guerrillamail|10minutemail|tempmail|yopmail|trashmail|sharklasers)\./', $email ) ) {
		$score += 4;
	}

	// 6. Name field carrying a URL or e-mail address.
	$name = isset( $data['name'] ) ? (string) $data['name'] : '';
	if ( $name && preg_match( '#https?://|www\.|@#i', $name ) ) {
		$score += 3;
	}

	// 7. No spaces anywhere in a long body: machine-generated.
	if ( strlen( $blob ) > 60 && false === strpos( trim( $blob ), ' ' ) ) {
		$score += 3;
	}

	return (int) apply_filters( 'sc_enq_spam_score', $score, $data );
}

/**
 * Reject a submission identical to one already received from the same IP.
 * Stops the common "same payload replayed a few hundred times" pattern.
 *
 * @return bool True when this exact payload was already seen.
 */
function sc_enq_is_duplicate( $type, $data, $ip ) {
	$fingerprint = md5( $type . '|' . wp_json_encode( $data ) );
	$key         = sc_enq_rl_key( 'dup_' . $fingerprint, $ip );
	if ( get_transient( $key ) ) {
		return true;
	}
	set_transient( $key, 1, 3 * HOUR_IN_SECONDS );
	return false;
}

/**
 * Strip anything that could break out of a mail header into a new one.
 * sanitize_text_field already removes newlines; this is defence in depth for
 * the display-name half of Reply-To, which is attacker-supplied.
 */
function sc_enq_header_safe( $value ) {
	$value = (string) $value;
	$value = str_replace( array( "\r", "\n", "\t", '%0a', '%0d', '%0A', '%0D' ), ' ', $value );
	$value = preg_replace( '/[<>";]/', '', $value );
	return trim( mb_substr( (string) $value, 0, 70 ) );
}

/**
 * Accept an optional attachment safely.
 *
 * Hardening over the previous version:
 *  - verifies the real MIME type from file content, not the client-supplied name
 *  - generates a random filename so uploads are not enumerable or guessable
 *  - drops the size cap to 5 MB
 *  - rejects anything with a double extension or a PHP-ish tail
 *
 * @return string Uploaded file URL, or '' when nothing valid was supplied.
 */
function sc_enq_handle_upload() {
	require_once ABSPATH . 'wp-admin/includes/file.php';

	if ( empty( $_FILES['sc_file']['name'] ) ) {
		return '';
	}
	$file = $_FILES['sc_file']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- validated below via wp_handle_upload.

	if ( ! isset( $file['error'] ) || UPLOAD_ERR_OK !== (int) $file['error'] ) {
		return '';
	}
	if ( ! isset( $file['size'] ) || (int) $file['size'] < 1 || (int) $file['size'] > 5 * 1024 * 1024 ) {
		return '';
	}
	if ( ! isset( $file['tmp_name'] ) || ! is_uploaded_file( $file['tmp_name'] ) ) {
		return '';
	}

	$allowed = array(
		'pdf'  => 'application/pdf',
		'doc'  => 'application/msword',
		'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'jpg'  => 'image/jpeg',
		'jpeg' => 'image/jpeg',
		'png'  => 'image/png',
	);

	$original = (string) $file['name'];

	// Reject double extensions and executable tails outright (shell.php.pdf).
	if ( preg_match( '/\.(php\d?|phtml|phar|js|html?|htaccess|sh|exe|svg)(\.|$)/i', $original ) ) {
		return '';
	}

	$check = wp_check_filetype_and_ext( $file['tmp_name'], $original, $allowed );
	if ( empty( $check['ext'] ) || empty( $check['type'] ) || ! in_array( $check['type'], $allowed, true ) ) {
		return '';
	}

	// Confirm the real content type independently of the filename.
	if ( function_exists( 'finfo_open' ) ) {
		$finfo = finfo_open( FILEINFO_MIME_TYPE );
		if ( false !== $finfo ) {
			$real = finfo_file( $finfo, $file['tmp_name'] );
			finfo_close( $finfo );
			if ( $real && ! in_array( $real, $allowed, true ) ) {
				return '';
			}
		}
	}

	// Unguessable filename: the URL is emailed to staff, never listed publicly.
	$file['name'] = 'enquiry-' . gmdate( 'Ymd' ) . '-' . wp_generate_password( 16, false, false ) . '.' . $check['ext'];

	// Store attachments in a dedicated folder that can never execute code or
	// be listed, instead of the public dated uploads folders.
	$dir_filter = 'sc_enq_private_upload_dir';
	add_filter( 'upload_dir', $dir_filter );
	$moved = wp_handle_upload(
		$file,
		array(
			'test_form' => false,
			'mimes'     => $allowed,
		)
	);
	remove_filter( 'upload_dir', $dir_filter );

	if ( is_array( $moved ) && empty( $moved['error'] ) && ! empty( $moved['url'] ) ) {
		return esc_url_raw( $moved['url'] );
	}
	return '';
}

/**
 * Route enquiry attachments to uploads/sc-enquiries/ and lock that folder down.
 */
function sc_enq_private_upload_dir( $dirs ) {
	$dirs['subdir'] = '/sc-enquiries';
	$dirs['path']   = $dirs['basedir'] . '/sc-enquiries';
	$dirs['url']    = $dirs['baseurl'] . '/sc-enquiries';
	if ( ! is_dir( $dirs['path'] ) ) {
		wp_mkdir_p( $dirs['path'] );
	}
	$ht = $dirs['path'] . '/.htaccess';
	if ( ! file_exists( $ht ) ) {
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
		@file_put_contents(
			$ht,
			"Options -Indexes -ExecCGI\n"
			. "<FilesMatch \"\\.(php\\d?|phtml|phar|pl|py|cgi|sh|html?|svg|js)$\">\n  Require all denied\n</FilesMatch>\n"
			. "<IfModule mod_headers.c>\n  Header set X-Robots-Tag \"noindex, nofollow\"\n  Header set X-Content-Type-Options \"nosniff\"\n  Header set Content-Disposition \"attachment\"\n</IfModule>\n"
		);
	}
	$idx = $dirs['path'] . '/index.php';
	if ( ! file_exists( $idx ) ) {
		@file_put_contents( $idx, "<?php // Silence is golden.\n" ); // phpcs:ignore
	}
	return $dirs;
}

/**
 * Delete stored enquiries (and their PII: name, e-mail, phone, IP) after a
 * retention period. Defaults to 24 months; filter to change or return 0 to
 * disable. Keeps the site from accumulating personal data indefinitely.
 */
function sc_enq_purge_expired() {
	$months = (int) apply_filters( 'sc_enq_retention_months', 24 );
	if ( $months < 1 ) {
		return;
	}
	$old = get_posts(
		array(
			'post_type'        => 'sc_enquiry',
			'post_status'      => 'any',
			'posts_per_page'   => 100,
			'fields'           => 'ids',
			'no_found_rows'    => true,
			'suppress_filters' => true,
			'date_query'       => array(
				array(
					'before' => $months . ' months ago',
				),
			),
		)
	);
	foreach ( $old as $id ) {
		wp_delete_post( $id, true );
	}
}
add_action( 'sc_enq_daily_purge', 'sc_enq_purge_expired' );

add_action(
	'init',
	function () {
		if ( ! wp_next_scheduled( 'sc_enq_daily_purge' ) ) {
			wp_schedule_event( time() + HOUR_IN_SECONDS, 'daily', 'sc_enq_daily_purge' );
		}
	}
);
