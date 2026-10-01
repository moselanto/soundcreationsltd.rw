<?php
/**
 * SEO and structured data: meta description, canonical, Open Graph, Twitter, JSON-LD.
 * Lives in the plugin so it survives theme changes.
 *
 * @package SoundCreationsCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Read a business setting, using the theme helper when present. */
function sc_core_get( $key, $default = '' ) {
	if ( function_exists( 'sc_setting' ) ) {
		return sc_setting( $key, $default );
	}
	$o = get_option( 'soundcreations_settings', array() );
	if ( is_array( $o ) && ! empty( $o[ $key ] ) ) {
		return $o[ $key ];
	}
	return $default;
}

/**
 * Trim a string to a meta-description length on a word boundary.
 *
 * Google renders roughly 155-160 characters. Cutting mid-word looks broken,
 * so back up to the last space and add an ellipsis only if we actually cut.
 */
function sc_seo_trim( $text, $limit = 158 ) {
	$text = trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( (string) $text ) ) );
	if ( '' === $text ) {
		return '';
	}
	if ( function_exists( 'mb_strlen' ) ? mb_strlen( $text ) <= $limit : strlen( $text ) <= $limit ) {
		return $text;
	}
	$cut = function_exists( 'mb_substr' ) ? mb_substr( $text, 0, $limit ) : substr( $text, 0, $limit );
	$sp  = strrpos( $cut, ' ' );
	if ( false !== $sp && $sp > (int) ( $limit * 0.6 ) ) {
		$cut = substr( $cut, 0, $sp );
	}
	return rtrim( $cut, " ,.;:-" ) . '...';
}

/**
 * A description for a listing page, written from the thing being listed.
 *
 * Archives and template-rendered pages have no body text to summarise, so
 * without this they all inherited the single site tagline.
 */
function sc_seo_type_description( $post_type, $title = '' ) {
	$brand = get_bloginfo( 'name' );
	$title = trim( (string) $title );

	// Archive context has no single title to weave in. The templates below are
	// written without a title placeholder for exactly that reason: substituting
	// an empty string into the singular copy produced "products supplied and
	// supported..." on /brands/ and "A completed  installation" (double space)
	// on /projects/.
	if ( '' === $title ) {
		$archive = array(
			'sc_solution' => 'Professional audio, acoustic and AV solutions engineered, installed and calibrated by %s across Kenya, Rwanda, DR Congo and the UAE.',
			'sc_project'  => 'Completed audio, acoustic and AV installations by %s - venues, system design and commissioning detail from across Africa and the Middle East.',
			'sc_service'  => 'Consultancy, distribution, integration and after-sale support from %s for professional audio, acoustic and AV systems.',
			'sc_brand'    => 'The professional audio, acoustic and AV brands distributed and supported across East Africa by %s.',
			'sc_product'  => 'Professional audio and loudspeaker components supplied by %s, authorised distributor across East Africa and the Middle East.',
			'sc_resource' => 'Technical resources and documentation from %s for professional audio, acoustic and AV systems.',
		);
		if ( isset( $archive[ $post_type ] ) ) {
			return sc_seo_trim( sprintf( $archive[ $post_type ], $brand ) );
		}
		return sc_core_get( 'tagline', get_bloginfo( 'description' ) );
	}

	$map = array(
		'sc_solution' => '%2$s from %1$s - engineered, installed and calibrated for venues across Kenya, Rwanda, DR Congo and the UAE.',
		'sc_project'  => '%2$s - a completed installation by %1$s, with system design, equipment and commissioning detail.',
		'sc_service'  => '%2$s from %1$s: specialist support for audio, acoustic and AV systems across East Africa and the Middle East.',
		'sc_brand'    => '%2$s products supplied and supported in East Africa by %1$s, an authorised distribution and dealership partner.',
		'sc_product'  => '%2$s - specifications, applications and availability from %1$s, authorised distributor in East Africa.',
		'sc_resource' => '%2$s - technical documentation from %1$s for professional audio, acoustic and AV systems.',
	);
	if ( isset( $map[ $post_type ] ) ) {
		return sc_seo_trim( sprintf( $map[ $post_type ], $brand, $title ) );
	}
	return sc_seo_trim( sprintf( '%1$s from %2$s - engineered audio, acoustic and AV solutions for Africa and the Middle East.', $title, $brand ) );
}

/** A description for a taxonomy term archive that has no term description set. */
function sc_seo_term_description( $term ) {
	$brand = get_bloginfo( 'name' );
	$name  = isset( $term->name ) ? $term->name : '';
	$by_tax = array(
		'sc_industry'         => '%2$s audio, acoustic and AV installations by %1$s - system design, equipment and commissioning.',
		'sc_location'         => 'Audio, acoustic and AV projects delivered by %1$s in %2$s.',
		'sc_brand_tax'        => '%2$s equipment supplied and supported in East Africa by %1$s.',
		'sc_product_category' => '%2$s available from %1$s, authorised distributor across East Africa and the Middle East.',
	);
	$tax = isset( $term->taxonomy ) ? $term->taxonomy : '';
	if ( isset( $by_tax[ $tax ] ) ) {
		return sc_seo_trim( sprintf( $by_tax[ $tax ], $brand, $name ) );
	}
	return sc_seo_trim( sprintf( '%1$s - %2$s.', $name, $brand ) );
}

function sc_seo_description() {
	if ( is_singular() ) {
		$id = get_queried_object_id();
		$d  = get_post_meta( $id, '_sc_seo_desc', true );
		if ( $d ) {
			return $d;
		}
		$sum = get_post_meta( $id, '_sc_summary', true );
		if ( $sum ) {
			return $sum;
		}
		$ex = get_the_excerpt( $id );
		if ( $ex ) {
			return sc_seo_trim( $ex );
		}
		// Most pages here render from PHP templates rather than post_content,
		// so get_the_excerpt() returns nothing and -- before this -- every one
		// of them fell through to the site tagline. An audit on 22 Sep 2026
		// found all 62 indexed URLs sharing one description, which Google
		// generally discards in favour of its own snippet.
		$post = get_post( $id );
		if ( $post instanceof WP_Post ) {
			$body = sc_seo_trim( strip_shortcodes( (string) $post->post_content ) );
			if ( '' !== $body ) {
				return $body;
			}
		}
		return sc_seo_type_description( get_post_type( $id ), (string) get_the_title( $id ) );
	}
	if ( is_tax() || is_category() || is_tag() ) {
		$t = term_description();
		if ( $t ) {
			return sc_seo_trim( $t );
		}
		$term = get_queried_object();
		if ( $term && ! is_wp_error( $term ) ) {
			return sc_seo_term_description( $term );
		}
	}
	if ( is_post_type_archive() ) {
		$pt = get_query_var( 'post_type' );
		if ( is_array( $pt ) ) {
			$pt = reset( $pt );
		}
		return sc_seo_type_description( (string) $pt );
	}
	return sc_core_get( 'tagline', get_bloginfo( 'description' ) );
}

function sc_seo_image() {
	if ( is_singular() && has_post_thumbnail( get_queried_object_id() ) ) {
		$src = wp_get_attachment_image_src( get_post_thumbnail_id( get_queried_object_id() ), 'large' );
		if ( $src ) {
			return $src[0];
		}
	}
	$icon = get_site_icon_url( 512 );
	return $icon ? $icon : '';
}

function sc_seo_canonical() {
	if ( is_singular() ) {
		return get_permalink();
	}
	if ( is_post_type_archive() ) {
		return get_post_type_archive_link( get_query_var( 'post_type' ) );
	}
	if ( is_tax() || is_category() || is_tag() ) {
		$term = get_queried_object();
		$link = $term ? get_term_link( $term ) : '';
		return is_wp_error( $link ) ? '' : $link;
	}
	if ( is_front_page() ) {
		return home_url( '/' );
	}
	return '';
}

add_action( 'wp_head', 'sc_seo_head', 1 );
function sc_seo_head() {
	if ( is_admin() ) {
		return;
	}
	$desc  = sc_seo_description();
	$img   = sc_seo_image();
	$canon = sc_seo_canonical();
	$title = wp_get_document_title();
	$type  = ( is_singular() && ! is_front_page() ) ? 'article' : 'website';

	echo "\n<!-- Sound Creations SEO -->\n";
	if ( $desc ) {
		echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
	}
	if ( $canon && ! is_singular() ) {
		echo '<link rel="canonical" href="' . esc_url( $canon ) . '">' . "\n";
	}
	echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '">' . "\n";
	echo '<meta property="og:type" content="' . esc_attr( $type ) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
	if ( $desc ) {
		echo '<meta property="og:description" content="' . esc_attr( $desc ) . '">' . "\n";
	}
	if ( $canon ) {
		echo '<meta property="og:url" content="' . esc_url( $canon ) . '">' . "\n";
	}
	if ( $img ) {
		echo '<meta property="og:image" content="' . esc_url( $img ) . '">' . "\n";
	}
	echo '<meta name="twitter:card" content="' . ( $img ? 'summary_large_image' : 'summary' ) . '">' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '">' . "\n";
	if ( $desc ) {
		echo '<meta name="twitter:description" content="' . esc_attr( $desc ) . '">' . "\n";
	}
	if ( $img ) {
		echo '<meta name="twitter:image" content="' . esc_url( $img ) . '">' . "\n";
	}
	// Structured data now lives in includes/seo-graph.php as a single
	// connected @graph. See that file's header for why the previous
	// multi-block approach was replaced.
	if ( function_exists( 'sc_seo_graph' ) ) {
		sc_seo_graph();
	}
}

// Per-post SEO fields.
add_action(
	'add_meta_boxes',
	function () {
		foreach ( array( 'page', 'post', 'sc_product', 'sc_brand', 'sc_project', 'sc_solution', 'sc_case_study', 'sc_resource' ) as $pt ) {
			add_meta_box( 'sc_seo', 'SEO', 'sc_seo_meta_box', $pt, 'normal', 'default' );
		}
	}
);

function sc_seo_meta_box( $post ) {
	wp_nonce_field( 'sc_seo_save', 'sc_seo_nonce' );
	$t = get_post_meta( $post->ID, '_sc_seo_title', true );
	$d = get_post_meta( $post->ID, '_sc_seo_desc', true );
	echo '<p><label style="font-weight:600;display:block;margin-bottom:.25rem;">SEO title (optional)</label>';
	echo '<input type="text" id="sc_seo_title" name="sc_seo_title" value="' . esc_attr( $t ) . '" style="width:100%;" maxlength="70"></p>';
	echo '<p><label style="font-weight:600;display:block;margin-bottom:.25rem;">Meta description (optional)</label>';
	echo '<textarea id="sc_seo_desc" name="sc_seo_desc" rows="3" style="width:100%;" maxlength="200">' . esc_textarea( $d ) . '</textarea>';
	echo '<span class="description">Around 150-160 characters. Falls back to the summary or excerpt when blank.</span></p>';
	$sc_seo_host = (string) wp_parse_url( home_url(), PHP_URL_HOST );
	echo '<div class="sc-seo-preview" hidden'
		. ' data-fallback-title="' . esc_attr( get_the_title( $post ) ) . '"'
		. ' data-fallback-desc="' . esc_attr( sc_core_get( 'tagline', get_bloginfo( 'description' ) ) ) . '"'
		. ' data-site="' . esc_attr( get_bloginfo( 'name' ) ) . '"'
		. ' data-url="' . esc_url( get_permalink( $post ) ) . '"'
		. ' data-host="' . esc_attr( $sc_seo_host ) . '"></div>';
	echo <<<'SCSEO'
<div class="sc-seo-fx">
  <div class="sc-seo-counts">
    <span>Title <b data-c-title>0</b> / 60</span>
    <span>Description <b data-c-desc>0</b> / 160</span>
  </div>
  <div class="sc-seo-help">These override the automatic title and description for this page in Google and social shares. Leave blank to use the page defaults shown below.</div>
  <div class="sc-seo-plabel">Google result preview</div>
  <div class="sc-seo-google">
    <div class="sc-seo-google__url" data-g-url></div>
    <div class="sc-seo-google__title" data-g-title></div>
    <div class="sc-seo-google__desc" data-g-desc></div>
  </div>
  <div class="sc-seo-plabel">Social share preview</div>
  <div class="sc-seo-social">
    <div class="sc-seo-social__meta">
      <div class="sc-seo-social__host" data-s-host></div>
      <div class="sc-seo-social__title" data-s-title></div>
      <div class="sc-seo-social__desc" data-s-desc></div>
    </div>
  </div>
</div>
<style>
.sc-seo-fx { margin-top: 14px; }
.sc-seo-counts { display: flex; gap: 18px; font-size: 12px; color: #555; margin-bottom: 10px; }
.sc-seo-counts b { color: #1a1a1a; }
.sc-seo-counts .sc-seo-over { color: #d63638; }
.sc-seo-help { font-size: 12px; color: #787c82; margin-bottom: 12px; max-width: 640px; }
.sc-seo-plabel { font-size: 11px; text-transform: uppercase; letter-spacing: .04em; color: #787c82; margin: 12px 0 6px; font-weight: 600; }
.sc-seo-google { border: 1px solid #dadce0; border-radius: 8px; padding: 12px 14px; background: #fff; font-family: arial, sans-serif; max-width: 640px; }
.sc-seo-google__url { color: #202124; font-size: 12px; line-height: 1.3; word-break: break-all; }
.sc-seo-google__title { color: #1a0dab; font-size: 18px; line-height: 1.3; margin: 3px 0; }
.sc-seo-google__desc { color: #4d5156; font-size: 13px; line-height: 1.45; }
.sc-seo-social { border: 1px solid #dadce0; border-radius: 8px; overflow: hidden; background: #fff; max-width: 520px; }
.sc-seo-social__meta { padding: 10px 12px; }
.sc-seo-social__host { color: #606770; font-size: 11px; text-transform: uppercase; letter-spacing: .02em; }
.sc-seo-social__title { color: #1d2129; font-size: 15px; font-weight: 600; line-height: 1.3; margin: 3px 0; }
.sc-seo-social__desc { color: #606770; font-size: 13px; line-height: 1.4; }
</style>
<script>
( function () {
  var root = document.querySelector( '.sc-seo-preview' );
  if ( root === null ) { return; }
  var d = root.dataset;
  var ti = document.getElementById( 'sc_seo_title' );
  var de = document.getElementById( 'sc_seo_desc' );
  function q( sel ) { return document.querySelector( sel ); }
  function v( el ) { return ( el === null ) ? '' : String( el.value || '' ); }
  function clip( str, n ) { return ( str.length > n ) ? ( str.slice( 0, n - 1 ) + '...' ) : str; }
  function paint() {
    var t = v( ti ).trim();
    var m = v( de ).trim();
    var et = ( t.length === 0 ) ? ( d.fallbackTitle || '' ) : t;
    var em = ( m.length === 0 ) ? ( d.fallbackDesc || '' ) : m;
    var ct = q( '[data-c-title]' );
    var cm = q( '[data-c-desc]' );
    ct.textContent = String( t.length );
    cm.textContent = String( m.length );
    ct.className = ( t.length > 60 ) ? 'sc-seo-over' : '';
    cm.className = ( m.length > 160 ) ? 'sc-seo-over' : '';
    q( '[data-g-url]' ).textContent = d.url || '';
    q( '[data-g-title]' ).textContent = clip( et, 60 );
    q( '[data-g-desc]' ).textContent = clip( em, 160 );
    q( '[data-s-host]' ).textContent = ( d.host || '' ).toUpperCase();
    q( '[data-s-title]' ).textContent = clip( et, 70 );
    q( '[data-s-desc]' ).textContent = clip( em, 160 );
  }
  if ( ti ) { ti.addEventListener( 'input', paint ); }
  if ( de ) { de.addEventListener( 'input', paint ); }
  paint();
} )();
</script>
SCSEO;

}

add_action(
	'save_post',
	function ( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! isset( $_POST['sc_seo_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sc_seo_nonce'] ) ), 'sc_seo_save' ) ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		$t = isset( $_POST['sc_seo_title'] ) ? sanitize_text_field( wp_unslash( $_POST['sc_seo_title'] ) ) : '';
		$d = isset( $_POST['sc_seo_desc'] ) ? sanitize_textarea_field( wp_unslash( $_POST['sc_seo_desc'] ) ) : '';
		update_post_meta( $post_id, '_sc_seo_title', $t );
		update_post_meta( $post_id, '_sc_seo_desc', $d );
	}
);

add_filter(
	'document_title_parts',
	function ( $parts ) {
		if ( is_singular() ) {
			$t = get_post_meta( get_queried_object_id(), '_sc_seo_title', true );
			if ( $t ) {
				$parts['title'] = $t;
			}
		}
		return $parts;
	}
);

/* ============================================================
   Thin auto-generated archives.

   A crawl of all 62 indexed URLs on 22 Sep 2026 found the taxonomy
   archives carrying 10-37 words of unique content each -- /industry/worship/
   had 10, /location/nairobi-kenya/ 28. They are machine-generated lists, and
   indexing them means they compete in search against the real Solution,
   Service and Project pages they point at.

   noindex,follow is the right pairing, not noindex,nofollow: Google should
   still crawl through them and pass authority on to the destinations, it
   just should not offer the list itself as a search result.

   Paged results (/page/2/ and beyond) are excluded for the same reason --
   page 2 of a list is never the best landing page for a query.
   ============================================================ */
function sc_seo_thin_taxonomies() {
	return array( 'sc_industry', 'sc_location', 'sc_brand_tax', 'sc_product_category' );
}

function sc_seo_is_thin_archive() {
	if ( is_admin() || is_feed() ) {
		return false;
	}
	if ( is_paged() ) {
		return true;
	}
	if ( is_search() || is_author() || is_date() ) {
		return true;
	}
	if ( is_tax( sc_seo_thin_taxonomies() ) ) {
		return true;
	}
	return false;
}

// Use the wp_robots filter rather than echoing a second robots tag: WordPress
// already prints one (max-image-preview:large), and two robots meta tags on a
// page is ambiguous. This merges into the single existing tag.
add_filter(
	'wp_robots',
	function ( $robots ) {
		if ( sc_seo_is_thin_archive() ) {
			$robots['noindex'] = true;
			$robots['follow']  = true;
			unset( $robots['index'] );
		}
		return $robots;
	}
);

// Keep the same archives out of the XML sitemap. Leaving them listed while
// telling Google not to index them sends contradictory signals.
add_filter(
	'wp_sitemaps_taxonomies',
	function ( $taxonomies ) {
		foreach ( sc_seo_thin_taxonomies() as $tax ) {
			unset( $taxonomies[ $tax ] );
		}
		return $taxonomies;
	}
);
