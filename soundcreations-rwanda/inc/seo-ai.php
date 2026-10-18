<?php
/**
 * AI search access layer (v2.0.0, corrected in v2.1.0).
 *
 * v2.1.0 corrections after the post-implementation audit (see
 * docs/SEO-AUDIT-2026-10.md):
 *  - robots.txt: 2.0.0 added explicit "Allow: /" groups for named AI bots.
 *    Under RFC 9309 a crawler obeys only the most specific group that names
 *    it, so those bots would have IGNORED the crawl-hygiene rules in
 *    sound-creations-core (wp-json, feeds, search, author URLs). Removed.
 *    Search and user-triggered retrieval crawlers (OAI-SearchBot,
 *    ChatGPT-User, PerplexityBot, Claude-SearchBot, Bingbot, Googlebot) are
 *    already allowed by the "User-agent: *" group, which is what we want.
 *    Training-only crawlers are a business decision, not an SEO one: set the
 *    scrw_ai_training_policy filter to 'block' to disallow them. Blocking
 *    them does not affect Google Search, AI Overviews or ChatGPT search.
 *  - /llms.txt: no major search or answer engine documents using it (Google
 *    says no special files are needed for AI features). It is now OFF by
 *    default and can be turned on with the scrw_llms_enabled filter if a
 *    verified use case appears.
 *  - speakable: removed. Google supports it only in beta for news publishers
 *    in US English, so it added schema with no benefit to this site.
 *
 * Rules kept (see AGENTS.md): Rank Math owns <head>; one JSON-LD graph; no
 * invented prices, ratings, reviews, stock or coordinates.
 *
 * @package SoundCreationsRwanda
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ---------- helpers ---------- */

/** A setting value or empty string. */
function scrw_ai_setting( $key ) {
	return function_exists( 'scrw_schema_setting' ) ? scrw_schema_setting( $key ) : '';
}

/** Permalink of a published page by path, or ''. */
function scrw_ai_page_url( $path ) {
	$page = get_page_by_path( $path );
	if ( $page && 'publish' === $page->post_status ) {
		return get_permalink( $page );
	}
	return '';
}

/** Markdown list of published posts of a type: "- [Title](url): excerpt". */
function scrw_ai_list( $post_type, $limit = 30 ) {
	if ( ! post_type_exists( $post_type ) ) {
		return '';
	}
	$posts = get_posts(
		array(
			'post_type'        => $post_type,
			'post_status'      => 'publish',
			'numberposts'      => $limit,
			'orderby'          => 'menu_order title',
			'order'            => 'ASC',
			'suppress_filters' => false,
		)
	);
	$out = '';
	foreach ( $posts as $p ) {
		$title = wp_strip_all_tags( get_the_title( $p ) );
		$desc  = has_excerpt( $p ) ? wp_strip_all_tags( get_the_excerpt( $p ) ) : '';
		if ( '' === $desc ) {
			$rm = get_post_meta( $p->ID, 'rank_math_description', true );
			$desc = $rm ? wp_strip_all_tags( $rm ) : '';
		}
		$desc = trim( preg_replace( '/\s+/', ' ', $desc ) );
		if ( strlen( $desc ) > 180 ) {
			$desc = rtrim( substr( $desc, 0, 177 ) ) . '...';
		}
		$out .= '- [' . $title . '](' . get_permalink( $p ) . ')' . ( $desc ? ': ' . $desc : '' ) . "\n";
	}
	return $out;
}

/** Build the llms.txt body. Cached for 12 hours, cleared on any publish. */
function scrw_ai_llms_txt() {
	$cached = get_transient( 'scrw_llms_txt' );
	if ( is_string( $cached ) && '' !== $cached ) {
		return $cached;
	}

	$name  = scrw_ai_setting( 'company_name' );
	$name  = $name ? $name : 'Sound Creations Ltd Rwanda';
	$phone = scrw_ai_setting( 'phone' );
	$email = scrw_ai_setting( 'email' );
	$map   = scrw_ai_setting( 'map_url' );
	$home  = home_url( '/' );

	$t  = '# ' . $name . "\n\n";
	$t .= '> ' . $name . ' is the Kigali, Rwanda company of the Sound Creations group. It supplies, installs and services professional audio, acoustics, lighting and audio-visual systems, and is the authorised Yamaha music and pro-audio distributor in Rwanda. Showroom: KN1 Rd, Muhima, Kigali.' . "\n\n";

	$t .= "## Key facts\n\n";
	$t .= '- Official website: ' . $home . "\n";
	$t .= "- Location: KN1 Rd, Muhima, Kigali, Rwanda\n";
	if ( $phone ) {
		$t .= '- Phone / WhatsApp: ' . $phone . "\n";
	}
	if ( $email ) {
		$t .= '- Email: ' . $email . "\n";
	}
	if ( $map ) {
		$t .= '- Map: ' . $map . "\n";
	}
	$t .= "- Areas served: Rwanda (Kigali and all provinces), with projects in the DRC and East Africa\n";
	$t .= "- Group website: " . SCRW_GROUP_URL . "\n";
	$t .= "- Note: Yamaha here means Yamaha musical instruments and professional audio, not Yamaha motorcycles.\n";
	$t .= "- Prices: given on request in RWF (WhatsApp or phone); products with a listed RWF price show it on the product page.\n\n";

	$t .= "## Main pages\n\n";
	$pages = array(
		'about'                  => 'About Sound Creations Rwanda',
		'yamaha'                 => 'Yamaha Rwanda (authorised distributor)',
		'fane'                   => 'FANE loudspeakers',
		'contact'                => 'Contact and showroom',
		'request-a-consultation' => 'Request a consultation',
	);
	foreach ( $pages as $path => $label ) {
		$u = scrw_ai_page_url( $path );
		if ( $u ) {
			$t .= '- [' . $label . '](' . $u . ")\n";
		}
	}
	foreach ( array( 'sc_product' => 'Products', 'sc_solution' => 'Solutions', 'sc_project' => 'Projects', 'sc_brand' => 'Brands' ) as $type => $label ) {
		$link = post_type_exists( $type ) ? get_post_type_archive_link( $type ) : '';
		if ( $link ) {
			$t .= '- [' . $label . '](' . $link . ")\n";
		}
	}
	$t .= "\n";

	$sections = array(
		'sc_solution' => array( 'Solutions and sectors', 40 ),
		'sc_service'  => array( 'Services', 20 ),
		'sc_project'  => array( 'Projects in Rwanda (case studies)', 30 ),
		'sc_brand'    => array( 'Brands supplied', 30 ),
		'post'        => array( 'Buyer guides and news', 20 ),
	);
	foreach ( $sections as $type => $cfg ) {
		$list = scrw_ai_list( $type, $cfg[1] );
		if ( '' !== $list ) {
			$t .= '## ' . $cfg[0] . "\n\n" . $list . "\n";
		}
	}

	$t .= "## Optional\n\n";
	$t .= '- [XML sitemap](' . home_url( '/sitemap_index.xml' ) . ")\n";
	if ( post_type_exists( 'sc_product' ) ) {
		$t .= '- Full product catalogue: ' . get_post_type_archive_link( 'sc_product' ) . "\n";
	}

	$t = (string) apply_filters( 'scrw_llms_txt', $t );
	set_transient( 'scrw_llms_txt', $t, 12 * HOUR_IN_SECONDS );
	return $t;
}

add_action(
	'transition_post_status',
	function ( $new, $old ) {
		if ( 'publish' === $new || 'publish' === $old ) {
			delete_transient( 'scrw_llms_txt' );
		}
	},
	10,
	2
);

/* ---------- 1. Serve /llms.txt (no rewrite rules needed) ---------- */
add_action(
	'parse_request',
	function () {
		$path = isset( $_SERVER['REQUEST_URI'] ) ? (string) wp_parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ), PHP_URL_PATH ) : '';
		$base = (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH );
		if ( untrailingslashit( $base ) . '/llms.txt' !== $path ) {
			return;
		}
		if ( ! apply_filters( 'scrw_llms_enabled', false ) ) {
			return; // Off by default: falls through to the normal WordPress 404.
		}
		status_header( 200 );
		nocache_headers();
		header( 'Content-Type: text/plain; charset=utf-8' );
		header( 'X-Robots-Tag: noindex, follow' );
		header( 'Cache-Control: public, max-age=3600' );
		echo scrw_ai_llms_txt(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- plain text.
		exit;
	},
	1
);

/* ---------- 2. robots.txt: optional training-crawler opt-out ---------- */
add_filter(
	'robots_txt',
	function ( $output, $public ) {
		if ( ! $public || 'block' !== apply_filters( 'scrw_ai_training_policy', 'default' ) ) {
			return $output;
		}
		// Training-only agents. Search / answer crawlers are deliberately NOT
		// listed so they keep following the "User-agent: *" hygiene rules.
		$train = array( 'GPTBot', 'Google-Extended', 'Applebot-Extended', 'ClaudeBot', 'CCBot', 'meta-externalagent' );
		$lines = array( '', '# --- Sound Creations Rwanda: AI model-training crawlers (business opt-out) ---' );
		foreach ( $train as $bot ) {
			$lines[] = 'User-agent: ' . $bot;
		}
		$lines[] = 'Disallow: /';
		$lines[] = '';
		return rtrim( $output ) . "\n" . implode( "\n", $lines ) . "\n";
	},
	20,
	2
);
