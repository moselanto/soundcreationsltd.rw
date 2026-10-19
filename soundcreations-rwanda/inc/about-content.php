<?php
/**
 * About page: render the page's own editor / Elementor content.
 *
 * page-about.php (parent theme) builds the About layout from Settings and never
 * calls the_content(), so text added to the About page in Elementor or the
 * block editor was saved but never shown, and Elementor reported "the content
 * area was not found". This prints the page content in a section after
 * "Our Brands" via the parent's sc_about_after_brands hook.
 *
 * A static guard stops recursion when the About design is itself placed on a
 * page through the "SC Page Design" Elementor widget.
 *
 * @package SoundCreationsRwanda
 */

if ( defined( 'ABSPATH' ) === false ) {
	exit;
}

add_action( 'sc_about_after_brands', 'scrw_about_page_content', 10 );

/**
 * Output the queried About page's content inside the main loop.
 */
function scrw_about_page_content() {
	static $scrw_running = false;
	if ( $scrw_running || in_the_loop() || ! is_page() ) {
		return;
	}
	$scrw_running = true;
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			ob_start();
			the_content();
			$scrw_html = ob_get_clean();
			if ( '' !== trim( wp_strip_all_tags( $scrw_html, true ) ) || false !== strpos( $scrw_html, 'elementor' ) ) {
				echo '<section class="sc-section sc-section--tight scrw-about-content" id="about-content"><div class="sc-container"><div class="sc-prose">';
				echo scrw_about_enhance( $scrw_html ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the_content output, restructured only.
				echo '</div></div></section>';
			}
		}
		rewind_posts();
	}
	$scrw_running = false;
}

/**
 * 2.3.15: present the About page text as a designed layout instead of one long
 * column. Only the HTML structure is changed, never the words:
 * - text before the first H2 becomes an intro lead;
 * - each H2 and what follows it becomes a block;
 * - two or more H3 sub-sections in a block become a grid of cards;
 * - the last block, when it links to Contact / Request a quote, becomes a call-to-action panel;
 * - links to other websites get a clear accessible name (PageSpeed "identical links").
 * On any parsing problem the original HTML is returned unchanged.
 * Off switch: add_filter( 'scrw_about_enhance', '__return_false' ).
 *
 * @param string $html the_content output.
 * @return string
 */
function scrw_about_enhance( $html ) {
	if ( ! apply_filters( 'scrw_about_enhance', true ) || ! class_exists( 'DOMDocument' ) || '' === trim( (string) $html ) ) {
		return $html;
	}
	try {
		$prev = libxml_use_internal_errors( true );
		$doc  = new DOMDocument();
		$ok   = $doc->loadHTML( '<?xml encoding="utf-8" ?><div id="scrw-ab-root">' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
		libxml_clear_errors();
		libxml_use_internal_errors( $prev );
		if ( ! $ok ) {
			return $html;
		}
		$xp   = new DOMXPath( $doc );
		$root = $xp->query( '//div[@id="scrw-ab-root"]' )->item( 0 );
		if ( ! $root ) {
			return $html;
		}

		$home = strtolower( (string) wp_parse_url( home_url(), PHP_URL_HOST ) );
		foreach ( $xp->query( './/a[@href]', $root ) as $a ) {
			$host = strtolower( (string) wp_parse_url( $a->getAttribute( 'href' ), PHP_URL_HOST ) );
			$text = trim( preg_replace( '/\s+/', ' ', $a->textContent ) );
			if ( '' !== $host && $host !== $home && '' !== $text && ! $a->hasAttribute( 'aria-label' ) ) {
				/* translators: %s: link text, e.g. Yamaha. */
				$a->setAttribute( 'aria-label', sprintf( __( '%s official website (external)', 'soundcreations-rwanda' ), $text ) );
			}
		}

		$parents = array();
		foreach ( $xp->query( './/h2', $root ) as $h ) {
			$parents[ spl_object_id( $h->parentNode ) ] = $h->parentNode;
		}
		foreach ( $parents as $parent ) {
			scrw_about_group( $doc, $parent );
		}

		$out = '';
		foreach ( $root->childNodes as $child ) {
			$out .= $doc->saveHTML( $child );
		}
		return '' !== trim( wp_strip_all_tags( $out ) ) ? $out : $html;
	} catch ( Throwable $e ) {
		return $html;
	}
}

/** Wrap the children of one container into intro + H2 blocks. */
function scrw_about_group( DOMDocument $doc, DOMNode $parent ) {
	$kids = array();
	foreach ( $parent->childNodes as $c ) {
		$kids[] = $c;
	}
	if ( ! $kids ) {
		return;
	}
	$intro  = $doc->createElement( 'div' );
	$intro->setAttribute( 'class', 'scrw-ab-intro' );
	$blocks = array();
	$cur    = null;
	foreach ( $kids as $c ) {
		$parent->removeChild( $c );
		if ( $c instanceof DOMElement && 'h2' === strtolower( $c->tagName ) ) {
			$cur = $doc->createElement( 'section' );
			$cur->setAttribute( 'class', 'scrw-ab-block' );
			$blocks[] = $cur;
		}
		if ( $cur ) {
			$cur->appendChild( $c );
		} else {
			$intro->appendChild( $c );
		}
	}
	if ( '' !== trim( $intro->textContent ) ) {
		$parent->appendChild( $intro );
	} else {
		while ( $intro->firstChild ) {
			$parent->appendChild( $intro->firstChild );
		}
	}
	$last = count( $blocks ) - 1;
	foreach ( $blocks as $i => $b ) {
		scrw_about_cards( $doc, $b );
		if ( $i === $last && $last > 0 && preg_match( '#/(contact|request-a-quote|request-a-consultation)/#', (string) $doc->saveHTML( $b ) ) ) {
			$b->setAttribute( 'class', 'scrw-ab-block scrw-ab-block--cta' );
		}
		$parent->appendChild( $b );
	}
}

/** Inside one block: two or more H3 sub-sections become a grid of cards. */
function scrw_about_cards( DOMDocument $doc, DOMElement $block ) {
	$kids = array();
	$h3s  = 0;
	foreach ( $block->childNodes as $c ) {
		$kids[] = $c;
		if ( $c instanceof DOMElement && 'h3' === strtolower( $c->tagName ) ) {
			$h3s++;
		}
	}
	if ( $h3s < 2 ) {
		return;
	}
	foreach ( $kids as $c ) {
		$block->removeChild( $c );
	}
	$grid = null;
	$card = null;
	foreach ( $kids as $c ) {
		$tag = $c instanceof DOMElement ? strtolower( $c->tagName ) : '';
		if ( 'h3' === $tag ) {
			if ( ! $grid ) {
				$grid = $doc->createElement( 'div' );
				$grid->setAttribute( 'class', 'scrw-ab-grid' );
				$block->appendChild( $grid );
			}
			$card = $doc->createElement( 'div' );
			$card->setAttribute( 'class', 'scrw-ab-card' );
			$grid->appendChild( $card );
		}
		if ( $card ) {
			$card->appendChild( $c );
		} else {
			$block->appendChild( $c );
		}
	}
}
