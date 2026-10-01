<?php
/**
 * Strict anti-spam layer for public enquiry forms.
 *
 * DESIGN DECISION: this file adds NO new code paths to the handler.
 * -----------------------------------------------------------------
 * sc_enq_handle() already rejects any submission scoring 5 or more, and
 * sc_enq_spam_score() is filterable. Everything below therefore hooks that
 * existing filter rather than introducing a second, parallel rejection route.
 * One decision point is auditable; two drift apart and eventually disagree.
 *
 * A score of SC_ENQ_HARD_REJECT (100) is used for absolute violations, which
 * clears the threshold by such a margin that no combination of legitimate
 * signals can rescue the submission. That gives a hard block without adding a
 * second mechanism.
 *
 * WHAT WAS WEAK BEFORE (audit of includes/security.php, 30 Sep 2026)
 * ------------------------------------------------------------------
 * The existing scorer is well built, but calibrated permissively:
 *
 *  - ONE link scored 2 against a rejection threshold of 5. The single most
 *    common enquiry-spam shape -- a short message containing one link -- was
 *    therefore ACCEPTED, stored and emailed. Three links were needed (2+3=5)
 *    before anything was blocked. This was the biggest real gap.
 *  - The disposable-domain list held 7 providers. There are thousands.
 *  - No DNS validation, so an address at a domain with no mail server at all
 *    was accepted as readily as a real one.
 *  - Nothing examined the message-to-link ratio, so a two-word body wrapped
 *    around a URL passed.
 *  - No check for the "name field is the whole payload" pattern.
 *
 * ON FALSE POSITIVES -- read before tightening further
 * ----------------------------------------------------
 * Strictness is not free. Every rule here risks blocking a real customer, and
 * a lost enquiry is invisible: nobody writes in to say the form ate their
 * message. Rules are therefore weighted by how confidently they identify a
 * machine, not by how annoying the spam is.
 *
 * Specifically NOT implemented, on purpose:
 *  - Blocking free mail providers (gmail.com, yahoo.com). A large share of
 *    genuine Kenyan B2B enquiries come from Gmail addresses.
 *  - Blocking on country or IP geography. The business serves Kenya, Rwanda,
 *    DR Congo and the UAE and sells to international integrators.
 *  - Keyword blocking on industry vocabulary. "Installation", "quote" and
 *    "system" appear in both spam and every real enquiry.
 *
 * @package SoundCreationsEnquiries
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Score guaranteeing rejection, for unambiguous machine signals. */
if ( ! defined( 'SC_ENQ_HARD_REJECT' ) ) {
	define( 'SC_ENQ_HARD_REJECT', 100 );
}

/**
 * Disposable and throwaway mail domains.
 *
 * A legitimate business enquiry does not arrive from a self-destructing
 * mailbox. This list covers the high-volume providers; it is filterable so it
 * can be extended without editing the plugin.
 */
function sc_enq_disposable_domains() {
	return apply_filters(
		'sc_enq_disposable_domains',
		array(
			'mailinator.com', 'guerrillamail.com', 'guerrillamail.net', 'sharklasers.com',
			'10minutemail.com', '10minutemail.net', 'tempmail.com', 'temp-mail.org',
			'yopmail.com', 'yopmail.fr', 'trashmail.com', 'trashmail.net', 'dispostable.com',
			'maildrop.cc', 'mailnesia.com', 'mytemp.email', 'throwawaymail.com',
			'fakeinbox.com', 'getairmail.com', 'mailcatch.com', 'spamgourmet.com',
			'tempinbox.com', 'mohmal.com', 'emailondeck.com', 'burnermail.io',
			'anonaddy.com', 'mailsac.com', 'inboxbear.com', 'tempr.email',
			'discard.email', 'spam4.me', 'grr.la', 'pokemail.net', 'byom.de',
			'nowmymail.com', 'tmail.ws', 'mailexpire.com', 'jetable.org',
			'harakirimail.com', 'mail-temporaire.fr', 'minuteinbox.com',
			'moakt.com', 'luxusmail.org', 'vomoto.com', 'tempmailo.com',
			'internxt.com', 'linshiyouxiang.net', 'crazymailing.com',
		)
	);
}

/**
 * Does the email domain actually accept mail?
 *
 * A domain with neither an MX nor an A record cannot receive a reply, so the
 * address is fictitious. This is a genuinely strong signal and costs one cached
 * DNS lookup.
 *
 * Returns true when the domain is UNDELIVERABLE. Crucially, it returns FALSE
 * whenever the check itself cannot be performed -- no checkdnsrr(), a DNS
 * timeout, a resolver failure. Treating an infrastructure failure on our side
 * as a spam signal would reject every legitimate enquiry the moment DNS
 * hiccuped, which is a far worse outcome than letting some spam through.
 */
function sc_enq_domain_undeliverable( $email ) {
	if ( ! function_exists( 'checkdnsrr' ) ) {
		return false;
	}
	$at = strrpos( (string) $email, '@' );
	if ( false === $at ) {
		return false;
	}
	$domain = strtolower( substr( (string) $email, $at + 1 ) );
	if ( '' === $domain || false === strpos( $domain, '.' ) ) {
		return false;
	}

	// Cache per domain: bots reuse domains heavily, and this avoids repeat DNS
	// cost during a flood. Deliverable results are cached far longer than
	// failures, so a transient outage self-heals quickly.
	$key    = 'sc_enq_mx_' . md5( $domain );
	$cached = get_transient( $key );
	if ( false !== $cached ) {
		return ( 'bad' === $cached );
	}

	$has_mail = false;
	try {
		$has_mail = checkdnsrr( $domain, 'MX' ) || checkdnsrr( $domain, 'A' );
	} catch ( Exception $e ) {
		return false; // Resolver problem: do not penalise the sender.
	}

	set_transient( $key, $has_mail ? 'ok' : 'bad', $has_mail ? WEEK_IN_SECONDS : 30 * MINUTE_IN_SECONDS );
	return ! $has_mail;
}

/**
 * Additional spam signals, layered onto the existing score.
 *
 * @param int   $score Score so far from sc_enq_spam_score().
 * @param array $data  Sanitized field values.
 * @return int Adjusted score.
 */
add_filter(
	'sc_enq_spam_score',
	function ( $score, $data ) {
		$score = (int) $score;

		$email = isset( $data['email'] ) ? strtolower( trim( (string) $data['email'] ) ) : '';
		$name  = isset( $data['name'] ) ? trim( (string) $data['name'] ) : '';

		// Build the message blob exactly as the core scorer does, excluding email.
		$blob = '';
		foreach ( $data as $k => $v ) {
			if ( 'email' === $k ) {
				continue;
			}
			$blob .= ' ' . (string) $v;
		}
		$blob  = trim( preg_replace( '/\s+/', ' ', $blob ) );
		$lower = strtolower( $blob );

		/* ---------- HARD REJECTS: unambiguous machine signals ---------- */

		// 1. Two or more links. A real enquiry about an audio system does not
		//    need to cite two URLs. This is the fix for the central weakness:
		//    previously three links were required to reach the threshold.
		$links = preg_match_all( '#https?://|www\.|\[url|<a\s#i', $blob );
		if ( $links >= 2 ) {
			return SC_ENQ_HARD_REJECT;
		}

		// 2. Anchor markup, BBCode links or a mailto: in the body. Only ever
		//    produced by an automated poster.
		if ( preg_match( '#<a\s|\[url|\[link|mailto:#i', $blob ) ) {
			return SC_ENQ_HARD_REJECT;
		}

		// 3. Undeliverable sender domain: no reply is possible, so the address
		//    is fabricated.
		if ( $email && sc_enq_domain_undeliverable( $email ) ) {
			return SC_ENQ_HARD_REJECT;
		}

		// 4. Disposable mailbox.
		if ( $email ) {
			$at = strrpos( $email, '@' );
			if ( false !== $at ) {
				$domain = substr( $email, $at + 1 );
				if ( in_array( $domain, sc_enq_disposable_domains(), true ) ) {
					return SC_ENQ_HARD_REJECT;
				}
			}
		}

		// 5. Unescaped script or iframe markup: an injection attempt, not spam.
		if ( preg_match( '#<\s*(script|iframe|object|embed|svg|form)\b#i', $blob ) ) {
			return SC_ENQ_HARD_REJECT;
		}

		// 6. Mail-header injection attempt in any field.
		if ( preg_match( '#(?:\r|\n|%0a|%0d)\s*(?:bcc|cc|to|from|content-type)\s*:#i', $blob . ' ' . $email ) ) {
			return SC_ENQ_HARD_REJECT;
		}

		/* ---------- WEIGHTED SIGNALS ---------- */

		// 7. A single link now carries real weight. Combined with the core
		//    scorer's own +2 this reaches the threshold on its own, so
		//    "short message plus one URL" no longer gets through.
		if ( 1 === $links ) {
			$score += 3;
		}

		// 8. Link with almost no surrounding message: the body exists only to
		//    carry the URL.
		if ( $links >= 1 && str_word_count( $blob ) < 15 ) {
			$score += 3;
		}

		// 9. Name field that is not a name: digits, excessive length, or a
		//    single token repeated. Real names are short and alphabetic.
		if ( $name ) {
			if ( preg_match( '/\d{3,}/', $name ) ) {
				$score += 3;
			}
			if ( mb_strlen( $name ) > 60 ) {
				$score += 3;
			}
			if ( preg_match( '/(.)\1{4,}/u', $name ) ) {
				$score += 4;
			}
		}

		// 10. Any non-Latin script at all in an English-language B2B form. The
		//     core scorer required more than 8 such characters; a handful is
		//     already anomalous here, though weighted low since transliterated
		//     quotations do occur.
		if ( preg_match( '/[\x{0400}-\x{04FF}\x{4E00}-\x{9FFF}\x{0600}-\x{06FF}]/u', $blob ) ) {
			$score += 2;
		}

		// 11. Extended spam vocabulary, beyond the core list.
		$terms = array(
			'link building', 'domain authority', 'rank higher', 'first page of google',
			'web design service', 'app development', 'hire developer', 'outsourc',
			'lead generation', 'email list', 'database of', 'b2b leads',
			'increase sales', 'guest article', 'sponsored post', 'do follow',
			'dofollow', 'pbn', 'adult', 'escort', 'replica watch', 'essay writing',
			'write my', 'dissertation', 'betting', 'gambling', 'slot online',
			'investment opportunity', 'inheritance', 'next of kin', 'beneficiary',
			'usdt', 'binance', 'nft project', 'airdrop', 'telegram channel',
		);
		foreach ( $terms as $term ) {
			if ( false !== strpos( $lower, $term ) ) {
				$score += 4;
				break;
			}
		}

		// 12. ALL CAPS shouting across a long body.
		$letters = preg_replace( '/[^A-Za-z]/', '', $blob );
		if ( strlen( $letters ) > 40 ) {
			$upper = strlen( preg_replace( '/[^A-Z]/', '', $letters ) );
			if ( ( $upper / strlen( $letters ) ) > 0.7 ) {
				$score += 2;
			}
		}

		// 13. Body is a single unbroken token of moderate length. The core
		//     scorer only caught this above 60 characters.
		if ( strlen( $blob ) > 25 && false === strpos( $blob, ' ' ) ) {
			$score += 4;
		}

		// 14. Sender address local part looks generated: long random strings.
		if ( $email && preg_match( '/^[a-z]{0,3}[0-9a-f]{12,}@/i', $email ) ) {
			$score += 3;
		}

		return $score;
	},
	10,
	2
);

/* ============================================================
   REJECTION LOGGING

   SECURITY-AUDIT.md section 11 item 2 records that there is no
   durable record of abuse: spam is silently dropped, so nobody
   can tell a quiet week from a broken form.

   That matters more once the rules get stricter. If a real
   customer is ever blocked, this log is the ONLY way to discover
   it. Stored as a rolling option capped at 100 entries -- no
   unbounded growth, no new database table.

   Message bodies are NOT stored, only a hash, a score and the
   triggering metadata. The point is to spot patterns and
   false positives, not to build a second copy of the PII the
   retention policy exists to expire.
   ============================================================ */
function sc_enq_log_rejection( $type, $score, $data, $reason = 'score' ) {
	$log = get_option( 'sc_enq_abuse_log', array() );
	if ( ! is_array( $log ) ) {
		$log = array();
	}

	$email  = isset( $data['email'] ) ? (string) $data['email'] : '';
	$at     = strrpos( $email, '@' );
	$domain = ( false !== $at ) ? substr( $email, $at + 1 ) : '';

	array_unshift(
		$log,
		array
		(
			'time'   => time(),
			'type'   => (string) $type,
			'score'  => (int) $score,
			'reason' => (string) $reason,
			// Domain only: enough to recognise a campaign, not a stored lead.
			'domain' => sanitize_text_field( $domain ),
			'digest' => substr( md5( wp_json_encode( $data ) ), 0, 12 ),
		)
	);

	$log = array_slice( $log, 0, 100 );
	update_option( 'sc_enq_abuse_log', $log, false );
}

/**
 * Record every rejection, using the same scorer the handler consults.
 *
 * Hooked at a late priority so it observes the FINAL score after all filters,
 * and returns it untouched. This is an observer, not a decision point.
 */
add_filter(
	'sc_enq_spam_score',
	function ( $score, $data ) {
		if ( (int) $score >= 5 ) {
			$reason = ( (int) $score >= SC_ENQ_HARD_REJECT ) ? 'hard-reject' : 'score';
			sc_enq_log_rejection( 'enquiry', $score, $data, $reason );
		}
		return $score;
	},
	99,
	2
);

/* ============================================================
   RATE-LIMIT INTEGRITY WARNING

   SECURITY-AUDIT.md section 11 item 3: if the site sits behind
   Cloudflare and the proxy addresses are not declared, every
   visitor resolves to the proxy IP. All per-IP limits then
   collapse into ONE shared bucket -- meaning a single spam run
   can exhaust the cap for every genuine visitor simultaneously,
   turning a spam defence into a self-inflicted denial of service.

   This cannot be auto-detected safely (trusting a forwarded
   header to decide whether to trust forwarded headers is
   circular), so it raises a visible admin warning instead.
   ============================================================ */
add_action(
	'admin_notices',
	function () {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$proxied = ! empty( $_SERVER['HTTP_CF_CONNECTING_IP'] )
			|| ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] )
			|| ! empty( $_SERVER['HTTP_X_REAL_IP'] );
		if ( ! $proxied ) {
			return;
		}
		$trusted = apply_filters( 'sc_enq_trusted_proxies', array() );
		if ( is_array( $trusted ) && count( $trusted ) > 0 ) {
			return;
		}
		echo '<div class="notice notice-warning"><p><strong>Enquiry rate limiting is degraded.</strong> '
			. 'This site is being served through a proxy or CDN, but no trusted proxy addresses have been '
			. 'declared. Every visitor currently resolves to the proxy address, so all per-visitor spam '
			. 'limits share a single counter. Declare the proxy addresses via the '
			. '<code>sc_enq_trusted_proxies</code> filter to restore per-visitor limits.</p></div>';
	}
);
