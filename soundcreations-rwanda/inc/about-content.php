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
	if ( $scrw_running || in_the_loop() || \! is_page() ) {
		return;
	}
	$scrw_running = true;
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			ob_start();
			the_content();
			$scrw_html = ob_get_clean();
			if ( '' \!== trim( wp_strip_all_tags( $scrw_html, true ) ) || false \!== strpos( $scrw_html, 'elementor' ) ) {
				echo '<section class="sc-section sc-section--tight scrw-about-content" id="about-content"><div class="sc-container"><div class="sc-prose">';
				echo $scrw_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the_content output.
				echo '</div></div></section>';
			}
		}
		rewind_posts();
	}
	$scrw_running = false;
}
