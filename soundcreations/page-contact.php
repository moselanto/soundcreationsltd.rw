<?php
/**
 * Contact page. Auto-applies to the page with slug "contact". Uses its own Contact Us layout.
 *
 * @package SoundCreations
 */

if ( defined( 'ABSPATH' ) === false ) {
	exit;
}
get_header();
get_template_part( 'template-parts/contact-page' );
get_footer();
